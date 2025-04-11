<header id="page-topbar">
  <div class="layout-width">
    <div class="navbar-header">
      <div class="d-flex">
        <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
          <span class="hamburger-icon">
            <span></span>
            <span></span>
            <span></span>
          </span>
        </button>
      </div>
      <div class="d-flex align-items-center">
        <div class="ms-1 header-item d-sm-flex">
          <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded light-dark-mode">
            <i class="bx bx-moon fs-22"></i>
          </button>
        </div>
        <div class="dropdown ms-sm-3 header-item topbar-user">
          <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center">
              <img class="rounded header-profile-user" src="<?php echo $site->user->person_picture ?>" alt="Header Avatar">
              <span class="text-start ms-xl-2">
                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text"><?php echo $site->user->person_name ?></span>
                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text"><?php echo $site->user->profiles_name ?> - <?php echo $site->user->store_name ?></span>
              </span>
            </span>
          </button>
          <div class="dropdown-menu dropdown-menu-end">
            <h6 class="dropdown-header">Hola <?php echo ucwords($site->user->person_name) ?>!</h6>
            <a class="dropdown-item" href="<?php echo $site->reset ?>"><i class="ri-account-circle-line text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Cambiar Contraseña</span></a>
            <a class="dropdown-item" href="<?php echo $site->lock ?>"><i class="ri-lock-line text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Bloquear Pantalla</span></a>
            <a class="dropdown-item" href="<?php echo $site->logout ?>"><i class="ri-logout-circle-r-line text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Cerrar Sesion</span></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>