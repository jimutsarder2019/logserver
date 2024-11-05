<?php

use app\models\Router;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\ApplicationHelper;
/** @var yii\web\View $this */
/** @var app\models\RouterSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Routers';
$this->params['breadcrumbs'][] = $this->title;

$isAdmin = ApplicationHelper::isAdmin();

$baseUrl = Url::base();
?>
<style>
tbody .danger{
	background-color:#FF0000;
}
tbody .danger td{
	color:#FFFFFF;
}
</style>
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
				<?php if($isAdmin){ ?>
				<div class="col-lg-6">
					<ol class="breadcrumb pull-right">
						<li class="breadcrumb-item">
							<a href="<?=$baseUrl ?>/?r=router/create">
								<i data-feather="wifi"></i>
							</a>
						</li>
						<li class="breadcrumb-item active">Add Router</li>
					</ol>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->

	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
				    <?php if($isAdmin){ ?>
					<div class="card-header">
						<div class="card-header-right">
							<a href="<?=Url::base()?>/?r=router/create" class="btn btn-primary">Add Router</a>
						</div>
					</div>
					<?php } ?>
					<div class="card-body">
						<div class="user-status table-responsive latest-order-table">
							<?= GridView::widget([
									'dataProvider' => $dataProvider,
									//'filterModel' => $searchModel,
									 'rowOptions'=>function($model){
											if(ApplicationHelper::checkRouterLog($model->ip) == 'not found'){
												//ApplicationHelper::sendMessageTelegram($model->ip);
												return ['class' => 'danger'];
												
											}else{
												return ['class' => ''];
											}
										},
									'columns' => [
										//['class' => 'yii\grid\SerialColumn'],

										//'id',
										'name',
										'identity',
										'ip',
										'api_port',
										'api_username',
										'type',
										[
											'label' => 'Password',
											'headerOptions' => ['style' => 'color:#ff4c3b'],
											'content' => function ($model) {
												return str_repeat("*", strlen($model->api_password)); 
											}
										],
										[
											'label' => 'Status',
											'headerOptions' => ['style' => 'color:#ff4c3b'],
											'content' => function ($model) {
												return $model->status == 1?'Active':'In active';
											}
										],
										//'date',
										[
										    'class' => 'yii\grid\ActionColumn',
											'visible' => ($isAdmin)?true:false,
                                            'template' => '{view} {update} {delete}',
												'buttons' => [
													'delete' => function ($url, $model) {
														return Html::a('<i style="color:red" class="fa fa-trash" title="Delete"></i>', ['router/delete', 'id' => $model->id], ["data-pjax" => 0, 'onClick' => 'return confirm("Are you sure you want to delete this item?") ']);
													},
											],
                                        ],
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