<div class="page-content">
   <div class="table_title">
      <section class="cart_information p-0">
         <?php //pr($bookingDetail);?>
         <div class="container-fluid p-0">
            <div class="sale_bar">
               <div class="row align-items-center">
                  <div class="col-md-6">
                     <h5 class="m-0"> Holioday Booking Details (<?php echo $bookingDetail['booking_ref_number']; ?>)</h5>
                  </div>
                  <div class="col-md-6 text-md-end">
                     <a class="badge badge-wt"
                        href="<?php echo site_url('/holiday/confirmation/') . $ticketData = dev_encode(json_encode(array("BookingId" => $bookingDetail['id'], "BookingToken" => $bookingDetail['tts_search_token']))); ?>">Booking
                     Summary</a>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12 col-12 col-lg-12">
                  <div class="cart_info">
                     <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                           <button class="accordion-button" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapseOne" aria-expanded="true"
                              aria-controls="collapseOne">
                           <span class="acordian_heading">Cart Information : <?php echo $bookingDetail['booking_ref_number']; ?></span>
                           </button>
                           <div id="collapseOne" class="accordion-collapse collapse show"
                              aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                 <div class="row">
                                    <div class="row">
                                       <div class="col-md-3 col-xs-6 col-6">
                                          <div class="cart_info-field">
                                             <p class="cart_info-field--title">Booking Ref Number :<span
                                                class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['booking_ref_number']; ?></span></span>
                                             </p>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-xs-6 col-6">
                                          <div class="cart_info-field">
                                             <p class="cart_info-field--title">Amount :<span
                                                class="cart_info-field--detail"><span> &nbsp;₹&nbsp;<?php echo $bookingDetail['total_price']; ?></span></span>
                                             </p>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-xs-6 col-6">
                                          <div class="cart_info-field">
                                             <p class="cart_info-field--title">Booking Status :<span
                                                class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['booking_status']; ?></span></span>
                                             </p>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-xs-6 col-6">
                                          <div class="cart_info-field">
                                             <p class="cart_info-field--title">Payment Status :<span
                                                class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['payment_status']; ?></span></span>
                                             </p>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-xs-6 col-6">
                                          <div class="cart_info-field">
                                             <p class="cart_info-field--title">Channel Type :<span
                                                class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['booking_channel']; ?></span></span>
                                             </p>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-xs-6 col-6">
                                          <div class="cart_info-field">
                                             <p class="cart_info-field--title">CreatedOn :<span
                                                class="cart_info-field--detail"><span> &nbsp;<?php echo date_created_format($bookingDetail['created']); ?></span></span>
                                             </p>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-xs-6 col-6">
                                          <div class="cart_info-field">
                                             <a href="<?php echo site_url('/holiday/confirmation/') . $ticketData = dev_encode(json_encode(array("BookingId" => $bookingDetail['id'], "BookingToken" => $bookingDetail['tts_search_token'])));; ?>"
                                                class="">Booking Summary</a>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-xs-6 col-6">
                                          <div class="cart_info-field">
                                             <p class="cart_info-field--title">Booking User :<span
                                                class="cart_info-field--detail"><span> &nbsp;<a
                                                href="#"
                                                class=""><?php echo $bookingDetail['staff_name']; ?></a></span></span>
                                             </p>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="accordion-item">
                           <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapseThree" aria-expanded="false"
                              aria-controls="collapseThree">
                              <span class="acordian_heading"> Amendments</span>
                              <span class="ball__mainwrapper"><span
                                 class="ball__border info_length-green"><span
                                 class="numbering-section"><?php echo count($amendment_list) ?></span></span></span>
                              <div class="cssCircle addsign" data-bs-toggle="modal"
                                 data-bs-target="#holiday-raise-amendment">
                                 <span class="cssCircle-plusdesign">+ Raise Amendments</span>
                              </div>
                           </button>
                           <div id="collapseThree" class="accordion-collapse collapse show"
                              aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                              <?php if ($amendment_list) {
                                 foreach ($amendment_list as $amendment) {
                                     if ($amendment['staff_name']) {
                                         $border = "tts-amendment-partner-border";
                                         $remark = "text-primary";
                                     } else {
                                         $border = "tts-amendment-admin-border";
                                         $remark = "text-success";
                                     }
                                     ?>
                              <div class="accordion-body mb-1 <?php echo $border; ?>">
                                 <div class="row">
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">Generation Time :
                                          <span
                                             class="cart_info-field--detail"><?php echo date_created_format($amendment['created']); ?></span>
                                       </p>
                                    </div>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">Amendment Id : <span
                                          class="cart_info-field--detail"><?php echo $amendment['id']; ?></span>
                                       </p>
                                    </div>
                                    <?php if ($amendment['staff_name']) { ?>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">User : <span
                                          class="cart_info-field--detail"><?php echo $amendment['staff_name']; ?></span>
                                       </p>
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">Status : <span
                                          class="cart_info-field--detail"><?php echo ucfirst($amendment['amendment_status']); ?></span>
                                       </p>
                                    </div>
                                    <?php if ($amendment['remark_from_web_partner']) { ?>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title text-danger">Remark
                                          : <span
                                             class="cart_info-field--detail <?php echo $remark; ?>"><?php echo $amendment['remark_from_web_partner']; ?></span>
                                       </p>
                                    </div>
                                    <?php } ?>
                                    <?php if ($amendment['remark_from_super_admin']) { ?>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title text-danger">Remark
                                          : <span
                                             class="cart_info-field--detail <?php echo $remark; ?>"><?php echo $amendment['remark_from_super_admin']; ?></span>
                                       </p>
                                    </div>
                                    <?php } ?>
                                    <?php if ($amendment['amendment_type']) { ?>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">Type : <span
                                          class="cart_info-field--detail"><?php echo ucwords(str_replace('_', ' ', $amendment['amendment_type'])); ?></span>
                                       </p>
                                    </div>
                                    <?php } ?>
                                 </div>
                              </div>
                              <?php if ($amendment['admin_reply']) {
                                 $border_admin = "tts-amendment-admin-border";
                                 $remark_admin = "text-success";
                                 foreach ($amendment['admin_reply'] as $amendment_reply) {
                                     ?>
                              <div class="accordion-body mb-1 <?php echo $border_admin; ?>">
                                 <div class="row">
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">Generation Time :
                                          <span
                                             class="cart_info-field--detail"><?php echo date_created_format($amendment_reply['created']); ?></span>
                                       </p>
                                    </div>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">Amendment Id :
                                          <span
                                             class="cart_info-field--detail"><?php echo $amendment['id']; ?></span>
                                       </p>
                                    </div>
                                    <?php if ($amendment_reply['staff_name']) { ?>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">User : <span
                                          class="cart_info-field--detail"><?php echo $amendment_reply['staff_name']; ?></span>
                                       </p>
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">Status : <span
                                          class="cart_info-field--detail"><?php echo ucfirst($amendment_reply['amendment_status']); ?></span>
                                       </p>
                                    </div>
                                    <?php if ($amendment_reply['remark_from_web_partner']) { ?>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title text-danger">
                                          Remark
                                          : <span
                                             class="cart_info-field--detail <?php echo $remark_admin; ?>"><?php echo $amendment_reply['remark_from_web_partner']; ?></span>
                                       </p>
                                    </div>
                                    <?php } ?>
                                    <?php if ($amendment_reply['remark_from_super_admin']) { ?>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title text-danger">
                                          Remark
                                          : <span
                                             class="cart_info-field--detail <?php echo $remark_admin; ?>"><?php echo $amendment_reply['remark_from_super_admin']; ?></span>
                                       </p>
                                    </div>
                                    <?php } ?>
                                    <?php if ($amendment_reply['amendment_type']) { ?>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">Type : <span
                                          class="cart_info-field--detail"><?php echo ucwords(str_replace('_', ' ', $amendment_reply['amendment_type'])); ?></span>
                                       </p>
                                    </div>
                                    <?php } ?>
                                 </div>
                              </div>
                              <?php }
                                 } ?>
                              <?php }
                                 } ?>
                           </div>
                        </div>
                        <div class="accordion-item">
                           <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapseFour" aria-expanded="false"
                              aria-controls="collapseFour">
                           <span class="acordian_heading">Booking Details</span>
                           </button>
                           <div id="collapseFour" class="accordion-collapse collapse show"
                              aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                 <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12">
                                       <div class="tts-holiday-review-details">
                                          <div class="my-3">
                                             <ul class="d-flex align-items-center justify-content-between">
                                                <li>
                                                   <h6>Package</h6>
                                                   <h6><?php echo $bookingDetail['package_details']['Name'] ?> </h6>
                                                </li>
                                                <li>
                                                   <h6>Category</h6>
                                                   <h6><?php echo $bookingDetail['package_details']['HolidayCategory'] ?> </h6>
                                                </li>
                                                <li>
                                                   <h6>Travel Date</h6>
                                                   <h6><?php echo $bookingDetail['package_details']['TravelDate'] ?> </h6>
                                                </li>
                                                <li>
                                                   <h6>Duration</h6>
                                                   <h6><?php echo $night = $bookingDetail['package_details']['DaysNights']; ?></h6>
                                                </li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-12 travelimp__thanku--panelHeadwrap">
                                       <div class="table-responsive travelimp__thanku--responsivewrap">
                                          <h6>Passenger Details</h6>
                                          <table class="table table-bordered table-hover travelimp__thanku--tablewrap">
                                             <thead class="table-active">
                                                <tr>
                                                   <th>Sr.</th>
                                                   <th>Name</th>
                                                   <th>Pax Type</th>
                                                   <th>Pan</th>
                                                   <th>Passport</th>
                                                   <th>Age</th>
                                                   <th>Child Need Bed</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <?php if ($bookingDetail['travellers']) {
                                                   foreach ($bookingDetail['travellers'] as $key => $traveller) {
                                                       if ($traveller['lead_pax'] == 1) {
                                                           $bookingDetail['email'] = $traveller['email_id'];
                                                           $bookingDetail['mobile_no'] = $traveller['mobile_number'];
                                                       }
                                                       ?>
                                                <tr>
                                                   <td><?php echo $key + 1; ?></td>
                                                   <td>
                                                      <?php echo $traveller['title'] . ' ' . $traveller['first_name'] . ' ' . $traveller['last_name'] ?>
                                                   </td>
                                                   <td>
                                                      <?php echo $traveller['pax_type']; ?>
                                                   </td>
                                                   <td><?php echo $traveller['pan_number']; ?></td>
                                                   <td><?php echo $traveller['passport_number']; ?></td>
                                                   <td><?php if ($traveller['pax_type'] == "Child" || $traveller['pax_type'] == "Infant") echo $traveller['age']; ?></td>
                                                   <td><?php echo $traveller['child_need_bed']; ?></td>
                                                </tr>
                                                <?php }
                                                   } ?>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="accordion-item">
                           <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapseFive" aria-expanded="false"
                              aria-controls="collapseFive">
                           <span class="acordian_heading">Payment Process</span>
                           <span class="ball__mainwrapper"><span
                              class="ball__border info_length-green"><span
                              class="numbering-section"><?php echo count($bookingDetail['paymentInfo']); ?></span></span></span>
                           </button>
                           <div id="collapseFive" class="accordion-collapse collapse show"
                              aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="table-responsive">
                                          <table class="table table-bordered table-hover">
                                             <thead class="table-active">
                                                <tr>
                                                   <th>Ref. Number</th>
                                                   <th>Remark</th>
                                                   <th>Credit</th>
                                                   <th>Debit</th>
                                                   <th>Type</th>
                                                   <th>Created</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <?php
                                                   if (!empty($bookingDetail['paymentInfo']) && is_array($bookingDetail['paymentInfo'])) {
                                                       foreach ($bookingDetail['paymentInfo'] as $data) {
                                                           ?>
                                                <tr>
                                                   <td> <?php echo $data['acc_ref_number']; ?></td>
                                                   <td><?php echo $data['remark']; ?></td>
                                                   <td> ₹ <?php echo $data['credit']; ?></td>
                                                   <td> ₹ <?php echo $data['debit']; ?></td>
                                                   <td><?php echo ucfirst($data['action_type']); ?></td>
                                                   <td>
                                                      <?php echo date_created_format($data['created']); ?>
                                                   </td>
                                                </tr>
                                                <?php }
                                                   } else {
                                                       echo "<tr> <td colspan='6' class='text_center'><b>No Data Found</b></td></tr>";
                                                   } ?>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="accordion-item">
                           <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapseSeven" aria-expanded="false"
                              aria-controls="collapseSeven">
                           <span class="acordian_heading">Fare Breakup </span>
                           </button>
                           <div id="collapseSeven" class="accordion-collapse collapse show"
                              aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                 <div class="table-responsive">
                                    <?php $FareBreakUp = $bookingDetail['FareBreakUp'];
                                       if ($FareBreakUp) { ?>
                                    <table class="table table-bordered table-hover">
                                       <tr>
                                          <th scope="row"><?php echo $FareBreakUp['WebPMarkUp']['LabelText']; ?>
                                             :
                                          </th>
                                          <td>
                                             ₹ <?php echo $FareBreakUp['WebPMarkUp']['Value']; ?>
                                          </td>
                                       </tr>
                                       <tr>
                                          <th scope="row"><?php echo $FareBreakUp['WebPDiscount']['LabelText']; ?>
                                             :
                                          </th>
                                          <td>
                                             ₹ <?php echo $FareBreakUp['WebPDiscount']['Value']; ?>
                                          </td>
                                       </tr>
                                    </table>
                                 </div>
                                 <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                       <?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
                                       <tr>
                                          <th scope="row"><?php echo $fare['LabelText']; ?>
                                             :
                                          </th>
                                          <td>
                                             ₹ <?php echo $fare['Value']; ?>
                                          </td>
                                       </tr>
                                       <?php } ?>
                                       <tr>
                                          <th scope="row"><?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>
                                             :
                                          </th>
                                          <th scope="row">
                                             ₹ <?php echo $FareBreakUp['TotalAmount']['Value']; ?>
                                          </th>
                                       </tr>
                                    </table>
                                 </div>
                                 <div class="table-responsive">
                                    <?php if (isset($FareBreakUp['GSTDetails']) && $FareBreakUp['GSTDetails']) { ?>
                                    <table class="table table-bordered table-hover">
                                       <tr>
                                          <th>
                                             Service Charges
                                          </th>
                                          <th>
                                             Taxable Value
                                          </th>
                                          <th>
                                             CGST
                                             @ <?php echo $FareBreakUp['GSTDetails']['CGSTRate']; ?>
                                             %
                                          </th>
                                          <th>
                                             SGST
                                             @ <?php echo $FareBreakUp['GSTDetails']['SGSTRate']; ?>
                                             %
                                          </th>
                                          <th>
                                             IGST
                                             @<?php echo $FareBreakUp['GSTDetails']['IGSTRate']; ?>
                                             %
                                          </th>
                                          <th>
                                             Total
                                          </th>
                                       </tr>
                                       <tr>
                                          <th>
                                             Service Charges
                                          </th>
                                          <th><?php echo $FareBreakUp['GSTDetails']['TaxableAmount']; ?></th>
                                          <th><?php echo $FareBreakUp['GSTDetails']['CGSTAmount']; ?></th>
                                          <th> <?php echo $FareBreakUp['GSTDetails']['SGSTAmount']; ?></th>
                                          <th> <?php echo $FareBreakUp['GSTDetails']['IGSTAmount']; ?></th>
                                          <th> <?php echo $FareBreakUp['GSTDetails']['CGSTAmount'] + $FareBreakUp['GSTDetails']['SGSTAmount'] + $FareBreakUp['GSTDetails']['IGSTAmount']; ?></th>
                                       </tr>
                                    </table>
                                    <?php }
                                       } ?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                       
                        <div class="accordion-item">
                           <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapseSix" aria-expanded="false"
                              aria-controls="collapseSix">
                           <span class="acordian_heading">User Information </span>
                           </button>
                           <div id="collapseSix" class="accordion-collapse collapse show"
                              aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                 <div class="amend_details-passengers--list">
                                    <div class="row">
                                       <div class="col-md-4">
                                          <div class="cart_info-field">
                                             <p class="cart_info-field--title">Contact's Email:<span
                                                class="cart_info-field--detail">
                                                <span><?php echo $bookingDetail['email']; ?></span></span>
                                             </p>
                                          </div>
                                       </div>
                                       <div class="col-md-4">
                                          <div class="cart_info-field">
                                             <p class="cart_info-field--title">Pax contact:<span
                                                class="cart_info-field--detail"><span> <?php echo $bookingDetail['mobile_no']; ?></span></span>
                                             </p>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
   </div>
</div>
<div class="modal fade" id="holiday-raise-amendment" tabindex="-1" aria-labelledby="holiday-raise-amendmentLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="holiday-raise-amendmentLabel">AMENDMENTS</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('holiday/raise-amendment'); ?>" method="post"
            tts-form="true" name="hotel-raise-amendment">
            <div class="modal-body">
               <div class="mb-3">
                  <label class="col-form-label">Amendment Type</label>
                  <select class="form-select" name="amendment_type">
                     <option value="">Amendment Type</option>
                     <option value="cancellation">Cancellation</option>
                     <option value="full_refund">Full Refund</option>
                     <option value="cancellation_quotation">Cancellation Quotation</option>
                     <option value="correction">Correction</option>
                  </select>
                  <input type="hidden" name="booking_ref_number"
                     value="<?php echo $bookingDetail['booking_ref_number']; ?>">
               </div>
               <div class="mb-3">
                  <label class="col-form-label">Remark:</label>
                  <textarea class="form-control" name="remark" rows="3" cols="15"></textarea>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary">Raise</button>
            </div>
         </form>
      </div>
   </div>
</div>