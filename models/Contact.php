<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Contact is the model behind the contact form.
 * @property string name
 * @property string email
 * @property string subject
 * @property string message
 * @property string verifyCode
 * @property array attachment
 */
class Contact extends ActiveRecord
{
    public $name;
    public $email;
    public $subject;
    public $message;
    public $attachment;

    const SCENARIO_ATTACH_FILES = 'attachment';
    const SCENARIO_CONTACT_FORM = 'contact';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and message are required
            [['name', 'email', 'subject', 'message'], 'required', 'except' => self::SCENARIO_ATTACH_FILES],
            // email has to be a valid email address
            ['email', 'email'],
            // max length for email, message, name and subject
            [['email', 'message'], 'string', 'max' => 150],
            [['name', 'subject'], 'string', 'max' => 45],
            // 10485760 bytes - 10 megabytes
            [['attachment'], 'file', 'maxFiles' => 5, 'maxSize' => 10485760, 'on' => self::SCENARIO_ATTACH_FILES]
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->message)
                ->send();

            return true;
        }
        return false;
    }
}
