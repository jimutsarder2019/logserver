 <div class="page-body">
	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-lg-6">
					<div class="page-header-left">
						<h3>System Info</h3>
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
					    <div class="row">
						    <div class="col-md-12" style="font-weight:bold; font-size:16px"> ----------------------------- License Details ---------------------------- </div>
						</div>
						</br>
					    <div class="row">
						    <div class="col-md-6">ISP Name:</div>
						    <div class="col-md-6"><?=@$license_data['registration_name']?></div>
						</div>
						<div class="row">
						    <div class="col-md-6">Version:</div>
						    <div class="col-md-6">7.1.24.05.05</div>
						</div>
						<!-- <div class="row">
						    <div class="col-md-3">Licensed NIC:</div>
						    <div class="col-md-9">----</div>
						</div> -->
						<div class="row">
						    <div class="col-md-6">License Expire:</div>
						    <div class="col-md-6"><?=@$license_data['license_expire']?></div>
						</div>
						<div class="row">
						    <div class="col-md-6">Upgradable till:</div>
						    <div class="col-md-6"><?=@$license_data['upgradable_till']?></div>
						</div>
						<div class="row">
						    <div class="col-md-6">Maximum number of User:</div>
						    <div class="col-md-6"><?=@$license_data['maximum_number_of_user_allow']?></div>
						</div>
						<div class="row">
						    <div class="col-md-6">Maximum number of Account:</div>
						    <div class="col-md-6"><?=@$license_data['maximum_number_of_account']?></div>
						</div>
						<div class="row">
						    <div class="col-md-6">Maximum number of Router/NAS:</div>
						    <div class="col-md-6"><?=@$license_data['maximum_number_of_router']?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->
</div>