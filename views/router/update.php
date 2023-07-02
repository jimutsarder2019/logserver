<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Router $model */

$this->title = 'Update Router: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Routers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="page-body">
	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-lg-6">
					<div class="page-header-left">
						<h3><?= Html::encode($this->title) ?></h3>
					</div>
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