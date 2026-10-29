<div class="content">
    <div class="page-content">
        <div class="page-content-area">
            <div class="card-header">
                <div class="table_title">
                    <div class="topbar">
                        <span> <?php 
                            echo $title ?> </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
               
                <form name="web-partner" tts-form='true'
                      action="<?php echo site_url('flight-ticket-upload/store-segement-info'); ?>"
                      method="POST" id="flight-upload-ticket">
                      <div class="view_head mb_10">
                        <div class="tts_row">
                            <div class="tts-col-12">
                                <span>Basic Information</span>
                            </div>
                        </div>
                    </div>
                      <div class="tts_row">
                
                  <div class="tts-col-3">
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
                            <div class="tts-col-3">
                            <div class="form-group">
                                <label>Cabin Class *</label>
                                <select class="form-control" name="cabin_class" placeholder="Cabin Class">
                                    <option value="Economy" selected>Economy</option>
                                    <option value="PremiumEconomy">Premium Economy</option>
                                    <option value="Business">Business</option>
                                    <option value="PremiumBusiness">Premium Business</option>
                                    <option value="First">First</option>
                                </select>
                            </div>
                        </div>

                        <div class="tts-col-3">
                            <div class="form-group">
                                <label>Refundable Type *</label>
                                <select class="form-control" name="is_refundable">
                                    <option value="0" selected>Non refundable</option>
                                    <option value="1">Refundable</option>
                                </select>
                            </div>
                        </div>
                    <div class="tts-col-12">
                    <div class="form-group">
                    <label>Note</label>
                    <textarea class="form-control" name="issuer_remark" placeholder="Note" rows="3"><?php if (isset($tripsegmentInfoData['issuer_remark'])) {
                                               echo trim($tripsegmentInfoData['issuer_remark']);
                                           } ?></textarea>
                    </div>
                    </div>
                    </div>
                      <div tts-call-put-trip-html="true">
                    <?php if(empty($tripsegmentInfo)) { echo  $tripView; }  
                    else {   $TripIndicator =  count($tripsegmentInfo); 
                    echo  view('Modules\FlightTicketImport\Views\trip-segment-exist-info', array('tripsegmentInfo'=>$tripsegmentInfo));
                    ?>
                      <?php } ?>  
                     </div>
                     <div class  =  "tts_row">   <div class="tts-col-12">
                    <div class="form-group">
                    <label>Airline Remark</label>
                    <textarea class="form-control" name="airline_remark" placeholder="Remark" rows="3"><?php if (isset($tripsegmentInfoData['airline_remark'])) {
                                               echo trim($tripsegmentInfoData['airline_remark']);
                                           } ?></textarea>
                    </div>
                    </div></div>
                    <div class="tts_row">
                        <div class="tts-col-6">
                        <button  type  = "button" class="badge badge-wt" flight-ticket-upload-add-trip ="true"
                                        tts-method-name="flight-ticket-upload/add-trip-details"><i
                                            class="tts-icon add "></i> Add More Trip
                                </button>
                        </div>
                        <div class="tts-col-6 text_right">
                            <input class="badge badge-md badge-primary" type="submit" value="Next">
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