<?php

$email=isset($_GET['email']) ? $_GET['email'] : (isset($_COOKIE['email']) ? $_COOKIE['email'] : '');

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Prijava</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <style>
        html,
        body {
            background-color: ivory;
        }
        </style>
    </head>

    <body>
    <div class="hero-head">
            <nav class="navbar">
                <div class="container">
                    <div class="navbar-brand">
                        <a class="navbar-item" href="../">
                        <h1>HardShop</h1>
                        </a>
                           </div>
                        </div>
                    </div>
            </nav>
        <div class="hero is-fullheight">
            <div class="hero-body is-justify-content-center is-align-items-center">
                <div class="columns is-flex is-flex-direction-column box">
                    <div class="column">
                    <form action="autorizacija.php" method="post">
                        <label for="email">Email adresa</label>
                        <input class="input is-primary" type="text" name="email" value="<?=$email?>" placeholder="email" id="">
                    </div>
                    <div class="column">
                        <label for="lozinka">Lozinka</label>
                        <input class="input is-primary" type="password" name="lozinka" placeholder="..." id="">
                        <a href="forget.html" class="is-size-7 has-text-primary">Zaboravljena lozinka?</a>
                    </div>
                    <div class="column">
                        <button class="button is-primary is-fullwidth" type="submit">Prijava</button>
                    </div>
                    <div class="has-text-centered">
                        <p class="is-size-7"> Nemate račun? <a href="registracija2.php" class="has-text-primary">Registrirajte se</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </body>
</html> 