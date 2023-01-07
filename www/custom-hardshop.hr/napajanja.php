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
          <p class="title">SILVERSTONE SST-ST1200-PTS, 1200 W, modularno, 120 mm, 80+ Platinum</p>
          <p class="subtitle">  </p>
          <figure class="image is-4by3">
            <img src="https://picsum.photos/500">
          </figure>
        </article>
      </div>
      <div class="tile is-parent">
        <article class="tile is-child notification is-info">
          <p class="title">FRACTAL DESIGN Ion+ 2 Platinum 860W, modularno, 80+ Platinum</p>
          <p class="subtitle">  </p>
          <figure class="image is-4by3">
            <img src="https://picsum.photos/500">
          </figure>
        </article>
      </div>
    </div>
    <div class="tile is-parent">
      <article class="tile is-child notification is-danger">
        <p class="title">THERMALTAKE Toughpower GF3, modularno, 750 W, 80+ Gold</p>
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