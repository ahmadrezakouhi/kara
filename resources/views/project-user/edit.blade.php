@extends('layouts.default')
@section('content')

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/project">پروژه ها</a></li>
    <li class="breadcrumb-item active" aria-current="page">ویرایش</li>
  </ol>
</nav>

    <!-- if there are creation errors, they will show here -->
    <div class="border border-1 rounded-1 p-3">
        {{ Form::model($project, array('route' => array('project.update', $project->id), 'method' => 'PUT')) }}

            <div class="row p-2">
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" for="title" :value="__('عنوان')" />
                        <div class="col-md-9">
                            {{ Form::text('title', null, array('class' => 'form-control')) }}

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label"  for="start_date" :value="__('زمان شروع')" />
                        <div class="col-md-7">
                            <input name="start_date" type="text" class="form-control"  value="{{\Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($project->start_date))}}" />
                        </div>
                    </div>    
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label"  for="end_date_pre" :value="__('پیش بینی زمان پایان')" />
                        <div class="col-md-7">
                            <input name="end_date_pre" type="text" class="form-control"  value="{{\Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($project->end_date_pre))}}" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-md-2">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" :value="__('سطح')" />
                        <div class="col-md-7">
                            {{ Form::number('level', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" :value="__('سرگروه')" />
                        <div class="col-md-7">
                            {{ Form::number('parent_level', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <x-label class="col-sm-1 col-form-label" for="description" :value="__('توضیحات')" />
                        <div class="col-md-11">
                            {{ Form::text('description', null, array('class' => 'form-control')) }}  
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            
            <div class="row p-2">
                <div class="col-md-12">
                    <button class="btn btn-primary" type="submit">ویرایش اطلاعات پروژه!</button>
                </div>
            </div>

        {{ Form::close() }}
    </div>
@stop

@section('scripts')
   <script src="{{ asset('/js/pages/project.js') }}"></script>
@endsection
