<div class="tts__filter booking-sidebar">
   <div class="widget filters">
      <h2 class="title">Filter Results</h2>
      <span><a href="javascript:void(0);" ng-click="clearFilterAll('all')" class="tts__fliter__reset">Reset All</a></span>
   </div>
   <div class="widget">
      <div class="filters-wrap">
         <h2 class="widget-title">Price</h2>
         <div class="price_filter">
            <div class="price-range price-filter"></div>
         </div>
         <div class="price_slider_amount">
            <span>Price :</span>
            <input type="text" class="left-price" readonly>
            <input type="text" class="right-price text-end" readonly>
         </div>
      </div>
   </div>
   <div class="widget">
      <h2 class="widget-title">Days & Nights</h2>
      <input type="text" class="form-control form-control1 operator-name-filter" placeholder="Search Days & Nights" ng-model="$myCtrl.searchbynights">
      <div id="bus_filter" class="bus0filter pt-2">
         <div class="promoOffersContent" ng-repeat="(key, item) in HolidayDaysNights | filter:$myCtrl.searchbynights:strict">
            <div class="form-check">
               <input class="form-check-input" type="checkbox" id="DaysNights{{key}}"
                  ng-click="doFilter('DaysNightsName',$event, item);" ng-model="item.isChecked"
                  operators-hit="true">
               <label class="form-check-label" for="DaysNights{{key}}">{{item.label}}
               </label>
            </div>
         </div>
      </div>
   </div>
   <div class="widget">
      <h2 class="widget-title">Destinations</h2>
      <input type="text" class="form-control form-control1 operator-name-filter" placeholder="Search Destinations" ng-model="$myCtrl.searchbydestinations">
      <div id="bus_filter" class="bus0filter pt-2">
         <div class="promoOffersContent" ng-repeat="(key, item) in HolidayDestinations | filter:$myCtrl.searchbydestinations:strict">
            <div class="form-check">
               <input class="form-check-input" type="checkbox" id="DestinationsName{{key}}"
                  ng-click="doFilter('DestinationsName',$event, item);" ng-model="item.isChecked"
                  operators-hit="true">
               <label class="form-check-label" for="DestinationsName{{key}}">{{item.label}}
               </label>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- <div class="tts__filter">
   <div class="d-flex justify-content-between p-2">
      <span><b>Filter Results</b></span>
      <span><a href="javascript:void(0);" ng-click="clearFilterAll('all')" class="tts__fliter__reset">Reset All</a></span>
   </div>
   <div class="tts__filter__heading p-2">
      <span><b>Price</b></span>
   </div>
   <style>
      p.tts-price-range input[type=text] {
      border: none;
      width: 50%;
      color: #111;
      float: left;
      font-size: 13px;
      box-shadow: unset !important;
      outline: 0;
      font-weight: 500;
      }
      div.price-filter {
      margin-top: 25px;
      margin-bottom: 10px;
      border: none !important;
      max-height: 6px;
      background-color: #e6e2e2;
      max-width: 92%;
      margin-left: 8px;
      }
   </style>
   <div class="p-2">
      <p class="tts-price-range">
         <input type="text" class="left-price" readonly>
         <input type="text" class="right-price text-end" readonly>
      </p>
      <div class="price-range price-filter"></div>
   </div>
   <div class="tts__filter__heading p-2">
      <span><b>Days & Nights</b></span>
   </div>
   <div class="p-2 mb-1">
      <input type="text" class="form-control form-control1 operator-name-filter" placeholder="Search Days & Nights"
         ng-model="$myCtrl.searchbynights">
   </div>
   <div id="bus_filter" class="bus0filter p-2">
      <div class="promoOffersContent" ng-repeat="(key, item) in HolidayDaysNights | filter:$myCtrl.searchbynights:strict">
         <div class="form-check">
            <input class="form-check-input" type="checkbox" id="DaysNights{{key}}"
               ng-click="doFilter('DaysNightsName',$event, item);" ng-model="item.isChecked"
               operators-hit="true">
            <label class="form-check-label" for="DaysNights{{key}}">{{item.label}}
            </label>
         </div>
      </div>
   </div>
   <div class="tts__filter__heading p-2">
      <span><b>Destinations</b></span>
   </div>
   <div class="p-2 mb-1">
      <input type="text" class="form-control form-control1 operator-name-filter" placeholder="Search Destinations" ng-model="$myCtrl.searchbydestinations">
   </div>
   <div id="bus_filter" class="bus0filter p-2">
      <div class="promoOffersContent"
         ng-repeat="(key, item) in HolidayDestinations | filter:$myCtrl.searchbydestinations:strict">
         <div class="form-check">
            <input class="form-check-input" type="checkbox" id="DestinationsName{{key}}"
               ng-click="doFilter('DestinationsName',$event, item);" ng-model="item.isChecked"
               operators-hit="true">
            <label class="form-check-label" for="DestinationsName{{key}}">{{item.label}}
            </label>
         </div>
      </div>
   </div>
   --
      <div class="tts__filter__heading p-2">
          <span><b>Themes</b></span>
      </div>
      <div class="p-2 mb-1">
          <input type="text" class="form-control form-control1" placeholder="Search Themes"
                 ng-model="$myCtrl.searchbyThemes">
      </div>
      <div id="bus_filter" class="bus0filter p-2">
          <div class="promoOffersContent" ng-repeat="(key, item) in HolidayThemes  | filter:$myCtrl.searchbyThemes:strict">
              <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="Themes{{key}}"
                         ng-click="doFilter('ThemesName',$event,item);" ng-model="item.isChecked">
                  <label class="form-check-label" for="Themes{{key}}">
                      {{item.label}}
                  </label>
              </div>
          </div>
      </div>--</div> -->