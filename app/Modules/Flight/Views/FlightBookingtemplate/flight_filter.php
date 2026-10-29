<div class="close-btn" ng-click="showHideMobileFilter('hide')">
   <i class="fa fa-close"></i>
</div>
<div class="tts__filter booking-sidebar">
   <div class="widget" ng-if="FlightFilter.length!=1">
      <nav>
         <div class="nav nav-tabs border-0 justify-content-center pt-2" id="nav-tab" role="tablist">
            <button class="btn btn-outline-dark btn-sm me-3 active" id="nav-filterob-tab" data-bs-toggle="tab"
               data-bs-target="#nav-filterob" type="button" role="tab" aria-controls="nav-home"
               aria-selected="true">Outbound</button>
            <button class="btn btn-outline-dark btn-sm" id="nav-filterib-tab" data-bs-toggle="tab"
               data-bs-target="#nav-filterib" type="button" role="tab" aria-controls="nav-profile"
               aria-selected="false">Inbound</button>
         </div>
      </nav>
   </div>
   <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade show active" id="nav-filterob" role="tabpanel" aria-labelledby="nav-filterob-tab">
         <div class="widget filters">
            <h2 class="title">Filter Results</h2>
            <span><a href="javascript:void(0);" class="tts__fliter__reset" ng-click="clearFilterAll(0,'all')">Reset
                  All</a></span>
         </div>
         <div class="widget d-flex align-items-center justify-content-between">
            <div>
               <i class="fa fa-share-alt"> </i> Share By:
               <button class="btn btn-success btn-sm" ng-if="sharebuttontext==''" ng-click="Shareby('OB','Whatsapp')"
                  data-bs-toggle="tooltip" data-bs-placement="top" title="Whatsapp">
                  <i class="fab fa-whatsapp"></i>
               </button>
               <button class="btn btn-secondary btn-sm" ng-if="sharebuttontext==''" ng-click="Shareby('OB','Email')"
                  data-bs-toggle="tooltip" data-bs-placement="top" title="Email"><i class="fa fa-envelope"></i></button>
               <button class="btn btn-secondary btn-sm" ng-if="sharebuttontext!=''"
                  ng-click="SendData('OB')">{{sharebuttontext}}</button>
               <button class="btn btn-secondary btn-sm" ng-click="Shareby('OB','Close')" ng-if="sharebuttontext!=''">
                  <i class="fa fa-close"></i></button>
            </div>
         </div>
         <div class="widget">
            <div class="filters-wrap">
               <h2 class="widget-title">Price</h2>
               <div class="price_filter">
                  <div class="price-range price-filter"></div>
               </div>
               <div class="price_slider_amount">
                  <span>Price :</span>
                  <input type="text" class="left-price" readonly />
                  <input type="text" class="right-price text-end" readonly />
               </div>
            </div>
         </div>
         <div class="widget">
            <h2 class="widget-title">Fare Type</h2>
            <ul class="airlines-cat-list">
               <li ng-repeat="(key,item) in FlightFilter[0]['FareType']">
                  <label class="tts__inputradio_label tts__checkbox__label">
                     <input type="checkbox" name="fare_type" value="{{item['value']}}" class="me-1 tts__checkbox__input"
                        ng-click="doFilter(0,'FareType',$event,item);" ng-model="item.isChecked" />
                     <span>{{item['label']}}</span>
                  </label>
               </li>
            </ul>
         </div>
         <div class="widget">
            <h2 class="widget-title">Stop</h2>
            <ul class="airlines-cat-list">
               <li ng-repeat="(key,item) in FlightFilter[0]['Stop']">
                  <label class="tts__inputradio_label tts__checkbox__label">
                     <input type="checkbox" name="stop" value="{{item['value']}}" class="me-1 tts__checkbox__input"
                        ng-click="doFilter(0,'Stop',$event,item);" ng-model="item.isChecked" />
                     <span>{{item['label']}}</span>
                  </label>
               </li>
            </ul>
         </div>
         <div class="widget">
            <h2 class="widget-title">Departure Times</h2>
            <ul class="nav tts__nav__tabs">
               <li class="nav-item" ng-repeat="(key,item) in FlightFilter[0]['DepartTime']">
                  <label class="w-100 tts__checkbox__label tts__flight__timing p-1"
                     ng-class="{'tts-filter-select':item.isChecked}">
                     <input type="checkbox" class="invisible tts__checkbox__input" name="depart_time"
                        value="{{item['value']}}" ng-click="doFilter(0,'DepartTime',$event,item);"
                        ng-model="item.isChecked" />
                     <img ng-if="item['icon']" src="{{item['icon']}}"
                        style="width: 25px; margin-left: auto; margin-right: auto; display: block;" />
                     <span class="tts__nav__tabs__span">{{item['label']}}</span>
                  </label>
               </li>
            </ul>
         </div>
         <div class="widget">
            <h2 class="widget-title">Arrival Times</h2>
            <ul class="nav tts__nav__tabs">
               <li class="nav-item" ng-repeat="(key,item) in FlightFilter[0]['ArrivalTime']">
                  <label class="w-100 tts__checkbox__label tts__flight__timing p-1"
                     ng-class="{'tts-filter-select':item.isChecked}">
                     <input type="checkbox" class="invisible tts__checkbox__input" name="depart_time"
                        value="{{item['value']}}" ng-click="doFilter(0,'ArrivalTime',$event,item);"
                        ng-model="item.isChecked" />
                     <img ng-if="item['icon']" src="{{item['icon']}}"
                        style="width: 25px; margin-left: auto; margin-right: auto; display: block;" />
                     <span class="tts__nav__tabs__span">{{item['label']}}</span>
                  </label>
               </li>
            </ul>
         </div>
         <div class="widget">
            <h2 class="widget-title">Airlines</h2>
            <ul class="airlines-cat-list">
               <li ng-repeat="(key,item) in FlightFilter[0]['Airline']">
                  <label class="tts__inputradio_label tts__checkbox__label">
                     <input type="checkbox" name="airline" value="{{item['value']}}" class="me-1 tts__checkbox__input"
                        ng-click="doFilter(0,'Airline',$event,key);" ng-model="item.isChecked" />
                     <span>{{item['label']}}</span>
                     <img src="<?php echo root_url; ?>uploads/airline-images/{{item['value']}}.png"
                        alt="{{item['label']}}" class=" airline-logo-small">
                  </label>
               </li>
            </ul>
         </div>
      </div>
      <div class="tab-pane fade" id="nav-filterib" role="tabpanel" aria-labelledby="nav-filterib-tab">
         <div class="widget filters">
            <h2 class="title">Filter Results</h2>
            <span><a href="javascript:void(0);" class="tts__fliter__reset" ng-click="clearFilterAll(1,'all')">Reset
                  All</a></span>
         </div>
         <div class="widget d-flex align-items-center justify-content-between">
            <div>
               <i class="fa fa-share-alt"> </i> Share By:
               <button class="btn btn-success btn-sm" ng-if="sharebuttontextib==''" ng-click="Shareby('IB','Whatsapp')"
                  data-bs-toggle="tooltip" data-bs-placement="top" title="Whatsapp">
                  <i class="fab fa-whatsapp"></i>
               </button>
               <button class="btn btn-secondary btn-sm" ng-if="sharebuttontextib==''" ng-click="Shareby('IB','Email')"
                  data-bs-toggle="tooltip" data-bs-placement="top" title="Email"><i class="fa fa-envelope"></i></button>
               <button class="btn btn-secondary btn-sm" ng-if="sharebuttontextib!=''"
                  ng-click="SendData('IB')">{{sharebuttontextib}}</button>
               <button class="btn btn-secondary btn-sm" ng-click="Shareby('IB','Close')" ng-if="sharebuttontextib!=''">
                  <i class="fa fa-close"></i></button>
            </div>
         </div>
         <div class="widget">
            <div class="filters-wrap">
               <h2 class="widget-title">Price</h2>
               <div class="price_filter">
                  <div class="price-range-return price-filter"></div>
               </div>
               <div class="price_slider_amount">
                  <span>Price :</span>
                  <input type="text" class="left-price-return" readonly />
                  <input type="text" class="right-price-return text-end" readonly />
               </div>
            </div>
         </div>
         <div class="widget">
            <h2 class="widget-title">Fare Type</h2>
            <ul class="airlines-cat-list">
               <li ng-repeat="(key,item) in FlightFilter[1]['FareType']">
                  <label class="tts__inputradio_label tts__checkbox__label">
                     <input type="checkbox" name="fare_type" value="{{item['value']}}" class="me-1 tts__checkbox__input"
                        ng-click="doFilter(1,'FareType',$event,item);" ng-model="item.isChecked" />
                     <span>{{item['label']}}</span>
                  </label>
               </li>
            </ul>
         </div>
         <div class="widget">
            <h2 class="widget-title">Stop</h2>
            <ul class="airlines-cat-list">
               <li ng-repeat="(key,item) in FlightFilter[1]['Stop']">
                  <label class="tts__inputradio_label tts__checkbox__label">
                     <input type="checkbox" name="stop" value="{{item['value']}}" class="me-1 tts__checkbox__input"
                        ng-click="doFilter(1,'Stop',$event,item);" ng-model="item.isChecked" />
                     <span>{{item['label']}}</span>
                  </label>
               </li>
            </ul>
         </div>
         <div class="widget">
            <h2 class="widget-title">Departure Times</h2>
            <ul class="nav tts__nav__tabs">
               <li class="nav-item" ng-repeat="(key,item) in FlightFilter[1]['DepartTime']">
                  <label class="w-100 tts__checkbox__label tts__flight__timing p-1"
                     ng-class="{'tts-filter-select':item.isChecked}">
                     <input type="checkbox" class="invisible tts__checkbox__input" name="depart_time"
                        value="{{item['value']}}" ng-click="doFilter(1,'DepartTime',$event,item);"
                        ng-model="item.isChecked" />
                     <img ng-if="item['icon']" src="{{item['icon']}}"
                        style="width: 25px; margin-left: auto; margin-right: auto; display: block;" />
                     <span class="tts__nav__tabs__span">{{item['label']}}</span>
                  </label>
               </li>
            </ul>
         </div>
         <div class="widget">
            <h2 class="widget-title">Arrival Times</h2>
            <ul class="nav tts__nav__tabs">
               <li class="nav-item" ng-repeat="(key,item) in FlightFilter[1]['ArrivalTime']">
                  <label class="w-100 tts__checkbox__label tts__flight__timing p-1"
                     ng-class="{'tts-filter-select':item.isChecked}">
                     <input type="checkbox" class="invisible tts__checkbox__input" name="depart_time"
                        value="{{item['value']}}" ng-click="doFilter(1,'ArrivalTime',$event,item);"
                        ng-model="item.isChecked" />
                     <img ng-if="item['icon']" src="{{item['icon']}}"
                        style="width: 25px; margin-left: auto; margin-right: auto; display: block;" />
                     <span class="tts__nav__tabs__span">{{item['label']}}</span>
                  </label>
               </li>
            </ul>
         </div>
         <div class="widget">
            <h2 class="widget-title">Airlines</h2>
            <ul class="airlines-cat-list">
               <li ng-repeat="(key,item) in FlightFilter[1]['Airline']">
                  <label class="tts__inputradio_label tts__checkbox__label">
                     <input type="checkbox" name="airline" value="{{item['value']}}" class="me-1 tts__checkbox__input"
                        ng-click="doFilter(1,'Airline',$event,key);" ng-model="item.isChecked" />
                     <span>{{item['label']}}</span>
                     <img src="<?php echo root_url; ?>uploads/airline-images/{{item['value']}}.png"
                        alt="{{item['label']}}" class=" airline-logo-small">
                  </label>
               </li>
            </ul>
         </div>
      </div>
   </div>
</div>