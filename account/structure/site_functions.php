<?php
function delete_image($name)
{
    if (file_exists($name)) {
        unlink($name);
        return "Archivo eliminado correctamente: $name";
    } else {
        return "Error: El archivo no -$name- existe.";
    }
}
function list_images()
{
    $array_send = [];
    $folder     = FOLDER_IMAGES;
    $filenames  = scandir($folder);
    $filenames  = array_diff($filenames, array('..', '.'));
    foreach ($filenames as $ix => $filename) {
        $filename_path                      = $folder . '/' . $filename;
        $filename_size                      = filesize($filename_path);
        $filename_volume                    = number_format($filename_size / (1024), 0);

        $filename_image                     = IMAGES . $filename;
        $array_send[$ix]['filename_image']  = $filename_image;
        $array_send[$ix]['filename']        = $filename;
        $array_send[$ix]['filename_path']   = pathinfo($filename_path, PATHINFO_EXTENSION);
        $array_send[$ix]['filename_size']   = $filename_size;
        $array_send[$ix]['filename_volume'] = $filename_volume;
        $array_send[$ix]['filename_date']   = date("Y-m-d H:i:s", filectime($filename_path));
        $array_send[$ix]['filename_href']   = URL . 'site/delete/' . $filename;
    }

    return $array_send;
}
function get_month($month)
{
    $months             = new monthsModel();
    $months->month_id   = $month;
    $months_data        = $months->one();

    if ($months_data == []) {
        $months_data =
            [
                'month_name'    => 'Enero',
                'month_abr'     => 'ene'
            ];
    }
    return $months_data;
}
