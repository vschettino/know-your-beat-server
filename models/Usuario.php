<?php

namespace app\models;

class Usuario extends \yii\redis\ActiveRecord
{
    public function attributes()
    {
        return ['id', 'name', 'address', 'registration_date'];
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['address', 'safe'],
            ['name', 'string', 'min' => 3, 'max' => 12],
        ];
    }
}
