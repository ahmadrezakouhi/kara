$(function () {



    var table = $('.data-table').DataTable({

        processing: true,

        serverSide: true,

        ajax: "{{ route('requirements.index') }}",

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'name', name: 'name'},

            {data: 'email', name: 'email'},

            {data: 'action', name: 'action', orderable: false, searchable: false},

        ]

    });



  });
