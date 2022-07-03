


function ajaxfunc(url, method, data) {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    return new Promise(function (resolve, reject) {
        $.ajax(
            {
                url: url,
                method: method,
                data: data,

            }
        ).done(resolve)
            .fail(reject)
    })



}



function datatable(table_id, url, columns,removeButtons=true) {
    var table = $(table_id).on('preXhr.dt', function (e, settings, json, xhr) { }).DataTable({
        ajax: {
            url: url,


        },



        columns: columns,
        language: {
            "decimal": "-",
            "decimal": "",
            "emptyTable": "هیچ اطلاعاتی برای نمایش وجود ندارد!",
            "info": "نمایش _START_ تا _END_ از تمام _TOTAL_ ",
            "infoEmpty": "چیزی پیدا نشد",
            "infoFiltered": "(جستجو شده از  _MAX_ پروژه )",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "نمایش _MENU_ ",
            "loadingRecords": "لطفا صبر کنید...",
            "processing": "در حال پردازش...",
            "search": "جستجوی عمومی:",
            "zeroRecords": " پیدا نشد",
            "paginate": {
                "first": ">>",
                "last": "<<",
                "next": "بعدی",
                "previous": "قبلی"
            }
        },

        createdRow: function (row, data, dataIndex) {

            $(row).find('button').attr("data-id", data['id']);
            if (removeButtons) {
                if (auth_id != data.user_id) {
                    $(row).find('.edit,.delete').remove();
                }
                if(!isAdmin && isOwner){
                    $(row).find('.add_phase,.sprints').remove();

                }
            }
        }


    }


    );
    table.on('draw', function () {

        table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {

            cell.innerHTML = table.page.len() * table.page() + i + 1;
        });
    }).draw();


    return table;
}


function submit_form(form_id, id, url, check_id = true) {


    if (check_id && id) {
        url += ('/' + id)
    }
    return ajaxfunc(url, "POST", $(form_id).serialize());



}



function getIDs(form_id) {
    var ids = [];
    $(form_id).find('.form-control').each(function (index, element) {

        ids.push(element.id);

    })
    return ids;
}


function removeIDValues(form_id) {
    getIDs(form_id).forEach(element => {
        $('#' + element).val("");
    });
}


function covertJalaliToGregorian(date) {
    if (date == null) {
        return '-';
    }
    return moment(date, 'YYYY-M-D HH:mm:ss').format('jYYYY/jMM/jDD');
}

function showErrors(errors) {
    $.each(errors, function (index, value) {
        toastr['error'](value);
    });
}
