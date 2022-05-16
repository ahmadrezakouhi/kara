@extends('layouts.default')
@section('content')

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/personel">کارمندان</a></li>
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
    @if($errors->has('address'))
        <div  class="alert alert-danger">{{ $errors->first('address') }}</div>
    @endif

    <div class="border border-1 rounded-1 p-3">
    <div class="row p-2">
            <div class="col-md-12">
        {{ Form::open(array('url' => 'personnel')) }}
            <div class="row p-2">
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" for="fname" :value="__('نام')" />
                        <div class="col-md-9">
                            <x-input id="fname" class="form-control" type="text" name="fname"  />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label"  for="lname" :value="__('نام خانوادگی')" />
                        <div class="col-md-9">
                            <x-input id="lname" class="form-control" type="text" name="lname"  />
                        </div>
                    </div>    
                </div>
                <div class="col-md-2">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label"  for="mellicode" :value="__('کدملی')" />
                        <div class="col-md-9">
                            <x-input id="mellicode" class="form-control" type="text" name="mellicode" max-length="10"  />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label" :value="__('شماره همراه اول')" />
                        <div class="col-md-7">
                            <x-input id="mobile1" class="form-control" type="text" name="mobile1" max-length="11" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label" for="mobile2" :value="__('شماره همراه دوم')" />
                        <div class="col-md-7">
                            <x-input id="mobile2" class="form-control" type="text" name="mobile2"  max-length="11" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" for="gender" :value="__('جنسیت')" />
                        <div class="col-md-9">
                            <div class="form-check form-check-inline">
                                <x-input class="form-check-input" type="radio" name="sex" value="0"/>
                                <label class="form-check-label" for="sex">مرد</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <x-input class="form-check-input" type="radio" name="sex" value="1"/>
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
                                <x-input class="form-check-input" type="radio" name="status" value="0"/>
                                <label class="form-check-label" for="status">غیر فعال</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <x-input class="form-check-input" type="radio" name="status" value="1"/>
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
                            <x-input id="email" class="form-control" type="email" name="email" />
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <x-label class="col-sm-1 col-form-label" for="adderss" :value="__('آدرس')" />
                        <div class="col-md-11">
                            <x-input id="address" class="form-control" type="text" name="address" />
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="row p-2">
            <div class="col-md-12">
            {{ Form::submit('ثبت کارمند!', array('class' => 'btn btn-primary')) }}
            </div></div>
        {{ Form::close() }}
    </div></div></div>
@stop

@section('scripts')
   <script src="{{ asset('/js/pages/personnel.js') }}"></script>
@endsection
