<div class="content">
   <div class="page-content">
      <div class="page-content-area">
         <div class="card-header">
            <div class="table_title">
               <div class="topbar">
                  <h5 class="m0"> <?php 
                     echo $title ?> </h5>
               </div>
            </div>
         </div>
         <div class="card-body">
            <form name="web-partner" tts-form='true'
               action="<?php echo site_url('flight-ticket-import/store-segement-info'); ?>"
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
                        <label>  Web Partner / Company Name *</label>
                        <input type="text" class="form-control" name="webpartner_info"
                           value="<?php if (isset($tripsegmentInfoData['for_issued_short_info'])) {
                              echo trim($tripsegmentInfoData['for_issued_short_info']);
                              } ?>" tts-get-web-partner-info="true"
                           tts-error-msg="Please enter search type"
                           placeholder="Companyid/Company Name/ Web Partner Name">
                        <input type="hidden" name="tts_web_partner_info_id" tts-web-partner-info-id="true"
                           value="<?php if (isset($tripsegmentInfoData['for_issued'])) {
                              echo trim($tripsegmentInfoData['for_issued']);
                              } ?>">
                        <input type="hidden" name="tts_web_partner_info" tts-web-partner-info="true"
                           value="<?php if (isset($tripsegmentInfoData['for_issued_info'])) {
                              echo trim($tripsegmentInfoData['for_issued_info']);
                              } ?>">
                        <span class  =  "success" webpartnerInfo = "true"><?php if (isset($tripsegmentInfoData['for_issued_info'])) {
                           echo trim($tripsegmentInfoData['for_issued_info']);
                           } ?></span>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Api Supplier *</label>
                        <select class="form-control" name="supplier">
                           <option value="" selected>Select</option>
                           <?php  if($apiSupplier) { foreach($apiSupplier as $supplier) { if(!in_array($supplier['supplier_name'],array("KAFILA","CRS"))) { ?>
                           <option value="<?php echo $supplier['supplier_name'];?>" <?php if (isset($tripsegmentInfoData['api_supplier']) && $tripsegmentInfoData['api_supplier']==$supplier['supplier_name']) {
                              echo  "selected";
                              } ?>><?php echo  $supplier['supplier_name'];?></option>
                           <?php } } } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Issue Supplier *</label>
                        <div class="form-group">
                        <select class="form-control" name="issue_supplier">
                           <option value="" selected>Select</option>
                           <?php  if($issueSupplier) { foreach($issueSupplier as $supplier) {  ?>
                           <option value="<?php echo  $supplier['id']."#".$supplier['supplier_name'];?>" <?php if (isset($tripsegmentInfoData['issue_by_supplier']) && $tripsegmentInfoData['issue_by_supplier']==$supplier['id']."#".$supplier['supplier_name']) {
                              echo  "selected";
                              } ?>> <?php echo  $supplier['supplier_name'];?></option>
                           <?php  } } ?>
                           </select>
                     </div>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Cabin Class *</label>
                        <select class="form-control" name="cabin_class" placeholder="Cabin Class">
                           <option value="Economy" <?php echo $tripsegmentInfoData['cabin_class']=="Economy"?"selected":"";  ?>>Economy</option>
                           <option value="PremiumEconomy" <?php echo $tripsegmentInfoData['cabin_class']=="PremiumEconomy"?"selected":"";  ?>>Premium Economy</option>
                           <option value="Business" <?php echo $tripsegmentInfoData['cabin_class']=="Business"?"selected":"";  ?>>Business</option>
                           <option value="PremiumBusiness" <?php echo $tripsegmentInfoData['cabin_class']=="PremiumBusiness"?"selected":"";  ?>>Premium Business</option>
                           <option value="First" <?php echo $tripsegmentInfoData['cabin_class']=="First"?"selected":"";  ?>>First</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Refundable Type *</label>
                        <select class="form-control" name="is_refundable">
                           <option value="0" <?php if (isset($tripsegmentInfoData['is_refundable']) && $tripsegmentInfoData['is_refundable']==1) {
                              echo  "selected";
                              } ?>>Non refundable</option>
                           <option value="1" <?php if (isset($tripsegmentInfoData['is_refundable']) && $tripsegmentInfoData['is_refundable']==1) {
                              echo  "selected";
                              } ?>>Refundable</option>
                        </select>
                     </div>
                  </div>
                  <?php  if(0) { ?>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label>Note</label>
                        <textarea class="form-control" name="issuer_remark" placeholder="Note" rows="3"><?php if (isset($tripsegmentInfoData['issuer_remark'])) {
                           echo trim($tripsegmentInfoData['issuer_remark']);
                           } ?></textarea>
                     </div>
                  </div>
                  <?php } ?>
               </div>
               <div tts-call-put-trip-html="true">
                  <?php 
                     echo  view('Modules\FlightTicketImport\Views\trip-segment-exist-info', array('tripsegmentInfo'=>$tripsegmentInfo));
                     ?>
               </div>
               <div class  =  "row">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label>Airline Remark</label>
                        <textarea class="form-control" name="airline_remark" placeholder="Remark" rows="2"><?php if (isset($tripsegmentInfoData['airline_remark'])) {
                           echo trim($tripsegmentInfoData['airline_remark']);
                           } ?></textarea>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 text-md-right">
                     <input class="btn btn-primary" type="submit" value="Next">
                  </div>
               </div>
               <input type  =  "hidden" name=  "ticket_type"  value  =  "<?php echo  $ticketType; ?>">
               <?php if(isset($_GET['segmentinfokey']) && $_GET['segmentinfokey']!="") { ?>
               <input type  =  "hidden"  name  =  "temptripSegmentId"  value  =  "<?php echo  $_GET['segmentinfokey']; ?>">
               <?php } ?>
            </form>
         </div>
      </div>
   </div>
</div>