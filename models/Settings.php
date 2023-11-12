<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string|null $company_name
 * @property string|null $license_number
 * @property string|null $company_address
 * @property string|null $company_phone
 * @property string|null $login_logo
 * @property string|null $user_logo
 * @property string|null $favicon
 * @property string|null $email_username
 * @property string|null $email_password
 * @property string|null $email_port
 * @property string|null $email_smtp_secure
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
            [['company_name', 'company_address', 'login_logo', 'user_logo','favicon','email_username','email_password',  'email_port', 'email_smtp_secure'], 'string', 'max' => 100],
            [['license_number'], 'string', 'max' => 50],
            [['company_phone'], 'string', 'max' => 20],
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
            'company_phone' => 'Company Phone',
            'company_address' => 'Company Address',
            'license_number' => 'License Number',
            'login_logo' => 'Login Logo',
            'user_logo' => 'User Logo',
            'favicon' => 'Company Favicon',
            'email_username' => 'Email',
            'email_password' => 'App Password',
            'email_port' => 'Email Port',
            'email_smtp_secure' => 'Email SMTP Secure',
        ];
    }
}
