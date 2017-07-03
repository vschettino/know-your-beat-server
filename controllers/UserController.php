<?php

namespace app\controllers;

use yii\rest\ActiveController;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
class UserController extends ActiveController
{
    public $modelClass = 'app\models\Usuario';

    public function actionToken(){

    $session = \Yii::$app->spotifySession;

    $api = new SpotifyWebAPI();

    if (isset($_GET['code'])) {
        $session->requestAccessToken($_GET['code']);
        $api->setAccessToken($session->getAccessToken());

        print_r($api->me());
    } else {
        $options = [
            'scope' => [
                'user-read-email',
            ],
        ];

        return [$session->getAuthorizeUrl($options)];
      }
      return ['hello there'];
    }


}
