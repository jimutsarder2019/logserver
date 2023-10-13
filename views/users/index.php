<?php

use app\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\ApplicationHelper;
/** @var yii\web\View $this */
/** @var app\models\RouterSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

$isAdmin = ApplicationHelper::isAdmin();

$baseUrl = Url::base();
?>
<style>
.table td a span{
	color:red !important;
}
</style>
<div class="page-body">
	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-lg-6">
					<div class="page-header-left">
						<h3>User List</h3>
					</div>
				</div>
				<div class="col-lg-6">
					<ol class="breadcrumb pull-right">
						<li class="breadcrumb-item">
							<a href="<?=$baseUrl ?>/?r=users/create">
								<i data-feather="user"></i>
							</a>
						</li>
						<li class="breadcrumb-item active">Add User</li>
					</ol>
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
						<div class="router-create">
						    <?= GridView::widget([
								'dataProvider' => $dataProvider,
								//'filterModel' => $searchModel,
								'columns' => [
									//['class' => 'yii\grid\SerialColumn'],

									//'id',
									'name',
									'username',
									//'password',
									//'authKey',
									//'accessToken',
									[
										'label' => 'Role',
										'headerOptions' => ['style' => 'color:#ff4c3b'],
										'content' => function ($model) {
											return ($model->role == 1)?'Admin':'User';
										}
									],
									[
										'label' => 'Status',
										'headerOptions' => ['style' => 'color:#ff4c3b'],
										'content' => function ($model) {
											return ($model->status)?'Active':'Inactive';
										}
									],
									'date',
									
									/*[
										'class' => ActionColumn::className(),
										'visible' => ($isAdmin)?true:false,
										'urlCreator' => function ($action, Users $model, $key, $index, $column) {
											return Url::toRoute([$action, 'id' => $model->id]);
										 }
									],*/
									
									
									
									[
										'class' => 'yii\grid\ActionColumn',
										'template' => '{view} {update} {delete}',
										'buttons' => [
											'delete' => function($url, $model){
												if($model->role > 1 ){
												return Html::a('<span class="fa fa-trash"></span>', 'javascript:void(0)', [
													'class' => 'remove',
													'data' => [
														'id' => $model->id,
													],
												]);
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

