<div class="modal-header">
   <h5 class="modal-title" >Add <?php echo 'Convenience Fee'; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('convenience-fee/add-convenience'); ?>" method="post" tts-form="true"
   name="add_bankaccounts">
   <div class="modal-body">
      <div class="row">
         <div class="col-md-4">
            <div class="form-group">
               <label>ConvenienceFee For *</label>
               <select class="form-select" name="convenience_fee_for" tts-markup-used-for="true">
                  <option disabled selected>Please Select</option>
                  <?php $markup_used_for = get_active_whitelable_business();
                     foreach ($markup_used_for as $key => $data) {
                     ?>
                  <option value="<?php echo $key ?>"><?php echo $key ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <div class="col-md-4 d-none" tts-agent-class="true">
            <div class="form-group">
               <label>Agent Class *</label>
               <select class="form-select select_search" name="agent_class_id[]" placeholder="Flight Type" multiple="multiple"> 
                  <?php foreach ($agent_class as $key => $data) {  ?>
                  <option value="<?php echo $data['id'] ?>"><?php echo $data['class_name'] ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Payment Gateway * </label>
               <select class="form-select" name="payment_getway">
                  <?php foreach($payment_gateway as $gateway): ?>
                  <option value="<?=$gateway?>"><?=$gateway?></option>
                  <?php endforeach; ?>
               </select>
            </div>
         </div>
         <div class="col-md-12">
            <div class="form-group form-mb-20">
               <label>Service * </label>
               <select class="form-select select_search" name="service[]" multiple="multiple">
                  <?php $service = get_active_whitelable_service();
                     foreach($service as $services) {
                     ?>
                  <option value="<?= $services ?>"><?= $services ?></option>
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
               <label> Debit Card value * </label>
               <input class="form-control" type="text" name="debit_card_value" placeholder="Debit Card Value">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Type </label>
               <select class="form-select" name="debit_card_type">
                  <option value="fixed" selected>Fixed</option>
                  <option value="percentage">Percentage</option>
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
               <label> Credit Card value * </label>
               <input class="form-control" type="text" name="credit_card_value" placeholder="Credit Card Value">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Type </label>
               <select class="form-select" name="credit_card_type">
                  <option value="fixed" selected>Fixed</option>
                  <option value="percentage">Percentage</option>
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
               <input class="form-control" type="text" name="net_banking_value" placeholder="Value">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Type </label>
               <select class="form-select" name="net_banking_type">
                  <option value="fixed" selected>Fixed</option>
                  <option value="percentage">Percentage</option>
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
               <input class="form-control" type="text" name="upi_value" placeholder="Value">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Type </label>
               <select class="form-select" name="upi_type">
                  <option value="fixed" selected>Fixed</option>
                  <option value="percentage">Percentage</option>
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
               <input class="form-control" type="text" name="mobile_wallet_value" placeholder="Value">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Type </label>
               <select class="form-select" name="mobile_wallet_type">
                  <option value="fixed" selected>Fixed</option>
                  <option value="percentage">Percentage</option>
               </select>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Save</button>
   </div>
</form>