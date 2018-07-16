<?php
session_start();

require_once "src/database.php";

if (isset($_SESSION['userSessionID']) == "" && isset($_SESSION['userSessionName']) == "" && isset($_SESSION['userSessionEmail']) == "") {
    header("location: /403.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="/bower_components/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/bower_components/datatables/datatables.min.css">
  <!-- select2 -->
  <link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">
  <!-- pNotify -->
  <link rel="stylesheet" href="/bower_components/pnotify/dist/pnotify.custom.min.css">
  <!-- formValidation -->
  <link href="/assets/css/formValidation.min.css" rel="stylesheet">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/assets/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/assets/css/skins/skin-green.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <style type="text/css">
    body {
      font-family: 'Roboto', sans-serif;
    }
  </style>
</head>
<body class="hold-transition skin-green sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="./" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>EL</b>RA</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Elra</b>scribe</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="/assets/images/user.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?= $_SESSION['userSessionName']; ?></p>
          <p>Notulis</p>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
        <li>
          <a href="/dasbor.php">
            <i class="fa fa-dashboard"></i> <span>Dasbor</span>
          </a>
        </li>
        <li>
          <a href="/transkrip/">
            <i class="fa fa-file-text"></i> <span>Transkrip</span>
          </a>
        </li>
        <li class="header">KONFIGURASI</li>
        <li>
          <a href="/logout.php">
            <i class="fa fa-sign-out"></i> <span>Keluar</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  <title>DASBOR | Elrascribe</title>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dasbor
        <small>statistik</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active"><a href="dasbor.php"><i class="fa fa-dashboard"></i> Dasbor</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-sm-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <?php
                $id = $_SESSION['userSessionID'];
                $sql = "SELECT * FROM transkrip WHERE id_notulis = '$id'";
                $res = mysqli_query($db, $sql) or die(mysqli_error($db));
                $row = mysqli_num_rows($res);
              ?>
              <h3><?= $row; ?></h3>

              <p>Transkrip</p>
            </div>
            <div class="icon">
              <i class="ion-document-text"></i>
            </div>
            <a href="/transkrip/" class="small-box-footer">Informasi lebih <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Catatan</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <p>Pastikan Anda menggunakan web browser Google Chrome untuk mengakses halaman ini.</p>
            </div>
            <!-- ./box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <h3 style="margin-top: 0px">Petunjuk</h3>
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-light-blue"><i class="fa fa-plus"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><strong>Tombol Tambah</strong></span>
              <span>Klik tombol ini untuk menambah transkrip.</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-eye"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><strong>Tombol Lihat</strong></span>
              <span>Klik tombol ini untuk melihat transkrip.</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-edit"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><strong>Tombol Ubah</strong></span>
              <span>Klik tombol ini untuk mengubah transkrip.</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-trash"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><strong>Tombol Hapus</strong></span>
              <span>Klik tombol ini untuk menghapus transkrip.</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon"><img src="/assets/images/mic.png"></span>

            <div class="info-box-content">
              <span class="info-box-text"><strong>Tombol Mikrofon</strong></span>
              <span>Klik tombol ini untuk memulai Speech Recognition.</span>
              <span>Cara singkat tekan ALT + 3</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon"><img src="/assets/images/moderator.png"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><strong>Tombol Kode Moderator</strong></span>
              <span>Klik tombol ini untuk menambah kode 'M : ' pada editor teks.</span>
              <span>Cara singkat tekan ALT + 2</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon"><img src="/assets/images/responden.png"></span>

            <div class="info-box-content">
              <span class="info-box-text"><strong>Tombol Kode Responden</strong></span>
              <span>Klik tombol ini untuk menambah kode 'R : ' pada editor teks.</span>
              <span>Cara singkat tekan ALT + 1</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Hak Cipta &copy; 2018 <a href="https://elraghifary.com">Elrascribe</a>.</strong>
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/assets/js/adminlte.min.js"></script>
