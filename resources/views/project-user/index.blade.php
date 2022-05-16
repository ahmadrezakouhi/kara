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
                    <label for="title" class="col-sm-5 col-form-label">عنوان</label>
                    <div class="col-sm-7">
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
    <table id="tbl-project" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>شناسه</th>
                <th>عنوان</th>
                <th>زمان شروع</th>
                <th>پیش بینی زمان پایان</th>
                <th>سطح</th>
                <th>سرگروه</th>
                <th>توضیحات</th>
            </tr>
        </thead>
        <tbody>
       
        </tbody>
    </table>

@stop

@section('scripts')
   <script src="{{ asset('/js/pages/project.js') }}"></script>
@endsection

