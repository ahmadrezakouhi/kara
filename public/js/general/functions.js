


function ajaxfunc(url, method, data) {
    var response;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax(
        {
            url: url,
            method: method,
            data: data,
            async: false,
            succuss: function (res) {
                toastr["success"](res.message);
                // toastr["success"]('جذف شد');
                // response = res;
                // table.ajax.reload();
                console.log(res.title)
                return res;
            },
            error: function (res) {
                var error = eval("(" + res.responseText + ")")
                $.each(error.errors, function (index, value) {
                    toastr["error"](value);
                })
                // response = res;
            }

        }
    )


    // return response;
}



function datatable(table_id, url,columns){
    var table = $(table_id).on('preXhr.dt', function(e, settings, json, xhr) {}).DataTable({
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

        createdRow: function(row, data, dataIndex) {
            $(row).find('button').attr("data-id", data['id']);

        }


    }


);


return table;
}


function submit_form(form_id,id,url,modal_id,table,){


        if (id ) {
            url += ('/'+id)
        }
        ajaxfunc(url, "POST", $(form_id).serialize());
        $(modal_id).modal('hide');
        table.ajax.reload();

}



function getIDs(form_id){
    var ids = [];
    $(form_id).find('.form-control').each(function(index, element) {

        ids.push(element.id);

    })
    return ids;
}


function removeIDValues(form_id){
    getIDs(form_id).forEach(element => {
        $('#' + element).val("")
    });
}
