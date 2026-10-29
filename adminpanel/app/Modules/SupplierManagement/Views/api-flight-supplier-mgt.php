
<div class="modal-header">
                <h5 class="modal-title"><?php echo ' ' . ' Flight API Management'; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

   <form action="<?php echo site_url('supplier-management/api-flight-supplier-mgt'); ?>" method="post"
      onsubmit="return validateForm()" tts-form="true" name="api_supplier">
      <div class="modal-body">
         <div class="row">
            <?php if ($supplier_list) {
               foreach ($supplier_list as $list) { ?>
            <div class="col-md-3">
               <div class="form-group form-mb-20">
                  <label>
                  <input class="" type="checkbox" name="api_supplier[]"
                     value="<?php echo $list['id']; ?>" <?php echo $list['flight'] == "active" ? "checked" : "" ?>> <?php echo $list['supplier_name']; ?>
                  </label>
               </div>
            </div>
            <?php }
               } ?>
         </div>
         <div class="row">
            <?php if ($api_account_group && !empty($api_account_group)) {
               foreach ($api_account_group as $group_key => $api_account) {
                   ?>
            <div class="col-md-12 p0">
               <h6 class="viewld_h5"><?php echo $group_key; ?></h6>
            </div>
            <?php foreach ($api_account as $account_key => $account) { ?>
            <input type="hidden" value="<?php echo $account['id']; ?>"
               name="api_account_group[<?php echo $group_key ?>][<?php echo $account_key ?>][id]">
            <div class="col-md-5">
               <div class="form-group form-mb-20">
                  <label>Username</label>
                  <input class="form-control" type="text"
                     name="api_account_group[<?php echo $group_key ?>][<?php echo $account_key ?>][username]"
                     value="<?php echo $account['username']; ?>"
                     placeholder="username">
               </div>
            </div>
            <div class="col-md-5">
               <div class="form-group form-mb-20">
                  <label>Password</label>
                  <input class="form-control" type="text"
                     name="api_account_group[<?php echo $group_key ?>][<?php echo $account_key ?>][password]"
                     value="<?php echo $account['password']; ?>"
                     placeholder="password">
               </div>
            </div>
            <div class="col-md-2 align-self-end">
               <div class="form-group form-mb-20">
                  <label class="mt_20">
                  <input id="api_account_group[<?php echo $group_key; ?>][<?php echo $account_key; ?>][status]"
                     class="" type="radio" onchange='api_flight_supplier_status(this,"<?php echo $group_key; ?>")'
                     name="temp[<?php echo $group_key; ?>]"
                     value="<?php echo $account['status']; ?>" <?php echo $account['status'] == "active" ? "checked" : "" ?>> <?php echo 'Active'; ?>
                  </label>
               </div>
               <input type="hidden" value="<?php echo $account['status']; ?>" class="<?php echo $group_key; ?>"
                  name="api_account_group[<?php echo $group_key; ?>][<?php echo $account_key; ?>][status]">
            </div>
            <?php } ?>
            <?php }
               } ?>
         </div>
      </div>
      <div class="modal-footer">
         <button class="btn btn-primary" type="submit" >Save</button>
      </div>
   </form>
