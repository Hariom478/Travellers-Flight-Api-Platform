<div class="row  flight-detail-IB" id="id-IB-{{flightitem.TtsIndex}}" style="display:none;">
    <div class="col-12 col-md-12" ng-if="ErrorCode==0 && FlightReturnSegment.length>0">
        <div class="flight-list-tab">
            <nav class="d-flex align-items-center justify-content-between border-bottom">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active flight-list-tab-btn"
                            id="nav-flightDeatil_IB_{{flightitem.TtsIndex}}-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-flightDeatil_IB_{{flightitem.TtsIndex}}" type="button" role="tab"
                            aria-controls="nav-flightDeatil_IB_{{flightitem.TtsIndex}}" aria-selected="true">Flight
                        Details
                    </button>
                    <button class="nav-link flight-list-tab-btn" id="nav-fareInfo_IB_{{flightitem.TtsIndex}}-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-fareInfo_IB_{{flightitem.TtsIndex}}" type="button"
                            role="tab" aria-controls="nav-fareInfo_IB_{{flightitem.TtsIndex}}" aria-selected="false"
                            tabindex="-1">Fare Details
                    </button>
                    <?php /* if(0) { */ ?>
                    <button class="nav-link flight-list-tab-btn" id="nav-farerule_IB_{{flightitem.TtsIndex}}-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-farerule_IB_{{flightitem.TtsIndex}}" type="button"
                            role="tab" aria-controls="nav-farerule_IB_{{flightitem.TtsIndex}}" aria-selected="false"
                            tabindex="-1">Fare Rules
                    </button>
                    <?php /* } */ ?>
                    <button class="nav-link flight-list-tab-btn" id="nav-baggageinfo_IB_{{flightitem.TtsIndex}}-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-baggageinfo_IB_{{flightitem.TtsIndex}}"
                            type="button" role="tab" aria-controls="nav-baggageinfo_IB_{{flightitem.TtsIndex}}"
                            aria-selected="false" tabindex="-1">Baggage
                    </button>

                </div>
                <div class="">
                    <button type="button" class="btn cross-btn" ng-click="hidFlightDetail(flightitem.TtsIndex,'IB')"><i
                                class="fas fa-times"></i></button>
                </div>
            </nav>
            <div class="tab-content flight-list-tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-flightDeatil_IB_{{flightitem.TtsIndex}}" role="tabpanel"
                     aria-labelledby="nav-flightDeatil_IB_{{flightitem.TtsIndex}}-tab" tabindex="0">
                    <div class="row align-items-center"
                         ng-repeat="(tripIndicatorkey,tripSegment) in FlightReturnSegment">
                        <p class="flight-details-top-list">
                            <b class="">
                                <span>{{tripSegment[0].Origin.CityName}}</span>
                                <span class="ars-arright">→</span>
                                <span>{{tripSegment[tripSegment.length-1].Destination.CityName}}</span>
                            </b>
                            <span class="graycolor"> {{tripSegment[0].Origin.DepartTime|date: 'EEE, MMM d y'}}</span>
                        </p>
                        <div class="col-md-12" ng-repeat="(segmentindicatorkey,segment) in tripSegment">
                            <div class="row">
                                <div class="col-sm-3 pr-0">
                                    <ul class="flight-listair1 d-flex">
                                        <li>
                                           
                                            <img src="<?php echo root_url; ?>uploads/airline-images/{{segment.Airline.AirlineCode}}.png" alt="{{segment.Airline.AirlineName}}" class="airline-logo">
                                        </li>
                                        <li>
                                            <div class="flight-holdid">
                                                <span class="at-fontweight arct-idcode">{{segment.Airline.AirlineCode}}-{{segment.Airline.FlightNumber}}</span><span
                                                        class="equipType" ng-if="segment.Craft"><i
                                                            class="fa fa-plane"></i>-{{segment.Craft}}</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="flight-listair1">
                                                <span>{{segment.Origin.DepartTime|date: 'MMM d, EEE, HH:mm'}}</span>
                                                <br><span class="at-fontweight atb-airport graycolor">{{segment.Origin.CityName}}, {{segment.Origin.CountryName}}</span>
                                                <span class="at-fontweight atb-airport graycolor"> {{segment.Origin.AirportName}}</span>
                                                <span class="graycolor" ng-if="segment.Origin.Terminal!=''">Terminal  {{segment.Origin.Terminal}}</span>

                                            </div>
                                        </div>
                                        <div class="col-sm-2 text-center">

                                            <span class="ars-lsprice ars-prclist atb-iconclass abt-nnstop stop-arrowline">Non-Stop</span>
                                            <span class="fa fa-long-arrow-right d-block "></span>

                                        </div>
                                        <div class="col-sm-5">
                                            <div class="flight-listair1 text-end">
                                                <span> {{segment.Destination.ArrivalTime|date: 'MMM d, EEE, HH:mm'}}</span>
                                                <br><span class="at-fontweight atb-airport graycolor">{{segment.Destination.CityName}}, {{segment.Destination.CountryName}}</span>
                                                <span class="at-fontweight atb-airport graycolor"> {{segment.Destination.AirportName}}</span>
                                                <span class="graycolor" ng-if="segment.Destination.Terminal!=''">Terminal  {{segment.Destination.Terminal}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-end flight-listair1">
                                    <span> {{segment.Duration|changeDurationHourMinFormat}}</span><br>
                                    <span class="at-fontweight atb-airport graycolor"
                                          ng-if="FlightReturnFareDetail.CabinClass">{{FlightReturnFareDetail.CabinClass}}</span>
                                    <span class=""
                                          ng-if="FlightReturnFareDetail.SeatBaggage[tripIndicatorkey][segmentindicatorkey].NoOfSeatAvailable!=''"> {{FlightReturnFareDetail.SeatBaggage[tripIndicatorkey][segmentindicatorkey].NoOfSeatAvailable}} seat(s) left</span>
                                          <br/><span class=""
                                          ng-if="segment.Airline.FareClass && segment.Airline.FareClass!=''">Fare Class - {{segment.Airline.FareClass}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-fareInfo_IB_{{flightitem.TtsIndex}}" role="tabpanel"
                     aria-labelledby="nav-fareInfo_IB_{{flightitem.TtsIndex}}-tab" tabindex="0">
                    <div class="row">
                        <div class="col-sm-3">
                            <h5 class="flight-typefare at-fontweight">
                                TYPE
                            </h5>


                        </div>
                        <div class="col-sm-4">
                            <h5 class="flight-typefare at-fontweight">
                                Fare
                            </h5>

                        </div>
                        <div class="col-sm-5">
                            <h5 class="flight-typefare at-fontweight">Total</h5>
                        </div>
                    </div>
                    <div class="row" ng-repeat="(paxFarekey,paxFare) in  FlightReturnFareDetail.FareBreakdown">
                        <span class="graycolor fare-details-text-label">Fare Details for {{paxFarekey|paxtype}}</span>
                        <div class="col-sm-3">
                            <ul class="ars-trasfee">
                                <li class="list-fare-ddetials-content"><span>Base Price</span></li>
                                <li class="list-fare-ddetials-content"><span>Taxes and fees</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-4">

                            <ul class="ars-trasfee">
                                <li class="list-fare-ddetials-content"><span>₹ {{paxFare.BaseFare/paxFare.PassengerCount}} x {{paxFare.PassengerCount}}</span>
                                </li>
                                <li class="list-fare-ddetials-content"><span>₹ {{paxFare.Tax/paxFare.PassengerCount}} x {{paxFare.PassengerCount}}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-5">

                            <ul class="ars-trasfee">
                                <li class="list-fare-ddetials-content"><span>₹ {{paxFare.BaseFare}}</span></li>
                                <li class="list-fare-ddetials-content"><span>₹ {{paxFare.Tax}}</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <span class="graycolor fare-details-text-label"></span>
                        <div class="col-sm-3">
                            <ul class="ars-trasfee">
                                <li class="list-fare-ddetials-content"><span>Service and Other Charges</span></li>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-4">

                            <ul class="ars-trasfee">
                                <li class="list-fare-ddetials-content"><span>₹ {{FlightReturnFareDetail.Fare.OtherCharges+FlightReturnFareDetail.Fare.ServiceCharges}} </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-5">

                            <ul class="ars-trasfee">
                                <li class="list-fare-ddetials-content"><span>₹ {{FlightReturnFareDetail.Fare.OtherCharges+FlightReturnFareDetail.Fare.ServiceCharges}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <span class="graycolor fare-details-text-label"></span>
                        <div class="col-sm-3">
                            <ul class="ars-trasfee">
                                <li class="list-fare-ddetials-content"><span>GST</span></li>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-4">

                            <ul class="ars-trasfee">
                                <li class="list-fare-ddetials-content"><span>₹ {{FlightReturnFareDetail.Fare.GST.CGSTAmount+FlightReturnFareDetail.Fare.GST.SGSTAmount+FlightReturnFareDetail.Fare.GST.IGSTAmount}} </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-5">

                            <ul class="ars-trasfee">
                                <li class="list-fare-ddetials-content"><span>₹ {{FlightReturnFareDetail.Fare.GST.CGSTAmount+FlightReturnFareDetail.Fare.GST.SGSTAmount+FlightReturnFareDetail.Fare.GST.IGSTAmount}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row border-top">
                        <div class="col-sm-7 mt-2">
                            <h4>Total</h4>
                        </div>
                        <div class="col-sm-5 mt-2">
                            <h4>₹ {{FlightReturnFareDetail.Fare.PublishedPrice}}</h4>
                        </div>
                    </div>
                </div>
                <?php /* if(0) { */ ?>
                <div class="tab-pane fade" id="nav-farerule_IB_{{flightitem.TtsIndex}}" role="tabpanel"
                     aria-labelledby="nav-farerule_IB_{{flightitem.TtsIndex}}-tab" tabindex="0">
                    <div class="row" ng-if="fareRuleReturnLoading==true">
                        <div class="col-md-12">
                            <div class="text-center">
                                <div class="loader mt-3 mb-3">
                                    <div role="status" class="spinner-grow text-primary">
                                        <span class="sr-only">Loading...</span></div>
                                    <div role="status" class="spinner-grow text-secondary">
                                        <span class="sr-only">Loading...</span></div>
                                    <div role="status" class="spinner-grow text-danger">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div role="status" class="spinner-grow text-dark">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <h5> Please Wait... </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row" ng-if="fareRuleReturnLoading==false && FareReturnRuleErrorCode==0"
                         ng-repeat="(farerulekey,farerule) in  FlightReturnFareRule">
                        <div class="col-md-12">
                            <button class="ars-activelist fare-rules-tabs">
                                {{farerule.Origin}}-{{farerule.Destination}}
                            </button>
                        </div>
                        <div class="col-md-12 fare-rule-content"
                             id="fareRuleDataIB{{flightitem.TtsIndex+farerule.Origin+farerule.Destination+farerulekey}}">
                            {{farerule.FareRuleDetail|html_filter:"fareRuleDataIB"++flightitem.TtsIndex+farerule.Origin+farerule.Destination+farerulekey}}
                        </div>
                    </div>
                    <div class="row" ng-if="fareRuleReturnLoading==false && FareReturnRuleErrorCode!=0">
                        <div class="col-md-12">
                            {{fareruleerrormessage}}
                        </div>
                    </div>
                </div>
                <?php /* } */ ?>
                <div class="tab-pane fade" id="nav-baggageinfo_IB_{{flightitem.TtsIndex}}" role="tabpanel"
                     aria-labelledby="nav-baggageinfo_IB_{{flightitem.TtsIndex}}-tab" tabindex="0">
                    <div class="row">
                        <div class="col-sm-3">
                            <h5 class="flight-typefare at-fontweight">
                                SECTOR
                            </h5>


                        </div>
                        <div class="col-sm-4">
                            <h5 class="flight-typefare at-fontweight">
                                CHECKIN
                            </h5>

                        </div>
                        <div class="col-sm-5">
                            <h5 class="flight-typefare at-fontweight">
                                CABIN
                            </h5>
                        </div>
                    </div>
                    <div class="baggage__data" ng-repeat="(tripBaggagekey,tripBaggageInfo)  in FlightReturnBaggageInfo">
                        <div class="row" ng-repeat="(segmentBaggagekey,BaggageInfo) in tripBaggageInfo">
                            <div class="col-sm-3">
                                <ul class="ars-trasfee">
                                    <li class="list-fare-ddetials-content"><span>{{BaggageInfo.Sector}}</span></li>

                                </ul>
                            </div>
                            <div class="col-sm-4">
                                <ul class="ars-trasfee">
                                    <li class="list-fare-ddetials-content"><span>{{BaggageInfo.CheckIn}}</span></li>

                                </ul>
                            </div>
                            <div class="col-sm-5">
                                <ul class="ars-trasfee">
                                    <li class="list-fare-ddetials-content"><span>{{BaggageInfo.Cabin}}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>