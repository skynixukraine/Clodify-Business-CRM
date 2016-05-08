<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "surveys".
 *
 * @property integer $id
 * @property string $shortcode
 * @property string $question
 * @property string $description
 * @property string $date_start
 * @property string $date_end
 * @property integer $is_private
 * @property integer $user_id
 * @property integer $total_votes
 * @property integer $is_delete
 */
class Surveys extends \yii\db\ActiveRecord
{
    public $result;
    public $model;
    public $name;
    public $descriptions;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'surveys';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['shortcode', 'question', /*'date_start', 'date_end'*/ ], 'required'],
            [['shortcode'], 'unique', 'message' => 'Sorry, the entered shortcode already exists'],
            [['name'],'string', 'max' => 250],
            [['descriptions'], 'string', 'max' => 1200],
            [['date_start', 'date_end'], 'safe'],
            [['is_private', 'user_id', 'total_votes', 'is_delete'], 'integer'],
            [['shortcode'], 'string', 'max' => 25],
            [['question'], 'string', 'max' => 250]
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveys()
    {
        return $this->hasMany(SurveysOption::className(), ['survey_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shortcode' => 'Shortcode',
            'question' => 'Question',
            'description' => 'Description',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'is_private' => 'Is Private',
            'user_id' => 'User ID',
            'total_votes' => 'Total Votes',
            'is_delete'    => 'Is Delete'
        ];
    }



}
