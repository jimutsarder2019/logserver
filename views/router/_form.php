<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Router $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="router-form">
    <?php 
	
	if(isset($error) && $error == 'yes'){
		print '<p style="color:red;">Router is not valid. Please create valid router</p>';
	}
	
	?>
    <?php $form = ActiveForm::begin(); ?>
	
	<div class="form-group row">
		<div class="col-xl-4 col-md-4">
		    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-xl-4 col-md-4">
		    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-xl-4 col-md-4">
		    <?= $form->field($model, 'api_port')->textInput(['maxlength' => true]) ?>
		</div>
	</div>
	
	<div class="form-group row">
		<div class="col-xl-4 col-md-4">
		    <?= $form->field($model, 'api_username')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-xl-4 col-md-4">
		    <?= $form->field($model, 'api_password')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-xl-4 col-md-4">
		    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>
		</div>
	</div>

    <div class="form-group row">
		<div class="col-xl-4 col-md-4">
		    <?= $form->field($model, 'status')->dropDownList([1=>'Active',0=>'In active']); ?>
		</div>
		<div class="col-xl-4 col-md-4">
		    <?= $form->field($model, 'connection')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-xl-4 col-md-4">
		    <?= $form->field($model, 'ipv6')->textInput(['maxlength' => true]) ?>
		</div>
	</div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
