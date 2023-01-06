<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
<link rel="stylesheet" href="css/app.css" />
    <h1>HardShop</h1>
    <div class="align-items-end" id="prijava">
    <div class="text-center py-5">
<div>
<button class="btn btn-light btn-lg btn-rounded px-5" type="submit"><?php include_once 'prijava.php'; ?></button>
</div><br>
<div>
<button class="btn btn-light btn-lg btn-rounded px-5" type="submit"><?php include_once 'registracija.php'; ?></button>
</div>
        
</div>  
</div>

<div>
<?php require_once 'izbornik.php'; ?>
</div>

<div id="podnozje">
    <?php include_once 'podnozje.php'; ?>
    </div>

    <div>
    <?php include_once 'skripte.php'; ?>
    </div>   

</body>
</html>