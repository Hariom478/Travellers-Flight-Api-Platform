<style>
   .add__datePicker {
      border-radius: 14px;
      font-size: 10px;
      cursor: pointer;
      width: 20px;
      float: right;
   }

   .add__datePicker button {
      background: green;
      border: 0;
      color: #fff;
      border-radius: 14px;
      width: 22px;
      height: 22px;
      text-align: center;
      line-height: 22px;
   }

   .btn-minus {
      border-radius: 14px;
      font-size: 10px;
      cursor: pointer;
      width: 20px;
      float: right;
   }

   .btn-minus button {
      background: #a94442;
      border: 0;
      color: #fff;
      border-radius: 14px;
      width: 22px;
      height: 22px;
      text-align: center;
      line-height: 22px;
   }
</style>


<div class="content">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m0"> Edit Fare Rule</h5>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <form name="web-partner" tts-form='true' action="<?php echo site_url('private-fare/edit-rate-plan-update/' . dev_encode($details['id'])); ?>" method="POST" id="web-partner">
               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Air Type *</label>
                        <select class="form-control" name="air_type" placeholder="Air Type">
                           <option value="" selected>Select Air Type</option>
                           <option value="All" <?php echo  $details['air_type'] == "All" ? "selected" : ""; ?>>All</option>
                           <option value="Domestic" <?php echo  $details['air_type'] == "Domestic" ? "selected" : ""; ?>>Domestic</option>
                           <option value="International" <?php echo  $details['air_type'] == "International" ? "selected" : ""; ?>>International</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Refundable Type * </label>
                        <select class="form-control" name="refundable_type">
                           <option value="">Select...</option>
                           <option value="Refundable" <?php echo  $details['refundable_type'] == "Refundable" ? "selected" : ""; ?>>Refundable</option>
                           <option value="NonRefundable" <?php echo  $details['refundable_type'] == "NonRefundable" ? "selected" : ""; ?>>Non Refundable</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Airline *</label>
                        <input class="form-control" type="text" tts-get-airline="true" name="airline_code" placeholder="Airline" value='<?php echo  $details['airline_code'] . "-" . $details['airline_name']; ?>'>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Status *</label>
                        <select class="form-control" name="status" placeholder="Status">
                           <option value="">Select...</option>
                           <option value="active" <?php echo  $details['status'] == "active" ? "selected" : ""; ?>>Active</option>
                           <option value="inactive" <?php echo  $details['status'] == "inactive" ? "selected" : ""; ?>>Inactive</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <label>Booking Class *</label>
                        <input class="form-control" type="text" name="booking_class" placeholder="Booking Class,Like All,S,L" value="<?php echo  $details['booking_class']; ?>">
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <label>Fare Rule Description </label>
                        <textarea class="form-control tts-editornote" name="description" placeholder="Fare Rule Description" value="<?php echo  $details['description']; ?>"><?php echo  $details['description']; ?></textarea>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <h3 class="dash-borderRadius main-heading-content">Hand Baggage Details</h3>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group form-mb-20">
                              <label>Adult *</label>
                              <input class="form-control" type="text" name="hand_baggage_adult" placeholder="Adult" value="<?php echo  $details['hand_baggage_adult']; ?>">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group form-mb-20">
                              <label>Child *</label>
                              <input class="form-control" type="text" name="hand_baggage_child" placeholder="Child" value="<?php echo  $details['hand_baggage_child']; ?>">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group form-mb-20">
                              <label>Infant *</label>
                              <input class="form-control" type="text" name="hand_baggage_infant" placeholder="Infant" value="<?php echo  $details['hand_baggage_infant']; ?>">
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <h3 class="dash-borderRadius main-heading-content">Check-In Baggage Details</h3>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group form-mb-20">
                              <label>Adult *</label>
                              <input class="form-control" type="text" name="checkin_baggage_adult" placeholder="Adult" value="<?php echo  $details['checkin_baggage_adult']; ?>">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group form-mb-20">
                              <label>Child *</label>
                              <input class="form-control" type="text" name="checkin_baggage_child" placeholder="Child" value="<?php echo  $details['checkin_baggage_child']; ?>">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group form-mb-20">
                              <label>Infant *</label>
                              <input class="form-control" type="text" name="checkin_baggage_infant" placeholder="Infant" value="<?php echo  $details['checkin_baggage_infant']; ?>">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form__buttons text-md-right">
                  <button type="submit" class="btn btn-primary">Submit</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>