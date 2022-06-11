@extends('layouts.default')
@section('title', 'نیازمندی های پروژه')
@section('content')

    <div class="container">
        <div class="card shadow-sm mt-5">
            <div class="card-header d-flex justify-content-between">
                <h2>تسک های اسپرینت {{ $sprint->title }}</h2>
                <button class="btn btn-success" id="create_button"> افزودن
                    تسک</button>
            </div>
            <div class="card-body">
                <div class="row pt-3">
                    {{-- <div class="col-md-10">
                        <form id='sf' action="getData" method="POST">
                            @csrf
                            <div class="row pt-3">
                                <div class="col-md-5">
                                    <div class="mb-3 row">
                                        <label for="title" class="col-sm-4 col-form-label">عنوان</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="search-title" name="search-title">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary mb-3" id="btn-filter">جستجو</button>
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        </form>
                    </div> --}}
                    {{-- <div class="col-md-2 pt-3 align-left">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlAddUser">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                افزودن کاربر
                </button>
            </div> --}}
                </div>
                <table id="tbl_requirements" class="table table-bordered table-striped">
                    <thead>
                        <th></th>
                        <th>عنوان</th>
                        <th>توضیحات</th>
                        <th>مدت زمان انجام</th>
                        <th>دسته بندی</th>
                        <th></th>
                        {{-- <th>تاریخ ثبت</th>
                        <th>مدیریت</th> --}}

                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="modal fade" id="add_requirements">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">افزودن تسک ها</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" action="post" id="create_update">
                    <input type="hidden" name="sprint_id" value="{{ $sprint->id }}">
                    <input type="hidden" name="requirement_id">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 mt-3">
                            <label for="title" class="form-label">عنوان</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="mb-3 mt3">
                            <label for="category" class="form-label">دسته بندی</label>
                            <select class="form-select" id="category" aria-label="Default select example" name="category">
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach

                              </select>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="description" class="form-label">توضیحات</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <div class="mb-3 mt-3">


                                    <label for="duration" class="form-label">مدت زمان انجام</label>
                                    <input type="text" class="form-control" id="duration" name="duration">


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

    <script src="{{ asset('js/general/functions.js') }}"></script>

    <script src="{{ asset('js/general/toastr_option.js') }}"></script>



    <script>
        $(document).ready(function() {
            $("#start_date, #end_date").pDatepicker({
                format: "YYYY/MM/DD",
                autoClose: true,
                onSelect: function() {}

            });
            $("#start_date, #end_date").val("");
            var clickButtonID;
            var columns = [{
                    title: 'ردیف',
                    "defaultContent": "-",
                },
                {
                    data: 'title'
                },
                {
                    data: 'description'
                },
                {
                    data: 'duration'
                },
                {
                    data: 'category.name'
                },

                {
                    responsivePriority: 0,
                    data: null,
                    title: "مدیریت",
                    className: "center",
                    defaultContent: "<button class='btn btn-danger delete'>" +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">' +
                        '<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>' +
                        '<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>' +
                        '</svg>' +
                        "</button>" +
                        "<button class='btn btn-warning edit'>" +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">' +
                        '<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>' +
                        '</svg>' +
                        "</button>"
                }

            ];
            var table =
                datatable('#tbl_requirements',
                    '{{ route("sprints.tasks.index", $sprint->id) }}',
                    columns);



            $('#create_update').submit(function(event) {
                event.preventDefault();
                submit_form('#create_update', clickButtonID,'{{ route("sprints.tasks.store") }}', '#add_requirements', table)
                    .then(function(res) {
                        toastr['success'](res.message);
                        table.ajax.reload();
                    }).catch(function(res) {

                    });
            })


            $(document).on('click', '.delete', function(event) {
                var id = $(this).attr('data-id');
                var url = "{{ route('sprints.tasks.store') }}" + '/' + id;
                // ajaxfunc(url, 'DELETE', '').then(function(res) {
                //     toastr['success'](res.message)
                // })

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
                            toastr['warning'](res.message);
                            table.ajax.reload();
                        }).catch(function(res) {
                            toastr['warning']('خطایی رخ داده است')
                        })



                    }
                })
            })


            $(document).on('click', '.edit', function(event) {
                clickButtonID = $(this).attr('data-id');



                ajaxfunc("{{ route('sprints.tasks.store') }}" + '/' + clickButtonID,
                        'GET', '').then(function(res) {
                        if (res.message) {
                            toastr['success'](res.message)
                        }
                        // console.log(res)
                        $('#title').val(res['title']);
                        $('#description').val(res.description);
                        $('#duration').val(res.duration);
                        $('#category').val(res.category_id);
                        $('#add_requirements').modal('show');
                    })
                    .catch(function(res) {


                    })




            });

            $('#create_button').click(function() {
                clickButtonID = undefined;
                removeIDValues('#create_update');
                $('#add_requirements').modal('show');

            })


            $(document).on('click', '.sprints', (e) => {
                let data_id = $(this).attr("data-id");
                window.location = '/phases/' + data_id + '/sprints';
                // console.log(data_id)
            })




        });
    </script>
@endsection
