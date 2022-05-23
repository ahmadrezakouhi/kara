@extends('layouts.default')
@section('content')

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                                نمایش اطلاعات                   
                    <span style="font-size:20px;font-wight:900">{{' فعالیت عنوان' . $taskTitle->title }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">   
                <li class="list-group-item"><label>عنوان فعالیت: </label><span class="card-title"> {{ $taskTitle->title }}</span></li>
               
            </ul>
            <a href="/taskTitle" class="btn btn-primary" style=" float:left">برگشت به لیست عنوان فعالیت ها</a>
        </div>
        <div class="card-footer text-muted align-left">
            {{  \Morilog\Jalali\CalendarUtils::strftime('H:i:s Y-m-d', strtotime($taskTitle->created_at)) }}
        </div>
    </div>
@stop
