let tt_task;


function funcDelete(e) {
    let data_id = $(e).parent().parent().attr("data-id");
    let data_name = $(e).parent().parent().attr("data-title");
    Confirm('توجه', '40%', 'BackToHome|5000', data_name + ' از لیست فعالیتها حذف شود؟ ', {
        BackToHome: {
            text: 'بازگشت به صفحه اصلی',
            btnClass: 'btn-' + 'green',
            action: function () {
                return;
            }
        }, OK: {
            text: 'حذف', btnClass: 'btn-' + 'red', action: function () {
                $.ajax({
                    type: "DELETE",
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    url: "/task/" + data_id,
                    async: false,
               
					success: function (data) {
							$(e).parent().parent().remove();
							Confirm('تایید', '45%', '', 'آیتم شما با کد پیگیری  ' + data_id + 'از سیستم حذف شد ', {
								tryAgain: {
									text: 'تایید',
									btnClass: 'btn-' + 'green',
									action: function () {
										$(".alert-info").html("Successfully updated task!");
									}
								}
							}, 'green');
						},
					error: function (data) {
						//this function called in return, but controller destroy function do what we want and 
					   //record deleted
						console.log(data);
						if(data.status == 403)
							$(".alert-info").html("عدم دسترسی!");
					}

            	})
			}
        }
    }, 'red');
}

function funcShow(e){
	let data_id = $(e).parent().parent().attr("data-id");
	window.open('/task/'+ data_id, '_blank');
}

function funcEdit(e){
	let data_id = $(e).parent().parent().attr("data-id");
	window.open('/task/'+ data_id + '/edit', '_blank');
}

function funcSetProject(e){
	
	$.post("/project/getProjects",{_token: $("input[name=_token]").val(), project_id: 0}, function (res) {
		var _result =res;
		$("#project_id").find('option')
			.remove()
			.end();
			$("#project_id").append('<option value="0" >اصلی</option>');
		$(_result).each(function (index, element) { 		
			$("#project_id").append('<option value="' + element.id + '" >' + element.title + '</option>');
		});	
		$('.selectpicker').selectpicker('refresh');
	});	
}

function funcSetCategory(e){
	
	$.post("/taskTitle/getTaskTitles",{_token: $("input[name=_token]").val()}, function (res) {
		var _result =res;
		$("#category_id").find('option')
			.remove()
			.end();
			$("#category_id").append('<option value="0" >اصلی</option>');
		$(_result).each(function (index, element) { 		
			$("#category_id").append('<option value="' + element.id + '" >' + element.title + '</option>');
		});	
		$('.selectpicker').selectpicker('refresh');
	});	
}

function funcSetUser(_projectid){
	
	$.post("/projectUser/getUserProjects",{_token: $("input[name=_token]").val(), project_id: _projectid }, function (res) {
		var _result =res;
		$("#userid").find('option')
			.remove()
			.end();
			$("#userid").append('<option value="0" >انجام دهنده</option>');
		$(_result).each(function (index, element) { 		
			$("#userid").append('<option value="' + element.id + '" >' + element.fname + " " + element.lname + '</option>');
		});	
		$('.selectpicker').selectpicker('refresh');
	});	
}
function funcDone(e){
	let data_id = $(e).parent().parent().attr("data-id");
	let data_title = $(e).parent().parent().attr("data-title");
	$.post("/task/setDoneTask",{_token: $("input[name=_token]").val(), id: data_id, type:1 }, function (res) {
		funcAlert("", "فعالیت " + data_title + " خاتمه یافت.")
		tt_task.ajax.reload();
		
	});	
}
function funcUnDone(e){
	let data_id = $(e).parent().parent().attr("data-id");
	let data_title = $(e).parent().parent().attr("data-title");
	$.post("/task/setDoneTask",{_token: $("input[name=_token]").val(), id: data_id, type:0 }, function (res) {
		funcAlert("", "فعالیت " + data_title + " خاتمه یافت.")
		tt_task.ajax.reload();
		
	});	
}
$(document).ready(function () {
	// var selected ='';
	// $(_users).each(function (index, element) {
	// 	selected += (selected=='') ? "Ids[]=" + element.id : "&Ids[]=" + element.id;
	// });
	funcSetCategory();
    funcSetProject();
	$("#project_id").change(function(){
		funcSetUser($(this).val())
	});
	$("#start_date, #end_date_pre, #estart_date, #eend_date_pre").pDatepicker({
		format: "YYYY/MM/DD",
		autoClose: true,
		onSelect: function () {}

	});
	$("#start_date, #end_date_pre").val("");

	$("#estart_date").val(_start);
	$("#eend_date_pre").val(_end);
	var btnManage = 
	'<button type="button" class="btn btn-info" onclick="funcEdit(this)">'+
	 	'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">'+
			'<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>'+
	   '</svg>'+
 	'</button>'  +
	 '<button type="button" class="btn btn-olive" onclick="funcDone(this)" data-bs-toggle="tooltip" title="اتمام فعالیت">'+
		'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-square" viewBox="0 0 16 16">'+
		 	'<path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>'+
		 	'<path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>'+
	   '</svg>'+
 	'</button>'  +
	 '<button type="button" class="btn btn-success" onclick="funcShow(this)" >'+
	 	'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">'+
			'<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>'+
			'<path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>'+
	   '</svg>'+
	   '</button>'+
	   '<button type="button" class="btn btn-warning pull-right"  onclick="funcDelete(this)">'+
		  '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">'+
			  '<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>'+
			  '<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>'+
		  '</svg>'+
	   '</button>'
	 ;

	 var btnManageDone = 
	
	 '<button type="button" class="btn btn-warning" onclick="funcUnDone(this)" data-bs-toggle="tooltip" title="عدم اتمام فعالیت">'+
		'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-square" viewBox="0 0 16 16">'+
		'<path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>'+
		'<path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>'+
	  '</svg>'+
 	'</button>'  +
	 '<button type="button" class="btn btn-success" onclick="funcShow(this)" >'+
	 	'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">'+
			'<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>'+
			'<path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>'+
	   '</svg>'+
	   '</button>'
	   
	 ;
	$("#btn-filter").click(function(){
		tt_task.ajax.reload();
	});
    tt_task = $('#tbl-task').on('preXhr.dt', function (e, settings, json, xhr) {}).DataTable(
		{
			"drawCallback": function (settings) {},
			"processing": true,
			"searching": false,
			"serverSide": true,
			"responsive": true,
			"ajax": {
				"url":"/task/getData",
				"type": "POST",
				"data": function (d) {
					d.sf = $('#sf').serializeObject();
					d._token = $("input[name=_token]").val();
				}
			},
			"fnInitComplete": function (oSettings, json) {},
			"initComplete": function(settings, json) {},
			order: [[0, "desc"]],
			"pageLength": 10,
			"searchDelay": 1000,
			"columns": [			
                { title: 'ردیف', "defaultContent": "-", },
				{title: 'عنوان', "name": 'title', "data": 'title', responsivePriority: 1,},
				{
					data: null, title: 'زمان شروع', render: function (data, type, row) {
						
						return moment(data["start_date"], 'YYYY-M-D HH:mm:ss').format('jYYYY/jMM/jDD');
					}
					, "visible": true,responsivePriority: 2,
				},
                {
					data: null, title: 'پیش بینی زمان پایان', render: function (data, type, row) {
						
						return moment(data["end_date_pre"], 'YYYY-M-D HH:mm:ss').format('jYYYY/jMM/jDD');
					}
					, "visible": true, responsivePriority: 3,
				},	
				{
					data: null, title: 'پروژه', render: function (data, type, row) {
						
						return data["project_title"];
					}
					, "visible": true
				},		
				{
					data: null, title: 'دسته بندی', render: function (data, type, row) {
						
						return data["category_title"];
					}
					, "visible": true
				},	
				{
					data: null, title: 'انجام دهنده', render: function (data, type, row) {
						
						return data["username"];
					}
					, "visible": true
				},	
				{
					data: null, title: 'زمان خاتمه', render: function (data, type, row) {
						
						return data["status"]== 1 ? moment(data["time_do"], 'YYYY-M-D HH:mm:ss').format('HH:mm - jYYYY/jMM/jDD') : "-";
					}
					, "visible": true, responsivePriority: 3,
				},				
				// {title: 'سطح', "name": 'level', "data": 'level'},
				{
					responsivePriority: 0,
					data: null,
					title: "مدیریت",
					className: "center",
					render: function (data, type, row) { return data["status"]== 1 ? btnManageDone : btnManage}
				}
			],
			createdRow: function (row, data, dataIndex) {
				$(row).attr("data-id", data['id']);
				// $(row).attr("data-name", data['fname'] + ' ' +  data['lname']);
				$(row).attr("data-title", data['title']);


			},
			"language": {
				"decimal": "-",
				"decimal": "",
				"emptyTable": "هیچ اطلاعاتی برای نمایش وجود ندارد!",
				"info": "نمایش _START_ تا _END_ از تمام _TOTAL_ ",
				"infoEmpty": "چیزی پیدا نشد",
				"infoFiltered": "(جستجو شده از  _MAX_ پرسنل )",
				"infoPostFix": "",
				"thousands": ",",
				"lengthMenu": "نمایش _MENU_ ",
				"loadingRecords": "لطفا صبر کنید...",
				"processing": "در حال پردازش...",
				"search": "جستجوی عمومی:",
				"zeroRecords": " پیدا نشد",
				"paginate": {
					"first": ">>",
					"last": "<<",
					"next": "بعدی",
					"previous": "قبلی"
				}
			}
		});

				// ///////
				tt_task.on('draw', function() {
			
					tt_task.column(0, {
					  search: 'applied',
					  order: 'applied'
					}).nodes().each(function(cell, i) {
					 
					  cell.innerHTML = tt_task.page.len() * tt_task.page()+i+1;
					});
				  }).draw();
		
		// ////

		$("#btnAddTask").click(function(){
			var required = ["#category_id","#title", "#start_date", "#end_date_pre", "#project_id", "#userid"];
			required = checkRequired(required);
			if (required) {	
					$.ajax({
						url: "/task/addTask",
						type: 'POST',
						data: {
							_token:$("input[name=_token]").val(),
							title : $("#title").val(),
							start_date : $("#start_date").val(),
							end_date_pre :$("end_date_pre").val(),
							project_id : $("#project_id").val(),
							userid : $("#userid").val(),
							description : $("#description").val(),
							category_id : $("#category_id").val(),
						}, success: function(result) {
							if(result == -1){
								funcAlert("", "خطا در ثبت اطلاعات");
							}else{
								$("#frmTask")[0].reset();
								myModalTask.hide()
								funcAlert("", "فعالیت " + $("#title").val() + " در سیستم ثبت شد.");
								tt_task.ajax.reload();
							}
						}
					})
				}else
					return false
	
			
			
		});

});