@extends('layouts.default')
@section('content')

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/task">فعالیت ها</a></li>
    <li class="breadcrumb-item active" aria-current="page">ویرایش</li>
  </ol>
</nav>

    <!-- if there are creation errors, they will show here -->
    <div class="border border-1 rounded-1 p-3">
        {{ Form::model($task, array('route' => array('task.update', $task->id), 'method' => 'PUT')) }}
            <script> 
                 _start= "{{\Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($task->start_date))}}";
                 _end= "{{\Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($task->end_date_pre))}}";
             </script>
            <div class="row p-2">
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label" for="title" :value="__('دسته بندی')" />
                        <div class="col-md-7">
                            <select class="selectpicker" data-live-search="true"  name="category_id"> 
                                @for($i=0; $i < count($taskTitles) ; $i++)
                                    @if( $taskTitles[$i]->id == $task->category_id)
                                    <option value='{{ $taskTitles[$i]->id }}' selected > {{ $taskTitles[$i]->title }} </option>
                                    @else
                                    <option value='{{ $taskTitles[$i]->id }}'> {{ $taskTitles[$i]->title }} </option>
                                    @endif
                                @endfor;
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <x-label class="col-sm-2 col-form-label" for="title" :value="__('عنوان')" />
                        <div class="col-md-10">
                            {{ Form::text('title', null, array('class' => 'form-control')) }}

                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label"  for="start_date" :value="__('زمان شروع')" />
                        <div class="col-md-7">
                            <input id="estart_date"  name="start_date" type="text" class="form-control"  />
                        </div>
                    </div>    
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label"  for="end_date_pre" :value="__('پیش بینی زمان پایان')" />
                        <div class="col-md-7">
                            <input id="eend_date_pre" name="end_date_pre" type="text" class="form-control"   />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-2">
                <!-- <div class="col-md-2">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" :value="__('سطح')" />
                        <div class="col-md-7">
                            {{ Form::number('level', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                </div>-->
                 <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-5 col-form-label" for="project_id" :value="__('پروژه')" />
                        <div class="col-md-7">
                            <select class="selectpicker" data-live-search="true"  name="project_id"> 
                                @for($i=0; $i < count($projects) ; $i++)
                                    @if( $projects[$i]->id == $task->project_id)
                                    <option value='{{ $projects[$i]->id }}' selected > {{ $projects[$i]->title }} </option>
                                    @else
                                    <option value='{{ $projects[$i]->id }}'> {{ $projects[$i]->title }} </option>
                                    @endif
                                @endfor;
                            </select>
                        </div>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="row">
                        <x-label class="col-sm-3 col-form-label" for="project_id" :value="__('کاربر')" />
                        <div class="col-md-9">
                            <select class="selectpicker" data-live-search="true"  name="userid"> 
                                @for($i=0; $i < count($users) ; $i++)
                                    @if( $users[$i]->id == $task->project_id)
                                    <option value='{{ $users[$i]->id }}' selected > {{ $users[$i]->fname . " " . $users[$i]->lname }} </option>
                                    @else
                                    <option value='{{ $users[$i]->id }}'> {{ $users[$i]->fname . " " . $users[$i]->lname }} </option>
                                    @endif
                                @endfor;
                            </select>
                        </div>
                    </div>
                </div> 
                <div class="col-md-6">
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
                    <button class="btn btn-primary" type="submit">ویرایش اطلاعات Task!</button>
                </div>
            </div>

        {{ Form::close() }}
    </div>
@stop

@section('scripts')
   <script src="{{ asset('/js/pages/task.js') }}"></script>
@endsection
