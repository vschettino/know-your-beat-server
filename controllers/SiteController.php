<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\HttpException;
class SiteController extends ActiveController
{
  public $modelClass = 'app\models\User';

    public function actionError()
    {
      if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
          return $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
      }
      return $exception;
     }
}
