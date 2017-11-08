<?php
/**
 * Created by SkynixTeam.
 * User: igor
 * Date: 07.11.17
 * Time: 9:11
 */
namespace app\modules\api\controllers;

use app\modules\api\components\Api\Processor;

class CounterpartiesController extends DefaultController
{
    public function actionCreate()
    {
        $this->di
            ->set('yii\db\ActiveRecordInterface', 'app\models\Counterparty')
            ->set('viewModel\ViewModelInterface', 'viewModel\CounterpartyCreate')
            ->set('app\modules\api\components\Api\Access', [
                'methods' => [Processor::METHOD_POST],
                'checkAccess' => true
            ])
            ->get('Processor')
            ->respond();
    }
}