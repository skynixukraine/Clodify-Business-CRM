<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\ProjectCustomer;
use app\models\PaymentMethod;
use app\components\DateUtil;

/**
 * This is the model class for table "invoices".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $note
 * @property string $subtotal
 * @property string $discount
 * @property string $total
 * @property string $currency
 * @property string $date_start
 * @property string $date_end
 * @property string $date_created
 * @property string $date_paid
 * @property string $date_sent
 * @property string $status
 * @property string $total_hours
 * @property integer $contract_number
 * @property integer $act_of_work
 * @property integer $contract_id
 * @property integer $project_id
 * @property integer $created_by
 * @property integer $payment_method_id
 * @property integer $invoice_id

 *
 * @property Report[] $reports
 */
class Invoice extends \yii\db\ActiveRecord
{
    const STATUS_NEW        = "NEW";
    const STATUS_CANCELED   = "CANCELED";
    const STATUS_PAID       = "PAID";

    const INVOICE_DELETED       = 1;
    const INVOICE_NOT_DELETED   = 0;

    const SCENARIO_INVOICE_CREATE = 'api-invoice-create';

    public $method;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['user_id', 'required'],
            [['date_end', 'total', 'user_id', 'date_start', 'subtotal', 'discount', 'note', 'currency'], 'required'],
            [['payment_method_id'], 'required',
                'on' => [self::SCENARIO_INVOICE_CREATE]],
            [['id', 'user_id', 'contract_number', 'act_of_work', 'project_id', 'contract_id', 'created_by', 'payment_method_id'], 'integer'],
            [['subtotal', 'total', 'discount'], 'number'],
            [['total_hours'], 'double'],
            [['date_start', 'date_end', 'date_created', 'date_paid', 'date_sent', 'method'], 'safe'],
            [['status', 'note', 'currency'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'project_id' => 'Project ID',
            'note' => 'Notes',
            'discount' => 'Discount',
            'subtotal' => 'Subtotal',
            'total' => 'Total',
            'contract_id' => 'Contract',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'date_created' => 'Date Created',
            'date_paid' => 'Date Paid',
            'date_sent' => 'Date Sent',
            'status' => 'Status',
            'total_hours' => 'Total Hours',
            'contract_number' => 'Contract Number',
            'act_of_work' => 'Act of Work',
            'payment_method_id' => 'Payment Method Id'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::className(), ['invoice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getContract()
    {
        return $this->hasOne(Contract::className(), ['id' => 'contract_id']);
    }

    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), [ 'id' => 'payment_method_id']);
    }

    public function beforeSave($insert)
    {
        if( $this->isNewRecord ) {

            $this->date_start   = DateUtil::convertData($this->date_start);
            $this->date_end     = DateUtil::convertData($this->date_end);
            $this->status       = Invoice::STATUS_NEW;

            $paymentMethod = PaymentMethod::findOne(['id' => $this->payment_method_id]);

            if($paymentMethod) {
                $businesses = Business::find()->where('id=' . $paymentMethod->business_id)->all();
                if(count($businesses) > 0) {
                    foreach ($businesses as $business){
                        //print_r($business);die;
                        if(!isset($business->invoice_increment_id)){
                            continue;
                        }
                        $business->invoice_increment_id = $business->invoice_increment_id + 1;

                        if(!$business->save()){
                            return false;
                            //print_r($business->getErrors());die;
                                //$this->addError() = $business->getErrors();
                                //print_r();die;
                        }


                        $this->invoice_id = $business->invoice_increment_id;
                    }

                }
            }

            /** @var $business Business */
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {

        if ($projects = $this->project_id) {

        } else {
            $projectsCustomer = ProjectCustomer::getReportsOfCustomer($this->user_id);
            $projectId = [];
            foreach ($projectsCustomer as $project) {

                $projectId[] = $project->project_id;

            }
            if ($projectId && $projectId != null) {

                $projects = implode(', ', $projectId);

            } else {

                $projects = 'null';
            }
        }

        $connection = Yii::$app->db;

        $connection->createCommand()
            ->update(Report::tableName(), [

                'invoice_id' => $this->invoice_id,
                'status' => Report::STATUS_INVOICED,

            ], 'project_id IN (' . $projects . ') AND date_report BETWEEN :start AND :end AND is_delete=0',
                [
                    ':start' => DateUtil::convertData($this->date_start),
                    ':end' => DateUtil::convertData($this->date_end),
                ])
            ->execute();

        if (!$insert) {
            if ($this->project_id) {
                $project = Project::findOne($this->project_id);
                $invoices = Invoice::find()->where(['project_id' => $this->project_id])
                    ->andWhere(['status' => self::STATUS_PAID])->all();
                $totalPaid = 0;
                if ($invoices) {
                    foreach ($invoices as $invoice) {
                        $totalPaid += $invoice->total_hours;
                    }
                    if ($totalPaid) {
                        $project->total_paid_hours = $totalPaid;
                        $project->save();
                    }
                }
            } else {  // if $this->project_id == null, there must be not NULL value of $this->user_id
                $projects = Project::ProjectsCurrentClient($this->user_id);
                foreach ($projects as $project) {
                    $totalHoursReport = Report::find()
                        ->andWhere([Report::tableName() . '.invoice_id' => $this->id])
                        ->andWhere([Report::tableName() . '.project_id' => $project->id])
                        ->andWhere([Report::tableName() . '.is_delete' => Report::ACTIVE])->sum('hours');
                    if ($totalHoursReport) {
                        $project->total_paid_hours = $totalHoursReport;
                        $project->save();
                    }
                }
            }
        }

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    /** find max date_end in table invoices to some project */
    public static function getInvoiceWithDateEnd($project_id)
    {
        return self::find()
            ->leftJoin(ProjectCustomer::tableName(), ProjectCustomer::tableName() . '.user_id=' .
                Invoice::tableName() .'.user_id')
            ->where(ProjectCustomer::tableName() . '.project_id=:projectID AND ' . Invoice::tableName() . '.is_delete=0',
                [':projectID'=>$project_id])
            ->max(Invoice::tableName() . '.date_end');
    }

    public static function report ( $user_id, $date_start, $date_end )
    {
        $projectsCustomer = ProjectCustomer::getReportsOfCustomer($user_id);
        $projectId = [];
        foreach($projectsCustomer as $project){

            $projectId[] = $project->project_id;

        }
        if($projectId && $projectId != null) {

            $projects =  implode(', ', $projectId);

        }else{

            $projects = 'null';
        }
       // var_dump($projects);exit();
        $result = Report::find()
                ->where(Report::tableName() . '.project_id IN ('. $projects .') AND date_report BETWEEN :start AND :end AND is_delete=0',
                    [
                        ':start'    => DateUtil::convertData($date_start),
                        ':end'      => DateUtil::convertData($date_end),
                    ])
            ->orderBy(Report::tableName() . '.date_report asc')
                ->all();
        //var_dump($result);exit();
        return $result;

        /*$connection = Report::find();

        $connection->createCommand()
            ->update(Report::tableName(), [

                'invoice_id' => $this->id,
                'status' => Report::STATUS_INVOICED,

            ], 'project_id IN ('. $projects .') AND date_report BETWEEN :start AND :end AND is_delete=0',
                [
                    ':start'    => DateUtil::convertData($this->date_start),
                    ':end'      => DateUtil::convertData($this->date_end),
                ])
            ->execute();*/

        parent::reportSave($insert, $changedAttributes, $reportId); // TODO: Change the autogenerated stub
    }

}
