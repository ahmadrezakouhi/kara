let tt_requirements;

function funcDelete(e) {
    let data_id = $(e).parent().parent().attr("data-id");
    let data_name = $(e).parent().parent().attr("data-title");
    Confirm('توجه', '40%', 'BackToHome|5000', data_name + ' از لیست نیازمندی ها حذف شود؟ ', {
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
                    url: "/requirement/" + data_id,
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

	var _btnDelete = '<button type="button" class="btn btn-warning pull-right"  onclick="funcDelete(this)">'+
	'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">'+
		'<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>'+
		'<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>'+
	'</svg>'+
 '</button>' ;
 	var _btnShow = '<button type="button" class="btn btn-success" onclick="funcShow(this)" >'+
	'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">'+
		'<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>'+
		'<path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>'+
	'</svg>';
	var _bnEdit =  '<button type="button" class="btn btn-info" onclick="funcEdit(this)">'+
	'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">'+
	   '<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>'+
  '</svg>'+
'</button>';
'	</button>';
	// if (_role == 'manager'){
	// 	var btnManage = _btnDelete + _btnShow + _bnEdit;
	// } else
	// var btnManage =  _btnShow + _bnEdit;


	$("#btn-filter").click(function(){
		tt_requirements.ajax.reload();
	});
    tt_requirements = $('#tbl_requirements').on('preXhr.dt', function (e, settings, json, xhr) {}).DataTable(
		{
			"drawCallback": function (settings) {},
			"processing": true,
			"searching": false,
			"serverSide": true,
			"responsive": true,
			"ajax": {
				"url":"/projects/requirements",
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

				{ title: 'ردیف', "defaultContent": "-", },
				///

                {title: 'عنوان', "name": 'title', "data": 'title'},
				{title: 'شرح', "name": 'description', "data": 'description'},

				// {
				// 	responsivePriority: 0,
				// 	data: null,
				// 	title: "مدیریت",
				// 	className: "center",
				// 	defaultContent: btnManage
				// }
			],
			createdRow: function (row, data, dataIndex) {
				$(row).attr("data-id", data['id']);
				$(row).attr("data-title", data['title']);
				$(row).attr("data-description", data['description']);



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
		tt_requirements.on('draw', function() {

			tt_requirements.column(0, {
			  search: 'applied',
			  order: 'applied'
			}).nodes().each(function(cell, i) {

			  cell.innerHTML = tt_requirements.page.len() * tt_requirements.page()+i+1;
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
							tt_requirements.ajax.reload();
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


    $('form').submit(function(event){
        event.preventDefault();
        $.ajax({
            method: 'POST',
            url: '/requirements/create',
            data: $(this).serialize(),
            success: function(res) {
                console.log('ok')
            },
            error: function(res) {
                var error = eval("(" + res.responseText + ")")
                $.each(error.errors, function(index, value) {
                    console.log(value);
                })
            }
        })
    });

});
