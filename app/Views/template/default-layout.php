<!DOCTYPE html>
<html lang="en">

<head>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>
      <?php if (isset($title) && $title) {
         echo esc(trim(ucwords($title)));
      } else {
         echo trim(ucwords(web_partner_details['meta_title']));
      } ?>
   </title>
   <meta name="keywords"
      content="<?php if (isset($metakeywords) && $metakeywords) {
         echo trim(ucwords($metakeywords));
      } else {
         echo trim(ucwords(web_partner_details['meta_keyword']));
      } ?>">
   <meta name="description"
      content="<?php if (isset($metadescription) && $metadescription) {
         echo trim(ucwords($metadescription));
      } else {
         echo trim(ucwords(web_partner_details['meta_description']));
      } ?>">
   <meta name="robots"
      content="<?php if (isset($metarobots) && $metarobots) {
         echo trim($metarobots);
      } else {
         echo trim(web_partner_details['meta_robots']);
      } ?>">
   <link rel="shortcut icon" href="<?php echo root_url . 'uploads/favicon/' . web_partner_details['company_favicon'] ?>"
      type="image/x-icon" />
   <link rel="apple-touch-icon"
      href="<?php echo root_url . 'uploads/favicon/' . web_partner_details['company_favicon'] ?>">
   <link rel="stylesheet"
      href="<?php echo site_url('webroot'); ?>/vendor/bootstrap/css/bootstrap.css<?php echo last_modifytime(FCPATH . 'webroot/vendor/bootstrap/css/bootstrap.css'); ?>">
   <link rel="stylesheet"
      href="<?php echo site_url('webroot'); ?>/css/tts_icon.css<?php echo last_modifytime(FCPATH . 'webroot/css/tts_icon.css'); ?>">
   <link rel="stylesheet"
      href="<?php echo site_url('webroot'); ?>/css/jquery-ui.min.css<?php echo last_modifytime(FCPATH . 'webroot/css/jquery-ui.min.css'); ?>">
   <link rel="stylesheet"
      href="<?php echo site_url('webroot'); ?>/vendor/select2/select2.min.css<?php echo last_modifytime(FCPATH . 'webroot/vendor/select2/select2.min.css'); ?>">
   <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/admin-login.css">
   <link rel="stylesheet"
      href="<?php echo site_url('webroot'); ?>/css/custom.css<?php echo last_modifytime(FCPATH . 'webroot/css/custom.css'); ?>">
   <link rel="stylesheet"
      href="<?php echo site_url('webroot'); ?>/css/searchForm.css<?php echo last_modifytime(FCPATH . 'webroot/css/searchForm.css'); ?>">
   <link rel="stylesheet"
      href="<?php echo site_url('webroot'); ?>/css/flight_result.css<?php echo last_modifytime(FCPATH . 'webroot/css/flight_result.css'); ?>">
   <link rel="stylesheet"
      href="<?php echo site_url('webroot/airlinewebroot'); ?>/css/dom_air.css<?php echo last_modifytime(FCPATH . 'webroot/airlinewebroot/css/dom_air.css'); ?>">
   <link rel="stylesheet"
      href="<?php echo site_url('webroot/airlinewebroot'); ?>/css/international_air.css<?php echo last_modifytime(FCPATH . 'webroot/airlinewebroot/css/international_air.css'); ?>">
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js<?php echo last_modifytime(FCPATH . 'webroot/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/jquery.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery.min.js'); ?>"></script>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/jquery-ui.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery-ui.min.js'); ?>"></script>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/print.js<?php echo last_modifytime(FCPATH . 'webroot/js/print.js'); ?>"></script>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/angular.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/angular.min.js'); ?>"></script>
   <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" /> -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

</head>

<body>
   <p class="tts_agentUserId" id="AddWaterMark"></p>
   <?php echo view('Views/template/home-header.php'); ?>
   <div class="def_layout_content">
      <?php
      try {
         echo view('Modules/' . $view);
      } catch (Exception $e) {
         echo "<pre><code>$e</code></pre>";
      }
      ?>
   </div>
   <style>
      <?php echo whitelabel['custom_css']; ?>
   </style>
   <?php echo view('Views/template/footer.php'); ?>
</body>

</html>