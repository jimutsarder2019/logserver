<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string|null $company_name
 * @property string|null $login_logo
 * @property string|null $user_logo
 * @property string|null $favicon
 */
class Settings extends \yii\db\ActiveRecord
{
	public $file1;
	public $file2;
	public $file3;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_name', 'login_logo', 'user_logo','favicon'], 'string', 'max' => 100],
			[['file1','file2','file3'], 'file'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_name' => 'Company Name',
            'login_logo' => 'Login Logo',
            'user_logo' => 'User Logo',
            'favicon' => 'Company Favicon',
        ];
    }
}
