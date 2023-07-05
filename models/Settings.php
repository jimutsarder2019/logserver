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
            [['company_name', 'company_address', 'login_logo', 'user_logo','favicon'], 'string', 'max' => 100],
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
        ];
    }
}
