<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php if(isset($title)) { echo $title; } else { echo "Error"; } ?> </title>
    <link rel="shortcut icon" href="<?php echo site_url('webroot'); ?>/img/favicon.svg" type="image/x-icon" />
    <link rel="apple-touch-icon" href="<?php echo site_url('webroot'); ?>/img/favicon.svg">
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/vendor/bootstrap/css/bootstrap.css<?php echo last_modifytime(FCPATH . 'webroot/vendor/bootstrap/css/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/custom.css<?php echo last_modifytime(FCPATH . 'webroot/css/custom.css'); ?>">
</head>

<body>
    <div class="def_layout_content">
            <div class="container">
                <div class="row">
                    <div class="col-12 mt-5 mb-5 tts-api-error-msg">
                            <img src="<?php echo site_url('webroot/img/generic-error.png'); ?>" width="15%">

                            <h1><b> <?php if (! empty($error_message) && $error_message !== '(null)') : ?>
                              <?= esc($error_message) ?>
                            <?php else : ?>
                                Sorry! Cannot seem to find the page you were looking for.
                            <?php endif ?></b> </h1>
                            
                            <p>If any issue please contact the administrator.</p>
                            <a class="mt-2 btn btn-outline-primary" href="<?php echo site_url('flight');?>">Go To Home Pgae</a>
                
                    </div>
                </div>
            </div>
    </div>

</body>
</html>