<?php

namespace app\components;

use yii\base\Action;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIException;

use app\models\User;
use yii\web\HttpException;


class TracksStatsAction extends Action
{

    private $api = null;

    public function run()
    {
      $user = \Yii::$app->user->identity;
      $this->api = new SpotifyWebAPI();
      $this->api->setReturnType(SpotifyWebAPI::RETURN_ASSOC);

      $this->api->setAccessToken($user->access_token);
      try{
        $tracks = $this->api->getMyRecentTracks()['items'];
      }
      catch (SpotifyWebAPIException $e){
        throw new HttpException($e->getCode(),$e->getMessage());
      }

      $ids = $this->getTrackIds($tracks);
      $popularity = $this->getPopularityAverage($tracks);
      $stats = $this->setUpStats($ids);
      $stats['popularity'] = $popularity;
      return $stats;

    }


    public function getTrackIds($tracks){
      $ids = [];
      foreach($tracks as $track){
        $ids[] = $track['track']['id'];
      }
      return $ids;
    }


    public function setUpStats($ids){
      $features = $this->api->getAudioFeatures($ids)['audio_features'];
      $stats = [];
      $i = 0;
      foreach($features as $feature){
        $i++;
        $this->incrementStat($stats['danceability'], $feature['danceability']);
        $this->incrementStat($stats['instrumentalness'], $feature['instrumentalness']);
        $this->incrementStat($stats['energy'], $feature['energy']);
        $this->incrementStat($stats['valence'], $feature['valence']);
      }
    return array_map(function($n) use ($i){ return $n/$i;}, $stats);
    }


    public function getPopularityAverage($tracks)
    {
      $popularity = 0;
      $i = 0;
      foreach($tracks as $track){
        $i++;
        $popularity += $track['track']['popularity'];
      }
      return $popularity/$i;
    }

    public function incrementStat(&$stat, $inc){
      $stat = isset($stat) ? $stat += $inc : $stat = $inc;
    }

}
