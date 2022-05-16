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
                <li class="list-group-item"><label>پروژه: </label><span class="card-title"> {{ $project->title }}</span></li>
                <li class="list-group-item"><label>زمان شروع: </label><span class="card-text"> {{  \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($project->start_date)) }}</span></li>
                <li class="list-group-item"><label>پیش بینی زمان پایان:  </label><span class="card-text"> {{  \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($project->end_date_pre)) }}</span></li>
                <!-- <li class="list-group-item"><label>سطح: </label><span class="card-text">{{ $project->level }}</span></li> -->
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>کاربران: </h5>
                            <ul class="list-group list-group-flush">
                                @for($i=0; $i< count($users); $i++)
                                
                                    @if($users[$i]->title ==0)
                                        <li class="list-group-item list-group-item-primary">{{ 'مدیر گروه: ' . $users[$i]->fname . ' ' . $users[$i]->lname }}</li>
                                    @else    
                                        <li class="list-group-item"><span class="badge bg-primary rounded-pill">{{$i}}</span> {{  $users[$i]->fname . ' ' . $users[$i]->lname }}</li>
                                    @endif
                                @endfor
                            </ul>
                        </div>
                        <div class="col-md-6">
                            @if (count($sub_projects)>0) 
                            <h5> زیر پروژه ها: </h5>
                               
                                <ul class="list-group list-group-flush">
                                    @for($i=0; $i< count($sub_projects); $i++)  
                                        <li class="list-group-item"><a href="/project/{{$sub_projects[$i]->id}}" target="_blank" ><span class="badge bg-success rounded-pill">{{$i+1}}</span> {{  $sub_projects[$i]->title }}</a></li>
                                    @endfor
                                </ul>
                            @endif
                        </div>
                        
                    </div>
                </li>
                @if (count($tasks)>0) 
                    <li class="list-group-item"><h5> فعالیت ها: </h5>
                        <ul class="list-group list-group-flush">
                            @for($i=0; $i< count($tasks); $i++)  
                                <li class="list-group-item" style="background-color:{{$tasks[$i]->status == 1 ? '#eee' :'unset'}}">
                                    <div class="row">
                                        <div class="col-md-2"><span class="badge bg-warning rounded-pill">{{$i+1}}</span> {{  $tasks[$i]->title }}</div> 
                                        <div class="col-md-3">انجام دهنده: {{  $tasks[$i]->username }}</div>
                                        <div class="col-md-2">زمان شروع:  {{  \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($tasks[$i]->start_date)) }}</div>
                                        <div class="col-md-2">تخمین زمان پایان: {{  \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($tasks[$i]->end_date_pre)) }}</div>
                                        <div class="col-md-3"> {!! $tasks[$i]->status==1?'زمان خاتمه:' .  \Morilog\Jalali\CalendarUtils::strftime("H:m - Y/m/d", strtotime($tasks[$i]->time_do)) :'<button class="btn btn-success btnDone " data-name="' .$tasks[$i]->title. '" data-id="' .$tasks[$i]->id. '"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-square" viewBox="0 0 16 16">
                                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                            <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>
                                            </svg> اتمام فعالیت</button>' !!}</div>
                                    </div>
                                </li>
                            @endfor
                        </ul>
                    </li>
                @endif
            </ul>
            <a href="/project" class="btn btn-primary" style=" float:left">برگشت به لیست پروژه ها</a>
        </div>
        <div class="card-footer text-muted align-left">
            {{  \Morilog\Jalali\CalendarUtils::strftime('H:i:s Y-m-d', strtotime($project->created_at)) }}
        </div>
    </div>
@stop
@section('scripts')
<script>
$(document).on("click", ".btnDone", function () {
    let _id = $(this).attr("data-id");
    let _name = $(this).attr("data-name");
    Confirm('توجه', '40%', 'BackToHome|5000',"فعالیت " + _name + ' خاتمه یافت؟', {
        BackToHome: {
            text: 'بازگشت به صفحه اصلی',
            btnClass: 'btn-' + 'red',
            action: function () {
                return;
            }
        }, OK: {
            text: 'ثبت', btnClass: 'btn-' + 'green', action: function () {
                $.ajax({
                    type: "POST",
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    url: "/task/setDoneTask",
                    async: false,
                    data: {_token: $("input[name=_token]").val(), id: _id, type:1 },
					success: function (data) {
							Confirm('تایید', '45%', '', "فعالیت " + _name + " خاتمه یافت.", {
								tryAgain: {
									text: 'تایید',
									btnClass: 'btn-' + 'green',
									action: function () {
                                        location.reload();
									}
								}
							}, 'green');
						},
					error: function (data) {
						//this function called in return, but controller destroy function do what we want and 
					   //record deleted
						console.log(data);
						if(data.status == 403)
							$(".alert-info").html("عدم دسترسی!");
					}

            	})
			}
        }
    }, 'red');
});
</script>
@endsection