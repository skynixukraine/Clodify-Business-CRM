<?php
/**
 * Created by PhpStorm.
 * User: olha
 * Date: 16.02.16
 * Time: 11:55
 */
namespace app\modules\cp\controllers;

use app\models\Report;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\AccessRule;
use app\components\DataTable;
use app\components\DateUtil;
use app\models\Invoice;
use app\models\User;
use app\models\ProjectCustomer;
//use kartik\mpdf\mPDF;
use mPDF;

class InvoiceController extends DefaultController
{
    public $enableCsrfValidation = false;
    public $layout = "admin";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions'   => ['index', 'find', 'create', 'view', 'send', 'paid', 'canceled', 'file'],
                        'allow'     => true,
                        'roles'     => [User::ROLE_ADMIN, User::ROLE_FIN],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index'     => ['get', 'post'],
                    'find'      => ['get', 'post'],
                    'create'    => ['get', 'post'],
                    'view'      => ['get', 'post'],
                    'send'      => ['get', 'post'],
                    'paid'      => ['get', 'post'],
                    'canceled'  => ['get', 'post'],
                    'file'   => ['get', 'post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /** Value table (Invoices in index) fields, filters, search */
    public function actionFind()
    {

        $order          = Yii::$app->request->getQueryParam("order");
        $search         = Yii::$app->request->getQueryParam("search");
        $keyword        = ( !empty($search['value']) ? $search['value'] : null);
        $query          = Invoice::find();

        $columns        = [
            'id',
            'first_name',
            'subtotal',
            'discount',
            'total',
            'date_start',
            'date_end',
            'date_created',
            'date_sent',
            'date_paid',
            'status'
        ];

        $dataTable = DataTable::getInstance()
            ->setQuery( $query )
            ->setLimit( Yii::$app->request->getQueryParam("length") )
            ->setStart( Yii::$app->request->getQueryParam("start") )
            ->setSearchValue( $keyword ) //$search['value']
            ->setSearchParams([ 'or',
                ['like', 'status', $keyword],
            ]);


        $dataTable->setOrder( $columns[$order[0]['column']], $order[0]['dir']);
       // $dataTable->setFilter('is_delete=0');

        $activeRecordsData = $dataTable->getData();
        $list = [];

            /* @var $model \app\models\Invoice */
        foreach ( $activeRecordsData as $model ) {

            $list[] = [
                $model->id,
                $model->getUser()->one()->first_name,
                $model->subtotal,
                $model->discount,
                $model->total,
                $model->date_start,
                $model->date_end,
                $model->date_created,
                $model->date_sent,
                $model->date_paid,
                $model->status
            ];
        }

        $data = [
            "draw"              => DataTable::getInstance()->getDraw(),
            "recordsTotal"      => DataTable::getInstance()->getTotal(),
            "recordsFiltered"   => DataTable::getInstance()->getTotal(),
            "data" => $list
        ];
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->content = json_encode($data);
        Yii::$app->end();

    }

    public function actionCreate()
    {
        $model = new Invoice();

        if($model->load(Yii::$app->request->post())){

            if($model->validate()){

                $model->save();
                Yii::$app->getSession()->setFlash('success', Yii::t("app", "You created new invoices " . $model->id));
            }
            return $this->redirect('index');
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionView()
    {
        if (( $id = Yii::$app->request->get("id") ) ) {

            $model = Invoice::find()
                ->where("id=:iD",
                    [
                        ':iD' => $id
                    ])
                ->one();
        }
        /** @var $model Invoice */
        return $this->render('view', ['model' => $model,
                                      'title' => 'You watch invoice #' . $model->id]);
    }

    public function actionSend()
    {
        if (( $id = Yii::$app->request->get("id") ) ) {

            $model = Invoice::find()
                ->where("id=:iD",
                    [
                        ':iD' => $id
                    ])
                ->one();
        }
        /** @var $model Invoice */
        if( $model->status == Invoice::STATUS_NEW && $model->date_sent == null) {

            Yii::$app->mailer->compose('invoice',
                [
                    'id'            => $model->id,
                    'nameCustomer'  => $model->getUser()->one()->first_name . $model->getUser()->one()->last_name,
                    'emailCustomer' => $model->getUser()->one()->email,
                    'date_start'    => $model->date_start,
                    'date_end'      => $model->date_end,
                    'totalHours'    => $model->total_hours,
                    'subtotal'      => $model->subtotal,
                    'discount'      => $model->discount,
                    'total'         => $model->total,
                    'note'          => $model->note,
                    'date_created'  => $model->date_created,
                    'date_sent'     => $model->date_sent,
                    'date_paid'     => $model->date_paid,
                    'status'        => $model->status,
                ])
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($model->getUser()->one()->email)
                ->setCc(Yii::$app->params['adminEmail'])
                ->setSubject('Invoice #' . $model->id)
                ->send();

            $connection = Yii::$app->db;
            $connection->createCommand()
                ->update(Invoice::tableName(), [

                    'date_sent' => date('Y-m-d'),

                ], 'id=:Id',
                    [
                        ':Id'    => $model->id,
                    ])
                ->execute();
        }
        Yii::$app->getSession()->setFlash('success', Yii::t("app", "You sent information about invoice # " . $model->id));
        return $this->redirect(['invoice/index']);
    }

    public function actionPaid()
    {
        if (( $id = Yii::$app->request->get("id") ) ) {

            $model = Invoice::find()
                ->where("id=:iD",
                    [
                        ':iD' => $id
                    ])
                ->one();
            $model->status = Invoice::STATUS_PAID;
            $model->date_paid = date('Y-m-d');
            $model->save(true, ['status', 'date_paid']);
            Yii::$app->getSession()->setFlash('success', Yii::t("app", "You paid invoice " . $id));
            return $this->redirect(['invoice/index']);
        }
    }

    public function actionCanceled()
    {
        if (( $id = Yii::$app->request->get("id") ) ) {

            $model = Invoice::find()
                ->where("id=:iD",
                    [
                        ':iD' => $id
                    ])
                ->one();
            $model->status = Invoice::STATUS_CANCELED;
            $model->save(true, ['status']);
            Yii::$app->getSession()->setFlash('success', Yii::t("app", "You canceled invoice " . $id));
            return $this->redirect(['invoice/index']);
        }
    }

    public function actionFile()
    {
        $model = Invoice::find()
            ->where("id=:iD",
                [
                    ':iD' => 4
                ])
            ->one();
        /** @var  $model Invoice */
        $content = $this->renderPartial('test', [
            'id'            => $model->id,
            'nameCustomer'  => $model->getUser()->one()->first_name . ' ' . $model->getUser()->one()->last_name,
            'emailCustomer' => $model->getUser()->one()->email,
            'date_start'    => $model->date_start,
            'date_end'      => $model->date_end,
            'totalHours'    => $model->total_hours,
            'subtotal'      => $model->subtotal,
            'discount'      => $model->discount,
            'total'         => $model->total,
            'note'          => $model->note,
            'date_created'  => $model->date_created,
            'date_sent'     => $model->date_sent,
            'date_paid'     => $model->date_paid,
            'status'        => $model->status,
        ]);
        $stylesheet = $this->renderPartial('style.css');

        $pdf =  new mPDF();
        $pdf->showImageErrors = true;
        $pdf->SetHeader('Document Title|Center Text|{PAGENO}');
        $pdf->SetFooter('Document Footer|Center Text|{PAGENO}');
        $pdf->WriteHTML($stylesheet,1);
        $pdf->WriteHTML($content);
        $pdf->current_filename = 'TestPdfFile';
        $pdf->AddPage();

        return $pdf->Output();

        /*$pdf =  new Pdf([
            'mode'      => Pdf::MODE_UTF8,
            'format'    => Pdf::FORMAT_A4,
            'content'   => $content,
            'methods'   => [
                'SetHeader' => ['Document Title|Center Text|{PAGENO}'],
                'SetFooter' => ['Document Footer|Center Text|{PAGENO}'],
            ],
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'filename'    => 'TestPdfFile',

        ]);
        return $pdf->Output();*/
    }


}