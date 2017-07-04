<?php

namespace app\controllers;
use yii\rest\ActiveController;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIException;

use app\models\User;
use yii\filters\auth\HttpBearerAuth;
use yii\web\HttpException;

class RecommendationController extends ActiveController
{

  public $modelClass = 'app\models\User';

    public function behaviors()
    {
      $behaviors = parent::behaviors();
      $behaviors['authenticator'] = [
          'class' => HttpBearerAuth::className(),
      ];
      return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index'] = 'app\components\RecommendationsAction';
        return $actions;
    }
}
