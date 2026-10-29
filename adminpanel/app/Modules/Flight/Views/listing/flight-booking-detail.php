<div class="page-content">
   <div class="table_title">
      <section class="cart_information p-0">
         <div class="container-fluid p-0">
               <div class="sale_bar">
                  <div class="row align-items-center">
                     <div class="col-md-4">
                        <h5 class="m-0"> Flight Booking Details (<?php echo $bookingDetail['booking_ref_number']; ?>)</h5>
                     </div>
                     <div class="col-md-8 text-md-end">
                        <?php if ($bookingDetail['booking_channel'] != "UploadTicket" &&  $bookingDetail['booking_status'] != "ImportTicket") { ?>
                        <a class="badge badge-wt" target="<?php echo target; ?>" href="<?php echo root_url . 'api/airservice/rest/get-logs/' . $bookingDetail['booking_ref_number']; ?>">
                           <i class="fa-solid fa-download"></i> Download Logs
                        </a>
                        <?php }  ?>
                        <a class="badge badge-wt" href="<?php echo site_url('/flight/confirmation/') . $ticketData = dev_encode(json_encode(array($bookingDetail['id']))); ?>">
                           <i class="fa-solid fa-book"></i> Booking Summary
                        </a>
                        <?php  $edit_status = edit_permission_status(whitelabel['is_direct_website'],$bookingDetail['inventory_source'],$bookingDetail['api_supplier']);  
                          if($edit_status['permission']){
                        ?>
                        <?php if (($bookingDetail['webpartner_assign_user'] != NULL && $bookingDetail['webpartner_assign_user'] != '' && $bookingDetail['webpartner_assign_user'] == admin_cookie_data()['admin_user_details']['id'] && $bookingDetail['webpartner_assign_user'] == admin_cookie_data()['admin_user_details']['id']) || admin_cookie_data()['admin_user_details']['primary_user'] == 1) { ?>
                        <a href="<?php echo site_url('/flight/get-update-flight-ticket-info/') . $ticketData = $bookingDetail['booking_ref_number']; ?>" target="<?php echo target ?>" class="badge badge-wt"><i class="fa-solid fa-edit"></i> Edit
                        Booking</a>
                        <?php } ?>
                        <?php } ?>
                     </div>
                  </div>
               </div>
               <?php echo view('Modules\Flight\Views\listing/flight-booking-detail-template',['bookingDetail'=>$bookingDetail,'edit_status'=>$edit_status]) ?>
               <form name="web-partner" tts-form='true' action="<?php echo site_url('flight/flight-update-ticket-info'); ?>" method="POST" id="flight-update-ticket">
               <?php if (isset($flightSupplier)) : ?>
                  <div class="cart_info mt-3 p-3">
                      <div class="row align-items-center">
                        <div class="col-md-2">
                           <div class="form-group">
                              <label>Issue Supplier *</label>
                              <select class="form-select" name="supplier">
                                 <option value="" selected>Select</option>
                                 <?php if ($flightSupplier) {
                                    foreach ($flightSupplier as $supplier) { ?>
                                 <option value="<?php echo $supplier['supplier_name']; ?>" <?php if (isset($bookingDetail['issue_supplier']) && strtolower($bookingDetail['issue_supplier']) == strtolower($supplier['supplier_name'])) {
                                    echo  "selected";
                                    } ?>><?php echo  $supplier['supplier_name']; ?></option>
                                 <?php }
                                    } ?>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              <label>Booking Status *</label>
                              <select class="form-select" name="booking_status" placeholder="Booking Status">
                                 <option value="Confirmed" <?php echo  $bookingDetail['booking_status'] == "Confirmed" ? "selected" : ""; ?>>Confirmed</option>
                                 <option value="Processing" <?php echo  $bookingDetail['booking_status'] == "Processing" ? "selected" : ""; ?>>Processing</option>
                                 <option value="Hold" <?php echo  $bookingDetail['booking_status'] == "Hold" ? "selected" : ""; ?>>Hold</option>
                                 <option value="Failed" <?php echo  $bookingDetail['booking_status'] == "Failed" ? "selected" : ""; ?>>Failed</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              <label>Payment Status *</label>
                              <select class="form-select" name="payment_status" placeholder="Payment Status">
                                 <option value="Successful" <?php echo  $bookingDetail['payment_status'] == "Successful" ? "selected" : ""; ?>>Successful</option>
                                 <?php if ($bookingDetail['payment_status'] != "Successful") { ?>
                                 <option value="Failed" <?php echo  $bookingDetail['payment_status'] == "Failed" ? "selected" : ""; ?>>Failed</option>
                                 <option value="Processing" <?php echo  $bookingDetail['payment_status'] == "Processing" ? "selected" : ""; ?>>Processing</option>
                                 <?php } ?>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                           <div class="form-group form-check">
                              <label class="form-check-label">
                                 <input class="form-check-input" type="checkbox" value="yes" name="refundbookingamount">Refund Booking Amount
                              </label>
                           </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                           <div class="form-group form-check">
                              <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" value="yes" name="deductbookingamount" <?php if ($bookingDetail['payment_status'] != "Successful") {
                                 echo  "checked";
                                 } ?>>Deduct Booking Amount
                              </label>
                           </div>
                        </div>
                        <?php echo  view('Modules\Flight\Views\listing\update-ticket-passenger-details', array('bookingInfo' => $bookingDetail)); ?>
                        <input type="hidden" name="flight_booking_id" value="<?php echo dev_encode($bookingDetail['id']);  ?>">
                        <input type="hidden" name="booking_ref_number" value="<?php echo dev_encode($bookingDetail['booking_ref_number']);  ?>">
                        <div class="col-md-12">
                           <div class="form-group">
                              <label>Note</label>
                              <?php
                                 $remark = '';
                                 if(isset($bookingDetail['paymentInfo'][0]['remark'])){
                                    $remark = $bookingDetail['paymentInfo'][0]['remark'];
                                 }
                                 ?>
                              <textarea class="form-control" name="remark" placeholder="Note" rows="3"><?=$remark?></textarea>
                           </div>
                        </div>
                     </div>
                     <?php if($edit_status['permission']): ?>
                     <div class="row">
                        <div class="col-md-12 text-md-right">
                           <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                     </div>
                     <?php endif; ?>
                  </div>
                 
               <?php endif; ?>
            </form>
         </div>
      </section>
   </div>
</div>
<div class="modal fade" id="flight-raise-amendment" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="flight-raise-amendmentLabel">AMENDMENTS</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('flight/raise-amendment'); ?>" method="post" tts-form="true" name="flight-raise-amendment">
            <div class="modal-body">
               <div class="mb-3">
                  <label class="col-form-label">Amendment Type</label>
                  <input type="text" name="booking_ref_number" value="<?php echo $bookingDetail['booking_ref_number']; ?>" class="form-control" readonly>
               </div>
               <div class="mb-3">
                  <label class="col-form-label">Amendment Type</label>
                  <select class="form-select" name="amendment_type" data-validation="required" data-validation-error-msg-required="Please select Amendment Type">
                     <option value="">Amendment Type</option>
                     <option value="cancellation">Cancellation</option>
                     <option value="full_refund">Full Refund</option>
                     <option value="reissue">Re-Issue</option>
                     <option value="correction">Correction</option>
                     <option value="cancellation_quotation">Cancellation Quotation</option>
                  </select>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary">Raise</button>
            </div>
         </form>
      </div>
   </div>
</div>