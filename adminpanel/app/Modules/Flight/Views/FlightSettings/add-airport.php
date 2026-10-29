<div class="modal-header">
        <h5 class="modal-title">Add <?php echo 'Airport';?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

   <form action="<?php echo site_url('flightsettings/add-airport'); ?>" method="post" tts-form="true" name="add_feedback" enctype="multipart/form-data">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Airport Code *  </label>
                  <input class="form-control" type="text" name="code" placeholder="Airport Code">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label> Name * </label>
                  <input class="form-control" type="text" name="name" placeholder="Name">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label> City Name * </label>
                  <input class="form-control" type="text" name="city_name" placeholder="City Name">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label> City Code * </label>
                  <input class="form-control" type="text" name="city_code" placeholder="City Code">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label> Country Name * </label>
                  <input class="form-control" type="text" name="country_name" placeholder="Country Name">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label> Country Code * </label>
                  <input class="form-control" type="text" name="country_code" placeholder="Country Code">
               </div>
            </div>
         </div>
      </div>
      <div class="modal-footer">
         <button class="btn btn-primary" type="submit">Save</button>
      </div>
   </form>
