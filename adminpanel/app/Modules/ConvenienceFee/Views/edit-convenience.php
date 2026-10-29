<div class="modal-header">
   <h5 class="modal-title">Edit <?php echo 'Convenience Fee'; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">
   <form action="<?php echo site_url('convenience-fee/edit-convenience/' . dev_encode($id)); ?>" method="post" tts-form="true" name="edit_bankaccounts">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label>ConvenienceFee For *</label>
                  <select class="form-select" name="convenience_fee_for" tts-markup-used-for="true">
                     <option value="">Please Select</option>
                     <?php $markup_used_for = get_active_whitelable_business();
                     foreach ($markup_used_for as $key => $data) {  ?>
                        <option value="<?php echo $key ?>" <?php if ($key == $details['convenience_fee_for']) {
                                                               echo "selected";
                                                            } ?>><?php echo $key ?></option>
                     <?php } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-4 <?php echo ($details['convenience_fee_for'] == 'B2B') ? '' : 'd-none'; ?> " tts-agent-class="true">
               <div class="form-group form-mb-20">
                  <label>Agent Class *</label>
                  <select class="form-select select_search" name="agent_class_id[]" multiple="multiple">
                     <?php foreach ($agent_class_list as $key => $data) {  ?>
                        <option value="<?php echo $data['id'] ?>" <?php if (in_array($data['id'], $details['agent_class_id'])) {
                                                                     echo "selected";
                                                                  } ?>><?php echo $data['class_name'] ?></option>
                     <?php } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Payment Gateway * </label>
                  <select class="form-select" name="payment_getway">
                     <?php foreach ($payment_gateway as $gateway) : ?>
                        <option value="<?= $gateway ?>" <?php if ($details['payment_getway'] == $gateway) {
                                                         echo 'selected';
                                                      } ?>><?= $gateway ?></option>
                     <?php endforeach; ?>
                  </select>
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group form-mb-20">
                  <label>Service * </label>
                  <select class="form-select select_search" name="service[]" multiple="multiple">
                     <?php $services = get_active_whitelable_service();
                     foreach ($services as $service) {   ?>
                        <option value="<?= $service ?>" <?php if (in_array($service, $details['service'])) {
                                                            echo "selected";
                                                         } ?>><?= $service ?></option>
                     <?php } ?>
                  </select>
               </div>
            </div>
         </div>



         <div class="row">
            <div class="col-md-4 align-items-center d-flex">
               <h6 class="mt-27"> Debit Card</h6>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label> Debit Card </label>
                  <input class="form-control" type="text" name="debit_card_value" value="<?php echo $details['debit_card_value']; ?>" placeholder="Value">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Type </label>
                  <select class="form-select" name="debit_card_type">
                     <option value="fixed" <?php if ($details['debit_card_type'] == "fixed") {
                                                echo 'selected';
                                             } ?>>Fixed
                     </option>
                     <option value="percentage" <?php if ($details['debit_card_type'] == "percentage") {
                                                   echo 'selected';
                                                } ?>>Percentage
                     </option>
                  </select>
               </div>
            </div>
         </div>



         <div class="row">
            <div class="col-md-4 align-items-center d-flex">
               <h6 class="mt-27"> Credit Card</h6>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label> Credit Card </label>
                  <input class="form-control" type="text" name="credit_card_value" value="<?php echo $details['credit_card_value']; ?>" placeholder="Value">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Type </label>
                  <select class="form-select" name="credit_card_type">
                     <option value="fixed" <?php if ($details['credit_card_type'] == "fixed") {
                                                echo 'selected';
                                             } ?>>Fixed
                     </option>
                     <option value="percentage" <?php if ($details['credit_card_type'] == "percentage") {
                                                   echo 'selected';
                                                } ?>>Percentage
                     </option>
                  </select>
               </div>
            </div>
         </div>




         <div class="row">
            <div class="col-md-4 align-items-center d-flex">
               <h6 class="mt-27">Net Banking</h6>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label> Value </label>
                  <input class="form-control" type="text" name="net_banking_value" placeholder="Value" value="<?php echo $details['net_banking_value']; ?>">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Type </label>
                  <select class="form-select" name="net_banking_type">
                     <option value="fixed" <?php if ($details['net_banking_type'] == "fixed") {
                                                echo 'selected';
                                             } ?>>Fixed
                     </option>
                     <option value="percentage" <?php if ($details['net_banking_type'] == "percentage") {
                                                   echo 'selected';
                                                } ?>>Percentage
                     </option>
                  </select>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-4 align-items-center d-flex">
               <h6 class="mt-27">UPI</h6>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label> Value </label>
                  <input class="form-control" type="text" name="upi_value" placeholder="Value" value="<?php echo $details['upi_value']; ?>">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Type </label>
                  <select class="form-select" name="upi_type">
                     <option value="fixed" <?php if ($details['upi_type'] == "fixed") {
                                                echo 'selected';
                                             } ?>>Fixed
                     </option>
                     <option value="percentage" <?php if ($details['upi_type'] == "percentage") {
                                                   echo 'selected';
                                                } ?>>Percentage
                     </option>
                  </select>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-4 align-items-center d-flex">
               <h6 class="mt-27">Mobile Wallet</h6>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label> Value </label>
                  <input class="form-control" type="text" name="mobile_wallet_value" placeholder="Value" value="<?php echo $details['mobile_wallet_value']; ?>">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Type </label>
                  <select class="form-select" name="mobile_wallet_type">
                     <option value="fixed" <?php if ($details['mobile_wallet_type'] == "fixed") {
                                                echo 'selected';
                                             } ?>>Fixed
                     </option>
                     <option value="percentage" <?php if ($details['mobile_wallet_type'] == "percentage") {
                                                   echo 'selected';
                                                } ?>>Percentage
                     </option>
                  </select>
               </div>
            </div>
         </div>
      </div>
      <div class="modal-footer">
         <button type="submit" class="btn btn-primary">Save</button>
      </div>
   </form>
</div>