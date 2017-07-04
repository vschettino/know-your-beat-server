<?php

namespace app\components;

use yii\base\Action;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIException;

use app\models\User;
use yii\web\HttpException;


class ArtistsStatsAction extends Action
{

    private $api = null;

    public function run()
    {
      $user = \Yii::$app->user->identity;
      $this->api = new SpotifyWebAPI();
      $this->api->setReturnType(SpotifyWebAPI::RETURN_ASSOC);

      $this->api->setAccessToken($user->access_token);
      try{
        $artists = $this->api->getMyTop('artists')['items'];
      }
      catch (SpotifyWebAPIException $e){
        throw new HttpException($e->getCode(),$e->getMessage());
      }

      $popularity = $this->getPopularityAverage($artists);
      $mostPopular = $this->getMostPopular($artists);
      return ['popularity_avg'=>$popularity, 'most_popular'=>$mostPopular];

    }


    public function getPopularityAverage($artists)
    {
      $popularity = 0;
      $i = 0;
      foreach($artists as $artist){
        $i++;
        $popularity += $artist['popularity'];
      }
      return $popularity/$i;
    }

    public function getMostPopular($artists)
    {
      usort($artists, function($a,$b){
        return ($a['popularity'] <=> $b['popularity']);
      });
      return end($artists);
    }

}
