<?php

use app\models\ReportBackup;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ReportBackupSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Downlaod Report';
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
								'filterModel' => $searchModel,
								'columns' => [
								    'date',
									[
										'label' => 'Date Range',
										'headerOptions' => ['style' => 'color:#ff4c3b'],
										'content' => function ($model) {
											return $model->from_date.' - '.$model->to_date;
										}
									],
									'report_type',
									[
										'label' => 'Status',
										'headerOptions' => ['style' => 'color:#ff4c3b'],
										'content' => function ($model) {
											if($model->status == 1){
												return 'Processing';
											}else if($model->status == 2){
												return 'Ready';
											}else{
												return 'Pending';
											}
										}
									],
									
									[
										'class' => 'yii\grid\ActionColumn',
										'template' => '{delete}{download}',
										'buttons' => [
											'download' => function($url, $model){
												if($model->status == 2){
													return Html::a('<span class="fa fa-download"></span>', '/uploads/report/'.$model->report_type.'/'.$model->file_name, [
														'class' => 'download',
														'target' => '_blank',
														'data' => [
															'id' => $model->id,
															'page' => 'client-say',
														],
													]);
												}else{
													return '';
												}
											}
										]
									]
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