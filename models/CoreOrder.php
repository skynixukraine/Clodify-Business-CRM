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
            [['id'], 'number'],
            [['client_id'], function (){

                $coreOrder = CoreOrder::find()->where([
                    'status' => 'PAID',
                    'paid' => date('Y-m-d', strtotime('now-1month')),
                ])->with(['clients' => function($query){
                    $query->andWere(['prepaid_for' => date('Y-m-d', strtotime('now'))]);
                }])->one();

                //print_r($coreOrder);die;

                if(!is_null($coreOrder)){
                    $this->addError('your last order exists and paid, no need to create one');
                }

            }, 'on'=> [self::SCENARIO_CREATE_VALIDATION] ]
        ];
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