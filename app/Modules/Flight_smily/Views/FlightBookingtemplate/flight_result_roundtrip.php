<!-- flight oneway result page starts here -->
<style>
    .flight__dropdown__icon {
        content: '';
        display: block;
        position: relative;
        width: 23px;
        z-index: 1;
        border-radius: 50%;
        top: 20px;
        left: 60%;
        height: 23px;
        background-color: #5a5a5a;
        transform: rotate(45deg);
        transition: transform 0.2s linear;
        cursor: pointer;
    }

    .flight__dropdown__icon:after {
        content: '';
        display: block;
        float: none;
        position: absolute;
        top: 5px;
        left: 5px;
        width: 0;
        border: solid #fff;
        border-width: 0 2px 2px 0;
        padding: 4px;
        height: 0;
    }

    .flight__dropdown__icon--selected {
        transform: rotate(-135deg);
    }

    /*============================*/
    .round-trip .flight-dur-timefont {
        font-size: 10px;
    }

    .round-trip .form-check .form-check-input {
        float: right;
        margin-left: 0;
    }

    .ars-lastre {
        font-size: 10px !important;
    }

    /*===================flight==========bottom=================*/
    .flight_list_asp-btm {
        background: #000;
        position: sticky;
        bottom: 0;
        width: 100%;
        left: 0;
        z-index: 9;
        padding: 18px 10px 10px;
    }

    .flight_list_asp-btm .selectionBox-flightLogo {
        width: 30px;
        margin-top: 0px;
    }

    .flight_list_asp-btm .select-flightsname {
        text-align: left;
        padding-left: 33px !important;
        color: #fff;
    }

    .flight_list_asp-btm .bottom-gridcode {
        color: #999;
        white-space: nowrap;
        display: block;
    }

    .flight_list_asp-btm .ars-list {
        padding: 0;
        margin: 0;
        list-style: none;
        display: flex;
        align-items: center;

    }

    .flight_list_asp-btm .ars-list li {
        margin-left: 20px;
        color: #fff;
    }

    .flight_list_asp-btm .ars-list li p {
        margin-bottom: 0;
    }

    .flight_list_asp-btm h5.whitecolor {
        color: #fff;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .flight_list_asp-btm .round_trip_book {
        
        padding: 10px 30px;
        font-weight: bold;
        height: auto;
        border-radius: 30px;
        border: 0;
        box-shadow: inset 0px -2px 4px 0 rgb(0 0 0 / 11%);
        background: white;
    color: black;
    }

    .flight_list_asp-btm .round_trip_book:hover {
        background: var(--white);
        color: var(--second-color);
    }

    .flight-radiolist .label-warning {
    font-size: 10px;
    }
    .tts__flight__price {
    font-size: 15px;
    }
    .tts-refundable {
    font-size: 12px;
    }
    .tts-non-refundable {
    font-size: 12px;
    }
    .fs_11{
        font-size:11px;
    }

    /*===================flight==========bottom=======end==========*/
</style>
<main ng-app="flightApp" ng-controller="flightCtrl" scroll>
    <div class="container" ng-if="loading==true">
        <?php echo view('Modules/Flight/Views\FlightBookingtemplate\loading-page.php'); ?>
    </div>
    <div class="tts__flight__result__page py-3 ng-cloak" ng-if="loading==false">
        <div class="container">
            <div class="row">
                <?php echo view('Modules/Flight/Views/FlightBookingtemplate\modify_search.php'); ?>
                <!-- filter starts here -->
                <div class="col-sm-3" ng-if="ErrorCode==0">
                    <?php echo view('Modules/Flight/Views/FlightBookingtemplate\flight_filter.php'); ?>
                </div>
                <!-- filter ends here -->

                <div class="col-sm-9" ng-if="ErrorCode==0">
                    <!-- Roundtrip result  OB starts here -->
                    <div class="tts__show__result p-2">
                        <div class="col-sm-2">
                            <div class="form-check form-switch float-right pt-2">
                                <input class="form-check-input" type="checkbox" role="switch" ng-model="shownetfare" id="net-fare">
                                <label class="form-check-label" for="net-fare">Show Net
                                    Fare</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-sm-12 mt-3">
                                    <div class="tts__show__result p-2">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="tts__showing__result__heading"><b class="fs13">Showing Result {{FilteredResultCount}} of {{TotalResult}} Flights</b>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="tts__showing__price__filter text-end">
                                                    <div class="btn-group dropdown">
                                                        <button type="button" class="btn  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <span class="text-muted mr-2">SORT BY:</span>
                                                            {{topsortingtext}}
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <button class="dropdown-item" type="button" ng-click="orderby('Fare',false,$event)">Price - Low to
                                                                High
                                                            </button>
                                                            <button class="dropdown-item" type="button" ng-click="orderby('Fare',true,$event)">Price - High to
                                                                Low
                                                            </button>
                                                            <button class="dropdown-item" type="button" ng-click="orderby('DepartTime',false,$event)">Depart - Early
                                                            </button>
                                                            <button class="dropdown-item" type="button" ng-click="orderby('DepartTime',true,$event)">Depart - Late
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <div class="tts__sorting__title p-2">
                                        <div class="row justify-content-between">
                                            <div class="col-2">
                                                <div class="tts__airline__name">
                                                    <a href="javascript:void(0);" ng-click="orderby('AirlineName')">Airlines
                                                        <span class="fa" ng-show="field === 'AirlineName'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="tts__airline__name">
                                                    <a href="javascript:void(0);" ng-click="orderby('DepartTime')">Depart
                                                        <span class="fa" ng-show="field === 'DepartTime'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="tts__airline__name">
                                                    <a href="javascript:void(0);" ng-click="orderby('ArrivalTime')">Arrive
                                                        <span class="fa" ng-show="field === 'ArrivalTime'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="tts__airline__name">
                                                    <a href="javascript:void(0);" ng-click="orderby('DurationMin')">Duration
                                                        <span class="fa" ng-show="field === 'DurationMin'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="tts__airline__name">
                                                    <a href="javascript:void(0);" ng-click="orderby('Fare')">Price
                                                        <span class="fa" ng-show="field === 'Fare'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3 flight_result" ng-repeat="(flightkey,flightitem) in Results|orderBy:field:reverse |limitTo:limit">
                                    <div class="flight-rowmain">
                                        <div class="row">
                                            <div class="col-sm-7">
                                                <div class="row align-items-center" ng-repeat="(tripskey,trip) in flightitem.MainSegment">
                                                    <div class="col-md-5">
                                                        <div class="flight-leftresult1">
                                                            <ul class="flight-listair d-flex align-items-center ">
                                                                <li class=""><span class="airline-logo {{airlineLogoClass}} size-28 x{{trip.Airlinecode}}"><span></li>
                                                                <li class="">
                                                                    {{trip.AirlineName}}
                                                                    <div class="flight-holdid" ng-if="trip.AirlineCodeFlightNumberString!=''">
                                                                        <span class="flightids" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{trip.AirlineCodeFlightNumberString}}">{{trip.AirlineCodeFlightNumberString}}</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 col-4 p0">
                                                        <ul class="flight-listair">
                                                            <li class="">
                                                                <p class="flight-city">{{trip.DepartureCity}}</p>
                                                                <span class="flight-timefont">{{trip.DepartTime}}</span> <br><span class="flight-dur-timefont">{{trip.DepartDate}}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-sm-3 col-4 text-center p0 fs_11">
                                                        <div class="atls-holdid"><span class="flight-arrow" ng-if="trip.Stops>0">{{trip.Stops}} Stop(s)</span><span class="flight-arrow" ng-if="trip.Stops==0">Non Stop(s)</span></div>
                                                        <span class="fa fa-long-arrow-right d-block "></span>{{trip.Duration}}
                                                    </div>
                                                    <div class="col-sm-2 col-4 text-end p0">
                                                        <ul class="flight-listair">
                                                            <li class="">
                                                                <p class="flight-city">{{trip.ArrivalCity}}</p>
                                                                <span class="flight-timefont">{{trip.ArrivalTime}}</span> <br><span class=" flight-dur-timefont">{{trip.ArrivalDate}}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-sm-12 clearfix">
                                                        <div class="indicator-content">
                                                            <span class="flightarrives-after" ng-if="trip.ArrivalDays!=0"> <i class="fa fa-plane flightarrive-icons"></i> Flight Arrives after {{trip.ArrivalDays}} Day(s)</span>
                                                            <span class="handbag-icons handbag-icons-positionHandle" style="color:#c90201;" ng-if="trip.Seats!=0 && trip.Seats!=''"> Seats left: <samp id="seat_left_OB_{{flightitem.TtsIndex}}{{tripskey}}">{{trip.Seats}}</samp></span>
                                                        </div>
                                                    </div>
                                                    <hr ng-if="flightitem.MainSegment.length!=tripskey+1">
                                                </div>
                                                <div class="row">
                                            <div class="col-sm-12 clearfix" id="airline_remark_OB_{{flightitem.TtsIndex}}">
                                                <span ng-if="flightitem.AirlineRemark!=''"> Airline Remark : {{flightitem.AirlineRemark}} </span>
                                            </div>
                                        </div>
                                                <div class="row">
                                                    <div class="view-details-mainbtn">
                                                        <button type="button" class="btn flight-viewbtn flight-viewbtn_harish_OB flight-viewbtn_harish_OB_{{flightitem.TtsIndex}}" ng-click="getFlightDetail(flightitem.TtsIndex,'OB')">View Details <i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <ul class="flight-radiolist flight-farelist_OB_{{flightitem.TtsIndex}}">
                                                            <li class="flight-farelist" ng-repeat="(farelistkey,farelist) in flightitem.FareList" ng-class="{'d-none' : farelistkey >= fareshowlimit}">
                                                                <div class="form-check">
                                                                    <label class="form-check-label"  ng-if="flightitem.TtsIndex==0">
                                                                        <input class="form-check-input" type="radio" name="search_result_OB" ng-if="flightitem.TtsIndex==0&&farelistkey==0" value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex,'OB')" checked>
                                                                        <!-- <input class="form-check-input" type="radio" name="search_result_OB" ng-if="flightitem.TtsIndex!=0" value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex,'OB')">
                                                                       -->  <input class="form-check-input" type="radio" name="search_result_OB" ng-if="farelistkey!=0" value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex,'OB')">
                                                                        <span class="tts__flight__price">₹ {{farelist.Fare.PublishedPrice}} </span>
                                                                        <span class="tts__flight__price text-success" ng-if="shownetfare">₹ {{farelist.Fare.OfferedPrice}} </span>
                                                                    </label>
                                                                    <label class="form-check-label" ng-if="flightitem.TtsIndex!=0">
                                                                        <input class="form-check-input" type="radio" name="search_result_OB"  value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex,'OB')">
                                                                        <!-- <input class="form-check-input" type="radio" name="search_result_OB" ng-if="flightitem.TtsIndex!=0" value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex,'OB')">
                                                                        <input class="form-check-input" type="radio" name="search_result_OB" ng-if="farelistkey!=0" value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex,'OB')">
                                                                       -->  <span class="tts__flight__price">₹ {{farelist.Fare.PublishedPrice}} </span>
                                                                        <span class="tts__flight__price text-success" ng-if="shownetfare">₹ {{farelist.Fare.OfferedPrice}} </span>
                                                                    </label>
                                                                    <ul class="d-flex align-items-center mb-2">
                                                                        <li class="label-warning ">{{farelist.FareType}}</li>
                                                                        <li class="">
                                                                            <span class="ars-refunsleft ars-lastre">{{farelist.CabinClass}}, <span data-bs-toggle="tooltip" data-bs-placement="top" title="Refundable" class="cursor-pointer tts-refundable" ng-if="farelist.IsRefundable==true"> R</span><span data-bs-toggle="tooltip" data-bs-placement="top" title="Non Refundable" class="cursor-pointer tts-non-refundable" ng-if="farelist.IsRefundable!=true"> N</span></span>
                                                                        </li>
                                                                        <li>
                                                                         <span ng-if="farelist.Source" class="ars-refunsleft ars-lastre">{{farelist.Source}}</span>
                                                                        </li>
                                                                       
                                                                    </ul>
                                                                    
                                                                    <hr class="m-0" ng-if="flightitem.FareList.length!=(farelistkey+1)">
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <span class="flight__dropdown__icon" data-limit="{{fareshowlimit}}" ng-if="flightitem['FareList'].length > fareshowlimit" ng-click="removefareHideclass($event,flightitem.TtsIndex,'OB')"></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <?php echo  view('Modules/Flight/Views/FlightBookingtemplate\flight_result_detail_page.php'); ?>
                                    </div>
                                </div>

                                <!-----Filter No Flight ------------>
                                <div class="no-flight tts-api-error-msg mt-4" ng-if="FliterNoFlight">
                                    <p class="mt-4">No flight found that matches your filter criteria.Reset your filter </p>
                                    <button type="button" class="btn btn-primary btn-sm" ng-click="clearFilterAll(0,'all')">Reset All Filter</button>
                                </div>
                                <!-----Filter No Flight ------------>
                            </div>
                        </div>
                        <!-- Roundtrip result  OB End  here -->
                        <!-- Roundtrip result  IB starts here -->
                        <div class="col-6">
                            <div class="row">
                                <div class="col-sm-12 mt-3">
                                    <div class="tts__show__result p-2">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="tts__showing__result__heading"><b class="fs13">Showing Result {{ReturnFilteredResultCount}} of {{TotalReturnResults}} Flights</b>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="tts__showing__price__filter text-end">
                                                    <div class="btn-group dropdown">
                                                        <button type="button" class="btn  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <span class="text-muted mr-2">SORT BY:</span>
                                                            {{topsortingtextIB}}
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <button class="dropdown-item" type="button" ng-click="orderbyib('Fare',false,$event)">Price - Low to
                                                                High
                                                            </button>
                                                            <button class="dropdown-item" type="button" ng-click="orderbyib('Fare',true,$event)">Price - High to
                                                                Low
                                                            </button>
                                                            <button class="dropdown-item" type="button" ng-click="orderbyib('DepartTime',false,$event)">Depart - Early
                                                            </button>
                                                            <button class="dropdown-item" type="button" ng-click="orderbyib('DepartTime',true,$event)">Depart - Late
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <div class="tts__sorting__title p-2">
                                        <div class="row justify-content-between">
                                            <div class="col-2">
                                                <div class="tts__airline__name">
                                                    <a href="javascript:void(0);" ng-click="orderbyib('AirlineName')">Airlines
                                                        <span class="fa" ng-show="fieldib === 'AirlineName'" ng-class="(reverseib) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="tts__airline__name">
                                                    <a href="javascript:void(0);" ng-click="orderbyib('DepartTime')">Depart
                                                        <span class="fa" ng-show="fieldib === 'DepartTime'" ng-class="(reverseib) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="tts__airline__name">
                                                    <a href="javascript:void(0);" ng-click="orderbyib('ArrivalTime')">Arrive
                                                        <span class="fa" ng-show="fieldib === 'ArrivalTime'" ng-class="(reverseib) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="tts__airline__name">
                                                    <a href="javascript:void(0);" ng-click="orderbyib('DurationMin')">Duration
                                                        <span class="fa" ng-show="fieldib === 'DurationMin'" ng-class="(reverseib) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="tts__airline__name">
                                                    <a href="javascript:void(0);" ng-click="orderbyib('Fare')">Price
                                                        <span class="fa" ng-show="fieldib === 'Fare'" ng-class="(reverseib) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3 flight_result" ng-repeat="(flightkey,flightitem) in ReturnResults|orderBy:fieldib:reverseib |limitTo:limit">
                                    <div class="flight-rowmain">
                                        <div class="row">
                                            <div class="col-sm-7">
                                                <div class="row align-items-center" ng-repeat="(tripskey,trip) in flightitem.MainSegment">
                                                    <div class="col-md-5">
                                                        <div class="flight-leftresult1">
                                                            <ul class="flight-listair d-flex align-items-center ">
                                                                <li class=""><span class="airline-logo {{airlineLogoClass}} size-28 x{{trip.Airlinecode}}"><span></li>
                                                                <li class="">
                                                                    {{trip.AirlineName}}
                                                                    <div class="flight-holdid" ng-if="trip.AirlineCodeFlightNumberString!=''">
                                                                        <span class="flightids" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{trip.AirlineCodeFlightNumberString}}">{{trip.AirlineCodeFlightNumberString}}</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 col-4 p0">
                                                        <ul class="flight-listair">
                                                            <li class="">
                                                                <p class="flight-city">{{trip.DepartureCity}}</p>
                                                                <span class="flight-timefont">{{trip.DepartTime}}</span> <br><span class="flight-dur-timefont">{{trip.DepartDate}}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-sm-3 col-4 text-center p0 fs_11">
                                                        <div class="atls-holdid"><span class="flight-arrow" ng-if="trip.Stops>0">{{trip.Stops}} Stop(s)</span><span class="flight-arrow" ng-if="trip.Stops==0">Non Stop(s)</span></div>
                                                        <span class="fa fa-long-arrow-right d-block "></span>{{trip.Duration}}
                                                    </div>
                                                    <div class="col-sm-2 col-4 text-end p0">
                                                        <ul class="flight-listair">
                                                            <li class="">
                                                                <p class="flight-city">{{trip.ArrivalCity}}</p>
                                                                <span class="flight-timefont">{{trip.ArrivalTime}}</span> <br><span class=" flight-dur-timefont">{{trip.ArrivalDate}}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-sm-12 clearfix">
                                                        <div class="indicator-content">
                                                            <span class="flightarrives-after" ng-if="trip.ArrivalDays!=0"> <i class="fa fa-plane flightarrive-icons"></i> Flight Arrives after {{trip.ArrivalDays}} Day(s)</span>
                                                            <span class="handbag-icons handbag-icons-positionHandle" style="color:#c90201;" ng-if="trip.Seats!=0 && trip.Seats!=''"> Seats left: <samp id="seat_left_IB_{{flightitem.TtsIndex}}{{tripskey}}">{{trip.Seats}}</samp></span>
                                                        </div>
                                                    </div>
                                                    <hr ng-if="flightitem.MainSegment.length!=tripskey+1">
                                                </div>
                                                <div class="row">
                                            <div class="col-sm-12 clearfix" id="airline_remark_IB_{{flightitem.TtsIndex}}">
                                                <span ng-if="flightitem.AirlineRemark!=''"> Airline Remark : {{flightitem.AirlineRemark}} </span>
                                            </div>
                                        </div>
                                                <div class="row">
                                                    <div class="view-details-mainbtn">
                                                        <button type="button" class="btn flight-viewbtn flight-viewbtn_harish_IB flight-viewbtn_harish_IB_{{flightitem.TtsIndex}}" ng-click="getFlightDetail(flightitem.TtsIndex,'IB')">View Details <i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <ul class="flight-radiolist flight-farelist_IB_{{flightitem.TtsIndex}}">
                                                            <li class="flight-farelist" ng-repeat="(farelistkey,farelist) in flightitem.FareList" ng-class="{'d-none' : farelistkey >= fareshowlimit}">
                                                                <div class="form-check">
                                                                    <label class="form-check-label" ng-if="flightitem.TtsIndex==0">
                                                                        <input class="form-check-input" type="radio" name="search_result_IB" ng-if="flightitem.TtsIndex==0 && farelistkey==0" value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex,'IB')" checked>
                                                                        <!-- <input class="form-check-input" type="radio" name="search_result_IB" ng-if="flightitem.TtsIndex!=0" value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex,'IB')"> -->
                                                                        <input class="form-check-input" type="radio" name="search_result_IB" ng-if="farelistkey!=0" value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex,'IB')">
                                                                        <span class="tts__flight__price">₹ {{farelist.Fare.PublishedPrice}} </span>
                                                                        <span class="tts__flight__price text-success" ng-if="shownetfare">₹ {{farelist.Fare.OfferedPrice}} </span>
                                                                    </label>
                                                                    <label class="form-check-label" ng-if="flightitem.TtsIndex!=0">
                                                                        <input class="form-check-input" type="radio" name="search_result_IB"  value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex,'IB')">
                                                                        <!-- input class="form-check-input" type="radio" name="search_result_IB" ng-if="flightitem.TtsIndex!=0" value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex,'IB')">
                                                                        <input class="form-check-input" type="radio" name="search_result_IB" ng-if="farelistkey!=0" value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex,'IB')"> -->
                                                                        <span class="tts__flight__price">₹ {{farelist.Fare.PublishedPrice}} </span>
                                                                        <span class="tts__flight__price text-success" ng-if="shownetfare">₹ {{farelist.Fare.OfferedPrice}} </span>
                                                                    </label>
                                                                    <ul class="d-flex align-items-center mb-2">
                                                                        <li class="label-warning ">{{farelist.FareType}}</li>
                                                                        <li class="">
                                                                            <span class="ars-refunsleft ars-lastre">{{farelist.CabinClass}}, <span data-bs-toggle="tooltip" data-bs-placement="top" title="Refundable" class="cursor-pointer tts-refundable" ng-if="farelist.IsRefundable==true"> R</span><span data-bs-toggle="tooltip" data-bs-placement="top" title="Non Refundable" class="cursor-pointer tts-non-refundable" ng-if="farelist.IsRefundable!=true"> N</span></span>
                                                                        </li>
                                                                        <li>
                                                                         <span ng-if="farelist.Source" class="ars-refunsleft ars-lastre">{{farelist.Source}}</span>
                                                                        </li>
                                                                    </ul>
                                                                    <hr class="m-0" ng-if="flightitem.FareList.length!=(farelistkey+1)">
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <span class="flight__dropdown__icon" data-limit="{{fareshowlimit}}" ng-if="flightitem['FareList'].length > fareshowlimit" ng-click="removefareHideclass($event,flightitem.TtsIndex,'IB')"></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <?php echo  view('Modules/Flight/Views/FlightBookingtemplate\flight_result_detail_return_page.php'); ?>
                                    </div>
                                </div>

                                <!-----Filter No Flight ------------>
                                <div class="no-flight tts-api-error-msg mt-4" ng-if="ReturnFliterNoFlight">
                                    <p class="mt-4">No flight found that matches your filter criteria.Reset your filter </p>
                                    <button type="button" class="btn btn-primary btn-sm" ng-click="clearFilterAll(1,'all')">Reset All Filter</button>
                                </div>
                                <!-----Filter No Flight ------------>
                            </div>
                        </div>
                        <!-- Roundtrip result  IB End  here -->
                    </div>
                </div>
                <!-- oneway result ends here -->
                <div class="col-sm-12 tts-api-error-msg" ng-if="ErrorCode!=0">
                    <img src="<?php echo site_url('webroot/img/no-flight-found.png'); ?>" width="25%">
                    <h5 class="mt-4"> {{errormessage}}</h5>
                    <p class="mb-2">Sorry,No flights found on this route for the requested date. Please change your dates / search criteria.</p>
                    <a href="<?php echo site_url('flight'); ?>" class="btn btn-outline-danger">PICK ANOTHER DATE</a>
                </div>
            </div>
        </div>
    </div>
    <!------Start Flight Confirmation modal------>
    <div class="modal fade" id="FlightConfirmationModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modal_content">
                <div class="modal-header modal_header">
                    <h5 class="modal-title" ng-if="fareConfrimationLoading==true  || FareConfirmationPriceChangeTitle==''">Confirming Your Flight</h5>
                    <h5 class="modal-title" ng-if="fareConfrimationLoading==false && FareConfirmationPriceChangeTitle!=''">{{FareConfirmationPriceChangeTitle}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="hotel-main">
                        <!----- start loading ---->
                        <div class="row" ng-if="fareConfrimationLoading==true">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <div class="loader mt-3 mb-3">
                                        <div role="status" class="spinner-grow text-primary">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div role="status" class="spinner-grow text-secondary">
                                            <span class="sr-only">Loading...</span>
                                        </div>
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
                        <!----- end loading -->
                        <div class="row" ng-if="fareConfrimationLoading==false">
                            <div class="col-md-12" ng-if="FareConfrimationErrorCode!=0">
                                <div class="text-center">
                                    <h5 class="mt-4"> {{FareConfrimationmessage}}</h5>
                                </div>
                            </div>
                            <div class="col-md-12" ng-if="FareConfrimationErrorCode==0 && GetConfirmShowBox==1">
                                <div class="text-center" id="fareConfirmationpricechangeinfo">
                                    {{FareConfirmationPriceChangeInfo|html_filter:"fareConfirmationpricechangeinfo"}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!------End Flight Confirmation modal------>

    <!------Start Flight preview modal------>
    <div class="modal fade" id="FlightDetailModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="FlightDetailModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal_content">
                <div class="modal-header modal_header">
                    <h5 class="modal-title">Flight Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="hotel-main">
                        <div class="row mb-4">
                            <h5 class="flight-details-top-list mb-3 text-center">Onward Details</h5>
                            <div class="col-md-12">

                                <div class="text-center">
                                    <div class="row align-items-center "
                                         ng-repeat="(tripIndicatorkeyPreview,tripSegment) in FlightSegmentPreview['Segments']">
                                        <h5 class="flight-details-top-list mb-3 text-start">
                                            <b class="">
                                                <span>{{tripSegment[0].Origin.CityName}}</span>
                                                <span class="ars-arright">→</span>
                                                <span>{{tripSegment[tripSegment.length-1].Destination.CityName}}</span>
                                            </b>
                                            <span class="graycolor"> {{tripSegment[0].Origin.DepartTime|date: 'EEE, MMM d y'}}</span>
                                        </h5>
                                        <div class="col-md-12 mb-4" ng-repeat="(segmentindicatorkeyPreview,segment) in tripSegment">
                                            <div class="row">
                                                <div class="col-sm-3 pr-0">
                                                    <ul class="flight-listair1 d-flex">
                                                        <li>
                                                            <span class="airline-logo {{airlineLogoClass}} size-28 x{{segment.Airline.AirlineCode}} me-2"><span
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
                                                                <span class="graycolor"
                                                                      ng-if="segment.Origin.Terminal!=''">Terminal  {{segment.Origin.Terminal}}</span>

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
                                                                <span class="graycolor"
                                                                      ng-if="segment.Destination.Terminal!=''">Terminal  {{segment.Destination.Terminal}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 text-end flight-listair1">
                                                    <span> {{segment.Duration|changeDurationHourMinFormat}}</span><br>
                                                    <span class="at-fontweight atb-airport graycolor"
                                                          ng-if="FlightFareDetail.CabinClass">{{FlightFareDetail.CabinClass}}</span>
                                                    <span class=""
                                                          ng-if="FlightFareDetail.SeatBaggage[tripIndicatorkeyPreview][segmentindicatorkeyPreview].NoOfSeatAvailable!=''"> {{FlightFareDetail.SeatBaggage[tripIndicatorkeyPreview][segmentindicatorkeyPreview].NoOfSeatAvailable}} seat(s) left</span>
                                                          <br/><span class=""
                                          ng-if="segment.Airline.FareClass && segment.Airline.FareClass!=''">Fare Class - {{segment.Airline.FareClass}}</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <h5 class="flight-details-top-list mb-3 text-center">Return Details</h5>
                            <div class="col-md-12">

                                <div class="text-center">
                                    <div class="row align-items-center "
                                         ng-repeat="(tripIndicatorkeyPreview,tripSegment) in selectedReturnFlightPreview['Segments']">
                                        <h5 class="flight-details-top-list mb-3 text-start">
                                            <b class="">
                                                <span>{{tripSegment[0].Origin.CityName}}</span>
                                                <span class="ars-arright">→</span>
                                                <span>{{tripSegment[tripSegment.length-1].Destination.CityName}}</span>
                                            </b>
                                            <span class="graycolor"> {{tripSegment[0].Origin.DepartTime|date: 'EEE, MMM d y'}}</span>
                                        </h5>
                                        <div class="col-md-12 mb-4" ng-repeat="(segmentindicatorkeyPreview,segment) in tripSegment">
                                            <div class="row">
                                                <div class="col-sm-3 pr-0">
                                                    <ul class="flight-listair1 d-flex">
                                                        <li>
                                                            <span class="airline-logo {{airlineLogoClass}} size-28 x{{segment.Airline.AirlineCode}} me-2"><span
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
                                                                <span class="graycolor"
                                                                      ng-if="segment.Origin.Terminal!=''">Terminal  {{segment.Origin.Terminal}}</span>

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
                                                                <span class="graycolor"
                                                                      ng-if="segment.Destination.Terminal!=''">Terminal  {{segment.Destination.Terminal}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 text-end flight-listair1">
                                                    <span> {{segment.Duration|changeDurationHourMinFormat}}</span><br>
                                                    <span class="at-fontweight atb-airport graycolor"
                                                          ng-if="FlightFareDetail.CabinClass">{{FlightFareDetail.CabinClass}}</span>
                                                    <span class=""
                                                          ng-if="FlightFareDetail.SeatBaggage[tripIndicatorkeyPreview][segmentindicatorkeyPreview].NoOfSeatAvailable!=''"> {{FlightFareDetail.SeatBaggage[tripIndicatorkeyPreview][segmentindicatorkeyPreview].NoOfSeatAvailable}} seat(s) left</span>
                                                          <br/><span class=""
                                          ng-if="segment.Airline.FareClass && segment.Airline.FareClass!=''">Fare Class - {{segment.Airline.FareClass}}</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end mt-5">
                        <div class="col-sm-3">
                            <button type="button" class="btn flight-book"
                                    ng-click="getConfirmationInfo('confirm')">Proceed
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!------End Flight preview modal------>

    <?php echo  view('Modules/Flight/Views/FlightBookingtemplate\flight_return_bottom_view.php'); ?>
</main>
<!-- flight oneway result page ends here -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
<script>
    let url = "<?php echo  site_url(); ?>"
    let limitStep = 20;
    var app = angular.module('flightApp', []);
    app.controller('flightCtrl', function($scope, $http) {
        $scope.fareshowlimit = 3;
        $scope.loading = true;
        $scope.fareRuleLoading = true;
        $scope.fareRuleReturnLoading = true;
        $scope.fareConfrimationLoading = true;
        $scope.topsortingtext = 'Price - Low to High';
        $scope.topsortingtextIB = 'Price - Low to High';
        $scope.shownetfare = false;
        $scope.FliterNoFlight = false;
        $scope.ReturnFliterNoFlight = false;
        $scope.FlightSegment = [];
        $scope.FlightFareDetail = [];
        $scope.FlightFareRule = [];
        $scope.FlightBaggageInfo = [];
        $scope.FlightReturnSegment = [];
        $scope.FlightReturnFareDetail = [];
        $scope.FlightReturnFareRule = [];
        $scope.FlightReturnBaggageInfo = [];
        $scope.selectedOnwardFlight = [];
        $scope.selectedReturnFlight = [];

        $scope.FlightSegmentPreview = [];

        $scope.selectedReturnFlightPreview =[];

        $http({
            method: "GET",
            url: url + "flight/flight-result?" + "<?php echo http_build_query($_GET); ?>"
        }).then(function mySuccess(response) {
            $scope.loading = false;
            $scope.ErrorCode = response.data.Error.ErrorCode;
            $scope.ErrorMessage = response.data.Error.ErrorMessage;
            if ($scope.ErrorCode == 0) {
                $scope.Results = response.data.Result[0];
                $scope.ReturnResults = response.data.Result[1];
                $scope.FilterResults = response.data.Result[0];
                $scope.ReturnFilterResults = response.data.Result[1];
                $scope.limit = limitStep;
                $scope.FilteredResultCount = $scope.Results.length;
                $scope.ReturnFilteredResultCount = $scope.ReturnResults.length;
                $scope.token = response.data.SearchTokenId
                $scope.TotalResult = response.data.TotalResults;
                $scope.TotalReturnResults = response.data.TotalReturnResults;
                $scope.airlineLogoClass = response.data.airlineLogoClass;
                $scope.selectedOnwardFlight['SegementInfo'] = $scope.Results[0]['MainSegment'];
                $scope.selectedOnwardFlight['FareInfo'] = $scope.Results[0]['FareList'][0];
                $scope.selectedReturnFlight['SegementInfo'] = $scope.ReturnResults[0]['MainSegment'];
                $scope.selectedReturnFlight['FareInfo'] = $scope.ReturnResults[0]['FareList'][0];


                $scope.FlightSegmentPreview['Segments'] = $scope.Results[0]['Segments'];

                $scope.selectedReturnFlightPreview['Segments'] = $scope.ReturnResults[0]['Segments'];


                $scope.onwardttsKey = 0;
                $scope.returnttsKey = 0;
                $scope.onwardfareKey = 0;
                $scope.returnfareKey = 0;
                $scope.onwardfarecode = $scope.Results[0]['FareList'][0]['FareId'];
                $scope.returnfarecode = $scope.ReturnResults[0]['FareList'][0]['FareId'];
                $scope.FlightFilter = response.data.Filter; 

                setTimeout(function() {
                    $scope.Priceslider();
                    $scope.PricesliderIB();
                    
                }, 100);

            } else {
                $scope.errormessage = $scope.ErrorMessage;
            }
        }, function myError(response) {
            $scope.errormessage = "Something went wrong";
        });
        $scope.showFareDetail = false;
        $scope.showTTsIndexFareDetail = "";
        $scope.showReturnFareDetail = false;
        $scope.showTTsReturnIndexFareDetail = "";
        $scope.hidFlightDetail = function(ttskey, rtype = "") {
            if (rtype == "IB") {
                angular.element($('#id-IB-' + ttskey).hide());
                var viewDetailButton = document.querySelector('.flight-viewbtn_harish_' + rtype + '_' + ttskey + ' i');
            } else {
                angular.element($('#id-' + ttskey).hide());
                var viewDetailButton = document.querySelector('.flight-viewbtn_harish_OB' + '_' + ttskey + ' i');
            }
   
            if (viewDetailButton.classList.contains('fa-minus')) {
                viewDetailButton.classList.remove('fa-minus');
                viewDetailButton.classList.add('fa-plus');
            }
            if (rtype == "IB") {
                $scope.showReturnFareDetail = false;
                $scope.showTTsReturnIndexFareDetail = "";
            } else {
                $scope.showFareDetail = false;
                $scope.showTTsIndexFareDetail = "";
            }
        }

        $scope.getFlightDetail = function(ttskey, rtype) {
            if (rtype == "OB") {
                $scope.fareRuleLoading = true;
            } else if (rtype == "IB") {
                $scope.fareRuleReturnLoading = true;
            }
            let checkbox = document.getElementsByName('search_result_' + rtype);
            let ln = 0;
            let selindex = "0";
            for (var i = 0; i < checkbox.length; i++) {
                if (checkbox[i].checked) {
                    selindex = checkbox[i].value;
                }
            }
            if (selindex) {
                if (rtype == "OB") {
                    var flightInfo = $scope.Results.filter(function(flightItem) {
                        return flightItem.TtsIndex == ttskey;
                    })[0];
                } else if (rtype == "IB") {
                    var flightInfo = $scope.ReturnResults.filter(function(flightItem) {
                        return flightItem.TtsIndex == ttskey;
                    })[0];
                }
                if (rtype == "OB") {
                    $scope.FlightSegment = flightInfo.Segments
                    $scope.FlightFareDetail = flightInfo.FareList[selindex];
                    $scope.FlightBaggageInfo = flightInfo.FareList[selindex].SeatBaggage;
                } else if (rtype == "IB") {
                    $scope.FlightReturnSegment = flightInfo.Segments
                    $scope.FlightReturnFareDetail = flightInfo.FareList[selindex];
                    $scope.FlightReturnBaggageInfo = flightInfo.FareList[selindex].SeatBaggage;
                }


                var FareRuleData = {};
                FareRuleData.token = $scope.token;
                FareRuleData.FareId = flightInfo.FareList[selindex].FareId;
                $http({
                    method: "POST",
                    url: url + 'flight/fare-rule',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    data: $.param(FareRuleData)
                }).then(function mySuccess(fareruleresponse) {

                    if (rtype == "OB") {
                        $scope.fareRuleLoading = false;
                        $scope.FareRuleErrorCode = fareruleresponse.data.Error.ErrorCode;
                        $scope.fareruleerrormessage = fareruleresponse.data.Error.ErrorMessage;
                        if ($scope.FareRuleErrorCode == 0) {
                            $scope.FlightFareRule = fareruleresponse.data.Result;
                        }
                    } else if (rtype == "IB") {
                        $scope.fareRuleReturnLoading = false;
                        $scope.FareReturnRuleErrorCode = fareruleresponse.data.Error.ErrorCode;
                        $scope.farereturnruleerrormessage = fareruleresponse.data.Error.ErrorMessage;
                        if ($scope.FareReturnRuleErrorCode == 0) {
                            $scope.FlightReturnFareRule = fareruleresponse.data.Result;
                        }
                    }
                }, function myError(fareruleresponse) {
                    if (rtype == "OB") {
                        $scope.fareRuleLoading = false;
                        $scope.fareruleerrormessage = "Something went wrong";
                    } else if (rtype == "IB") {
                        $scope.fareRuleReturnLoading = false;
                        $scope.farereturnruleerrormessage = "Something went wrong";
                    }
                });
            }
            if (rtype == "OB") {
                angular.element($(".flight-detail").hide());
            } else if (rtype == "IB") {
                angular.element($(".flight-detail-IB").hide());
            }
            angular.element($(".flight-viewbtn_harish_" + rtype + " i").removeClass('fa-plus fa-minus'));
            angular.element($(".flight-viewbtn_harish_" + rtype + " i").addClass('fa-plus'));
            angular.element($("#airline_remark_"+ rtype +"_" + ttskey).html('<span>  Airline Remark : '+flightInfo.FareList[selindex].AirlineRemark+'</span>'));
            for (seats = 0; seats < $scope.FlightBaggageInfo.length; seats++) {
                angular.element($("#seat_left_" + rtype + "_" + ttskey + seats).text($scope.FlightBaggageInfo[seats][0].NoOfSeatAvailable));
            }

            let viewDetailButton = document.querySelector('.flight-viewbtn_harish_' + rtype + '_' + ttskey + ' i');
            if (viewDetailButton.classList.contains('fa-plus')) {
                viewDetailButton.classList.remove('fa-plus');
                viewDetailButton.classList.add('fa-minus');
            }
            if (rtype == "OB") {
                angular.element($('#id-' + ttskey).show());
                $scope.showFareDetail = true;
                $scope.showTTsIndexFareDetail = ttskey;
            } else if (rtype == "IB") {
                angular.element($('#id-IB-' + ttskey).show());
                $scope.showReturnFareDetail = true;
                $scope.showTTsReturnIndexFareDetail = ttskey;
            }

        }
        $scope.fareoOptionselected = function(ttskey, rtype) {
            let checkbox = document.getElementsByName('search_result_' + rtype);
            let ln = 0;
            let selindex;
            for (var i = 0; i < checkbox.length; i++) {
                if (checkbox[i].checked) {
                    selindex = checkbox[i].value;
                }
            }
            if (selindex) {
                if (rtype == "OB") {
                    var flightInfo = $scope.Results.filter(function(flightItem) {
                        return flightItem.TtsIndex == ttskey;
                    })[0];
                    $scope.FlightSegmentPreview['Segments'] = flightInfo.Segments;

                    $scope.selectedOnwardFlight['SegementInfo'] = flightInfo.MainSegment;
                    $scope.selectedOnwardFlight['FareInfo'] = flightInfo.FareList[selindex];
                    $scope.onwardttsKey = ttskey;
                    $scope.onwardfareKey = selindex;
                    $scope.onwardfarecode = flightInfo.FareList[selindex]['FareId'];
                }
                if (rtype == "IB") {
                    var flightInfo = $scope.ReturnResults.filter(function(flightItem) {
                        return flightItem.TtsIndex == ttskey;
                    })[0];
                    $scope.selectedReturnFlightPreview['Segments'] = flightInfo.Segments;
                    $scope.selectedReturnFlight['SegementInfo'] =flightInfo.MainSegment;
                    $scope.selectedReturnFlight['FareInfo'] = flightInfo.FareList[selindex];
                    $scope.returnttsKey =ttskey;
                    $scope.returnfareKey = selindex;
                    $scope.returnfarecode = flightInfo.FareList[selindex]['FareId'];

                }
                let FlightBaggageInfo = flightInfo.FareList[selindex].SeatBaggage;
                angular.element($("#airline_remark_"+ rtype +"_" + ttskey).html('<span>  Airline Remark : '+flightInfo.FareList[selindex].AirlineRemark+'</span>'));
                for (seats = 0; seats < FlightBaggageInfo.length; ++seats) {
                    angular.element($("#seat_left_" + rtype + "_" + ttskey + seats).text(FlightBaggageInfo[seats][0].NoOfSeatAvailable));
                }
                if ($scope.showFareDetail == true && $scope.showTTsIndexFareDetail == ttskey && rtype == "OB") {
                    $scope.getFlightDetail(ttskey, rtype);
                }
                if ($scope.showReturnFareDetail == true && $scope.showTTsReturnIndexFareDetail == ttskey && rtype == "IB") {
                    $scope.getFlightDetail(ttskey, rtype);
                }
            }
        }
        $scope.removefareHideclass = function(event, ttsindex,rtype) {

            event.target.classList.toggle('flight__dropdown__icon--selected');
            let limit = event.target.getAttribute('data-limit');
            let uldata = document.querySelector('.flight-farelist_'+rtype+"_" + ttsindex);
            for (var i = 0; i < uldata.children.length; ++i) {
                var item = uldata.children.item(i);
                if (i >= limit) {
                    if (item.classList.contains('d-none')) {
                        item.classList.remove('d-none');
                    } else {
                        item.classList.add('d-none');
                    }
                }
            }
        }
        $scope.getConfirmationInfo = function(confirmOrPreview) {
            $scope.fareConfrimationLoading = true;

            if (confirmOrPreview=='preview') {
                let FlightDetailModel = document.getElementById("FlightDetailModel");
                if (FlightDetailModel !== null) {
                    new bootstrap.Modal(FlightDetailModel).show();
                }
            }else {
                let FlightDetailModel = document.getElementById("FlightDetailModel").style.display='none';

                if ($scope.onwardfarecode != "" && $scope.returnfarecode != "") {
                    var FareConfirmationData = {};
                    FareConfirmationData.token = $scope.token;
                    FareConfirmationData.OnwardFareId = $scope.onwardfarecode;
                    FareConfirmationData.ReturnFareId = $scope.returnfarecode;
                    FareConfirmationData.TtsOnwardResultIndexkey = $scope.onwardttsKey;
                    FareConfirmationData.TtsReturnResultIndexkey = $scope.returnttsKey;
                    FareConfirmationData.FareListOptionOnwardkey = $scope.onwardfareKey;
                    FareConfirmationData.FareListOptionReturnkey = $scope.returnfareKey;
                    var FlightConfirmationModel = document.getElementById("FlightConfirmationModel");
                    if (FlightConfirmationModel !== null) {
                        new bootstrap.Modal(FlightConfirmationModel).show();
                    }
                    $http({
                        method: "POST",
                        url: url + 'flight/fare-confirmation-roundtrip',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        data: $.param(FareConfirmationData)
                    }).then(function mySuccess(fareconfrimationresponse) {
                        $scope.fareConfrimationLoading = false;
                        $scope.FareConfrimationErrorCode = fareconfrimationresponse.data.Error.ErrorCode;
                        $scope.FareConfrimationmessage = fareconfrimationresponse.data.Error.ErrorMessage;
                        $scope.FareConfirmationPriceChangeInfo = '';
                        $scope.FareConfirmationPriceChangeTitle = '';
                        if ($scope.FareConfrimationErrorCode == 0) {
                            $scope.FareConfirmationPriceChangeInfo = fareconfrimationresponse.data.PriceChange;
                            $scope.GetConfirmShowBox = fareconfrimationresponse.data.GetConfirmShowBox;
                            $scope.FareConfirmationPriceChangeTitle = fareconfrimationresponse.data.Title;
                            RedirectUrl = fareconfrimationresponse.data.RedirectUrl;
                            if ($scope.GetConfirmShowBox == 0) {
                                angular.element($("#FlightConfirmationModel").hide());
                                window.location.href = RedirectUrl;
                            }

                        }
                    }, function myError(fareconfrimationresponse) {
                        $scope.fareConfrimationLoading = false;
                        $scope.FareConfrimationmessage = "Something went wrong";
                    });
                } else {
                    alert("Something went wrong");
                }
            }
        }
        $scope.orderby = function(field, order = null, event = null) {
            if (order === null) {
                $scope.reverse = ($scope.field === field) ? !$scope.reverse : true;
                $scope.field = field;
            } else {
                $scope.reverse = order;
                $scope.field = field;
                $scope.topsortingtext = event.target.innerHTML;
            }
        }
        $scope.orderbyib = function(field, order = null, event = null) {
            if (order === null) {
                $scope.reverseib = ($scope.fieldib === field) ? !$scope.reverseib : true;
                $scope.fieldib = field;
            } else {
                $scope.reverseib = order;
                $scope.fieldib = field;
                $scope.topsortingtextIB = event.target.innerHTML;
            }
        }

        $scope.Priceslider = function() {
            $ = jQuery.noConflict();
            price_min = $scope.FlightFilter[0]['Price']['min'];
            price_max = $scope.FlightFilter[0]['Price']['max'];
            var step=0.01;
            $(".price-range").slider({
                range: true,
                min: price_min,
                max: price_max,
                step: parseFloat(step),
                values: [price_min, price_max],
                slide: function(event, ui) {
                    $(".left-price").val(ui.values[0].toString());
                    $(".right-price").val(ui.values[1].toString());
                },
                stop: function(event, ui) {
                   
                    var min = ui.values[0];
                    var max = ui.values[1];
                    let filters = [];
                    let filteredResult = [];

                    let FilterData=[];
                     FilterData=$scope.FlightFilter[0];

                    let filterAirline = $scope.checkedfilter(FilterData['Airline'],'value');
                    let filterStop = $scope.checkedfilter(FilterData['Stop'],'value');
                    let filterFaretype = $scope.checkedfilter(FilterData['FareType'],'value');
                    let filterDepartTime = $scope.checkedfilter(FilterData['DepartTime'],'value');
                    let filterArrivalTime = $scope.checkedfilter(FilterData['ArrivalTime'],'value');
                    if(filterAirline.length !== 0) 
                    {
                        filters['Airlinecode'] = filterAirline;
                    }
                    if(filterStop.length !== 0) 
                    {
                        filters['Stops'] = filterStop;
                    }
                    if(filterFaretype.length !== 0) 
                    {
                        filters['IsRefundable'] = filterFaretype;
                    }
                    if(filterDepartTime.length !== 0) 
                    {
                        filters['DepartString'] = filterDepartTime;
                    }
                    if(filterArrivalTime.length !== 0) 
                    {
                        filters['ArrivalString'] = filterArrivalTime;
                    }

                   
                    var filtered = $scope.multiFilter(0,$scope.FilterResults, filters);
                    angular.forEach(filtered, function(item) {
                        if (item.MainSegment[0].Fare >= min && item.MainSegment[0].Fare <= max) {
                            filteredResult.push(item);
                        }
                    });

                    $scope.FilteredResultCount = filteredResult.length;
                    $scope.Results = filteredResult;
                    if (filteredResult.length == 0) {
                            $scope.FliterNoFlight = true;
                    } else {
                        $scope.FliterNoFlight = false;
                    }
                    $scope.limit = limitStep;
                    $scope.$evalAsync();
                }
            });
            $(".left-price").val($(".price-range").slider("values", 0).toString());
            $(".right-price").val($(".price-range").slider("values", 1).toString());
        }

        $scope.PricesliderIB = function() {
            $ = jQuery.noConflict();
            price_min = $scope.FlightFilter[1]['Price']['min'];
            price_max = $scope.FlightFilter[1]['Price']['max'];
            var step=0.01;
            $(".price-range-return").slider({
                range: true,
                min: price_min,
                max: price_max,
                step: parseFloat(step),
                values: [price_min, price_max],
                slide: function(event, ui) {
                    $(".left-price-return").val(ui.values[0].toString());
                    $(".right-price-return").val(ui.values[1].toString());
                },
                stop: function(event, ui) {
                   
                    var min = ui.values[0];
                    var max = ui.values[1];
                    let filters = [];
                    let filteredResult = [];

                    let FilterData=[];
                     FilterData=$scope.FlightFilter[1];

                    let filterAirline = $scope.checkedfilter(FilterData['Airline'],'value');
                    let filterStop = $scope.checkedfilter(FilterData['Stop'],'value');
                    let filterFaretype = $scope.checkedfilter(FilterData['FareType'],'value');
                    let filterDepartTime = $scope.checkedfilter(FilterData['DepartTime'],'value');
                    let filterArrivalTime = $scope.checkedfilter(FilterData['ArrivalTime'],'value');
                    if(filterAirline.length !== 0) 
                    {
                        filters['Airlinecode'] = filterAirline;
                    }
                    if(filterStop.length !== 0) 
                    {
                        filters['Stops'] = filterStop;
                    }
                    if(filterFaretype.length !== 0) 
                    {
                        filters['IsRefundable'] = filterFaretype;
                    }
                    if(filterDepartTime.length !== 0) 
                    {
                        filters['DepartString'] = filterDepartTime;
                    }
                    if(filterArrivalTime.length !== 0) 
                    {
                        filters['ArrivalString'] = filterArrivalTime;
                    }

                   
                    var filtered = $scope.multiFilter(1,$scope.ReturnFilterResults, filters);
                    angular.forEach(filtered, function(item) {
                        if (item.MainSegment[0].Fare >= min && item.MainSegment[0].Fare <= max) {
                            filteredResult.push(item);
                        }
                    });

                    $scope.ReturnFilteredResultCount = filteredResult.length;
                    $scope.ReturnResults = filteredResult;
                    if (filteredResult.length == 0) {
                        $scope.ReturnFliterNoFlight = true;
                    } else {
                        $scope.ReturnFliterNoFlight = false;
                    }
                    $scope.limit = limitStep;
                    $scope.$evalAsync();
                }
            });
            $(".left-price-return").val($(".price-range-return").slider("values", 0).toString());
            $(".right-price-return").val($(".price-range-return").slider("values", 1).toString());
        }

    
        $scope.doFilter = function(filtertype,type, event, item) {

            if (type == "FareType") {
                if (event.target.checked) {
                    item['isChecked']=true;
                } else {
                    item['isChecked']=false;
                }
            }
            if (type == "Stop") {
                if (event.target.checked) {
                    item['isChecked']=true;
                } else {
                    item['isChecked']=false;
                }
            }
            if (type == "DepartTime") {
                if (event.target.checked) {
                    item['isChecked']=true;
                } else {
                    item['isChecked']=false;
                }
            }
            if (type == "Airline") {
                if (event.target.checked) {
                    item['isChecked']=true;
                } else {
                    item['isChecked']=false;
                }
            }
 
            let min;  let max;
            if(filtertype==0)
            {
                 min = $.trim($(".left-price").val());
                 max = $.trim($(".right-price").val());
            } else {
                 min = $.trim($(".left-price-return").val());
                 max = $.trim($(".right-price-return").val());
            }
   
            let FilterData=[];
            FilterData=$scope.FlightFilter[filtertype];
            
            let filters = [];
            let filteredResult = [];
            setTimeout(() => {
                let filterAirline = $scope.checkedfilter(FilterData['Airline'],'value');
                let filterStop = $scope.checkedfilter(FilterData['Stop'],'value');
                let filterFaretype = $scope.checkedfilter(FilterData['FareType'],'value');
                let filterDepartTime = $scope.checkedfilter(FilterData['DepartTime'],'value');
                let filterArrivalTime = $scope.checkedfilter(FilterData['ArrivalTime'],'value');
                if(filterAirline.length !== 0) 
                {
                    filters['Airlinecode'] = filterAirline;
                }
                if(filterStop.length !== 0) 
                {
                    filters['Stops'] = filterStop;
                }
                if(filterFaretype.length !== 0) 
                {
                    filters['IsRefundable'] = filterFaretype;
                }
                if(filterDepartTime.length !== 0) 
                {
                    filters['DepartString'] = filterDepartTime;
                }
                if(filterArrivalTime.length !== 0) 
                {
                    filters['ArrivalString'] = filterArrivalTime;
                }
                if (type == 'clear') {
                   filters = [];
                }

                let data=[];
                if(filtertype==0)
                {
                    data=$scope.FilterResults;
                } else {
                    data=$scope.ReturnFilterResults;
                }
            
                filtered=$scope.multiFilter(filtertype,data, filters);
                angular.forEach(filtered, function(item) {
                        if (item.MainSegment[0].Fare >= min && item.MainSegment[0].Fare <= max) {
                            filteredResult.push(item);
                        }
                });

                if(filtertype==0)
                {
                    $scope.FilteredResultCount = filteredResult.length;
                    $scope.Results = filteredResult;
                    if (filteredResult.length == 0) {
                       $scope.FliterNoFlight = true;
                    } else {
                        $scope.FliterNoFlight = false;
                    }
                } else {
                    $scope.ReturnFilteredResultCount = filteredResult.length;
                    $scope.ReturnResults = filteredResult; 
                    if (filteredResult.length == 0) {
                       $scope.ReturnFliterNoFlight = true;
                    } else {
                        $scope.ReturnFliterNoFlight = false;
                    }
                }
                $scope.limit = limitStep;
                $scope.$digest();

            }, 10);
    
            
        }
      
        $scope.multiFilter = function(filtertype,array, filters) {

                var filterKeys = Object.keys(filters);
                let newfilters=[];
                filterKeys.forEach(function(value, key) {
                    if(value!='IsRefundable')
                    {
                        newfilters[value]=filters[value];
                    }
                });
                var newfilterKeys=Object.keys(newfilters);
                let response=[];
                array.filter((item) => {
                    if(filterKeys.includes("IsRefundable"))
                    {
                        let fareitem=[];
                        item['FareList'].forEach(function(value, key) {
                            if(filters['IsRefundable'].indexOf(value['IsRefundable']) !== -1)  
                            {  
                                    fareitem.push(value);
                            }   
                        });
                        if(newfilterKeys.every(key1 => !!~newfilters[key1].indexOf(item['MainSegment'][0][key1])))
                        {   
                            if(fareitem.length!=0)
                            {
                                let obj = Object.assign({}, item);
                                obj['FareList']=fareitem;
                                response.push(obj);
                            }
                        }
                    } else {
                        if(filterKeys.every(key => !!~filters[key].indexOf(item['MainSegment'][0][key])))
                        {
                                response.push(item);
                        }
                    }
                });
                return response;
        }

        $scope.clearFilterAll = function(filtertype,type) {
            if (type == 'all') {
                
                $scope.FlightFilter[filtertype]['FareType']=$scope.resetfilter($scope.FlightFilter[filtertype]['FareType']);
                $scope.FlightFilter[filtertype]['Stop']=$scope.resetfilter($scope.FlightFilter[filtertype]['Stop']);
                $scope.FlightFilter[filtertype]['DepartTime']=$scope.resetfilter($scope.FlightFilter[filtertype]['DepartTime']);
                $scope.FlightFilter[filtertype]['ArrivalTime']=$scope.resetfilter($scope.FlightFilter[filtertype]['ArrivalTime']);
                $scope.FlightFilter[filtertype]['Airline']=$scope.resetfilter($scope.FlightFilter[filtertype]['Airline']);

                if(filtertype==0)
                {
                    angular.element($(".price-range").slider("values", 0, $scope.FlightFilter[filtertype]['Price']['min']));
                    angular.element($(".price-range").slider("values", 1, $scope.FlightFilter[filtertype]['Price']['max']));
                    angular.element($(".left-price").val($scope.FlightFilter[filtertype]['Price']['min']));
                    angular.element($(".right-price").val($scope.FlightFilter[filtertype]['Price']['max']));
                    $scope.Priceslider();
                } else {
                    angular.element($(".price-range-return").slider("values", 0, $scope.FlightFilter[filtertype]['Price']['min']));
                    angular.element($(".price-range-return").slider("values", 1, $scope.FlightFilter[filtertype]['Price']['max']));
                    angular.element($(".left-price-return").val($scope.FlightFilter[filtertype]['Price']['min']));
                    angular.element($(".right-price-return").val($scope.FlightFilter[filtertype]['Price']['max']));
                    $scope.PricesliderIB();
                }
               
            }

            $scope.doFilter(filtertype,'clear', '', '');
            $scope.limit = limitStep;
        }

        $scope.checkedfilter = function(array,field) {
            let response = [];
            array.forEach(function(value, key) {
                if (value.isChecked) {
                    response.push(value[field]);
                }
            });
            return response;
        }

        $scope.resetfilter = function(array) {
            let response = [];
            array.forEach(function(value, key) {
                value.isChecked = false;
                response.push(value);
            });
            return response;
        }
    });

    app.directive("scroll", function($window) {
        return function(scope, element) {
            angular.element($window).bind("scroll", function() {
                var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
                var body = document.body,
                    html = document.documentElement;
                var docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);
                windowBottom = windowHeight + window.pageYOffset + 1000;
                if (windowBottom >= docHeight) {
                    scope.limit += limitStep;
                }
                scope.$apply();
            });
        };
    });

    app.filter('rtrim', function() {
        return function(value) {
            return value.replace(/, \W+/g, '');
        }
    });
    app.filter('changeDurationHourMinFormat', function() {
        return function(value) {
            var hour = Math.floor(value / 60);
            var minutes = value - (hour * 60);
            return hour + "h  " + minutes + "m";
        }
    });
    app.filter("trusthtml", ['$sce', function($sce) {
        return function(htmlCode) {
            return $sce.trustAsHtml(htmlCode);
        }
    }]);
    app.filter('html_filter', function() {
        return function(input, element) {
            return $("#" + element).html(input.replace(/&lt;/g, '<').replace(/&gt;/g, '>'));
        };
    });
    app.filter('paxtype', function() {
        return function(input) {
            var paxtype;
            switch (input) {
                case 'ADT':
                    paxtype = "Adult";
                    break;
                case 'CHD':
                    paxtype = "Child";
                    break;
                case 'INF':
                    paxtype = "Infant";
                    break;
                default:
                    paxtype = "ADULT";

            }
            return paxtype;
        };
    });
</script>