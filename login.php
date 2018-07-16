<?php
session_start();

require_once "src/database.php";

if (isset($_SESSION['userSessionID']) != "" && isset($_SESSION['userSessionName']) != "" && isset($_SESSION['userSessionEmail']) != "") {
    header("location: /dasbor.php");
    exit();
}

if (isset($_POST['btn-login'])) {
    $email = trim($_POST['txtEmail']);
    $password = trim($_POST['txtPassword']);

    $sql = "SELECT * FROM notulis WHERE email = '$email'";
    $res = mysqli_query($db, $sql) or die(mysqli_error($db));

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        if ($row['status'] == "1") {
            if (password_verify($password, $row['password'])) {
                $_SESSION['userSessionID'] = $row['id'];
                $_SESSION['userSessionName'] = $row['nama'];
                $_SESSION['userSessionEmail'] = $row['email'];
                header("location: /dasbor.php");
                exit();
            } else {
                header("location: /login.php?salah");
                exit();
            }
        } else if ($row['status'] == "0") {
            if (!password_verify($password, $row['password'])) {
                header("location: /login.php?salah");
                exit();
            } else {
                header("location: /login.php?tidak_aktif");
                exit();
            }
        }
    } else {
        header("location: /login.php?tidak_ditemukan");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>LOGIN - Elrascribe</title>

        <!-- CSS -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.css" media="screen,projection"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link href="assets/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    </head>

    <body class="bg-image-login">
        <?php include "menu.php"; ?>

        <main>
            <div class="container">
                <div class="section">
                    <div class="row">
                        <br><br>
                        <div class="col s12 m6 center">
                            <h1 class="grey-text text-lighten-2"><b>Selamat Datang di Elrascribe</b></h1>
                            <h5 class="grey-text text-lighten-2 light">Masuk dan mulai kelola transkrip Anda</h5><br>
                            <a href="registrasi.php" class="btn-large waves-effect waves-light light-green darken-2">Buat Akun</a>
                            <div class="row"></div>
                        </div>
                        <div class="enter"></div>
                        <div class="col s12 m6">
                            <div class="card-panel hoverable">
                                <div class="row">
                                    <div class="card-content white-text center">
                                        <h5 class="light black-text">Masuk</h5>
                                    </div>
                                    <form id="loginForm" method="post">
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">mail</i>
                                            <label for="email">Email</label>
                                            <input id="email" name="txtEmail" type="email" data-error=".errorEmail">
                                            <div class="errorEmail"></div>
                                        </div>
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">lock_outline</i>
                                            <label for="password">Kata Sandi</label>
                                            <input id="password" name="txtPassword" type="password" data-error=".errorPassword">
                                            <div class="errorPassword"></div>
                                        </div>
                                        <div class="col s6">
                                            <a class="light-green-text text-darken-2" href="lupa_password.php">Lupa kata sandi?</a>
                                        </div>
                                        <div class="col s6 right-align">
                                            <button class="btn waves-effect waves-light light-green darken-2" type="submit" name="btn-login">Masuk</button>
                                        </div>
                                    </form>
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

        <?php
        if (isset($_GET['tidak_aktif'])) {
            ?>
            <script>
                Materialize.toast('Akun Anda belum aktif. Silahkan periksa kembali email Anda. &nbsp;<a onClick=\'closeToast()\'; href=\'#\'>CLOSE</a>', 10000);
            </script>
            <?php
        }
        ?>

        <?php
        if (isset($_GET['salah'])) {
            ?>
            <script>
                Materialize.toast('Kata sandi yang Anda masukkan salah. Silahkan coba lagi. &nbsp;<a onClick=\'closeToast()\'; href=\'#\'>CLOSE</a>', 10000);
            </script>
            <?php
        }
        ?>

        <?php
        if (isset($_GET['tidak_ditemukan'])) {
            ?>
            <script>
                Materialize.toast('Email yang Anda masukkan tidak ditemukan. Silahkan coba lagi. &nbsp;<a onClick=\'closeToast()\'; href=\'#\'>CLOSE</a>', 10000);
            </script>
            <?php
        }
        ?>

        <script>
            $("#loginForm").validate({
                rules: {
                    txtEmail: {
                        required: true,
                        email:true
                    },
                    txtPassword: {
                        required: true
                    },
                },
                messages: {
                    txtEmail: {
                        required: "Kolom ini tidak boleh kosong.",
                        email: "Silahkan masukkan alamat email yang benar."
                    },
                    txtPassword: {
                        required: "Kolom ini tidak boleh kosong."
                    }
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
