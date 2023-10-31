<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var yii\widgets\ActiveForm $form */
use yii\helpers\Url;

$baseUrl = Url::base();
?>
<style>
input[type=file] {
  width: 100%;
  max-width: 100%;
  color: #444;
  padding: 5px;
  background: #fff;
  border: 1px solid #555;
}

input[type=file]::file-selector-button {
  margin-right: 20px;
  border: none;
  background: #ff4c3b;
  padding: 10px 20px;
  color: #fff;
  cursor: pointer;
  transition: background .2s ease-in-out;
}

input[type=file]::file-selector-button:hover {
  background: #ff4c3b;
}
</style>
<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
	
    <?= $form->field($model, 'accessToken')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'role')->dropDownList([
		1 => 'Admin',
		2 => 'User',
	]) ?>	
	
	<?= $form->field($model, 'status')->dropDownList([
		1 => 'Active',
		0 => 'Inactive',
	]) ?>
	
	<?= $form->field($model, 'file')->fileInput()->label('User Picture [Hints: width:150px; Height:50px]'); ?>
	<?php if($model->authKey){ ?>
	    <img src="<?=$baseUrl?>/<?=$model->authKey?>" width="150" height="150">
	<?php } ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
