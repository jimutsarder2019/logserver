<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Router $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Routers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
				<div class="col-lg-6">
					<ol class="breadcrumb pull-right">
						<li class="breadcrumb-item">
							<a href="index.html">
								<i data-feather="home"></i>
							</a>
						</li>
						<li class="breadcrumb-item active"><?= Html::encode($this->title) ?></li>
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
						<div class="user-status table-responsive latest-order-table">
							 <?= DetailView::widget([
								'model' => $model,
								'attributes' => [
									'id',
									'name',
									'identity',
									'type',
									'ip',
									'api_port',
									'api_username',
									'api_password',
									'status',
									'ipv6',
									'connection',
									'date',
								],
							]) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->
</div>
