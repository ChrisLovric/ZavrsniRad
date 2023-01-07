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
          <p class="title">G.SKILL 32 GB (2x16 GB) DDR5, 6000 MHz, DIMM, Trident Z5 RGB, CL40, F5-6000U4040E16GX2-TZ5RK</p>
          <p class="subtitle">  </p>
          <figure class="image is-4by3">
            <img src="https://picsum.photos/500">
          </figure>
        </article>
      </div>
      <div class="tile is-parent">
        <article class="tile is-child notification is-info">
          <p class="title">CORSAIR 64 GB (2x32 GB) DDR4, 3000 MHz, DIMM, Vengeance LPX, CL16</p>
          <p class="subtitle">  </p>
          <figure class="image is-4by3">
            <img src="https://picsum.photos/500">
          </figure>
        </article>
      </div>
    </div>
    <div class="tile is-parent">
      <article class="tile is-child notification is-danger">
        <p class="title">CORSAIR 16 GB (2x8 GB) DDR4, 3000 MHz, DIMM, Dominator Platinum RGB, CL15, XMP 2.0</p>
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