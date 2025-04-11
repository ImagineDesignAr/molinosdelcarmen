<?php

class siteController extends Controller
{

    function __construct() {}

    function index()
    {
        /**
      200 OK
      201 Created
      300 Multiple Choices
      301 Moved Permanently
      302 Found
      304 Not Modified
      307 Temporary Redirect
      400 Bad Request
      401 Unauthorized
      403 Forbidden
      404 Not Found
      410 Gone
      500 Internal Server Error
      501 Not Implemented
      503 Service Unavailable
      550 Permission denied
         */
        json_response(403);
    }
    function upload()
    {
        check_csrf();
        if (isset($_FILES['filename'])) {
            if ($_FILES['filename']['name'] != '') {
                $file = upload_files($_FILES['filename']);
                if ($file != false) {
                    json_response(200, $file, 'Subida a servidor');
                }
            }
        }
        json_response(400, null, 'Archivo invalido');
    }
    function delete($name)
    {
        echo delete_image(FOLDER_IMAGES . $name);
        echo '<br/>';
        echo '<a href="' . URL . 'pages/delete_image">Volver</a>';
    }
    function cvs_to($file = null)
    {
        debug(cvs_to($file));
    }
}
