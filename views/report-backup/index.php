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

<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 25%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  text-align:right;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

#myBtn{
	color: #FFFFFF;
	background-color:#FF0000;
	border: 2px solid #FFFFFF;
}
</style>

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
												$bytes = filesize(__DIR__ . '/../../web/uploads/report/'.$model->report_type.'/'.$model->file_name);
											    $dec = 2;
											    $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
												$factor = floor((strlen($bytes) - 1) / 3);
												if ($factor == 0) $dec = 0;
												$exact_size = sprintf("%.{$dec}f %s", $bytes / (1024 ** $factor), $size[$factor]);
												return '<button data-exactsize="'.$exact_size.'" data-size="'.$model->total_possible_size.'" data-total="'.$model->total_possible_data.'" id="myBtn">Processing</button>';
											}else if($model->status == 2){
												return 'Ready';
											}else{
												return 'Pending';
												//return '<button data-total="'.$model->total_possible_data.'" id="myBtn">Processing</button>';
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


<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p class="total_data">Total Data: .....</p>
    <p class="total_size">Total Size: .....</p>
    <p class="total_download_size">Total Downloadable Size: .....</p>
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  var total_data = this.getAttribute("data-total");
  var total_size = this.getAttribute("data-size");
  var total_download_size = this.getAttribute("data-exactsize");
  document.getElementsByClassName("total_data")[0].innerHTML = 'Total Data: '+total_data+'+/-';
  document.getElementsByClassName("total_size")[0].innerHTML = 'Total Size: '+total_size+'+/-';
  document.getElementsByClassName("total_download_size")[0].innerHTML = 'Total Downloadable Size: '+total_download_size;
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>