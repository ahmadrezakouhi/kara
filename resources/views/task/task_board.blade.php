@extends('layouts.default')
@section('title', 'تسک بورد')
@section('content')

    <div class="container mt-3  shadow-sm border p-5 d-flex align-items-center rounded">
       <form action="" method="post" id="search">
        <div class="row">
            <div class="col-md-6">
                <label for="user">کاربر</label>
                <input type="text" class="form-control" id="user" name="user">
            </div>
            <div class="col-md-6">
                <label for="project">پروژه</label>
                <input type="text" class="form-control" id="project" name="project">
            </div>

        </div>
       </form>
    </div>
    <div class="container border rounded mt-2">

        <div class="row card-group">
            <!-- <div class="card-group"> -->
            <div class="col-lg-2 p-0 card border" style="min-height:100vh ;">
                <div class="card-header  text-center">
                    در صف انتظار
                </div>
                <div class="card-body" style="">
                    <ul class="list-group  sortable droppable p-0" id="todo" style="width:100%;height: 100% ;">


                    </ul>
                </div>
            </div>
            <div class="col-lg-2 p-0 card border">
                <div class="card-header  text-center">در حال انجام</div>
                <div class="card-body" style="">
                    <ul class="list-group  sortable droppable p-0 " id="indo" style="width:100%;height:100%;">

                    </ul>
                </div>
            </div>
            <div class="col-lg-2 p-0 card border">
                <div class="card-header  text-center">انجام شده</div>
                <div class="card-body" style="">
                    <ul class="list-group  sortable droppable p-0" id="done" style="width:100%;height:100%;">

                    </ul>
                </div>
            </div>
            <!-- </div> -->
        </div>

        <div class="modal fade" id="add_task_comment">
            <div class="modal-dialog ">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        {{-- <h4 class="modal-title" id="modal_title"></h4> --}}
                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" action="post" id="create_update">

                        <!-- Modal body -->
                        <div class="modal-body">

                            <div class="mb-3 mt-3">
                                <label for="comment" class="form-label">توضیحات</label>
                                <textarea name="comment" id="comment" cols="30" rows="10" class="form-control"></textarea>
                            </div>


                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" id="submit-form" class="btn btn-success"
                                data-bs-dismiss="modal">ثبت</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@section('scripts')
    <script src="{{ asset('js/general/functions.js') }}"></script>
    <script>
        var item_id = null;
        $(document).ready(function() {
            var auth_id = {{ Auth::id() }};

            var url = '/tasks/task-board';
            var palyIcon =
                '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-play-circle-fill" viewBox="0 0 16 16">' +
                '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"/>' +
                '</svg>';
            var pauseIcon =
                '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-pause-circle-fill" viewBox="0 0 16 16">' +
                '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.25 5C5.56 5 5 5.56 5 6.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C7.5 5.56 6.94 5 6.25 5zm3.5 0c-.69 0-1.25.56-1.25 1.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C11 5.56 10.44 5 9.75 5z"/>' +
                '</svg>';
            ajaxfunc(url, 'GET', '').then(function(res) {

                cards(res);
                loading(false);

            }).catch(function(res) {
                loading(false);
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
                        $item.remove();
                        loading(true);
                        setTimeout(() => {

                            changeStatus($item, $target);
                        }, 5000);



                    }


                }
            });


            $(document).on('click', '.play-pause', function() {
                var background_color = $(this).parents('li').attr('data-background-color');


                var status = $(this).attr('data-play');
                if (status == 1) {
                    $(this).empty();
                    $(this).append(palyIcon);
                    $(this).attr('data-play', '0');
                    $(this).parents('li')
                        .css('background', 'repeating-linear-gradient(45deg,' + background_color + ' 0px,' +
                            background_color + ' 100px,#a3a3a3ab 300px,#a3a3a3ab 20px)')
                } else {
                    stopUIOtherTasks();
                    $(this).empty();
                    $(this).append(pauseIcon);
                    $(this).attr('data-play', '1');
                    $(this).parents('li')
                        .css('background', '').css('background-color', background_color);
                }
                var task_id = $(this).parents('li').attr('data-id');
                ajaxfunc('/tasks/' + task_id + '/play-pause', 'POST', '')
                    .then(function(res) {
                        loading(false);
                    })
                    .catch(function(res) {
                        loading(false);
                    })


            })

            $('#create_update').submit(function(event) {
                event.preventDefault();
                submit_form('#create_update', null, '/tasks/' + item_id + '/addComment', false)
                    .then(function(res) {
                        loading(false);
                        toastr["success"](res.message);
                        $('#add_task_comment').modal('hide');

                    }).catch(function(res) {
                        loading(false);


                    });
            });

        });
        $(document).on('click', '.plus', function() {
            var $item = $(this).parent().parent().parent();
            if ($item.css('height') == '110px') {
                $item.animate({
                    height: '400px'
                })
                $item.find('.content').css('display', 'block')
            } else {
                $item.animate({
                        height: '110px'
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


        $(document).on('keyup', '#user,#project', function() {
            console.log($(this).val())
            $('#done').empty();
            var url = '/tasks/task-board';
            ajaxfunc(url, 'GET', $('#search').serialize()).then(function(res) {

                cards(res);
                loading(false);

            }).catch(function(res) {
                loading(false);
            });

        })

        function canMove(current, next) {
            return ((current == 'todo' && next == 'indo') || (current == 'indo' && next == 'done'));


        }


        function changeStatus($item, $target) {
            var task_id = $item.attr('data-id');
            ajaxfunc('/tasks/' + task_id + '/change-status', 'POST', '').then(function(
                res) {
                loading(false);
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
                    var background_color = $item.attr('data-background-color');
                    $item.find('.play-pause').remove();
                    item_id = $item.attr('data-id');

                    $item.css('background', background_color);
                    $('#add_task_comment').modal('show');
                }
                if ($target.attr('id') == 'indo') {

                    stopUIOtherTasks();
                    $item.find('.container-play-pause').append(
                        '<div data-play="1" class="mt-1 play-pause" style="cursor:pointer">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-pause-circle-fill" viewBox="0 0 16 16">' +
                        '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.25 5C5.56 5 5 5.56 5 6.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C7.5 5.56 6.94 5 6.25 5zm3.5 0c-.69 0-1.25.56-1.25 1.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C11 5.56 10.44 5 9.75 5z"/>' +
                        '</svg>' +
                        '</div>');

                }
                $target.prepend($item[0].outerHTML);
            }).catch(function(res) {
                loading(false);
            });
        }


        function removeNextLevelButton($item) {
            $item.find('.next_level').remove();
        }


        function stopUIOtherTasks() {
            $('ul#indo>li[data-user-id="' + {{ Auth::id() }} + '"]')
                .css('background', 'repeating-linear-gradient(45deg,' + '{{ Auth::user()->background_color }}' + ' 0px,' +
                    '{{ Auth::user()->background_color }}' + ' 100px,#a3a3a3ab 300px,#a3a3a3ab 20px)');
            $('ul#indo>li[data-user-id="' + {{ Auth::id() }} + '"] .play-pause').attr('data-play', 0);
        }



        function cards(res) {
            res.forEach(task => {
                var $li =
                    '  <li class="animate__animated animate__flipInX list-group-item shadow mt-2   rounded " data-id="' +
                    task.id + '" data-user-id="' + task.user_id + '" ' +
                    ' data-background-color="' + task.user
                    .background_color + '"' +
                    'style="width:100%;height: 110px; background:' + (task.play != null && task
                        .play == 0 ?

                        'repeating-linear-gradient(45deg,' + task.user.background_color +
                        ' 0px,' +
                        task.user.background_color + ' 100px,#a3a3a3ab 300px,#a3a3a3ab 20px)' :
                        task.user.background_color) +
                    ';color:' + task.user.text_color + '">' +

                    '<div class="d-flex justify-content-between"> ' +
                    '<h5 class="">' + task.id + '# ' + '</h5> ' +

                    '<p>' + task.user.fname + ' ' + task.user.lname + '</p>' +

                    '<div class="d-flex flex-row-reverse justify-content-between " > ' +
                    '<a class=" plus" style="text-decoration: none ;cursor: pointer ;color:' +
                    task.user.text_color + '"><svg ' +
                    'xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" ' +
                    'class="bi bi-plus-square-fill" viewBox="0 0 16 16"> ' +
                    '<path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0z" /> ' +
                    '</svg></a> ' + ((task.status != 2) ? (
                        '<a class=" next_level" style="text-decoration: none ;cursor: pointer ;color:' +
                        task.user.text_color +
                        '"><svg ' +
                        'xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" ' +
                        'class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16"> ' +
                        '<path ' +
                        'd="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z" /> ' +
                        '</svg></a> ') : '') +
                    '(' + task.duration + 'دقیقه)' +
                    '</div> ' +
                    '</div> ' +
                    '<p>' + (truncate(task.title, 45)) + '</p>' +
                    '<div class="d-flex justify-content-between">' +
                    '<p>' + (truncate(task.sprint.phase.project.title, 20)) + '/' + (truncate(
                        task.sprint.phase.title, 20)) + '/' + (truncate(task.sprint.title,
                        20)) + '</p>' +

                    '</div>' +
                    '<div class="content" style="display:none">' +
                    '<table class="table  text-center table-bordered" style="width:100%;border : 1px ' +
                    task.user.text_color + ';color:' + task.user.text_color + '">' +
                    '<thead class="">' +
                    '<tr>' +
                    '<th width="33%">' +
                    'ورود' +
                    '</th>' +
                    '<th width="33%">' +
                    'انجام' +
                    '</th>' +
                    '<th width="33%">' +
                    'پایان' +
                    '</th>' +
                    '<tr>' +
                    '</thead>' +
                    '<tbody >' +
                    '<tr>' +
                    '<td>' +
                    covertGregorianToJalali(task.todo_date) +
                    '<br>' +
                    covertGregorianToJalaliTime(task.todo_date) +
                    '</td>' +
                    '<td>' +
                    covertGregorianToJalali(task.indo_date) +
                    '<br>' +
                    covertGregorianToJalaliTime(task.indo_date) +
                    '</td>' +
                    '<td>' +
                    covertGregorianToJalali(task.done_date) +
                    '<br>' +
                    covertGregorianToJalaliTime(task.done_date) +
                    '</td>' +
                    '</tr>' +


                    '</table>' +

                    '<hr>' +
                    '<p class="" style="overflow-y:scroll;height:50px">' +
                    (task.description ? task.description : '-') +
                    '</p>' +

                    '<div class="p-1 rounded bg-white">' +
                    '<div class="progress">' +

                    '<div class="progress-bar progress-bar-stripe2022-06-15 12:10:41d bg-info" role="progressbar"' +
                    'style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">' +
                    '</div>' +
                    '</div>' +


                    '</div>' +

                    '<div class="d-flex justify-content-center container-play-pause">' +
                    '<div  class="mt-1 play-pause" data-play="' + task.play + '"' +
                    ' style="cursor:pointer">' +
                    (task.play ?
                        ('<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-pause-circle-fill" viewBox="0 0 16 16">' +
                            '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.25 5C5.56 5 5 5.56 5 6.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C7.5 5.56 6.94 5 6.25 5zm3.5 0c-.69 0-1.25.56-1.25 1.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C11 5.56 10.44 5 9.75 5z"/>' +
                            '</svg>') :
                        ('<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-play-circle-fill" viewBox="0 0 16 16">' +
                            '<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"/>' +
                            '</svg>')
                    ) +
                    '</div>' +
                    '</div>' +


                    '</div>' +
                    '</li>';
                var target = '';
                if (task.status == 0) {
                    target = '#todo';
                } else if (task.status == 1) {
                    target = '#indo';

                } else {
                    target = '#done';

                }
                $(target).append($li);

                if (task.status != 1) {

                    var $play_pause_button = $(target).find('.play-pause');
                    $play_pause_button.remove();

                }

            });
        }
    </script>
@endsection
@endsection
