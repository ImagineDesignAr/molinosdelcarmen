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
        <div class="col-12">
          <div class="card mt-4">
            <div class="card-body p-4 text-center">
              <table class="table table-hover table-nowrap mb-0">
                <thead>
                  <tr>
                    <th scope="col">Nombre del Archivo</th>
                    <th scope="col">Extensión</th>
                    <th scope="col">Tamaño (KByte)</th>
                    <th scope="col">Fecha de Creación</th>
                    <th scope="col">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php echo $site->filenames ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>