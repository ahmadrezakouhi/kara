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
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlAddTaskTitle">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            افزودن عنوان فعالیت
            </button>
        </div>
    </div>
    <table id="tbl-task-title" class="table table-striped table-bordered">
        <thead>
        </thead>
        <tbody>
       
        </tbody>
    </table>

    <div class="modal fade" tabindex="-1" aria-labelledby="mdlAddTaskModalLabel" aria-hidden="true" id="mdlAddTaskTitle">
        <div class="modal-dialog ">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">افزودن عنوان فعالیت </h5>
                <button type="button" class="btn-close close-mdl" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($errors->has('title'))
                    <div  class="alert alert-danger">{{ $errors->first('title') }}</div>
                @endif
            
                <form method="POST" id="frmTask">
                    @csrf
        
                    <div class="row">
                        <x-label class="col-sm-2 col-form-label" for="title" :value="__('عنوان')" />
                        <div class="col-md-10">
                            <x-input id="title" class="form-control" type="text" name="title"  />
                        </div>
                    </div>
        
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnAddTaskTitle" data-id="">ثبت</button>
                <button type="button" class="btn btn-secondary close-mdl" data-bs-dismiss="modal">انصراف</button>
            </div>
            </div>
        </div>
    </div>


@stop

@section('scripts')
<script>
        var myModalTaskTitle = new bootstrap.Modal(document.getElementById('mdlAddTaskTitle'), {});
</script>
   <script src="{{ asset('/js/pages/taskTitle.js') }}"></script>
@endsection

