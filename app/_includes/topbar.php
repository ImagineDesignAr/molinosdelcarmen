<?php
$nav_array          = gestion_navs;
$profile            = mb_strtolower($site->user->profile);
$profiles_array     = get_pages($profile);    # Roles traidos desde el usuario loggeado
$nav_temp = '';

foreach ($nav_array as $nav) {
  $sub_nav = '';
  if (in_array($nav['navs'], $profiles_array['navs'])) {
    foreach ($nav['children'] as $subnav) {
      if (in_array($subnav['path'], $profiles_array['pages'])) {
        $nav_link = '<li class="nav-item"><a href="%s" class="nav-link">%s</a></li>';
        $sub_nav .= sprintf($nav_link, $subnav['path'], $subnav['label']);
      }
    }
    $nav_item = '<li class="nav-item"><a class="nav-link menu-link" href="#sidebar%s" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebar%s"><i class="%s"></i><span data-key="t-%s">%s</span></a><div class="collapse menu-dropdown" id="sidebar%s"><ul class="nav nav-sm flex-column">%s</ul></div></li>';
    $nav_temp .= sprintf($nav_item, $nav['navs'], $nav['navs'], $nav['icon'], $nav['navs'], $nav['label'], $nav['navs'], $sub_nav);
  }
}
$navbar =  preg_replace('/[\n\r\t]+/', '', $nav_temp);
?>
<div class="app-menu navbar-menu">
  <div class="navbar-brand-box">
    <!-- Dark Logo-->
    <a href="<?php echo $site->home ?>" class="logo logo-dark">
      <span class="logo-sm">
        <img src="<?php echo $site->logo_light ?>" alt="" height="48">
      </span>
      <span class="logo-lg">
        <img src="<?php echo $site->logo_light ?>" alt="" height="72">
      </span>
    </a>
    <!-- Light Logo-->
    <a href="<?php echo $site->home ?>" class="logo logo-light">
      <span class="logo-sm">
        <img src="<?php echo $site->logo_light ?>" alt="" height="48">
      </span>
      <span class="logo-lg">
        <img src="<?php echo $site->logo_light ?>" alt="" height="72">
      </span>
    </a>
    <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
      <i class="ri-record-circle-line"></i>
    </button>
  </div>
  <div id="scrollbar">
    <div class="container-fluid">
      <div id="two-column-menu"></div>
      <ul class="navbar-nav" id="navbar-nav">
        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
        <?php echo $navbar ?>
      </ul>
    </div>
  </div>
  <div class="sidebar-background"></div>
  <div class="row position-absolute bottom-0 end-0 p-0">
    <div class="text-light ">
      Diseño <a href="<?php echo $site->about ?>" alt="iD">iD</a>
    </div>
  </div>
</div>