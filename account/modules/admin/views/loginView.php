<div class="auth-page-wrapper pt-3">
  <div class="auth-one-bg-position auth-one-bg" style="background-image: url('<?php echo bg_enterprise ?>');" id="auth-particles">
    <div class="bg-overlay"></div>
    <div class="shape">
      <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
        <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
      </svg>
    </div>
  </div>
  <div class="auth-page-content p-0">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5 col-md-5">
          <div class="card mt-2">
            <div class="card-body p-2">
              <div class="text-center mb-1 text-white-50">
                <div>
                  <a class="d-inline-block auth-logo">
                    <img src="<?php echo $site->logo ?>" alt="" height="100">
                  </a>
                </div>
              </div>
              <div class="text-center mt-2">
                <h5 class="text-primary">Bienvenido!</h5>
                <p class="text-muted">Inicie sesion para usar el sistema</p>
              </div>
              <?php echo Flasher::flash() ?>
              <div class="p-2 mt-2">
                <form method="POST" action="<?php echo $site->action ?>" autocomplete="off">
                  <?php echo insert_inputs(); ?>
                  <div class="mb-3">
                    <div class="form-label">Perfil</div>
                    <select class="form-control" name="profile">
                      <?php echo $site->profiles ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <div class="form-label">Sucursal</div>
                    <select class="form-control" name="store">
                      <?php echo $site->stores ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" class="form-control text-lowercase" id="username" name="user" autofocus required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="password-input">Contraseña</label>
                    <div class="position-relative auth-pass-inputgroup mb-3">
                      <input type="password" class="form-control pe-5 password-input" id="password-input" autocomplete="current-password" name="pass">
                      <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill ri-lg align-middle"></i></button>
                    </div>
                  </div>
                  <div class="mt-4">
                    <button class="btn btn-dark w-100" type="submit">Acceder</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <footer class="footer">
    <div class="container-fluid">
      <div class="text-sm-end d-none d-sm-block">
        Diseño &amp; Programacion por iD
      </div>
    </div>
  </footer>
</div>