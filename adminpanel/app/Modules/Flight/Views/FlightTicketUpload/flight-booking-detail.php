<div class="page-content">
   <div class="table_title">
      <section class="cart_information p0">
         <div class="container-fluid p0">
            <div class="row">
               <div class="col-md-12">
                  <div class="cart_info">
                     <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                           <button class="accordion-button" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapseOne" aria-expanded="true"
                              aria-controls="collapseOne">
                           <span class="acordian_heading">Review Details</span>
                           </button>
                           <div id="collapseOne" class="accordion-collapse collapse show"
                              aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                              <div class="accordion-body cart-details-borderline">
                                 <div class  =  "row">
                                    <?php if ($bookingDetail['pnr'] != "") { ?>
                                    <div class="col-md-3">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">PNR :<span
                                             class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['pnr']; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-3">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Refundable :<span
                                             class="cart_info-field--detail"><span
                                             class="<?php echo $bookingDetail['is_refundable'] == 1 ? "tts-text-success" : "tts-text-danger"; ?>"> &nbsp;<?php echo $bookingDetail['is_refundable'] == 1 ? "Yes" : "NO"; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Amount :<span
                                             class="cart_info-field--detail"><span> &nbsp;<i class="fa fa-inr" aria-hidden="true"></i> <?php echo custom_money_format($bookingDetail['total_price']); ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Fare Type :<span
                                             class="cart_info-field--detail"><span> &nbsp;<a
                                             href="javascript:void(0)"
                                             class=""><?php echo $bookingDetail['fare_type']; ?></a></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Supplier :<span
                                             class="cart_info-field--detail"><span> &nbsp;<a
                                             href="javascript:void(0)"
                                             class=""><?php echo $bookingDetail['issue_by_supplier']; ?></a></span></span>
                                          </p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <?php if (isset($bookingDetail['UserInfo']) && !empty($bookingDetail['UserInfo']) && $bookingDetail['UserInfo']['role'] == "agent") {
                           $WebPartnerInfo = $bookingDetail['UserInfo']; ?>
                        <div class="accordion-item">
                           <button class="accordion-button" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapseWebPartnerInfo" aria-expanded="true"
                              aria-controls="collapseWebPartnerInfo">
                           <span class="acordian_heading">Agent Info </span>
                           </button>
                           <div id="collapseWebPartnerInfo" class="accordion-collapse collapse show"
                              aria-labelledby="collapseWebPartnerInfo"
                              data-bs-parent="#accordionExample">
                              <div class="accordion-body cart-details-borderline">
                                 <div class="row">
                                    <div class="col-md-3">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Company name :<span
                                             class="cart_info-field--detail"><span> &nbsp;<?php echo $WebPartnerInfo['company_name']; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Company Id :<span
                                             class="cart_info-field--detail"><span> &nbsp;&nbsp;<?php echo $WebPartnerInfo['company_id']; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <?php } ?>
                        <?php if (isset($bookingDetail['UserInfo']) && !empty($bookingDetail['UserInfo']) && $bookingDetail['UserInfo']['role'] == "customer") {
                           $WebPartnerInfo = $bookingDetail['UserInfo']; ?>
                        <div class="accordion-item">
                           <button class="accordion-button" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapseWebPartnerInfo" aria-expanded="true"
                              aria-controls="collapseWebPartnerInfo">
                           <span class="acordian_heading">Customer Info </span>
                           </button>
                           <div id="collapseWebPartnerInfo" class="accordion-collapse collapse show"
                              aria-labelledby="collapseWebPartnerInfo"
                              data-bs-parent="#accordionExample">
                              <div class="accordion-body cart-details-borderline">
                                 <div class="row">
                                    <div class="col-md-3">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Customer Name :<span
                                             class="cart_info-field--detail"><span> &nbsp;<?php echo $WebPartnerInfo['first_name'].' '.$WebpartnerInfo['last_name']; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="cart_info-field">
                                          <p class="cart_info-field--title">Email ID :<span
                                             class="cart_info-field--detail"><span> &nbsp;&nbsp;<?php echo $WebPartnerInfo['email_id']; ?></span></span>
                                          </p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <?php } ?>
                        <div class="accordion-item">
                           <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapseFour" aria-expanded="false"
                              aria-controls="collapseFour">
                           <span class="acordian_heading">Booking Details</span>
                           </button>
                           <div id="collapseFour" class="accordion-collapse collapse show"
                              aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                              <div class="accordion-body cart-details-borderline">
                                 <?php  $gdsPnr = array();if (isset($bookingDetail['segment_temp_data'])) {
                                    $tripInfo = json_decode($bookingDetail['segment_temp_data'], true);
                                    foreach ($tripInfo as $key => $trips) {
                                        if ($trips) {
                                            foreach ($trips as $segmentIndicatorkey => $segment) {
                                                if(isset($segment['AirlinePNR'])) {
                                                $gdsPnr[$segment['TripIndicator']][$segment['SegmentIndicator']] = $segment['AirlinePNR'];
                                                }
                                                ?>
                                 <div class="row">
                                    <div class="col-md-2 mb-3">
                                       <div class="travelimp__segmentwrap d-flex align-items-center">
                                          <img src="<?php echo root_url . 'uploads/airline-images/' . $segment['Airline']['AirlineCode'] . '.png' ?>"
                                             width="40px">
                                          <ul class="travelimp__segmentwrap--flightul ml-5">
                                             <li class="travelimp__segmentwrap--flightname"><?php echo $segment['Airline']['AirlineName']; ?></li>
                                             <li class="travelimp__segmentwrap--flightcode"><?php echo $segment['Airline']['AirlineCode']; ?>
                                                -<?php echo $segment['Airline']['FlightNumber']; ?>
                                             </li>
                                             <li class="travelimp__segmentwrap--airportlist">
                                                Fare Class
                                                : <?php echo isset($segment['Airline']['FareClass']) ? $segment['Airline']['FareClass'] : "-"; ?>
                                             </li>
                                             <li class="travelimp__segmentwrap--airportlist">
                                                Craft
                                                : <?php echo $segment['Craft']; ?>
                                             </li>
                                          </ul>
                                       </div>
                                    </div>
                                    <div class="col-md-7 mb-3">
                                       <div class="row">
                                          <div class="col-md-5">
                                             <ul class="travelimp__segmentwrap--airportul travelimp__segmentwrap">
                                                <li class="travelimp__segmentwrap--airportlist"><?php echo get_flight_date_upload_ticket($segment['Origin']['DepartTime']); ?>
                                                   , <?php echo get_flight_time_upload_ticket($segment['Origin']['DepartTime']); ?>
                                                </li>
                                                <li class="travelimp__segmentwrap--airportname"><?php echo $segment['Origin']['CityName']; ?>
                                                   , <?php echo $segment['Origin']['CountryName']; ?> 
                                                </li>
                                                <li class="travelimp__segmentwrap--airportname"><?php echo $segment['Origin']['AirportName']; ?></li>
                                                <?php if ($segment['Origin']['Terminal'] != "") { ?>
                                                <li class="travelimp__segmentwrap--airportname">
                                                   <span class="travelimp__segmentwrap--terminaltext">Terminal : </span>Terminal <?php echo $segment['Origin']['Terminal']; ?>
                                                </li>
                                                <?php } ?>
                                             </ul>
                                          </div>
                                          <div class="col-md-2">
                                             <ul class="travelimp__segmentwrap--stoparrowul travelimp__segmentwrap">
                                                <li class="travelimp__segmentwrap--stoparrowlist">
                                                   Non-Stop
                                                </li>
                                                <li class="travelimp__segmentwrap--airportlist"><?php echo get_convertToHoursMinsfromMinDuration_upload_ticket($segment['Duration']); ?></li>
                                             </ul>
                                          </div>
                                          <div class="col-md-5">
                                             <ul class="travelimp__segmentwrap--airportul travelimp__segmentwrap">
                                                <li class="travelimp__segmentwrap--airportlist"><?php echo get_flight_date_upload_ticket($segment['Destination']['ArrivalTime']); ?>
                                                   , <?php echo get_flight_time_upload_ticket($segment['Destination']['ArrivalTime']); ?>
                                                </li>
                                                <li class="travelimp__segmentwrap--airportname"><?php echo $segment['Destination']['CityName']; ?>
                                                   , <?php echo $segment['Destination']['CountryName']; ?> 
                                                </li>
                                                <li class="travelimp__segmentwrap--airportname"><?php echo $segment['Destination']['AirportName']; ?></li>
                                                <?php if ($segment['Destination']['Terminal'] != "") { ?>
                                                <li class="travelimp__segmentwrap--airportname">
                                                   <span class="travelimp__segmentwrap--terminaltext">Terminal : </span>Terminal <?php echo $segment['Destination']['Terminal']; ?>
                                                </li>
                                                <?php } ?>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                       <ul class="travelimp__segmentwrap--airportul travelimp__segmentwrap">
                                          <li class="travelimp__segmentwrap--airportname"><?php echo $segment['CabinClass']; ?>
                                             , <?php if ($bookingDetail['is_refundable']) { ?>
                                             Refundable
                                             <?php } else { ?>
                                             Non Refundable
                                             <?php } ?>
                                          </li>
                                       </ul>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                       <div class="travelimp__segmentwrap--baggageInfo">
                                          <b class="">Baggage Information</b>
                                          <span class=""> <b> Adult - </b>
                                          <span class="travelimp__segmentwrap--baggagecheckin">Check-in : <?php echo $segment['CheckInBaggage']; ?>, Cabin : <?php echo $segment['CabinBaggage']; ?></span> 
                                          </span>
                                          <?php if (isset($bookingDetail['childCount']) && $bookingDetail['childCount']) { ?>
                                          | <span class=""><b>Child - </b>
                                          <span class="travelimp__segmentwrap--baggagecheckin">Check-in : <?php echo $segment['CheckInBaggage']; ?>, Cabin : <?php echo $segment['CabinBaggage']; ?></span> 
                                          </span>
                                          <?php } ?>
                                          <?php if (isset($bookingDetail['infantCount']) && $bookingDetail['infantCount']) { ?>
                                          | <span class=""><b>Infant -</b>
                                          <span class="travelimp__segmentwrap--baggagecheckin">Cabin : <?php echo $segment['CabinBaggage']; ?></span>
                                          </span>
                                          <?php } ?>
                                       </div>
                                    </div>
                                 </div>
                                 <?php }
                                    }
                                    }
                                    }  ?>
                                 <div class="amend_details-passengers--list">
                                    <?php if (isset($bookingDetail['passenger_detail'])) {
                                       $travelersInfo = $bookingDetail['passenger_detail'];
                                       
                                       foreach ($travelersInfo as $paxkey => $traveler) {
                                          if($bookingDetail['bussiness_type'] == "B2B"){
                                             $fare = json_decode($traveler['agent_fare'], true);
                                          }else{
                                             $fare = json_decode($traveler['customer_fare'],true);
                                          }
                                       
                                           $paxTotalprice = 0;
                                       
                                           ?>
                                    <div class="row amend_details-passengers_bg">
                                       <div class="col-md-3">
                                          <div class="amend_passenger_details">
                                             <span>Last Name/First Name Title</span>
                                             <div class="person-name d-flex align-items-center justify-content-between">
                                                <span class=""><?php echo $paxkey + 1; ?>. <?php echo $traveler['last_name']; ?>/<?php echo $traveler['first_name']; ?> <?php echo $traveler['title']; ?>. (<?php echo $traveler['pax_type']; ?>)</span>
                                             </div>
                                             <div class="row">
                                                <?php if ($traveler['date_of_birth'] != NULL) { ?>
                                                <div class="col-md-12">
                                                   <span class="sm_font amendment_leftpad"> <b>DOB : </b><span
                                                      class="bold"><?php echo $traveler['date_of_birth'] != "" ? display_custom_date_format($traveler['date_of_birth'], false) : "-"; ?></span></span>
                                                </div>
                                                <?php } ?>
                                                <?php if ($traveler['pan_number'] != NULL) { ?>
                                                <div class="col-md-12">
                                                   <span class="sm_font amendment_leftpad"><b>Pan Number :</b> <span
                                                      class="bold"><?php echo $traveler['pan_number']; ?></span></span>
                                                </div>
                                                <?php } ?>
                                                <?php if ($traveler['passport_number'] != NULL) { ?>
                                                <div class="col-md-12">
                                                   <span class="sm_font amendment_leftpad"><b>Passport Number :</b> <span
                                                      class="bold"><?php echo $traveler['passport_number']; ?></span></span>
                                                </div>
                                                <?php } ?>
                                                <?php if ($traveler['passport_expiry'] != NULL) { ?>
                                                <div class="col-md-12">
                                                   <span class="sm_font amendment_leftpad"> <b>Passport Expiry :</b> <span
                                                      class="bold"><?php echo $traveler['passport_expiry'] != "" ? display_custom_date_format($traveler['passport_expiry'], false) : "-"; ?></span></span>
                                                </div>
                                                <?php } ?>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col-md-9 passenger_faredetail">
                                          <div class="row">
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">Base Fare</p>
                                                <p class="price-width-left">
                                                   ₹ <?php echo $fare['BaseFare'];
                                                      $paxTotalprice = $paxTotalprice + $fare['BaseFare']; ?>
                                                </p>
                                             </div>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">Taxes</p>
                                                <p class="price-width-left">
                                                   ₹ <?php echo $fare['Tax'];
                                                      $paxTotalprice = $paxTotalprice + $fare['Tax']; ?>
                                                </p>
                                             </div>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">YQ Tax</p>
                                                <p class="price-width-left">
                                                   ₹ <?php echo $fare['YQTax']; ?>
                                                </p>
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
                                                <p class="m0">Service Charges</p>
                                                <p class="price-width-left">
                                                   ₹ <?php echo $fare['ServiceCharges']; $paxTotalprice = $paxTotalprice + $fare['ServiceCharges'];?>
                                                </p>
                                             </div>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">Other Charges</p>
                                                <p class="price-width-left">
                                                   ₹ <?php echo $OtherCharges; ?>
                                                </p>
                                             </div>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">Agent Commission</p>
                                                <p class="price-width-left">
                                                   ₹ <?php echo $AgentCommission; ?>
                                                </p>
                                             </div>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">Discount</p>
                                                <p class="price-width-left">
                                                   ₹ <?php echo $Discount; ?>
                                                </p>
                                             </div>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">GST Amount</p>
                                                <p class="price-width-left">
                                                   ₹ <?php echo $GSTAmount; ?>
                                                </p>
                                             </div>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">TDS</p>
                                                <p class="price-width-left">
                                                   ₹ <?php echo $TDS; ?>
                                                </p>
                                             </div>
                                             <?php if ($fare['BaggageCharges']) { ?>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">Baggage Charges</p>
                                                <p class="price-width-left">
                                                   ₹ <?php echo $fare['BaggageCharges'];
                                                      $paxTotalprice = $paxTotalprice + $fare['BaggageCharges']; ?>
                                                </p>
                                             </div>
                                             <?php } ?>
                                             <?php if ($fare['MealCharges']) { ?>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">Meal Charges</p>
                                                <p class="price-width-left">
                                                   ₹ <?php echo $fare['MealCharges'];
                                                      $paxTotalprice = $paxTotalprice + $fare['MealCharges']; ?>
                                                </p>
                                             </div>
                                             <?php } ?>
                                             <?php if ($fare['SeatCharges']) { ?>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">Seat Charges</p>
                                                <p class="price-width-left">
                                                   ₹ <?php echo $fare['SeatCharges'];
                                                      $paxTotalprice = $paxTotalprice + $fare['SeatCharges']; ?>
                                                </p>
                                             </div>
                                             <?php } ?>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0"><b>Total Amount</b></p>
                                                <p class="price-width-left">
                                                   <b> ₹ <?php echo $paxTotalprice; ?> </b>
                                                </p>
                                             </div>
                                             <?php if ($bookingDetail['pnr'] != Null) { ?>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0"> PNR</p>
                                                <p class="price-width-left"> <?php echo $bookingDetail['pnr']; ?></p>
                                             </div>
                                             <?php } ?>
                                             <?php if ($bookingDetail['pnr'] != Null) { ?>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">Airline PNR</p>
                                                <p class="price-width-left"> <?php
                                                   echo $gdsPnrs = getGdsPnrUploadTicket($gdsPnr); ?></p>
                                             </div>
                                             <?php } ?>
                                             <?php if ($traveler['ticket_number'] != Null) { ?>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">Ticket Number</p>
                                                <p class="price-width-left"><?php echo $traveler['ticket_number']; ?></p>
                                             </div>
                                             <?php } ?>
                                             <?php if ($traveler['baggage'] != Null) { ?>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">Baggage</p>
                                                <p class="price-width-left"><?php echo $traveler['baggage']; ?></p>
                                             </div>
                                             <?php } ?>
                                             <?php if ($traveler['meal'] != Null) { ?>
                                             <div class="col-md-2 amendment_leftpad">
                                                <p class="m0">Meal</p>
                                                <p class="price-width-left"><?php echo $traveler['meal']; ?></p>
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
                        <div class="accordion-item">
                           <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapseSeven" aria-expanded="false"
                              aria-controls="collapseSeven">
                           <span class="acordian_heading">Fare Breakup : </span>
                           </button>
                           <div id="collapseSeven" class="accordion-collapse collapse show"
                              aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                              <div class="accordion-body cart-details-borderline">
                                 <?php $FareBreakUp = $bookingDetail['FareBreakUp'];
                                    if ($FareBreakUp) { ?>
                                 <div class="table-responsive">
                                    <table class="table table-bordered">
                                       <tr>
                                          <th scope="row"><?php echo $FareBreakUp['WebPMarkUp']['LabelText']; ?> :</th>
                                          <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo ($FareBreakUp['WebPMarkUp']['Value']); ?></td>
                                       </tr>
                                    </table>
                                 </div>
                                 <div class="table-responsive">
                                    <table class="table table-bordered">
                                       <?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
                                       <tr>
                                          <th scope="row"><?php echo $fare['LabelText']; ?> : </th>
                                          <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo ($fare['Value']); ?></td>
                                       </tr>
                                       <?php } ?>
                                       <tr>
                                          <th scope="row"><?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>
                                             :
                                          </th>
                                          <td>
                                             <i class="fa fa-inr" aria-hidden="true"></i> <?php echo ($FareBreakUp['TotalAmount']['Value']); ?>
                                          </td>
                                       </tr>
                                    </table>
                                 </div>
                                 <?php if (isset($FareBreakUp['GSTDetails']) && $FareBreakUp['GSTDetails']) { ?>
                                 <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                       <thead class="table-active">
                                          <tr>
                                             <th>
                                                Service Description
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
                                       </thead>
                                       <tr>
                                          <th>Service Charges</th>
                                          <th> <?php echo ($FareBreakUp['GSTDetails']['TaxableAmount']); ?></th>
                                          <th> <?php echo ($FareBreakUp['GSTDetails']['CGSTAmount']); ?></th>
                                          <th> <?php echo ($FareBreakUp['GSTDetails']['SGSTAmount']); ?></th>
                                          <th> <?php echo ($FareBreakUp['GSTDetails']['IGSTAmount']); ?></th>
                                          <th> <?php echo ($FareBreakUp['GSTDetails']['CGSTAmount'] + $FareBreakUp['GSTDetails']['SGSTAmount'] + $FareBreakUp['GSTDetails']['IGSTAmount']); ?></th>
                                       </tr>
                                    </table>
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
         <div class="row">
            <div class="col-md-6">
               <a class="btn btn-primary" href  =  "<?php echo  site_url('flight-ticket-upload/segment-passenger-detail?segmentinfokey='.$SegmentInfokey) ?>"> Previous</a>
            </div>
            <?php if($showSaveButton) { ?>
            <div class="col-md-6 text-end">
               <a class="btn btn-primary" href  =  "<?php echo  site_url('flight-ticket-upload/generate-ticket?segmentinfokey='.$SegmentInfokey) ?>"> Save</a>
            </div>
            <?php } ?>
         </div>
      </section>
   </div>
</div>