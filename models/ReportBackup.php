<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "report_backup".
 *
 * @property int $id
 * @property string $report_type
 * @property string $from_date
 * @property string $to_date
 * @property string $match1
 * @property string $match2
 * @property string $match_type
 * @property string $file_name
 * @property int $status
 * @property string $date
 */
class ReportBackup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'report_backup';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['report_type', 'from_date', 'to_date', 'date'], 'required'],
            [['status'], 'integer'],
            [['date'], 'safe'],
            [['match1', 'match2', 'file_name'], 'string'],
            [['report_type', 'match_type'], 'string', 'max' => 20],
            [['from_date', 'to_date'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'report_type' => 'Report Type',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'status' => 'Status',
            'date' => 'Date',
        ];
    }
}
