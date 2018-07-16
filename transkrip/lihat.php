<?php
include "../header.php";
?>
<title>LIHAT TRANSKRIP | Elrascribe</title>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lihat Transkrip
        <small>isian</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active"><a href="/transkrip/"><i class="fa fa-dashboard"></i> Transkrip</a></li>
        <li><a href="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>"> Lihat</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <form id="ftranscript" role="form">
              <div class="box-body">
                <?php
                $id = $_GET['id'];
                $sql = "SELECT t.*, u.nama AS user_name FROM transkrip t JOIN notulis u ON u.id = t.id_notulis WHERE t.id = '$id'";
                $res = mysqli_query($db, $sql) or die(mysqli_error($db));
                $row = mysqli_fetch_assoc($res);
                if ($row['id_notulis'] != $_SESSION['userSessionID']) {
                  echo '<script type="text/javascript">
                          window.location = "/404.php"
                        </script>';
                }
                ?>
                <input type="hidden" id="crudmethod" value="E">
                <input type="hidden" id="id" name="id" value="<?= $row['id']; ?>">
                <input type="hidden" id="txtid" value="<?= $row['id']; ?>">
                <div class="row">
                  <div class="col-sm-12">
                    <a href="./ekspor.php?id=<?= $row['id']; ?>" class="btn btn-primary" role="button">Ekspor</a>
                    <a href="./" class="btn btn-default" role="button">Kembali</a>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="txtUserID">Notulis</label>
                    <input class="form-control" id="txtUserID" name="userID" type="text" value="<?= $row['user_name']; ?>" style="background-color: white;" readonly />
                  </div>
                  <div class="col-sm-6 form-group">
                    <label for="txtName">Proyek</label>
                    <input class="form-control" id="txtName" name="name" type="text" value="<?= $row['proyek']; ?>" style="background-color: white;" readonly />
                  </div>
                  <span class="clearfix">
                </div>
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="txtIDI">IDI</label>
                    <input class="form-control" id="txtIDI" name="idi" type="text" value="<?= $row['idi']; ?>" style="background-color: white;" readonly />
                  </div>
                  <div class="col-sm-6 form-group">
                    <label for="txtDate">Tanggal</label>
                    <input class="form-control" id="txtDate" name="date" type="text" value="<?php setlocale(LC_TIME, 'Indonesian'); echo strftime("%d %B %Y", strtotime($row['tanggal'])); ?>" style="background-color: white;" readonly />
                  </div>
                  <span class="clearfix">
                </div>
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="txtDay">Hari</label>
                    <input class="form-control" id="txtDay" name="day" type="text" value="<?= $row['hari']; ?>" style="background-color: white;" readonly />
                  </div>
                  <div class="col-sm-6 form-group">
                      <label for="txtTime">Waktu</label>
                      <input class="form-control" id="txtTime" name="time" type="text" value="<?= $row['waktu']; ?>" style="background-color: white;" readonly />
                  </div>
                  <span class="clearfix">
                </div>
                <div class="row">
                  <div class="col-sm-6 form-group">
                    <label for="txtModerator">Moderator</label>
                    <input class="form-control" id="txtModerator" name="moderator" type="text" value="<?= $row['moderator']; ?>" style="background-color: white;" readonly />
                  </div>
                  <div class="col-sm-6 form-group">
                      <label for="txtCriteria">Kriteria</label>
                      <input class="form-control" id="txtCriteria" name="criteria" type="text" value="<?= $row['kriteria']; ?>" style="background-color: white;" readonly />
                  </div>
                  <span class="clearfix">
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label for="body">Isi Transkrip</label>
                    <textarea class="form-control col-sm-12" id="txtBody" name="body" required><?= $row['isi'] ?></textarea>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
            </form>
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
<!-- App -->
<script type="text/javascript">
  CKEDITOR.replace('txtBody');
  CKEDITOR.config.readOnly = true;
</script>
</body>
</html>
