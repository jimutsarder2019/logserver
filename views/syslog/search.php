<style>
.common-date{
	width:100%;
}

.common-time{
	width:50%;
}

.search-form select {
    border: 1px solid #eff0f1;
    border-radius: 5px;
    background-color: #f8f8f9;
}

</style>
<div class="page-body">
	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-lg-6">
					<div class="page-header-left">
						<h3>Log Search</h3>
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
					<div class="card-header search-form">
						<div></div>
					</div>
					<div class="card-header search-form">
						<input class="js_date_start" type="datetime-local" placeholder="From Date Time">
						<input class="js_date_end" type="datetime-local" placeholder="To Date Time">
						
						<select style="width:258px;" class="custom-select form-control js_router">
							<?php
							$option = '<option value="all">----- All Router-----</option>';
							foreach($routers as $router){
								if($router['type'] != 'pppoe'){
								    $option .= '<option value="'.$router['ip'].'">'.$router['name'].' ('.$router['ip'].')</option>';
							    }
							}
							print $option;
							?>
						</select>
					</div>
						
					<div class="card-header search-form">
						<input class="mac" type="search" placeholder="Mac">
						<input class="srcip" type="search" placeholder="Src IP">
						<input class="dstip" type="search" placeholder="Dst IP">
					</div>
					<div class="card-header search-form">
						<input class="natip" type="search" placeholder="NAT IP..">
						<input class="user" type="search" placeholder="User">
						<select style="width:258px; display:none;" class="custom-select form-control" required="">
							<option value="">Mikrotik</option>
							<option value="100">100 entries</option>
							<option value="1">200 entries</option>
							<option value="2">500 entries</option>
							<option value="3">1000 entries</option>
							<option value="4">2000 entries</option>
						</select>
						<div style="display:none;" class="form-inline search-form search-box">
								<select class="custom-select form-control js_limit_change" required="">
									<option value="50">50 entries</option>
									<option value="100">100 entries</option>
									<option value="200">200 entries</option>
									<option value="500">500 entries</option>
									<option value="1000">1000 entries</option>
									<option value="2000">2000 entries</option>
								</select>
							</div>
						<button style="width:258px"  type="button" class="btn btn-primary js_search_btn">Search</button>
					</div>
					<input value="search" type="hidden" class="js_page_name"> 
					<div class="card-body">
						<div class="user-status table-responsive latest-order-table">
							<table class="table table-bordernone">
								<thead>
									<tr>
										<th scope="col">DateTime</th>
										<th scope="col">Router IP</th>
										<th scope="col">User</th>
										<th scope="col">Protocol</th>
										<th scope="col">MAC</th>
										<th scope="col">Src IP</th>
										<th scope="col">Port</th>
										<th scope="col">Dst IP</th>
										<th scope="col">Port</th>
										<th scope="col">NAT IP</th>
										<th scope="col">Port</th>
									</tr>
								</thead>
								<tbody class="data-render">
									
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