<?php

namespace app\controllers;
use yii\rest\ActiveController;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use app\models\User;
use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;

class OauthController extends Controller
{

    public function actionCallback()
    {
      $session = \Yii::$app->spotifySession;
      $api = new SpotifyWebAPI();
      $api->setReturnType(SpotifyWebAPI::RETURN_ASSOC);

      // Request a access token using the code from Spotify
      $session->requestAccessToken(\Yii::$app->request->get('code'));
      $accessToken = $session->getAccessToken();
      $refreshToken = $session->getRefreshToken();
      // Set the access token on the API wrapper
      $api->setAccessToken($accessToken);
      $user = User::find($api->me()['id'])->one();
      if($user == null){
        $user = new User();
      }
      $user->attributes = ($api->me());
      $user->access_token = $accessToken;
      $user->refresh_token = $refreshToken;
      $user->save();
      $client_callback = \Yii::$app->params['client_callback_uri'].$user->access_token;
      return $this->render('index', ['client_callback'=>$client_callback]);

    }

}
