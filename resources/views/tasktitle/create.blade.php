@extends('layouts.default')
@section('content')

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/task">عنوان فعالیت ها</a></li>
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
    
    <div class="border border-1 rounded-1 p-3">
    <div class="row p-2">
            <div class="col-md-12">
        {{ Form::open(array('url' => 'taskTitle')) }}
            <div class="row p-2">
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" for="title" :value="__('عنوان')" />
                        <div class="col-md-9">
                            <x-input id="title" class="form-control" type="text" name="title"  />
                        </div>
                    </div>
                </div>               
            </div>
        
            <div class="row p-2">
            <div class="col-md-12">
            {{ Form::submit('ثبت عنوان فعالیت!', array('class' => 'btn btn-primary')) }}
            </div></div>
        {{ Form::close() }}
    </div></div></div>
@stop

@section('scripts')
   <script src="{{ asset('/js/pages/tasktitle.js') }}"></script>
@endsection
