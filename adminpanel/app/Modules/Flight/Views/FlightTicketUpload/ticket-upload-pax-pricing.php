
   <?php $paxType  =  $pax_type; ?>
   <div class="col-md-12">
      <h6 class="view_head"><?php echo $paxType; ?> Fare Details(Per pax Wise) </h6>
   </div>
   <div class="col-md-2">
      <div class="form-group">
         <label>Base Fare *</label>
         <input class="form-control" type="text"
            name="pricing[<?php echo $paxType; ?>][base_fare]" 
            placeholder="Base Fare" value  =  "<?php echo   isset($pricingInfo['base_fare'])?$pricingInfo['base_fare']:"0"; ?>" >
      </div>
   </div>
   <div class="col-md-2">
      <div class="form-group">
         <label>Tax *</label>
         <input class="form-control" type="text"
            name="pricing[<?php echo $paxType; ?>][tax]"    
            placeholder="Tax" value  =  "<?php echo   isset($pricingInfo['tax'])?$pricingInfo['tax']:0; ?>">
      </div>
   </div>
   <div class="col-md-2">
      <div class="form-group">
         <label>Other Charges *</label>
         <input class="form-control" type="text"
            name="pricing[<?php echo $paxType; ?>][other_charges]"
            placeholder="Other Charges" value  =  "<?php echo   isset($pricingInfo['other_charges'])?$pricingInfo['other_charges']:0; ?>">
      </div>
   </div>
   <!--   <div class="col-md-2">
      <div class="form-group">
      
          <label>Markup(In Taxes)*</label>
      
          <input class="form-control" type="text"
      
                  name="pricing[<?php echo $paxType; ?>][markup]"
      
                 placeholder="Markup" value  =  "<?php echo   isset($pricingInfo['markup'])?$pricingInfo['markup']:0; ?>">
      
      </div>
      
      </div> -->
