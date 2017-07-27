<?php
/**
 * Created by Skynix Team
 * Date: 25.07.17
 * Time: 13:48
 */

namespace viewModel;

use Yii;
use app\modules\api\components\SortHelper;
use app\components\DataTable;
use app\components\DateUtil;
use app\models\FinancialReport;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\modules\api\components\Api\Processor;

/**
 * Class FinancialReportFetch
 * @see     https://jira-v2.skynix.company/browse/SI-1024
 * @package viewModel
 */
class FinancialReportFetch extends ViewModelAbstract
{
    public function define()
    {
        if (User::hasPermission([User::ROLE_ADMIN, User::ROLE_FIN,])) {
            $order = Yii::$app->request->getQueryParam('order');
            $start = Yii::$app->request->getQueryParam('start') ?: 0;
            $limit = Yii::$app->request->getQueryParam('limit') ?: SortHelper::DEFAULT_LIMIT;

            $query = FinancialReport::find();

            $dataTable = DataTable::getInstance()
                ->setQuery($query)
                ->setLimit($limit)
                ->setStart($start);

            if ($order) {
                foreach ($order as $name => $value) {
                    $dataTable->setOrder(FinancialReport::tableName() . '.' . $name, $value);
                }

            } else {
                $dataTable->setOrder(FinancialReport::tableName() . '.id', 'desc');
            }

            $financialReport = $dataTable->getData();

            if ($financialReport) {
                $financialReport = ArrayHelper::toArray($financialReport, [
                    'app\models\FinancialReport' => [
                        'id',
                        'report_date' => function ($finRep) {
                            return DateUtil::convertDateFromUnix($finRep->report_date);
                        },
                        'balance' => function ($finRep) {
                            return FinancialReport::getBalance($finRep->id);
                        },
                        'currency'=> function ($finRep) {
                            if($finRep->currency){
                                return $finRep->currency;
                            } else {
                                return 0;
                            }
                        },
                        'income' => function ($finRep) {
                            return FinancialReport::sumIncome($finRep->id);
                        },
                        'expenses' => function ($finRep) {
                            return FinancialReport::sumExpenses($finRep->id);
                        },
                        'profit' => function ($finRep) {
                            return FinancialReport::getProfit($finRep->id);
                        },
                        'investments' => function ($finRep) {
                            return FinancialReport::sumInvestments($finRep->id);
                        },

                    ],
                ]);
            } else {
                $financialReport = [];
            }

            $data = [
                'reports' => $financialReport,
                'total_records' => DataTable::getInstance()->getTotal()
            ];

            $this->setData($data);

        } else {
            return $this->addError(Processor::ERROR_PARAM, Yii::t('yii', 'You have no permission for this action'));
        }

    }
}
