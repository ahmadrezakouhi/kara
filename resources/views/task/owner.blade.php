@extends('layouts.default')
@section('title', 'نیازمندی های پروژه')
@section('content')

    <div class="container">

        <div class="card shadow-sm mt-5 border">
            <div class="card-header ">

                <div class="d-flex justify-content-between">
                    <h2>لیست کارها</h2>
                </div>
            </div>
            <div class="card-body">
                <div class="row pt-3">

                </div>
                <table id="tbl_tasks" class="table  table-bordered border table-striped nowrap" width="100%">
                    <thead>
                        <th>شماره</th>
                        <th>عنوان</th>
                        <th>توضیحات</th>
                        <th>مدت زمان انجام</th>
                        <th>مجری</th>
                        <th>تایید</th>

                        <th>وضعیت</th>
                        <th>دسته بندی</th>
                        <th>عنوان اسپرینت</th>
                        <th>عنوان فاز</th>
                        <th>عنوان پروژه</th>
                        <th>مدیریت</th>


                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>



@endsection
@section('scripts')
    <script></script>
    <script src="{{ asset('js/general/functions.js') }}"></script>

    <script src="{{ asset('js/general/toastr_option.js') }}"></script>



    <script>
        $(document).ready(function() {

            var columns = [{
                    data: 'id'

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
                        return data['user']['fname'] + ' ' + data['user']['lname'];
                    }
                },
                {
                    data: null,
                    "render": function(data, type, row) {
                        if (data['todo_date'] === null) {
                            return '<svg xmlns="http://www.w3.org/2000/svg" class="text-danger"  width="32" height="32" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">' +
                                '<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>' +
                                '</svg>';

                        } else {
                            return '<svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="32" height="32" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">' +
                                '<path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>' +
                                '</svg>';
                        }
                    }
                },

                {
                    data: null,
                    render: function(data, type, row) {
                        switch (data['status']) {
                            case 0:
                                return 'در صف انتظار';
                                break;
                            case 1:
                                return 'در صف انجام';
                                break;
                            default:
                                return 'پایان';
                                break;
                        }
                    }
                },

                {
                    data: 'category.name'
                },
                {
                    data: 'sprint.title'
                },
                {
                    data: 'sprint.phase.title'
                },
                {
                    data: 'sprint.phase.project.title'
                },
                {
                    responsivePriority: 0,
                    data: null,
                    title: "مدیریت",
                    className: "center",
                    defaultContent:
                        "<button class='btn btn-outline-success accept'>" +
                        'تایید'+
                        "</button>"
                }


            ];
            var table =
                datatable('#tbl_tasks',
                    "{{ route('tasks.owner') }}",
                    columns, false);


            new $.fn.dataTable.FixedHeader(table);




                $(document).on('click','.accept',function(){
                    var id = $(this).attr('data-id');
                    var url = "tasks/"  + id+'/accept';
                    Swal.fire({
                    text: "می خواهید تسک مورد نظر تایید شود ؟",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'خیر',
                    confirmButtonText: 'بله می خواهم'
                }).then((result) => {
                    if (result.isConfirmed) {
                        ajaxfunc(url, 'POST', '').then(function(res) {
                            loading(false);
                            toastr['success'](res.message);
                            table.ajax.reload();
                        }).catch(function(res) {
                            loading(false);
                            toastr['error'](res.responseJSON.message);


                        })



                    }
                })
                })











        });
    </script>
@endsection
