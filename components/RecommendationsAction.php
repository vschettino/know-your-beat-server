<?php

namespace app\components;

use yii\base\Action;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIException;

use app\models\User;
use yii\web\HttpException;


class RecommendationsAction extends Action
{

    private $api = null;

    public function init(){
      $this->api = new SpotifyWebAPI();
      $this->api->setReturnType(SpotifyWebAPI::RETURN_ASSOC);
    }

    public function run()
    {
      $user = \Yii::$app->user->identity;
      $this->api->setAccessToken($user->access_token);

      $aseeds = $this->getArtistsSeeds();
      $tseeds = $this->getTracksSeeds();
      $targets = $this->getTargets();
      $targets['seed_artists'] = $aseeds;
      $targets['seed_tracks'] = $tseeds;


      try{
        return $this->api->getRecommendations($targets)['tracks'];
      }
      catch (SpotifyWebAPIException $e){
        throw new HttpException($e->getCode(),$e->getMessage());
      }


    }

    public function getArtistsSeeds()
    {
      $topArtists = $this->api->getMyTop('artists', ['limit'=>'2'])['items'];
      return array_map(function($artist){return $artist['id'];}, $topArtists);
    }

    public function getTracksSeeds()
    {
      $topTracks = $this->api->getMyTop('tracks', ['limit'=>'3'])['items'];
      return array_map(function($track){return $track['id'];}, $topTracks);
    }

    public function getTargets()
    {
      $statsAction = new TracksStatsAction(null, null, null);
      $stats = $statsAction->run();
      foreach ($stats as $key => $stat) {
        $stats['target_'.$key] = $stat;
        unset($stats[$key]);
      }
      $stats['target_popularity'] = round($stats['target_popularity']);
      return $stats;
    }


    public function getTrackIds($tracks){
      $ids = [];
      foreach($tracks as $track){
        $ids[] = $track['track']['id'];
      }
      return $ids;
    }
}
