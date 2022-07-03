@extends('layouts.default')
@section('content')

<nav class="shadow-sm border my-3 p-3 d-flex justify-content-between" >
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/user">کاربران</a></li>
    <li class="breadcrumb-item active" aria-current="page">ویرایش</li>
  </ol>
  <a href="{{ route('user.index') }}"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
    <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z"/>
  </svg></a>
</nav>

    <!-- if there are creation errors, they will show here -->
    <div class="border border-1 rounded-1 p-3 shadow-sm">
        {{ Form::model($user, array('route' => array('user.update', $user->id), 'method' => 'PUT')) }}
            @if( Auth::user()->role == "manager")
            <div class="row p-2">
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" for="fname" :value="__('نام')" />
                        <div class="col-md-9">
                            {{ Form::text('fname', null, array('class' => 'form-control')) }}

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label"  for="lname" :value="__('نام خانوادگی')" />
                        <div class="col-md-9">
                            {{ Form::text('lname', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                </div>

            </div>

            <div class="row p-2">
                <div class="col-md-4">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" for="background_color" :value="__('رنگ پس زمینه')" />
                        <div class="col-md-9">
                            {{ Form::text('background_color', null, array('class' => 'form-control','id'=>'background_color')) }}

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label"  for="text_color" :value="__('رنگ متن')" />
                        <div class="col-md-9">
                            {{ Form::text('text_color', null, array('class' => 'form-control','id'=>'text_color')) }}
                        </div>
                    </div>
                </div>

                <div id="preview" class="d-flex justify-content-center align-items-center rounded"
                style="font-size: 20px;width:30px;height:30px;background:{{ Auth::user()->background_color }};color:{{ Auth::user()->text_color }}">
                    A
                </div>

            </div>
            <div class="row p-2">
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label" :value="__('شماره همراه')" />
                        <div class="col-md-7">
                            {{ Form::tel('mobile', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label" for="mobile2" :value="__('تلفن ثابت')" />
                        <div class="col-md-7">
                            {{ Form::tel('phone', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <x-label class="col-sm-2 col-form-label" for="role" :value="__('نوع کاربر')" />
                        <div class="col-md-9">
                            <div class="form-check form-check-inline">
                                {{ Form::radio('role', "manager") }}
                                <label class="form-check-label" for="role">ارشد</label>
                            </div>
                            <div class="form-check form-check-inline">
                                {{ Form::radio('role', "admin") }}
                                <label class="form-check-label" for="role">مدیر</label>
                            </div>
                            <div class="form-check form-check-inline">
                                {{ Form::radio('role', "user") }}
                                <label class="form-check-label" for="role">کاربر</label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endif
            <div class="row p-2">
                @if( Auth::user()->role == "manager")
                <div class="col-md-5">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label"  for="email" :value="__('پست الکترونیک')" />
                        <div class="col-md-9">
                            {{ Form::email('email', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="form-check my-3">
                        <label class="form-check-label">
                          <input class="form-check-input" type="checkbox" name="changePassword"> تغییر رمز
                        </label>
                      </div>
                </div>
                <div class="col-md-3 password">
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

                <div class="col-md-3">
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
            {{ Form::submit('ویرایش اطلاعات کاربر!', array('class' => 'btn btn-primary')) }}
            </div>
            </div>
        {{ Form::close() }}
    </div>
@stop

@section('scripts')
   <script src="{{ asset('/js/pages/user.js') }}"></script>
   <script>
    $(document).ready(function(){
        $('#text_color').keyup(function(){
            $('#preview').css('color',$(this).val())
            console.log($(this).val())
        });
        $('#background_color').keyup(function(){
            $('#preview').css('background',$(this).val())
            console.log($(this).val())
        });
    })
   </script>
@endsection
