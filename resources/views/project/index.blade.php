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
                            <label for="title" class="col-sm-5 col-form-label">زمان شروع از:</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="search-start-date" name="search-start-date">
                            </div>
                            <label  class="col-sm-1 col-form-label">تا</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="search-start-date-to" name="search-start-date-to">
                            </div>
                        </div>
        
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 row">
                            <label for="title" class="col-sm-5 col-form-label">تخمین زمان پایان از:</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="search-end-date" name="search-end-date">
                            </div>
                            <label  class="col-sm-1 col-form-label">تا</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="search-end-date-to" name="search-end-date-to">
                            </div>
                        </div>
        
                    </div>
                </div>                
                <div class="row pt-3">
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="title" class="col-sm-2 col-form-label">عنوان:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="search-title" name="search-title">
                            </div>
                        </div>
        
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label" for="title" >پروژه ارشد</label>
                            <div class="col-md-8">
                                <select class="selectpicker"  data-live-search="true" id="search-parent-id" name="search-parent-id"> 
                                
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary mb-3" id="btn-filter">جستجو</button>
                    </div>
                </div>           
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            </form>
        </div>
        <div class="col-md-2 pt-3 align-left">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlAddProject">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            افزودن پروژه
            </button>
        </div>
    </div>
    <table id="tbl-project" class="table table-striped table-bordered">
        <thead>
            <!-- <tr> -->
                <!-- <th>شناسه</th> -->
                <!-- <th>عنوان</th>
                <th>زمان شروع</th>
                <th>پیش بینی زمان پایان</th> -->
                <!-- <th>سطح</th> -->
                <!-- <th>سرگروه</th>
                <th>توضیحات</th> -->
            <!-- </tr> -->
        </thead>
        <tbody>
       
        </tbody>
    </table>

    <div class="modal fade" tabindex="-1" aria-labelledby="mdlAddProjectModalLabel" aria-hidden="true" id="mdlAddProject">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">افزودن پروژه </h5>
                <button type="button" class="btn-close close-mdl" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <form method="POST" id="frmProject">
                    @csrf
                    <div class="row p-2">
                        <x-label class="col-sm-2 col-form-label" for="title" :value="__('عنوان')" />
                        <div class="col-md-10">
                            <x-input id="titleProject" class="form-control" type="text" name="titleProject"  />
                        </div>
                    </div>
                    <div class="row p-2">
                        <x-label class="col-sm-2 col-form-label" for="title" :value="__('پروژه ارشد')" />
                        <div class="col-md-10">
                            
                         <select class="selectpicker"  data-live-search="true" id="project_id" name="project_id"> 
                            </select>
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-md-6">
                            <div class="row">
                                <x-label class="col-sm-5 col-form-label"  for="start_date" :value="__('زمان شروع')" />
                                <div class="col-md-7">
                                    <x-input id="start_date" class="form-control" type="text" name="start_date"  />
                                </div>
                            </div>    
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <x-label class="col-sm-5 col-form-label"  for="end_date_pre" :value="__('تخمین پایان')" />
                                <div class="col-md-7">
                                    <x-input id="end_date_pre" class="form-control" type="text" name="end_date_pre" max-length="10"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <x-label class="col-sm-2 col-form-label" for="description" :value="__('توضیحات')" />
                        <div class="col-md-10">
                            <x-input id="descriptionProject" class="form-control" type="text" name="descriptionProject" />
                        </div>
                    </div>

                   
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnAddProject" data-id="">ثبت</button>
                <button type="button" class="btn btn-secondary close-mdl" data-bs-dismiss="modal">انصراف</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" aria-labelledby="mdlAddUsersModalLabel" aria-hidden="true" id="mdlAddUsers">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">افزودن اعضا <span class="title-project"  style="font-size: 1.25rem; color: var(--mdb-teal); font-weight:900"></span></h5>
                <button type="button" class="btn-close close-mdl" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('register') }}">
                        @csrf
                     
                        <div class="row">
                            <div class="col-md-10">
                                <table id="tbl-user" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>شناسه</th>
                                            <th>مدیر</th>
                                            <th>اعضا</th>
                                            <!-- <th>سمت</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                
                                    </tbody>
                                </table>
                            </div>
                        </div>
                           
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnAddUser" data-id="">ثبت</button>
                <button type="button" class="btn btn-secondary close-mdl" data-bs-dismiss="modal">انصراف</button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" aria-labelledby="mdlAddParentProjectModalLabel" aria-hidden="true" id="mdlAddParentProject">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">افزودن پروژه ارشد</h5>
                <button type="button" class="btn-close close-mdl" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                        @csrf
                     
                        <div class="row">
                            <div class="col-md-10">
                                <select class="selectpicker" data-live-search="true" id="parent-level"> </select>

                            </div>
                        </div>
                           
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnAddParentProject" data-id="">ثبت</button>
                <button type="button" class="btn btn-secondary close-mdl" data-bs-dismiss="modal">انصراف</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" aria-labelledby="mdlAddTaskProjectModalLabel" aria-hidden="true" id="mdlAddTaskProject">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">افزودن فعالیت کاربران پروژه <span class="title-project" style="font-size: 1.25rem; color: var(--mdb-teal); font-weight:900"></span></h5>
                <button type="button" class="btn-close close-mdl" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="frmTaskProject">
                    @csrf
                     
                    <div class="row p-2">
                        <div class="col-md-12">
                            <div class="row">
                                <x-label class="col-sm-2 col-form-label" for="title" :value="__('عنوان')" />
                                <div class="col-md-10">
                                    <x-input id="title_task" class="form-control" type="text" name="title_task"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-md-6">
                            <div class="row">
                                <x-label class="col-sm-4 col-form-label"  for="start_date" :value="__('شروع')" />
                                <div class="col-md-7">
                                    <x-input id="start_date_task" class="form-control" type="text" name="start_date_task"  />
                                </div>
                            </div>    
                        </div>                   
                        <div class="col-md-6">
                            <div class="row">
                                <x-label class="col-sm-5 col-form-label"  for="end_date" :value="__('تخمین پایان')" />
                                <div class="col-md-7">
                                    <x-input id="end_date_task" class="form-control" type="text" name="end_date_task" max-length="10"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-2">
                                   
                        <div class="col-md-8">
                            <div class="row">
                                <x-label class="col-sm-3 col-form-label" for="project_id" :value="__('کاربر')" />
                                <div class="col-md-9">
                                    <select class="selectpicker" data-live-search="true"  name="userid_task"  id="userid_task"> 
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
                                    <x-input id="description_task" class="form-control" type="text" name="description_task" />
                                </div>
                            </div>
                        </div>
                    </div>
                           
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnAddTaskProject" data-id="">ثبت</button>
                <button type="button" class="btn btn-secondary close-mdl" data-bs-dismiss="modal">انصراف</button>
            </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script> var _role = "<?php

        use Illuminate\Support\Facades\Auth;

        echo Auth::user()->role; ?>" ;
    </script>
    <script>
        var myModalUser = new bootstrap.Modal(document.getElementById('mdlAddUsers'), {});
        var myModalParentProject = new bootstrap.Modal(document.getElementById('mdlAddParentProject'), {});
        var myModalTaskProject = new bootstrap.Modal(document.getElementById('mdlAddTaskProject'), {});
        var myModalProject = new bootstrap.Modal(document.getElementById('mdlAddProject'), {});
    </script>
   <script src="{{ asset('/js/pages/project.js') }}"></script>
@endsection

