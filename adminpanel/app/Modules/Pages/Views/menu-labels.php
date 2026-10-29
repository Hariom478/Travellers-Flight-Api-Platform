<div class="modal-header">
   <h5 class="modal-title">Edit Menu Labels</h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
   <form action="<?php echo site_url('pages/edit-menu-labels'); ?>" method="post" tts-form="true">
      <?php if (!empty($details) && is_array($details)) {

         foreach ($details as $key => $data) {
            ?>
            <div class="row">
               <div class="col-md-8">
                  <div class="form-group form-mb-20">
                     <label class="form-label">Label <?php echo $key + 1; ?></label>
                     <input class="form-control" type="text" name="menu_name[<?php echo $data['id']; ?>]"
                        placeholder="Label <?php echo $key + 1; ?>" value="<?php echo $data['menu_name']; ?>">
                  </div>
               </div>
            </div>
            <?php if (permission_access_error("Page", "update_menu_label")) { ?>
               <div class="col-md-12">
                  <div class="form-group form-mb-20">
                     <button class="btn btn-primary " id="<?php echo 'edit_menu' . dev_encode($data['id']) ?>"
                        type="submit">Update</button>
                  </div>
               </div>
            <?php } ?>
         <?php }
      } ?>
   </form>
</div>