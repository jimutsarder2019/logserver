<div class="page-body">
	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<h2>Download Log</h2>
				<div class="card">
					<div class="form-group row">
						<div class="card-header search-form">
							<input class="datepicker-here js_date_start" type="search" placeholder="From Date">
							<input class="datepicker-here js_date_end" type="search" placeholder="To Date">
							<input class="user" type="search" placeholder="User">
						</div>
						<div class="card-header search-form">
							<input class="mac" type="search" placeholder="Mac">
							<input class="srcip" type="search" placeholder="Src IP">
							<input class="dstip" type="search" placeholder="Dst IP">
						</div>
						<div class="card-header search-form">
							<input class="natip" type="search" placeholder="NAT IP..">
							<select style="width:258px; display:none;" class="custom-select form-control" required="">
								<option value="">Mikrotik</option>
								<option value="100">100 entries</option>
								<option value="1">200 entries</option>
								<option value="2">500 entries</option>
								<option value="3">1000 entries</option>
								<option value="4">2000 entries</option>
								<option value="5">2000+ entries</option>
							</select>
							<div class="form-inline search-form search-box">
								<select class="custom-select form-control js_router" required="">
									<?php
									$option = '<option value="all">----- All Router-----</option>';
									foreach($routers as $router){
										$option .= '<option value="'.$router['ip'].'">'.$router['name'].' ('.$router['ip'].')</option>';
									}
									print $option;
									?>
								</select>
							</div>
						</div>
						<div class="card-header search-form">
							<button style="width:258px"  type="button" class="btn btn-primary js_report_excel">Excel</button>
							<button style="width:258px"  type="button" class="btn btn-primary js_report_csv">CSV</button>
							<button style="width:258px"  type="button" class="btn btn-primary js_report_pdf">PDF</button>
						</div>
						<div class="card-body" style="display:none;">
							<div class="user-status table-responsive latest-order-table" id="table-data">
								<style type="text/css" media="print">
								  @page { size: landscape; }
								</style>
								<table class="table table-bordernone">
									<thead>
										<tr>
											<th scope="col">DateTime</th>
											<th scope="col">Router IP</th>
											<th scope="col">User</th>
											<th scope="col">Protocol</th>
											<th scope="col">Mac</th>
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
</div>