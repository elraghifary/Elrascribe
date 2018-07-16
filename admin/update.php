<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../src/PHPMailer.php';
require '../src/SMTP.php';
require '../src/Exception.php';

require_once "../src/database.php";

if (isset($_SESSION['adminSessionID']) == "" && isset($_SESSION['adminSessionName']) == "" && isset($_SESSION['adminSessionEmail']) == "") {
    header("location: /403.php");
}

$id = $_POST['id'];
$status = $_POST['status'];
$name = $_POST['name'];
$email = $_POST['email'];

if ($status == "0") {
    $new_status = "1";
} else if ($status == "1") {
    $new_status = "0";
}

$sql = "UPDATE notulis SET status = '$new_status' WHERE id = '$id'";
$res = mysqli_query($db, $sql) or die(mysqli_error($db));

if ($res == true && $new_status == "1") {
    $message['error'] = "";
    $message['result'] = 1;
    $msg = "
        <!doctype html>
        <html>
          <head>
            <meta name='viewport' content='width=device-width'>
            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
            <title>Notifikasi Akun</title>
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
                    <span class='preheader' style='color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;'>Notifikasi Akun</span>
                    <table class='main' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;'>
                      <tr>
                        <td class='wrapper' style='font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;'>
                          <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;'>
                            <tr>
                              <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>
                                <center><p style='font-family: sans-serif; font-size: 18px; font-weight: bold; margin: 0; Margin-bottom: 15px;'>ELRASCRIBE</p></center>
                                <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>Halo $name,</p>
                                <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>Akun Anda telah disetujui oleh admin dan sudah dapat digunakan.</p>
                                <p>Terima kasih.</p>
                                <table border='0' cellpadding='0' cellspacing='0' class='btn btn-primary' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;'>
                                  <tbody>
                                    <tr>
                                      <td align='left' style='font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;'>
                                        <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;'>
                                          <tbody>
                                            <tr>
                                              <td style='font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #689F38; border-radius: 5px; text-align: center;'> <a href='https://scribe.elraghifary.com/login.php' target='_blank' style='display: inline-block; color: #ffffff; background-color: #689F38; border: solid 1px #689F38; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #689F38;'>MASUK ELRASCRIBE</a> </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
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

    $subject = "Notifikasi Akun";
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
        $mail->msgHTML($msg);
        $mail->send();
    } catch (Exception $e) {
        echo $e->errorMessage();
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
} else if ($res == true && $new_status == "0") { 
    $message['error'] = "";
    $message['result'] = 1;
} else {
    $message['error'] = mysqli_error($db);
    $message['result'] = 0;
}

echo json_encode($message);
?>
