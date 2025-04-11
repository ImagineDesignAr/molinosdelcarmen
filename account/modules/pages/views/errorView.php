<div class="auth-page-wrapper">
  <div class="auth-one-bg-position auth-one-bg" style="background-image: url('<?php echo $site->bg_enterprise ?>');" id="auth-particles">
    <div class="bg-overlay"></div>
    <div class="shape">
      <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
        <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
      </svg>
    </div>
  </div>
  <div class="auth-page-content p-2">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="text-center">
            <div class="">
              <img src="<?php echo $site->logo_page ?>" alt="" class="error-basic-img move-animation">
            </div>
            <div class="mt-n4">
              <h1 class="display-1 fw-semibold"><?php echo $site->error ?></h1>
              <h3 class="text-uppercase"><?php echo $site->titulo ?></h3>
              <p class="text-muted mb-4"><?php echo $site->mensaje ?></p>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer galaxy-border-none">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="text-end">
                <p class="mb-0 text-muted">© FlexiMVC. Crafted with <i class="mdi mdi-heart text-danger"></i> by Innovacion Digital</p>
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
</div>