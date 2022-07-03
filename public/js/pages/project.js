let tt_project;
var _users = [];
var _manage = [];
var _owner = [];
var _parent = 0;
var _alluser = [];
function funcDelete(e) {
    let data_id = $(e).parent().parent().attr("data-id");
    let data_name = $(e).parent().parent().attr("data-title");
    Confirm('توجه', '40%', 'BackToHome|5000', data_name + ' از لیست پروژه ها حذف شود؟ ', {
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
                        if (data.status == 403)
                            $(".alert-info").html("عدم دسترسی!");
                    }

                })
            }
        }
    }, 'red');
}

function funcShow(e) {
    let data_id = $(e).parent().parent().attr("data-id");
    window.open('/project/' + data_id, '_blank');
}

function funcEdit(e) {
    let data_id = $(e).parent().parent().attr("data-id");
    window.open('/project/' + data_id + '/edit', '_blank');
}

function funcShowRequirements(e) {
    let data_id = $(e).parent().parent().attr("data-id");
    window.location = '/projects/' + data_id + '/requirements';
}

function funcShowPhases(e) {
    let data_id = $(e).parent().parent().attr("data-id");
    window.location = '/projects/' + data_id + '/phases';
}

function funcSetUser(e) {
    let data_id = $(e).parent().parent().attr("data-id");
    let data_parent = $(e).parent().parent().attr("data-parent");
    $(".title-project").html($(e).parent().parent().attr("data-title"));
    _parent = data_parent;

    // tt_user.ajax.url( _parent == 0 ? "/user/getDataForProject" :"/projectUser/getUserByParentProject"  ).load();

    $.get("/projectUser/getAllWithID/" + data_id, function (res) {
        var _result = (res);
        console.log(_result)
        _users = [];
        $(_result).each(function (index, element) {
            _users.push({ id: element.userid })
            if (element.status == 0) {

                _owner.push({ id: element.userid });
            }

            if(element.status == 1){
                _manage.push({ id: element.userid });
            }
        });
        // console.log(_users)
        // tt_user.draw();
    });
    $("#btnAddUser").attr("data-id", data_id);
    $("#btnAddUser").attr("data-parent", data_parent);
    // tt_user.draw();
    myModalUser.show()
    // $("#mdlAddUsers").fadeIn("show")
}

function funcSetParentProject(e) {
    let data_id = $(e).parent().parent().attr("data-id");
    let data_parent = $(e).parent().parent().attr("data-parent");
    $.post("/project/getProjects", { _token: $("input[name=_token]").val(), project_id: data_id }, function (res) {
        var _result = res;
        $("#parent-level").find('option')
            .remove()
            .end();
        if (_role == 'manager')
            $("#project_id").append('<option value="0" >اصلی</option>');
        // else
        // $("#project_id").append('<option value="-1" selected >اصلی</option>');
        $(_result).each(function (index, element) {
            $("#parent-level").append('<option value="' + element.id + '" >' + element.title + '</option>');
        });
        $("#parent-level").val(data_parent);
        $('.selectpicker').selectpicker('refresh');
    });
    $("#btnAddParentProject").attr("data-id", data_id);
    myModalParentProject.show()
}

function funcGetParentProject() {
    $.post("/project/getProjects", { _token: $("input[name=_token]").val(), project_id: 0 }, function (res) {
        var _result = res;
        $("#project_id").find('option')
            .remove()
            .end();
        if (_role == 'manager')
            $("#project_id").append('<option value="0" >اصلی</option>');
        else
            $("#project_id").append('<option value="-1" selected >همه</option>');
        $(_result).each(function (index, element) {
            $("#project_id").append('<option value="' + element.id + '" >' + element.title + '</option>');
        });
        $('.selectpicker').selectpicker('refresh');
    });
}
let parentListId = [];
function funcGetAllParentProject() {

    $.post("/project/getParentProjects", { _token: $("input[name=_token]").val(), project_id: 0 }, function (res) {
        var _result = res;
        $("#search-parent-id").find('option')
            .remove()
            .end();
        if (_role == 'manager')
            $("#search-parent-id").append('<option value="0" >اصلی</option>');
        else
            $("#search-parent-id").append('<option value="-1" selected >همه</option>');
        $(_result).each(function (index, element) {
            if (parentListId.indexOf(element.parent_level_id) < 0) {
                parentListId.push(element.parent_level_id);
                $("#search-parent-id").append('<option value="' + element.parent_level_id + '" >' + element.parent_level_name + '</option>');
            }
        });
        $('.selectpicker').selectpicker('refresh');
    });
}
function funcSetUserTask(_projectid) {

    $.post("/projectUser/getUserProjects", { _token: $("input[name=_token]").val(), project_id: _projectid }, function (res) {
        var _result = res;
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
function funcSetTask(e) {
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
    funcGetParentProject();
    funcGetAllParentProject();
    $('#mdlAddUsers').on('shown.bs.modal', function () {
        if ($.fn.DataTable.isDataTable('#tbl-user')) {
            tt_user.destroy();
        }
        tt_user = $('#tbl-user').on('preXhr.dt', function (e, settings, json, xhr) { }).DataTable(
            {
                "drawCallback": function (settings) {
                    $(_users).each(function (index, value) {
                        $.each($(".check-user"), function (index, element) {
                            var _idtr = $(element).parents("tr").attr("data-id");
                            var _idchecked = value.id;

                            if (_idtr == _idchecked) {
                                $(element).prop('checked', true).click();
                                $(element).click()
                            }
                        });
                        $.each($(".radio-user"), function (index, element) {
                            var _idtr = $(element).parents("tr").attr("data-id");
                            var _idchecked = _manage[0].id;

                            if (_idtr == _idchecked) {
                                $(element).prop('checked', true).click();
                            }
                        });
                        $.each($(".radio-owner"), function (index, element) {
                            var _idtr = $(element).parents("tr").attr("data-id");
                            var _idchecked = _owner[0].id;

                            if (_idtr == _idchecked) {
                                $(element).prop('checked', true).click();
                            }
                        })
                    });
                },
                "processing": true,
                "searching": false,
                "serverSide": true,
                "responsive": true,
                "ajax": {
                    "url": _parent == 0 ? "/user/getDataForProject" : "/projectUser/getUserByParentProject",
                    "type": "POST",
                    "data": function (d) {
                        d.sf = $('#sf').serializeObject();
                        d._token = $("input[name=_token]").val();
                        d._parent = _parent
                    }
                },
                "fnInitComplete": function (oSettings, json) { },
                "initComplete": function (settings, json) {
                    _alluser = json.data;
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
                            '<div class="form-check">' +
                            '<input class="form-check-input radio-user" type="radio" name="check-manager" value="" >' +
                            '<label class="form-check-label" for="flexCheckDefault">' +
                            '</label>' +
                            '</div>'
                        ,
                    },
                    {
                        responsivePriority: 0,
                        // "className": 'details-control',
                        "orderable": false,
                        title:
                            'مالک'
                        ,
                        "defaultContent":
                            '<div class="form-check">' +
                            '<input class="form-check-input radio-owner" type="radio" name="check-owner" value="" >' +
                            '<label class="form-check-label" for="flexCheckDefault">' +
                            '</label>' +
                            '</div>'
                        ,
                    },
                    {
                        responsivePriority: 1,
                        // "className": 'details-control',
                        "orderable": false,
                        title:
                            '<div class="form-check">' +
                            '<input class="form-check-input" id="checkedAll" type="checkbox" value="" >' +
                            '<label class="form-check-label" for="flexCheckDefault">اعضا</label>' +
                            '</div>'
                        ,
                        "defaultContent":
                            '<div class="form-check">' +
                            '<input class="form-check-input check-user" type="checkbox" value="" >' +
                            '<label class="form-check-label" for="flexCheckDefault">' +
                            '</label>' +
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
                    $(row).attr("data-name", data['fname'] + ' ' + data['lname']);
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
    })
    $("#start_date, #end_date_pre, #estart_date, #eend_date_pre, #start_date_task , #end_date_task, #search-start-date, #search-end-date, #search-start-date-to, #search-end-date-to").pDatepicker({
        format: "YYYY/MM/DD",
        autoClose: true,
        onSelect: function () { }

    });
    $("#start_date, #end_date_pre, #start_date_task , #end_date_task, #search-start-date, #search-end-date, #search-start-date-to, #search-end-date-to").val("");

    $("#estart_date").val(_start);
    $("#eend_date_pre").val(_end);
    var btnManage =
        '<button type="button" class="btn btn-link"  onclick="funcSetProgressProject(this)" data-bs-toggle="tooltip"  title="افزودن پروژه والد" style="text-decoration: underline;">' +
        'پیشرفت' +
        '</button>' +
        '<button type="button" class="btn btn-link"  onclick="funcSetParentProject(this)" data-bs-toggle="tooltip"  title="افزودن پروژه والد" style="text-decoration: underline;">' +
        'والد' +
        '</button>' +
        '<button type="button" class="btn btn-link" onclick="funcSetUser(this)" data-bs-toggle="tooltip" title="افزودن کاربران" style="text-decoration: underline;">' +
        'کاربران' +
        '</button>' +
        '<button type="button" class="btn btn-link" onclick="funcSetTask(this)" data-bs-toggle="tooltip" title="افزودن فعالیت" style="text-decoration: underline;">' +
        'کارها' +
        '</button>' +
        '<button type="button" class="btn btn-link" onclick="funcEdit(this)" style="text-decoration: underline;">' +
        'ویرایش' +
        '</button>' +
        '<button type="button" class="btn btn-link" onclick="funcShow(this)" style="text-decoration: underline;">' +
        'مشاهده' +
        '</button>' +
        '<button type="button" class="btn btn-link pull-right"  onclick="funcDelete(this)" style="text-decoration: underline;">' +
        'حذف' +
        '</button>' +
        '<button type="button" class="btn btn-link pull-right"  onclick="funcShowRequirements(this)" style="text-decoration: underline;">' +
        'نیازمندی ها' +
        '</button>' +
        '<button type="button" class="btn btn-link pull-right"  onclick="funcShowPhases(this)" style="text-decoration: underline;">' +
        'فازها'+
        '</button>'

        ;
    $("#btn-filter").click(function () {
        tt_project.ajax.reload();
    });
    tt_project = $('#tbl-project').on('preXhr.dt', function (e, settings, json, xhr) { }).DataTable(
        {
            "drawCallback": function (settings) { },
            "processing": true,
            "searching": false,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "/project/getData",
                "type": "POST",
                "data": function (d) {
                    d.sf = $('#sf').serializeObject();
                    d._token = $("input[name=_token]").val();
                }
            },
            "fnInitComplete": function (oSettings, json) { },
            "initComplete": function (settings, json) { },
            order: [[0, "desc"]],
            "pageLength": 10,
            "searchDelay": 1000,
            "columns": [
                { title: 'ردیف', "defaultContent": "-", },
                { title: 'عنوان', "name": 'title', "data": 'title', responsivePriority: 1, },
                {
                    data: null, title: 'زمان شروع', render: function (data, type, row) {

                        return moment(data["start_date"], 'YYYY-M-D HH:mm:ss').format('jYYYY/jMM/jDD');
                    }
                    , "visible": true, responsivePriority: 2,
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
                if (data['status'] == 1 && _role != "manager") {
                    row.childNodes[5].innerHTML = '-';
                }
            },
            "language": {
                "decimal": "-",
                "decimal": "",
                "emptyTable": "هیچ اطلاعاتی برای نمایش وجود ندارد!",
                "info": "نمایش _START_ تا _END_ از تمام _TOTAL_ ",
                "infoEmpty": "چیزی پیدا نشد",
                "infoFiltered": "(جستجو شده از  _MAX_ پروژه )",
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
    tt_project.on('draw', function () {

        tt_project.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {

            cell.innerHTML = tt_project.page.len() * tt_project.page() + i + 1;
        });
    }).draw();

    // ////


    $(document).on("click", "#checkedAll", function () {
        $('.check-user').prop('checked', this.checked);
        var check = this.checked;
        _users = [];
        if (check) {

            // $.post("/user/getAll", {_token:$("input[name=_token]").val()}, function (res) {
            // var _result =(res);
            $(_alluser).each(function (index, element) {
                _users.push({ id: element.userid })
            });
            // console.log(_users)
            // });

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
        var check = this.checked;
        var len = 0;
        if (check) {
            _users.push({ id: $(this).parents("tr").attr("data-id") });
        } else {
            _users = _users.filter(e => e.id != $(this).parents("tr").attr("data-id"));

        }
    });

    $(document).on("click", ".radio-user", function () {
        $(this).prop('checked', this.checked);
        var check = this.checked;
        if (check) {
            _manage = [];
            _manage.push({ id: $(this).parents("tr").attr("data-id") });
        }
    });

    $(document).on("click", ".radio-owner", function () {
        $(this).prop('checked', this.checked);
        var check = this.checked;
        if (check) {
            _owner = [];
            _owner.push({ id: $(this).parents("tr").attr("data-id") });
        }
    });

    $("#btnAddUser").click(function () {
        if (_manage.length != 0 && _users.length != 0) {
            let _ids = "";
            $(_users).each(function (index, element) {
                if (_ids == "")
                    _ids = element.id;
                else
                    _ids = _ids + ',' + element.id;
            });
            // console.log(_ids)
            // return false;
            $.post("/projectUser/add",
                {
                    _token: $("input[name=_token]").val(),
                    manage: _manage[0].id,
                    owner:_owner[0].id,
                    users: _ids,
                    project_id: $("#btnAddUser").attr("data-id")
                }, function (res) {
                    myModalUser.hide();
                    funcAlert("", "کاربران ثبت شدند.")
                })
        } else {
            funcAlert("", "کاربری انتخاب نشده است!")
        }
    });
    $("#btnAddParentProject").click(function () {
        $.ajax({
            url: "/project/addParent",
            type: 'POST',
            data: {
                _token: $("input[name=_token]").val(),
                parent_level_id: $("#parent-level").val(),
                parent_level_name: $("#parent-level option:selected").text(),
                project_id: $("#btnAddParentProject").attr("data-id")
            }, success: function (result) {
                myModalParentProject.hide()
                tt_project.ajax.reload();
            }
        })
    });
    $("#btnAddTaskProject").click(function () {
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
                            _token: $("input[name=_token]").val(),
                            title: $("#title_task").val(),
                            start_date: $("#start_date_task").val(),
                            end_date: $("#end_date_task").val(),
                            description: $("#description_task").val(),
                            userid: $("#userid_task").val(),
                            username: $("#userid_task option:selected").text(),
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
                            if (data.status == 403)
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

    $("#btnAddProject").click(function () {
        if (_role == 'admin')
            var required = ["#titleProject", "#start_date", "#end_date_pre", "#project_id"];
        else
            var required = ["#titleProject", "#start_date", "#end_date_pre"];

        required = checkRequired(required);
        // if (required && vmsNationalCode($("#MelliCode")) && vmobile($("#Mobile"))) {
        if (required) {
            $.ajax({
                url: "/project/addProject",
                type: 'POST',
                data: {
                    _token: $("input[name=_token]").val(),
                    title: $("#titleProject").val(),
                    start_date: $("#start_date").val(),
                    end_date_pre: $("#end_date_pre").val(),
                    description: $("#descriptionProject").val(),
                    project_id: $("#project_id").val(),
                }, success: function (result) {
                    if (result == -1) {
                        funcAlert("", "خطا در ثبت اطلاعات");
                    } else {
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
