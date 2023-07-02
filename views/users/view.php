<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Users $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
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
						<h3><?= $model->name ?></h3>
					</div>
				</div>
				<div class="col-lg-6">
					<ol class="breadcrumb pull-right">
						<li class="breadcrumb-item">User
							<a href="index.html">
								<i data-feather="home"></i>
							</a>
						</li>
						<li class="breadcrumb-item active"><?= $model->name ?></li>
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
						        <?= DetailView::widget([
									'model' => $model,
									'attributes' => [
										'id',
										'username',
										'password',
										'authKey',
										'accessToken',
										'role',
										'status',
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
