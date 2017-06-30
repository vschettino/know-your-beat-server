<?php

namespace app\controllers;

use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\Usuario';

    public function actionToken(){
      return ['hello there'];
    }


}
