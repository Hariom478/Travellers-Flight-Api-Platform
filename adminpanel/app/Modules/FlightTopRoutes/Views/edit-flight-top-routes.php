<div class="modal-header">

   <h5 class="modal-title" id="exampleModalLongTitle">Edit <?php echo 'Flight Top Routes '; ?></h5>

   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>

<form action="<?php echo site_url('flight-top-routes/edit-flight-top-routes-seved/' . dev_encode($id)); ?>"
   method="post" tts-form="true" name="add_flight_discount">

   <div class="modal-body">

      <div class="row align-items-center">

         <div class="col-md-4">

            <div class="form-group form-mb-20">

               <label>Journey Type *</label>

               <select class="form-select select_search" name="journeytype" placeholder="Journey Type">

                  <option value="oneway" <?php if ($details['journeytype'] == "oneway") {
                     echo "selected";
                  } ?>>Oneway
                  </option>

                  <option value="round-trip" <?php if ($details['journeytype'] == "round-trip") {
                     echo "selected";
                  } ?>>
                     Round Trip</option>

               </select>

            </div>

         </div>

         <div class="col-md-4">

            <div class="form-group form-mb-20">

               <label>Origin * </label>

               <input class="form-control" type="text" tts-get-flight-top-route="true"
                  value="<?php echo $details['origin'] ?>" name="origin" placeholder="Origin">

               <input type="hidden" name="origin_code" value="<?php echo $details['origin_code'] ?>">

            </div>

         </div>

         <div class="col-md-4">

            <div class="form-group form-mb-20">

               <label>Destination * </label>

               <input class="form-control" type="text" tts-get-flight-top-route="true"
                  value="<?php echo $details['destination'] ?>" name="destination" placeholder="Destination">

               <input type="hidden" name="destination_code" value="<?php echo $details['destination_code'] ?>">

            </div>

         </div>



         <div class="col-md-4">

            <div class="form-group form-mb-20">

               <label>Depart Date </label>

               <input class="form-control" type="text" flight-departure-date="true"
                  value="<?php echo $details['depart_date'] ?>" name="depart_date" placeholder="Depart date">

            </div>

         </div>



         <div class="col-md-4">

            <div class="form-group form-mb-20">

               <label>Return Date </label>

               <input class="form-control" type="text" flight-return-date="true"
                  value="<?php echo $details['return_date'] ?>" name="return_date" placeholder="Return date">

            </div>

         </div>

         <div class="col-md-4">

            <div class="form-group form-mb-20">

               <label>Cabin Class *</label>

               <select class="form-select select_search" name="cabin_class" placeholder="Cabin Class">

                  <option value="Economy" <?php if ($details['cabin_class'] == "Economy") {
                     echo "selected";
                  } ?>>Economy
                  </option>

                  <option value="PremiumEconomy" <?php if ($details['cabin_class'] == "PremiumEconomy") {
                     echo "selected";
                  } ?>>Premium Economy</option>

                  <option value="Business" <?php if ($details['cabin_class'] == "Business") {
                     echo "selected";
                  } ?>>
                     Business</option>

                  <option value="First" <?php if ($details['cabin_class'] == "First") {
                     echo "selected";
                  } ?>>First
                  </option>

                  <option value="PremiumBusiness" <?php if ($details['cabin_class'] == "PremiumBusiness") {
                     echo "selected";
                  } ?>>Premium Business</option>

               </select>

            </div>

         </div>

         <div class="col-md-4">

            <div class="form-group form-mb-20">

               <label> Direct Flight *</label>

               <select class="form-select select_search" name="direct_flight" placeholder="Direct Flight">

                  <option value="true" <?php if ($details['direct_flight'] == "true") {
                     echo "selected";
                  } ?>>True
                  </option>

                  <option value="false" <?php if ($details['direct_flight'] == "false") {
                     echo "selected";
                  } ?>> False
                  </option>

               </select>

            </div>

         </div>



         <div class="col-md-4">

            <div class="form-group form-mb-20">

               <label>Adult *</label>

               <select class="form-select" name="adult" placeholder="adult">

                  <option value="" selected>Select Adult</option>

                  <?php for ($adult = 1; $adult <= 10; $adult++): ?>



                     <?php $selected = ($adult == $details['adult']) ? 'selected' : ''; ?>

                     <option value="<?php echo $adult; ?>" <?php echo $selected; ?>><?php echo $adult; ?> Adult</option>

                  <?php endfor; ?>

               </select>

            </div>

         </div>

         <div class="col-md-4">

            <div class="form-group form-mb-20">

               <label>Child *</label>

               <select class="form-select" name="child" placeholder="child">

                  <?php for ($Child = 0; $Child <= 10; $Child++): ?>



                     <?php $selected = ($Child == $details['child']) ? 'selected' : ''; ?>

                     <option value="<?php echo $Child; ?>" <?php echo $selected; ?>><?php echo $Child; ?> Child</option>

                  <?php endfor; ?>

               </select>

            </div>

         </div>

         <div class="col-md-4">

            <div class="form-group form-mb-20">

               <label>Infant *</label>

               <select class="form-select" name="infant" placeholder="infant">

                  <?php for ($x = 0; $x <= 10; $x++): ?>

                     <?php $selected = ($x == $details['infant']) ? 'selected' : ''; ?>

                     <option value="<?php echo $x; ?>" <?php echo $selected; ?>><?php echo $x; ?> Infant</option>

                  <?php endfor; ?>

               </select>

            </div>

         </div>



         <div class="col-md-4">

            <div class="form-group form-mb-20">

               <label>Price </label>

               <input class="form-control" type="text" value="<?php echo $details['price'] ?>" name="price"
                  placeholder="Price">

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

   </div>

   <div class="modal-footer">

      <button class="btn btn-primary" type="submit">Save</button>

   </div>

</form>