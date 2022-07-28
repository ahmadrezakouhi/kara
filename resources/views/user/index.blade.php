@extends('layouts.default')
@section('title', 'کاربران')
@section('content')

    <div class="container">

        <div class="card shadow-sm mt-5 border">
            <div class="card-header ">

                <div class="d-flex justify-content-between">
                    <h2>لیست کاربران </h2>

                    {{-- @can('create', [\App\Models\Task::class, $sprint->id]) --}}
                    <button class="btn btn-success" id="create_button"> افزودن
                        کاربر</button>
                    {{-- @endcan --}}
                </div>
            </div>
            <div class="card-body">
                <div class="row pt-3">

                </div>
                <table id="tbl_users" class="table  table-bordered border table-striped nowrap" width="100%">
                    <thead>
                        <th>شماره</th>
                        <th>نام </th>
                        <th>نام خانوادگی</th>
                        <th>شماره همراه</th>
                        <th>نوع کاربر</th>
                        <th>رنگ پس زمینه</th>
                        <th>رنگ متن</th>
                        <th></th>


                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="modal fade" id="add_users">
        <div class="modal-dialog ">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">افزودن کاربر ها</h4>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" action="post" id="create_update">

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row my-3">
                            <div class="col-md-6">
                                <label for="fname" class="form-label">نام</label>
                                <input type="text" class="form-control" id="fname" name="fname">
                            </div>
                            <div class="col-md-6">
                                <label for="fname" class="form-label">نام خانوادگی </label>
                                <input type="text" class="form-control" id="lname" name="lname">
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">تلفن</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6">
                                <label for="mobile" class="form-label">موبایل</label>
                                <input type="text" class="form-control" id="mobile" name="mobile">
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-md-12">
                                <label for="email" class="form-label">ایمیل</label>
                                <input type="text" class="form-control" id="email" name="email">
                            </div>

                        </div>



                        <div class="row my-3 d-flex align-items-center justify-content-center justify-content-between">
                            <div class="col-md-5">
                                <label for="background_color" class="form-label">رنگ پس زمینه</label>
                                <input type="text" class="form-control " id="background_color" name="background_color" dir="ltr" style="font-family: sans-serif">
                                <div  class=" background_color border rounded"></div>
                            </div>

                            <div class="col-md-5">
                                <label for="text_color" class="form-label">رنگ متن</label>
                                <input type="text" class="form-control " id="text_color" name="text_color" dir="ltr" style="font-family: sans-serif">
                                <div class="text_color  border rounded" ></div>
                            </div>

                            <div class="col-md-2  preview border rounded d-flex justify-content-center  align-items-center" style="height: 30px">
                                ABCD
                            </div>
                        </div>


                        <div class="row my-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">پسورد</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label">تکرار پسورد</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
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

@endsection
@section('scripts')
    <script>
        var auth_id = {{ Auth::id() }};

        @if (Auth::user()->isAdmin())
            var isSuperAdmin = true;
        @else
            var isSuperAdmin = false;
            var isAdmin =
                {{ Auth::user()->projects->find($sprint->phase->project->id)->pivot->admin }};
            var isOwner =
                {{ Auth::user()->projects->find($sprint->phase->project->id)->pivot->owner }};
            var isDeveloper =
                {{ Auth::user()->projects->find($sprint->phase->project->id)->pivot->developer }};
        @endif
    </script>
    <script src="{{ asset('js/general/functions.js') }}"></script>

    <script src="{{ asset('js/general/toastr_option.js') }}"></script>

    <script src="{{ asset('js/general/colorpicker.js') }}"></script>

    <script>
        $(document).ready(function() {

            var clickButtonID;
            var columns = [{
                    data: 'id'
                },
                {
                    data: 'fname'
                },
                {
                    data: 'lname'
                },
                {
                    data: 'mobile'
                },

                {
                    data: null,
                    render: function(data, row, full) {
                        return data['role'] == 'admin' ? 'مدیر' : 'کاربر';
                    }
                },
                {
                    data: null,
                    render: function(data, row, full) {
                        return  data['background_color'] ? '<div style="color:'+data['background_color']+'"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-square-fill border rounded shadow" viewBox="0 0 16 16">' +
                            '<path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2z"/>' +
                            '</svg></div>' : '-' ;
                    }
                },


                {
                    data: null,
                    render: function(data, row, full) {
                        return data['text_color'] ? '<div style="color:'+data['text_color']+'"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-square-fill border rounded shadow"  viewBox="0 0 16 16">' +
                            '<path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2z"/>' +
                            '</svg></div>' : '-' ;
                    }
                },

                {
                    responsivePriority: 0,
                    data: null,
                    title: "مدیریت",
                    className: "center",
                    defaultContent: "<div class='btn-group'><button class='btn btn-danger delete'>" +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">' +
                        '<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>' +
                        '<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>' +
                        '</svg>' +
                        "</button>" +
                        "<button class='btn btn-warning edit'>" +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">' +
                        '<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>' +
                        '</svg>' +
                        "</button></div>"
                }

            ];
            var table =
                datatable('#tbl_users',
                    '{{ route('users.index') }}',
                    columns);

            new $.fn.dataTable.FixedHeader(table);



            $('#create_update').submit(function(event) {
                event.preventDefault();
                submit_form('#create_update', clickButtonID, "{{ route('users.index') }}", )
                    .then(function(res) {
                        loading(false);
                        toastr["success"](res.message);
                        $('#add_users').modal('hide');
                        table.ajax.reload();
                    }).catch(function(res) {
                        loading(false);
                        var error = eval("(" + res.responseText + ")")
                        $.each(res.responseJSON.errors, function(index, value) {
                            toastr["error"](value);
                        })

                    });
            })


            $(document).on('click', '.delete', function(event) {
                var id = $(this).attr('data-id');
                var url = "{{ route('users.index') }}" + '/' + id;

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



                ajaxfunc("{{ route('users.index') }}" + '/' + clickButtonID,
                        'GET', '').then(function(res) {

                        loading(false);
                        if (res.message) {
                            toastr['success'](res.message)
                        }

                        setIDValues('#create_update',res);
                        $('.preview').css({'color':res.text_color , 'background':res.background_color});

                        $('#add_users').modal('show');
                    })
                    .catch(function(res) {
                        loading(false);
                        if (res.responseJSON.message) {
                            toastr['error'](res.responseJSON.message);
                        } else {
                            $.each(res.responseJSON, function(index, value) {
                                toastr['error'](value);
                            })
                        }
                    })




            });

            $('#create_button').click(function() {
                clickButtonID = undefined;
                removeIDValues('#create_update');
                $('.preview').css({'color':'', 'background':''});

                $('#add_users').modal('show');

            })







        });
    </script>
@endsection
