<div class="content">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row">
               <div class="col-md-4">
                  <h5 class="m0"> <?php  echo $title ?> </h5>
               </div>
            </div>
         </div>
         <div class="card">
            <div class="card-body">
               <form name="web-partner" tts-form='true'
                  action="<?php echo site_url('flight-ticket-upload/store-segement-info'); ?>"
                  method="POST" id="flight-upload-ticket">
                  <div class="view_head ">
                     <div class="row">
                        <div class="col-md-12">
                           <span>Basic Information</span>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                       <!--  add by Abhay this line start -->
                       <?php $markup_used_for = get_active_whitelable_business();  ?>
                        <?php if ($markup_used_for) : ?>
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Business Type *</label>
                                    <select class="form-select" agent-customer="true" name="bussiness_type">
                                       <option value="">Select</option>
                                        <?php
                                        $LoopOutSite = array(); // Initialize
                                        foreach ($markup_used_for as $key => $data) {
                                            $LoopOutSite[] = $key; ?>
                                            <option value="<?php echo $key ?>" <?php if(isset($getVisaInfo['bussiness_type']) && $getVisaInfo['bussiness_type']== $key){ echo "selected"; } ?>><?php echo $key ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div> 
                        <?php endif ?>
                       
                        <?php if (isset($LoopOutSite)) : ?>
                            <div class="col-md-3" agent-customer-show<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? '' : '=""' ?>>
                                <div class="form-group form-mb-20">
                                    <label><?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'Agent' : 'Customer' ?> Name *</label>
                                    <?php
                                        $nameValue = isset($getVisaInfo['agent_info']) ? trim($getVisaInfo['agent_info']) : (isset($getVisaInfo['customer_info']) ? $getVisaInfo['customer_info'] : "");
                                        $idValue = isset($getVisaInfo['tts_agent_info_id']) ? trim($getVisaInfo['tts_agent_info_id']) : (isset($getVisaInfo['tts_customer_info_id']) ? $getVisaInfo['tts_customer_info_id'] : "");
                                        $ttsValue = isset($getVisaInfo['tts_agent_info']) ? trim($getVisaInfo['tts_agent_info']) : (isset($getVisaInfo['tts_customer_info']) ? $getVisaInfo['tts_customer_info'] : "");
                                    ?>
                                    <input type="text" class="form-control" name="<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>_info" value="<?= $nameValue ?>" tts-get-<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>-info="true" tts-error-msg="Please enter search type" placeholder="<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'Agent' : 'Customer' ?> Name" autocomplete="off">
                                    <input type="hidden" name="tts_<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>_info_id" tts-<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>-info-id="true" value="<?= $idValue ?>">
                                    <input type="hidden" name="tts_<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>_info" tts-<?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>-info="true" value="<?= $ttsValue ?>">
                                    <span class="success" <?= (isset($LoopOutSite[0]) && $LoopOutSite[0] == "B2B") ? 'agent' : 'customer' ?>info="true"><?= $ttsValue ?></span>
                                </div>
                            </div>
                        <?php endif  ?> 
                    <!--  add by Abhay this line End -->
                     <div class="col-md-3">
                        <div class="form-group form-mb-20">
                           <label>Issue Supplier *</label>
                           <select class="form-select" name="supplier">
                              <option value="" selected>Select</option>
                              <?php  if($flightSupplier) { foreach($flightSupplier as $supplier) { ?>
                              <option value="<?php echo $supplier['id']."#".$supplier['supplier_name'];?>" <?php if (isset($tripsegmentInfoData['issue_by_supplier']) && $tripsegmentInfoData['issue_by_supplier']==$supplier['id']."#".$supplier['supplier_name']) {
                                 echo  "selected";
                                 
                                 } ?>><?php echo  $supplier['supplier_name'];?></option>
                              <?php } } ?>
                           </select>
                        </div>
                     </div>
                     <!-- <div class="col-md-3">
                        <div class="form-group form-mb-20">
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
                        </div> -->
                     <div class="col-md-3">
                        <div class="form-group form-mb-20">
                           <label>Cabin Class *</label>
                           <select class="form-select" name="cabin_class" placeholder="Cabin Class">
                              <option value="Economy" selected>Economy</option>
                              <option value="PremiumEconomy">Premium Economy</option>
                              <option value="Business">Business</option>
                              <option value="PremiumBusiness">Premium Business</option>
                              <option value="First">First</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group form-mb-20">
                           <label>Refundable Type *</label>
                           <select class="form-select" name="is_refundable">
                              <option value="0" selected>Non refundable</option>
                              <option value="1">Refundable</option>
                           </select>
                        </div>
                     </div>
                     <!--  <div class="col-md-12">
                        <div class="form-group form-mb-20">
                        
                        <label>Note</label>
                        
                        <textarea class="form-control" name="issuer_remark" placeholder="Note" rows="3"><?php if (isset($tripsegmentInfoData['issuer_remark'])) {
                           echo trim($tripsegmentInfoData['issuer_remark']);
                           
                           } ?></textarea>
                        
                        </div>
                        
                        </div>
                        -->
                  </div>
                  <div tts-call-put-trip-html="true">
                     <?php if(empty($tripsegmentInfo)) { echo  $tripView; }  
                        else {   $TripIndicator =  count($tripsegmentInfo); 
                        
                        echo  view('Modules\Flight\Views\FlightTicketUpload\trip-segment-exist-info', array('tripsegmentInfo'=>$tripsegmentInfo));
                        
                        ?>
                     <?php } ?>  
                  </div>
                  <div class  =  "row">
                     <div class="col-md-12">
                        <div class="form-group form-mb-20">
                           <label>Airline Remark</label>
                           <textarea class="form-control" name="airline_remark" placeholder="Remark" rows="3"><?php if (isset($tripsegmentInfoData['airline_remark'])) {
                              echo trim($tripsegmentInfoData['airline_remark']);
                              
                              } ?></textarea>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <button  type  = "button" class="badge badge-wt" flight-ticket-upload-add-trip ="true"
                           tts-method-name="flight-ticket-upload/add-trip-details"><i
                           class="fa-solid fa-add "></i> Add More Trip
                        </button>
                     </div>
                     <div class="col-md-6 text-md-end">
                        <button class="btn btn-primary" type="submit">Next</button>
                     </div>
                  </div>
                  <input type  =  "hidden" name=  "ticket_type"  value  =  "<?php echo  $ticketType; ?>">
                  <?php if(isset($_GET['segmentinfokey']) && $_GET['segmentinfokey']!="") { ?>
                  <input type  =  "hidden"  name  =  "temptripSegmentId"  value  =  "<?php echo  $_GET['segmentinfokey']; ?>">
                  <?php } ?>
               </form>
               <input type  =  "hidden"  flight-ticket-upload-trip-indicator-couter =  "true"  value  =  "<?php echo  $TripIndicator; ?>">
            </div>
         </div>
      </div>
   </div>
</div>