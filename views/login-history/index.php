<?php

use app\models\LoginHistory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\LoginHistorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Login Histories';
$this->params['breadcrumbs'][] = $this->title;
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
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="user-status table-responsive latest-order-table">
							<?= GridView::widget([
								'dataProvider' => $dataProvider,
								//'filterModel' => $searchModel,
								'columns' => [
									//['class' => 'yii\grid\SerialColumn'],

									//'id',
									'username',
									'ip',
									[
										'label' => 'Date',
										'headerOptions' => ['style' => 'color:#ff4c3b'],
										'content' => function ($model) {
											$dateTime = explode(' ',$model->checkin);
											return @$dateTime[0];
										}
									],
									[
										'label' => 'Time',
										'headerOptions' => ['style' => 'color:#ff4c3b'],
										'content' => function ($model) {
											$dateTime = explode(' ',$model->checkin);
											return @$dateTime[1];
										}
									],
									
									'checkin',

									
									/*[
										'class' => ActionColumn::className(),
										'urlCreator' => function ($action, LoginHistory $model, $key, $index, $column) {
											return Url::toRoute([$action, 'id' => $model->id]);
										 }
									],*/
								],
							]); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->
</div>
