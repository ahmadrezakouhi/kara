@extends('layouts.default')
@section('title', 'نیازمندی های پروژه')
@section('content')

    <div class="container">
        <div class="mt-3 mt-3 shadow-sm border p-3 d-flex align-items-center rounded">
            <nav aria-label="breadcrumb ">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item " aria-current="page"><a href="{{ route('projects.index') }}">پروژه ها</a>
                    </li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('projects.phases.index', $sprint->phase->project->id) }}">پروژه
                            {{ $sprint->phase->project->title }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('phases.sprints.index', $sprint->phase->id) }}">{{ $sprint->phase->title }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $sprint->title }}</li>
                </ol>
            </nav>
        </div>
        <div class="card shadow-sm mt-2 border">
            <div class="card-header ">

                <div class="d-flex justify-content-between">
                    <h2>لیست تسک ها</h2>

                    @can('create', [\App\Models\Task::class, $sprint->id])
                        <button class="btn btn-success" id="create_button"> افزودن
                            تسک</button>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row pt-3">

                </div>
                <table id="tbl_requirements" class="table  table-bordered border table-striped nowrap display" style="width:100%">
                    <thead>
                        <th>شماره</th>
                        <th>عنوان</th>
                        <th>توضیحات</th>
                        <th>مجری</th>
                        <th>مدت زمان انجام</th>
                        <th>دسته بندی</th>
                        <th></th>


                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="modal fade" id="add_requirements">
        <div class="modal-dialog ">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">افزودن تسک ها</h4>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
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
                        @can('createForOthers',[App\models\Task::class,$sprint->id])
                        <div class="mb-3 mt3">
                            <label for="user_id" class="form-label">مجری</label>
                            <select class="form-select" id="user_id"  name="user_id">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->fname .' ' .$user->lname}}</option>
                                @endforeach

                            </select>
                        </div>
                        @endcan
                        <div class="mb-3 mt-3">


                            <label for="duration" class="form-label">مدت زمان انجام(به دقیقه)</label>
                            <input type="text" class="form-control" id="duration" name="duration">

                            @php
                                $base = 60;
                            @endphp
                            @for ($j = 1; $j <= 2; $j++)
                                @if ($j == 1)
                                    @php
                                        $start = 1;
                                        $end = 4;
                                    @endphp
                                @else
                                    @php
                                        $start = 5;
                                        $end = 8;
                                    @endphp
                                @endif
                                <div class="d-flex justify-content-between mt-3">
                                    @for ($i = $start; $i <= $end; $i++)
                                        <button type="button" class="btn btn-info picker text-center"
                                            id="picker{{ $i }}" name="duration_picker"
                                            value="{{ $i * $base }}"
                                            style="width:100px;font-size:12px">{{ $i * $base }}دقیقه
                                        </button>
                                    @endfor


                                </div>
                            @endfor
                            @can('confirm', [App\Models\Task::class, $sprint->id])
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" id="confirm" name="confirm">
                                    <label class="form-check-label">تایید</label>
                                </div>
                            @endcan

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



    <script>
        $(document).ready(function() {

            var clickButtonID;
            var columns = [{
                    data:'id'
                },
                {
                    data: 'title'
                },
                {
                    data: null,
                    render:function(data,row,full){
                        return data['description'] ? data['description'] : '-';


                    }
                },
                {
                    data: null,
                    render:function(data,row,full){
                        return data['user']['fname'] + ' '+ data['user']['lname'];


                    }
                },
                {
                    data: null,
                    render:function(data,row,full){
                        return data['duration'] + ' دقیقه'
                    }
                },
                {
                    data: 'category.name'
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
                datatable('#tbl_requirements',
                    '{{ route('sprints.tasks.index', $sprint->id) }}',
                    columns);

                    new $.fn.dataTable.FixedHeader( table );



            $('#create_update').submit(function(event) {
                event.preventDefault();
                submit_form('#create_update', clickButtonID, '{{ route('sprints.tasks.store') }}', )
                    .then(function(res) {
                        loading(false);
                        toastr["success"](res.message);
                        $('#add_requirements').modal('hide');
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
                var url = "{{ route('sprints.tasks.store') }}" + '/' + id;

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



                ajaxfunc("{{ route('sprints.tasks.store') }}" + '/' + clickButtonID,
                        'GET', '').then(function(res) {
                        loading(false);
                        if (res.message) {
                            toastr['success'](res.message)
                        }

                        $('#title').val(res['title']);
                        $('#description').val(res.description);
                        $('#duration').val(res.duration);
                        $('#category').val(res.category_id);
                        if (res.todo_date) {
                            $('#confirm').prop('checked', true);
                        } else {
                            $('#confirm').prop('checked', false);
                        }
                        $('#add_requirements').modal('show');
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
                $('#add_requirements').modal('show');

            })


            $(document).on('click', '.sprints', (e) => {
                let data_id = $(this).attr("data-id");
                window.location = '/phases/' + data_id + '/sprints';

            })

            $(document).on('click', '.picker', function() {
                $('#duration').val($(this).val())
            })



        });
    </script>
@endsection
