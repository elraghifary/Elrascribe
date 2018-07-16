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

    $sql = "SELECT nama FROM notulis WHERE id = '$id' AND token = '$remember_token'";
    $res = mysqli_query($db, $sql) or die(mysqli_error($db));
    $row = mysqli_fetch_assoc($res);

    $name = $row['nama'];

    if (mysqli_num_rows($res) == 1) {
        $msg = "
                <div class='row'>
                    <div class='card-content center'>
                        <span class='card-title black-text'>Ubah Kata Sadi</span>
                        <span class='black-text'>Halo, $name. Silahkan masukkan kata sandi yang baru.</span>
                    </div>
                    <form id='resetForm' method='post'>
                        <div class='input-field col s12'>
                            <i class='material-icons prefix'>lock_outline</i>
                            <label for='password'>Kata Sandi</label>
                            <input id='password' name='txtPassword' type='password' data-error='.errorPassword'>
                            <div class='errorPassword'></div>
                        </div>
                        <div class='input-field col s12'>
                            <i class='material-icons prefix'>lock_outline</i>
                            <label for='cpassword'>Ulangi Kata Sandi</label>
                            <input id='cpassword' name='txtCpassword' type='password' data-error='.errorCpassword'>
                            <div class='errorCpassword'></div>
                        </div>
                        <div class='col s12 center'>
                            <button class='btn waves-effect waves-light light-green darken-2' type='submit' name='btn-reset'>Ubah Password</button>
                        </div>
                    </form>
                </div>
            ";

        if (isset($_POST['btn-reset'])) {
            $password = $_POST['txtPassword'];
            $new_token = md5(uniqid(rand()));
            $password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE notulis SET password = '$password', token = '$new_token' WHERE id = '$id'";
            $res = mysqli_query($db, $sql) or die(mysqli_error($db));

            $msg = "
                <div class='row center'>
                    <div class='card-content white-text center'>
                        <span class='card-title black-text'>Sukses!</span>
                        <span class='black-text'>Kata sandi akun Anda telah berhasil diubah. Silahkan masuk dengan menggunakan kata sandi yang baru.</span>
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

            // header("refresh:10;login.php");
        }
    } else {
        $msg = "
            <div class='row center'>
                <div class='card-content white-text center'>
                    <span class='card-title black-text'>Maaf!</span>
                    <span class='black-text'>Kami tidak mengenali permintaan ubah kata sandi akun Anda.</span>
                </div>
                <a class='waves-effect waves-light btn-large light-green darken-2' href='lupa_password.php'><i class='material-icons right'>send</i>Lupa Kata Sandi</a>
            </div>
            <div class='row'>
                <div class='card-action center'>
                    <div class='col s6'>
                        <span class='black-text'>Punya akun? <a class='light-green-text text-darken-2' href='login.php'>Masuk</a></span>
                    </div>
                    <div class='col s6'>
                        <span class='black-text'>Belum punya akun? <a class='light-green-text text-darken-2' href='registrasi.php'>Daftar</a></span>
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
        <title>UBAH KATA SANDI - Elrascribe</title>

        <!-- CSS -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.css" media="screen,projection"/>
        <link href="assets/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    </head>

    <body class="bg-image-reset">
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
                                    <div class="row"></div>
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
        <script src="assets/js/jquery.validate.min.js"></script>
        <script src="assets/js/init.js"></script>
        <script>
            $("#resetForm").validate({
                rules: {
                    txtPassword: {
                        required: true,
                        minlength: 4
                    },
                    txtCpassword: {
                        required: true,
                        minlength: 4,
                        equalTo: "#password"
                    },
                },
                messages: {
                    txtPassword: {
                        required: "Kolom ini tidak boleh kosong.",
                        minlength: "Silahkan masukkan kata sandi minimal 4 karakter."
                    },
                    txtCpassword: {
                        required: "Kolom ini tidak boleh kosong.",
                        minlength: "Silahkan masukkan kata sandi minimal 4 karakter.",
                        equalTo: "Silahkan masukkan kata sandi yang sama."
                    },
                },
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    var placement = $(element).data('error');
                    if (placement) {
                        $(placement).append(error)
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        </script>
    </body>
</html>
