@extends('layouts.default')
@section('content')

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/project">پروژه ها</a></li>
    <li class="breadcrumb-item active" aria-current="page">ایجاد</li>
  </ol>
</nav>

    <!-- if there are creation errors, they will show here -->
    <!-- @if($errors->any())
        {{ implode('', $errors->all('<div>:message</div>')) }}
    @endif -->
    @if($errors->has('title'))
        <div  class="alert alert-danger">{{ $errors->first('title') }}</div>
    @endif
    @if($errors->has('start_date'))
        <div  class="alert alert-danger">{{ $errors->first('start_date') }}</div>
    @endif
    @if($errors->has('end_date_pre'))
        <div  class="alert alert-danger">{{ $errors->first('end_date_pre') }}</div>
    @endif
    <div class="border border-1 rounded-1 p-3">
    <div class="row p-2">
            <div class="col-md-12">
        {{ Form::open(array('url' => 'project')) }}
            <div class="row p-2">
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" for="title" :value="__('عنوان')" />
                        <div class="col-md-9">
                            <x-input id="title" class="form-control" type="text" name="title"  />
                        </div>
                    </div>
                </div>
               
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label"  for="start_date" :value="__('تاریخ شروع')" />
                        <div class="col-md-7">
                            <x-input id="start_date" class="form-control" type="text" name="start_date"  />
                        </div>
                    </div>    
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label"  for="end_date_pre" :value="__('تخمین زمان پایان')" />
                        <div class="col-md-7">
                            <x-input id="end_date_pre" class="form-control" type="text" name="end_date_pre" max-length="10"  />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-4 col-form-label" for="title" :value="__('پروژه ارشد')" />
                        <div class="col-md-8">
                            
                         <select class="selectpicker"  data-live-search="true" id="project_id" name="project_id"> 
                            </select>
                        </div>
                    </div> 
                </div>
                <!-- <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label" :value="__('سطح')" />
                        <div class="col-md-7">
                            <x-input id="level" class="form-control" type="number" min="0" name="level"  />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label" for="parent_level" :value="__('سرگروه')" />
                        <div class="col-md-7">
                            <x-input id="parent_level" class="form-control" type="number" min="0"  name="parent_level"  />
                        </div>
                    </div>
                </div> -->
                <div class="col-md-6">
                    <div class="row">
                        <x-label class="col-sm-2 col-form-label" for="description" :value="__('توضیحات')" />
                        <div class="col-md-10">
                            <x-input id="description" class="form-control" type="text" name="description" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row p-2">
                <div class="col-md-12">
                {{ Form::submit('ثبت پروژه!', array('class' => 'btn btn-primary')) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@stop

@section('scripts')
    <script> var _role = "<?php

use Illuminate\Support\Facades\Auth;

 echo Auth::user()->role; ?>" ;</script>
   <script src="{{ asset('/js/pages/project.js') }}"></script>
@endsection
