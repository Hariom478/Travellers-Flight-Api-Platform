<div class="tts__filter">
    <nav ng-if="FlightFilter.length!=1">
        <div class="nav nav-tabs border-0 justify-content-center pt-2" id="nav-tab" role="tablist">
            <button class="btn btn-outline-dark btn-sm me-3 active" id="nav-filterob-tab" data-bs-toggle="tab" data-bs-target="#nav-filterob" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Outbound</button>
            <button class="btn btn-outline-dark btn-sm" id="nav-filterib-tab" data-bs-toggle="tab" data-bs-target="#nav-filterib" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Inbound</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-filterob" role="tabpanel" aria-labelledby="nav-filterob-tab">
            <div class="d-flex justify-content-between p-2">
                <span><b>Filter Results</b></span>
                <span><a href="javascript:void(0);" class="tts__fliter__reset" ng-click="clearFilterAll(0,'all')">Reset All</a></span>
            </div>
            <div class="tts__filter__heading p-2">
                <span><b>Price</b></span>
            </div>
            <div class="p-2 tts__filter-price">
                <p class="tts-price-range">
                    <input type="text" class="left-price" readonly />
                    <input type="text" class="right-price text-end" readonly />
                </p>
                <div class="price-range price-filter"></div>
            </div>
            <div class="tts__filter__heading p-2">
                <span><b>Fare Type</b></span>
            </div>
            <div class="p-2">
                <ul class="list-unstyled mb-1">
                    <li ng-repeat="(key,item) in FlightFilter[0]['FareType']">
                        <label class="tts__inputradio_label tts__checkbox__label">
                            <input type="checkbox" name="fare_type" value="{{item['value']}}" class="me-1 tts__checkbox__input" ng-click="doFilter(0,'FareType',$event,item);" ng-model="item.isChecked" />
                            <span>{{item['label']}}</span>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="tts__filter__heading p-2">
                <span><b>Stop</b></span>
            </div>
            <div class="p-2">
                <ul class="list-unstyled mb-1">
                    <li ng-repeat="(key,item) in FlightFilter[0]['Stop']">
                        <label class="tts__inputradio_label tts__checkbox__label">
                            <input type="checkbox" name="stop" value="{{item['value']}}" class="me-1 tts__checkbox__input" ng-click="doFilter(0,'Stop',$event,item);" ng-model="item.isChecked" />
                            <span>{{item['label']}}</span>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="tts__filter__heading p-2">
                <span><b>Departure Times</b></span>
            </div>
            <div class="p-2">
                <ul class="nav tts__nav__tabs">
                    <li class="nav-item" ng-repeat="(key,item) in FlightFilter[0]['DepartTime']">
                        <label class="w-100 tts__checkbox__label tts__flight__timing p-1" ng-class="{'tts-filter-select':item.isChecked}">
                            <input type="checkbox" class="invisible tts__checkbox__input" name="depart_time" value="{{item['value']}}" ng-click="doFilter(0,'DepartTime',$event,item);" ng-model="item.isChecked" />
                            <img ng-if="item['icon']" src="{{item['icon']}}" style="width: 25px; margin-left: auto; margin-right: auto; display: block;" />
                            <span class="tts__nav__tabs__span">{{item['label']}}</span>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="tts__filter__heading p-2">
                <span><b>Arrival Times</b></span>
            </div>
            <div class="p-2">
                <ul class="nav tts__nav__tabs">
                    <li class="nav-item" ng-repeat="(key,item) in FlightFilter[0]['ArrivalTime']">
                        <label class="w-100 tts__checkbox__label tts__flight__timing p-1" ng-class="{'tts-filter-select':item.isChecked}">
                            <input type="checkbox" class="invisible tts__checkbox__input" name="depart_time" value="{{item['value']}}" ng-click="doFilter(0,'ArrivalTime',$event,item);" ng-model="item.isChecked" />
                            <img ng-if="item['icon']" src="{{item['icon']}}" style="width: 25px; margin-left: auto; margin-right: auto; display: block;" />
                            <span class="tts__nav__tabs__span">{{item['label']}}</span>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="tts__filter__heading p-2">
                <span><b>Airlines</b></span>
            </div>
            <div class="p-2">
                <ul class="list-unstyled mb-1">
                    <li ng-repeat="(key,item) in FlightFilter[0]['Airline']">
                        <label class="tts__inputradio_label tts__checkbox__label">
                            <input type="checkbox" name="airline" value="{{item['value']}}" class="me-1 tts__checkbox__input" ng-click="doFilter(0,'Airline',$event,key);" ng-model="item.isChecked" />
                            <span>{{item['label']}}</span>
                            <span class="float-end {{airlineLogoClass}} size-18 x{{item['value']}}"></span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-filterib" role="tabpanel" aria-labelledby="nav-filterib-tab">
            <div class="d-flex justify-content-between p-2">
                <span><b>Filter Results</b></span>
                <span><a href="javascript:void(0);" class="tts__fliter__reset" ng-click="clearFilterAll(1,'all')">Reset All</a></span>
            </div>
            <div class="tts__filter__heading p-2">
                <span><b>Price</b></span>
            </div>
            <div class="p-2 tts__filter-price">
                <p class="tts-price-range">
                    <input type="text" class="left-price-return" readonly />
                    <input type="text" class="right-price-return text-end" readonly />
                </p>
                <div class="price-range-return price-filter"></div>
            </div>
            <div class="tts__filter__heading p-2">
                <span><b>Fare Type</b></span>
            </div>
            <div class="p-2">
                <ul class="list-unstyled mb-1">
                    <li ng-repeat="(key,item) in FlightFilter[1]['FareType']">
                        <label class="tts__inputradio_label tts__checkbox__label">
                            <input type="checkbox" name="fare_type" value="{{item['value']}}" class="me-1 tts__checkbox__input" ng-click="doFilter(1,'FareType',$event,item);" ng-model="item.isChecked" />
                            <span>{{item['label']}}</span>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="tts__filter__heading p-2">
                <span><b>Stop</b></span>
            </div>
            <div class="p-2">
                <ul class="list-unstyled mb-1">
                    <li ng-repeat="(key,item) in FlightFilter[1]['Stop']">
                        <label class="tts__inputradio_label tts__checkbox__label">
                            <input type="checkbox" name="stop" value="{{item['value']}}" class="me-1 tts__checkbox__input" ng-click="doFilter(1,'Stop',$event,item);" ng-model="item.isChecked" />
                            <span>{{item['label']}}</span>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="tts__filter__heading p-2">
                <span><b>Departure Times</b></span>
            </div>
            <div class="p-2">
                <ul class="nav tts__nav__tabs">
                    <li class="nav-item" ng-repeat="(key,item) in FlightFilter[1]['DepartTime']">
                        <label class="w-100 tts__checkbox__label tts__flight__timing p-1" ng-class="{'tts-filter-select':item.isChecked}">
                            <input type="checkbox" class="invisible tts__checkbox__input" name="depart_time" value="{{item['value']}}" ng-click="doFilter(1,'DepartTime',$event,item);" ng-model="item.isChecked" />
                            <img ng-if="item['icon']" src="{{item['icon']}}" style="width: 25px; margin-left: auto; margin-right: auto; display: block;" />
                            <span class="tts__nav__tabs__span">{{item['label']}}</span>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="tts__filter__heading p-2">
                <span><b>Arrival Times</b></span>
            </div>
            <div class="p-2">
                <ul class="nav tts__nav__tabs">
                    <li class="nav-item" ng-repeat="(key,item) in FlightFilter[1]['ArrivalTime']">
                        <label class="w-100 tts__checkbox__label tts__flight__timing p-1" ng-class="{'tts-filter-select':item.isChecked}">
                            <input type="checkbox" class="invisible tts__checkbox__input" name="depart_time" value="{{item['value']}}" ng-click="doFilter(1,'ArrivalTime',$event,item);" ng-model="item.isChecked" />
                            <img ng-if="item['icon']" src="{{item['icon']}}" style="width: 25px; margin-left: auto; margin-right: auto; display: block;" />
                            <span class="tts__nav__tabs__span">{{item['label']}}</span>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="tts__filter__heading p-2">
                <span><b>Airlines</b></span>
            </div>
            <div class="p-2">
                <ul class="list-unstyled mb-1">
                    <li ng-repeat="(key,item) in FlightFilter[1]['Airline']">
                        <label class="tts__inputradio_label tts__checkbox__label">
                            <input type="checkbox" name="airline" value="{{item['value']}}" class="me-1 tts__checkbox__input" ng-click="doFilter(1,'Airline',$event,key);" ng-model="item.isChecked" />
                            <span>{{item['label']}}</span>
                            <span class="float-end {{airlineLogoClass}} size-18 x{{item['value']}}"></span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
