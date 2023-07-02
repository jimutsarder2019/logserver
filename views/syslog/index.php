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
								<option value="100">100 entries</option>
								<option value="200">200 entries</option>
								<option value="500">500 entries</option>
								<option value="1000">1000 entries</option>
								<option value="2000">2000 entries</option>
								<option value="10000">2000+ entries</option>
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
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->
</div>