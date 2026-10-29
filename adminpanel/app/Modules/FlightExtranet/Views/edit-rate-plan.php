<div class="modal-header">
   <h5 class="modal-title">Rate Plan</h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
   </button>
</div>
<form action="<?php echo site_url('private-fare/edit-rate-plan/' . dev_encode($id)); ?>" method="post" tts-form="true" name="add_blogs">
<div class="modal-body">
      <div class="row ">
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label for="plan-name">Plan Name</label>
               <input type="text" id="plan-name" value="<?php echo $details['plan_name']?>" class="form-control" name="plan_name" placeholder="Plan Name">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label for="booking-class">Booking Class</label>
               <input type="text" id="booking-class" class="form-control" value="<?php echo $details['booking_class']?>" name="booking_class" placeholder="Booking Class"
                  value="">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label></label>
               <select class="form-control " name="cabin_class" id="cabin-class">
                  <option value="Economy" <?php if ($details['cabin_class'] =='Economy'){ echo 'selected';}?>>Economy</option>
                  <option value="PremiumEconomy" <?php if ($details['cabin_class'] =='PremiumEconomy'){ echo 'selected';}?> >Premium Economy</option>
                  <option value="Business" <?php if ($details['cabin_class'] =='Business'){ echo 'selected';}?> >Business</option>
                  <option value="PremiumBusiness" <?php if ($details['cabin_class'] =='PremiumBusiness'){ echo 'selected';}?> >Premium Business</option>
                  <option value="First"  <?php if ($details['cabin_class'] =='First'){ echo 'selected';}?> >First</option>
               </select>
            </div>
         </div>
      </div>
      <div class="row justify-content-center ">
         <div class="col-md-4">
         </div>
         <div class="col-md-4">
            <span class="rate-plan__details__label">Base Fare</span>
         </div>
         <div class="col-md-4">
            <span class="rate-plan__details__label">Tax</span>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <span class="rate-plan__details__label">Adult</span>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Base Fare</label>
                     <input type="text" class="form-control" value="<?php echo $details['adult_base_fare']?>" name="adult_base_fare" placeholder="Base Fare">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Tax</label>
                     <input type="text" class="form-control" value="<?php echo $details['adult_tax']?>" name="adult_tax" placeholder="Tax">
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-12">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <span class="rate-plan__details__label">Child</span>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Base Fare</label>
                     <input type="text" class="form-control" value="<?php echo $details['child_base_fare']?>" name="child_base_fare" placeholder="Base Fare">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Tax</label>
                     <input type="text" class="form-control" value="<?php echo $details['child_tax']?>" name="child_tax" placeholder="Tax">
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-12">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <span class="rate-plan__details__label">Infant</span>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Base Fare</label>
                     <input type="text" class="form-control" value="<?php echo $details['infant_base_fare']?>" name="infant_base_fare" placeholder="Base Fare">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Tax</label>
                     <input type="text" class="form-control" value="<?php echo $details['infant_tax']?>" name="infant_tax" placeholder="Tax">
                  </div>
               </div>
            </div>
         </div>
      </div>
</div>
<div class="modal-footer text-md-right">
         <div class="form__buttons">
            <button type="submit" class="btn btn-primary">Submit </i>
            </button>
         </div>
</div>
</form>