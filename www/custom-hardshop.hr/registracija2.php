<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registracija</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <style>
        html,
        body {
            background-color: <?php echo $_GET['boja'] ?>;
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
                        <label for="imeprezime">Ime i prezime</label>
                        <input class="input is-primary" type="text" placeholder="...">
                    </div>
                    <div class="column">
                        <label for="email">Email adresa</label>
                        <input class="input is-primary" type="text" placeholder="...">
                    </div>
                    <div class="column">
                        <label for="lozinka">Lozinka</label>
                        <input class="input is-primary" type="text" placeholder="...">
                    </div>
                    <div class="column">
                        <label for="ponovilozinka">Ponovite lozinku</label>
                        <input class="input is-primary" type="text" placeholder="...">
                    </div>
                    <div class="column">
                        <label for="adresadostava">Adresa za dostavu</label>
                        <input class="input is-primary" type="text" placeholder="...">
                    </div>
                    <div class="column">
                        <label for="adresaracun">Adresa za račun</label>
                        <input class="input is-primary" type="text" placeholder="...">
                    </div>
                    <div class="column">
                        <label for="brojtelefona">Broj telefona</label>
                        <input class="input is-primary" type="password" placeholder="...">
                    </div>
                    <div class="column">
                        <button class="button is-primary is-fullwidth" type="submit">Registrirajte se</button>
                    </div>
                    <div class="has-text-centered">
                        <p class="is-size-7"> Već imate račun? <a href="prijava2.php" class="has-text-primary">Prijavite se</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </body>
</html>