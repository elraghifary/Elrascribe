<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Elrascribe</title>

        <!-- CSS -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.css" media="screen,projection"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link href="assets/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    </head>

    <body>
        <?php include "menu.php"; ?>

        <main>
            <div class="parallax-container valign-wrapper">
                <div class="section">
                    <div class="container">
                        <h1 class="header col s12 center">ELRASCRIBE</h1>
                        <div class="row center">
                          <h5 class="header col s12 light">Aplikasi transkrip wawancara menggunakan teknologi Speech Recognition</h5>
                        </div>
                        <div class="row center">
                          <a href="registrasi.php" class="btn-large waves-effect waves-light light-green darken-2">Daftar</a>
                          <a href="lupa_password.php" class="btn-large waves-effect waves-light light-green darken-2">Lupa Password</a>
                          <a href="login.php" class="btn-large waves-effect waves-light light-green darken-2">Masuk</a>
                        </div>
                    </div>
                </div>
                <div class="parallax"><img src="assets/images/bg20.jpg" alt="Unsplash @firsara"></div>
            </div>
            <!-- <div class="container">
                <div class="section">
                    <div class="row">
                        <div class="col s12 m4">
                            <div class="icon-block">
                                <h2 class="center light-green-text"><i class="material-icons">queue_music</i></h2>
                                <h5 class="center">Speech Recognition</h5>
                                <p class="center light">Transcripts can be easily done using Speech Recognition by recognizing speech and converting it into a sentence.</p>
                        </div>
                    </div>

                    <div class="col s12 m4">
                        <div class="icon-block">
                            <h2 class="center light-green-text"><i class="material-icons">description</i></h2>
                            <h5 class="center">Text Editor</h5>
                            <p class="center light">Text Editor makes your transcript easy to modify.</p>
                        </div>
                    </div>

                    <div class="col s12 m4">
                        <div class="icon-block">
                            <h2 class="center light-green-text"><i class="material-icons">play_circle_filled</i></h2>
                            <h5 class="center">Noise Suppression</h5>
                            <p class="light">Noise Suppression reduce noise during interviews used in Speech Recognition system.</p>
                        </div>
                    </div>
                  </div>

                </div>
            </div> -->
        </main>

        <?php include "link.php"; ?>

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
