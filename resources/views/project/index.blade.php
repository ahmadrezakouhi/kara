@extends('layouts.default')
@section('title', 'نیازمندی های پروژه')
@section('content')

    <div class="container">

        <div class="card shadow-sm mt-5 border">
            <div class="card-header ">

                <div class="d-flex justify-content-between">
                    <h2>لیست پروژه ها</h2>

                    @can('create', \App\Models\Project::class)
                        <button class="btn btn-success" id="create_button"> افزودن
                            پروژه</button>
                    @endcan
                </div>


            </div>
            <div class="card-body">
                <div class="row pt-3">

                </div>
                <div class="">
                <table id="tbl_projects" class="table text-center table-striped table-bordered border nowrap" style="width:100%">
                    <thead>
                        <th>شماره</th>
                        <th>عنوان</th>
                        <th>توضیحات</th>
                        <th>والد</th>
                        <th>تاریخ شروع</th>
                        <th>تاریخ پایان</th>
                        <th>
                        </th>


                    </thead>
                    <tbody>

                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_projects">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">افزودن پروژه </h4>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" action="post" id="create_update">

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 mt-3">
                            <label for="title" class="form-label">عنوان</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="description" class="form-label">توضیحات</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="project_id" class="form-label">والد</label>

                            <select class="form-select" id="project_id" name="project_id">

                            </select>
                        </div>
                        <div class="mb-3 mt-3">
                            <div class="row">

                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">زمان شروع</label>
                                    <input type="text" class="form-control" id="start_date" name="start_date">
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">زمان پایان</label>
                                    <input type="text" class="form-control" id="end_date" name="end_date">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" id="submit-form" class="btn btn-success"
                            data-bs-dismiss="modal">افزودن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="add_users">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">افزودن کاربرها </h4>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" action="post" id="add_users_form">

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="">
                            <table id="tbl_users" class="table table-bordered border table-striped nowrap" width="100%">
                                <thead>
                                    <th>شماره</th>
                                    <th>مالک</th>
                                    <th>مدیر</th>
                                    <th>اعضا </th>
                                    <th>نام و نام خانوادگی</th>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" id="submit-form" class="btn btn-success"
                            data-bs-dismiss="modal">افزودن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        var auth_id = {{ Auth::id() }};
    </script>
    <script src="{{ asset('js/general/functions.js') }}"></script>

    <script src="{{ asset('js/general/toastr_option.js') }}"></script>
    <script src="{{ asset('js/general/datepikcer_options.js') }}"></script>



    <script>
        $(document).ready(function() {

            var clickButtonID, projectUsers, userTable;
            var columns = [{
                    data:'id'
                },
                {
                    data: 'title'
                },
                {
                    data: 'description'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return data['parent'] ? data['parent']['title'] : '-';

                    }

                },

                {
                    data: null,
                    render: function(data, type, row) {
                        return covertGregorianToJalali(data['start_date']);

                    },

                    "visible": true,
                    responsivePriority: 2,
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return covertGregorianToJalali(data['end_date']);

                    },
                    "visible": true,
                    responsivePriority: 2,
                },
                {
                    responsivePriority: 0,
                    data: null,
                    title: "تنظیمات",
                    className: "center",
                    defaultContent: '<div class="dropdown dropstart">' +
                        '<button type="button" class="btn btn-link text-black-50 " data-bs-toggle="dropdown" style="content: none;">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">' +
                        '<path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>' +
                        '</svg>' +
                        '</button>' +
                        '<ul class="dropdown-menu border">' +


                        @can('isAdmin')
                            '<li><a class="dropdown-item delete" style="cursor:pointer">حذف</a></li>' +
                            '<li><a class="dropdown-item edit"  style="cursor:pointer">ویرایش</a></li>' +
                            '<li><a class="dropdown-item users" style="cursor:pointer">کاربر ها</a></li>' +
                        @endcan

                        '<li><a class="dropdown-item requirements" style="cursor:pointer">نیازمندی ها</a></li>' +
                        '<li><a class="dropdown-item phases" style="cursor:pointer">فاز ها</a></li>' +

                    '</ul>' +
                    '</div>'
                }

            ];



            var userColumns = [{
                    title: 'ردیف',
                    "defaultContent": "-",
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        var project_user = projectUsers.find(function(element) {
                            return element.id == data['id']
                        })
                        var checked = '';
                        if (project_user && project_user.id == data['id'] && project_user.pivot.owner) {
                            checked = 'checked';
                        }
                        return '<input type="radio" class="form-check-input" id="owner' + data['id'] +
                            '" name="owner" value="' +
                            data['id'] + '"' + checked + '>';

                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        var project_user = projectUsers.find(function(element) {
                            return element.id == data['id']
                        })
                        var checked = '';
                        if (project_user && project_user.id == data['id'] && project_user.pivot.admin) {
                            checked = 'checked';
                        }
                        return '<input type="radio" class="form-check-input" id="admin' + data['id'] +
                            '" name="admin" value="' +
                            data['id'] + '" ' + checked + '>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        var project_user = projectUsers.find(function(element) {
                            return element.id == data['id']
                        })
                        var checked = '';
                        if (project_user && project_user.id == data['id'] && project_user.pivot.developer) {
                            checked = 'checked';
                        }
                        return '<input type="checkbox" class="form-check-input" id="developer' + data[
                                'id'] + '" name="developer[]" value="' +
                            data['id'] + '"' + checked + '>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return data['fname'] + ' ' + data['lname'];
                    }
                },


            ];




            var table =
                datatable('#tbl_projects',
                    '{{ route('projects.index') }}',
                    columns, false);

                    new $.fn.dataTable.FixedHeader( table );

            $('#create_update').submit(function(event) {
                event.preventDefault();
                submit_form('#create_update', clickButtonID,
                        '{{ route('projects.store') }}')
                    .then(function(res) {
                        loading(false);
                        toastr['success'](res.message);
                        $('#add_projects').modal('hide');
                        table.ajax.reload();
                    }).catch(function(res) {
                        loading(false);
                        showErrors(res.responseJSON.errors);
                    });
            });




            $('#add_users_form').submit(function(event) {
                event.preventDefault();

                ajaxfunc('/project/' + clickButtonID + '/add-users', 'POST', $(this).serialize())
                    .then(function(res) {
                        loading(false);
                        toastr['success'](res.message);
                    })
                    .catch(function(res) {
                        loading(false);
                    });
                $('#add_users').modal('hide');



            });


            $(document).on('click', '.delete', function(event) {
                var id = $(this).attr('data-id');
                var url = "{{ route('projects.store') }}" + '/' + id;
                Swal.fire({
                    text: "می خواهید رکورد مورد نظر حذف شود ؟",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'خیر',
                    confirmButtonText: 'بله می خواهم'
                }).then((result) => {
                    if (result.isConfirmed) {
                        ajaxfunc(url, 'DELETE', '').then(function(res) {
                            loading(false);
                            toastr['warning'](res.message);
                            table.ajax.reload();
                        }).catch(function(res) {
                            loading(false);
                            toastr['error'](res.responseJSON.message);
                        })



                    }
                })
            })


            $(document).on('click', '.edit', function(event) {
                clickButtonID = $(this).attr('data-id');
                ajaxfunc('{{ route('projects.store') }}' + '/' + clickButtonID,
                        'GET', '').then(function(res) {
                            loading(false);
                        if (res.message) {
                            toastr['success'](res.message)
                        }
                        $('#title').val(res.title);
                        $('#description').val(res.description);
                        $('#start_date').val(covertGregorianToJalali(res.start_date));
                        getProjectForParentSelect();
                        $('#end_date').val(covertGregorianToJalali(res.end_date));
                        $('#add_projects').modal('show');
                    })
                    .catch(function(res) {
                        loading(false);
                        // toastr['error'](res.responseJSON.message);

                    })




            });

            $('#create_button').click(function() {
                clickButtonID = undefined;
                removeIDValues('#create_update');
                getProjectForParentSelect();

                $('#add_projects').modal('show');

            })


            $(document).on('click', '.tasks', function(e) {
                let data_id = $(this).attr("data-id");
                window.location = '/sprints/' + data_id + '/tasks';
            })

            $(document).on('click', '.phases', function(e) {
                let data_id = $(this).attr("data-id");
                window.location = '/projects/' + data_id + '/phases';

            });

            $(document).on('click', '.requirements', function(e) {
                let data_id = $(this).attr("data-id");
                window.location = '/projects/' + data_id + '/requirements';

            });


            $(document).on('click', '.users', function() {
                clickButtonID = $(this).attr("data-id");
                if (userTable) {
                    userTable.destroy();
                }
                ajaxfunc("project/" + clickButtonID + '/getProjectUsers', 'GET', '')
                    .then(function(res) {
                        loading(false);
                        projectUsers = res;
                        userTable = datatable('#tbl_users',
                            '{{ route('projects.getUsers') }}',
                            userColumns, false, false);

                            new $.fn.dataTable.FixedHeader( userTable );
                        $('#add_users').modal('show')
                    })
                    .catch(function(res) {
                        loading(false);
                    });


            })



            function getProjectForParentSelect() {
                ajaxfunc("{{ route('projects.getProjects') }}", "GET", "")
                    .then(function(res) {
                        loading(false);
                        options = '<option value="0">اصلی</option>';
                        res.forEach(project => {
                            options += '<option value="' + project.id + '">' + project.title +
                                '</option>'
                        });
                        $('#project_id').empty();
                        $('#project_id').append(options);
                    }).catch(function(res) {
                        loading(false);
                    })
            }


        });
    </script>


@endsection
