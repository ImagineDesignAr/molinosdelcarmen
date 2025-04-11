<?php

/**
 * Clase para mostrar alertas desde PHP al Front End. v 1.0
 */
class Flasher
{

    private $valid_types = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
    private $default = 'primary';
    private $type;
    private $msg;
    /**
     * Metodo para guardar una notificacion flash
     * 
     * @param string array 
     * @param string
     * @return void
     */

    public static function new($msg, $type = null)
    {
        $self = new self();
        if ($type === null) {
            $self->type = $self->default;
        }

        /* if (!)){
            
        } */
        $self->type = in_array($type, $self->valid_types) ? $type : $self->default;

        // Guardar la notificacion en un array de sesion
        if (is_array($msg)) {
            foreach ($msg as $m) {
                $_SESSION[$self->type][] = $m;
            }

            return true;
        }

        $_SESSION[$self->type][] = $msg;

        /** 
         * Flasher::new(mensaje, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark']);
         * Flasher::flash(); 
         */

        return true;
    }

    public static function flash()
    {
        $self = new self();
        $output = '';

        foreach ($self->valid_types as $type) {
            if (isset($_SESSION[$type]) && !empty($_SESSION[$type])) {
                foreach ($_SESSION[$type] as $m) {
                    $outputText =
                        '<div class="alert alert-%s alert-dismissible text-center" role="alert">
                            %s
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';

                    $output .= sprintf($outputText, $type, $m);
                }
                unset($_SESSION[$type]);
            }
        }
        return $output;
    }
}
