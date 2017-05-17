<?php
/**
 * Created by Skynix Team
 * Date: 20.04.17
 * Time: 11:22
 */

namespace viewModel;

use Yii;
use app\models\Report;
use app\models\User;
use app\models\Contract;
use app\models\Invoice;
use app\components\DateUtil;
use app\components\DataTable;
use app\modules\api\components\SortHelper;

class ContractsFetch extends ViewModelAbstract
{
    public function define()
    {
        $customerId     = Yii::$app->request->getQueryParam("customers");
        $order          = Yii::$app->request->getQueryParam("order");
        $search         = Yii::$app->request->getQueryParam("search_query");
        $start          = Yii::$app->request->getQueryParam('start') ?: 0;
        $keyword        = (!empty($search['value']) ? $search['value'] : null);
        $limit          = Yii::$app->request->getQueryParam('limit') ?: SortHelper::DEFAULT_LIMIT;

        $query = Contract::find()
            ->groupBy('id');
        $dataTable = DataTable::getInstance()
            ->setQuery( $query )
            ->setLimit( $limit )
            ->setStart( $start )
            ->setSearchValue( $keyword ) //$search['value']
            ->setSearchParams([ 'or',
                ['like', 'contract_id', $keyword],
                ['like', 'created_by', $keyword],
                ['like', 'customer_id', $keyword],
                ['like', 'act_number', $keyword]
            ]);
        // DateUtil::convertData() returns incoming param only if it does not match to 01/01/2017 format
        if (!empty($keyword) && ($date = DateUtil::convertData($keyword)) !== $keyword ) {
            $dataTable->setSearchParams([ 'or',
                ['like', 'start_date', $date],
                ['like', 'end_date', $date],
                ['like', 'act_date', $date],
            ]);
        }

        if ($customerId && $customerId != null){

            $dataTable->setFilter('customer_id=' . $customerId);
        }

        if ($order) {
            foreach ($order as $name => $value) {
                $dataTable->setOrder(Contract::tableName() . '.' . $name, $value);
            }

        } else {
            $dataTable->setOrder( Contract::tableName() . '.id', 'asc');
        }

        if (User::hasPermission([User::ROLE_SALES])) {
            $dataTable->setFilter(Contract::tableName() . '.created_by=' . Yii::$app->user->id);
        }
        if (User::hasPermission([User::ROLE_CLIENT])) {
            $dataTable->setFilter((Contract::tableName() . '.customer_id=' . Yii::$app->user->id));
        }

        $activeRecordsData = $dataTable->getData();
        $list = [];

        /* @var $model Contract*/
        foreach ($activeRecordsData as $model) {
            $total_hours = 0;
            $expenses = 'Unknown';
            $user = null;
            $createdByCurrentUser = null;
            $canInvoice = null;
            $initiator = User::findOne($model->created_by);
            $customer = User::findOne($model->customer_id);
            if ($model->hasInvoices() && ($invoice = Invoice::findOne(['contract_id' => $model->id, 'is_delete' => 0]))
                && $invoice->status != Invoice::STATUS_CANCELED ) {
                $total_hours = Yii::$app->Helper->timeLength( $invoice->total_hours * 3600);
                $expenses = '$' . (Report::getReportsCostOnInvoice($invoice->id)
                        ? Report::getReportsCostOnInvoice($invoice->id) : 0);
            }

            $dataList = [
                'id' => $model->id,
                'contract_id' => $model->contract_id,
                'created_by' => [
                    'id' => $initiator->id,
                    'name' => $initiator->first_name . ' ' . $initiator->last_name
                ],
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->first_name . ' ' . $customer->last_name
                ],
                'act_number' => $model->act_number,
                'start_date' => date("d/m/Y", strtotime($model->start_date)),
                'end_date' => date("d/m/Y", strtotime($model->end_date)),
                'act_date' => date("d/m/Y", strtotime($model->act_date)),
                'total' => '$' . number_format($model->total, 2),
                'total_hours' => $total_hours
            ];

            if (!User::hasPermission([User::ROLE_CLIENT])) {
                $dataList['expenses'] = $expenses;
            }

            $list[] = $dataList;
        }

        $data = [
            "contracts" => $list,
            "total_records" => DataTable::getInstance()->getTotal(),
        ];
        $this->setData($data);
    }

}