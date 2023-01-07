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
          <p class="title">SSD 2 TB, SAMSUNG 990 PRO, M.2 2280, PCIe 4.0 x4 NVMe 2.0, 3-bit MLC V-NAND, MZ-V9P2T0BW</p>
          <p class="subtitle">  </p>
          <figure class="image is-4by3">
            <img src="https://picsum.photos/500">
          </figure>
        </article>
      </div>
      <div class="tile is-parent">
        <article class="tile is-child notification is-info">
          <p class="title">SSD 2 TB, CORSAIR MP600, M.2 2280, PCIe 4.0 x4 NVMe, 3D TLC NAND, aluminium heatsink, CSSD-F2000GBMP600</p>
          <p class="subtitle">  </p>
          <figure class="image is-4by3">
            <img src="https://picsum.photos/500">
          </figure>
        </article>
      </div>
    </div>
    <div class="tile is-parent">
      <article class="tile is-child notification is-danger">
        <p class="title">SSD disk 2 TB, HP EX950, M.2 2280, PCIe 3.0 x4 NVMe, 3D NAND TLC, 3500/2900 MB/s</p>
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