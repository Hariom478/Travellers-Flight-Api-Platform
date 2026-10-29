<section>
    <div class="container">
        <div class="row">
            <div class="table_title">
                <div class="sale_bar">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h5 class="m-0">Group Ticket</h5>
                        </div>
                        <div class="col-md-8">
                            <div class="text-md-end">
                                <a href="<?php echo site_url("groupticket/request-list"); ?>" class="btn btn-primary btn-sm">Group Request List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="<?php echo site_url("groupticket/generate-ticket"); ?>" method="POST" tts-form="true" name="group-ticket" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <h5 class="card-title ">Flight Details</h5>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20">
                                            <label class="form-label">User Type *</label>
                                            <select class="form-select" id="user-type" name="user_type" placeholder="User Type">
                                                <option value="Individual" selected="">Individual</option>
                                                <option value="Corporate">Corporate</option>
                                                <option value="Travel_Agent">Travel Agent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20">
                                            <label class="form-label">Flight Type *</label>
                                            <select class="form-select" id="flight-type" name="flight_type" placeholder="Flight Type">
                                                <option value="">Please Select</option>
                                                <option value="domestic">Domestic</option>
                                                <option value="international">International</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20">
                                            <label class="form-label">Journey Type *</label>
                                            <select class="form-select" name="journey_type" id="journey-type" placeholder="Journey Type">
                                                <option value="">Please Select</option>
                                                <option value="oneway">Oneway</option>
                                                <option value="roundtrip">RoundTrip</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20">
                                            <label class="form-label">Origin *</label>
                                            <input class="form-control" type="text" name="origin_airport_code" tts-flight-origin="true" placeholder="Origin">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20 ">
                                            <label class="form-label">Destination *</label>
                                            <input class="form-control" type="text" name="destination_airport_code" tts-flight-destination="true" placeholder="Destination">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20 ">
                                            <label class="form-label">Departure Date *</label>
                                            <input class="form-control" type="text" name="depart_date" placeholder="Departure Date" readonly flight-departure-date="true">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20">
                                            <label class="form-label">Preferred Classs *</label>
                                            <select class="form-select" name="cabin_class" placeholder="Preferred Class">
                                                <option value="Any" selected="">Any</option>
                                                <option value="Economy">Economy</option>
                                                <option value="PremiumEconomy">Premium Economy</option>
                                                <option value="PremiumBusiness">Premium Business</option>
                                                <option value="First">First</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20 ">
                                            <label class="form-label">Airline Preference *</label>
                                            <input type="text" class="form-control inputtext  airlinelist" tts-get-airline="true" placeholder="Airline Preference" name="onward_airline_prefrence">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20 ">
                                            <label class="form-label">Flight Number *</label>
                                            <input type="text" class="form-control inputtext  airlinelist" placeholder="Flight Number" name="origin_preference_flight_number">
                                        </div>
                                    </div>

                                    <div class="col-md-9 mb-3" tts-return-html="true">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <h5 class="card-title ">Passenger Contact Information</h5>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="form-group form-mb-20 ">
                                            <label class="form-label">Email *</label>
                                            <input type="email" class="form-control inputtext" placeholder="Email" name="contact_email">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group form-mb-20">
                                            <label class="form-label">Mobile Number *</label>
                                            <input type="text" class="form-control" placeholder="Mobile Number" name="contact_number">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group form-mb-20">
                                            <label class="form-label">Address *</label>
                                            <input type="text" class="form-control" placeholder="Address" name="address">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20">
                                            <label class="form-label">City *</label>
                                            <input type="text" class="form-control" placeholder="City" name="city">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20">
                                            <label class="form-label">State *</label>
                                            <input type="text" class="form-control" placeholder="State" name="state">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20">
                                            <label class="form-label">Country *</label>
                                            <input type="text" class="form-control" placeholder="Country" name="country">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group form-mb-20">
                                            <label class="form-label">Pincode *</label>
                                            <input type="text" class="form-control" placeholder="Pincode" name="pincode">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-mb-20">
                                            <label class="form-label">Remark </label>
                                            <input type="text" class="form-control" placeholder="Remark" name="remark">
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3 ">
                                    <div class="col-md-12">
                                        <div class="bg-light p-2 rounded-2">
                                            <div class="row align-items-center">
                                                <div class="col-md-6 ">
                                                    <h5 class="card-title m-0">Passenger Details</h5>
                                                </div>
                                                <div class="col-md-6 text-end ">
                                                    <button type="button" class="btn btn-sm btn-success pax-add adult-pax" data-attr="adult" data-count="0">+ Adult</button>
                                                    <button type="button" class="btn btn-sm btn-primary pax-add" data-attr="child" data-count="0">+ Child</button>
                                                    <button type="button" class="btn btn-sm btn-secondary pax-add" data-attr="infant" data-count="0">+ Infant</button>
                                                </div>
                                                <div class="tts-pax-info"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>