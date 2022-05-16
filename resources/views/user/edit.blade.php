@extends('layouts.default')
@section('content')

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/user">کاربران</a></li>
    <li class="breadcrumb-item active" aria-current="page">ویرایش</li>
  </ol>
</nav>

    <!-- if there are creation errors, they will show here -->
    <div class="border border-1 rounded-1 p-3">
        {{ Form::model($user, array('route' => array('user.update', $user->id), 'method' => 'PUT')) }}

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
            <div class="row p-2">
                <div class="col-md-5">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label"  for="email" :value="__('پست الکترونیک')" />
                        <div class="col-md-9">
                            {{ Form::email('email', null, array('class' => 'form-control')) }}
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
@endsection
