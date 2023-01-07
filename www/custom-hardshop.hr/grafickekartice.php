<!DOCTYPE html>
<html>

<head>
<?php include_once 'head.php'; ?>
</head>

<body>
    <section class="hero is-fullheight is-default is-bold">
    <div>
    <?php include_once 'izbornik.php'; ?>
    </div>
    
    <div class="tile is-ancestor">
  <div class="tile is-vertical is-8">
    <div class="tile">
      <div class="tile is-parent is-vertical">
        <article class="tile is-child notification is-primary">
          <p class="title">ASUS GeForce TUF-RTX4080, 16 GB GDDR6X</p>
          <p class="subtitle">  </p>
          <figure class="image is-4by3">
            <img src="https://picsum.photos/500">
          </figure>
        </article>
      </div>
      <div class="tile is-parent">
        <article class="tile is-child notification is-info">
          <p class="title">ASUS GeForce DUAL-RTX3070, 8 GB GDDR6</p>
          <p class="subtitle">  </p>
          <figure class="image is-4by3">
            <img src="https://picsum.photos/500">
          </figure>
        </article>
      </div>
    </div>
    <div class="tile is-parent">
      <article class="tile is-child notification is-danger">
        <p class="title">GIGABYTE AMD Radeon RX 6650 XT EAGLE, 8GB GDDR6</p>
        <p class="subtitle">    </p>
        <figure class="image is-16by9">
            <img src="https://picsum.photos/500">
          </figure>
        <div class="content">
          <!-- Content -->
        </div>
      </article>
    </div>
  </div>
</div>

<div>
<?php include_once 'podnozje.php'; ?>
</div>
    </section>
    <script src="../js/bulma.js"></script>
</body>

</html>