<div class="modal-header">
        <h5 class="modal-title">Rate Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
<form action="<?php echo site_url('private-fare/add-rate-plan'); ?>" method="post" tts-form="true" name="add_blogs">      
   <div class="modal-body">
      <div class="row ">
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label for="plan-name">Plan Name</label>
               <input type="text" id="plan-name" class="form-control" name="plan_name" placeholder="Plan Name">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label for="booking-class">Booking Class</label>
               <input type="text" id="booking-class" class="form-control" name="booking_class" placeholder="Booking Class" value="">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label></label>
               <select class="form-control " name="cabin_class" id="cabin-class">
                  <option value="Economy" selected>Economy</option>
                  <option value="PremiumEconomy">Premium Economy</option>
                  <option value="Business">Business</option>
                  <option value="PremiumBusiness">Premium Business</option>
                  <option value="First">First</option>
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
         <!-- <div class="col-md-4">
            <span class="rate-plan__details__label">Gst</span>
            
            </div> -->
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
                     <input type="text" class="form-control" name="adult_base_fare" placeholder="Base Fare">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Tax</label>
                     <input type="text" class="form-control" name="adult_tax" placeholder="Tax">
                  </div>
               </div>
               <!--  <div class="col-md-4">
                  <div class="form-group form-mb-20">
                  
                      <label for="from-date">GST</label>
                  
                      <input type="text" class="form-control" name="adult_gst" placeholder="GSt">
                  
                      
                  
                  </div>
                  
                  </div> -->
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
                     <input type="text" class="form-control" name="child_base_fare" placeholder="Base Fare">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Tax</label>
                     <input type="text" class="form-control" name="child_tax" placeholder="Tax">
                  </div>
               </div>
               <!--  <div class="col-md-4">
                  <div class="form-group form-mb-20">
                  
                       <label for="from-date">GST</label>
                  
                      <input type="text" class="form-control" name="child_gst" placeholder="GSt">
                  
                     
                  
                  </div>
                  
                  </div> -->
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
                     <input type="text" class="form-control" name="infant_base_fare" placeholder="Base Fare">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Tax</label>
                     <input type="text" class="form-control" name="infant_tax" placeholder="Tax">
                  </div>
               </div>
               <!--  <div class="col-md-4">
                  <div class="form-group form-mb-20">
                  
                      <label for="from-date">GST</label>
                  
                      <input type="text" class="form-control" name="infant_gst" placeholder="GSt">
                  
                      
                  
                  </div>
                  
                  </div> -->
            </div>
         </div>
      </div>
</div>
   <div class="modal-footer">
         <div class="form__buttons">
            <button type="submit" class="btn btn-primary">Submit</i>
            </button>
         </div>
      </div>
   </form>