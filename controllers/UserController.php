<?php

namespace app\controllers;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';


    public function behaviors()
    {
      $behaviors = parent::behaviors();
      $behaviors['authenticator'] = [
          'class' => HttpBearerAuth::className(),
          'except' => ['authorize-url']
      ];
      return $behaviors;
    }

    public function actionAuthorizeUrl(){

      $session = \Yii::$app->spotifySession;
      $api = new SpotifyWebAPI();
      $options = [
          'scope' => [
              'user-read-email',
              'playlist-read-private',
              'playlist-read-collaborative',
              'user-follow-read',
              'user-library-read',
              'user-read-private',
              'user-read-birthdate',
              'user-top-read',
          ],
      ];

      return ['authorize_url' => $session->getAuthorizeUrl($options)];
    }

    public function actionRefreshToken(){
      $session = \Yii::$app->spotifySession;
      $user = \Yii::$app->user->identity;

      $session->refreshAccessToken($user->refresh_token);
      $user->access_token = $session->getAccessToken();
      $user->save();
      return ['access_token' => $session->getAccessToken()];
    }




}
