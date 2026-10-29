<main ng-app="myApp" ng-controller="myCtrl" scroll>
	<div class="container" ng-if="loading==true">
		<?php echo view('Modules/Holiday/Views/frentend\loading-page.php'); ?>
	</div>
	<div class="tts__flight__result__page py-3 ng-cloak" ng-if="loading==false">
		<div class="container">
			<div class="row">
				<?php echo view('Modules/Holiday/Views/frentend\modify-search'); ?>
				<!-- filter starts here -->
				<div class="col-sm-3" ng-if="ErrorCode==0">
					<?php echo view('Modules/Holiday/Views/frentend\holiday_filter'); ?>
				</div>
				<!-- filter ends here -->

				<div class="col-sm-9" ng-if="ErrorCode==0">
					<div class="row">
						<div class="col-sm-12 mt-3">
							<div class="tts__show__result p-2">
								<div class="row">
									<div class="col-sm-4">
										<div class="tts__showing__result__heading">
											<b>Showing Result {{ FilteredResultCount }} of {{ TotalResult }} Holidays</b>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="text-end tts__showing__price__filter">
											<div class="btn-group dropdown">
												<button type="button" class="btn  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
													<span class="text-muted mr-2">SORT BY:</span> {{topsortingtext}}
												</button>
												<div class="dropdown-menu dropdown-menu-right">
													<button class="dropdown-item" type="button" ng-click="orderby('PublishedPrice',false,$event)">Price - Low to High
													</button>
													<button class="dropdown-item" type="button" ng-click="orderby('PublishedPrice',true,$event)">Price - High to Low
													</button>
												</div>
											</div>
										</div>

									</div>
									<div class="col-sm-2">
										<div class="form-check form-switch float-right pt-2">
											<input class="form-check-input" type="checkbox" role="switch" ng-model="shownetfare" id="net-fare">
											<label class="form-check-label" for="net-fare">Show Net Price</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!----col-sm--12-->
						<div class="col-sm-12 " ng-repeat="(key,holiday)  in Results | orderBy:field:reverse | limitTo :limit">

							<div class="hotal-filter-card">
								<div class="col-md-12">
									<div class="hotel_box mt-3">
										<div class="row d-flex align-items-center">
											<div class="col-md-4">
												<img ng-src="{{holiday.HolidayImage}}" alt="{{holiday.Name}}" alt="Trendy Pants and Shoes" class="img-fluid rounded" style="height:200px; width:100%; object-fit:cover"/>
											</div>
											<div class="col-md-6">
												<span class=" m0 f16 pfc3"><b>{{holiday.Name}}</b></span>
												<div class="d-flex align-items-center mb-2">
													<span class=""> <span class="">{{holiday.DaysNights}}</span>

													</span> <span class="ms-2">Customizable</span>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="mb-2">
															<p class="m0 f12 pfc3 pb4 fw7"><b> Includeds: &nbsp;</b>{{holiday.Includes}}
															</p>
														</div>
														<div class=" d-flex align-items-center mb-2">
															<p class="float-left position-relative  m0 mr4">
																<b>Cities: &nbsp; </b></p>
															<ul class="d-flex align-items-center">
																<li class="me-2" ng-repeat="(key,item) in holiday.itinerary">
																	{{item.city}} ({{key+1}}D)
																</li>
															</ul>
														</div>
														<div class="">{{holiday.Description | trusthtml }}
														</div>
													</div>
												</div>

											</div>
											<div class="col-md-2 text-end">
												<div class="hotal_price">
													<div class="d-block">
														<h4 class="ng-star-inserted "> ₹ {{holiday.priceBreakup.PublishedPrice}} </h4>
														<span class="tts__flight__price text-success d-block" ng-if="shownetfare"> ₹ {{holiday.priceBreakup.OfferedPrice}}</span>
													</div>
													<div class="float-right">
														<a href="<?php echo site_url('holiday-booking/holiday-details?id=') ?>{{holiday.id}}&slug={{holiday.Slug}}" target="_blank">
															<span class="badge badge_bg text-end">Book Now</span> </a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
						<!-----Filter No ------------>
						<div class="no-bus tts-api-error-msg mt-4" ng-if="FliterNo">
							<p class="mt-4">No holiday found that matches your filter criteria.Reset your filter </p>
							<button type="button" class="btn btn-primary btn-sm" ng-click="clearFilterAll('all')">Reset All Filter
							</button>
						</div>
						<!-----Filter No ------------>
					</div>
				</div>
				<!-------------API Error  ------------>
				<div class="col-12 tts-api-error-msg" ng-if="ErrorCode!=0">
					<img src="<?php echo site_url('webroot/img/no-cab-found.png'); ?>">
					<h5 class="mt-4"> {{errormessage}}</h5>
					<p class="mb-2">No holiday found for your selected destination. Please change your criteria</p>
					<a href="<?php echo site_url('holiday-booking'); ?>" class="btn btn-outline-danger">SEARCH HOLIDAYS</a>
				</div>
				<!-------------API Error  ------------>
			</div>
		</div>
	</div>
</main>


<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
<script>
	let url = "<?php echo site_url();?>";
	let limitStep = 20;
	let price = [];
	let ThemesName = [];
	let DestinationsName = [];
	let DaysNightsName = [];

	var app = angular.module('myApp', []);

	app.filter("trusthtml", ['$sce', function ($sce) {
		return function (htmlCode) {
			return $sce.trustAsHtml(htmlCode);
		}
	}]);


	app.controller('myCtrl', function ($scope, $http) {
		$scope.loading = true;
		$scope.topsortingtext = 'Price - Low to High';
		$scope.shareresult = false;
		$scope.shownetfare = false;
		$scope.FliterNo = false;
		$scope.ShareCheckbox = {};
		$http({
			method: "GET",
			url: url + "holiday-booking/holiday-result?" + "<?php  echo http_build_query($_GET);?>",
		}).then(function mySuccess(response) {
			$scope.loading = false;
			$scope.ErrorCode = response.data.Error.ErrorCode;
			$scope.ErrorMessage = response.data.Error.ErrorMessage;
			if ($scope.ErrorCode == 0) {
				$scope.Results = response.data.Result.Holidays;
				$scope.FilterResults = response.data.Result.Holidays;
				$scope.FilteredResultCount = $scope.Results.length;
				$scope.limit = limitStep;
				$scope.TotalResult = response.data.TotalResults;
				$scope.MinPrice = Number(response.data.Result.Min);
				$scope.MaxPrice = Number(response.data.Result.Max);
				$scope.HolidayDestinations = response.data.Result.Destinations;
				$scope.HolidayThemes = response.data.Result.Themes;
				$scope.HolidayDaysNights = response.data.Result.DaysNights;
				setTimeout(function () {
					$scope.Priceslider();
				}, 200);

			} else {
				$scope.errormessage = $scope.ErrorMessage;
			}
		}, function myError(response) {
			$scope.errormessage = response.statusText;
		});

		/**Filter Code Start Here*/
		$scope.orderby = function (field, order = null, event = null) {
			if (order === null) {
				$scope.reverse = ($scope.field === field) ? !$scope.reverse : true;
				$scope.field = field;
			} else {
				$scope.reverse = order;
				$scope.field = field;
				$scope.topsortingtext = event.target.innerHTML;
			}
		}
		$scope.multiFilter = function (array, filters) {
			/*console.log(array);
			console.log(filters);*/
			let newfilters = [];
			var filterKeys = Object.keys(filters);
			var newfilterKeys = Object.keys(newfilters);
			let result = [];
			if (filterKeys.includes("Destinations") || filterKeys.includes("Themes")) {

				array.filter((mainitem, mainkey) => {
					if (mainitem['Destinations']) {
						mainitem['Destinations'].filter((item1) => {
							if (filters['Destinations'].indexOf(item1['Name']) !== -1) {
								result.push(array[mainkey]);
							}
						});
					}
				});


				//console.log(response)
				return result;


			} else {
				return array.filter((item) => {
					return filterKeys.every(key => !!~filters[key].indexOf(item[key]));
				});
			}
		}

		$scope.multiFilters = function (filtertype, array, filters) {

			var filterKeys = Object.keys(filters);
			let newfilters = [];
			filterKeys.forEach(function (value, key) {
				if (value != 'IsRefundable') {
					newfilters[value] = filters[value];
				}
			});
			var newfilterKeys = Object.keys(newfilters);
			let response = [];
			array.filter((item) => {
				if (filterKeys.includes("IsRefundable")) {
					let fareitem = [];
					item['FareList'].forEach(function (value, key) {
						if (filters['IsRefundable'].indexOf(value['IsRefundable']) !== -1) {
							fareitem.push(value);
						}
					});
					if (newfilterKeys.every(key1 => !!~newfilters[key1].indexOf(item['MainSegment'][filters][key1]))) {
						if (fareitem.length != 0) {
							let obj = Object.assign({}, item);
							obj['FareList'] = fareitem;
							response.push(obj);
						}
					}
				} else {
					if (filterKeys.every(key => !!~filters[key].indexOf(item['MainSegment'][filtertype][key]))) {
						response.push(item);
					}
				}
			});
			return response;
		}

		$scope.doFilter = function (type, event, item) {

			if (type == "ThemesName") {
				if (event.target.checked) {
					item['isChecked'] = true;
				} else {
					item['isChecked'] = false;
				}

			}

			if (type == "DestinationsName") {
				if (event.target.checked) {
					item['isChecked'] = true;
				} else {
					item['isChecked'] = false;
				}
			}

			if (type == "DaysNightsName") {
				if (event.target.checked) {
					item['isChecked'] = true;
				} else {
					item['isChecked'] = false;
				}
			}

			let filters = [];
			let filteredResult = [];
			let Themes = [];
			let Destinations = [];
			let DaysNights = [];


			Themes = $scope.checkedfilter($scope.HolidayThemes);
			Destinations = $scope.checkedfilter($scope.HolidayDestinations);
			DaysNights = $scope.checkedfilter($scope.HolidayDaysNights);

			if (Themes.length !== 0) {
				filters['Themes'] = Themes;
			}
			if (Destinations.length !== 0) {
				filters['Destinations'] = Destinations;
			}
			if (DaysNights.length !== 0) {
				filters['DaysNights'] = DaysNights;
			}

			if (type == 'clear') {
				filters = [];
			}

			filteredResult = $scope.multiFilter($scope.FilterResults, filters);

			if (filteredResult.length == 0) {
				$scope.FliterNo = true;
			} else {
				$scope.FliterNo = false;
			}
			$scope.FilteredResultCount = filteredResult.length;
			$scope.Results = filteredResult;
			$scope.limit = limitStep;

		}
		$scope.Priceslider = function () {
			$ = jQuery.noConflict();
			price_min = $scope.MinPrice;
			price_max = $scope.MaxPrice
			var step = 0.01;
			$(".price-range").slider({
				range: true,
				min: price_min,
				max: price_max,
				step: parseFloat(step),
				values: [price_min, price_max],
				slide: function (event, ui) {
					$(".left-price").val(ui.values[0].toString());
					$(".right-price").val(ui.values[1].toString());
				},
				stop: function (event, ui) {
					var min = ui.values[0];
					var max = ui.values[1];
					let filters = [];
					let filteredResult = [];

					let Themes = [];
					let Destinations = [];
					let DaysNights = [];


					Themes = $scope.checkedfilter($scope.HolidayThemes);
					Destinations = $scope.checkedfilter($scope.HolidayDestinations);
					DaysNights = $scope.checkedfilter($scope.HolidayDaysNights);

					if (Themes.length !== 0) {
						filters['Themes'] = Themes;
					}
					if (Destinations.length !== 0) {
						filters['Destinations'] = Destinations;
					}
					if (DaysNights.length !== 0) {
						filters['DaysNights'] = DaysNights;
					}


					var filtered = $scope.multiFilter($scope.FilterResults, filters);
					angular.forEach(filtered, function (item) {
						if (item.StandardPrice >= min && item.StandardPrice <= max) {
							filteredResult.push(item);
						}
					});

					$scope.FilteredResultCount = filteredResult.length;
					if (filteredResult.length == 0) {
						$scope.FliterNo = true;
					} else {
						$scope.FliterNo = false;
					}

					$scope.limit = limitStep;
					$scope.Results = filteredResult;
					$scope.$evalAsync();
				}
			});
			$(".left-price").val($(".price-range").slider("values", 0).toString());
			$(".right-price").val($(".price-range").slider("values", 1).toString());
		}
		$scope.checkedfilter = function (array) {
			let response = [];
			array.forEach(function (value, key) {
				if (value.isChecked) {
					response.push(value.label);
				}
			});
			return response;
		}
		$scope.resetfilter = function (array) {
			let response = [];
			array.forEach(function (value, key) {
				value.isChecked = false;
				response.push(value);
			});
			return response;
		}
		$scope.clearFilterAll = function (type) {
			if (type == 'all') {
				$scope.resetfilter($scope.HolidayDaysNights);
				angular.element($(".price-range").slider("values", 0, $scope.MinPrice));
				angular.element($(".price-range").slider("values", 1, $scope.MaxPrice));
				angular.element($(".left-pricel").val($scope.MinPrice));
				angular.element($(".right-price").val($scope.MaxPrice));
				$scope.Priceslider();
			}
			$scope.doFilter('clear', '', '');
		}
		$scope.ShareItinerary = function () {
			$scope.shareresult = !$scope.shareresult;
			$scope.ShareCheckbox = {};
		}
		$scope.ShareData = function (type, event) {
			var output = [];
			angular.forEach($scope.ShareCheckbox, function (value, key) {
				if (value) {
					output.push(key);
				}
			});
			if (output.length == 0) {
				alert('Please select atleast one checkbox');
			} else {
				if (type == 'EmailBox') {
					let emailModal = document.getElementById("BusQuotation");
					if (emailModal !== null) {
						new bootstrap.Modal(emailModal).show();
					}
					return false;
				}
				let data = {};
				if (type == 'Email') {
					data = {'itinerary': output, 'type': type, 'email': $scope.email};
				} else {
					data = {'itinerary': output, 'type': type};
				}
				$http({
					method: "POST",
					url: url + "bus/share-itinerary",
					headers: {
						'Content-Type': undefined
					},
					data: data
				}).then(function mySuccess(response) {


				}, function myError(response) {
					alert(response.statusText)
				});
			}
		}
		/**Filter Code End Here*/


	});
	app.directive("scroll", function ($window) {
		return function (scope, element) {
			angular.element($window).bind("scroll", function () {
				var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
				var body = document.body, html = document.documentElement;
				var docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);
				windowBottom = windowHeight + window.pageYOffset + 1000;
				if (windowBottom >= docHeight) {
					scope.limit += limitStep;
				}
				scope.$apply();
			});
		};
	});
</script>