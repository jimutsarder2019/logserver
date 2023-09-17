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

let limit = 10;
let offset = 1;

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
		offset = 1;
		$(".js_page_no").val(offset);
		generateLogData();
	});
	
	$(".js_router").change(function(){
		generateLogData();
	});
	
	$('.js_search_btn').click(function(){
		generateLogData();
	});
	
	$(".js_searching").keyup(function(){
		var search_value = $(this).val();
		if(search_value.length > 2 || search_value.length === 0){
		   generateLogData();
		}
	});
	
    $('.js_report_csv').click(function(){
		generateLogData('csv');
	});
	
    $('.js_report_excel').click(function(){
		generateLogData('excel');
	});
	
	
	$('.js_report_pdf').click(function(){
		generateLogData('pdf');
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


function getPostParams()
{
	var search_value = $('.js_searching').val();
	var date_start = $('.js_date_start').val();
	var date_end = $('.js_date_end').val();
	
	var user = $('.user').val();
	var mac = $('.mac').val();
	var src_ip = $('.srcip').val();
	var dst_ip = $('.dstip').val();
	var nat_ip = $('.natip').val();
	var router = $('.js_router').val();
	return {offset:offset, limit:limit, search:search_value, from_date:date_start, to_date:date_end, router:router, user:user, mac:mac, src_ip:src_ip, dst_ip:dst_ip, nat_ip:nat_ip};
}

function generateLogData(type=false)
{
	$('.data-render').html('<tr><td style="color:#FF0000">Loading......</td></tr>');
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
				}
					
				if(type == 'csv'){
					generateReport(response.data);
				}else if(type == 'excel'){
					excelReport(response.data);
				}else if(type == 'pdf'){
					pdfPrint();
				}
			}else{
				alert('No data found!');
                $('.data-render').html('<tr><td style="color:#FF0000">No data found!</td></tr>');
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
	
	let blob = new Blob([csv.join("\n")],{type:"text/csv"})
	let download = document.createElement("a")
	download.download = company_name+"LogReport" + Date();
	download.href = URL.createObjectURL(blob);
	download.click();
	csv = [];
	csvRow = [];
	csvHeader = [];  
}



function pdfPrint() {
	var date_start = $('.js_date_start').val();
	var date_end = $('.js_date_end').val();
	
	var divContents = document.getElementById("table-data").innerHTML;
	var a = window.open('', '', 'height=500, width=500');
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
	a.document.write(divContents);
	a.document.write('</body></html>');
	a.document.close();
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