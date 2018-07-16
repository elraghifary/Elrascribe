<?php
include "../header.php";
?>
<title>TAMBAH TRANSKRIP | Elrascribe</title>
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
  ul {
    list-style: none;
  }
  pre#log {
      height: 200px;
      overflow: auto;
      font-size: 1em;
  }
  audio {
      display: block;
  }
  #recordingslist audio {
    display: block;
    margin-left: -40px;
  }
  #recordingslist a {
    display: block;
    margin-left: -40px;
  }
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        TAMBAH TRANSKRIP
        <small>isian</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active"><a href="/transkrip/"><i class="fa fa-dashboard"></i> Transkrip</a></li>
        <li><a href="/transkrip/tambah.php"> Tambah</a></li>
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
                  <input type="hidden" id="crudmethod" value="N">
                  <input type="hidden" id="txtid" value="0">
                  <!-- <div class="row">
                    <div class="col-sm-12">
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#identificationModal">Identification</button>
                       Identification Modal 
                      <div class="modal fade" id="identificationModal" tabindex="-1" role="dialog" aria-labelledby="identificationModalLabel">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="identificationModalLabel">Enrol for Identification</h4>
                            </div>
                            <div class="modal-body">
                              <div class="row">
                                <div class="col-sm-12">
                                  <button type="button" class="btn bg-navy" onclick="enrollNewProfile();">Speak for Identification</button>
                                  <button type="button" class="btn bg-navy" onclick="startListeningForIdentification();">Identify Speaker</button>
                                  <button type="button" class="btn bg-navy" onclick="BurnItAll();">Delete All Profiles</button>
                                  <hr>
                                  <pre id="log"></pre>
                                </div>
                              </div>
                              <div class="row">
                              <div class="col-sm-12">
                                <div id="boxStatus" class="box box-default box-solid">
                                  <div class="box-header">
                                    <p id="idStatus" class="box-title">Press the <strong>Speak for Identification</strong> button to start.</p>
                                  </div>
                                </div>
                              </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> -->

                  <!--<hr>-->
                  <div class="row">
                    <div class="col-sm-6 form-group">
                      <label for="txtUserID">Notulis *</label>
                      <input class="form-control" id="txtUserID" name="userID" type="text" value="<?= $_SESSION['userSessionName']; ?>" readonly/>
                    </div>
                    <div class="col-sm-6 form-group">
                      <label for="txtName">Proyek *</label>
                      <input class="form-control" id="txtName" name="name" type="text" list="projectName" />
                      <datalist id="projectName">
                        <?php
                        $id = $_SESSION['userSessionID'];
                        $sql = "SELECT DISTINCT(proyek) FROM transkrip WHERE id_notulis = '$id'";
                        $res = mysqli_query($db, $sql) or die(mysqli_error($db));
                        while ($row = mysqli_fetch_assoc($res)) {
                          ?>
                          <option value="<?= $row['proyek']; ?>"></option>
                          <?php
                        }
                        ?>
                      </datalist>
                    </div>
                    <span class="clearfix">
                  </div>
                  <div class="row">
                    <div class="col-sm-6 form-group">
                      <label for="txtIDI">IDI *</label>
                      <input class="form-control" id="txtIDI" name="idi" type="text" value="1" />
                    </div>
                    <div class="col-sm-6 form-group">
                      <label for="txtDate">Tanggal *</label>
                      <input class="form-control" id="txtDate" name="date" type="text" value="<?= date("Y-m-d"); ?>" />
                    </div>
                    <span class="clearfix">
                  </div>
                  <div class="row">
                    <div class="col-sm-6 form-group">
                      <label for="txtDay">Hari *</label>
                      <select class="form-control" id="txtDay" name="day" type="text"/>
                        <?php
                        setlocale(LC_TIME, 'Indonesian');
                        $date = strftime("%A");
                        $day = array("Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu");
                        foreach ($day as $d) {
                        ?>
                          <option value="<?= $d; ?>" <?= $d == $date ? 'selected' : ''; ?>><?= $d; ?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-sm-6 form-group">
                      <label for="txtTime">Waktu *</label>
                      <input class="form-control" id="txtTime" name="time" type="text" value="<?= date("h:i"); ?>" />
                    </div>
                    <span class="clearfix">
                  </div>
                  <div class="row">
                    <div class="col-sm-6 form-group">
                      <label for="txtModerator">Moderator *</label>
                      <input class="form-control" id="txtModerator" name="moderator" type="text"/>
                    </div>
                    <div class="col-sm-6 form-group">
                      <label for="txtCriteria">Kriteria *</label>
                      <input class="form-control" id="txtCriteria" name="criteria" type="text"/>
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
                      <textarea class="form-control col-sm-12" id="txtBody" name="body" required></textarea>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-6">
                      <button type="submit" class="btn btn-success" id="btn-save">Simpan</button>
                      <a href="./" class="btn btn-default" role="button">Batal</a>
                      <p>Klik tombol <strong>Simpan</strong> untuk menyimpan data transkrip.</p>
                    </div>
                    <div class="col-sm-6">
                      <button class="btn btn-primary" onclick="startRecording(this);">Mulai Merekam</button>
                      <button class="btn btn-warning" onclick="stopRecording(this);" disabled>Berhenti Merekam</button>
                      <p id="instructions">Klik tombol <strong>Mulai Merekam</strong> untuk merekam audio.</p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6">
                      <ul id="recordingslist"></ul>
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
<script src="./transkrip.js"></script>
<script src="./noise.js"></script>
<script src="/bower_components/ckeditor/plugins/ckwebspeech/plugin.js"></script>
<script src="./recorder.js"></script>
<!--<script src="./identification.js"></script>-->
<script src="./audio.js"></script>
<!-- App -->
<script type="text/javascript">
  var boxStatus = $('#boxStatus');
  var idStatus = $('#idStatus');
  var audio;
  var audioContext;

  function onMediaSuccess(stream, callback, secondsOfAudio) {
      audioContext = audioContext || new window.AudioContext;
      var input = audioContext.createMediaStreamSource(stream);
      audio = new Recorder(input);
      audio.record();

      setTimeout(() => { StopListening(callback); }, secondsOfAudio*1000);
  }

  function StopListening(callback){
      console.log('...working...');
      audio && audio.stop();
      audio.exportWAV(function(blob) {
          callback(blob);
      });
      audio.clear();
  }

  $("#txtName").on("input", function () {
    var value = {
      projectName: this.value
    };
    $.ajax({
      url : "idi.php",
      type: "POST",
      data : value,
      success: function(data, textStatus, jqXHR) {
        var idi = parseInt(data) + 1 || 1;
        $('#txtIDI').val(idi);
      }
    });
  });
  $("#txtDay").select2({
    placeholder: "Pilih Hari",
    minimumResultsForSearch: Infinity
  });
  CKEDITOR.replace('txtBody');
  CKEDITOR.config.autoParagraph = false;
  function startUserMedia(stream) {
    getMicrophoneAccess();
    initializeNoiseSuppressionModule();
    suppressNoise = true;
  }

  function startRecording(button) {
    recorder && recorder.record();
    button.disabled = true;
    button.nextElementSibling.disabled = false;
    instructions.text('Recording...');
  }

  function stopRecording(button) {
    recorder && recorder.stop();
    button.disabled = true;
    button.previousElementSibling.disabled = false;
    instructions.text('Stopped recording.');

    createDownloadLink();

    recorder.clear();
    stopMicrophone();
  }

  function createDownloadLink() {
    recorder && recorder.exportWAV(function(blob) {
      var url = URL.createObjectURL(blob);
      var li = document.createElement('li');
      var au = document.createElement('audio');
      var hf = document.createElement('a');

      au.controls = true;
      au.src = url;
      hf.href = url;
      hf.download = new Date().toDateString() + '.wav';
      hf.innerHTML = 'Download : ' + hf.download;
      li.appendChild(au);
      li.appendChild(hf);
      recordingslist.appendChild(li);
    });
  }

  window.onload = function init() {
    try {
      window.AudioContext = window.AudioContext || window.webkitAudioContext;
      navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia;
      window.URL = window.URL || window.webkitURL;

      audio_context = new AudioContext();
    } catch (e) {
      alert('Web Audio API tidak dapat digunakan pada web browser ini.');
    }

    navigator.getUserMedia({audio: true}, startUserMedia, function(e) {
      console.log('Tidak ada masukan audio: ' + e);
    });
  };
</script>
</body>
</html>
