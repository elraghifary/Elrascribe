<?php
session_start();

require_once "../src/database.php";

if (isset($_SESSION['adminSessionID']) == "" && isset($_SESSION['adminSessionName']) == "" && isset($_SESSION['adminSessionEmail']) == "") {
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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.0.0-9/css/ionicons.min.css" />
  <!-- DataTables -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-colvis-1.5.1/b-html5-1.5.1/b-print-1.5.1/r-2.2.1/datatables.min.css"/>
  <!-- select2 -->
  <link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">
  <!-- pNotify -->
  <link rel="stylesheet" href="/bower_components/pnotify/dist/pnotify.custom.min.css">
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

    .no-browser-support {
      display: none;
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
          <p><?= $_SESSION['adminSessionName']; ?></p>
          <p>Administrator</p>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
        <li>
          <a href="./dasbor.php">
            <i class="fa fa-dashboard"></i> <span>Dasbor</span>
          </a>
        </li>
        <li>
          <a href="./notulis.php">
            <i class="fa fa-file-text"></i> <span>Notulis</span>
          </a>
        </li>
        <li class="header">KONFIGURASI</li>
        <li>
          <a href="./logout.php">
            <i class="fa fa-sign-out"></i> <span>Keluar</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
