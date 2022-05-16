@extends('layouts.default')
@section('content')

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/personnel">کارمندان</a></li>
    <li class="breadcrumb-item active" aria-current="page">ویرایش</li>
  </ol>
</nav>

    <!-- if there are creation errors, they will show here -->
    <div class="border border-1 rounded-1 p-3">
        {{ Form::model($personnel, array('route' => array('personnel.update', $personnel->id), 'method' => 'PUT')) }}

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
                <div class="col-md-2">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label"  for="mellicode" :value="__('کدملی')" />
                        <div class="col-md-9">
                            {{ Form::tel('mellicode', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label" :value="__('شماره همراه اول')" />
                        <div class="col-md-7">
                            {{ Form::tel('mobile1', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label" for="mobile2" :value="__('شماره همراه دوم')" />
                        <div class="col-md-7">
                            {{ Form::tel('mobile2', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" for="gender" :value="__('جنسیت')" />
                        <div class="col-md-9">
                            <div class="form-check form-check-inline">
                                {{ Form::radio('sex', "0") }}
                                <label class="form-check-label" for="sex">مرد</label>
                            </div>
                            <div class="form-check form-check-inline">
                                {{ Form::radio('sex', "1") }}
                                <label class="form-check-label" for="sex">زن</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" for="gender" :value="__('وضعیت')" />
                        <div class="col-md-9">
                            <div class="form-check form-check-inline">
                                {{ Form::radio('status', "0") }}
                                <label class="form-check-label" for="status">غیرفعال</label>
                            </div>
                            <div class="form-check form-check-inline">
                                {{ Form::radio('status', "1") }}
                                <label class="form-check-label" for="status">فعال</label>
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
                <div class="col-md-7">
                    <div class="row">
                        <x-label class="col-sm-1 col-form-label" for="adderss" :value="__('آدرس')" />
                        <div class="col-md-11">
                            {{ Form::text('address', null, array('class' => 'form-control')) }}  
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-md-12">
                    {{ Form::submit('ویرایش اطلاعات کارمند!', array('class' => 'btn btn-primary')) }}
                </div>
            </div>

        {{ Form::close() }}
    </div>
@stop

@section('scripts')
   <script src="{{ asset('/js/pages/personnel.js') }}"></script>
@endsection
