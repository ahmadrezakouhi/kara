@extends('layouts.default')
@section('title', 'تسک بورد')
@section('content')


    <div class="container border mt-5">
        <div class="row card-group">
            <!-- <div class="card-group"> -->
            <div class="col-lg-2 p-0 card border" style="min-height:100vh ;">
                <div class="card-header persian text-center">
                    در صف انتظار
                </div>
                <div class="card-body" style="">
                    <ul class="list-group  sortable droppable p-0" id="todo" style="width:100%;height: 100% ;">


                    </ul>
                </div>
            </div>
            <div class="col-lg-2 p-0 card border">
                <div class="card-header persian text-center">در حال انجام</div>
                <div class="card-body" style="">
                    <ul class="list-group  sortable droppable p-0 " id="indo" style="width:100%;height:100%;">

                    </ul>
                </div>
            </div>
            <div class="col-lg-2 p-0 card border">
                <div class="card-header persian text-center">انجام شده</div>
                <div class="card-body" style="">
                    <ul class="list-group  sortable droppable p-0" id="done" style="width:100%;height:100%;">

                    </ul>
                </div>
            </div>
            <!-- </div> -->
        </div>
    </div>
@section('scripts')
    <script src="{{ asset('js/general/functions.js') }}"></script>
    <script>
        $(document).ready(function() {
            var url = '/tasks/task-board'
            ajaxfunc(url, 'GET', '').then(function(res) {

                res.forEach(element => {

                    var $li =
                        '  <li class="animate__animated animate__flipInX list-group-item shadow mt-2   rounded " data-id="' +
                        element.id + '" '+ ' data-background-color="'+element.user.background_color+'"'+'style="width:100%;height: 80px; background-color:'+element.user.background_color+
                        ';color:'+element.user.text_color+'">' +

                        '<div class="d-flex justify-content-between"> ' +
                        '<h4 class="persian">' + element.title + '</h4> ' +
                        '<div> ' +
                        '<a class="text-white plus" style="text-decoration: none ;cursor: pointer ;"><svg ' +
                        'xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" ' +
                        'class="bi bi-plus-square-fill" viewBox="0 0 16 16"> ' +
                        '<path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0z" /> ' +
                        '</svg></a> ' + ((element.status != 2) ? (
                            '<a class="text-white next_level" style="text-decoration: none ;cursor: pointer ;"><svg ' +
                            'xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" ' +
                            'class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16"> ' +
                            '<path ' +
                            'd="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z" /> ' +
                            '</svg></a> ') : '') +
                        '</div> ' +
                        '</div> ' +


                        '<div class="d-flex justify-content-between">' +
                        '<p class="persian">تخمین زمان پایان</p>' +
                        '<p class="persian text-center" style="font-weight: bold">' + element
                        .duration +
                        '</p>' +
                        '</div>' +




                        '<div class="content" style="display:none ;">' +
                        '<div class="d-flex justify-content-between">' +
                        '<p class="persian">زمان ورود به صف انتظار</p>' +
                        '<p class="persian text-center todo_date">' + covertGregorianToJalali(
                            element
                            .todo_date) + '</p>' +
                        '</div>' +
                        '<div class="d-flex justify-content-between">' +
                        '<p class="persian">زمان ورود به صف در حال انجام</p>' +
                        '<p class="persian text-center indo_date">' + covertGregorianToJalali(
                            element.indo_date) + '</p>' +
                        '</div>' +
                        '<div class="d-flex justify-content-between">' +
                        '<p class="persian">زمان پایان</p>' +
                        '<p class="persian text-center done_date">' + covertGregorianToJalali(
                            element.done_date) + '</p>' +
                        '</div>' +
                        '<hr>' +
                        '<p class="persian">' + element.description + '</p>' +

                        '<div class="p-1 rounded bg-white">' +
                        '<div class="progress">' +

                        '<div class="progress-bar progress-bar-stripe2022-06-15 12:10:41d bg-info" role="progressbar"' +
                        'style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">' +
                        '</div>' +
                        '</div>' +


                        '</div>' +

                        '<div class="d-flex justify-content-center container-play-pause">' +
                        '<div data-play="1" class="mt-1 play-pause" style="cursor:pointer">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-pause-circle-fill" viewBox="0 0 16 16">' +
                        '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.25 5C5.56 5 5 5.56 5 6.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C7.5 5.56 6.94 5 6.25 5zm3.5 0c-.69 0-1.25.56-1.25 1.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C11 5.56 10.44 5 9.75 5z"/>' +
                        '</svg>' +
                        '</div>'+
                        '</div>' +


                        '</div>' +
                        '</li>';
                    var target = '';
                    if (element.status == 0) {
                        target = '#todo';
                    } else if (element.status == 1) {
                        target = '#indo';

                    } else {
                        target = '#done';

                    }
                    $(target).append($li);


                    if(target != '#indo' || element.user_id != {{ Auth::id() }}){
                        var $paly_pause_button = $(target).find('.play-pause');
                        $paly_pause_button.remove();
                        console.log(element.user_id +" : "+{{ Auth::id() }})
                    }
                });
            }).catch(function(res) {

            });
            $('.sortable').sortable({
                start: function(event, ui) {

                }
            });
            $(".draggable").draggable();
            $(".droppable").droppable({

                drop: function(event, ui) {
                    var $item = $(ui.draggable);
                    var parentID = $item.parent().attr('id');


                    var $target = $(event.target);
                    var targetID = $target.attr('id');
                    if (canMove(parentID, targetID)) {

                        changeStatus($item, $target);

                    }


                }
            });


            $(document).on('click', '.play-pause', function() {
                var background_color = $(this).parents('li').attr('data-background-color');

                var palyIcon =
                    '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-play-circle-fill" viewBox="0 0 16 16">' +
                    '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"/>' +
                    '</svg>';
                var pauseIcon =
                    '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-pause-circle-fill" viewBox="0 0 16 16">' +
                    '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.25 5C5.56 5 5 5.56 5 6.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C7.5 5.56 6.94 5 6.25 5zm3.5 0c-.69 0-1.25.56-1.25 1.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C11 5.56 10.44 5 9.75 5z"/>' +
                    '</svg>';
                var status = $(this).attr('data-play');
                if (status == 1){
                    $(this).empty();
                    $(this).append(palyIcon);
                    $(this).attr('data-play','0');
                    $(this).parents('li')
                    .css('background','repeating-linear-gradient(45deg,'+background_color+' 0px,'+background_color+' 20px,#a3a3a3ab 20px,#a3a3a3ab 40px)')
                }else{
                    $(this).empty();
                    $(this).append(pauseIcon);
                    $(this).attr('data-play','1');
                    $(this).parents('li')
                    .css('background','').css('background-color',background_color);
                }
                var task_id = $(this).parents('li').attr('data-id');
                ajaxfunc('/tasks/'+task_id+'/play-pause','POST','')
                .then(function(res){

                })
                .catch(function(res){

                })


            })



        });
        $(document).on('click', '.plus', function() {
            var $item = $(this).parent().parent().parent();
            if ($item.css('height') == '80px') {
                $item.animate({
                    height: '300px'
                })
                $item.find('.content').css('display', 'block')
            } else {
                $item.animate({
                        height: '80px'
                    }

                    ,
                    function() {
                        $item.find('.content').css('display', 'none')
                    })

            }


        })


        $(document).on('click', '.next_level', function() {
            var $item = $(this).parent().parent().parent();
            var parentID = $item.parent().attr('id');
            var targetID = '';
            Swal.fire({
                text: "آیا می خواهید به صف بعدی منتقل شود ؟",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'خیر',
                confirmButtonText: 'بله می خواهم'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (parentID != 'done') {
                        if (parentID == 'todo') {
                            targetID = 'indo'
                        } else if (parentID == 'indo') {
                            targetID = 'done';
                        }
                        $target = $('#' + targetID);
                        changeStatus($item, $target);
                    }



                }
            })


        });

        function canMove(current, next) {
            return ((current == 'todo' && next == 'indo') || (current == 'indo' && next == 'done'));


        }


        function changeStatus($item, $target) {
            var task_id = $item.attr('data-id');
            ajaxfunc('/tasks/' + task_id + '/change-status', 'POST', '').then(function(
                res) {
                $item.detach();
                $item.css({
                    'position': '',
                    'left': '',
                    'top': '',
                    'z-index': ''
                });
                $item.find('.indo_date').text(covertGregorianToJalali(res
                    .indo_date));
                $item.find('.done_date').text(covertGregorianToJalali(res
                    .done_date));
                if ($target.attr('id') == 'done') {
                    removeNextLevelButton($item);
                    $item.find('.play-pause').remove();
                }
                if ($target.attr('id') == 'indo') {

                    $item.find('.container-play-pause').append('<div data-play="1" class="mt-1 play-pause" style="cursor:pointer">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-pause-circle-fill" viewBox="0 0 16 16">' +
                        '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.25 5C5.56 5 5 5.56 5 6.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C7.5 5.56 6.94 5 6.25 5zm3.5 0c-.69 0-1.25.56-1.25 1.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C11 5.56 10.44 5 9.75 5z"/>' +
                        '</svg>' +
                        '</div>');

                }
                $target.append($item[0].outerHTML);
            }).catch(function(res) {

            });
        }


        function removeNextLevelButton($item) {
            $item.find('.next_level').remove();
        }
    </script>
@endsection
@endsection
