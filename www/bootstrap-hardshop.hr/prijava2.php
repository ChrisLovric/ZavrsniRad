<section class="intro">
<head>
<link rel="stylesheet" href="css/bootstrap.css" />
<link rel="stylesheet" href="css/login.css" />
</head>
  <div class="bg-image h-100" style="background-image: url('https://mdbootstrap.com/img/Photos/new-templates/search-box/img4.jpg');">
    <div class="mask d-flex align-items-center h-100" style="background-color: rgba(255,255,255,.6);">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5" div style="background-color: <?php echo $_GET['boja']; ?>">
          <h1>
           <a href='index.php'>HardShop</a>
            </h1>
            <div class="card gradient-custom" style="border-radius: 1rem;">
              <div class="card-body p-5 text-white">

                <div class="my-md-5">

                  <div class="text-center pt-1">
                    <i class="fas fa-user-astronaut fa-3x"></i>
                    <h1 class="fw-bold my-5 text-uppercase">Prijava</h1>
                  </div>

                  <div class="form-outline form-white mb-4">
                    <input type="email" id="typeEmail" class="form-control form-control-lg" />
                    <label class="form-label" for="typeEmail">Email adresa</label>
                  </div>

                  <div class="form-outline form-white mb-4">
                    <input type="password" id="typePassword" class="form-control form-control-lg" />
                    <label class="form-label" for="typePassword">Lozinka</label>
                  </div>

                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      value=""
                      id="flexCheckDefault"
                    />
                    <label class="form-check-label" for="flexCheckDefault">
                      Zapamti me
                    </label>
                  </div>

                  <div class="text-center py-5">
                    <button class="btn btn-light btn-lg btn-rounded px-5" type="submit">Prijava</button>
                  </div>

                </div>

                <div class="text-center">
                  <p class="mb-0"><a href="#!" class="text-white fw-bold">Zaboravljena lozinka?</a></p>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>