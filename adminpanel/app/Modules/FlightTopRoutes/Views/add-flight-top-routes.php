<div class="modal-header">

   <h5 class="modal-title" id="exampleModalLongTitle">Add <?php echo 'Flight Top Routes'; ?></h5>

   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>

<div class="vewmodelhed">

   <form action="<?php echo site_url('flight-top-routes/flight-top-routes-saved'); ?>" method="post" tts-form="true"
      name="add_flight_discount">

      <div class="modal-body">

         <div class="row align-items-center">

            <div class="col-md-4">

               <div class="form-group form-mb-20">

                  <label>Journey Type *</label>

                  <select class="form-select select_search" name="journeytype" placeholder="Journey Type">

                     <option value="oneway" selected>Oneway</option>

                     <option value="round-trip">Round Trip</option>

                  </select>

               </div>

            </div>

            <div class="col-md-4">

               <div class="form-group form-mb-20">

                  <label>Origin * </label>

                  <input class="form-control" type="text" tts-get-flight-top-route="true" name="origin"
                     placeholder="Origin">

                  <input type="hidden" name="origin_code">

               </div>

            </div>

            <div class="col-md-4">

               <div class="form-group form-mb-20">

                  <label>Destination * </label>

                  <input class="form-control" type="text" tts-get-flight-top-route="true" name="destination"
                     placeholder="Destination">

                  <input type="hidden" name="destination_code">

               </div>

            </div>



            <div class="col-md-4">

               <div class="form-group form-mb-20">

                  <label>Depart Date </label>

                  <input class="form-control" type="text" flight-departure-date="true" name="depart_date"
                     placeholder="Depart date">

               </div>

            </div>



            <div class="col-md-4">

               <div class="form-group form-mb-20">

                  <label>Return Date </label>

                  <input class="form-control" type="text" flight-return-date="true" name="return_date"
                     placeholder="Return date">

               </div>

            </div>

            <div class="col-md-4">

               <div class="form-group form-mb-20">

                  <label>Cabin Class *</label>

                  <select class="form-select select_search" name="cabin_class" placeholder="Cabin Class">

                     <option value="Economy">Economy</option>

                     <option value="PremiumEconomy">Premium Economy</option>

                     <option value="Business">Business</option>

                     <option value="First">First</option>

                     <option value="PremiumBusiness">Premium Business</option>

                  </select>

               </div>

            </div>

            <div class="col-md-4">

               <div class="form-group form-mb-20">

                  <label> Direct Flight *</label>

                  <select class="form-select select_search" name="direct_flight" placeholder="Direct Flight">

                     <option value="true" selected>True</option>

                     <option value="false"> False</option>

                  </select>

               </div>

            </div>



            <div class="col-md-4">

               <div class="form-group form-mb-20">

                  <label>Adult *</label>

                  <select class="form-select" name="adult" placeholder="adult">

                     <option value="" selected>Select Adult</option>

                     <?php for ($adult = 1; $adult <= 10; $adult++): ?>

                        <option value="<?php echo $adult; ?>"><?php echo $adult; ?> Adult</option>

                     <?php endfor; ?>

                  </select>

               </div>

            </div>

            <div class="col-md-4">

               <div class="form-group form-mb-20">

                  <label>Child *</label>

                  <select class="form-select" name="child" placeholder="child">

                     <?php for ($Child = 0; $Child <= 10; $Child++): ?>

                        <option value="<?php echo $Child; ?>"><?php echo $Child; ?> Child</option>

                     <?php endfor; ?>

                  </select>

               </div>

            </div>

            <div class="col-md-4">

               <div class="form-group form-mb-20">

                  <label>Infant *</label>

                  <select class="form-select" name="infant" placeholder="infant">

                     <?php for ($x = 0; $x <= 10; $x++): ?>

                        <option value="<?php echo $x; ?>"><?php echo $x; ?> Infant</option>

                     <?php endfor; ?>

                  </select>

               </div>

            </div>



            <div class="col-md-4">

               <div class="form-group form-mb-20">

                  <label>Price </label>

                  <input class="form-control" type="text" name="price" placeholder="Price">

               </div>

            </div>



            <div class="col-md-4">

               <div class="form-group form-mb-20">

                  <label>Status *</label>

                  <select class="form-select" name="status" placeholder="Status">

                     <option value="active" selected>Active</option>

                     <option value="inactive"> Inactive</option>

                  </select>

               </div>

            </div>

         </div>

      </div>

      <div class="modal-footer">

         <button class="btn btn-primary" type="submit">Save</button>

      </div>

   </form>

</div>