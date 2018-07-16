<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

require_once "src/database.php";

if (isset($_SESSION['userSessionID']) != "" && isset($_SESSION['userSessionName']) != "" && isset($_SESSION['userSessionEmail']) != "") {
    header("location: /dasbor.php");
}

if (isset($_POST['btn-register'])) {
    $name = trim($_POST['txtName']);
    $email = trim($_POST['txtEmail']);
    $phone = trim($_POST['txtPhone']);
    $password = trim($_POST['txtPassword']);
    $remember_token = md5(uniqid(rand()));

    $sql = "SELECT * FROM notulis WHERE email = '$email'";
    $res = mysqli_query($db, $sql) or die(mysqli_error($db));

    if (mysqli_num_rows($res) > 0) {
        $msg = "
            <script>
                Materialize.toast('Email yang Anda masukkan sudah terdaftar. Silahkan coba yang lain. &nbsp;<a onClick=\'closeToast()\'; href=\'#\'>CLOSE</a>', 10000);
            </script>
        ";
    } else {
        if ($res == true) {
            $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO notulis(id_admin, nama, email, telepon, password, token) VALUES('1', '$name', '$email', '$phone', '$password', '$remember_token')";
            $res = mysqli_query($db, $sql) or die(mysqli_error($db));

            $id = mysqli_insert_id($db);
            $data = base64_encode($id);
            $data = str_replace(array('+','/','='), array('-','_',''), $data);
            $key = $data;
            $id = $key;

            $message = "
                <!doctype html>
                <html>
                  <head>
                    <meta name='viewport' content='width=device-width'>
                    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
                    <title>Registrasi Akun</title>
                    <style>
                    @media only screen and (max-width: 620px) {
                      table[class=body] h1 {
                        font-size: 28px !important;
                        margin-bottom: 10px !important;
                      }
                      table[class=body] p,
                        table[class=body] ul,
                        table[class=body] ol,
                        table[class=body] td,
                        table[class=body] span,
                        table[class=body] a {
                        font-size: 16px !important;
                      }
                      table[class=body] .wrapper,
                        table[class=body] .article {
                        padding: 10px !important;
                      }
                      table[class=body] .content {
                        padding: 0 !important;
                      }
                      table[class=body] .container {
                        padding: 0 !important;
                        width: 100% !important;
                      }
                      table[class=body] .main {
                        border-left-width: 0 !important;
                        border-radius: 0 !important;
                        border-right-width: 0 !important;
                      }
                      table[class=body] .btn table {
                        width: 100% !important;
                      }
                      table[class=body] .btn a {
                        width: 100% !important;
                      }
                      table[class=body] .img-responsive {
                        height: auto !important;
                        max-width: 100% !important;
                        width: auto !important;
                      }
                    }
                    @media all {
                      .ExternalClass {
                        width: 100%;
                      }
                      .ExternalClass,
                        .ExternalClass p,
                        .ExternalClass span,
                        .ExternalClass font,
                        .ExternalClass td,
                        .ExternalClass div {
                          line-height: 100%;
                        }
                      .apple-link a {
                        color: inherit !important;
                        font-family: inherit !important;
                        font-size: inherit !important;
                        font-weight: inherit !important;
                        line-height: inherit !important;
                        text-decoration: none !important;
                      }
                      .btn-primary table td:hover {
                        background-color: #34495e !important;
                      }
                      .btn-primary a:hover {
                        background-color: #34495e !important;
                        border-color: #34495e !important;
                      }
                    }
                    </style>
                  </head>
                  <body class='' style='background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;'>
                    <table border='0' cellpadding='0' cellspacing='0' class='body' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;'>
                      <tr>
                        <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>&nbsp;</td>
                        <td class='container' style='font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;'>
                          <div class='content' style='box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;'>
                            <span class='preheader' style='color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;'>Registrasi Akun</span>
                            <table class='main' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;'>
                              <tr>
                                <td class='wrapper' style='font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;'>
                                  <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;'>
                                    <tr>
                                      <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>
                                        <center><p style='font-family: sans-serif; font-size: 18px; font-weight: bold; margin: 0; Margin-bottom: 15px;'>ELRASCRIBE</p></center>
                                        <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>Halo $name,</p>
                                        <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>Terima kasih telah mendaftar sebagai pengguna Elrascribe. Anda akan diberi tahu melalui email jika registrasi Anda telah disetujui oleh admin.</p>
                                        <p>Terima kasih.</p>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                            <div class='footer' style='clear: both; Margin-top: 10px; text-align: center; width: 100%;'>
                              <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;'>
                                <tr>
                                  <td class='content-block' style='font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;'>
                                    <span class='apple-link' style='color: #999999; font-size: 12px; text-align: center;'>Elrascribe © 2018</span>
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </div>
                        </td>
                        <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>&nbsp;</td>
                      </tr>
                    </table>
                  </body>
                </html>
            ";

            $subject = "Registrasi Akun";
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->SMTPDebug = 0;
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                $mail->Username = "EMAIL_USER";
                $mail->Password = "EMAIL_PASS";
                $mail->setFrom('scribe@elraghifary.com', 'Elrascribe');
                $mail->addReplyTo('scribe@elraghifary.com', 'Elrascribe');
                $mail->addAddress($email);
                $mail->Subject = $subject;
                $mail->IsHTML(true);
                $mail->msgHTML($message);
                $mail->send();
            } catch (Exception $e) {
                echo $e->errorMessage();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            $msg = "
                <script>
                    Materialize.toast('Kami telah mengirim email ke $email. &nbsp; <a onClick=\'closeToast()\'; href=\'#\'>CLOSE</a>', 15000);
                </script>
            ";
        } else {
            $msg = "
                <script>
                    Materialize.toast('Maaf, kami tidak dapat memproses registrasi akun Anda. &nbsp;<a onClick=\'closeToast()\'; href=\'#\'>CLOSE</a>', 10000);
                </script>
            ";
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>REGISTRASI - Elrascribe</title>

        <!-- CSS -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.css" media="screen,projection"/>
        <link href="assets/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    </head>

    <body class="bg-image-register">
        <?php include "menu.php"; ?>

        <main>
            <div class="container">
                <div class="section">
                    <div class="row">
                        <br><br>
                        <div class="enter"></div>
                        <div class="col s12 m6">
                            <div class="card-panel hoverable">
                                <div class="row">
                                    <div class="card-content white-text center">
                                        <h5 class="light black-text">Buat akun Elrascribe</h5>
                                    </div>
                                    <form id="registerForm" method="post">
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">account_circle</i>
                                            <label for="name">Nama</label>
                                            <input id="name" name="txtName" type="text" data-error=".errorName">
                                            <div class="errorName"></div>
                                        </div>
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
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">lock_outline</i>
                                            <label for="cpassword">Ulangi Kata Sandi</label>
                                            <input id="cpassword" name="txtCpassword" type="password" data-error=".errorCpassword">
                                            <div class="errorCpassword"></div>
                                        </div>
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">phone</i>
                                            <label for="phone">Nomor Telepon</label>
                                            <input id="phone" name="txtPhone" type="text" data-error=".errorPhone">
                                            <div class="errorPhone"></div>
                                        </div>
                                        <div class="col s6">
                                            <a class="light-green-text text-darken-2" href="/login.php">Punya akun?</a>
                                        </div>
                                        <div class="col s6 right-align">
                                            <button class="btn waves-effect waves-light light-green darken-2" type="submit" name="btn-register">Daftar <i class="material-icons right">send</i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 center top">
                            <div class="row">
                                <div class="col s12">
                                    <div class="icon-block">
                                        <h2 class="center white-text"><i class="material-icons">flag</i></h2>
                                        <h3 class="grey-text text-lighten-2">Daftar</h3>
                                        <h5 class="grey-text text-lighten-2 light">Daftar dan konfirmasi akun Anda</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <div class="icon-block">
                                        <h2 class="center white-text"><i class="material-icons">library_books</i></h2>
                                        <h3 class="grey-text text-lighten-2">Petunjuk</h3>
                                        <h5 class="grey-text text-lighten-2 light">Silahkan baca petunjuk pada dasbor setelah masuk</h5>
                                    </div>
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
            $("#registerForm").validate({
                rules: {
                    txtName: {
                        required: true,
                        lettersonly: true
                    },
                    txtEmail: {
                        required: true,
                        email:true
                    },
                    txtPhone: {
                        digits: true,
                        minlength: 9
                    },
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
                    txtName: {
                        required: "Kolom ini tidak boleh kosong."
                    },
                    txtEmail: {
                        required: "Kolom ini tidak boleh kosong.",
                        email: "Silahkan masukkan alamat email yang benar."
                    },
                    txtPhone: {
                        digits: "Silahkan masukkan angka saja.",
                        minlength: "Silahkan masukkan kata sandi minimal 9 karakter."
                    },
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

            jQuery.validator.addMethod("lettersonly", function(value, element) {
                return this.optional(element) || /^[a-z\s]+$/i.test(value);
            }, "Silahkan masukkan huruf dan spasi saja.");
        </script>

        <?php if(isset($msg)) echo $msg; ?>
    </body>
</html>
