<?php
session_start();

require_once "../src/database.php";

if (isset($_SESSION['adminSessionID']) != "" && isset($_SESSION['adminSessionName']) != "" && isset($_SESSION['adminSessionEmail']) != "") {
    header("location: ./dasbor.php");
    exit();
}

if (isset($_POST['btn-login'])) {
    $email = trim($_POST['txtEmail']);
    $password = trim($_POST['txtPassword']);

    $sql = "SELECT * FROM admin WHERE email = '$email'";
    $res = mysqli_query($db, $sql) or die(mysqli_error($db));

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        if (password_verify($password, $row['password'])) {
            $_SESSION['adminSessionID'] = $row['id'];
            $_SESSION['adminSessionName'] = $row['nama'];
            $_SESSION['adminSessionEmail'] = $row['email'];
            header("location: ./dasbor.php");
            exit();
        } else {
            header("location: ./?salah");
            exit();
        }
    } else {
        header("location: ./?tidak_ditemukan");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>LOGIN - Elrascribe Admin</title>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
        <link href="/assets/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
        <link href="/assets/css/font-awesome.min.css" type="text/css" rel="stylesheet" media="screen,projection"/>
        <link href="/assets/css/login.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    </head>
    <body style="background-color: #eeeeee">

        <main>
            <div class="login-wrapper">
                <div class="row">
                    <div class="col s12">
                        <div class="card">
                            <div class="card-image">
                                <img src="/assets/images/bg21.jpg">
                                <span class="card-title"><h5>Masuk ke <b class="main-text"><a class="light-green-text text-darken-2" href="/">Elrascribe</a></b> Admin</h5></span>
                            </div>
                            <form id="loginForm" method="post">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">mail</i>
                                            <label for="email">Email</label>
                                            <input id="email" name="txtEmail" type="email" data-error=".errorEmail">
                                            <div class="errorEmail"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">lock_outline</i>
                                            <label for="password">Kata Sandi</label>
                                            <input id="password" name="txtPassword" type="password" data-error=".errorPassword">
                                            <div class="errorPassword"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <div class="right-align">
                                        <button class="btn waves-effect waves-light light-green darken-2" type="submit" name="btn-login">Masuk</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/materialize.js"></script>
        <script src="/assets/js/jquery.validate.min.js"></script>
        <script src="/assets/js/init.js"></script>

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