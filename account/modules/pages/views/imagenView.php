<div class="auth-page-wrapper pt-2">
  <div class="auth-one-bg-position auth-one-bg" style="background-image: url('<?php echo $site->bg_enterprise ?>');" id="auth-particles">
    <div class="bg-overlay"></div>
    <div class="shape">
      <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
        <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
      </svg>
    </div>
  </div>
  <div class="auth-page-content">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-md-5">
          <div class="card mt-4">
            <div class="card-body p-4 text-center">
              <div class="avatar-lg mx-auto mt-2">
                <div class="avatar-title bg-light text-success display-3 rounded">
                  <i class="ri-checkbox-circle-fill ri-lg align-middle"></i>
                </div>
              </div>
              <div class="mt-4 pt-2">
                <h4><?php echo $site->titulo ?></h4>
                <p class="text-muted mx-4"><?php echo $site->mensaje ?></p>
                <div class="mt-4">
                  <a href="<?php echo $site->buttom_ref ?>" class="btn btn-info w-100"><?php echo $site->buttom_text ?></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>