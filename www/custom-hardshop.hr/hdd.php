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
          <p class="title">WESTERN DIGITAL 14 TB Red Pro, 3.5", SATA III, 7200 rpm, 512 MB, WD141KFGX</p>
          <p class="subtitle">  </p>
          <figure class="image is-4by3">
            <img src="https://picsum.photos/500">
          </figure>
        </article>
      </div>
      <div class="tile is-parent">
        <article class="tile is-child notification is-info">
          <p class="title">TOSHIBA X300 14 TB, 3.5", SATA III, 7200 rpm, 256 MB, HDWR21EEZSTA</p>
          <p class="subtitle">  </p>
          <figure class="image is-4by3">
            <img src="https://picsum.photos/500">
          </figure>
        </article>
      </div>
    </div>
    <div class="tile is-parent">
      <article class="tile is-child notification is-danger">
        <p class="title">SEAGATE Exos X16 10 TB, 3.5", SATA III, 512e/4Kn, 7200 rpm, 256 MB, ST10000NM001G</p>
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