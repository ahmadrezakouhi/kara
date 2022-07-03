let tt_user;

function funcDelete(e) {
    let data_id = $(e).parent().parent().attr("data-id");
    let data_name = $(e).parent().parent().attr("data-name");
    Confirm('توجه', '40%', 'BackToHome|5000', data_name + ' از لیست کاربران حذف شود؟ ', {
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
                    url: "/user/" + data_id,
                    async: false,

					success: function (data) {
							$(e).parent().parent().remove();
							Confirm('تایید', '45%', '', 'آیتم شما با کد پیگیری  ' + data_id + 'از سیستم حذف شد ', {
								tryAgain: {
									text: 'تایید',
									btnClass: 'btn-' + 'green',
									action: function () {
										$(".alert-info").html("Successfully updated user!");
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
	window.open('/user/'+ data_id, '_blank');
}

function funcEdit(e){
	let data_id = $(e).parent().parent().attr("data-id");
	window.open('/user/'+ data_id + '/edit', '_blank');
}

$(document).ready(function () {

	var _btnDelete = '<button type="button" class="btn btn-link pull-right"  onclick="funcDelete(this)" style="text-decoration: underline;">'+
	'حذف'+
 '</button>' ;
 	var _btnShow = '<button type="button" class="btn btn-link" onclick="funcShow(this)" style="text-decoration: underline;">'+
	'مشاهده';
	var _bnEdit =  '<button type="button" class="btn btn-link" onclick="funcEdit(this)" style="text-decoration: underline;">'+
	'ویرایش'+
'</button>';
'	</button>';
	if (_role == 'manager'){
		var btnManage = _btnDelete + _btnShow + _bnEdit;
	} else
	var btnManage =  _btnShow + _bnEdit;


	$("#btn-filter").click(function(){
		tt_user.ajax.reload();
	});
    tt_user = $('#tbl-user').on('preXhr.dt', function (e, settings, json, xhr) {}).DataTable(
		{
			"drawCallback": function (settings) {},
			"processing": true,
			"searching": false,
			"serverSide": true,
			"responsive": true,
			"ajax": {
				"url":"/user/getData",
				"type": "POST",
				"data": function (d) {
					d.sf = $('#sf').serializeObject();
					d._token = $("input[name=_token]").val();
				}
			},
			"fnInitComplete": function (oSettings, json) {},
			"initComplete": function(settings, json) {},
			order: [[0, "desc"]],
			"pageLength": 20,
			"searchDelay": 1000,
			"columns": [

				{ title: 'ردیف', "defaultContent": "-", },
				///
				{
					data: null, title: 'نام و نام خانوادگی', render: function (data, type, row) {
						return data.fname + ' ' + data.lname;
					}
					, "visible": true
				},

				{title: 'تلفن همراه', "name": 'mobile', "data": 'mobile'},
                {title: 'تلفن ثابت', "name": 'phone', "data": 'phone'},
				{title: 'ایمیل', "name": 'email', "data": 'email'},
				{title: 'نوع کاربری', "name": 'role', "data": 'role'},
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
				$(row).attr("data-name", data['fname'] + ' ' +  data['lname']);
				$(row).attr("data-mobile", data['mobile']);
				let _role='';
				switch(data['role']){
					case 'manager':
						_role= 'کاربر ارشد';
						break;
					case 'admin':
						_role= 'مدیر';
						break;
					default:
						_role= 'کارمند';
						break;
				}
				row.childNodes[5].innerHTML = _role;

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
		tt_user.on('draw', function() {

			tt_user.column(0, {
			  search: 'applied',
			  order: 'applied'
			}).nodes().each(function(cell, i) {

			  cell.innerHTML = tt_user.page.len() * tt_user.page()+i+1;
			});
		  }).draw();

// ////


	$("#btnAddUser").click(function(){
		var required = ["#fname", "#lname", "#mobile", "#password", "#password_confirmation", "#email"];
		required = checkRequired(required);
		// if (required && vmsNationalCode($("#MelliCode")) && vmobile($("#Mobile"))) {
		if($("#password").val() == $("#password_confirmation").val() ){
				if (required) {
				$.ajax({
					url: "/user/addUser",
					type: 'POST',
					data: {
						_token:$("input[name=_token]").val(),
						fname : $("#fname").val(),
						lname : $("#lname").val(),
						role :$("input[name=role]:checked").val(),
						mobile : $("#mobile").val(),
						email : $("#email").val(),
						password : $("#password").val(),
					}, success: function(result) {
						if(result == -1){
							funcAlert("", "خطا در ثبت اطلاعات");
						}else{
							$("#frmUser")[0].reset();
							myModalUser.hide()
							funcAlert("", "کاربر " + $("#fname").val() + " " + $("#lname").val() + "در سیستم ثبت شد.");
							tt_user.ajax.reload();
						}
					}
				})
			}else
				return false

		}else{
			funcAlert("", ".رمز ورود با تکرار آن همخوانی ندارد");
			return false
		}

	});

});
