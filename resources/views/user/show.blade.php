@extends('layouts.default')
@section('content')

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                                نمایش اطلاعات                   
                    <span style="font-size:20px;font-wight:900">{{$user->fname . ' ' . $user->lname }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">   
                <li class="list-group-item"><label>نوع کاربری: </label>
                    
                        @if ($user->role == 'manager')
                            <span class="card-text">ارشد</span>
                        @elseif ($user->role == 'admin')
                            <span class="card-text">مدیر</span>
                        @else
                            <span class="card-text">کارمند</span>
                        @endif
                    
                </li>
                <li class="list-group-item"><label>شماره همراه: </label><span class="card-text">{{ $user->mobile }}</span></li>
                <li class="list-group-item"><label>شماره ثابت: </label><span class="card-text">{{ $user->phone }}</span></li>
                <li class="list-group-item"><label>پست الکترونیک: </label><span class="card-text">{{ $user->email }}</span></li>
            </ul>
            <a href="/user" class="btn btn-primary" style=" float:left">برگشت به لیست کاربران</a>
        </div>
        <div class="card-footer text-muted align-left">
            {{  \Morilog\Jalali\CalendarUtils::strftime('H:i:s Y-m-d', strtotime($user->created_at)) }}
        </div>
    </div>
@stop


