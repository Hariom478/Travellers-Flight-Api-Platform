<style>
   .segment_body {
   display: flex;
   display: -webkit-flex;
   display: -moz-flex;
   align-items: center;
   -webkit-align-items: center;
   -moz-align-items: center;
   padding: 10px;
   background-color: #f4f7f8;
   font-size: 13px;
   flex-wrap: wrap;
   box-shadow: 0 1px 4px 0 rgb(0 0 0 / 14%);
   border-radius: 6px;
   margin-bottom: 5px;
   }
   .segment_header-top {
   background: #fff;
   color: #004684;
   padding: 12px 10px;
   display: flex;
   display: -webkit-flex;
   display: -moz-flex;
   align-items: center;
   -webkit-align-items: center;
   -moz-align-items: center;
   font-size: 13px;
   font-weight: 500;
   border-radius: 6px 6px 0 0;
   }
   .passenger_list div {
   padding-right: 0;
   }
   .passenger_list-details {
   display: flex;
   flex-direction: column;
   display: -webkit-flex;
   display: -moz-flex;
   align-items: center;
   -webkit-align-items: center;
   -moz-align-items: center;
   padding-top: 5px;
   background: #fff;
   box-shadow: 0 1px 4px 0 rgb(0 0 0 / 14%);
   border-radius: 6px;
   margin: 8px 0;
   min-height: 60px;
   }
   .al-label:hover .al-indiv {
   background: #fff;
   }
   .passenger_list-details-erormsg .al-label .al-indiv {
   width: 16px;
   height: 16px;
   line-height: 15px;
   }
   .al-label i {
   font-size: 14px;
   opacity: 0;
   }
   .fa-check:before {
   content: "\f00c";
   }
   .arrow_right-sm {
   height: 2px;
   width: 100%;
   background: #004684;
  
   position: relative;
   }
   .arrow_right-sm:before {
   content: '';
   position: absolute;
   height: 8px;
   top: -6px;
   width: 2px;
   background: #004684;
   right: 3px;
   transform: rotate(135deg);
   }
   .arrow_right-sm:after {
   content: '';
   position: absolute;
   height: 8px;
   top: 0;
   width: 2px;
   background: #004684;
   right: 3px;
   transform: rotate(45deg);
   } 
   .segment_header-top .margin-left {
   margin-left: 0.3%;
   }
   .cart_info-field{
      font-size: 13px;
   }
   .cart_info-field--title{
      font-weight: 600;
   }
   .cart_info-field--detail{
      font-weight: normal;
   }
</style>
<div class="page-content">
<div class="table_title">
   <section class="cart_information p-0">
      <div class="container-fluid p-0">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m-0"> Amendment  Details (<?php echo $bookingDetail['booking_ref_number']; ?>)</h5>
               </div>
               <div class="col-md-8 text-md-end">
                  <a class="badge badge-wt" href="<?php echo site_url('/flight/confirmation/') . $ticketData = dev_encode(json_encode(array($bookingDetail['flightbookingid']))); ?>" target  =  "_blank">Booking Summary</a>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12 col-12 col-lg-12">
               <div class="cart_info">
                  <div class  =  "sale_bar">
                     <div class="cart-details-borderline">
                        <div class="row">
                           <div class="row">
                              <div class="col-md-4 col-xs-6 col-6">
                                 <div class="cart_info-field">
                                    <p class="cart_info-field--title m-0">Booking Ref Number :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['booking_ref_number']; ?></span></span>
                                    </p>
                                 </div>
                              </div>
                              <div class="col-md-4 col-xs-6 col-6">
                                 <div class="cart_info-field">
                                    <p class="cart_info-field--title m-0">Amendment Type :<span class="cart_info-field--detail"><span> &nbsp;<?php echo ucfirst(str_replace("_"," ",$bookingDetail['amendment_type'])); ?></span></span>
                                    </p>
                                 </div>
                              </div>
                              <?php if ($bookingDetail['pnr'] != "") { ?>
                              <div class="col-md-4 col-xs-6 col-6">
                                 <div class="cart_info-field">
                                    <p class="cart_info-field--title m-0">PNR :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['pnr']; ?></span></span>
                                    </p>
                                 </div>
                              </div>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div  class  =  "sale_bar">
                     <div class="cart-details-borderline">
                        <?php if (isset($bookingDetail['segments'])) {
                           $tripInfo = json_decode($bookingDetail['segments'], true);
                           foreach ($tripInfo as $key => $trips) {
                           	if ($trips) { 
                           		$firstSegment  =  reset($trips);
                           		$lastegment  =  end($trips);
                           		?>
                        <div class="row segment_header-top">
                           <div class  =  "col-md-12"> <span class=""><?php echo $firstSegment['Origin']['CityName']  ?></span>-><span><?php echo $lastegment['Destination']['CityName']  ?></span><span class="margin-left">on <?php echo get_flight_date($firstSegment['Origin']['DepartTime']); ?></span></div>
                        </div>
                        <?php 	foreach ($trips as $segmentIndicatorkey => $segment) { ?>
                        <div class="row segment_body m-0">
                           <div class="col-md-2">
                              <div class="segment_body-airlogo">
                                 <span class="airline-logo <?php echo $bookingDetail['airlineLogoClass']; ?> size-28 x<?php echo $segment['Airline']['AirlineCode']; ?>"></span>
                                 <p class="mb-0"><?php echo $segment['Airline']['AirlineName']; ?>
                                    <span class="airline-code"><?php echo $segment['Airline']['AirlineCode']; ?> -<?php echo $segment['Airline']['FlightNumber']; ?></span>
                                 </p>
                              </div>
                           </div>
                           <div class="col-md-4 segment_body-flight-info text-center">
                              <p class="mb-0"><?php echo $segment['Origin']['CityName']; ?>
                                 <span class="air_sourcr-none"><?php echo $segment['Origin']['CountryName']; ?> (<?php echo $segment['Origin']['AirportName']; ?>) - <?php echo $segment['Origin']['CityCode']; ?></span>
                              </p>
                              <p class="mb-0"><?php echo get_flight_date($segment['Origin']['DepartTime']); ?>, <?php echo get_flight_time($segment['Origin']['DepartTime']); ?></p>
                           </div>
                           <div class="col-md-2 segment_body-flight-stop text-center">
                              <span class="via-city-codes">Non-Stop</span>
                              <div class="arrow_right-sm"></div>
                           </div>
                           <div class="col-md-4 segment_body-flight-info text-center">
                              <p class="mb-0"><?php echo $segment['Destination']['CityName']; ?>
                                 <span class="air_sourcr-none"><?php echo $segment['Destination']['CountryName']; ?> (<?php echo $segment['Destination']['AirportName']; ?>) - <?php echo $segment['Destination']['CityCode']; ?></span>
                              </p>
                              <p class="mb-0"><?php echo get_flight_date($segment['Destination']['ArrivalTime']); ?>, <?php echo get_flight_time($segment['Destination']['ArrivalTime']); ?></p>
                           </div>
                           <?php }
                              }
                              }
                              } ?>
                        </div>
                     </div>
                     <?php  if (isset($bookingDetail['travelersInfo'])) {
                        $travelersInfo = json_decode($bookingDetail['travelersInfo'], true);
                        ?>
                     <div  class  =  "sale_bar">
                        <div class="passenger_list">
                           <div class="row">
                              <?php  
                                 $paxid  =  json_decode($bookingDetail['request'],true)['PaxId'];
                                 foreach ($travelersInfo as $paxkey => $traveler) { 
                                 	if(in_array($traveler['id'],$paxid)){
                                 	?>
                              <div class="col-md-4 col-xs-6 col-6">
                                 <div class="passenger_list-details">
                                    <div class="passenger_list-details-fix-box">
                                       <div class="hidden">
                                          <p class="pax-name no_margin m-0"> <?php echo $traveler['title']." ".$traveler['first_name']." ".$traveler['last_name']; ?>  (<?php echo $traveler['pax_type'] ?>)<span class="tooltip-keys"></span></p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <?php  } } ?>
                           </div>
                        </div>
                     </div>
                     <div class  =  "sale_bar">
                        <div class =  "row">
                           <div class  =  "col-md-12">
                              <label class="col-form-label">Remark</label>
                              <textarea class  =  "form-control"  name  =  "remark" data-validation  =  "required"  data-validation-error-msg-required="Please enter remark" > <?php echo  $bookingDetail['remark_from_user'] ?></textarea>
                           </div>
                        </div>
                     </div>
                     <?php }  ?>
                  </div>
               </div>
            </div>
         </div>
   </section>
   </div>
</div>