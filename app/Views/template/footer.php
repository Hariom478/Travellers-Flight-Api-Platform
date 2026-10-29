<?php
$router = service('router');
$class_name = $router->controllerName();
$methodName = $router->methodName();
$classparm = explode("\\", $class_name);
$controller = end($classparm);

?>
<?php if (!empty(session()->get("Message"))):
   $message = session()->get("Message"); ?>
   <div class="message  <?= $message['Class'] ?>" onclick="this.classList.add('hide');"><?php echo $message['Message']; ?>
   </div>
<?php endif ?>
<div data-message="true"></div>
<!------footer---->
<?php if (session()->get('wl_agent')) { ?>


   <footer class="footer">
      <div class="footer-top">
         <div class="container">
            <div class="row">
               <div class="col-lg-3 col-md-4 footer-logo">
                  <a href="<?php echo site_url("dashboard"); ?>">
                     <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo'] ?>"
                        class="img-fluid " />
                       <!--  <?php if (session()->get('wl_agent') && !empty(session()->get('wl_agent')['company_logo'])) : ?>
                    
                     <img src="<?php echo root_url . 'uploads/agent/logo/' . session()->get('wl_agent')['company_logo']; ?>" class="img-fluid"/>
                  <?php else : ?>
                     <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo']; ?>" class="img-fluid" />
                  <?php endif; ?> -->
                  </a>
               </div>
               <?php if (isset(tts_pages['footer1']) && tts_pages['footer1']) { ?>
                  <div class="col-lg-2 col-md-4 footer-links">
                     <h4><?php echo tts_pages['footer1']['menu_name']; ?></h4>
                     <?php if (tts_pages['footer1']['page_content']) { ?>
                        <ul>
                           <?php foreach (tts_pages['footer1']['page_content'] as $pagecontent) {
                              if (isset($pagecontent['custom_url']) && $pagecontent['custom_url']) { ?>
                                 <li><a href="<?php echo $pagecontent['custom_url'] ?>"
                                       target="_blank"><?php echo $pagecontent['title'] ?></a></li>
                              <?php } else { ?>
                                 <li><a href="<?php echo site_url('pages/pages/') . $pagecontent['slug_url'] ?>"
                                       target="_blank"><?php echo $pagecontent['title'] ?></a></li>
                              <?php } ?>
                           <?php } ?>
                        </ul>
                     <?php } ?>
                  </div>
               <?php } ?>
               <?php if (isset(tts_pages['footer2']) && tts_pages['footer2']) { ?>
                  <div class="col-lg-2 col-md-4 footer-links">
                     <h4><?php echo tts_pages['footer2']['menu_name']; ?></h4>
                     <?php if (tts_pages['footer2']['page_content']) { ?>
                        <ul>
                           <?php foreach (tts_pages['footer2']['page_content'] as $pagecontent) {
                              if (isset($pagecontent['custom_url']) && $pagecontent['custom_url']) { ?>
                                 <li><a href="<?php echo $pagecontent['custom_url'] ?>"
                                       target="_blank"><?php echo $pagecontent['title'] ?></a></li>
                              <?php } else { ?>
                                 <li><a href="<?php echo site_url('pages/pages/') . $pagecontent['slug_url'] ?>"
                                       target="_blank"><?php echo $pagecontent['title'] ?></a></li>
                              <?php } ?>
                           <?php } ?>
                        </ul>
                     <?php } ?>
                  </div>
               <?php } ?>
               <?php if (isset(tts_pages['footer3']) && tts_pages['footer3']) { ?>
                  <div class="col-lg-2 col-md-4 footer-links">
                     <h4><?php echo tts_pages['footer3']['menu_name']; ?></h4>
                     <?php if (tts_pages['footer3']['page_content']) { ?>
                        <ul>
                           <?php foreach (tts_pages['footer3']['page_content'] as $pagecontent) {
                              if (isset($pagecontent['custom_url']) && $pagecontent['custom_url']) { ?>
                                 <li><a href="<?php echo $pagecontent['custom_url'] ?>"
                                       target="_blank"><?php echo $pagecontent['title'] ?></a></li>
                              <?php } else { ?>
                                 <li><a href="<?php echo site_url('pages/pages/') . $pagecontent['slug_url'] ?>"
                                       target="_blank"><?php echo $pagecontent['title'] ?></a></li>
                              <?php } ?>
                           <?php } ?>
                        </ul>
                     <?php } ?>
                  </div>
               <?php } ?>

               <div class="col-lg-3 col-md-4 footer-info">
                  <?php if (isset(tts_pages['footer4']) && tts_pages['footer4']) { ?>
                     <h4><?php echo tts_pages['footer4']['menu_name']; ?></h4>
                  <?php } ?>
                  <ul>
                     <? if (web_partner_details['support_email']): ?>
                        <li><i class="fa-solid fa-envelope"></i> <a
                              href="mailto:<?php echo web_partner_details['support_email']; ?>">
                              <?php echo web_partner_details['support_email'] ?> </a></li>
                     <? endif; ?>
                     <? if (web_partner_details['support_no']): ?>
                        <li><i class="fa-solid fa-phone"></i> <a href="tel:<?php echo web_partner_details['support_no']; ?>">
                              <?php echo web_partner_details['support_no'] ?> </a></li>
                     <? endif; ?>
                     <? if (web_partner_details['whatsapp_no']): ?>
                        <li><i class="fa-brands fa-whatsapp"></i> <a href="tel:<?php echo web_partner_details['whatsapp_no']; ?>">
                              <?php echo web_partner_details['whatsapp_no'] ?> </a></li>
                     <? endif; ?>
                     <li>
                        <i class="fa-solid fa-map-marker"></i>
                        <a>
                           <?php echo web_partner_details['address'] ?>
                           <?php echo web_partner_details['city'] ?>
                           <?php echo web_partner_details['pincode'] ?>
                           <?php echo web_partner_details['state'] ?>,

                        </a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
      <div class="bottom-footer-text">
         <div class="container bottom__content py-2">
            <div class="row">
               <div
                  class="col-md-12 d-lg-flex d-md-flex align-items-center justify-content-between text-md-center text-center">

                  <div class="copyright"> © Copyright
                     <strong><span><?php echo web_partner_details['company_name'] ?></span></strong>. All Rights Reserved
                  </div>
                  <div class="social">
                     <? if (web_partner_details['facebook_link']): ?>
                        <a target="_blank" class="social__list" href="<? echo web_partner_details['facebook_link']; ?>"
                           title="Facebook"><i class="fa-brands fa-facebook"></i></a>
                     <? endif; ?>
                     <? if (web_partner_details['instagram_link']): ?>
                        <a target="_blank" class="social__list" href="<? echo web_partner_details['instagram_link']; ?>"
                           title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                     <? endif; ?>
                     <? if (web_partner_details['twitter_link']): ?>
                        <a target="_blank" class="social__list" href="<? echo web_partner_details['twitter_link']; ?>"
                           title="Twitter"><i class="fa-brands fa-twitter"></i></a>
                     <? endif; ?>
                     <? if (web_partner_details['linkedin_link']): ?>
                        <a target="_blank" class="social__list" href="<? echo web_partner_details['linkedin_link']; ?>"
                           title="Linkedin"><i class="fa-brands fa-linkedin"></i></a>
                     <? endif; ?>
                     <? if (web_partner_details['youtube_link']): ?>
                        <a target="_blank" class="social__list" href="<? echo web_partner_details['youtube_link']; ?>"
                           title="Youtube"><i class="fa-brands fa-youtube"></i></a>
                     <? endif; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </footer>
   <!----footer------>
<?php } ?>
<div id="common_modal" class="modal fade" tabindex="-1">
   <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content" data-modal-view="view_modal_data"></div>
   </div>
</div>
<input type="hidden" value="<?php echo web_partner_details['id']; ?>" id="web-partner-company-id">
<script type="text/javascript">
   var get_param = "<?php echo http_build_query($_GET); ?>";
   var site_url = "<?php echo site_url(); ?>";
   var DateFormat = "<?php echo DateFormat; ?>";
</script>
<?php if ($controller == 'Flight') { ?>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/flight.js<?php echo last_modifytime(FCPATH . 'webroot/js/flight.js'); ?>"></script>
<?php } ?>
<?php
if ($controller == 'Flight' && $methodName == 'search' && isset($searchData)) { ?>
   <script>
      flightsearchData = <?php echo json_encode($searchData); ?>

   </script>
<?php } ?>
<script type="text/javascript"
   src="<?php echo site_url('webroot'); ?>/vendor/select2/select2.min.js<?php echo last_modifytime(FCPATH . 'webroot/vendor/select2/select2.min.js'); ?>"></script>
<script type="text/javascript"
   src="<?php echo site_url('webroot'); ?>/js/slick.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/slick.min.js'); ?>"></script>
<script type="text/javascript"
   src="<?php echo site_url('webroot'); ?>/js/custom.js<?php echo last_modifytime(FCPATH . 'webroot/js/custom.js'); ?>"></script>
<script type="text/javascript"
   src="<?php echo site_url('webroot'); ?>/js/sidebar.js<?php echo last_modifytime(FCPATH . 'webroot/js/sidebar.js'); ?>"></script>
<script type="text/javascript"
   src="<?php echo site_url('webroot'); ?>/js/jquery.form-validator.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery.form-validator.min.js'); ?>"></script>
<?php if ($controller == 'Hotel') { ?>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/hotel.js<?php echo last_modifytime(FCPATH . 'webroot/js/hotel.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'GroupTicket') { ?>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/groupticket.js<?php echo last_modifytime(FCPATH . 'webroot/js/groupticket.js'); ?>"></script>
   <script>
      setTimeout(function () {
         let notificationModal = document.getElementById("notificationModal");
         if (notificationModal) {
            new bootstrap.Modal(notificationModal).show();
         }
      }, 900);
   </script>
<?php } ?>
<?php if ($controller == 'CarBooking') { ?>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/car.js<?php echo last_modifytime(FCPATH . 'webroot/js/car.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'VisaBooking') { ?>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/visa.js<?php echo last_modifytime(FCPATH . 'webroot/js/visa.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'Bus') { ?>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/bus.js<?php echo last_modifytime(FCPATH . 'webroot/js/bus.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'CruiseBooking') { ?>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/cruise.js<?php echo last_modifytime(FCPATH . 'webroot/js/cruise.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'HolidayBooking') { ?>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/holiday.js<?php echo last_modifytime(FCPATH . 'webroot/js/holiday.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'Tourguide') { ?>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/tourguide.js<?php echo last_modifytime(FCPATH . 'webroot/js/tourguide.js'); ?>"></script>
<?php } ?>
<?php if ($controller == 'Activities') { ?>
   <script type="text/javascript"
      src="<?php echo site_url('webroot'); ?>/js/activities.js<?php echo last_modifytime(FCPATH . 'webroot/js/activities.js'); ?>"></script>
<?php } ?>