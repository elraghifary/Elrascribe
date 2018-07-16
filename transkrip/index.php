<?php
include "../header.php";
?>
  <title>TRANSKRIP | Elrascribe</title>
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
        Transkrip
        <small>tabel data</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active"><a href="/transkrip/"><i class="fa fa-dashboard"></i> Transkrip</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              <a type="button" class="btn btn-primary btn-lg" href="tambah.php" id="btn-add"><i class="fa fa-plus"></i> Tambah Transkrip</a>
              <hr style="margin-top: 10px; margin-bottom: 15px;">
              <div class="row">
                <div class="col-md-3 col-sm-12 form-group">
                  <select class="form-control" id="projectNameFilter" multiple="multiple"></select>
                </div>
                <div class="col-md-3 col-sm-12 form-group">
                  <select class="form-control" id="moderatorFilter" multiple="multiple"></select>
                </div>
                <div class="col-md-3 col-sm-12 form-group">
                  <select class="form-control" id="monthFilter" multiple="multiple"></select>
                </div>
                <div class="col-md-3 col-sm-12 form-group">
                  <button type="button" class="btn btn-default btn-sm" id="deleteFilter">Hapus Filter</button>
                </div>
              </div>
              <hr style="margin-bottom: 10px; margin-top: 0px;">
              <div class="table-responsive">
                <table id="transcript" class="responsive nowrap table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                  <thead>
                  <tr>
                    <th style="width: 5%">#</th>
                    <th>Proyek</th>
                    <th>IDI</th>
                    <th>Tanggal</th>
                    <th>Hari / Waktu</th>
                    <th>Moderator</th>
                    <th style="width: 5%">Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th style="width: 5%">#</th>
                    <th>Proyek</th>
                    <th>IDI</th>
                    <th>Tanggal</th>
                    <th>Hari / Waktu</th>
                    <th>Moderator</th>
                    <th style="width: 5%">Aksi</th>
                  </tr>
                  </tfoot>
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
include "../footer.php";
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment-with-locales.min.js"></script>
<script src="./transkrip.js"></script>
<script type="text/javascript">
  var $projectNameFilter = $("#projectNameFilter").select2({
    placeholder: "Filter Proyek"
  });

  var $moderatorFilter = $("#moderatorFilter").select2({
    placeholder: "Filter Moderator"
  });

  var $monthFilter = $("#monthFilter").select2({
    placeholder: "Filter Bulan"
  });

  $("#deleteFilter").on("click", function () {
    $projectNameFilter.val(null).trigger("change");
    $moderatorFilter.val(null).trigger("change");
    $monthFilter.val(null).trigger("change");
  });
</script>
</body>
</html>
