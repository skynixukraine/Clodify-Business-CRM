<?php

namespace app\modules\cp\controllers;

use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use app\models\User;
use app\components\AccessRule;
use app\components\Language;
use app\modules\api\models\AccessKey;


class DefaultController extends Controller
{

    public function beforeAction( $action )
    {
        if(isset($_COOKIE[User::READ_COOKIE_NAME])) {

            $session = AccessKey::checkCrowdSession($_COOKIE[User::READ_COOKIE_NAME]);

            if(isset($session->reason)){
                Yii::$app->getSession()->setFlash('success',
                    Yii::t("app", $session->reason . " You have to authenticate with email and password"));
                return $this->redirect(["/site/login"]);
            } else {
                Yii::$app->assetManager->bundles['yii\web\JqueryAsset'] = false;
                Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = false;
                Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = false;
                return parent::beforeAction( $action );
            }
        } else {
            Yii::$app->getSession()->setFlash('error',
                Yii::t("app",  "You have to authenticate with email and password"));
            return $this->redirect(["/site/login"]);
        }

    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [ User::ROLE_DEV, User::ROLE_PM, User::ROLE_ADMIN, User::ROLE_SALES, User::ROLE_CLIENT, User::ROLE_FIN ],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (User::hasPermission([User::ROLE_DEV, User::ROLE_PM])) {
            
            return $this->redirect(['index/index']);
            
        } elseif (User::hasPermission([User::ROLE_SALES, User::ROLE_CLIENT, User::ROLE_FIN])) {
           
            return $this->redirect(['report/index']);
            
        } elseif (User::hasPermission([User::ROLE_ADMIN])) {
            
            return $this->redirect(['user/index']);
        }
        return $this->redirect(['index/index']);
    }
}