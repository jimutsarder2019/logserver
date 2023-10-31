<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ReportBackup $model */

$this->title = 'Create Report Backup';
$this->params['breadcrumbs'][] = ['label' => 'Report Backups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-backup-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
