<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Prijava</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    </head>

    <body>
    <div class="hero-head">
            <nav class="navbar">
                <div class="container">
                    <div class="navbar-brand">
                        <a class="navbar-item" href="../">
                        <h1>HardShop</h1>
                        </a>
                        <span class="navbar-burger burger" data-target="navbarMenu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </div>
                    <div id="navbarMenu" class="navbar-menu">
                        <div class="navbar-end">
                            <div class="tabs is-right">
                                <ul>
                                    <div>
                                    <button class="button is-link is-light is-outlined is-rounded" type="submit"><?php include_once 'prijava.php'; ?></button>
                                    </div>
                                    <button class="button is-link is-light is-outlined is-rounded" type="submit"><?php include_once 'registracija.php'; ?></button>
                                    </div>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        <div class="hero is-fullheight">
            <div class="hero-body is-justify-content-center is-align-items-center">
                <div class="columns is-flex is-flex-direction-column box">
                    <div class="column">
                        <label for="email">Email adresa</label>
                        <input class="input is-primary" type="text" placeholder="...">
                    </div>
                    <div class="column">
                        <label for="Name">Lozinka</label>
                        <input class="input is-primary" type="password" placeholder="...">
                        <a href="forget.html" class="is-size-7 has-text-primary">Zaboravljena lozinka?</a>
                    </div>
                    <div class="column">
                        <button class="button is-primary is-fullwidth" type="submit">Prijava</button>
                    </div>
                    <div class="has-text-centered">
                        <p class="is-size-7"> Nemate račun? <a href="#" class="has-text-primary">Registrirajte se</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>