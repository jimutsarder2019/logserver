<?php
namespace app\models;
use Yii;
class Elastic extends \yii\elasticsearch\ActiveRecord
{
    public function attributes()
    {
        return['name', 'email'];
    }
}