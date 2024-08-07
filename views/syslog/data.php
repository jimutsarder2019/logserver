<?php

use yii\helpers\Url;
$baseUrl = Url::base();

?>

<style>
.center {
  text-align: center;
}

.pagination {
  display: inline-block;
}

.pagination a, .pagination select {
  color: black;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
  border: 1px solid #ddd;
  margin: 0 4px;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
  border: 1px solid #4CAF50;
}

.pagination a:hover:not(.active) {background-color: #ddd;}
</style>
 
 <div class="page-body">
	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-lg-6">
					<div class="page-header-left">
						<h3>Index List <span style="font-size:18px;text-transform: capitalize;">(Last 10 days)</span></h3>
						<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']){ ?>
						</br>
						<p style="color:#ff4c3b">Download request has been sent</p>
						<?php } ?>
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
				<div class="card height-equal">
					<div class="card-body">
						<div class="user-status table-responsive products-table">
							<table class="table table-bordernone mb-0" style="table-layout:fixed">
								<thead>
									<tr>
										<th scope="col">Date</th>
										<th scope="col">Data Count</th>
										<th scope="col">Size</th>
										<th scope="col">Action</th>
										
									</tr>
								</thead>
								<tbody>
								
								    <?php
									$index_tr = '';
									foreach($index_data as $k=>$index){
										$index_tr .= '<tr>
													<td>'.$k.'</td>
													<td>'.$index['count'].'</td>
													<td>'.$index['size'].'</td>
													<td><a href="'.$baseUrl.'/?r=syslog/download-request&date='.$k.'&count='.$index['count'].'">Download</a></td>
												</tr>';
									}
									print $index_tr;
									?>
								</tbody>
							</table>
						</div>
						<div class="row">
						        <?php
								$class = 'col-md-6';
								if(isset($_REQUEST['page']) && $_REQUEST['page'] > 1){ ?>
									<div class="col-md-6">
										<h3>
										<a href="<?=$baseUrl ?>/?r=syslog/data&page=<?=($page-2)?>" style="float:left"><<</a></h3>
									</div>
								<?php }else{
									$class = 'col-md-12';
								}
								?>
								<?php if(!empty($index_data)){ ?>
									<div class="<?=$class?>">
										<h3>
										<a href="<?=$baseUrl ?>/?r=syslog/data&page=<?=$page?>" style="float:right">>></a></h3>
									</div>
								<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->
</div>