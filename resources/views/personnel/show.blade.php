@extends('layouts.default')
@section('content')

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                                نمایش اطلاعات                   
                    <span style="font-size:20px;font-wight:900">{{$personnel->sex==0? 'آقا' : 'خانم' . ' ' . $personnel->fname . ' ' . $personnel->lname }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">   
                <li class="list-group-item"><label>کد ملی: </label><span class="card-title">{{ $personnel->mellicode }}</span></li>
                <li class="list-group-item"><label>شماره همراه اول: </label><span class="card-text">{{ $personnel->mobile1 }}</span></li>
                <li class="list-group-item"><label>شماره همراه دوم: </label><span class="card-text">{{ $personnel->mobile2 }}</span></li>
                <li class="list-group-item"><label>پست الکترونیک: </label><span class="card-text">{{ $personnel->email }}</span></li>
                <li class="list-group-item"><label>آدرس: </label><span class="card-text">{{ $personnel->address }}</span></li>
            </ul>
            <a href="/personnel" class="btn btn-primary" style=" float:left">برگشت به لیست پرسنل</a>
        </div>
        <div class="card-footer text-muted align-left">
            {{  \Morilog\Jalali\CalendarUtils::strftime('H:i:s Y-m-d', strtotime($personnel->created_at)) }}
        </div>
    </div>
@stop
