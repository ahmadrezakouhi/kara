@extends('layouts.default')
@section('content')

    <!-- <h1>All the personnel</h1> -->

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    <div class="row pt-3">
        <div class="col-md-10">
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
        </div>
        <div class="col-md-2 pt-3 align-left">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlAddTask">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            افزودن فعالیت
            </button>
        </div>
    </div>
    <table id="tbl-task" class="table table-striped table-bordered">
        <thead>
            <!-- <tr>
                <th>شناسه</th>
                <th>عنوان</th>
                <th>زمان شروع</th>
                <th>پیش بینی زمان پایان</th>
                 <th>سطح</th> -->
                <!-- <th>پروژه</th>
                <th>انجام دهنده</th>
                <th>زمان خاتمه</th>
                <th>توضیحات</th> -->
            <!-- </tr>  -->
        </thead>
        <tbody>
       
        </tbody>
    </table>

    <div class="modal fade" tabindex="-1" aria-labelledby="mdlAddTaskModalLabel" aria-hidden="true" id="mdlAddTask">
        <div class="modal-dialog ">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">افزودن فعالیت </h5>
                <button type="button" class="btn-close close-mdl" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            @if($errors->has('title'))
                <div  class="alert alert-danger">{{ $errors->first('title') }}</div>
            @endif
            @if($errors->has('start_date'))
                <div  class="alert alert-danger">{{ $errors->first('start_date') }}</div>
            @endif
            @if($errors->has('end_date_pre'))
                <div  class="alert alert-danger">{{ $errors->first('end_date_pre') }}</div>
            @endif
            <form method="POST" id="frmTask">
                @csrf
                <div class="row p-2">
                 <div class="col-md-12">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" for="title" :value="__('دسته بندی')" />
                        <div class="col-md-9">
                            <select class="selectpicker" data-live-search="true" id="category_id" name="category_id"> 
                            </select>
                        </div>
                    </div>
                </div>
                </div>
                <div class="row p-2">
                    <div class="col-md-12">
                        <div class="row">
                            <x-label class="col-sm-2 col-form-label" for="title" :value="__('عنوان')" />
                            <div class="col-md-10">
                                <x-input id="title" class="form-control" type="text" name="title"  />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row p-2">
                    <div class="col-md-6">
                        <div class="row">
                            <x-label class="col-sm-5 col-form-label"  for="start_date" :value="__('تاریخ شروع')" />
                            <div class="col-md-7">
                                <x-input id="start_date" class="form-control" type="text" name="start_date"  />
                            </div>
                        </div>    
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <x-label class="col-sm-5 col-form-label"  for="end_date_pre" :value="__('تخمین زمان پایان')" />
                            <div class="col-md-7">
                                <x-input id="end_date_pre" class="form-control" type="text" name="end_date_pre" max-length="10"  />
                            </div>
                        </div>
                    </div>
            </div>
            <div class="row p-2">
              
                <div class="col-md-6">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label" for="project_id" :value="__('پروژه')" />
                        <div class="col-md-7">
                            <select class="selectpicker" data-live-search="true" id="project_id" name="project_id"> 
                            </select>
                        </div>
                    </div>
                </div> 
                <div class="col-md-6">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label" for="project_id" :value="__('کاربر')" />
                        <div class="col-md-7">
                            <select class="selectpicker" data-live-search="true"  name="userid"  id="userid"> 
                            </select>
                        </div>
                    </div>
                </div> 
                </div>
                <div class="row p-2">
                <div class="col-md-12">
                    <div class="row">
                        <x-label class="col-sm-2 col-form-label" for="description" :value="__('توضیحات')" />
                        <div class="col-md-10">
                            <x-input id="description" class="form-control" type="text" name="description" />
                        </div>
                    </div>
                </div>
            </div>

           
            </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnAddTask" data-id="">ثبت</button>
                <button type="button" class="btn btn-secondary close-mdl" data-bs-dismiss="modal">انصراف</button>
            </div>
            </div>
        </div>
    </div>


@stop

@section('scripts')
<script>
        var myModalTask = new bootstrap.Modal(document.getElementById('mdlAddTask'), {});
</script>
   <script src="{{ asset('/js/pages/task.js') }}"></script>
@endsection

