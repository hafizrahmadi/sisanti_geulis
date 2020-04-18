@extends('home.template')

@section('content-header')
    <h1>Master User</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Master User</li>
      </ol>
@endsection
@section('content')
    <!-- Default box -->
    <div class="box" >
        <div class="box-header with-border">
            <h3 class="box-title">Master User</h3>
            <div class="box-tools pull-right">
                <!-- <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
                <a href="javascript:modalForm('Tambah User','','');"><button class="btn btn-sm btn-success"><i class="fa fa-pencil-square-o" style=""></i> Tambah User</button></a>
            </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table id="tablex" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>ID User</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Token</th>
                        <th class="no-sort">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
            </div><!-- /.box-body -->
        
        </div>
        <div class="overlay div_loading" id="loading"><i class="fa fa-refresh fa-spin"></i></div>
        <!-- <div class="box-footer">

        </div> --><!-- /.box-footer-->
    </div><!-- /.box -->
@endsection

@section('outside-content')
    <!-- Modal -->
<div class="modal fade" id="modal" role="dialog" style="z-index: 1050;">
   <div class="modal-dialog">
      <div class="box box-success">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title" id="modal_title" style="font-weight:bold;"></h4>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label>Username</label>
                  <input type="text" name="username" class="form-control" id="username" placeholder="Username">
               </div>
               <div class="form-group">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" id="password" placeholder="Password">
               </div>

               <div class="form-group" id="group-conf_password">
                  <label>Konfirmasi Password</label>
                  <input type="password" name="conf_password" class="form-control" id="conf_password" placeholder="Konfirmasi Password">
                  <span class="help-block" id="msg-conf_password"></span>
               </div>
               <div class="form-group">
                  <label>Role</label>
                  <select class="form-control" name="role" id="role">
                    <option value="">Pilih Role</option>
                    <option value="camat">Camat</option>
                    <option value="sekretaris_camat">Sekretaris Camat</option>
                    <option value="kasi">Kasi</option>
                    <option value="kasubag">Kasubag</option>
                    <option value="admin">Admin</option>
                  </select>
               </div>
               <!-- <input type="hidden" id="note_breach_date" name="note_breach_date" /> -->
               <input type="hidden" id="id_user" name="id_user" />
            </div>
            <div class="modal-footer">
               <!-- <button type="button" class="btn btn-sm btn-default" title="Reset" id="appendix1_reset"><i class="fa fa-undo"></i></button> -->
               <button type="button" class="btn btn-sm btn-success" title="Save" id="btn_save"><i class="fa fa-save"></i></button>
            </div>
         </div>
      </div>
      <!-- /.box -->
   </div>
</div>
@endsection

@section('script')
<link rel="stylesheet" href="{{asset('file_assets/adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.css') }}">
<!-- DataTables -->
<script src="{{asset('file_assets/adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('file_assets/adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

<script type="text/javascript">
    var tablex = null;
    $(document).ready(function() {
        getListUser();
        $("#conf_password").keyup(validate);
    });

    function validate() {
      var password1 = $("#password").val();
      var password2 = $("#conf_password").val();

        
     
        if(password1 == password2) {
           $("#group-conf_password").removeClass("has-error").addClass("has-success");        
           $('#msg-conf_password').html("Password sama.");
        } else {
           $("#group-conf_password").addClass("has-error").removeClass('has-success');        
           $('#msg-conf_password').html("Password harus sama!");
            
        }
        
    }

    function modalForm(title,index,id){
        $('#username').val("");
        $('#password').val("");
        $('#conf_password').val("");
        $('#role').val("");
        $('#id_user').val("");
        $('#modal_title').html(title);
        $('#id_user').val(id);

        // if (index!='') {
        //
        //   $('#no_perkara').val($('#no_perkara_'+index).html());
        //   $('#jenis_sidang').val($('#jenis_sidang_'+index).html());
        //   $('#agenda_sidang').val($('#agenda_sidang_'+index).html());
        //   $('#waktu_sidang').val($('#waktu_sidang_'+index).html());
        //   $('#pasal').val($('#pasal_'+index).html());
        //   $('#nama_terdakwa').val($('#nama_terdakwa_'+index).html());
        //   $('#nama_hakim').val($('#nama_hakim_'+index).html());
        //   $('#nama_jaksa').val($('#nama_jaksa_'+index).html());
        //   $('#keterangan').val($('#keterangan_'+index).html());
        // }
        if (id!='') {
            $('#username').val($('#username_'+index).html());
            $('#password').val($("#username_"+index).attr('data-pass'));
            $('#conf_password').val($("#username_"+index).attr('data-pass'));
            $('#role').val($('#role_'+index).html());
            $('#id_user').val(id);
        }
        $('#btn_save').attr('onclick', 'modalSave()');
        $('#modal').modal('show');
  }

  function getListUser(){
    $('#tbody').html("");
    $.ajax({
                url: "{{url('/getlistuser')}}",
                type: "GET",
                data: {
                },
                beforeSend: function() {
                  // console.log(tablex);

                  if (tablex!=null) {
                    tablex.destroy();
                  }
                  $('#loading').show();
                },
                success: function(data) {
                  dt = JSON.parse(data);
                  

                  console.log(dt);
                  content = '';
                  for (var i = 0; i < dt.length; i++) {
                    content+='<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td id="id_user_'+(i+1)+'" data-val="'+dt[i].id+'">'+dt[i].id+'</td>'+
                                '<td id="username_'+(i+1)+'" data-val="'+dt[i].username+'" data-pass="'+dt[i].password+'">'+dt[i].username+'</td>'+
                                '<td id="role_'+(i+1)+'" data-val="'+dt[i].role+'">'+dt[i].role+'</td>'+
                                '<td id="token_'+(i+1)+'" data-val="'+dt[i].token+'">'+dt[i].token+'</td>'+
                                '<td class="text-center">'+
                                      '<div class="btn-group" >'+
                                       '<a href="javascript:modalForm(\'Edit User\',\''+(i+1)+'\',\''+dt[i].id+'\')">'+
                                       '<button class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil" style=""></i></button>'+
                                       '</a>&nbsp;'+
                                       '<a href="javascript:deleteUser('+dt[i].id+')"  onclick="return conf();">'+
                                       '<button class="btn btn-xs btn-success" title="Delete"><i class="fa fa-trash-o" style=""></i></button>'+
                                       '</a>'+
                                      '</div>'+
                                  '</td>'+
                              '</tr>';
                  }
                  $('#tbody').html(content);
                },
                complete: function() {

                  tablex = $('#tablex').DataTable({
                      "columnDefs": [
                      { "targets": 5, "orderable": false}
                    ]
                    // "paging": true,
                    // "lengthChange": false,
                    // "searching": false,
                    // "ordering": false,
                    // "info": true,
                    // "autoWidth": false,

                  });
                  $('#loading').hide();
                },
                error: function() {
                    alert("Memproses data gagal !");
                }
            });``
  }

  function modalSave(){
    var id_user = $('#id_user').val();
    var username = $('#username').val();
    var password = $('#password').val();
    var conf_password = $('#conf_password').val();
    var role = $('#role').val();
    if (username!=''&&password!=''&&conf_password!=''&&role!='') {
        $.ajax({
               url: "{{url('/adduserpost')}}",
               type: "POST",
               data: {
                    _token: '{{csrf_token()}}',
                  'id_user': id_user,
                  'username': username,
                  'password': password,
                  'conf_password': conf_password,
                  'role': role,
               },
               beforeSend: function() {
                 console.log({
                   _token: '{{csrf_token()}}',
                  'id_user': id_user,
                  'username': username,
                  'password': password,
                  'conf_password': conf_password,
                  'role': role,
                 });
                 if (id_user=='') {
                   console.log('Tambah data user : '+username + ' | role : '+role);
                 }else{
                   console.log('Update data user ID : '+id_user+' | username : '+username+ ' | role : '+role);
                 }

               },
               success: function(data) {
                    // alert(data);
                    if (data) {
                        if (id_user!='') {
                            alert('User berhasil diperbarui!');
                        }else{
                            alert('User berhasil ditambahkan!');
                        }
                        
                        $('#id_user').val("");
                        $('#username').val("");
                        $('#password').val("");
                        $('#conf_password').val("");
                        $('#role').val("");
                        $('#modal').modal('hide');
                    }else{
                        alert('User gagal ditambahkan!');
                    }
               },
               complete: function() {
                 getListUser();
               },
               error: function() {
                   alert("Memproses data gagal !");
               }
           });
    }else{
        alert('Lengkapi form!');
    }
    
  }

  function deleteUser(id){
    $.ajax({
               url: "{{url('/deleteuser')}}",
               type: "POST",
               data: {
                    _token: '{{csrf_token()}}',
                  'id': id,
               },
               beforeSend: function() {
                 console.log({
                   _token: '{{csrf_token()}}',
                  'id': id,
                 });
                 
                console.log('Delete user : '+id);
                 

               },
               success: function(data) {
                    // alert(data);
                    if (data) {
                        alert('User berhasil dihapus!');
                    }else{
                        alert('User gagal dihapus!');
                    }
               },
               complete: function() {
                 getListUser();
               },
               error: function() {
                   alert("Memproses data gagal !");
               }
           });
  }
</script>
@endsection