<?php echo view('template/search.php'); ?>
<?php echo view('template/home-page-offers.php'); ?>
<?php if (0) { ?>
<!-- Modal -->
<div class="modal" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-default modal-lg modal-dialog-centered">
      <div class="modal-content  modal-content-refresh">
         <div class="modal-body modal-body-refresh">
            <div id="notificationModalCaptions" class="carousel slide" data-bs-ride="carousel">
               <div class="carousel-indicators">
                  <?php
                     if ($notification_list) {
                     	foreach ($notification_list as $key => $notification) { ?>
                  <button type="button" data-bs-target="#notificationModalCaptions" data-bs-slide-to="<?php echo $key;?>" class="<?php if ($key == 0) {echo 'active';} ?>" aria-current="true" aria-label="Slide <?php echo $key;?>"></button>
                  <?php }}?>
               </div>
               <div class="carousel-inner">
                  <?php
                     if ($notification_list) {
                     	foreach ($notification_list as $key => $notification) {
                     		?>
                  <div class="carousel-item <?php if ($key == 0) {echo 'active';} ?>">
                     <img src="<?php echo root_url . "uploads/sliders/" . $notification['slider_image']; ?>" class="d-block w-100" alt="<?php echo $notification['slider_text1']; ?>">
                     <?php if ($notification['slider_text1'] || $notification['slider_text2']  || $notification['url'] || $notification['url_button_text']){?>
                     <div class="modal-footer-refresh ">
                        <h5><?php echo $notification['slider_text1']?></h5>
                        <p><?php echo $notification['slider_text2']?></p>
                        <?php if ($notification['url'] && $notification['url_button_text']){ ?>
                        <a href="<?php  echo $notification['url'];?>" class="btn btn-modal-refresh"><?php  echo $notification['url_button_text'];?></a>
                        <?php } ?>
                     </div>
                     <?php }?>
                  </div>
                  <?php }
                     } ?>
               </div>
            </div>
            <button type="button" class="btn-close modal-refresh-btn" data-bs-dismiss="modal" aria-label="Close">
            <span class="fa fa-close"></span>
            </button>
         </div>
      </div>
   </div>
</div>
<?php } ?>