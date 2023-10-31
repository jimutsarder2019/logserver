<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$baseUrl = Url::base();

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
                                <?php if(@$login_logo){ ?>
                                <h1><img width="150" height="50" src="<?=$baseUrl?>/<?=@$login_logo?>"></h1>
								<?php } ?>
                                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="top-profile-tab" data-bs-toggle="tab"
                                            href="#top-profile" role="tab" aria-controls="top-profile"
                                            aria-selected="true"><span class="icon-user me-2"></span>Login</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="top-tabContent">
									<div class="site-login">
										<?php $form = ActiveForm::begin([
											'id' => 'login-form',
											'layout' => 'horizontal',
											'fieldConfig' => [
												'template' => "{label}\n{input}\n{error}",
												'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
												'inputOptions' => ['class' => 'col-lg-3 form-control'],
												'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
											],
										]); ?>

											<?= $form->field($model, 'username')->textInput(['autofocus' => true])->label(false); ?>

											<?= $form->field($model, 'password')->passwordInput()->label(false); ?>
											
											<div class="form-group">
												<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
											</div>

										<?php ActiveForm::end(); ?>
									</div>
                                    <div class="tab-pane fade" id="top-contact" role="tabpanel"
                                        aria-labelledby="contact-top-tab">
                                        <form class="form-horizontal auth-form">
                                            <div class="form-group">
                                                <input required="" name="login[username]" type="email"
                                                    class="form-control" placeholder="Username"
                                                    id="exampleInputEmail12">
                                            </div>
                                            <div class="form-group">
                                                <input required="" name="login[password]" type="password"
                                                    class="form-control" placeholder="Password">
                                            </div>
                                            <div class="form-group">
                                                <input required="" name="login[password]" type="password"
                                                    class="form-control" placeholder="Confirm Password">
                                            </div>
                                            <div class="form-terms">
                                                <div class="form-check mesm-2">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="customControlAutosizing1">
                                                    <label class="form-check-label ps-2"
                                                        for="customControlAutosizing1"><span>I agree all statements in
                                                            <a href="" class="pull-right">Terms &amp;
                                                                Conditions</a></span></label>
                                                </div>
                                            </div>
                                            <div class="form-button">
                                                <button class="btn btn-primary" type="submit">Register</button>
                                            </div>
                                            <div class="form-footer">
                                                <span>Or Sign up with social platforms</span>
                                                <ul class="social">
                                                    <li><a class="ti-facebook" href=""></a></li>
                                                    <li><a class="ti-twitter" href=""></a></li>
                                                    <li><a class="ti-instagram" href=""></a></li>
                                                    <li><a class="ti-pinterest" href=""></a></li>
                                                </ul>
                                            </div>
                                        </form>
                                    </div>

                                </div>
								
								
								

