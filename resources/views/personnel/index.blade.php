@extends('layouts.default')
@section('content')

    <!-- <h1>All the personnel</h1> -->

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    <form id='sf' action="getData" method = "POST">
        @csrf
        <div class="row pt-3">
            <div class="col-md-3">
                <div class="mb-3 row">
                    <label for="name" class="col-sm-5 col-form-label">نام و نام خانوادگی</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="search-name" name="search-name">
                    </div>
                </div>
  
            </div>
            <div class="col-md-3">
                <div class="mb-3 row">
                    <label for="mellicode" class="col-sm-5 col-form-label">کد ملی</label>
                    <div class="col-sm-7">
                        <input type="tel" class="form-control" id="search-mellicode" name="search-mellicode">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-primary mb-3" id="btn-filter">جستجو</button>
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </form>
    <table id="tbl-personnel" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>شناسه</th>
                <th>نام و نام خانوادگی</th>
                <th>کد ملی</th>
                <th>همراه اول</th>
                <th>همراه دوم</th>
                <th>آدرس</th>
                <th>وضعیت</th>
            </tr>
        </thead>
        <tbody>
       
        </tbody>
    </table>

@stop

@section('scripts')
   <script src="{{ asset('/js/pages/personnel.js') }}"></script>
@endsection

