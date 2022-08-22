@extends('layouts.default')
@section('title', 'نیازمندی های پروژه')
@section('content')

    <div class="container">

        <div class="card shadow-sm mt-5 border">
            <div class="card-header ">

                <div class="d-flex justify-content-between">
                    <h2>لیست اسپرینت ها</h2>
                </div>
            </div>
            <div class="card-body">
                <div class="row pt-3">

                </div>
                <table id="tbl_sprints" class="table  table-bordered border table-striped wrap" width="100%">
                    <thead>
                        <th>شماره</th>
                        <th>پروژه</th>
                        <th>فاز</th>
                        <th>عنوان</th>
                        <th>توضیحات</th>



                        <th>مدیر پروژه</th>

                        <th>زمان شروع</th>
                        <th>زمان پایان</th>
                        <th>تسک ها</th>
                        <th>وضعیت</th>


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
                    data: 'phase.project.title'
                },
                {
                    data: 'phase.title'
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
                        if (data['phase']['project']['users'].length > 0) {
                            return data['phase']['project']['users'][0]['fname'] +
                                ' ' +
                                data['phase']['project']['users'][0]['lname'];
                        } else {
                            return '-';
                        }
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

                    className: "center",
                    defaultContent: "<button class='btn btn-info tasks' >" +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-stars" viewBox="0 0 16 16">' +
                        '<path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5z"/>' +
                        '<path d="M2.242 2.194a.27.27 0 0 1 .516 0l.162.53c.035.115.14.194.258.194h.551c.259 0 .37.333.164.493l-.468.363a.277.277 0 0 0-.094.3l.173.569c.078.256-.213.462-.423.3l-.417-.324a.267.267 0 0 0-.328 0l-.417.323c-.21.163-.5-.043-.423-.299l.173-.57a.277.277 0 0 0-.094-.299l-.468-.363c-.206-.16-.095-.493.164-.493h.55a.271.271 0 0 0 .259-.194l.162-.53zm0 4a.27.27 0 0 1 .516 0l.162.53c.035.115.14.194.258.194h.551c.259 0 .37.333.164.493l-.468.363a.277.277 0 0 0-.094.3l.173.569c.078.255-.213.462-.423.3l-.417-.324a.267.267 0 0 0-.328 0l-.417.323c-.21.163-.5-.043-.423-.299l.173-.57a.277.277 0 0 0-.094-.299l-.468-.363c-.206-.16-.095-.493.164-.493h.55a.271.271 0 0 0 .259-.194l.162-.53zm0 4a.27.27 0 0 1 .516 0l.162.53c.035.115.14.194.258.194h.551c.259 0 .37.333.164.493l-.468.363a.277.277 0 0 0-.094.3l.173.569c.078.255-.213.462-.423.3l-.417-.324a.267.267 0 0 0-.328 0l-.417.323c-.21.163-.5-.043-.423-.299l.173-.57a.277.277 0 0 0-.094-.299l-.468-.363c-.206-.16-.095-.493.164-.493h.55a.271.271 0 0 0 .259-.194l.162-.53z"/>' +
                        '</svg>' +
                        "</button></div>"
                },
                {
                    responsivePriority: 0,
                    data: null,
                    render: function(data, row, type) {




                        return '<select class="form-select status" >' +

                            '<option value="0" ' + (data['status'] == 0 ? 'selected' : '') +'>متوقف</option>' +
                            '<option value="1" ' + (data['status'] == 1 ? 'selected' : '') +'>فعال</option>' +
                            '<option value="2" ' + (data['status'] == 2 ? 'selected' : '') +'>تمام شده</option>' +
                            '</select>';
                    }
                }


            ];
            var table =
                datatable('#tbl_sprints',
                    "{{ route('sprints.owner') }}",
                    columns, false);

            new $.fn.dataTable.FixedHeader(table);





            $(document).on('click', '.tasks', function(e) {
                let data_id = $(this).attr("data-id");
                window.location = '/sprints/' + data_id + '/tasks';

            })




            $(document).on('change', '.status', function() {
                var data = {
                    status: $(this).children("option:selected").val()
                };
                var sprint_id = $(this).attr('data-id');
                var url = '/sprints/' + sprint_id + '/change-status';
                ajaxfunc(url, 'POST', data).then(function(res) {
                    loading(false);
                    toastr["success"](res.message);
                    table.ajax.reload();
                }).catch(function(res) {
                    loading(false);
                    toastr['error'](res.responseJSON.message);
                    table.ajax.reload();
                });

            })






        });
    </script>
@endsection
