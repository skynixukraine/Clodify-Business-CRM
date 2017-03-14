<?php
/**
 * Created by Skynix Team
 * Date: 06.03.17
 * Time: 15:34
 */

namespace app\modules\api\controllers;

use app\modules\api\components\Api\Processor;

class UsersController extends DefaultController
{

    public function actionIndex(){

        $this->di
            ->set('yii\db\ActiveRecordInterface', 'app\models\User')
            ->set('viewModel\ViewModelInterface', 'viewModel\UsersFetch')
            ->set('app\modules\api\components\Api\Access', [
                'methods'       => [ Processor::METHOD_GET ],
                'checkAccess'   => true
            ])
            ->get('Processor')
            ->respond();

    }

    public function actionActivate(){

        $this->di
            ->set('yii\db\ActiveRecordInterface', 'app\models\User')
            ->set('viewModel\ViewModelInterface', 'viewModel\UserActivate')
            ->set('app\modules\api\components\Api\Access', [
                'methods'       => [ Processor::METHOD_PUT ],
                'checkAccess'   => true
            ])
            ->get('Processor')
            ->respond();
    }

    public function actionDeactivate(){

        $this->di
            ->set('yii\db\ActiveRecordInterface', 'app\models\User')
            ->set('viewModel\ViewModelInterface', 'viewModel\UserDeactivate')
            ->set('app\modules\api\components\Api\Access', [
                'methods'       => [ Processor::METHOD_PUT ],
                'checkAccess'   => true
            ])
            ->get('Processor')
            ->respond();
    }

    public function actionView(){

        $this->di
            ->set('yii\db\ActiveRecordInterface', 'app\models\User')
            ->set('viewModel\ViewModelInterface', 'viewModel\UserView')
            ->set('app\modules\api\components\Api\Access', [
                'methods'       => [ Processor::METHOD_GET ],
                'checkAccess'   => true
            ])
            ->get('Processor')
            ->respond();
    }

    public function actionDelete(){

        $this->di
            ->set('yii\db\ActiveRecordInterface', 'app\models\User')
            ->set('viewModel\ViewModelInterface', 'viewModel\UserDelete')
            ->set('app\modules\api\components\Api\Access', [
                'methods'       => [ Processor::METHOD_DELETE ],
                'checkAccess'   => true
            ])
            ->get('Processor')
            ->respond();
    }

}