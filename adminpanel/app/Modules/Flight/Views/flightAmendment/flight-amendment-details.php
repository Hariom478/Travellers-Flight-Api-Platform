<style>
   .input-floating-label {
   font-size: 13px;
   padding: 13.5px 0;
   border: none;
   border-bottom: solid 1px #e5e5e5;
   width: 100%;
   box-sizing: border-box;
   transition: all 0.3s linear;
   color: #333;
   font-weight: 400;
   -webkit-appearance: none;
   -moz-appearance: none;
   -o-appearance: none;
   border-radius: 0;
   background: transparent;
   }
   .input-floating-label:focus{
      box-shadow: none;
      outline: none;
   }
</style>
<div class="page-content">
   <div class="table_title">

<section class="cart_information p0">
   <div class="container-fluid p0">
      <div class="sale_bar">
         <div class="row align-items-center">
            <div class="col-md-6">
               <h5 class="m0"> Flight Amendment</h5>
            </div>
            <div class="col-md-6 text-md-end">
               <a class="badge badge-wt" href="<?php echo site_url('/flight/confirmation/') .   $ticketData  =  dev_encode(json_encode(array($amendmentDetail['flightbookingid']))); ?>">
                  <i class="fa-solid fa-book"></i> Booking Summary
               </a>
            </div>
         </div>
      </div>
      <?php  $edit_status = edit_permission_status(whitelabel['is_direct_website'],$bookingDetail['inventory_source'],$bookingDetail['api_supplier']);?>
      <div class="row">
         <div class="col-md-12 mb-4">
            <div class="cart_info">
               <div class="accordion" id="accordionPanelsStayOpenExample">
                  <div class="accordion-item">
                    <!--  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseamendment" aria-expanded="true" aria-controls="collapseamendment">
                     <span class="acordian_heading">Amendment Information : <?php echo  $amendmentDetail['id']; ?></span>
                     </button> -->
                     <div id="collapseamendment" class="accordion-collapse collapse show" aria-labelledby="headingamendment" data-bs-parent="#accordionExample">
                        <div class="accordion-body ">
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="cart_info-field">
                                    <p class="cart_info-field--title">Amendment Id :<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $amendmentDetail['id']; ?></span></span></p>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="cart_info-field">
                                    <p class="cart_info-field--title">Amendment Type :<span class="cart_info-field--detail"><span> &nbsp;<?php echo ucfirst($amendmentDetail['amendment_type']); ?></span></span></p>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="cart_info-field">
                                    <p class="cart_info-field--title">Amendment Status :<span class="cart_info-field--detail"><span> &nbsp;&nbsp;<?php echo  ucfirst($amendmentDetail['amendment_status']); ?></span></span></p>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="cart_info-field">
                                    <p class="cart_info-field--title">Created On :<span class="cart_info-field--detail"><span> &nbsp;<?php echo date_created_format($amendmentDetail['created']); ?></span></span></p>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="cart_info-field">
                                    <p class="cart_info-field--title">Remark From User:<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $amendmentDetail['remark_from_user']; ?></span></span></p>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="cart_info-field">
                                    <p class="cart_info-field--title"> Staff Name : <span class="cart_info-field--detail"><span> &nbsp;<?php echo  isset($amendmentDetail['staff_name'])?$amendmentDetail['staff_name']:"NA"; ?></span></span></p>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="cart_info-field">
                                    <p class="cart_info-field--title">Remark From Staff :<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $amendmentDetail['remark_from_web_partner']; ?></span></span></p>
                                 </div>
                              </div>
                              
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="accordion-item">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseamendmentDetail" aria-expanded="true" aria-controls="collapseamendmentDetail">
                     <span class="acordian_heading">Amendment Detail </span>
                     <?php if ($amendmentDetail['amendment_type'] == "reissue") { ?>
                     <a class="acordian_heading pull-right" href="<?php echo  site_url('flight-ticket-upload/reissue-ticket?booking-ref=' . $amendmentDetail['booking_ref_number'] . "&amendment-id=" . $amendmentDetail['id']) ?>">Reissue</a>
                     <?php } ?>
                     </button>
                     <div id="collapseamendmentDetail" class="accordion-collapse collapse show" aria-labelledby="headingamendmentDetail" data-bs-parent="#accordionExample">
                        <?php $amendmentrequestDetail = json_decode($amendmentDetail['request'], true); ?>
                        <div class="accordion-body ">
                           <?php if (isset($amendmentrequestDetail['Sectors'])) {   ?>
                           <div class="row">
                              <?php foreach ($amendmentrequestDetail['Sectors'] as  $Sectors) { ?>
                              <div class="col-md-1">
                                 <div class="segment_body-airlogo">
                                    <p class="mb-0"><span class="airline-code"><?php echo  $Sectors['Origin']; ?> -<?php echo  $Sectors['Destination']; ?></span></p>
                                 </div>
                              </div>
                              <?php } ?>
                           </div>
                           <?php }   ?>
                           <div class="amend_details-passengers--list">
                              <form action="<?php echo site_url('flight/flight-amendment-cancellation-charge'); ?>" method="post" tts-form="true" name="cancellation_charge_update">
                                 <?php
                                    if (isset($amendmentDetail['travelersInfo'])) {
                                       $travelersInfo  = json_decode($amendmentDetail['travelersInfo'], true);
                                       $cancelledpaxInfoId  =  explode(",", $amendmentDetail['pax_id']);
                                       $amendmentPassengerKey = 0;
                                       foreach ($travelersInfo as $paxkey => $traveler) {
                                          $mealInfo  =  json_decode($traveler['meal'], true);
                                          $baggageInfo  =  json_decode($traveler['baggage'], true);
                                          $amendment_charges =  array();
                                    
                                          $charge =   0;
                                    
                                          $service_charge =   0;
                                    
                                          $meal_charge =   0;
                                    
                                          $baggage_charge =  0;
                                    
                                          $seat_charge =   0;
                                    
                                          $refund =   0;
                                    
                                          $service_charge_gst =   0;
                                    
                                          $TDSReturnIdentifier =   "no";
                                    
                                          $TDSReturnIdentifierChecked =   "";
                                          if (isset($traveler['amendment_charges']) && $traveler['amendment_charges'] != Null) {
                                    
                                             $amendment_charges  =  json_decode($traveler['amendment_charges'], true);
                                    
                                             $charge =   $amendment_charges['Charge'];
                                    
                                             $service_charge =   $amendment_charges['ServiceCharge'];
                                    
                                             $meal_charge =   $amendment_charges['MealCharge'];
                                    
                                             $baggage_charge =   $amendment_charges['BaggageCharge'];
                                    
                                             $seat_charge =   $amendment_charges['SeatCharge'];
                                    
                                             $refund =   $amendment_charges['Refund'];
                                    
                                             $TDSReturnIdentifier = isset($amendment_charges['TDSReturnIdentifier']) ? $amendment_charges['TDSReturnIdentifier'] : "no";
                                    
                                             $TDSReturnIdentifierChecked =   $TDSReturnIdentifier == "yes" ? "checked" : "";
                                    
                                             $service_charge_gst =   $amendment_charges['GST']['TotalGSTAmount'];
                                          }
                                          if($bookingDetail['booking_source'] == "Wl_b2b"){
                                             $fare  =  json_decode($traveler['agent_fare'], true);
                                          }else{
                                             $fare  =  json_decode($traveler['customer_fare'], true);
                                          }
                                          if (in_array($traveler['id'], $cancelledpaxInfoId)) {
                                    
                                    ?>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <div class="amend_passenger_details mb-3 mb-lg-0">
                                          <span>Last Name/First Name Title</span>
                                          <div class="person-name d-flex align-items-center justify-content-between">
                                             <span class=""><?php echo $paxkey + 1; ?>. <?php echo  $traveler['last_name']; ?>/<?php echo  $traveler['first_name']; ?> <?php echo  $traveler['title']; ?>. (<?php echo  $traveler['pax_type']; ?>)</span>
                                          </div>
                                          <div class="row">
                                             <div class="col-md-6">
                                                <span class="sm_font padd-left-amendment">Status : <span class="bold"><?php echo  $traveler['booking_status']; ?></span></span>
                                             </div>
                                             <?php if ($traveler['date_of_birth'] != NULL) {  ?>
                                             <div class="col-md-6">
                                                <span class="sm_font padd-left-amendment">DOB : <span class="bold"><?php echo $traveler['date_of_birth'] != "" ? display_custom_date_format($traveler['date_of_birth'], false) : "-"; ?></span></span>
                                             </div>
                                             <?php } ?>
                                             <?php if ($traveler['pan_number'] != NULL) {  ?>
                                             <div class="col-md-6">
                                                <span class="sm_font padd-left-amendment">Pan Number : <span class="bold"><?php echo  $traveler['pan_number']; ?></span></span>
                                             </div>
                                             <?php } ?>
                                             <?php if ($traveler['passport_number'] != NULL) {  ?>
                                             <div class="col-md-6">
                                                <span class="sm_font padd-left-amendment">Passport Number : <span class="bold"><?php echo  $traveler['passport_number']; ?></span></span>
                                             </div>
                                             <?php } ?>
                                             <?php if ($traveler['passport_expiry'] != NULL) {  ?>
                                             <div class="col-md-6">
                                                <span class="sm_font padd-left-amendment">Passport Expiry : <span class="bold"><?php echo $traveler['passport_expiry'] != "" ? display_custom_date_format($traveler['passport_expiry'], false) : "-"; ?></span></span>
                                             </div>
                                             <?php } ?>
                                          </div>
                                       </div>
                                       <?php if ($traveler['baggage'] != null || $traveler['meal'] != null) {  ?>
                                       <div class="amend_passenger_details">
                                          <div class="row">
                                             <?php if ($traveler['baggage'] != null && $baggageInfo) { ?>
                                             <div class="col-sm-12 col-xs-6 col-6 padd-left-amendment">
                                                <p class="m-0">Baggage</p>
                                                <?php if ($baggageInfo) {
                                                   foreach ($baggageInfo as $baggage) {
                                                      $AirlineDescription =  isset($baggage['AirlineDescription']) ? $baggage['AirlineDescription'] : '';
                                                      $baggageData =   "" . $baggage['Origin'] . "-" . $baggage['Destination'] . ": - " . $AirlineDescription . ""; ?>
                                                <p class="price-width-left "> <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $baggageData; ?>"><?php echo limitTextChars($baggageData, 60, true, true); ?> </span></p>
                                                <?php   }
                                                   } else {   ?>
                                                <p class="price-width-left "> NA</p>
                                                <?php } ?>
                                             </div>
                                             <?php } ?>
                                             <?php if ($traveler['meal'] != null && $mealInfo) { ?>
                                             <div class="col-sm-12 col-xs-6 col-6 padd-left-amendment">
                                                <p class="m-0">Meal</p>
                                                <?php if ($mealInfo) {
                                                   foreach ($mealInfo as $meal) {
                                                      $mealData  =   "" . $meal['Origin'] . "-" . $meal['Destination'] . ": - " . $meal['AirlineDescription'] . "(" . $meal['Code'] . ")" . " ( QTY : " . $meal['Quantity'] . " )";   ?> <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $mealData; ?>"><?php echo limitTextChars($mealData, 60, true, true); ?> </span></p><?php }
                                                   } else {   ?>
                                                <p class="price-width-left "> NA</p>
                                                <?php } ?>
                                             </div>
                                             <?php } ?>
                                          </div>
                                       </div>
                                       <?php  } ?>
                                    </div>
                                    <div class="col-md-8 passenger_faredetail">
                                       <div class="row">
                                          <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0">Base Fare</p>
                                             <p id="base_fare_<?php echo $traveler['id'];  ?>" fareCharge="<?php echo  $fare['BaseFare']; ?>">₹ <?php echo  $fare['BaseFare'];      ?></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0">Taxes</p>
                                             <p id="airline_tax_<?php echo $traveler['id'];  ?>" airlineTaxCharge="<?php echo  $fare['Tax']; ?>">₹ <?php echo  $fare['Tax'];      ?></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0">YQ Tax</p>
                                             <p id="yq_tax_<?php echo $traveler['id'];  ?>" yqtaxCharge="<?php echo  $fare['YQTax']; ?>">₹ <?php echo  $fare['YQTax'];      ?></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0">YR Tax</p>
                                             <p >₹ 0</p>
                                          </div>
                                          <?php
                                             $OtherCharges = 0;
                                             
                                             $Discount = 0;
                                             
                                             $TDS = 0;
                                             
                                             $GSTAmount = 0;
                                             
                                             $AgentCommission = 0;
                                             
                                             if (isset($fare['OtherCharges']) && $fare['OtherCharges'] != null) {
                                             
                                                $OtherCharges = $fare['OtherCharges'];
                                             }
                                             
                                             if (isset($fare['Discount']) && $fare['Discount'] != null) {
                                             
                                                $Discount = $fare['Discount'];
                                             }
                                             
                                             if (isset($fare['TDS']) && $fare['TDS'] != null && $bookingDetail['booking_source'] == "Wl_b2b") {
                                             
                                                $TDS = $fare['TDS'];
                                             }
                                             
                                             if (isset($fare['AgentCommission']) && $fare['AgentCommission'] != null) {
                                             
                                                $AgentCommission = $fare['AgentCommission'];
                                             }
                                             
                                             if (isset($fare['GSTAmount']) && $fare['GSTAmount'] != null) {
                                             
                                                $GSTAmount = $fare['GSTAmount'];
                                             }
                                             
                                             
                                             
                                             
                                             
                                             ?>
                                              <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0">Other Charges</p>
                                             <p id="airline_other_charges_<?php echo $traveler['id'];  ?>" airlineOtherCharges="<?php echo  $OtherCharges; ?>">₹ <?php echo  $OtherCharges;      ?></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0">Service Charges</p>
                                             <p id="airline_service_charges_<?php echo $traveler['id'];  ?>" airlineServiceCharge="<?php echo  $fare['ServiceCharges']; ?>">₹ <?php echo  $fare['ServiceCharges'];      ?></p>
                                          </div>
                                         
                                          <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0">Agent Commission</p>
                                             <p id="airline_agent_commission_<?php echo $traveler['id'];  ?>" airlineAgentCommission="<?php echo  $AgentCommission; ?>">₹ <?php echo  $AgentCommission;      ?></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0">Discount</p>
                                             <p id="airline_discount_<?php echo $traveler['id'];  ?>" airlineDiscount="<?php echo  $Discount; ?>">₹ <?php echo  $Discount;      ?></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0">GST Amount</p>
                                             <p id="airline_gst_amount_<?php echo $traveler['id'];  ?>" airlineGSTAmount="<?php echo  $GSTAmount; ?>">₹ <?php echo  $GSTAmount;      ?></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0">TDS</p>
                                             <p id="airline_tds_<?php echo $traveler['id'];  ?>" airlineTDS="<?php echo  $TDS; ?>">₹ <?php echo  $TDS;      ?></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment col-6 <?php if (!$fare['BaggageCharges']) {
                                             echo  'hide';
                                             } ?>">
                                             <p class="mb-0">Baggage Charges</p>
                                             <p id="airline_baggage_charges_<?php echo $traveler['id'];  ?>" airlineBaggageCharge="<?php echo  $fare['BaggageCharges']; ?>">₹ <?php echo  $fare['BaggageCharges'];      ?></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment col-6 <?php if (!$fare['MealCharges']) {
                                             echo  'hide';
                                             } ?>">
                                             <p class="mb-0">Meal Charges</p>
                                             <p id="airline_meal_charges_<?php echo $traveler['id'];  ?>" airlineMealCharge="<?php echo  $fare['MealCharges']; ?>">₹ <?php echo  $fare['MealCharges'];      ?></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment col-6   <?php if (!$fare['SeatCharges']) {
                                             echo  'hide';
                                             } ?>">
                                             <p class="mb-0">Seat Charges</p>
                                             <p id="airline_seat_charges_<?php echo $traveler['id'];  ?>" airlineSeatCharge="<?php echo  $fare['SeatCharges']; ?>">₹ <?php echo  $fare['SeatCharges'];      ?></p>
                                          </div>
                                          <?php if (isset($amendmentDetail['pnr']) && $amendmentDetail['pnr'] != Null) { ?>
                                          <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0"> Airline PNR</p>
                                             <p> <?php echo  $amendmentDetail['pnr'];      ?></p>
                                          </div>
                                          <?php } ?>
                                          <?php if (isset($amendmentDetail['airline_pnr']) && $amendmentDetail['airline_pnr'] != Null) { ?>
                                          <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0">GDS PNR</p>
                                             <p> <?php $gdsPnr  =  json_decode($amendmentDetail['airline_pnr'], true);
                                                echo $gdsPnr = getGdsPnr($gdsPnr); ?></p>
                                          </div>
                                          <?php } ?>
                                          <?php if (isset($traveler['ticket_number']) && $traveler['ticket_number'] != Null) { ?>
                                          <div class="col-md-4 padd-left-amendment col-6">
                                             <p class="mb-0">Ticket Number</p>
                                             <p><?php echo  $traveler['ticket_number'];      ?></p>
                                          </div>
                                          <?php } ?>
                                          <input type="hidden" name="amendment_id" value="<?php echo   dev_encode($amendmentDetail['id']);  ?>">
                                          <?php if ($amendmentDetail['amendment_type'] == "cancellation" || $amendmentDetail['amendment_type'] == "full_refund" || $amendmentDetail['amendment_type'] == "no_show") {
                                             $paxid  =  $traveler['id'];  ?>
                                          <div class="col-md-4 padd-left-amendment">
                                             <p class="mb-0">Airline Cancellation Fee</p>
                                             <p><input class="input-floating-label" type="text" name="charge[<?php echo  $amendmentPassengerKey; ?>][charge]" value="<?php echo  $charge; ?>" id="charge_<?php echo  $traveler['id'];  ?>" oninput='getFlightRefundCharges(event,"<?php echo  $paxid;  ?>")'>
                                                <input type="hidden" name="charge[<?php echo  $amendmentPassengerKey; ?>][pax_id]" value="<?php echo $traveler['id'];  ?>">
                                             </p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment">
                                             <p class="mb-0"> Cancellation Service Charge</p>
                                             <p><input class="input-floating-label" type="text" name="charge[<?php echo  $amendmentPassengerKey; ?>][service_charge]" value="<?php echo  $service_charge; ?>" id="service_charge_<?php echo  $traveler['id'];  ?>" oninput='getFlightRefundCharges(event,"<?php echo  $paxid;  ?>")'></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment">
                                             <p class="mb-0">Meal Charge</p>
                                             <p><input class="input-floating-label" type="text" name="charge[<?php echo  $amendmentPassengerKey; ?>][meal_charge]" value="<?php echo  $meal_charge; ?>" id="meal_charge_<?php echo  $traveler['id'];  ?>" oninput='getFlightRefundCharges(event,"<?php echo  $paxid;  ?>")'></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment">
                                             <p class="mb-0">Baggage Charge</p>
                                             <p><input class="input-floating-label" type="text" name="charge[<?php echo  $amendmentPassengerKey; ?>][baggage_charge]" value="<?php echo  $baggage_charge; ?>" id="baggage_charge_<?php echo  $traveler['id'];  ?>" oninput='getFlightRefundCharges(event,"<?php echo  $paxid;  ?>")'></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment  <?php if (!$fare['SeatCharges']) {
                                             echo  'hide';
                                             } ?>">
                                             <p class="mb-0">Seat Charge</p>
                                             <p><input class="input-floating-label" type="text" name="charge[<?php echo  $amendmentPassengerKey; ?>][seat_charge]" value="<?php echo  $seat_charge; ?>" id="seat_charge_<?php echo  $traveler['id'];  ?>" oninput='getFlightRefundCharges(event, "<?php echo  $paxid;  ?>")'></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment">
                                             <p class="mb-0">Cancellation Charge GST</p>
                                             <p><input class="input-floating-label" type="text" name="charge[<?php echo  $amendmentPassengerKey; ?>][service_charge_gst]" value="<?php echo  $service_charge_gst; ?>" id="service_charge_gst_<?php echo  $traveler['id'];  ?>" readonly></p>
                                          </div>
                                          <div class="col-md-4 padd-left-amendment">
                                             <p class="mb-0">Refund Amount</p>
                                             <p><input class="input-floating-label" type="text" name="charge[<?php echo  $amendmentPassengerKey; ?>][refund]" value="<?php echo  $refund; ?>" id="refund_<?php echo  $traveler['id'];  ?>" readonly></p>
                                          </div>
                                          <?php if($bookingDetail['booking_source'] == "Wl_b2b"){ ?>
                                          <div class="col-md-4 padd-left-amendment">
                                             <label class="form-check-label"><input class="form-check-input" type="checkbox" name="charge[<?php echo  $amendmentPassengerKey; ?>][tdsreturn]" value="yes" id="tdsreturn_<?php echo  $traveler['id'];  ?>" onclick='getFlightRefundCharges(event, "<?php echo  $paxid;  ?>")' <?php echo  $TDSReturnIdentifierChecked; ?>>TDS Return</label>
                                          </div>
                                          <?php } ?>
                                          <?php  } ?>
                                       </div>
                                    </div>
                                 </div>
                                 <?php $amendmentPassengerKey =  $amendmentPassengerKey + 1;
                                    }
                                    }
                                    } ?>
                                 <?php if ($amendmentDetail['refund_status'] != "Close" && ($amendmentDetail['amendment_type'] == "cancellation" || $amendmentDetail['amendment_type'] == "full_refund")) { ?>
                                 <div class="row">
                                    <div class="col-md-12"><button class="btn btn-primary pull-right" type="submit">Update</button></div>
                                 </div>
                                 <?php } ?>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-12">
             <?php echo view('Modules\Flight\Views\listing/flight-booking-detail-template', ['bookingDetail' => $bookingDetail,'edit_status'=>$edit_status]) ?>
         </div>
      </div>
</section>
</div>
</div>
<script>
   function getFlightRefundCharges(evt, paxId) {
   
      var flightgst = 18;
   
      evt = (evt) ? evt : window.event;
   
      var charCode = (evt.which) ? evt.which : evt.keyCode;
   
      /*  if ((charCode > 31) && (charCode <= 48 || charCode > 57)) { */
   
      var charge = parseFloat(document.getElementById("charge_" + paxId).value);
   
      var serviceCharge = parseFloat(document.getElementById("service_charge_" + paxId).value);
   
      var mealCharge = parseFloat(document.getElementById("meal_charge_" + paxId).value);
   
      var baggageCharge = parseFloat(document.getElementById("baggage_charge_" + paxId).value);
   
      var seatCharge = parseFloat(document.getElementById("seat_charge_" + paxId).value);
   
      var basefare = parseFloat(document.getElementById("base_fare_" + paxId).getAttribute('fareCharge'));
   
      var airline_tax = parseFloat(document.getElementById("airline_tax_" + paxId).getAttribute('airlineTaxCharge'));
   
      var yq_tax = parseFloat(document.getElementById("yq_tax_" + paxId).getAttribute('yqtaxCharge'));
   
      var airline_service_charges = parseFloat(document.getElementById("airline_service_charges_" + paxId).getAttribute('airlineServiceCharge'));
   
      var airline_other_charges = parseFloat(document.getElementById("airline_other_charges_" + paxId).getAttribute('airlineOtherCharges'));
   
      var tds = 0;
   
      if ($("#tdsreturn_" + paxId).prop('checked') == true) {
   
         var tds = parseFloat(document.getElementById("airline_tds_" + paxId).getAttribute('airlineTDS'));
   
      }
   
      var airline_agent_commission = parseFloat(document.getElementById("airline_agent_commission_" + paxId).getAttribute('airlineAgentCommission'));
   
      var airline_discount = parseFloat(document.getElementById("airline_discount_" + paxId).getAttribute('airlineDiscount'));
   
      var airline_gst_amount = parseFloat(document.getElementById("airline_gst_amount_" + paxId).getAttribute('airlineGSTAmount'));
   
      var airline_baggage_charges = parseFloat(document.getElementById("airline_baggage_charges_" + paxId).getAttribute('airlineBaggageCharge'));
   
      var airline_meal_charges = parseFloat(document.getElementById("airline_meal_charges_" + paxId).getAttribute('airlineMealCharge'));
   
      var airline_seat_charges = parseFloat(document.getElementById("airline_seat_charges_" + paxId).getAttribute('airlineSeatCharge'));
   
      /* var TotalpaxFare  = parseFloat((basefare+airline_tax+airline_service_charges+airline_baggage_charges+airline_meal_charges+airline_seat_charges)); */
   
      var TotalpaxFare = parseFloat((basefare + airline_tax + airline_other_charges + airline_gst_amount - airline_agent_commission - airline_discount + tds)).toFixed(2);
      var serviceChargeGst = calculate_flight_gst(serviceCharge, flightgst);
   
      var totalRefundAmount = parseFloat((charge + serviceCharge + serviceChargeGst));
   
      var ssrPrice = mealCharge + baggageCharge + seatCharge;
   
      var refund = (TotalpaxFare - totalRefundAmount).toFixed(2);
   
   
   
      if (!isNaN(serviceChargeGst))
   
      {
   
         document.getElementById("service_charge_gst_" + paxId).value = serviceChargeGst;
   
      } else {
   
         document.getElementById("service_charge_gst_" + paxId).value = 0;
   
      }
   
      if (!isNaN(refund)) {
   
         if (refund < 0) {
   
            $("[data-message]").addClass('error_popup').html("Please check refund amount value is negative.");
   
         } else {
   
            $("[data-message]").removeClass('error_popup').html("");
   
         }
   
         document.getElementById("refund_" + paxId).value = parseFloat(refund) + parseFloat(ssrPrice);
   
      } else {
   
         document.getElementById("refund_" + paxId).value = 0;
   
      }
   
      /* } */
   
   }
   
   function calculate_flight_gst(serviceCharge, flightgst)
   
   {
   
      var returnval = Math.round(((serviceCharge * flightgst) / 100), 2);
   
      return returnval;
   
   }
</script>