<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property string $accessToken
 * @property int $role
 * @property int $status
 * @property string $date
 */
class Users extends \yii\db\ActiveRecord
{
    public $file;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'date'], 'required'],
            [['role', 'status'], 'integer'],
            [['date'], 'safe'],
            [['name', 'username', 'password', 'accessToken'], 'string', 'max' => 50],
            [['authKey'], 'string', 'max' => 100],
			[['file'], 'file'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'authKey' => 'Image',
            'accessToken' => 'Email',
            'role' => 'Role',
            'status' => 'Status',
            'date' => 'Date',
        ];
    }
}
