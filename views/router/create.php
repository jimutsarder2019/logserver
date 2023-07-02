<?php
use yii\helpers\Url;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Router $model */

$this->title = 'Create Router';
$this->params['breadcrumbs'][] = ['label' => 'Routers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$baseUrl = Url::base();
?>



<div class="page-body">
	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-lg-6">
					<div class="page-header-left">
						<h3>Router</h3>
					</div>
				</div>
				<div class="col-lg-6">
					<ol class="breadcrumb pull-right">
						<li class="breadcrumb-item">
							<a href="<?=$baseUrl ?>/?r=router/index">
								<i data-feather="wifi"></i>
							</a>
						</li>
						<li class="breadcrumb-item active">Router</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->

	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-6 xl-100">
				<div class="card">
					<div class="card-body">
						<div class="router-create">
							<?= $this->render('_form', [
								'model' => $model,
							]) ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->
</div>