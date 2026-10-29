<div class="row">
   <div class="col-md-12 ">
      <h6 class="view_head">Fare Breakup</h6>
   </div>
   <?php if($FareBreakup) { foreach($FareBreakup as $breakupkey=>$Breakup) { ?>
   <div class="col-md-2">
      <div class="form-group">
         <?php if($breakupkey!="GST" && $breakupkey!="TaxBreakup") { ?>
         <label><?php  echo  $breakupkey." : ". $Breakup;?>  </label>
         <?php } else if($breakupkey=="GST" && $breakupkey!="TaxBreakup") { ?>
         <label><?php echo  "GST : " . $Breakup['CGSTAmount']+$Breakup['IGSTAmount']+$Breakup['SGSTAmount'];?>  </label>
         <?php } ?>
      </div>
   </div>
   <?php } } ?>
</div>
<div class="row">
   <div class="col-md-12 ">
      <h6 class="view_head">Deal & Markup </h6>
   </div>
   <div class="col-md-2">
      <div class="form-group">
         <label>Basic * </label>
         <input class="form-control" type="text"
            name="deal[basic]" 
            placeholder="Base " value  =  "<?php echo   isset($dealData['basic'])?$dealData['basic']:"0"; ?>" >
      </div>
   </div>
   <div class="col-md-2">
      <div class="form-group">
         <label>YQ *</label>
         <input class="form-control" type="text"
            name="deal[yq]"    
            placeholder="YQ" value  =  "<?php echo   isset($dealData['yq'])?$dealData['yq']:0; ?>">
      </div>
   </div>
   <div class="col-md-2">
      <div class="form-group">
         <label>Basic IATA *</label>
         <input class="form-control" type="text"
            name="deal[basic_iata]"
            placeholder="Basic IATA" value  =  "<?php echo   isset($dealData['basic_iata'])?$dealData['basic_iata']:0; ?>">
      </div>
   </div>
   <div class="col-md-2">
      <div class="form-group">
         <label>YQ IATA *</label>
         <input class="form-control" type="text"
            name="deal[yq_iata]"
            placeholder="YQ IATA" value  =  "<?php echo   isset($dealData['yq_iata'])?$dealData['yq_iata']:0; ?>">
      </div>
   </div>
   <div class="col-md-2">
      <div class="form-group">
         <label>Display Markup*</label>
         <select class="form-control" name="deal[display_markup]" placeholder="Display Markup">
            <option value="in_tax" <?php echo   isset($dealData['display_markup'])&& $dealData['display_markup']=='in_tax'?"selected":''; ?>  >In Tax</option>
            <option value="in_service_charge" <?php echo   isset($dealData['display_markup'])&& $dealData['display_markup']=='in_service_charge'?"selected":''; ?>>In Service Charge</option>
         </select>
      </div>
   </div>
   <div class="col-md-2">
      <div class="form-group">
         <label>Markup (Per pax)*</label>
         <input class="form-control" type="text"
            name="deal[markup]"
            placeholder="Markup (Per pax)" value  =  "<?php echo   isset($dealData['markup'])?$dealData['markup']:0; ?>">
      </div>
   </div>
</div>