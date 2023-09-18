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
						<h3>Activity Log</h3>
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
						<div class="form-inline search-form search-box">
							<select class="custom-select form-control js_limit_change" required="">
								<option value="50">50 entries</option>
								<option value="100">100 entries</option>
								<option value="200">200 entries</option>
								<option value="500">500 entries</option>
								<option value="1000">1000 entries</option>
								<option value="2000">2000 entries</option>
							</select>
						</div>
						
						<div class="form-inline search-form search-box">
							<select class="custom-select form-control js_router" required=""  style="display:none;">
							    <?php
								$option = '<option value="all">----- All Router-----</option>';
								foreach($routers as $router){
									$option .= '<option value="'.$router['ip'].'">'.$router['name'].' ('.$router['ip'].')</option>';
								}
                                print $option;
								?>
							</select>
						</div>

						<input class="js_searching" value="<?=@$search?>" type="search" placeholder="Search..">
					</div>
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
						</br>
						<div class="center">
						  <div class="pagination">
						  <a href="javascript:void(0)" data-action="prev" class="js_pagination">&laquo;</a>
						  <select class="js_page_no">
						  <?php for($c = 1; $c <= 200; $c++){ ?>
						     <option value="<?=$c?>"><?=$c?></option>
						  <?php } ?>
						  </select>
						  <a href="javascript:void(0)" data-action="next" class="js_pagination">&raquo;</a>
						  </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->
</div>