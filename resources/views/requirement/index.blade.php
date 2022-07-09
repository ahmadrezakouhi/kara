@extends('layouts.default')
@section('title', 'نیازمندی های پروژه')
@section('content')

    <div class="container">
        <div class="card shadow-sm border mt-5">
            <div class="card-header d-flex justify-content-between">
                <h2>نیازمندی های پروژه {{ $project->title }}</h2>
                @can('create', [App\Models\Requirement::class, $project->id])
                    <button class="btn btn-success" id="create_button"> افزودن
                        نیازمندی</button>
                @endcan
            </div>
            <div class="card-body">
                <div class="row pt-3">

                </div>
                <table id="tbl_requirements" class="table table-bordered table-striped">
                    <thead>
                        <th></th>
                        <th>عنوان</th>
                        <th>توضیحات</th>
                        <th>فاز</th>
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
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
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
    <div class="modal fade" id="add_phase">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">تعیین فاز نیازمندی ها</h4>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>

                </div>
                <form action="" action="post" id="add_requrements_phase">
                    {{-- <input type="hidden" name="project_id" value="{{ $project->id }}"> --}}

                    <!-- Modal body -->
                    <div class="modal-body">
                        @foreach ($phases as $phase)
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="phase{{ $phase->id }}" name="phase_id"
                                    value="{{ $phase->id }}">{{ $phase->title }} : <span
                                    class="text-black-50">{{ $phase->description }}</span>
                                <label class="form-check-label" for="phase{{ $phase->id }}"></label>
                            </div>
                        @endforeach
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
                    data: 'phase',
                    "render": function(data, type, row) {
                        if (data === null) {
                            return '-';
                        } else {
                            return data['title'];
                        }
                    }

                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return covertGregorianToJalali(data['created_at']);

                    },
                    "visible": true,
                    responsivePriority: 2,
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
                        "</button>" +
                        "<button class='btn btn-info add_phase'>" +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">' +
                        '<path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>' +
                        '<path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>' +
                        '</svg>' +
                        "</button></div>"
                }

            ];
            var table =
                datatable('#tbl_requirements',
                    '{{ route('projects.requirements.index', $project->id) }}',
                    columns, true);



            $('#create_update').submit(function(event) {
                event.preventDefault();
                console.log('hi')
                submit_form('#create_update', clickButtonID,
                '{{ route("projects.requirements.store") }}')
                    .then(function(res) {
                        $('#add_requirements').modal('hide');
                        toastr['success'](res.message);
                        table.ajax.reload();
                    }).catch(function(res) {
                        showErrors(res.responseJSON.errors);
                    });


            })


            $(document).on('click', '.delete', function(event) {
                var id = $(this).attr('data-id');
                var url = "{{ route('projects.requirements.store') }}" + '/' + id;


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


                    }
                })

            })


            $(document).on('click', '.edit', function(event) {
                clickButtonID = $(this).attr('data-id');



                ajaxfunc('{{ route('projects.requirements.store') }}' + '/' + clickButtonID,
                        'GET', '').then(function(res) {

                        if (res.message) {
                            toastr['success'](res.message)
                        }

                        $('#title').val(res.title);
                        $('#description').val(res.description);
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


            $(document).on('click', '.add_phase', function(e) {
                clickButtonID = $(this).attr('data-id');
                $('#add_phase').modal('show');
            })

            $('#add_requrements_phase').submit(function(event) {
                event.preventDefault();
                submit_form(this, clickButtonID, "/requirements/" + clickButtonID + "/phases",
                        false)
                    .then(function(res) {
                        $('#add_phase').modal('hide');
                        table.ajax.reload();
                        toastr['success'](res.message);
                    }).catch(function(res) {
                        $('#add_phase').modal('hide');
                        toastr['error'](res.responseJSON.message);
                    });

            })
        });
    </script>
@endsection
