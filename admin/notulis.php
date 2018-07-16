<?php
include "./header.php";
?>
  <title>NOTULIS | Elrascribe</title>
  <style type="text/css">
    .input-disabled {
      background-color:#EBEBE4;
      border:1px solid #ABADB3;
      padding:2px 1px;
    }
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Notulis
        <small>tabel data</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active"><a href="./notulis.php"><i class="fa fa-dashboard"></i> Notulis</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              <!-- <hr style="margin-top: 10px; margin-bottom: 15px;">
              <div class="row">         
                <div class="col-md-3 col-sm-12 form-group">
                  <select class="form-control" id="statusFilter" multiple="multiple"></select>
                </div>
                <div class="col-md-3 col-sm-12 form-group">
                  <button type="button" class="btn btn-default btn-sm" id="deleteFilter">Hapus Filter</button>
                </div>
              </div>
              <hr style="margin-bottom: 10px; margin-top: 0px;"> -->
              <div class="table-responsive">
                <table id="notulis" class="responsive nowrap table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                  <thead>
                  <tr>
                    <th style="width: 5%">#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th style="width: 5%">Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
include "./footer.php";
?>
<script src="./notulis.js"></script>
<script type="text/javascript">
  var $statusFilter = $("#statusFilter").select2({
    placeholder: "Filter Status"
  });

  $("#deleteFilter").on("click", function () {
    $statusFilter.val(null).trigger("change");
  });
</script>
</body>
</html>
