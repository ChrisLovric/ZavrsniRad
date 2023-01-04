<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <?php include_once 'head.php'; ?>
  </head>
<body>

    <div class="grid-container">
      <?php require_once 'izbornik.php'; ?>
      </div>

      <?php 
          
          echo '<p style="color: red;">ASUS GeForce TUF-RTX4080, 16 GB GDDR6X</p>';

          echo '<p style="color: red;">ASUS GeForce DUAL-RTX3070, 8 GB GDDR6</p>';

          echo '<p style="color: red;">ASUS AMD Radeon DUAL-RX6600XT, 8 GB GDDR6</p>';

          echo '<p style="color: red;">GIGABYTE AMD Radeon RX 6650 XT EAGLE, 8GB GDDR6</p>';

          ?>

          <hr>
          <div style="background-color: green; text-transform: lowercase; color: white;">
           <?php 
          
          echo $_GET['grafickakartica'];
          
          ?>
          </div>


    </div>
    <?php include_once 'podnozje.php'; ?>
    <?php include_once 'skripte.php'; ?>
  </body>
</html>
