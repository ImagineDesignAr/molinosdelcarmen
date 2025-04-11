<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="dark" data-sidebar="dark" data-sidebar-size="sm" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default" data-sidebar-visibility="show" data-layout-style="default" data-bs-theme="light" data-layout-width="fluid" data-layout-position="fixed" data-body-image="none">

<head>
  <meta name="google" content="notranslate" />
  <meta charset="utf-8" />
  <title><?php echo $site->title ?></title>
  <link rel="icon" type="image/png" href="<?php echo $site->favicon ?>" sizes="16x16">
  <!-- App favicon -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="<?php echo $site->description ?>" name="description" />
  <meta content="<?php echo $site->author ?>" name="author" />
  <?php set_meta($site->custom_metas ?? []); ?>
  <?php echo load_styles($site->head); ?>
  <style>
    [v-cloak] {
      display: none;
    }

    ::-webkit-scrollbar {
      width: 10px;
    }

    ::-webkit-scrollbar-thumb {
      background-color: #888;
    }

    ::-webkit-scrollbar-track {
      background-color: #f1f1f1;
    }

    .zoom {
      transition: transform .2s;
      cursor: pointer;
      z-index: 1;
      /* Initial z-index */
    }

    .zoom:hover {
      transform: scale(1.5);
      position: relative;
      z-index: 1000;
      /* Bring to front when clicked */
    }
  </style>
</head>

<body class="<?php echo $site->body_class ?>" style="<?php echo $site->body_style ?>">