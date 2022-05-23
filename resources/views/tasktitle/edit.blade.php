@extends('layouts.default')
@section('content')

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/task">عنوان فعالیت ها</a></li>
    <li class="breadcrumb-item active" aria-current="page">ویرایش</li>
  </ol>
</nav>

    <!-- if there are creation errors, they will show here -->
    <div class="border border-1 rounded-1 p-3">
        {{ Form::model($tasktitle, array('route' => array('taskTitle.update', $tasktitle->id), 'method' => 'PUT')) }}
           
            <div class="row p-2">
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" for="title" :value="__('عنوان')" />
                        <div class="col-md-9">
                            {{ Form::text('title', null, array('class' => 'form-control')) }}

                        </div>
                    </div>
                </div>
               
            </div>
            
            
            <div class="row p-2">
                <div class="col-md-12">
                    <button class="btn btn-primary" type="submit">ویرایش اطلاعات عنوان فعالیت!</button>
                </div>
            </div>

        {{ Form::close() }}
    </div>
@stop

@section('scripts')
   <script src="{{ asset('/js/pages/taskTitle.js') }}"></script>
@endsection
