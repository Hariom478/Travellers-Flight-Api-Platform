<div class="modal-header">
   <h5 class="modal-title">Edit
      <?php echo 'Holiday Markup '; ?>
   </h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?php echo site_url('holiday/edit-admin-holiday-markup/') . dev_encode($id); ?>" method="post"
   tts-form="true" name="edit_visa_markup">
   <div class="modal-body">
      <div class="row">
         <div class="col-md-4">
            <div class="form-group">
               <label>Markup For *</label>
               <select class="form-select" name="markup_for" tts-markup-used-for="true">
                  <option value="">Please Select</option>
                  <?php $markup_used_for = get_active_whitelable_business();
                  foreach ($markup_used_for as $key => $data) { ?>
                     <option value="<?php echo $key ?>" <?php if ($key == $details['markup_for']) {
                           echo "selected";
                        } ?>><?php echo $key ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>

         <div class="col-md-4 <?php echo ($details['markup_for'] == 'B2B') ? '' : 'd-none'; ?>" tts-agent-class="true">
            <div class="form-group">
               <label>Agent Class *</label>
               <select class="form-select select_search" name="agent_class[]" placeholder="Holiday Type"
                  multiple="multiple">

                  <?php foreach ($agent_class_list as $key => $data) { ?>
                     <option value="<?php echo $data['id'] ?>" <?php if (in_array($data['id'], $details['agent_class'])) {
                           echo "selected";
                        } ?>><?php echo $data['class_name'] ?>
                     </option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Markup Type *</label>
               <select class="form-select" name="markup_type" placeholder="Markup Type">
                  <option value="fixed" <?php if ($details['markup_type'] == "fixed") {
                     echo "selected";
                  } ?>>Fixed</option>
                  <option value="percent" <?php if ($details['markup_type'] == "percent") {
                     echo "selected";
                  } ?>>Percent</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Value *</label>
               <input class="form-control" type="text" value="<?php echo $details['value']; ?>" name="value"
                  placeholder="Value">
            </div>
         </div>

         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Display Markup *</label>
               <select class="form-select" name="display_markup" placeholder="Display Markup">
                  <option value="in_tax" <?php if ($details['display_markup'] == "in_tax") {
                     echo "selected";
                  } ?>>In Tax</option>
                  <option value="in_service_charge" <?php if ($details['display_markup'] == "in_service_charge") {
                     echo "selected";
                  } ?>>In Service Charge</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Max Limit </label>
               <input class="form-control" type="text" value="<?php echo $details['max_limit']; ?>" name="max_limit"
                  placeholder="Max Limit">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Status *</label>
               <select class="form-select" name="status" placeholder="Status">
                  <option value="active" <?php if ($details['status'] == "active") {
                     echo "selected";
                  } ?>>Active
                  </option>
                  <option value="inactive" <?php if ($details['status'] == "inactive") {
                     echo "selected";
                  } ?>> Inactive
                  </option>
               </select>
            </div>
         </div>
      </div>
      <div class="row">
         <?php $destinationidArray = explode(',', $details['destination_id']); ?>
         <div class="col-md-8">
            <div class="form-group form-mb-20">
               <label>Holiday Destination *</label>
               <select name="destination_name[]" id="destination-name" class="form-select select_search"
                  multiple="multiple" <?= (($details['destination_name'] == 'ANY')) ? 'disabled' : '' ?>>
                  <?php foreach ($holiday_destination as $key => $destination): ?>
                     <option value="<?= $destination['id'] . '_' . $destination['destination_name'] ?>"
                        <?= (in_array($destination['id'], $destinationidArray)) ? 'selected' : '' ?>>
                        <?= $destination['destination_name'] ?>
                     </option>
                  <?php endforeach; ?>
               </select>
            </div>
         </div>

         <div class="col-md-4 align-self-end">
            <div class="form-group form-mb-20">
               <label>
                  <input type="checkbox" tts-select-any="true" resettag="destination-name" name="destination_select"
                     value="ANY" class="Lead" <?php echo ($details['destination_name'] == 'ANY') ? 'checked' : ''; ?>> Any
                  Holiday Destination Name
               </label>
            </div>
         </div>

         <div class="col-md-8">
            <?php $themeArray = explode(',', $details['theme_id']); ?>
            <div class="form-group form-mb-20">
               <label>Holiday Theme *</label>
               <select name="theme_name[]" id="theme-name" class="form-select select_search" multiple="multiple"
                  <?= (($details['theme_name'] == 'ANY')) ? 'disabled' : '' ?>>
                  <?php foreach ($holiday_theme as $key => $theme): ?>
                     <option value="<?= $theme['id'] . '_' . $theme['theme_name'] ?>" <?= (in_array($theme['id'], $themeArray)) ? 'selected' : '' ?>>
                        <?= $theme['theme_name'] ?>
                     </option>
                  <?php endforeach; ?>
               </select>
            </div>
         </div>

         <div class="col-md-4 align-self-end">
            <div class="form-group form-mb-20">
               <label>
                  <input type="checkbox" tts-select-any="true" name="theme_select" resettag="theme-name" value="ANY" class="Lead" <?php echo ($details['theme_name'] == 'ANY') ? 'checked' : ''; ?>> Any Holiday Theme Name
               </label>
            </div>
         </div>


         <div class="col-md-8">
            <?php $packageArray = explode(',', $details['holiday_package_id']); ?>
            <div class="form-group form-mb-20">
               <label>Package Name *</label>
               <select name="holiday_package[]" id="holiday-package" class="form-select select_search"
                  multiple="multiple" <?= (($details['holiday_package'] == 'ANY')) ? 'disabled' : '' ?>>
                  <?php foreach ($holiday_package as $key => $package): ?>
                     <option value="<?= $package['id'] . '_' . $package['holiday_name'] ?>"
                        <?= (in_array($package['id'], $packageArray)) ? 'selected' : '' ?>>
                        <?= $package['holiday_name'] ?>
                     </option>
                  <?php endforeach; ?>
               </select>
            </div>
         </div>
         <div class="col-md-4 align-self-end">
            <div class="form-group form-mb-20">
               <label>
                  <input type="checkbox" tts-select-any="true" resettag="holiday-package" name="package_select"
                     value="ANY" class="Lead" <?php echo ($details['holiday_package'] == 'ANY') ? 'checked' : ''; ?>> Any
                  Holiday Package Name
               </label>
            </div>
         </div>

      </div>
   </div>
   <div class="modal-footer">
      <button class="btn btn-primary" type="submit">Save</button>
   </div>
</form>