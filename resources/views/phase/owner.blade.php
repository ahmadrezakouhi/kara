@extends('layouts.default')
@section('title', 'نیازمندی های پروژه')
@section('content')

    <div class="container">

        <div class="card shadow-sm mt-5 border">
            <div class="card-header ">

                <div class="d-flex justify-content-between">
                    <h2>لیست فاز ها</h2>
                </div>
            </div>
            <div class="card-body">
                <div class="row pt-3">

                </div>
                <table id="tbl_phases" class="table table-bordered table-striped">
                    <thead>
                        <th></th>
                        <th> پروژه</th>
                        <th>فاز</th>
                        <th>توضیحات</th>


                       <th>مدیر پروژه</th>

                        <th>زمان شروع</th>
                        <th>زمان پایان</th>



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
                    title: 'ردیف',
                    "defaultContent": "-",
                },
                {
                    data: 'project.title'
                },
                {
                    data: 'title'
                },
                {
                    data: 'description'
                },

                {
                    data: null,
                    render:function(data,type,row){
                        if(data['project']['users'].length > 0){
                            return data['project']['users'][0]['fname']
                            +' '
                            +data['project']['users'][0]['lname'];
                        }else {
                            return '-';
                        }
                    }
                },

                {
                    data: null,
                    render: function(data, type, row) {
                        return covertJalaliToGregorian(data['start_date']);

                    },
                    "visible": true,
                    responsivePriority: 2,
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return covertJalaliToGregorian(data['end_date']);

                    },
                    "visible": true,
                    responsivePriority: 2,
                },

            ];
            var table =
                datatable('#tbl_phases',
                    "{{ route('phases.owner') }}",
                    columns, false);


















        });
    </script>
@endsection
