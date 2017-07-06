<?php

namespace app\controllers;
use yii\rest\ActiveController;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIException;

use app\models\User;
use yii\filters\auth\HttpBearerAuth;
use yii\web\HttpException;

class TrackController extends ActiveController
{

  public $modelClass = 'app\models\User';

    public function behaviors()
    {
      $behaviors = parent::behaviors();
      $behaviors['authenticator'] = [
          'class' => HttpBearerAuth::className(),
          'except' => ['authorize-url','options']
      ];
      return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['view']);
        $actions['stats'] = 'app\components\TracksStatsAction';
        return $actions;
    }

    public function actionIndex()
    {
      $session = \Yii::$app->spotifySession;
      $user = \Yii::$app->user->identity;
      $api = new SpotifyWebAPI();
      $api->setReturnType(SpotifyWebAPI::RETURN_ASSOC);

      $api->setAccessToken($user->access_token);
      try{
        return $api->getMyRecentTracks();
      }
      catch (SpotifyWebAPIException $e){
        throw new HttpException($e->getCode(),$e->getMessage());
      }

    }

    public function actionView($id){
        //27WqFiOFm2u1hax2ksdyqK
        $session = \Yii::$app->spotifySession;
        $user = \Yii::$app->user->identity;
        $api = new SpotifyWebAPI();
        $api->setReturnType(SpotifyWebAPI::RETURN_ASSOC);

        $api->setAccessToken($user->access_token);
        try{
          return $api->getAudioFeatures($id);
        }
        catch (SpotifyWebAPIException $e){
          throw new HttpException($e->getCode(),$e->getMessage());
        }
    }



}
