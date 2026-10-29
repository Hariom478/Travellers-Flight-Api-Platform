<div class="content">
   <div class="page-content">
      <div class="page-content-area">
         <div class="card-header">
            <div class="table_title">
               <div class="topbar">
                  <h5 class="m0" style="color: white;"> <?php
                  echo $title ?> </h5>
               </div>
            </div>
         </div>
         <div class="card-body">
            <form name="web-partner" tts-form='true' action="<?php echo site_url('flight-ticket-import/check-pnr'); ?>"
               method="POST" id="flight-upload-ticket">
               <div class="view_head mb_10">
                  <div class="row">
                     <div class="col-md-12">
                        <span>Basic Information</span>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group">
                        <label> Web Partner / Company Name *</label>
                        <input type="text" class="form-control" name="webpartner_info" tts-get-web-partner-info="true"
                           tts-error-msg="Please enter search type" placeholder="Web Partner/Company Name">
                        <input type="hidden" name="tts_web_partner_info_id" tts-web-partner-info-id="true">
                        <input type="hidden" name="tts_web_partner_info" tts-web-partner-info="true">
                        <span class="success" webpartnerInfo="true"></span>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Api Supplier *</label>
                        <select class="form-control" name="supplier">
                           <option value="" selected>Select</option>
                           <?php if ($apiSupplier) {
                              foreach ($apiSupplier as $supplier) {
                                 if (!in_array($supplier['supplier_name'], array("KAFILA", "CRS", "GOFIRST"))) { ?>
                                    <option value="<?php echo $supplier['supplier_name']; ?>">
                                       <?php echo $supplier['supplier_name']; ?>
                                    </option>
                                 <?php }
                              }
                           } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Issue Supplier *</label>
                        <div class="form-group">
                           <select class="form-control" name="issue_supplier">
                              <option value="" selected>Select</option>
                              <?php if ($issueSupplier) {
                                 foreach ($issueSupplier as $supplier) { ?>
                                    <option value="<?php echo $supplier['id'] . "#" . $supplier['supplier_name']; ?>">
                                       <?php echo $supplier['supplier_name']; ?>
                                    </option>
                                 <?php }
                              } ?>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>PNR *</label>
                        <input class="form-control" name="pnr" placeholder="PNR">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Last Name </label>
                        <input class="form-control" name="last_name" placeholder="Last Name">
                        <span class="text-info"> Lead Passenger Last Name</span>
                     </div>

                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6 text-md-right">
                     <button class="btn-primary btn" type="submit">Import</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>