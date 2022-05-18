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
                    <div class="col-md-5">
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-4 col-form-label">نام و نام خانوادگی</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="search-name" name="search-name">
                            </div>
                        </div>
        
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3 row">
                            <label for="mellicode" class="col-sm-5 col-form-label">تلفن همراه</label>
                            <div class="col-sm-7">
                                <input type="tel" class="form-control" maxlength="11" id="search-mobile" name="search-mobile">
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
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlAddUser">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            افزودن کاربر
            </button>
        </div>
    </div>
    <table id="tbl-user" class="table table-striped table-bordered">
        <thead>
            <tr>
                <!-- <th>شناسه</th> -->
                <th>نام و نام خانوادگی</th>
                <th>تلفن همراه</th>
                <th>تلفن ثابت</th>
                <th>ایمیل</th>
                <th>نوع کاربری</th>
                <th>مدیریت</th>
            </tr>
        </thead>
        <tbody>
       
        </tbody>
    </table>

    <div class="modal fade" tabindex="-1" aria-labelledby="mdlAddUserModalLabel" aria-hidden="true" id="mdlAddUser">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">افزودن کاربر </h5>
                <button type="button" class="btn-close close-mdl" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            @if($errors->has('fname'))
                <div  class="alert alert-danger">{{ $errors->first('fname') }}</div>
            @endif
            @if($errors->has('lname'))
                <div  class="alert alert-danger">{{ $errors->first('lname') }}</div>
            @endif
            @if($errors->has('email'))
                <div  class="alert alert-danger">{{ $errors->first('email') }}</div>
            @endif
            @if($errors->has('mobile'))
                <div  class="alert alert-danger">{{ $errors->first('mobile') }}</div>
            @endif
            <form method="POST" id="frmUser">
                    @csrf
                    <div class="row p-2">
                        <div class="col-md-6">
                            <div class="row">
                                <x-label class="col-sm-3 col-form-label" for="fname" :value="__('نام')" />
                                <div class="col-md-9">
                                    <x-input id="fname" class="form-control" type="text" name="fname" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <x-label class="col-sm-3 col-form-label"  for="lname" :value="__('نام خانوادگی')" />
                                <div class="col-md-9">
                                    <x-input id="lname" class="form-control" type="text" name="lname" required />
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-md-4">
                            <div class="row">
                                <x-label class="col-sm-5 col-form-label" :value="__('شماره همراه')" />
                                <div class="col-md-7">
                                    <x-input id="mobile" class="form-control" type="tel" name="mobile" max-length="11" required/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <x-label class="col-sm-5 col-form-label" for="phone" :value="__('شماره ثابت')" />
                                <div class="col-md-7">
                                    <x-input id="phone" class="form-control" type="tel" name="phone"  max-length="11" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-md-6">
                            <div class="row">
                                <x-label class="col-sm-3 col-form-label" for="gender" :value="__('نوع کاربر')" />
                                <div class="col-md-9">
                                    <div class="form-check form-check-inline">
                                        <x-input class="form-check-input" type="radio" name="role" value="manager"/>
                                        <label class="form-check-label" for="role">ارشد</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <x-input class="form-check-input" type="radio" name="role" value="admin"/>
                                        <label class="form-check-label" for="role">مدیر</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <x-input class="form-check-input" type="radio" name="role" value="user" checked/>
                                        <label class="form-check-label" for="user">کاربر</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row p-2">
                        <div class="col-md-6">
                            <div class="row">
                                <x-label class="col-sm-3 col-form-label"  for="password" :value="__('رمز ورود')" />
                                <div class="col-md-9">
                                    <x-input id="password" class="form-control"
                                                type="password"
                                                name="password"
                                                required autocomplete="new-password" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <x-label class="col-sm-3 col-form-label"  for="password_confirmation" :value="__('تکرار رمز ')" />
                                <div class="col-md-9">
                                    <x-input id="password_confirmation" class="form-control"
                                                type="password"
                                                name="password_confirmation"/>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row p-2">
                        <div class="col-md-12">
                            <div class="row">
                                <x-label class="col-sm-2 col-form-label"  for="email" :value="__('پست الکترونیک')" />
                                <div class="col-md-10">
                                    <x-input id="email" class="form-control" type="email" name="email" />
                                </div>
                            </div>
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

@stop

@section('scripts')
    <script>
        var _role = "<?php echo Auth::user()->role ?>";
        var myModalUser = new bootstrap.Modal(document.getElementById('mdlAddUser'), {});

    </script>
   <script src="{{ asset('/js/pages/user.js') }}"></script>
@endsection
