<?php
include "../header.php";
?>
<title>UBAH TRANSKRIP | Elrascribe</title>
<style type="text/css">
  .final {
    color: black;
    padding-right: 3px;
  }
  .interim {
    color: gray;
  }
  #results {
    font-size: 14px;
    font-weight: bold;
    border: 1px solid #ddd;
    padding: 15px;
    text-align: left;
    min-height: 150px;
  }
  .no-browser-support {
    display: none;
  }
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Ubah Transkrip
        <small>isian</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active"><a href="/transkrip/"><i class="fa fa-dashboard"></i> Transkrip</a></li>
        <li><a href="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>"> Ubah</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="no-browser-support">Maaf, web browser yang Anda gunakan tidak mendukung Web Speech API. Silahkan menggunakan web browser Google Chrome.</h3>
            </div>
            <form id="ftranscript" role="form">
              <div class="app">
                <div class="box-body">
                  <?php
                  $id = $_GET['id'];
                  $sql = "SELECT t.*, u.id AS id_notulis, u.nama AS user_name FROM transkrip t JOIN notulis u ON u.id = t.id_notulis WHERE t.id = '$id'";
                  $res = mysqli_query($db, $sql) or die(mysqli_error($db));
                  $row = mysqli_fetch_assoc($res);
                  ?>
                  <input type="hidden" id="crudmethod" value="E">
                  <input type="hidden" id="id" name="id" value="<?= $row['id']; ?>">
                  <input type="hidden" id="txtid" value="<?= $row['id']; ?>">
                  <div class="row">
                    <div class="col-sm-6 form-group">
                      <label for="txtUserID">Notulis *</label>
                      <input class="form-control" id="txtUserID" name="userID" type="text" value="<?= $row['user_name']; ?>" readonly/>
                    </div>
                    <div class="col-sm-6 form-group">
                      <label for="txtName">Proyek *</label>
                      <input class="form-control" id="txtName" name="name" type="text" value="<?= $row['proyek']; ?>" required/>
                    </div>
                    <span class="clearfix">
                  </div>
                  <div class="row">
                    <div class="col-sm-6 form-group">
                      <label for="txtIDI">IDI *</label>
                      <input class="form-control" id="txtIDI" name="idi" type="text" value="<?= $row['idi']; ?>" required/>
                    </div>
                    <div class="col-sm-6 form-group">
                      <label for="txtDate">Tanggal *</label>
                      <input class="form-control" id="txtDate" name="date" type="text" value="<?= $row['tanggal']; ?>" required/>
                    </div>
                    <span class="clearfix">
                  </div>
                  <div class="row">
                    <div class="col-sm-6 form-group">
                      <label for="txtDay">Hari *</label>
                      <select class="form-control" id="txtDay" name="day" type="text" required/>
                        <?php
                        setlocale(LC_TIME, 'Indonesian');
                        $date = strftime("%A");
                        $day = array("Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu");
                        foreach ($day as $d) {
                        ?>
                          <option value="<?= $d; ?>" <?= $d == $row['hari'] ? 'selected' : ''; ?>><?= $d; ?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="txtTime">Waktu *</label>
                        <input class="form-control" id="txtTime" name="time" type="text" value="<?= $row['waktu']; ?>" required/>
                    </div>
                    <span class="clearfix">
                  </div>
                  <div class="row">
                    <div class="col-sm-6 form-group">
                      <label for="txtModerator">Moderator *</label>
                      <input class="form-control" id="txtModerator" name="moderator" type="text" value="<?= $row['moderator']; ?>" required/>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="txtCriteria">Kriteria *</label>
                        <input class="form-control" id="txtCriteria" name="criteria" type="text" value="<?= $row['kriteria']; ?>" required/>
                    </div>
                    <span class="clearfix">
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label>Pengenalan Kata</label>
                      <div id="results">
                        <span id="final_span" class="final"></span>
                        <span id="interim_span" class="interim"></span>
                        <p>
                      </div>
                    </div>
                    <div class="col-sm-12 form-group">
                      <label for="body">Isi Transkrip</label>
                      <textarea class="form-control col-sm-12" id="txtBody" name="body" required><?= $row['isi'] ?></textarea>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-12">
                      <button type="submit" class="btn btn-success" id="btn-save">Simpan</button>
                      <a href="./" class="btn btn-default" role="button">Batal</a>
                      <p>Klik tombol <strong>Simpan</strong> untuk menyimpan data transkrip.</p>
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
              </div>
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
  $("#txtDay").select2({
    placeholder: "Pilih Hari",
    minimumResultsForSearch: Infinity
  });
  CKEDITOR.replace('txtBody');
  CKEDITOR.config.autoParagraph = false;
</script>
<script src="./transkrip.js"></script>
<script src="./noise.js"></script>
</body>
</html>
