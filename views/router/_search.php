<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\RouterSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="router-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'identity') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'api_port') ?>

    <?php // echo $form->field($model, 'api_username') ?>

    <?php // echo $form->field($model, 'api_password') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'ipv6') ?>

    <?php // echo $form->field($model, 'connection') ?>

    <?php // echo $form->field($model, 'date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
