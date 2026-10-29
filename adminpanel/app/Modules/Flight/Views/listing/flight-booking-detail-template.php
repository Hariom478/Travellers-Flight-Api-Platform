<div class="row">
   <div class="col-md-12 col-12 col-lg-12">
      <div class="cart_info">
         <div class="accordion" id="accordionPanelsStayOpenExample">
            <div class="accordion-item">
               <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                  aria-expanded="true" aria-controls="collapseOne">
                  <span class="acordian_heading">Cart Information </span>
               </button>
               <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                  data-bs-parent="#accordionExample">
                  <div class="accordion-body">

                     <div class="row">
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title"> Ref Number :<span
                                    class="cart_info-field--detail"><span> &nbsp;
                                       <?php echo $bookingDetail['booking_ref_number']; ?>
                                    </span></span>
                              </p>
                           </div>
                        </div>

                        <?php if ($bookingDetail['pnr'] != "") { ?>
                           <div class="col-md-3 col-xs-6 col-6">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Airline PNR :<span
                                       class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $bookingDetail['pnr']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                        <?php } ?>
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Fare Rule :<a href="javascript:void(0);"
                                    data-bs-toggle="modal" data-bs-target="#FareRuleDetailModel"><span
                                       class="cart_info-field--detail"><span
                                          class="<?php echo $bookingDetail['is_refundable'] == 1 ? "tts-text-success" : "tts-text-danger"; ?>"
                                          style="color:blue;"> &nbsp;
                                          <?php echo $bookingDetail['is_refundable'] == 1 ? "Refundable" : "Non Refundable"; ?>
                                       </span></span>
                                 </a></p>
                           </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Invoice Amount :<span
                                    class="cart_info-field--detail"><span> &nbsp;₹&nbsp;
                                       <?php echo $bookingDetail['total_price']; ?>
                                    </span></span>
                              </p>
                           </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Booking Status :<span
                                    class="cart_info-field--detail"><span> &nbsp;
                                       <?php echo $bookingDetail['booking_status']; ?>
                                    </span></span>
                              </p>
                           </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Payment Status :<span
                                    class="cart_info-field--detail"><span> &nbsp;
                                       <?php echo $bookingDetail['payment_status']; ?>
                                    </span></span>
                              </p>
                           </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Channel Type :<span
                                    class="cart_info-field--detail"><span> &nbsp;
                                       <?php echo $bookingDetail['booking_channel']; ?>
                                    </span></span>
                              </p>
                           </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Created On :<span class="cart_info-field--detail"><span>
                                       &nbsp;
                                       <?php echo date_created_format($bookingDetail['created']); ?>
                                    </span></span>
                              </p>
                           </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Company User :<span
                                    class="cart_info-field--detail"><span> &nbsp;
                                       <?php echo $bookingDetail['staff_name']; ?>
                                    </span></span>
                              </p>
                           </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Fare Type :<span class="cart_info-field--detail"><span>
                                       &nbsp;
                                       <?php echo $bookingDetail['fare_type']; ?>
                                    </span></span>
                              </p>
                           </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Vendor :<span class="cart_info-field--detail"><span>
                                       &nbsp;
                                       <?php echo $edit_status['supplier']; ?>
                                    </span></span>
                              </p>
                           </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Offline Issuing Supplier :<span
                                    class="cart_info-field--detail"><span> &nbsp;
                                       <?php echo $bookingDetail['issue_supplier']; ?>
                                    </span></span>
                              </p>
                           </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Booking Source :<span
                                    class="cart_info-field--detail"><span> &nbsp;
                                       <?php echo service_booking_source($bookingDetail['booking_source']); ?>
                                    </span></span>
                              </p>
                           </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                           <div class="cart_info-field">
                              <a href="<?php echo site_url('/flight/confirmation/') . $ticketData = dev_encode(json_encode(array($bookingDetail['id']))); ?>"
                                 class="">Booking Summary</a>
                           </div>
                        </div>
                        <?php if ($bookingDetail['last_ticket_date'] != "") { ?>
                           <div class="col-md-3 col-xs-6 col-6">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Last Ticket Date :<span
                                       class="cart_info-field--detail"><span> &nbsp;<a href="#" class="">
                                             <?php echo $bookingDetail['last_ticket_date'] != "" ? display_custom_date_format($bookingDetail['last_ticket_date'], true) : ""; ?>
                                          </a></span></span>
                                 </p>
                              </div>
                           </div>
                        <?php } ?>
                     </div>

                  </div>
               </div>
            </div>
            <?php if (isset($bookingDetail['AgentInfo']) && !empty($bookingDetail['AgentInfo'])) {
               $AgentInfo = $bookingDetail['AgentInfo'];
               ?>
               <div class="accordion-item">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                     data-bs-target="#collapseAgentInfo" aria-expanded="true" aria-controls="collapseAgentInfo">
                     <span class="acordian_heading">Company Info </span>
                  </button>
                  <div id="collapseAgentInfo" class="accordion-collapse collapse show"
                     aria-labelledby="collapseWebPAgentInfo" data-bs-parent="#accordionExample">
                     <div class="accordion-body">
                        <div class="row">
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Company name :<span
                                       class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $AgentInfo['company_name']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Company Id
                                    :<span class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $AgentInfo['company_id']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                           <!-- <div class="col-md-3">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Agent Name
                                 :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $AgentInfo['first_name'] . " " . $AgentInfo['last_name']; ?></span></span>
                              </p>
                           </div>
                        </div> -->
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Email :<span class="cart_info-field--detail"><span>
                                          &nbsp;
                                          <?php echo $AgentInfo['login_email']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Mobile Number :<span
                                       class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $AgentInfo['mobile_no']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            <?php } ?>
            <?php if (isset($bookingDetail['CustomerInfo']) && !empty($bookingDetail['CustomerInfo'])) {
               $CustomerInfo = $bookingDetail['CustomerInfo']; ?>
               <div class="accordion-item">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                     data-bs-target="#collapseCustomerInfo" aria-expanded="true" aria-controls="collapseCustomerInfo">
                     <span class="acordian_heading">Customer Info </span>
                  </button>
                  <div id="collapseCustomerInfo" class="accordion-collapse collapse show"
                     aria-labelledby="collapseWebPCustomerInfo" data-bs-parent="#accordionExample">
                     <div class="accordion-body">
                        <div class="row">
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title"> Customer ID :<span
                                       class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $CustomerInfo['customer_id']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Customer Name
                                    :<span class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $CustomerInfo['first_name'] . " " . $CustomerInfo['last_name']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Email :<span class="cart_info-field--detail"><span>
                                          &nbsp;
                                          <?php echo $CustomerInfo['email_id']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Mobile Number :<span
                                       class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $CustomerInfo['mobile_no']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            <?php } ?>
            <?php if (isset($bookingDetail['DistributorInfo']) && !empty($bookingDetail['DistributorInfo'])) {
               $DistributorInfo = $bookingDetail['DistributorInfo'];
               ?>
               <div class="accordion-item">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                     data-bs-target="#collapseAgentInfo" aria-expanded="true" aria-controls="collapseAgentInfo">
                     <span class="acordian_heading">Distributor Info </span>
                  </button>
                  <div id="collapseAgentInfo" class="accordion-collapse collapse show"
                     aria-labelledby="collapseWebPAgentInfo" data-bs-parent="#accordionExample">
                     <div class="accordion-body">
                        <div class="row">
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Company name :<span
                                       class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $DistributorInfo['company_name']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Company Id
                                    :<span class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $DistributorInfo['company_id']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                           <!-- <div class="col-md-3">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Agent Name
                                 :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $DistributorInfo['first_name'] . " " . $DistributorInfo['last_name']; ?></span></span>
                              </p>
                           </div>
                        </div> -->
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Email :<span class="cart_info-field--detail"><span>
                                          &nbsp;
                                          <?php echo $DistributorInfo['login_email']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Mobile Number :<span
                                       class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $DistributorInfo['mobile_no']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            <?php } ?>
            <?php if (isset($bookingDetail['SupplierInfo']) && !empty($bookingDetail['SupplierInfo'])) {
               $SupplierInfo = $bookingDetail['SupplierInfo'];
               ?>
               <div class="accordion-item">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                     data-bs-target="#collapseAgentInfo" aria-expanded="true" aria-controls="collapseAgentInfo">
                     <span class="acordian_heading">Supplier Info </span>
                  </button>
                  <div id="collapseAgentInfo" class="accordion-collapse collapse show"
                     aria-labelledby="collapseWebPAgentInfo" data-bs-parent="#accordionExample">
                     <div class="accordion-body">
                        <div class="row">
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Company name :<span
                                       class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $SupplierInfo['company_name']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Company Id
                                    :<span class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $SupplierInfo['company_id']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                           <!-- <div class="col-md-3">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Agent Name
                                 :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $SupplierInfo['first_name'] . " " . $SupplierInfo['last_name']; ?></span></span>
                              </p>
                           </div>
                        </div> -->
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Email :<span class="cart_info-field--detail"><span>
                                          &nbsp;
                                          <?php echo $SupplierInfo['login_email']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="cart_info-field">
                                 <p class="cart_info-field--title">Mobile Number :<span
                                       class="cart_info-field--detail"><span> &nbsp;
                                          <?php echo $SupplierInfo['mobile_no']; ?>
                                       </span></span>
                                 </p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            <?php } ?>
            <?php if (isset($amendmentList)): ?>
               <div class="accordion-item">
                  <div class="cart_acordian">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <span class="acordian_heading">Cart Amendments</span>
                        <span class="ball__mainwrapper"><span class="ball__border info_length-green"><span
                                 class="numbering-section">
                                 <?php echo count($amendmentList); ?>
                              </span></span></span>
                        <div class="cssCircle addsign">
                           <?php if ($bookingDetail['booking_status'] != "Cancelled" && $bookingDetail['booking_status'] != "Processing" && 0) { ?>
                              <span class="cssCircle-plusdesign"><a href="javascript:void(0);" data-bs-toggle="modal"
                                    data-bs-target="#flight-raise-amendment">+ Raise Amendments</a></span>
                           </div>
                        <?php } ?>
                     </button>
                  </div>
                  <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree"
                     data-bs-parent="#accordionExample">
                     <div class="accordion-body">
                        <?php if ($amendmentList) {
                           foreach ($amendmentList as $amendment) {
                              if ($amendment['staff_name']) {
                                 $border = "tts-amendment-partner-border";
                                 $remark = "txt-black";
                              } else {
                                 $border = "tts-amendment-admin-border";
                                 $remark = "txt-black";
                              }
                              ?>
                              <div class="cart-details-borderline <?php echo $border; ?>">
                                 <div class="row amendment_box">
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">Generation Time :
                                          <span class="cart_info-field--detail">
                                             <?php echo date_created_format($amendment['created']); ?>
                                          </span>
                                       </p>
                                    </div>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">Amendment Id : <span class="cart_info-field--detail">
                                             <?php echo $amendment['id']; ?>
                                          </span>
                                       </p>
                                    </div>
                                    <?php if ($amendment['staff_name']) { ?>
                                       <div class="col-md-3">
                                          <p class="cart_info-field--title">User : <span class="cart_info-field--detail">
                                                <?php echo $amendment['staff_name']; ?>
                                             </span>
                                          </p>
                                       </div>
                                    <?php } ?>
                                    <div class="col-md-3">
                                       <p class="cart_info-field--title">Status : <span class="cart_info-field--detail">
                                             <?php echo ucfirst($amendment['amendment_status']); ?>
                                          </span>
                                       </p>
                                    </div>
                                    <?php if ($amendment['remark_from_web_partner']) { ?>
                                       <div class="col-md-3">
                                          <p class="cart_info-field--title text-danger">Remark
                                             : <span class="cart_info-field--detail <?php echo $remark; ?>">
                                                <?php echo $amendment['remark_from_web_partner']; ?>
                                             </span>
                                          </p>
                                       </div>
                                    <?php } ?>
                                    <?php if ($amendment['amendment_type']) { ?>
                                       <div class="col-md-3">
                                          <p class="cart_info-field--title">Type : <span class="cart_info-field--detail">
                                                <?php echo ucwords(str_replace('_', ' ', $amendment['amendment_type'])); ?>
                                             </span>
                                          </p>
                                       </div>
                                    <?php } ?>
                                    <?php if ($amendment['id']) { ?>
                                       <div class="col-md-3">
                                          <p class="cart_info-field--title"><a
                                                href="<?php echo site_url('flight/amendment-details/') . dev_encode($amendment['id']); ?>">View
                                                Detail</a>
                                          </p>
                                       </div>
                                    <?php } ?>
                                    <?php if ($amendment['remark_from_super_admin']) { ?>
                                       <div class="tts-col-12 amendment_reply">
                                          <p class="cart_info-field--title text-danger">Reply Remark
                                             : <span class="cart_info-field--detail <?php echo $remark; ?>">
                                                <?php echo $amendment['remark_from_super_admin']; ?>
                                             </span>
                                          </p>
                                       </div>
                                    <?php } ?>
                                 </div>
                              </div>
                              <?php if (isset($amendment['admin_reply'])) {
                                 $border_admin = "tts-amendment-admin-border";
                                 $remark_admin = "text-success";
                                 foreach ($amendment['admin_reply'] as $amendment_reply) {
                                    ?>
                                    <div class="accordion-body mb-1 cart-details-borderline <?php echo $border_admin; ?>">
                                       <div class="row">
                                          <div class="col-md-3">
                                             <p class="cart_info-field--title">Generation Time :
                                                <span class="cart_info-field--detail">
                                                   <?php echo date_created_format($amendment_reply['created']); ?>
                                                </span>
                                             </p>
                                          </div>
                                          <div class="col-md-3">
                                             <p class="cart_info-field--title">Amendment Id :
                                                <span class="cart_info-field--detail">
                                                   <?php echo $amendment['id']; ?>
                                                </span>
                                             </p>
                                          </div>
                                          <?php if ($amendment_reply['staff_name']) { ?>
                                             <div class="col-md-3">
                                                <p class="cart_info-field--title">User : <span class="cart_info-field--detail">
                                                      <?php echo $amendment_reply['staff_name']; ?>
                                                   </span>
                                                </p>
                                             </div>
                                          <?php } ?>
                                          <div class="col-md-3">
                                             <p class="cart_info-field--title">Status : <span class="cart_info-field--detail">
                                                   <?php echo ucfirst($amendment_reply['amendment_status']); ?>
                                                </span>
                                             </p>
                                          </div>
                                          <?php if ($amendment_reply['remark_from_web_partner']) { ?>
                                             <div class="col-md-3">
                                                <p class="cart_info-field--title text-danger">
                                                   Remark
                                                   : <span class="cart_info-field--detail <?php echo $remark_admin; ?>">
                                                      <?php echo $amendment_reply['remark_from_web_partner']; ?>
                                                   </span>
                                                </p>
                                             </div>
                                          <?php } ?>
                                          <?php if ($amendment_reply['remark_from_super_admin']) { ?>
                                             <div class="col-md-3">
                                                <p class="cart_info-field--title text-danger">
                                                   Remark
                                                   : <span class="cart_info-field--detail <?php echo $remark_admin; ?>">
                                                      <?php echo $amendment_reply['remark_from_super_admin']; ?>
                                                   </span>
                                                </p>
                                             </div>
                                          <?php } ?>
                                          <?php if ($amendment_reply['amendment_type']) { ?>
                                             <div class="col-md-3">
                                                <p class="cart_info-field--title">Type : <span class="cart_info-field--detail">
                                                      <?php echo ucwords(str_replace('_', ' ', $amendment_reply['amendment_type'])); ?>
                                                   </span>
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
               </div>
            <?php endif; ?>
            <div class="accordion-item">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                  data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                  <span class="acordian_heading">Booking Details</span>
               </button>
               <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour"
                  data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                     <?php if (isset($bookingDetail['segments'])) {
                        $tripInfo = json_decode($bookingDetail['segments'], true);
                        foreach ($tripInfo as $key => $trips) {
                           if ($trips) {
                              foreach ($trips as $segmentIndicatorkey => $segment) { ?>
                                 <div class="row segment_body bookingDetails_body m-0 mb-3">
                                    <div class="col-md-2">
                                       <div class="segment_body-airlogo">
                                          <img
                                             src="<?php echo root_url . 'uploads/airline-images/' . $segment['Airline']['AirlineCode'] . '.png' ?>"
                                             width="40px">
                                          <span
                                             class="airline-logo <?php echo $bookingDetail['airlineLogoClass']; ?> size-28 x<?php echo $segment['Airline']['AirlineCode']; ?>"></span>
                                          <p class="mb-0">
                                             <?php echo $segment['Airline']['AirlineName']; ?>
                                             <span class="airline-code">
                                                <?php echo $segment['Airline']['AirlineCode']; ?> -
                                                <?php echo $segment['Airline']['FlightNumber']; ?>
                                             </span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-4 segment_body-flight-info text-center">
                                       <p class="mb-0">
                                          <?php echo $segment['Origin']['CityName']; ?>
                                          <span class="air_sourcr-none">
                                             <?php echo $segment['Origin']['CountryName']; ?> (
                                             <?php echo $segment['Origin']['AirportName']; ?>) -
                                             <?php echo $segment['Origin']['CityCode']; ?>
                                          </span>
                                       </p>
                                       <p class="mb-0">
                                          <?php echo get_flight_date($segment['Origin']['DepartTime']); ?>,
                                          <?php echo get_flight_time($segment['Origin']['DepartTime']); ?>
                                       </p>
                                    </div>
                                    <div class="col-md-2 segment_body-flight-stop text-center">
                                       <span class="via-city-codes">Non-Stop</span>
                                       <div class="arrow_right-sm"></div>
                                       <span class="travelimp__segmentwrap--airportlist">
                                          <?php echo get_convertToHoursMinsfromMinDuration($segment['Duration']); ?>
                                       </span>
                                    </div>
                                    <div class="col-md-4 segment_body-flight-info text-center">
                                       <p class="mb-0">
                                          <?php echo $segment['Destination']['CityName']; ?>
                                          <span class="air_sourcr-none">
                                             <?php echo $segment['Destination']['CountryName']; ?> (
                                             <?php echo $segment['Destination']['AirportName']; ?>) -
                                             <?php echo $segment['Destination']['CityCode']; ?>
                                          </span>
                                       </p>
                                       <p class="mb-0">
                                          <?php echo get_flight_date($segment['Destination']['ArrivalTime']); ?>,
                                          <?php echo get_flight_time($segment['Destination']['ArrivalTime']); ?>
                                       </p>
                                       <?php if (isset($segment['CabinClass'])) { ?>
                                          <p class="mb-0">Cabin Class -
                                             <?php echo $segment['CabinClass']; ?>
                                          <p class="mb-0">Fare Class/RVD -
                                             <?php echo $segment['Airline']['FareClass']; ?>
                                          </p>
                                       <?php } ?>
                                    </div>
                                 </div>
                              <?php }
                           }
                        }
                     } ?>
                     <div class="amend_details-passengers--list">
                        <?php if (isset($bookingDetail['travelersInfo'])) {
                           $travelersInfo = json_decode($bookingDetail['travelersInfo'], true);
                           foreach ($travelersInfo as $paxkey => $traveler) {
                              if ($bookingDetail['booking_source'] == "Wl_b2b") {
                                 $fare = json_decode($traveler['agentfare'], true);
                              } else {
                                 $fare = json_decode($traveler['customerfare'], true);
                              }
                              $mealInfo = json_decode($traveler['meal'], true);
                              $baggageInfo = json_decode($traveler['baggage'], true);
                              $paxTotalprice = 0;
                              ?>
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="amend_passenger_details">
                                       <span>Last Name/First Name Title</span>
                                       <div class="person-name d-flex align-items-center justify-content-between">
                                          <p class="bold">
                                             <?php echo $paxkey + 1; ?>.
                                             <?php echo $traveler['last_name']; ?>/
                                             <?php echo $traveler['first_name']; ?>
                                             <?php echo $traveler['title']; ?>. (
                                             <?php echo $traveler['pax_type']; ?>)
                                          </p>
                                       </div>
                                       <div class="row">
                                          <?php if ($traveler['date_of_birth'] != NULL) { ?>
                                             <div class="col-md-6">
                                                <span class="sm_font padd-left-amendment">DOB :
                                                   <span class="bold">
                                                      <?php echo $traveler['date_of_birth'] != "" ? display_custom_date_format($traveler['date_of_birth'], false) : "-"; ?>
                                                   </span></span>
                                             </div>
                                          <?php } ?>
                                          <?php if ($traveler['pan_number'] != NULL) { ?>
                                             <div class="col-md-6">
                                                <span class="sm_font padd-left-amendment">Pan Number :
                                                   <span class="bold">
                                                      <?php echo $traveler['pan_number']; ?>
                                                   </span></span>
                                             </div>
                                          <?php } ?>
                                          <?php if ($traveler['passport_number'] != NULL) { ?>
                                             <div class="col-md-6">
                                                <span class="sm_font padd-left-amendment">Passport Number :
                                                   <span class="bold">
                                                      <?php echo $traveler['passport_number']; ?>
                                                   </span></span>
                                             </div>
                                          <?php } ?>
                                          <?php if ($traveler['passport_expiry'] != NULL) { ?>
                                             <div class="col-md-6">
                                                <span class="sm_font padd-left-amendment">Passport Expiry :
                                                   <span class="bold">
                                                      <?php echo $traveler['passport_expiry'] != "" ? display_custom_date_format($traveler['passport_expiry'], false) : "-"; ?>
                                                   </span></span>
                                             </div>
                                          <?php } ?>
                                       </div>
                                    </div>
                                    <?php if ($traveler['baggage'] != null || $traveler['meal'] != null) { ?>
                                       <div class="amend_passenger_details">
                                          <div class="row">
                                             <?php if ($traveler['baggage'] != null && $baggageInfo) { ?>
                                                <div class="col-sm-12 col-xs-6 col-6 padd-left-amendment">
                                                   <p class="m-0">Baggage</p>
                                                   <?php if ($baggageInfo) {
                                                      foreach ($baggageInfo as $baggage) {
                                                         $AirlineDescription = isset($baggage['AirlineDescription']) ? $baggage['AirlineDescription'] : '';
                                                         $baggageData = "" . $baggage['Origin'] . "-" . $baggage['Destination'] . ": - " . $AirlineDescription . ""; ?>
                                                         <p class="price-width-left text-right"> <span data-bs-toggle="tooltip"
                                                               data-bs-placement="top" title="<?php echo $baggageData; ?>">
                                                               <?php echo limitTextChars($baggageData, 60, true, true); ?>
                                                            </span></p>
                                                      <?php }
                                                   } else { ?>
                                                      <p class="price-width-left text-right"> NA</p>
                                                   <?php } ?>
                                                </div>
                                             <?php } ?>
                                             <?php if ($traveler['meal'] != null && $mealInfo) { ?>
                                                <div class="col-sm-12 col-xs-6 col-6 padd-left-amendment">
                                                   <p class="m-0">Meal</p>
                                                   <?php if ($mealInfo) {
                                                      foreach ($mealInfo as $meal) {
                                                         $mealData = "" . $meal['Origin'] . "-" . $meal['Destination'] . ": - " . $meal['AirlineDescription'] . "(" . $meal['Code'] . ")" . " ( QTY : " . $meal['Quantity'] . " )"; ?>
                                                         <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="<?php echo $mealData; ?>">
                                                            <?php echo limitTextChars($mealData, 60, true, true); ?>
                                                         </span></p>
                                                      <?php }
                                                   } else { ?>
                                                      <p class="price-width-left text-right"> NA</p>
                                                   <?php } ?>
                                                </div>
                                             <?php } ?>
                                          </div>
                                       </div>
                                    <?php } ?>
                                 </div>
                                 <div class="col-md-8 passenger_faredetail">
                                    <div class="row">
                                       <div class="col-md-2 amendment_leftpad">
                                          <p class="mb-0">Base Fare</p>
                                          <p class="price-width-left">
                                             ₹
                                             <?php echo $fare['BaseFare'];
                                             $paxTotalprice = $paxTotalprice + $fare['BaseFare']; ?>
                                          </p>
                                       </div>
                                       <div class="col-md-2 amendment_leftpad">
                                          <p class="mb-0">Taxes</p>
                                          <p class="price-width-left">
                                             ₹
                                             <?php echo $fare['Tax'];
                                             $paxTotalprice = $paxTotalprice + $fare['Tax']; ?>
                                          </p>
                                       </div>
                                       <div class="col-md-2 amendment_leftpad">
                                          <p class="mb-0">YQ Tax</p>
                                          <p class="price-width-left">
                                             ₹
                                             <?php echo $fare['YQTax']; ?>
                                          </p>
                                       </div>
                                       <div class="col-md-4 padd-left-amendment col-6">
                                          <p class="mb-0">YR Tax</p>
                                          <p>₹ 0</p>
                                       </div>
                                       <?php
                                       $OtherCharges = 0;

                                       $Discount = 0;

                                       $TDS = 0;

                                       $GSTAmount = 0;

                                       $AgentCommission = 0;

                                       if (isset($fare['OtherCharges']) && $fare['OtherCharges'] != null) {

                                          $OtherCharges = $fare['OtherCharges'];

                                          $paxTotalprice = $paxTotalprice + $OtherCharges;
                                       }

                                       if (isset($fare['Discount']) && $fare['Discount'] != null) {

                                          $Discount = $fare['Discount'];

                                          $paxTotalprice = $paxTotalprice - $Discount;
                                       }

                                       if (isset($fare['TDS']) && $fare['TDS'] != null) {

                                          $TDS = $fare['TDS'];

                                          $paxTotalprice = $paxTotalprice + $TDS;
                                       }

                                       if (isset($fare['AgentCommission']) && $fare['AgentCommission'] != null) {

                                          $AgentCommission = $fare['AgentCommission'];

                                          $paxTotalprice = $paxTotalprice - $AgentCommission;
                                       }

                                       if (isset($fare['GSTAmount']) && $fare['GSTAmount'] != null) {

                                          $GSTAmount = $fare['GSTAmount'];

                                          $paxTotalprice = $paxTotalprice + $GSTAmount;
                                       }





                                       ?>
                                       <div class="col-md-2 amendment_leftpad">
                                          <p class="mb-0">Service Charges</p>
                                          <p class="price-width-left">
                                             ₹
                                             <?php echo $fare['ServiceCharges'];
                                             $paxTotalprice = $paxTotalprice + $fare['ServiceCharges']; ?>
                                          </p>
                                       </div>
                                       <div class="col-md-2 amendment_leftpad">
                                          <p class="mb-0">Other Charges</p>
                                          <p class="price-width-left">
                                             ₹
                                             <?php echo $OtherCharges; ?>
                                          </p>
                                       </div>
                                       <div class="col-md-2 amendment_leftpad">
                                          <p class="mb-0">Agent Commission</p>
                                          <p class="price-width-left">
                                             ₹
                                             <?php echo $AgentCommission; ?>
                                          </p>
                                       </div>
                                       <div class="col-md-2 amendment_leftpad">
                                          <p class="mb-0">Discount</p>
                                          <p class="price-width-left">
                                             ₹
                                             <?php echo $Discount; ?>
                                          </p>
                                       </div>
                                       <div class="col-md-2 amendment_leftpad">
                                          <p class="mb-0">GST Amount</p>
                                          <p class="price-width-left">
                                             ₹
                                             <?php echo $GSTAmount; ?>
                                          </p>
                                       </div>
                                       <div class="col-md-2 amendment_leftpad">
                                          <p class="mb-0">TDS</p>
                                          <p class="price-width-left">
                                             ₹
                                             <?php echo $TDS; ?>
                                          </p>
                                       </div>
                                       <?php if ($fare['BaggageCharges']) { ?>
                                          <div class="col-md-2 amendment_leftpad">
                                             <p class="mb-0">Baggage Charges</p>
                                             <p class="price-width-left">
                                                ₹
                                                <?php echo $fare['BaggageCharges'];
                                                $paxTotalprice = $paxTotalprice + $fare['BaggageCharges']; ?>
                                             </p>
                                          </div>
                                       <?php } ?>
                                       <?php if ($fare['MealCharges']) { ?>
                                          <div class="col-md-2 amendment_leftpad">
                                             <p class="mb-0">Meal Charges</p>
                                             <p class="price-width-left">
                                                ₹
                                                <?php echo $fare['MealCharges'];
                                                $paxTotalprice = $paxTotalprice + $fare['MealCharges']; ?>
                                             </p>
                                          </div>
                                       <?php } ?>
                                       <?php if ($fare['SeatCharges']) { ?>
                                          <div class="col-md-2 amendment_leftpad">
                                             <p class="mb-0">Seat Charges</p>
                                             <p class="price-width-left">
                                                ₹
                                                <?php echo $fare['SeatCharges'];
                                                $paxTotalprice = $paxTotalprice + $fare['SeatCharges']; ?>
                                             </p>
                                          </div>
                                       <?php } ?>
                                       <div class="col-md-2 amendment_leftpad">
                                          <p class="mb-0"><b>Total Amount</b></p>
                                          <p class="price-width-left">
                                             <b> ₹
                                                <?php echo $paxTotalprice; ?>
                                             </b>
                                          </p>
                                       </div>
                                       <?php if ($bookingDetail['pnr'] != Null) { ?>
                                          <div class="col-md-2 amendment_leftpad">
                                             <p class="mb-0"> PNR</p>
                                             <p class="price-width-left">
                                                <?php echo $bookingDetail['pnr']; ?>
                                             </p>
                                          </div>
                                       <?php } ?>
                                       <?php if ($bookingDetail['pnr'] != Null) { ?>
                                          <div class="col-md-2 amendment_leftpad">
                                             <p class="mb-0">Airline PNR</p>
                                             <p class="price-width-left">
                                                <?php $gdsPnr = json_decode($bookingDetail['airline_pnr'], true);
                                                echo $gdsPnr = getGdsPnr($gdsPnr); ?>
                                             </p>
                                          </div>
                                       <?php } ?>
                                       <?php if ($traveler['ticket_number'] != Null) { ?>
                                          <div class="col-md-2 amendment_leftpad">
                                             <p class="mb-0">Ticket Number</p>
                                             <p class="price-width-left">
                                                <?php echo $traveler['ticket_number']; ?>
                                             </p>
                                          </div>
                                       <?php } ?>
                                    </div>
                                 </div>
                              </div>
                           <?php }
                        } ?>
                     </div>
                  </div>
               </div>
            </div>
            <?php
            $paymentInfo = array();
            if (isset($bookingDetail['paymentInfo']) && $bookingDetail['paymentInfo']) {
               $paymentInfo = $bookingDetail['paymentInfo'];
            }
            ?>
            <?php if (!empty($paymentInfo) && is_array($paymentInfo)) { ?>
               <div class="accordion-item">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                     data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                     <span class="acordian_heading">Payment Process</span>
                     <span class="ball__mainwrapper"><span class="ball__border info_length-green"><span
                              class="numbering-section">
                              <?php echo count($paymentInfo); ?>
                           </span></span></span>
                  </button>
                  <div id="collapseFive" class="accordion-collapse collapse show" aria-labelledby="headingFive"
                     data-bs-parent="#accordionExample">
                     <div class="accordion-body">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="table-responsive">
                                 <table class="table table-bordered table-hover">
                                    <thead class="table-active">
                                       <tr>
                                          <th>Sr.No.</th>
                                          <th>Booking Ref. No.</th>
                                          <th>Invoice No.</th>
                                          <th>Credit Note No.</th>
                                          <th>Date</th>
                                          <th>Payments Type/Transaction id</th>
                                          <th>Debit</th>
                                          <th>Credit</th>
                                          <th>Balance</th>
                                          <th style="white-space:unset">Remark</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                       if (!empty($paymentInfo) && is_array($paymentInfo)) {

                                          foreach ($paymentInfo as $key => $data) {

                                             $prefix_booking_ref_number = '';
                                             ?>
                                             <tr>
                                                <td>
                                                   <?php echo $key + 1; ?>
                                                </td>
                                                <td>
                                                   <?php $extra_parm = json_decode($data['extra_param'], true);
                                                   if (isset($extra_parm['booking_ref_number'])) {
                                                      $book_ref_no = explode(',', $extra_parm['booking_ref_number']);
                                                      if ($data['transaction_type'] == "credit" && count($book_ref_no) > 1) {
                                                         echo '<a href="' . site_url('flight/details/' . $book_ref_no[0]) . '">' . $book_ref_no[0] . '</a>,';
                                                         echo '<a href="' . site_url('flight/details/' . $book_ref_no[1]) . '">' . $book_ref_no[1] . '</a>';
                                                      } else {
                                                         echo '<a href="' . site_url('flight/details/' . $extra_parm['booking_ref_number']) . '">' . $extra_parm['booking_ref_number'] . '</a>';
                                                      }
                                                   } else {
                                                      echo '------';
                                                   }
                                                   ?>
                                                </td>

                                                <td>
                                                   <?php echo ($data['action_type'] == "booking") ? $data['invoice_number'] : '-'; ?>
                                                </td>
                                                <td>
                                                   <?php echo ($data['action_type'] == "refund") ? $data['invoice_number'] : '-'; ?>
                                                </td>
                                                <td>
                                                   <?php
                                                   echo date_created_format($data['created']);
                                                   ?>
                                                </td>
                                                <td>
                                                   <?php
                                                   $transaction_id = '';

                                                   $payment_mode = str_replace("_", " ", $data['payment_mode']);
                                                   if (isset($bookingDetail['paymentInfo']) && ($data['action_type'] == "recharge")) {

                                                      $transaction_id = "/" . $bookingDetail['paymentInfo'][$key]['transaction_id'];
                                                   }





                                                   echo $payment_mode != "" ? "<b></b> " . $payment_mode . $transaction_id . "<br/>" : ""; ?>
                                                </td>
                                                <td>
                                                   <?php echo custom_money_format($data['debit']); ?>
                                                </td>
                                                <td>
                                                   <?php echo custom_money_format($data['credit']); ?>
                                                </td>
                                                <td>
                                                   <?php echo custom_money_format(round_value($data['balance'])); ?>
                                                </td>
                                                <td style="white-space:unset">
                                                   <?php $controller = ($bookingDetail['booking_source'] == "Wl_b2b") ? 'agent' : 'customer' ?>
                                                   <a href="javascript:void(0);" view-data-modal="true"
                                                      data-controller='webpartneraccounts'
                                                      data-href="<?php echo site_url('/' . $controller . '/view-remark/') . dev_encode($data['id']) ?>"><i
                                                         class="fa-solid fa-eye"></i> View</a>
                                                </td>
                                             </tr>
                                             <?php
                                          }
                                       } else {

                                          echo "<tr> <td colspan='11' class='text_center'><b>No Account Logs Found</b></td></tr>";
                                       } ?>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            <?php } ?>
            <div class="accordion-item">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                  data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                  <span class="acordian_heading">Fare Breakup </span>
               </button>
               <div id="collapseSeven" class="accordion-collapse collapse show" aria-labelledby="headingSeven"
                  data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                     <div class="table-responsive">
                        <?php $FareBreakUp = $bookingDetail['FareBreakUp'];
                        if ($FareBreakUp) { ?>
                           <table class="table table-bordered ">
                              <tr>
                                 <th scope="row">
                                    <?php echo $FareBreakUp['WebPMarkUp']['LabelText']; ?>:
                                 </th>
                                 <td>₹
                                    <?php echo $FareBreakUp['WebPMarkUp']['Value']; ?>
                                 </td>
                              </tr>
                              <tr>
                                 <th scope="row">
                                    <?php echo $FareBreakUp['WebPDiscount']['LabelText']; ?>:
                                 </th>
                                 <td>₹
                                    <?php echo $FareBreakUp['WebPDiscount']['Value']; ?>
                                 </td>
                              </tr>
                              <tr>
                                 <th scope="row">
                                    <?php echo $FareBreakUp['WebPDisplayMarkup']['LabelText']; ?>:
                                 </th>
                                 <td>
                                    <?php echo $FareBreakUp['WebPDisplayMarkup']['Value']; ?>
                                 </td>
                              </tr>
                           </table>
                        </div>
                        <div class="table-responsive">
                           <table class="table table-bordered ">
                              <?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
                                 <tr>
                                    <th scope="row">
                                       <?php echo $fare['LabelText']; ?>:
                                    </th>
                                    <td>₹
                                       <?php echo $fare['Value']; ?>
                                    </td>
                                 </tr>
                              <?php } ?>
                              <tr>
                                 <th scope="row">
                                    <?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>:
                                 </th>
                                 <th scope="row">₹
                                    <?php echo $FareBreakUp['TotalAmount']['Value']; ?>
                                 </th>
                              </tr>
                           </table>
                        </div>
                        <div class="table-responsive">
                           <?php if (isset($FareBreakUp['GSTDetails']) && $FareBreakUp['GSTDetails']) { ?>
                              <table class="table table-bordered ">
                                 <tr>
                                    <th>Service Description</th>
                                    <th>Taxable Value</th>
                                    <th>CGST @
                                       <?php echo $FareBreakUp['GSTDetails']['CGSTRate']; ?> %
                                    </th>
                                    <th>SGST @
                                       <?php echo $FareBreakUp['GSTDetails']['SGSTRate']; ?>%
                                    </th>
                                    <th>IGST @
                                       <?php echo $FareBreakUp['GSTDetails']['IGSTRate']; ?> %
                                    </th>
                                    <th>Total</th>
                                 </tr>
                                 <tr>
                                    <th>Service Charges</th>
                                    <th>
                                       <?php echo $FareBreakUp['GSTDetails']['TaxableAmount']; ?>
                                    </th>
                                    <th>
                                       <?php echo $FareBreakUp['GSTDetails']['CGSTAmount']; ?>
                                    </th>
                                    <th>
                                       <?php echo $FareBreakUp['GSTDetails']['SGSTAmount']; ?>
                                    </th>
                                    <th>
                                       <?php echo $FareBreakUp['GSTDetails']['IGSTAmount']; ?>
                                    </th>
                                    <th>
                                       <?php echo $FareBreakUp['GSTDetails']['CGSTAmount'] + $FareBreakUp['GSTDetails']['SGSTAmount'] + $FareBreakUp['GSTDetails']['IGSTAmount']; ?>
                                    </th>
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
                  data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                  <span class="acordian_heading">Pax Information </span>
               </button>
               <div id="collapseSix" class="accordion-collapse collapse show" aria-labelledby="headingSix"
                  data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                     <div class="amend_details-passengers--list">
                        <?php if (isset($bookingDetail['travelersInfo'])) {
                           $travelersInfo = json_decode($bookingDetail['travelersInfo'], true);
                           if ($travelersInfo) { ?>
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="cart_info-field">
                                       <p class="cart_info-field--title"> Email:<span class="cart_info-field--detail"><span>
                                                <?php echo $travelersInfo[0]['email_id']; ?>
                                             </span></span>
                                       </p>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="cart_info-field">
                                       <p class="cart_info-field--title">Mobile Number :<span
                                             class="cart_info-field--detail"><span>
                                                <?php echo $travelersInfo[0]['mobile_number']; ?>
                                             </span></span>
                                       </p>
                                    </div>
                                 </div>
                              </div>
                           <?php }
                        } ?>
                     </div>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>
</div>
<div class="modal fade" id="FareRuleDetailModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
   aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index:999999!important;">
   <div class="modal-dialog">
      <div class="modal-content modal_content">
         <div class="modal-header modal_header">
            <h5 class="modal-title">Fare Rule Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <?php if ($bookingDetail['fare_rule']) {
               $fareRuleDeatils = json_decode($bookingDetail['fare_rule'], true); ?>

               <div class="row">
                  <?php foreach ($fareRuleDeatils as $fare_rule) { ?>
                     <div class="col-md-12 mb-3">
                        <button class="ars-activelist fare-rules-tabs">
                           <?php echo $fare_rule['Origin'] . "-" . $fare_rule['Destination']; ?>
                        </button>
                     </div>
                     <div class="col-md-12">
                        <?php echo $fare_rule['FareRuleDetail']; ?>
                     </div>
                  <?php } ?>
               </div>
            <?php }

            ?>
         </div>
      </div>
   </div>
</div>