<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "router".
 *
 * @property int $id
 * @property string $name
 * @property string $identity
 * @property string $type
 * @property string $ip
 * @property string $api_port
 * @property string $api_username
 * @property string $api_password
 * @property string $status
 * @property string $ipv6
 * @property string|null $connection
 * @property string $date
 */
class Router extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'router';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'identity', 'type', 'ip', 'api_port', 'api_username', 'api_password', 'status', 'ipv6', 'date'], 'required'],
            [['date'], 'safe'],
            [['name', 'identity', 'type', 'api_username', 'api_password', 'connection'], 'string', 'max' => 50],
            [['ip'], 'string', 'max' => 30],
            [['api_port', 'status', 'ipv6'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'identity' => 'Identity',
            'type' => 'Type',
            'ip' => 'IP',
            'api_port' => 'Port',
            'api_username' => 'Username',
            'api_password' => 'Password',
            'status' => 'Status',
            'ipv6' => 'Ipv6',
            'connection' => 'Connection',
            'date' => 'Date',
        ];
    }
}
