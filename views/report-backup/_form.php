<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ReportBackup $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="report-backup-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'report_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'from_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'to_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
