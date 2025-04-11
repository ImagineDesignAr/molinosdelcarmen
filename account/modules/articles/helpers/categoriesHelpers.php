<?php

function category_all()
{
    $categories     = new categoriesModel();
    $category_data  = $categories->category_all();

    return $category_data;
}
