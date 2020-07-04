<!DOCTYPE html>
<html>
<head>
  <title>Laravel CRUD Ajax DataTable</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>

  <div class="container">
    <h1 style="margin-botton: 20px;">Laravel CRUD Ajax DataTable</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewAnggota" style="padding-botton: 20px;"> Tambah Anggota</a>
    <br />
    <br />
    <table class="table table-bordered data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Alamat</th>
          <th width="280px">Atur</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modelHeading"></h4>
        </div>
        <div class="modal-body">
          <form id="anggotaForm" name="anggotaForm" class="form-horizontal">
            <input type="hidden" name="anggota_id" id="anggota_id">
            <div class="form-group">
              <label for="nama" class="col-sm-2 control-label">Name</label>
              <div class="col-sm-12">
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" value="" maxlength="50" required="">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Alamat</label>
              <div class="col-sm-12">
                <textarea id="alamat" name="alamat" required="" placeholder="Masukkan Alamat" class="form-control"></textarea>
              </div>
            </div>

            <div class="col-sm-offset-2 col-sm-10">
             <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
             </button>
           </div>
         </form>
       </div>
     </div>
   </div>
 </div>

 <script type="text/javascript">
     $(function () {

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('crud-ajax.index') }}",
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'nama', name: 'nama'},
        {data: 'alamat', name: 'alamat'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
      });

      $('#createNewAnggota').click(function () {
        $('#saveBtn').val("create-anggota");
        $('#anggota_id').val('');
        $('#anggotaForm').trigger("reset");
        $('#modelHeading').html("Tambah Anggota");
        $('#ajaxModel').modal('show');
      });

      $('body').on('click', '.editAnggota', function () {
        var anggota_id = $(this).data('id');
        $.get("{{ route('crud-ajax.index') }}" +'/' + anggota_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Anggota");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#anggota_id').val(data.id);
          $('#nama').val(data.nama);
          $('#alamat').val(data.alamat);
        })
      });

      $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Memproses..');
        
        $.ajax({
          data: $('#anggotaForm').serialize(),
          url: "{{ route('crud-ajax.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {

            $('#anggotaForm').trigger("reset");
            $('#ajaxModel').modal('hide');
            table.draw();

          },
          error: function (data) {
            console.log('Error:', data);
            $('#saveBtn').html('Save Changes');
          }
        });
      });

      $('body').on('click', '.deleteAnggota', function () {
        var anggota_id = $(this).data("id");
        confirm("Are You sure want to delete !");

        $.ajax({
          type: "DELETE",
          url: "{{ route('crud-ajax.store') }}"+'/'+anggota_id,
          success: function (data) {
            table.draw();
          },
          error: function (data) {
            console.log('Error:', data);
          }
        });
      });
    });
  </script>
</body>
</html>