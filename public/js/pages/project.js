let tt_project;
var _users=[];
var _manage=[];
var _parent=0;

function funcDelete(e) {
    let data_id = $(e).parent().parent().attr("data-id");
    let data_name = $(e).parent().parent().attr("data-name");
    Confirm('توجه', '40%', 'BackToHome|5000', data_name + ' از لیست کارمندان حذف شود؟ ', {
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
                    url: "/project/" + data_id,
                    async: false,
               
					success: function (data) {
							$(e).parent().parent().remove();
							Confirm('تایید', '45%', '', 'آیتم شما با کد پیگیری  ' + data_id + 'از سیستم حذف شد ', {
								tryAgain: {
									text: 'تایید',
									btnClass: 'btn-' + 'green',
									action: function () {
										$(".alert-info").html("Successfully updated project!");
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
	window.open('/project/'+ data_id, '_blank');
}

function funcEdit(e){
	let data_id = $(e).parent().parent().attr("data-id");
	window.open('/project/'+ data_id + '/edit', '_blank');
}


function funcSetUser(e){
	let data_id = $(e).parent().parent().attr("data-id");
	let data_parent = $(e).parent().parent().attr("data-parent");
	$(".title-project").html($(e).parent().parent().attr("data-title"));
	_parent= data_parent;

	tt_user.ajax.url( _parent == 0 ? "/user/getDataForProject" :"/projectUser/getUserByParentProject"  ).load();
	$.get("/projectUser/getAllWithID/" + data_id, function (res) {
		var _result =(res);
		_users=[];
		$(_result).each(function (index, element) {
			_users.push({id:element.userid})
			if(element.title==0){
				_manage.push({id:element.userid})
			}
		});
		// tt_user.draw();
	});
	$("#btnAddUser").attr("data-id", data_id);
	$("#btnAddUser").attr("data-parent", data_parent);
	tt_user.draw();
	myModalUser.show()
	// $("#mdlAddUsers").fadeIn("show")
}

function funcSetParentProject(e){
	let data_id = $(e).parent().parent().attr("data-id");
	let data_parent = $(e).parent().parent().attr("data-parent");
	$.post("/project/getProjects",{_token: $("input[name=_token]").val()}, function (res) {
		var _result =res;
		$("#parent-level").find('option')
			.remove()
			.end();
			$("#parent-level").append('<option value="0" >اصلی</option>');
		$(_result).each(function (index, element) { 		
			$("#parent-level").append('<option value="' + element.id + '" >' + element.title + '</option>');
		});	
		$("#parent-level").val(data_parent);
		$('.selectpicker').selectpicker('refresh');
	});
	$("#btnAddParentProject").attr("data-id", data_id);
	myModalParentProject.show()
}

function funcSetUserTask(_projectid){
	
	$.post("/projectUser/getUserProjects",{_token: $("input[name=_token]").val(), project_id: _projectid }, function (res) {
		var _result =res;
		$("#userid_task").find('option')
			.remove()
			.end();
			$("#userid_task").append('<option value="0" >انجام دهنده</option>');
		$(_result).each(function (index, element) { 		
			$("#userid_task").append('<option value="' + element.id + '" >' + element.fname + " " + element.lname + '</option>');
		});	
		$('.selectpicker').selectpicker('refresh');
	});	
}
function funcSetTask(e){
	let data_id_project = $(e).parent().parent().attr("data-id");
	$(".title-project").html($(e).parent().parent().attr("data-title"));
	$("#btnAddTaskProject").attr("data-id", data_id_project);
	funcSetUserTask(data_id_project);
	myModalTaskProject.show()
}

$(document).ready(function () {
	// var selected ='';
	// $(_users).each(function (index, element) {
	// 	selected += (selected=='') ? "Ids[]=" + element.id : "&Ids[]=" + element.id;
	// });

	$("#start_date, #end_date_pre, #estart_date, #eend_date_pre, #start_date_task , #end_date_task").pDatepicker({
		format: "YYYY/MM/DD",
		autoClose: true,
		onSelect: function () {}

	});
	$("#start_date, #end_date_pre, #start_date_task , #end_date_task").val("");

	$("#estart_date").val(_start);
	$("#eend_date_pre").val(_end);
	var btnManage = 
	'<button type="button" class="btn btn-primary"  onclick="funcSetParentProject(this)" data-bs-toggle="tooltip"  title="افزودن پروژه والد">'+
		'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-plus" viewBox="0 0 16 16">'+
			'<path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z"/>'+
			'<path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>'+
			'<path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>'+
	  	'</svg>'+
	'</button>'  +
	'<button type="button" class="btn btn-teal" onclick="funcSetUser(this)" data-bs-toggle="tooltip" title="افزودن کاربران" >'+
		'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">'+
			'<path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>'+
			'<path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>'+
	    '</svg>'+
	'</button>'  +
	'<button type="button" class="btn btn-blue" onclick="funcSetTask(this)" data-bs-toggle="tooltip" title="افزودن فعالیت" >'+
		'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-window-plus" viewBox="0 0 16 16">'+
			'<path d="M2.5 5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1ZM4 5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1Zm2-.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Z"/>'+
			'<path d="M0 4a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v4a.5.5 0 0 1-1 0V7H1v5a1 1 0 0 0 1 1h5.5a.5.5 0 0 1 0 1H2a2 2 0 0 1-2-2V4Zm1 2h13V4a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2Z"/>'+
			'<path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5Z"/>'+
	  	'</svg>'+
	'</button>'  +
	'<button type="button" class="btn btn-info" onclick="funcEdit(this)">'+
	 	'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">'+
			'<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>'+
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
	$("#btn-filter").click(function(){
		tt_project.ajax.reload();
	});
    tt_project = $('#tbl-project').on('preXhr.dt', function (e, settings, json, xhr) {}).DataTable(
		{
			"drawCallback": function (settings) {},
			"processing": true,
			"searching": false,
			"serverSide": true,
			"responsive": true,
			"ajax": {
				"url":"/project/getData",
				"type": "POST",
				"data": function (d) {
					d.sf = $('#sf').serializeObject();
					d._token = $("input[name=_token]").val();
				}
			},
			"fnInitComplete": function (oSettings, json) {},
			"initComplete": function(settings, json) {},
			order: [[0, "desc"]],
			"pageLength": 5,
			"searchDelay": 1000,
			"columns": [			
                {title: 'شناسه', "name": 'id', "data": 'id'},
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
					data: null, title: 'سر پروژه', render: function (data, type, row) {
						
						return data["parent_level_id"] == 0 ? "-" : data["parent_level_name"];
					}
					, "visible": true
				},				
				// {title: 'سطح', "name": 'level', "data": 'level'},
				{
					responsivePriority: 0,
					data: null,
					title: "مدیریت",
					className: "center",
					defaultContent: btnManage
				}
			],
			createdRow: function (row, data, dataIndex) {
				$(row).attr("data-id", data['id']);
				// $(row).attr("data-name", data['fname'] + ' ' +  data['lname']);
				$(row).attr("data-title", data['title']);
				$(row).attr("data-parent", data['parent_level_id']);
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


	tt_user = $('#tbl-user').on('preXhr.dt', function (e, settings, json, xhr) {}).DataTable(
		{
			"drawCallback": function (settings) {
				$(_users).each(function (index, value) {
					$.each($(".check-user"), function (index, element) {
						var _idtr=$(element).parents("tr").attr("data-id");
						var _idchecked = value.id;

						if(_idtr== _idchecked){
							$(element).prop('checked',true);
						}
					});
					$.each($(".radio-user"), function (index, element) {
						var _idtr=$(element).parents("tr").attr("data-id");
						var _idchecked = _manage[0].id;

						if(_idtr== _idchecked){
							$(element).prop('checked',true);
						}
					})
				});
			},
			"processing": true,
			"searching": false,
			"serverSide": true,
			"responsive": true,
			"ajax": {
				"url":  "/user/getDataForProject",
				"type": "POST",
				"data": function (d) {
					d.sf = $('#sf').serializeObject();
					d._token = $("input[name=_token]").val();
					d._parent = _parent
				}
			},
			"fnInitComplete": function (oSettings, json) {},
			"initComplete": function(settings, json) {
				document.querySelectorAll('.form-outline').forEach((formOutline) => {
					new mdb.Input(formOutline).init();
					});
			},
			order: [[0, "desc"]],
			"pageLength": 10,
			"searchDelay": 1000,
			"columns": [	
				{
					responsivePriority: 0,
					// "className": 'details-control',
					"orderable": false,
					title: 
						'مدیر'
						,						
					"defaultContent": 
						'<div class="form-check">'+
							'<input class="form-check-input radio-user" type="radio" name="check-manage" value="" >'+
							'<label class="form-check-label" for="flexCheckDefault">'+								
							'</label>'+
						'</div>'
						,
				},	
				{
					responsivePriority: 1,
					// "className": 'details-control',
					"orderable": false,
					title: 
						'<div class="form-check">'+
							'<input class="form-check-input" id="checkedAll" type="checkbox" value="" >'+
							'<label class="form-check-label" for="flexCheckDefault">اعضا</label>'+
						'</div>'
						,						
					"defaultContent": 
						'<div class="form-check">'+
							'<input class="form-check-input check-user" type="checkbox" value="" >'+
							'<label class="form-check-label" for="flexCheckDefault">'+								
							'</label>'+
						'</div>'
						,
				},	
				{
					data: null, title: 'نام', render: function (data, type, row) {
						return data.fname + ' ' + data.lname;
					}
					, "visible": true
				},
				// {
				// 	data: null, title: 'سمت', render: function (data, type, row) {
				// 		return ('<div class="form-outline"><input type="text" class="form-control job-position" /><label class="form-label" ">سمت</label></div>');
				// 	}
				// 	, "visible": true
				// },
				
			],
			createdRow: function (row, data, dataIndex) {
				$(row).attr("data-id", _parent == 0 ? data['id'] : data['userid']);
				$(row).attr("data-name", data['fname'] + ' ' +  data['lname']);
				// $(row).attr("data-mobile", data['mobile']);
			
				
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

		
	$(document).on("click", "#checkedAll", function () {
		$('.check-user').prop('checked', this.checked);
		var check= this.checked;
		_users=[];
		if(check){
			$.post("/user/getAll", {_token:$("input[name=_token]").val()}, function (res) {
				var _result =(res);
				$(_result).each(function (index, element) {
					_users.push({id:element.id})
				});
				// console.log(_users)
			});

				// console.log("ddd");
				// $("#btnSendMdl").removeClass("disabled");
				// $("#btnMultiDelete").removeClass("disabled");

		// }else{
		// 	$("#btnSendMdl").addClass("disabled");
		// 	$("#btnMultiDelete").addClass("disabled");
		}


	});
	$(document).on("click", ".check-user", function () {
		$(this).prop('checked', this.checked);
		var user = "";
		var check= this.checked;
		var len = 0;
		if(check){
			_users.push({id:$(this).parents("tr").attr("data-id")});
		}else{
			console.log($(this).parents("tr").attr("data-id"))
			_users = _users.filter(e => e.id != $(this).parents("tr").attr("data-id"));
			console.log(_users)

		}
	});
	
	$(document).on("click", ".radio-user", function () {
		$(this).prop('checked', this.checked);
		var check= this.checked;
		if(check){
			_manage=[];
			_manage.push({id:$(this).parents("tr").attr("data-id")});
		}
	});

	$("#btnAddUser").click(function(){
		if(_manage.length!= 0 && _users.length!=0){
			let _ids="";
			$(_users).each(function (index, element) {
				if(_ids=="")
					_ids= element.id;
				else
					_ids = _ids + ',' + element.id;
			});
			$.post("/projectUser/add", 
				{
					_token:$("input[name=_token]").val(),
					manage: _manage[0].id,
					users: 	_ids,
					project_id: $("#btnAddUser").attr("data-id")			
				}, function (res) {
					myModalUser.hide();
					funcAlert("", "کاربران ثبت شدند.")
			})
		}else{
			funcAlert("", "کاربری انتخاب نشده است!")
		}
	});
	$("#btnAddParentProject").click(function(){
		$.ajax({
			url: "/project/addParent",
			type: 'POST',
			data: {
				_token:$("input[name=_token]").val(),
				parent_level_id : $("#parent-level").val(),
				parent_level_name : $("#parent-level option:selected").text(),
				project_id: $("#btnAddParentProject").attr("data-id")
			}, success: function(result) {
				myModalParentProject.hide()
				tt_project.ajax.reload();
			}
		})
	});
	$("#btnAddTaskProject").click(function(){
		Confirm('توجه', '40%', 'BackToHome|5000', 'فعالیت ' + $("#title_task").val() + ' برای کاربر ' + $("#userid_task option:selected").text() + " ثبت شود؟", {
			BackToHome: {
				text: 'بازگشت به صفحه اصلی',
				btnClass: 'btn-' + 'red',
				action: function () {
					return;
				}
			}, OK: {
				text: 'ثبت', btnClass: 'btn-' + 'green', action: function () {
					$.ajax({
						type: "POST",
						headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
						url: "/task/addTask",
						async: false,
						data: {
							_token:$("input[name=_token]").val(),
							title : $("#title_task").val(),
							start_date : $("#start_date_task").val(),
							end_date : $("#end_date_task").val(),
							description : $("#description_task").val(),
							userid : $("#userid_task").val(),
							username : $("#userid_task option:selected").text(),
							project_id: $("#btnAddTaskProject").attr("data-id")
						},
						success: function (data) {
								Confirm('تایید', '45%', '', 'فعالیت ' + $("#title_task").val() + ' برای ' + $("#userid_task option:selected").text() + " ثبت شد.", {
									tryAgain: {
										text: 'تایید',
										btnClass: 'btn-' + 'green',
										action: function () {
											$('.selectpicker').selectpicker('refresh');
											$('#frmTaskProject')[0].reset();
											tt_project.ajax.reload();
											myModalTaskProject.hide();
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
		// $.ajax({
		// 	url: "/task/addTask",
		// 	type: 'POST',
		// 	data: {
		// 		_token:$("input[name=_token]").val(),
		// 		title : $("#title_task").val(),
		// 		start_date : $("#start_date_task").val(),
		// 		end_date : $("#end_date_task").val(),
		// 		description : $("#description_task").val(),
		// 		userid : $("#userid_task").val(),
		// 		username : $("#userid_task option:selected").text(),
		// 		project_id: $("#btnAddTaskProject").attr("data-id")
		// 	}, success: function(result) {

		// 		$('#frmTaskProject')[0].reset()
		// 		tt_project.ajax.reload();
		// 	}
		// })
	});

	$("#btnAddProject").click(function(){
		var required = ["#titleProject", "#start_date", "#end_date"];
        required = checkRequired(required);
        // if (required && vmsNationalCode($("#MelliCode")) && vmobile($("#Mobile"))) {
        if (required) {
			$.ajax({
				url: "/project/addProject",
				type: 'POST',
				data: {
					_token:$("input[name=_token]").val(),
					title : $("#titleProject").val(),
					start_date : $("#start_date").val(),
					end_date_pre : $("#end_date_pre").val(),
					description : $("#descriptionProject").val(),
				}, success: function(result) {
					if(result == -1){
						funcAlert("", "خطا در ثبت اطلاعات");
					}else{
						$("#frmProject")[0].reset();
						myModalProject.hide()
						funcAlert("", "پروژه " + $("#titleProject").val() + "در سیستم ثبت شد.");
						tt_project.ajax.reload();
					}
				}
			})
		}
	});

	
});