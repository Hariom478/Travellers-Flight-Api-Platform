<?php
if (!empty(session()->get("Message"))) : $message = session()->get("Message"); ?>
    <div class="message  <?= $message['Class'] ?>" onclick="this.classList.add('d-none');"><?php echo $message['Message']; ?></div>
<?php endif ?>

<div data-message="true"></div>
<input type="hidden" value="<?php echo admin_cookie_data()['admin_comapny_detail']['company_id']; ?>" id="web-partner-company-id">
<div id="common_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" data-modal-view="view_modal_data"></div>
    </div>
</div>
 

<script type="text/javascript">
    var site_url = "<?php echo site_url(); ?>";
</script>
<script type="text/javascript" src="<?php echo site_url('webroot'); ?>/vendor/select2/select2.min.js<?php echo last_modifytime(FCPATH . 'webroot/vendor/select2/select2.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/jquery.multiselect.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery.multiselect.js'); ?>"></script>

<script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/custom.js<?php echo last_modifytime(FCPATH . 'webroot/js/custom.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/car.js<?php echo last_modifytime(FCPATH . 'webroot/js/car.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/sidebar.js<?php echo last_modifytime(FCPATH . 'webroot/js/sidebar.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/jquery.form-validator.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery.form-validator.min.js'); ?>"></script>

