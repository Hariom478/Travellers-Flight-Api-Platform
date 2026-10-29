

<div class="modal-header">
      <h5 class="modal-title">Add <?php echo ' '.' Flight API Management';?></h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?php echo site_url('supplier-management/add-flight-api-mgt'); ?>" method="post" onsubmit="return validateForm()" tts-form="true" name="add_supplier" enctype="multipart form-data">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-3">
               <div class="form-group form-mb-20">
                  <label>Suppliers*</label>
                  <select class="form-control" name="api_supplier_id" >
                     <option value="" selected>Select Suppler</option>
                     <?php if ($supplier_list){
                        foreach ($supplier_list as $list){ ?>
                     <option value="<?php echo $list['id']?>"><?php echo $list['supplier_name']?></option>
                     <?php }
                        }?>
                  </select>
               </div>
            </div>
            <div class="col-md-3">
               <div class="form-group form-mb-20">
                  <label>Air Type *</label>
                  <select class="form-control" name="air_type">
                     <option value="All"> All</option>
                     <option value="Domestic" > Domestic </option>
                     <option value="International" > International </option>
                  </select>
               </div>
            </div>
            <div class="col-md-3">
               <div class="form-group form-mb-20">
                  <label>Search Type *</label>
                  <select class="form-control" name="search_type">
                     <option value="All"> All</option>
                     <option value="Oneway"> Oneway </option>
                     <option value="Roundtrip"> Roundtrip </option>
                  </select>
               </div>
            </div>
            <div class="col-md-3">
               <div class="form-group form-mb-20">
                  <label> Status *</label>
                  <select class="form-control" name="status">
                     <option value="active"> Active</option>
                     <option value="inactive" > Inactive </option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label> Allowed Airline  </label>
                  <input class="form-control" tts-get-airline-multiple="true" type="text" name="allowed_airline" placeholder="Allowed Airline">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label> Excluded Airline  </label>
                  <input class="form-control" type="text" tts-get-airline-multiple="true" name="excluded_airline" placeholder="Excluded Airline">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Fare Type</label>
                  <select class="form-control select_search" name="fare_type[]" multiple="multiple">
                     <option value="">Select Fare Type</option>
                     <?php
                        $get_fare_type = get_fare_type();
                        if ($get_fare_type) {
                            foreach ($get_fare_type as $key => $list) { ?>
                     <option value="<?php echo $key ?>"><?php echo $key ?></option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
         </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit" >Save</button>
      </div>
   </form>
