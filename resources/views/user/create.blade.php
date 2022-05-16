@extends('layouts.default')
@section('content')

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/user">کاربران</a></li>
    <li class="breadcrumb-item active" aria-current="page">ایجاد</li>
  </ol>
</nav>

    <!-- if there are creation errors, they will show here -->
    <!-- @if($errors->any())
        {{ implode('', $errors->all('<div>:message</div>')) }}
    @endif -->
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

    <div class="border border-1 rounded-1 p-3">
    {{ Form::open(array('url' => 'user')) }}
        <div class="row p-2">
            <div class="col-md-3">
                <div class="row">
                    <x-label class="col-sm-3 col-form-label" for="fname" :value="__('نام')" />
                    <div class="col-md-9">
                        <x-input id="fname" class="form-control" type="text" name="fname" required />
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <x-label class="col-sm-3 col-form-label"  for="lname" :value="__('نام خانوادگی')" />
                    <div class="col-md-9">
                        <x-input id="lname" class="form-control" type="text" name="lname" required />
                    </div>
                </div>    
            </div>
        </div>
        <div class="row p-2">
            <div class="col-md-3">
                <div class="row">
                    <x-label class="col-sm-5 col-form-label" :value="__('شماره همراه')" />
                    <div class="col-md-7">
                        <x-input id="mobile" class="form-control" type="tel" name="mobile" max-length="11" required/>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <x-label class="col-sm-5 col-form-label" for="phone" :value="__('شماره ثابت')" />
                    <div class="col-md-7">
                        <x-input id="phone" class="form-control" type="tel" name="phone"  max-length="11" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <x-label class="col-sm-2 col-form-label" for="gender" :value="__('نوع کاربر')" />
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
            <div class="col-md-5">
                <div class="row">
                    <x-label class="col-sm-3 col-form-label"  for="email" :value="__('پست الکترونیک')" />
                    <div class="col-md-9">
                        <x-input id="email" class="form-control" type="email" name="email" />
                    </div>
                </div>
            </div>
             <div class="col-md-3">
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
                {{ Form::submit('ثبت پرسنل!', array('class' => 'btn btn-primary')) }}
            </div>
        </div>


    {{ Form::close() }}
    </div>

@stop

@section('scripts')
   <script src="{{ asset('/js/pages/user.js') }}"></script>
@endsection