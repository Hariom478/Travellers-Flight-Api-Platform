<!DOCTYPE html>
<html lang="en">

<head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title><?= esc($title) ?> </title> 

      <link rel="shortcut icon" href="<?php echo root_url . 'uploads/favicon/' . super_admin_website_setting['company_favicon'] ?>" type="image/x-icon" />
      <link rel="alternate" href="<?php echo root_url . 'uploads/favicon/' . super_admin_website_setting['company_favicon'] ?>" hreflang="en" />
      <link rel="icon" href="<?php echo root_url . 'uploads/favicon/' . super_admin_website_setting['company_favicon'] ?>" type="image/x-icon" />
      <link rel="apple-touch-icon" href="<?php echo root_url . 'uploads/favicon/' . super_admin_website_setting['company_favicon'] ?>">

      <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/vendor/bootstrap/css/bootstrap.css<?php echo last_modifytime(FCPATH . 'webroot/vendor/bootstrap/css/bootstrap.css'); ?>">
      <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/jquery-ui.min.css<?php echo last_modifytime(FCPATH . 'webroot/css/jquery-ui.min.css'); ?>">
      <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/vendor/select2/select2.min.css<?php echo last_modifytime(FCPATH . 'webroot/vendor/select2/select2.min.css'); ?>">
      <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/summernote-lite.min.css<?php echo last_modifytime(FCPATH . 'webroot/css/summernote-lite.min.css'); ?>">

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
      <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/sidebar.css<?php echo last_modifytime(FCPATH . 'webroot/css/sidebar.css'); ?>">
      <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/jquery.multiselect.css<?php echo last_modifytime(FCPATH . 'webroot/css/jquery.multiselect.css'); ?>">
  

      <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/style.css<?php echo last_modifytime(FCPATH . 'webroot/css/style.css'); ?>">

      <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js<?php echo last_modifytime(FCPATH . 'webroot/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
      <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/jquery.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery.min.js'); ?>"></script>
      <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/jquery-ui.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery-ui.min.js'); ?>"></script>
      <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/summernote-lite.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/summernote-lite.min.js'); ?>"></script>
      <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/countdown.js<?php echo last_modifytime(FCPATH . 'webroot/js/countdown.js'); ?>"></script>
      <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/angular.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/angular.min.js'); ?>"></script>

</head>

<body>

      <?php echo view('Views/template/sidebar_header.php'); ?>
      <?php echo view('Views/template/sidebar_menu.php'); ?>

      <?php
      try {
            echo view('Modules/' . $view);
      } catch (Exception $e) {
            echo "<pre><code>$e</code></pre>";
      }
      ?>

      <?php
      try {
            echo view('Views/template/footer_sidebar.php');
      } catch (Exception $e) {
            echo "<pre><code>$e</code></pre>";
      }
      ?>
</body>

</html>