<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "login_history".
 *
 * @property int $id
 * @property int $user_id
 * @property string $session_id
 * @property string $ip
 * @property string $username
 * @property string $checkin
 */
class LoginHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'login_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'session_id', 'ip', 'username', 'checkin'], 'required'],
            [['user_id'], 'integer'],
            [['checkin'], 'safe'],
            [['session_id', 'username'], 'string', 'max' => 100],
            [['ip'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'session_id' => 'Session ID',
            'ip' => 'Ip Address',
            'username' => 'Username',
            'checkin' => 'Checkin',
        ];
    }
}
