<!---------------flight--list-----end---------->
<section class="flight_list_asp-btm" ng-if="ErrorCode==0 && loading==false">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 d-flex align-items-center justify-content-between" ng-repeat="(segmentkey,trip) in selectedOnwardFlight['SegementInfo']">
                <div class="ars-listair d-flex align-items-center">
                    <!-- <span class="airline-logo {{airlineLogoClass}} size-28 x{{trip.Airlinecode}}"><span> -->
                    <img src="<?php echo root_url; ?>uploads/airline-images/{{trip.Airlinecode}}.png" alt="{{trip.AirlineName}}" class="airline-logo">
                    <div class="asp-totalam select-flightsname"> {{trip.AirlineName}}
                        <span class="bottom-gridcode ">{{trip.AirlineCodeFlightNumberString}}</span>
                    </div>
                </div>
                <ul class="ars-list">
                    <li>
                        <p>{{trip.DepartTime}}</p>
                        <span class="graycolor">{{trip.DepartureCity}}</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </li>
                    <li>
                        <p> {{trip.ArrivalTime}} </p>
                        <span class="graycolor">{{trip.ArrivalCity}}</span>
                    </li>
                </ul>
                <div>
                    <h5 class="whitecolor">₹ {{selectedOnwardFlight['FareInfo']['Fare']['PublishedPrice']}}</h5>
                </div>
            </div>
            <div class="col-sm-4 d-flex align-items-center justify-content-between" ng-repeat="(segmentkey,trip) in selectedReturnFlight['SegementInfo']">
                <div class="ars-listair d-flex align-items-center">
                    <img src="<?php echo root_url; ?>uploads/airline-images/{{trip.Airlinecode}}.png" alt="{{trip.AirlineName}}" class="airline-logo">
                    <div class="asp-totalam select-flightsname"> {{trip.AirlineName}}<span class="bottom-gridcode ">{{trip.AirlineCodeFlightNumberString}}</span></div>
                </div>
                <ul class="ars-list">
                    <li>
                        <p>{{trip.DepartTime}}</p>
                        <span class="graycolor">{{trip.DepartureCity}}</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </li>
                    <li>
                        <p> {{trip.ArrivalTime}} </p>
                        <span class="graycolor">{{trip.ArrivalCity}}</span>
                    </li>
                </ul>
                <div>
                    <h5 class="whitecolor">₹ {{selectedReturnFlight['FareInfo']['Fare']['PublishedPrice']}}</h5>
                </div>
            </div>
            <div class="col-sm-4 d-flex align-items-center justify-content-end">
                <ul class="ars-list">
                    <li>
                        <h5 class="mb-0">₹
                            {{(selectedOnwardFlight['FareInfo']['Fare']['PublishedPrice']+selectedReturnFlight['FareInfo']['Fare']['PublishedPrice']).toFixed(2)}}
                        </h5>
                    </li>
                    <li>
                        <button type="button" class="btn round_trip_book" ng-click="getConfirmationInfo('confirm')">BOOK</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>