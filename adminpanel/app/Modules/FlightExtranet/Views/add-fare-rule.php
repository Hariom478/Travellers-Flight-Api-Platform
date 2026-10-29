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
                  <h5 class="m-0"> Add Fare Rule</h5>
               </div>
               <div class="col-md-8 text-md-end">

               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <form name="web-partner" tts-form='true' action="<?php echo site_url('private-fare/fare-rule-save'); ?>" method="POST" id="web-partner" Class="col-md-12">
               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Air Type *</label>
                        <select class="form-control" name="air_type" placeholder="Air Type">
                           <option value="" selected>Select Air Type</option>
                           <option value="All" selected>All</option>
                           <option value="Domestic">Domestic</option>
                           <option value="International">International</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Refundable Type * </label>
                        <select class="form-control" name="refundable_type">
                           <option value="">Select...</option>
                           <option value="Refundable">Refundable</option>
                           <option value="NonRefundable">Non Refundable</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Airline *</label>
                        <input class="form-control" type="text" tts-get-airline="true" name="airline_code" placeholder="Airline">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Status *</label>
                        <select class="form-control" name="status" placeholder="Status">
                           <option value="">Select...</option>
                           <option value="active">Active</option>
                           <option value="inactive">Inactive</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <label>Booking Class *</label>
                        <input class="form-control" type="text" name="booking_class" placeholder="Booking Class,Like All,S,L" value="All">
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <label>Fare Rule Description </label>
                        <textarea class="form-control tts-editornote" name="description" placeholder="Fare Rule Description"></textarea>
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
                              <input class="form-control" type="text" name="hand_baggage_adult" placeholder="Adult" value="7 Kg">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group form-mb-20">
                              <label>Child *</label>
                              <input class="form-control" type="text" name="hand_baggage_child" placeholder="Child" value="7 Kg">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group form-mb-20">
                              <label>Infant *</label>
                              <input class="form-control" type="text" name="hand_baggage_infant" placeholder="Infant" value="0 Kg">
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
                              <input class="form-control" type="text" name="checkin_baggage_adult" placeholder="Adult" value="7 Kg">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group form-mb-20">
                              <label>Child *</label>
                              <input class="form-control" type="text" name="checkin_baggage_child" placeholder="Child" value="7 Kg">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group form-mb-20">
                              <label>Infant *</label>
                              <input class="form-control" type="text" name="checkin_baggage_infant" placeholder="Infant" value="0 Kg">
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