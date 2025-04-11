<?php

/**
 * Metodo para crear las barras de navegacion
 * 
 * @return void
 */

class Navbar
{
    public static function gestion($nav_array, $profile)
    {
        $profile            = mb_strtolower($profile);
        $profiles_array     = get_pages($profile); # Roles traidos desde el usuario loggeado
        $navTemp = '';
        
        foreach ($nav_array as $nav) {
            $subNavTemp = '';
            if (in_array($nav['name'], $profiles_array['navs'])) {
                foreach ($nav['links'] as $subnav) {
                    if (in_array($subnav['link'], $profiles_array['pages'])) {
                        $tempSubNav = '<li class="nav-item"><a href="%s" class="nav-link">%s</a></li>';
                        $subNavTemp .= sprintf($tempSubNav, $subnav['link'], $subnav['name']);
                    }
                }

                $tempNav = '
                    <li class="nav-item">
                        <a class="nav-link menu-link" 
                            href="#sidebar%s" 
                            data-bs-toggle="collapse" 
                            role="button"
                            aria-expanded="true" 
                            aria-controls="sidebar%s">
                            <i class="%s"></i>
                            <span data-key="t-%s">%s</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebar%s">
                            <ul class="nav nav-sm flex-column">
                            %s
                            </ul>
                        </div>
                    </li>';
                $navTemp .= sprintf(
                    $tempNav,
                    $nav['name'],
                    $nav['name'],
                    $nav['icon'],
                    $nav['name'],
                    $nav['vista'],
                    $nav['name'],
                    $subNavTemp
                );
            }
        }
        $navResolve = preg_replace('/[\n\r\t]+/', '', $navTemp);
        echo $navResolve;
    }
}
