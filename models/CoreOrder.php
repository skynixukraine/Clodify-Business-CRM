<?php
/**
 * Create By Skynix Team
 * Author: Pristashkin
 * Date: 11/5/18
 * Time: 9:57 PM
 */

namespace app\models;

use app\components\Bootstrap;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $status
 * @property double $amount
 * @property integer $payment_id
 * @property integer $recurrent_id
 * @property string $created
 * @property string $paid
 * @property string $notes
 *
 * @package app\models
 */
class CoreOrder extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'orders';
    }

    public function rules()
    {
        return [
            [['id'], 'number']];
    }



    public static function getDb()
    {
        return Yii::$app->dbCore;
    }


    public function getClient()
    {
        return $this->hasOne(CoreClient::className(), ['id' => 'client_id']);
    }


}