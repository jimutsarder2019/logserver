let csv = []
let csvRow = []
let csvHeader = []
let reportHeaders = [
        { name: "DateTime" },
        { name: "Router IP" },
        { name: "User" },
        { name: "Protocol" },
        { name: "Mac" },
        { name: "Src IP" },
        { name: "Port" },
        { name: "Destination IP" },
        { name: "Port" },
        { name: "NAT IP" },
        { name: "Port" }
    ];

let limit = 50;
let offset = 0;

//document.addEventListener('contextmenu', event => event.preventDefault());

$(document).ready(function(){
	
	const queryString = window.location.search;
	
	if(queryString.includes('syslog/index')){
	   generateLogData();
	}
	
	if(queryString.includes('dashboard/index') || queryString.includes('dashboard%2Findex')){
	   getUserCount();
	}
	
    $(".js_limit_change").change(function(){
		limit = $(this).val();
		offset = 0;
		$(".js_page_no").val(offset);
		generateLogData();
	});
	
	$(".js_router").change(function(){
		generateLogData();
	});
	
	$('.js_search_btn').click(function(){
	    commonSearch('search');
	});
	
	$(".js_searching").keyup(function(){
		var search_value = $(this).val();
		if(search_value.length > 2 || search_value.length === 0){
		   generateLogData();
		}
	});
	
    $('.js_report_csv').click(function(){
		commonSearch('csv');
	});
	
    $('.js_report_excel').click(function(){
		commonSearch('excel');
	});
	
	
	$('.js_report_pdf').click(function(){
		commonSearch('pdf');
	});
	
	
	
	
	$(".global_search").keypress(function(event){
		if (event.key === "Enter") {
			// Cancel the default action, if needed
			event.preventDefault();
			var search_value = $(this).val();
			if(search_value.length > 2){
		        window.location = base_url+'/?r=syslog/index&search='+search_value;
			}else{
				alert('Please enter at least 3 characters');
			}
	    }
	});
	
	$(".global_search_btn").click(function(){
		var search_value = $('.global_search').val();
		if(search_value.length > 2){
			window.location = base_url+'/?r=syslog/index&search='+search_value;
			//window.location = 'http://localhost/logserver/?r=syslog/index&search='+search_value;
		}else{
			alert('Please enter at least 3 characters');
		}
	});
	
	$(".remove").click(function(){
		var id = $(this).data('id');
		if(id){
			if (confirm("Are you sure you want to delete this user?") == true) {
			    window.location = base_url+'/?r=users/delete&id='+id;
			}
		}
	});
	
	$(".close-alert").click(function(){
		$("#myModal-alert").hide();
	});
	
	$(".js_pagination").click(function(){
		let action = $(this).data('action');
		
		if(action == 'next'){
		   offset = parseInt(offset)+ parseInt(1);
		}else{
		   offset = parseInt(offset) - parseInt(1);
		}
		$(".js_page_no").val(offset);
		generateLogData();
	});
	
	$(".js_page_no").change(function(){
		offset = $(this).val();
		limitCount= 10;
		generateLogData();
	});
});


function commonSearch(type)
{
	var long_date_start = $('.js_date_start').val();
	var long_date_end = $('.js_date_end').val();
	
	
	if(long_date_start && long_date_end){
	
		var dateStartMyArray1 = long_date_start.split("T");
		var dateStartMyArray2 = dateStartMyArray1[1].split(":");
		
		var date_start = dateStartMyArray1[0];
		var from_hours = dateStartMyArray2[0];
		var from_mins = dateStartMyArray2[1];
		
		
		var dateEndMyArray1 = long_date_end.split("T");
		var dateEndMyArray2 = dateEndMyArray1[1].split(":");
		
		var date_end = dateEndMyArray1[0];
		var to_hours = dateEndMyArray2[0];
		var to_mins = dateEndMyArray2[1];
		
		if(long_date_start && long_date_end && date_start && date_end && from_hours && from_mins && to_hours && to_mins){
			
			if((date_start <= date_end) && (parseInt(from_hours) <= parseInt(to_hours)) && (parseInt(from_mins) <= parseInt(to_mins))){
				if(type === 'search'){
					generateLogData();
				}else{
					generateLogData(type);
				}
			}else{
				alert('From Date-Time should be equal or less than To Date-Time');
			}
		}else{
			alert('Please select From Date-Time and To Date-Time');
		}
	}else{
		alert('Please select From Date-Time and To Date-Time');
	}
}

function getPostParams()
{
	var search_value = $('.js_searching').val();

	var long_date_start = $('.js_date_start').val();
	var long_date_end = $('.js_date_end').val();
	
	var date_start = '';
	var from_hours = '';
	var from_mins = '';
	
	var date_end = '';
	var to_hours = '';
	var to_mins = '';
	if(long_date_start && long_date_end){
		var dateStartMyArray1 = long_date_start.split("T");
		var dateStartMyArray2 = dateStartMyArray1[1].split(":");
		
		date_start = dateStartMyArray1[0];
		from_hours = dateStartMyArray2[0];
		from_mins = dateStartMyArray2[1];
		
		var dateEndMyArray1 = long_date_end.split("T");
		var dateEndMyArray2 = dateEndMyArray1[1].split(":");
		
		date_end = dateEndMyArray1[0];
		to_hours = dateEndMyArray2[0];
		to_mins = dateEndMyArray2[1];
	}
	
	var user = $('.user').val();
	var mac = $('.mac').val();
	var src_ip = $('.srcip').val();
	var dst_ip = $('.dstip').val();
	var nat_ip = $('.natip').val();
	var router = $('.js_router').val();
	var page_name = $('.js_page_name').val();
	return {page_name:page_name, offset:offset, limit:limit, search:search_value, from_date:date_start, to_date:date_end, from_hours:from_hours, from_mins:from_mins, to_hours:to_hours, to_mins:to_mins, router:router, user:user, mac:mac, src_ip:src_ip, dst_ip:dst_ip, nat_ip:nat_ip};
}

function generateLogData(type=false)
{
	$('.data-render').html('<tr><td style="color:#FF0000">Loading......</td></tr>');
	if(type){
	    $('.js-report-loading').html('<tr><td style="color:#FF0000; font-size:21px;">Loading......</td></tr>');
	}
	$.ajax({  
		url: base_url+'/?r=elastic/get',
		type: 'POST',
        dataType: 'JSON',
        data:getPostParams(),		
		success: function(response) {   
            
			if(response.data && response.data.length > 0){
				let tr = '';
				$.each( response.data, function( key, value ) {
					tr += '<tr>'+
								'<td class="digits">'+value['datetime']+'</td>'+
								'<td class="digits">'+value['host']+'</td>'+
								'<td class="digits">'+value['user']+'</td>'+
								'<td class="digits">'+value['protocol']+'</td>'+
								'<td class="digits">'+value['mac']+'</td>'+
								'<td class="digits">'+value['src_ip']+'</td>'+
								'<td class="digits">'+value['src_port']+'</td>'+
								'<td class="digits">'+value['destination_ip']+'</td>'+
								'<td class="digits">'+value['destination_port']+'</td>'+
								'<td class="digits">'+value['nat_ip']+'</td>'+
								'<td class="digits">'+value['nat_port']+'</td>'+
							'</tr>';
				});
			
				if(tr){
					$('.data-render').html(tr);
				}else{
					$('.data-render').html('<tr><td style="color:#FF0000">No data found!</td></tr>');
					if(type){
					    $('.js-report-loading').html('');
					}
				}
					
				if(type == 'csv'){
					generateReport(response.data);
				}else if(type == 'excel'){
					excelReport(response.data);
				}else if(type == 'pdf'){
					pdfPrint(response.data);
				}
				
				if(response.data.length === 10000){
					alert('Your searching data limitation have already exceed. So, Please add any one filtering option (Mac, Src IP, User, NAT, DST IP).');
				}
			}else{
				alert('No data found!');
                $('.data-render').html('<tr><td style="color:#FF0000">No data found!</td></tr>');
				if(type){
					    $('.js-report-loading').html('');
					}
			}				
		}  
	});  
	
}



function generateReport(data){
	
	var date_start = $('.js_date_start').val();
	var date_end = $('.js_date_end').val();
	
	
	csvTitle = [];
	csvTitle.push(company_name+' Log Report');
	csv.push(csvTitle.join(","));
	
	csvTitle = [];
	csvTitle.push('License Number: '+license_number);
	csv.push(csvTitle.join(","));
	
	csvTitle = [];
	csvTitle.push('Address: '+company_address);
	csv.push(csvTitle.join(","));
	
	csvTitle = [];
	csvTitle.push('Phone Number: '+company_phone);
	csv.push(csvTitle.join(","));
	
	csvTitle = [];
	
	if(date_start && date_end){
	    csvTitle.push('Log Report: '+date_start+' to '+date_end);
	}else{
		csvTitle.push('Log Report:');
	}
	csv.push(csvTitle.join(","));
	
	if(csvHeader.length == 0){
		reportHeaders.forEach((header) => {
			csvHeader.push(header.name);
		});
		csv.push(csvHeader.join(","));
	}
	
    csvTitle = [];
	csvTitle.push(' ');
	csv.push(csvTitle.join(","));
	
	
	$.each( data, function( key, value ) {
		//csvRow.push(key+1);
		csvRow.push(value['datetime']);
		csvRow.push(value['host']);
		csvRow.push(value['user']);
		csvRow.push(value['protocol']);
		csvRow.push(value['mac']);
		csvRow.push(value['src_ip']);
		csvRow.push(value['src_port']);
		csvRow.push(value['destination_ip']);
		csvRow.push(value['destination_port']);
		csvRow.push(value['nat_ip']);
		csvRow.push(value['nat_port']);
		
		csv.push(csvRow.map(str => `"${str}"`).join(","));
		csvRow = []
	});
	$('.js-report-loading').html('');
	let blob = new Blob([csv.join("\n")],{type:"text/csv"})
	let download = document.createElement("a")
	download.download = company_name+"LogReport" + Date();
	download.href = URL.createObjectURL(blob);
	download.click();
	csv = [];
	csvRow = [];
	csvHeader = [];  
}



function pdfPrint(pdfData) {
	
	let tr = '';
	$.each(pdfData, function( key, value ) {
		tr += '<tr>'+
					'<td class="digits">'+value['datetime']+'</td>'+
					'<td class="digits">'+value['host']+'</td>'+
					'<td class="digits">'+value['user']+'</td>'+
					'<td class="digits">'+value['protocol']+'</td>'+
					'<td class="digits">'+value['mac']+'</td>'+
					'<td class="digits">'+value['src_ip']+'</td>'+
					'<td class="digits">'+value['src_port']+'</td>'+
					'<td class="digits">'+value['destination_ip']+'</td>'+
					'<td class="digits">'+value['destination_port']+'</td>'+
					'<td class="digits">'+value['nat_ip']+'</td>'+
					'<td class="digits">'+value['nat_port']+'</td>'+
				'</tr>';
	});
	
	var pdfBodyContent = '<style type="text/css" media="print">@page { size: landscape; }</style>'+
								'<table class="table table-bordernone">'+
									'<thead>'+
										'<tr>'+
											'<th scope="col">DateTime</th>'+
											'<th scope="col">Router IP</th>'+
											'<th scope="col">User</th>'+
											'<th scope="col">Protocol</th>'+
											'<th scope="col">Mac</th>'+
											'<th scope="col">Src IP</th>'+
											'<th scope="col">Port</th>'+
											'<th scope="col">Dst IP</th>'+
											'<th scope="col">Port</th>'+
											'<th scope="col">NAT IP</th>'+
											'<th scope="col">Port</th>'+
										'</tr>'+
									'</thead>'+
									'<tbody class="data-render2">'+tr+'</tbody>'+
								'</table>';
	
	
				 //document.getElementById("table-data").innerHTML = pdfBodyContent;
				
	var date_start = $('.js_date_start').val();
	var date_end = $('.js_date_end').val();
	
	//var divContents = document.getElementById("table-data").innerHTML;
	var a = window.open('', '');
	a.document.write('<html><style>table{border-collapse: collapse;} table, td, th{border:1px solid #000000 !important; padding:2px !important;}</style>');
	a.document.write('<body ><h1>'+company_name+' Log Report</h1><br>');
	a.document.write('<p>License Number: '+license_number+'</p>');
	a.document.write('<p>Address: '+company_address+'</p>');
	a.document.write('<p>Phone Number: '+company_phone+'</p>');
	if(date_start && date_end){
	    a.document.write('<p>Log Report: '+date_start+' to '+date_end+'</p>');
	}else{
		a.document.write('<p>Log Report:</p>');
	}
	a.document.write('<br>');
	a.document.write(pdfBodyContent);
	a.document.write('</body></html>');
	a.document.close();
	$('.js-report-loading').html('');
	a.print();
}


function excelReport(data) {
	final_data = [];
	excelRow = [];
	excelHeader = [];
	
	var date_start = $('.js_date_start').val();
	var date_end = $('.js_date_end').val();
	
	
	csvTitle = [];
	csvTitle.push(company_name+' Log Report');
	final_data.push(csvTitle);
	
	csvTitle = [];
	csvTitle.push('License Number: '+license_number);
	final_data.push(csvTitle);
	
	csvTitle = [];
	csvTitle.push('Address: '+company_address);
	final_data.push(csvTitle);
	
	csvTitle = [];
	csvTitle.push('Phone Number: '+company_phone);
	final_data.push(csvTitle);
	
	csvTitle = [];
	
	if(date_start && date_end){
	    csvTitle.push('Log Report: '+date_start+' to '+date_end);
	}else{
		csvTitle.push('Log Report:');
	}
	final_data.push(csvTitle);
	
	csvTitle = [];
	csvTitle.push(' ');
	final_data.push(csvTitle);
	
	
	
	
	if(excelHeader.length == 0){
		reportHeaders.forEach((header) => {
			excelHeader.push(header.name);
		});
		final_data.push(excelHeader);
	}
	
	$.each( data, function( key, value ) {
		//excelRow.push(key+1);
		excelRow.push(value['datetime']);
		excelRow.push(value['host']);
		excelRow.push(value['user']);
		excelRow.push(value['protocol']);
		excelRow.push(value['mac']);
		excelRow.push(value['src_ip']);
		excelRow.push(value['src_port']);
		excelRow.push(value['destination_ip']);
		excelRow.push(value['destination_port']);
		excelRow.push(value['nat_ip']);
		excelRow.push(value['nat_port']);
		final_data.push(excelRow);
		excelRow = []
	});
	$('.js-report-loading').html('');				
	  // (C2) CREATE NEW EXCEL "FILE"
	var workbook = XLSX.utils.book_new(),
	worksheet = XLSX.utils.aoa_to_sheet(final_data);
	workbook.SheetNames.push("First");
	workbook.Sheets["First"] = worksheet;

	  // (C3) "FORCE DOWNLOAD" XLSX FILE
	XLSX.writeFile(workbook, company_name+"LogReport" + Date()+".xlsx");
}



function getUserCount()
{
	$.ajax({  
		url: base_url+'/?r=api/user',
		type: 'POST',
        dataType: 'JSON',
        data:{page:'dashboard'},		
		success: function(response) { 
		    $(".js-user-counter").text(response.active_user_count);
			if(response.alert){
			    $(".alert-msg").html(response.alert_msg);
			    $("#myModal-alert").show();
			}
		}  
	});  
	
}