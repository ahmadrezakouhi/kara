@extends('layouts.default')
@section('content')

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                                نمایش اطلاعات                   
                    <span style="font-size:20px;font-wight:900">{{' فعالیت ' . $task->title }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">   
                <li class="list-group-item"><label>عنوان فعالیت: </label><span class="card-title"> {{ $task->title }}</span></li>
                <li class="list-group-item"><label>زمان شروع: </label><span class="card-text"> {{  \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($task->start_date)) }}</span></li>
                <li class="list-group-item"><label>پیش بینی زمان پایان:  </label><span class="card-text"> {{  \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($task->end_date_pre)) }}</span></li>
                <li class="list-group-item"><label>پروژه: </label><span class="card-text"> {{ $task->project_title }}</span></li>
                <li class="list-group-item"><label>انجام دهنده: </label><span class="card-text"> {{ $task->username }}</span></li>
            </ul>
            <a href="/task" class="btn btn-primary" style=" float:left">برگشت به لیست فعالیت ها</a>
        </div>
        <div class="card-footer text-muted align-left">
            {{  \Morilog\Jalali\CalendarUtils::strftime('H:i:s Y-m-d', strtotime($task->created_at)) }}
        </div>
    </div>
@stop
