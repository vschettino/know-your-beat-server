<?php

namespace app\models;

class User extends \yii\redis\ActiveRecord implements \yii\web\IdentityInterface
{
    public function attributes()
    {
        return ['id', 'email','display_name','product','uri','birthdate','access_token','refresh_token'];
    }

    public function rules()
    {
        return [
            ['email', 'required'],
            [['id', 'email','display_name','product','uri','birthdate','access_token','refresh_token'], 'safe'],
            [['email','id'], 'unique'],
        ];
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
      return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->access_token;
    }

    public function validateAuthKey($authKey)
    {
        return $this->access_token === $authKey;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

}
