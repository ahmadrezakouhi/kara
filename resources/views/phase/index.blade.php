@extends('layouts.default')
@section('title', 'نیازمندی های پروژه')
@section('content')

    <div class="container">
        <div class="mt-3 shadow-sm border p-3 d-flex align-items-center rounded">
            <nav aria-label="breadcrumb ">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item " aria-current="page"><a href="{{ route('project.index') }}">پروژه ها</a></li>
                    <li class="breadcrumb-item active " aria-current="page">{{ $project->title }}</li>
                </ol>
            </nav>
        </div>
        <div class="card shadow-sm mt-3 border">
            <div class="card-header ">

                <div class="d-flex justify-content-between">
                    <h2>لیست فاز ها</h2>

                    @can('create', [App\Models\Phase::class, $project->id])
                        <button class="btn btn-success" id="create_button"> افزودن
                            فاز</button>
                    @endcan
                </div>

            </div>
            <div class="card-body">

                <table id="tbl_requirements" class="table table-bordered table-striped">
                    <thead>
                        <th></th>
                        <th>عنوان</th>
                        <th>توضیحات</th>
                        <th>مدت زمان انجام</th>
                        <th>تاریخ شروع</th>
                        <th>تاریخ پایان</th>
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
                    <h4 class="modal-title" id="modal_title">افزودن فاز ها</h4>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" action="post" id="create_update">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <input type="hidden" name="requirement_id">
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
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="duration" class="form-label">مدت زمان انجام</label>
                                    <input type="text" class="form-control" id="duration" name="duration">
                                </div>
                                <div class="col-md-4">
                                    <label for="start_date" class="form-label">زمان شروع</label>
                                    <input type="text" class="form-control" id="start_date" name="start_date">
                                </div>
                                <div class="col-md-4">
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


@endsection
@section('scripts')
    <script>
        var auth_id = {{ Auth::id() }};
        var isAdmin =
            {{ Auth::user()->projects->find($project->id)->pivot->admin }};
        var isOwner =
            {{ Auth::user()->projects->find($project->id)->pivot->owner }};
        var isDeveloper =
            {{ Auth::user()->projects->find($project->id)->pivot->developer }};
    </script>
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
                    data: null,
                    render: function(data, type, row) {
                        return moment(data["start_date"], 'YYYY-M-D HH:mm:ss').format(
                            'jYYYY/jMM/jDD');

                    },
                    "visible": true,
                    responsivePriority: 2,
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return moment(data["end_date"], 'YYYY-M-D HH:mm:ss').format(
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
                    defaultContent:

                        "<div class='btn-group'><button class='btn btn-danger delete'>" +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">' +
                        '<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>' +
                        '<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>' +
                        '</svg>' +
                        "</button>" +
                        "<button class='btn btn-warning edit'>" +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">' +
                        '<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>' +
                        '</svg>' +

                        "</button>" +

                        "</button>" +
                        "<button class='btn btn-info sprints' >" +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-stars" viewBox="0 0 16 16">' +
                        '<path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5z"/>' +
                        '<path d="M2.242 2.194a.27.27 0 0 1 .516 0l.162.53c.035.115.14.194.258.194h.551c.259 0 .37.333.164.493l-.468.363a.277.277 0 0 0-.094.3l.173.569c.078.256-.213.462-.423.3l-.417-.324a.267.267 0 0 0-.328 0l-.417.323c-.21.163-.5-.043-.423-.299l.173-.57a.277.277 0 0 0-.094-.299l-.468-.363c-.206-.16-.095-.493.164-.493h.55a.271.271 0 0 0 .259-.194l.162-.53zm0 4a.27.27 0 0 1 .516 0l.162.53c.035.115.14.194.258.194h.551c.259 0 .37.333.164.493l-.468.363a.277.277 0 0 0-.094.3l.173.569c.078.255-.213.462-.423.3l-.417-.324a.267.267 0 0 0-.328 0l-.417.323c-.21.163-.5-.043-.423-.299l.173-.57a.277.277 0 0 0-.094-.299l-.468-.363c-.206-.16-.095-.493.164-.493h.55a.271.271 0 0 0 .259-.194l.162-.53zm0 4a.27.27 0 0 1 .516 0l.162.53c.035.115.14.194.258.194h.551c.259 0 .37.333.164.493l-.468.363a.277.277 0 0 0-.094.3l.173.569c.078.255-.213.462-.423.3l-.417-.324a.267.267 0 0 0-.328 0l-.417.323c-.21.163-.5-.043-.423-.299l.173-.57a.277.277 0 0 0-.094-.299l-.468-.363c-.206-.16-.095-.493.164-.493h.55a.271.271 0 0 0 .259-.194l.162-.53z"/>' +
                        '</svg>' +
                        "</button> </div>"
                }

            ];
            var table =
                datatable('#tbl_requirements',
                    '{{ route('projects.phases.index', $project->id) }}',
                    columns);



            $('#create_update').submit(function(event) {
                event.preventDefault();
                submit_form('#create_update', clickButtonID,
                        '{{ route('projects.phases.store') }}', '#add_requirements', table)
                    .then(function(res) {
                        toastr["success"](res.message);
                        $('#add_requirements').modal('hide');
                        table.ajax.reload();
                    }).catch(function(res) {
                        showErrors(res.responseJSON.errors);
                    });
            })


            $(document).on('click', '.delete', function(event) {
                var id = $(this).attr('data-id');
                var url = "{{ route('projects.phases.store') }}" + '/' + id;


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

                            toastr['error'](res.responseJSON.message);
                        })


                        // Swal.fire(
                        //     'Deleted!',
                        //     'Your file has been deleted.',
                        //     'success'
                        // )
                    }
                })
            })


            $(document).on('click', '.edit', function(event) {
                clickButtonID = $(this).attr('data-id');



                ajaxfunc('{{ route('projects.phases.store') }}' + '/' + clickButtonID,
                        'GET', '').then(function(res) {
                        if (res.message) {
                            toastr['success'](res.message)
                        }

                        $('#title').val(res.title);
                        $('#description').val(res.description);
                        $('#duration').val(res.duration);
                        $('#start_date').val(covertJalaliToGregorian(res.start_date));
                        $('#end_date').val(covertJalaliToGregorian(res.end_date));
                        $('#add_requirements').modal('show');
                    })
                    .catch(function(res) {

                        toastr['error'](res.responseJSON.message);
                    })




            });

            $('#create_button').click(function() {
                clickButtonID = undefined;
                removeIDValues('#create_update');
                $('#add_requirements').modal('show');

            })


            $(document).on('click', '.sprints', function(e) {
                let data_id = $(this).attr("data-id");
                window.location = '/phases/' + data_id + '/sprints';

            })




        });
    </script>
@endsection
