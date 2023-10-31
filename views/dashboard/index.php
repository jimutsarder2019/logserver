<?php
use yii\helpers\Url;

$baseUrl = Url::base();

?>
<style>
.fa{
	color:#FFFFFF;
    font-size:20px;
}

.font-white{
	color: #FFFFFF !important;
}

.static-top-widget .media-body h3{
	color: #a5a5a5 !important;
}



/* The Modal (background) */
.modal-alert {
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
.modal-content-alert {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 50%;
}

/* The Close Button */
.close-alert {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close-alert:hover,
.close-alert:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>
<!-- The Modal -->
<div id="myModal-alert" class="modal-alert">

  <!-- Modal content -->
  <div class="modal-content-alert">
    <span class="close-alert">&times;</span>
    <p class="alert-msg" style="color:#ff4c3b">Loading...</p>
  </div>

</div>
<div class="page-body">
	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-lg-6">
					<div class="page-header-left">
						<h3>Dashboard</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->

	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="row">
			<div class="col-xxl-3 col-md-3 xl-25">
				<div class="card o-hidden widget-cards">
					<div class="success-box card-body">
						<div class="media static-top-widget align-items-center">
							<div class="icons-widgets">
								<div class="align-self-center text-center" style="background-color:#90EE90">
									<i data-feather="users" class="font-white"></i>
								</div>
							</div>
							<div class="media-body media-doller">
								<span class="m-0">Active User</span>
								<h3 class="mb-0"><span class="js-user-counter"><?=@$users_count?></span>
								</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xxl-3 col-md-3 xl-25">
				<div class="card o-hidden widget-cards">
					<div class="secondary-box card-body">
						<div class="media static-top-widget align-items-center">
							<div class="icons-widgets">
								<div class="align-self-center text-center">
									<i data-feather="wifi" class="font-secondary"></i>
								</div>
							</div>
							<div class="media-body media-doller">
								<span class="m-0">Router</span>
								<h3 class="mb-0"><span class="counter"><?=@$router_count?></span>
								</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xxl-3 col-md-3 xl-25">
				<div class="card o-hidden widget-cards">
					<div class="primary-box card-body">
						<div class="media static-top-widget align-items-center">
							<div class="icons-widgets">
								<div class="align-self-center text-center"><i
										class="fa fa-calendar"></i></div>
							</div>
							<div class="media-body media-doller"><span class="m-0">Uptime</span>
								<p class="mb-0"><span class="counter"><?=@$uptime?></span> Days</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-xxl-3 col-md-3 xl-25">
				<div class="card o-hidden widget-cards">
					<div class="primary-box card-body">
						<div class="media static-top-widget align-items-center">
							<div class="icons-widgets">
								<div class="align-self-center text-center" style="background-color:#FFFF00"><i class="fa fa-archive"></i></div>
							</div>
							<div class="media-body media-doller"><span class="m-0">Disk Usage</span>
								<h3 class="mb-0"><span class="counter"><?=@$disk_use?></span>%</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-xxl-3 col-md-3 xl-25">
				<div class="card o-hidden widget-cards">
					<div class="danger-box card-body">
						<div class="media static-top-widget align-items-center">
							<div class="icons-widgets">
								<div class="align-self-center text-center" style="background-color:#A020F0"><i data-feather="message-square"
										class="font-danger"></i></div>
							</div>
							<div class="media-body media-doller"><span class="m-0">Disk Free</span>
								<h3 class="mb-0"><span class="counter"><?=@$disk_free?></span>%</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xxl-3 col-md-3 xl-25">
				<div class="card o-hidden widget-cards">
					<div class="warning-box card-body">
						<div class="media static-top-widget align-items-center">
							<div class="icons-widgets">
								<div class="align-self-center text-center">
									<i
										class="fa fa-desktop"></i>
								</div>
							</div>
							<div class="media-body media-doller">
								<span class="m-0">CPU</span>
								<h3 class="mb-0"><span class="counter"><?=@$cpu?></span>%
								</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-xxl-3 col-md-3 xl-25">
				<div class="card o-hidden widget-cards">
					<div class="secondary-box card-body">
						<div class="media static-top-widget align-items-center">
							<div class="icons-widgets">
								<div class="align-self-center text-center" style="background-color:#0000FF">
									<i data-feather="users"></i>
								</div>
							</div>
							<div class="media-body media-doller">
								<span class="m-0">Max User</span>
								<h3 class="mb-0"><span class="counter"><?=@$max_user_count_limit?></span>
								</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-xxl-3 col-md-3 xl-25">
				<div class="card o-hidden widget-cards">
					<div class="danger-box card-body">
						<div class="media static-top-widget align-items-center">
							<div class="icons-widgets">
								<div class="align-self-center text-center"><i
										class="fa fa-clock-o"></i></div>
							</div>
							<div class="media-body media-doller"><span class="m-0">Support Expired</span>
								<p class="mb-0"><?=@$license_expire?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			
			
			
			
			
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h5>Active Router</h5>
						<div class="card-header-right">
							<ul class="list-unstyled card-option">
								<li><i class="icofont icofont-simple-left"></i></li>
								<li><i class="view-html fa fa-code"></i></li>
								<li><i class="icofont icofont-maximize full-card"></i></li>
								<li><i class="icofont icofont-minus minimize-card"></i></li>
								<li><i class="icofont icofont-refresh reload-card"></i></li>
								<li><i class="icofont icofont-error close-card"></i></li>
							</ul>
						</div>
					</div>
					<div class="card-body">
						<div class="user-status table-responsive latest-order-table">
							<table class="table table-bordernone">
								<thead>
									<tr>
										<th scope="col">Name</th>
										<th scope="col">Identity</th>
										<th scope="col">IP</th>
										<th scope="col">Port</th>
										<th scope="col">Username</th>
										<th scope="col">Type</th>
										<th scope="col">Status</th>
									</tr>
								</thead>
								<tbody>
								     
									<?php
									$router_tr = '';
									foreach($router_data as $router){
										
										$status = ($router['status'] == 1)?'Active':'In active';
										$router_tr .= '<tr>
													<td>'.$router['name'].'</td>
													<td>'.$router['identity'].'</td>
													<td>'.$router['ip'].'</td>
													<td>'.$router['api_port'].'</td>
													<td>'.$router['api_username'].'</td>
													<td>'.$router['type'].'</td>
													<td>'.$status.'</td>
												</tr>';
									}
									print $router_tr;
									?>
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>


			<div class="col-md-12">
				<div class="card height-equal">
					<div class="card-header">
						<h5>User List</h5>
					</div>
					<div class="card-body">
						<div class="user-status table-responsive products-table">
							<table class="table table-bordernone mb-0" style="table-layout:fixed">
								<thead>
									<tr>
										<th scope="col">Name</th>
										<th scope="col">Username</th>
										<th scope="col">Registration Date</th>
										<th scope="col">Status</th>
									</tr>
								</thead>
								<tbody>
								
								    <?php
									$user_tr = '';
									foreach($user_data as $k=>$user){
										$user_tr .= '<tr>
													<td>'.$user['name'].'</td>
													<td>'.$user['username'].'</td>
													<td>'.$user['date'].'</td>
													<td>'.($user['status']?'Active':'Inactive').'</td>
												</tr>';
									}
									print $user_tr;
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>



		</div>
	</div>
	<!-- Container-fluid Ends-->
</div>