@extends('layouts.default')
@section('title', 'نیازمندی های پروژه')
@section('content')

    <div class="container">
        <div class="card shadow-sm mt-5">
            <div class="card-header d-flex justify-content-between">
                <h2>نیاز مندی های پروژه <span class="text-success text-weight-bold">{{ $project->title }}</span></h2>
                <button class="btn btn-success" id="create_button"> افزودن
                    نیازمندی</button>
            </div>
            <div class="card-body">
                <div class="row pt-3">
                    <div class="col-md-10">
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
                    </div>
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
                        <th>عنوان</th>
                        <th>توضیحات</th>
                        <th>تاریخ ثبت</th>
                        <th>مدیریت</th>

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
                    <h4 class="modal-title" id="modal_title">افزودن نیازمندی ها</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" action="post" id="create_update">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <input type="hidden" name="requirement_id">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 mt-3">
                            <label for="title" class="form-label">عنوان</label>
                            <input type="title" class="form-control" id="title" name="title">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="description" class="form-label">توضیحات</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea>
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
            var clickButtonID;
            var columns = [{
                    data: 'title'
                },
                {
                    data: 'description'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return moment(data["created_at"], 'YYYY-M-D HH:mm:ss').format(
                            'jYYYY/jMM/jDD');

                    },
                    "visible": true,
                    responsivePriority: 2,
                },
                {
                    responsivePriority: 0,
                    data: null,
                    title: "مدیریت",
                    className: "center",
                    defaultContent: "<button class='btn btn-danger delete'>delete</button>" +
                        "<button class='btn btn-warning edit'>edit</button>"
                }

            ];
            var table =
                datatable('#tbl_requirements',
                    '{{ route("projects.requirements.index", $project->id) }}',
                    columns);



            $('#create_update').submit(function(event) {
                event.preventDefault();
                submit_form(this, clickButtonID,
                    '{{ route("projects.requirements.store") }}', '#add_requirements', table);
            })


            $(document).on('click', '.delete', function(event) {
                var id = $(this).attr('data-id');
                var url = "{{ route('projects.requirements.store') }}" + '/'+ id ;
                ajaxfunc(url, 'DELETE', '');
                table.ajax.reload();

            })


            $(document).on('click', '.edit', function(event) {
                clickButtonID = $(this).attr('data-id');
                // $.ajax({
                //     url: "{{ route('projects.requirements.store') }}" +"/"+ clickButtonID,
                //     method: 'GET',
                //     success: function(res,status) {
                //         $('#title').val(res.title);
                //         $('#description').val(res.description);
                //         $('#add_requirements').modal('show');


                //     },

                // })
             var  res = ajaxfunc('{{ route("projects.requirements.store") }}'+'/'+clickButtonID,'GET','')
                    //  $('#title').val(res.title);
                    //     $('#description').val(res.description);
                    //     $('#add_requirements').modal('show');
                    // console.log(res)
            });

            $('#create_button').click(function() {
                clickButtonID = undefined;
                removeIDValues('#create_update');
                $('#add_requirements').modal('show');

            })





        });
    </script>
@endsection
