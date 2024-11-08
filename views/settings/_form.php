<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$baseUrl = Url::base();

/** @var yii\web\View $this */
/** @var app\models\Settings $model */
/** @var yii\widgets\ActiveForm $form */
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
<div class="settings-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
	    <?php if(isset($type)){ ?>
	    <?php if($type == 'account'){ ?>
		<div class="col-md-12">
			<?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>
			
			<?= $form->field($model, 'license_number')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'company_phone')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'company_address')->textInput(['maxlength' => true]) ?>
			
			
			<?= $form->field($model, 'file2')->fileInput()->label('Company Dashboard Logo [Hints: width:60px; Height:50px]'); ?>
			<?php if($model->user_logo){ ?>
			<img src="<?=$baseUrl?>/<?=$model->user_logo?>" width="150" height="150">
			<?php } ?>

			</br>
			</br>
			</br>

			<?= $form->field($model, 'file1')->fileInput()->label('Company Login Logo [Hints: width:150px; Height:50px]'); ?>
			<?php if($model->login_logo){ ?>
				<img src="<?=$baseUrl?>/<?=$model->login_logo?>" width="150" height="150">
			<?php } ?>
			
			
			
			</br>
			</br>
			
			
			<?= $form->field($model, 'file3')->fileInput()->label('Company Favicon'); ?>
			<?php if($model->favicon){ ?>
				<img src="<?=$baseUrl?>/<?=$model->favicon?>" width="150" height="150">
			<?php } ?>
		</div>
		
		<?php }else if($type == 'email'){ ?>
		<div class="col-md-12">
		    <?= $form->field($model, 'email_username')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'email_password')->passwordInput(['maxlength' => true]) ?>
			<div style="display:none">
			<?= $form->field($model, 'email_port')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'email_smtp_secure')->textInput(['maxlength' => true]) ?>
		    </div>
		</div>
		<?php }else{ ?>
		<div class="col-md-12">
		    <?= $form->field($model, 'telegram_bot_token')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'telegram_chat_id')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'telegram_message')->textInput(['maxlength' => true]) ?>
		</div>
		<?php } ?>
		<?php } ?>
	</div>
	
	</br>
	</br>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
