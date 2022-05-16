@extends('layouts.default')
@section('content')

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                                نمایش اطلاعات                   
                    <span style="font-size:20px;font-wight:900">{{' پروژه ' . $project->title }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">   
                <li class="list-group-item"><label>پروژه: </label><span class="card-title">{{ $project->title }}</span></li>
                <li class="list-group-item"><label>زمان شروع: </label><span class="card-text"> {{  \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($project->start_date)) }}</span></li>
                <li class="list-group-item"><label>پیش بینی زمان پایان: </label><span class="card-text">{{  \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($project->end_date_pre)) }}</span></li>
                <li class="list-group-item"><label>سطح: </label><span class="card-text">{{ $project->level }}</span></li>
                <li class="list-group-item"><label>سرگروه: </label><span class="card-text">{{ $project->parent_level }}</span></li>
            </ul>
            <a href="/project" class="btn btn-primary" style=" float:left">برگشت به لیست پروژه ها</a>
        </div>
        <div class="card-footer text-muted align-left">
            {{  \Morilog\Jalali\CalendarUtils::strftime('H:i:s Y-m-d', strtotime($project->created_at)) }}
        </div>
    </div>
@stop
