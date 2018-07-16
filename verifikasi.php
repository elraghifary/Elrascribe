<?php
require_once "src/database.php";

if (empty($_GET['id']) && empty($_GET['remember_token'])) {
    header("location: /");
    exit();
}

if (isset($_GET['id']) && isset($_GET['remember_token'])) {
    $id = $_GET['id'];
    $data = str_replace(array('-','_'), array('+','/'), $id);
    $mod4 = strlen($data) % 4;

    if ($mod4) {
        $data .= substr('====', $mod4);
    }

    $id = base64_decode($data);
    $remember_token = $_GET['remember_token'];

    $statusY = "1";
    $statusN = "0";

    $sql = "SELECT id, status FROM notulis WHERE id = '$id' AND token = '$remember_token' LIMIT 1";
    $res = mysqli_query($db, $sql) or die(mysqli_error($db));
    $row = mysqli_fetch_assoc($res);

    if (mysqli_num_rows($res) > 0) {
        if ($row['status'] == $statusN) {
            $new_token = md5(uniqid(rand()));

            $sql = "UPDATE notulis SET status = '$statusY', token = '$new_token' WHERE id = '$id'";
            $res = mysqli_query($db, $sql) or die(mysqli_error($db));

            $msg = "
                <div class='row center'>
                    <div class='card-content white-text center'>
                        <span class='card-title black-text'>Selamat!</span>
                        <span class='black-text'>Akun Anda telah berhasil diaktifkan. Akun dapat digunakan jika registrasi Anda telah disetujui oleh admin.</span>
                    </div>
                    <a class='waves-effect waves-light btn-large light-green darken-2' href='login.php'><i class='material-icons right'>arrow_forward</i>Masuk</a>
                </div>
                <div class='row'>
                    <div class='card-action center'>
                        <div class='col s6'>
                            <span class='black-text'>Belum punya akun? <a class='light-green-text text-darken-2' href='registrasi.php'>Daftar</a></span>
                        </div>
                        <div class='col s6'>
                            <span class='black-text'>Lupa kata sandi? <a class='light-green-text text-darken-2' href='lupa_password.php'>Ubah Kata Sandi</a></span>
                        </div>
                        <div class='row'></div>
                    </div>
                </div>
            ";
        } else {
            $msg = "
                <div class='row center'>
                    <div class='card-content white-text center'>
                        <span class='card-title black-text'>Halo!</span>
                        <span class='black-text'>Akun Anda sudah diaktifkan.</span>
                    </div>
                    <div class='progress'>
                        <div class='indeterminate'></div>
                    </div>
                </div>
                <div class='row'>
                    <div class='card-action center'>
                        <div class='col s6'>
                            <span class='black-text'>Punya akun? <a class='light-green-text text-darken-2' href='login.php'>Masuk</a></span>
                        </div>
                        <div class='col s6'>
                            <span class='black-text'>Lupa kata sandi? <a class='light-green-text text-darken-2' href='lupa_password.php'>Ubah Kata Sandi</a></span>
                        </div>
                        <div class='row'></div>
                    </div>
                </div>
            ";
        }
    } else {
        $msg = "
            <div class='row center'>
                <div class='card-content white-text center'>
                    <span class='card-title black-text'>Maaf!</span>
                    <span class='black-text'>Kami tidak mengenali permintaan registrasi akun Anda.</span>
                </div>
                <a class='waves-effect waves-light btn-large light-green darken-2' href='registrasi.php'><i class='material-icons right'>send</i>Daftar</a>
            </div>
            <div class='row'>
                <div class='card-action center'>
                    <div class='col s6'>
                        <span class='black-text'>Punya akun? <a class='light-green-text text-darken-2' href='login.php'>Masuk</a></span>
                    </div>
                    <div class='col s6'>
                        <span class='black-text'>Lupa kata sandi? <a class='light-green-text text-darken-2' href='lupa_password.php'>Ubah Kata Sandi</a></span>
                    </div>
                    <div class='row'></div>
                </div>
            </div>
        ";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>VERIFIKASI - Elrascribe</title>

        <!-- CSS -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.css" media="screen,projection"/>
        <link href="assets/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    </head>

    <body class="bg-image-verify">
        <?php include "menu.php"; ?>

        <main>
            <div class="container">
                <div class="section">
                    <div class="row">
                        <br><br>
                        <div class="enter"></div>
                        <div class="col s12">
                            <div class="card white hoverable">
                                <div class="container">
                                    <?php if(isset($msg)) { echo $msg; } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php include "link.php"; ?>

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
