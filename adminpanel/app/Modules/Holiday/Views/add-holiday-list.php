<style>
    .back-praveen-danger {
        border-bottom: 3px solid #af0909 !important;
    }

    .praveen-border {
        border: solid #af0909 2px !important;
        border-radius: 5px;
    }
</style>
<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="m-0"> Add
                            <?php echo $title ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="page-content-area">
                <div class="card-body">
                    <form name="web-partner" tts-form='true'
                        action="<?php echo site_url('holiday/add-holiday-list-save'); ?>" method="POST"
                        id="web-partner">
                        <div class="row">
                            <div class="col-md-12 ">
                                <h6 class="view_head">Holiday Package information</h6>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Package Name *</label>
                                    <input class="form-control" type="text" name="holiday_name"
                                        placeholder="Package Name" onblur='tts_slug_url(this.value,"package-slug")'>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Package Slug *</label>
                                    <input class="form-control" type="text" name="holiday_slug" id="package-slug"
                                        placeholder="Holiday Slug">
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Starting Price Standard *</label>
                                    <input class="form-control" type="text" name="starting_price_standard"
                                        placeholder="Starting Price Standard">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Starting Price Deluxe </label>
                                    <input class="form-control" type="text" name="starting_price_deluxe"
                                        placeholder="Starting Price Deluxe">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Starting Price Luxury </label>
                                    <input class="form-control" type="text" name="starting_price_luxury"
                                        placeholder="Starting Price Luxury">
                                </div>
                            </div>

                            <!-- <div class="col-md-3">
                             <div class="form-group form-mb-20">
                                 <label>Advance payment value in percentage *</label>
                                 <input class="form-control" type="text" name="advance_value"
                                        placeholder="Advance payment value in percentage">
                             </div>
                         </div>

                         <div class="col-md-3">
                             <div class="form-group form-mb-20">
                                 <label>Advance payment before days *</label>
                                 <input class="form-control" type="text" name="advance_days"
                                        placeholder="Advance payment before days">
                             </div>
                         </div>
                            -->
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Package Includes *</label>
                                    <select class="form-control select_search holiday_includes"
                                        name="holiday_includes[]" placeholder="Package Includes" multiple="multiple">

                                        <?php
                                        $package_includes = package_includes();
                                        if (!empty($package_includes) && is_array($package_includes)) {
                                            foreach ($package_includes as $key_includes => $include) { ?>
                                                <option value="<?php echo $key_includes ?>">
                                                    <?php echo $include ?>
                                                </option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Package Image *</label>
                                    <input class="form-control" type="file" name="holiday_image"
                                        placeholder="Holiday Image">
                                </div>
                            </div>
                        </div>
                        <div class="row mb_10">
                            <div class="col-md-3">

                                <label class="mt20">
                                    <input type="checkbox" name="price_on_request" value="1" class="Lead">Price on
                                    request
                                </label>

                            </div>

                            <div class="col-md-3">

                                <label class="mt20">
                                    <input type="checkbox" name="show_on_home" value="1" class="Lead">Show on Home
                                </label>

                            </div>

                            <div class="col-md-3">

                                <label class="mt20">
                                    <input type="checkbox" name="passport_required_adult" value="1"
                                        class="Lead">Passport
                                    Required for Adult
                                </label>
                                <div class="tts-tooltip disply-inline">
                                    <span class="tts-icon information "></span>
                                    <span class="tts-tooltip-text ">Check if passport required for adult in online
                                        booking</span>
                                </div>
                            </div>

                            <div class="col-md-3 hide">

                                <label class="mt20">
                                    <input type="checkbox" name="passport_required_child" value="1"
                                        class="Lead">Passport
                                    Required for Child
                                </label>
                                <div class="tts-tooltip disply-inline">
                                    <span class="tts-icon information "></span>
                                    <span class="tts-tooltip-text ">Check if passport required for child in online
                                        booking</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="mt20">
                                    <input type="checkbox" name="pan_card_required_adult" value="1" class="Lead">Pan
                                    Card
                                    Required for Adult
                                </label>
                                <div class="tts-tooltip disply-inline">
                                    <span class="tts-icon information "></span>
                                    <span class="tts-tooltip-text ">Check if pan card required for adult in online
                                        booking</span>
                                </div>
                            </div>

                            <div class="col-md-3 hide">
                                <label class="mt20">
                                    <input type="checkbox" name="pan_card_required_child" value="1" class="Lead">Pan
                                    Card
                                    Required for Child
                                </label>
                                <div class="tts-tooltip disply-inline">
                                    <span class="tts-icon information "></span>
                                    <span class="tts-tooltip-text ">Check if pan card required for child in online
                                        booking</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="mt20">
                                    <input type="checkbox" name="book_online" value="1" class="Lead">Book Online
                                </label>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12 ">
                                <h6 class="view_head"> Holiday Package And Other information</h6>
                            </div>
                            <div id="tabs">
                                <ul>
                                    <li><a href="#tts-holiday-themes">Holiday Themes</a></li>
                                    <li><a href="#tts-holiday-destinations">Holiday Destinations</a></li>
                                    <li><a href="#tts-holiday-inclusions">Holiday Inclusions</a></li>
                                    <li><a href="#tts-holiday-exclusion">Holiday Exclusion</a></li>
                                    <li><a href="#tts-package-description">Package Description</a></li>
                                    <li><a href="#tts-package-policy">Terms & Conditions</a></li>

                                    <li><a href="#tts-payment-structure">Payment Structure</a></li>
                                    <li><a href="#tts-documentation">Documentation</a></li>
                                    <li><a href="#tts-cancellation-policy">Cancellation policy</a></li>
                                    <li><a href="#tts-refund-policy">Refund Policy</a></li>
                                </ul>
                                <div id="tts-holiday-themes">
                                    <!--<div class="row">
                                <div class="col-md-12 text-md-right">
                                    <button class="badge badge-wt" view-data-modal="true" data-controller='holiday'
                                            data-href="<?php /*echo site_url('holiday/add-themes-template') */ ?>">
                                        <i class="tts-icon add "></i> Add Theme
                                    </button>
                                </div>
                                <div class="col-md-12 mt20">
                                    <input class="form-control" type="text" placeholder="Search Themes">
                                </div>
                            </div>-->

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <input tts-input-box-value="holiday-themes" type="text"
                                                placeholder="Search.." class="form-control">
                                        </div>
                                    </div>

                                    <div class="row gy-2">
                                        <div class="col-md-12">
                                            <h6 class="tts-holiday-themes"></h6>
                                        </div>
                                        <?php if ($themes_lists) {
                                            foreach ($themes_lists as $theme) {
                                                ?>

                                                <div class="col-md-4" holiday-themes>
                                                    <label>
                                                        <input type="checkbox" name="holiday_themes_id[]"
                                                            value="<?php echo $theme['id'] ?>" class="Lead">
                                                        <?php echo $theme['theme_name'] ?>
                                                    </label>

                                                </div>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                                <div id="tts-holiday-destinations">
                                    <!--<div class="row">
                                <div class="col-md-12 text-md-right">
                                    <button class="badge badge-wt" view-data-modal="true" data-controller='holiday'
                                            data-href="<?php /*echo site_url('holiday/add-destination-template') */ ?>">
                                        <i class="tts-icon add "></i> Add Destination
                                    </button>
                                </div>
                                <div class="col-md-12 mt20">
                                    <input class="form-control" type="text" placeholder="Search Destinations">
                                </div>
                            </div>-->

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <input tts-input-box-value="holiday-destination" type="text"
                                                placeholder="Search.." class="form-control">
                                        </div>
                                    </div>

                                    <div class="row gy-2">
                                        <div class="col-md-12">
                                            <h6 class="tts-holiday-destinations"></h6>
                                        </div>
                                        <?php if ($destination_lists) {
                                            foreach ($destination_lists as $destination) {
                                                ?>
                                                <div class="col-md-4" holiday-destination>
                                                    <label>
                                                        <input type="checkbox" name="holiday_destination_id[]"
                                                            value="<?php echo $destination['id'] ?>" class="Lead">
                                                        <?php echo $destination['destination_name'] ?>
                                                    </label>
                                                </div>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                                <div id="tts-holiday-inclusions">
                                    <!--<div class="row">
                                <div class="col-md-12 text-md-right">
                                    <button class="badge badge-wt" view-data-modal="true" data-controller='holiday'
                                            data-href="<?php /*echo site_url('holiday/add-inclusion-template') */ ?>">
                                        <i class="tts-icon add "></i> Add Inclusion
                                    </button>
                                </div>
                                <div class="col-md-12 mt20">
                                    <input class="form-control" type="text" placeholder="Search Inclusions">
                                </div>
                            </div>-->

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <input tts-input-box-value="holiday-inclusions" type="text"
                                                placeholder="Search.." class="form-control">
                                        </div>
                                    </div>

                                    <div class="row gy-2">
                                        <div class="col-md-12">
                                            <h6 class="tts-holiday-inclusions"></h6>
                                        </div>
                                        <?php if ($inclusion_lists) {
                                            foreach ($inclusion_lists as $inclusion) {
                                                ?>
                                                <div class="col-md-4" holiday-inclusions>

                                                    <label>
                                                        <input type="checkbox" name="holiday_inclusion_id[]"
                                                            value="<?php echo $inclusion['id'] ?>" class="Lead">
                                                        <?php echo $inclusion['inclusion_title'] ?>
                                                    </label>

                                                </div>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                                <div id="tts-holiday-exclusion">
                                    <!--<div class="row">
                                <div class="col-md-12 text-md-right">
                                    <button class="badge badge-wt" view-data-modal="true" data-controller='holiday'
                                            data-href="<?php /*echo site_url('holiday/add-exclusion-template') */ ?>">
                                        <i class="tts-icon add "></i> Add Exclusion
                                    </button>
                                </div>
                                <div class="col-md-12 mt20">
                                    <input class="form-control" type="text" placeholder="Search Exclusions">
                                </div>
                            </div>-->

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <input tts-input-box-value="holiday-exclusion" type="text"
                                                placeholder="Search.." class="form-control">
                                        </div>
                                    </div>

                                    <div class="row gy-2">
                                        <div class="col-md-12">
                                            <h6 class="tts-holiday-exclusion"></h6>
                                        </div>
                                        <?php if ($exclusion_lists) {
                                            foreach ($exclusion_lists as $exclusion) {
                                                ?>
                                                <div class="col-md-4" holiday-exclusion>

                                                    <label>
                                                        <input type="checkbox" name="holiday_exclusion_id[]"
                                                            value="<?php echo $exclusion['id'] ?>" class="Lead">
                                                        <?php echo $exclusion['exclusion_title'] ?>
                                                    </label>

                                                </div>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                                <div id="tts-package-description">
                                    <div class="col-md-12 mt20">
                                        <div class="form-group form-mb-20">
                                            <label>Holiday Description *</label>
                                            <textarea class="form-control tts-editornote" type="textarea"
                                                name="holiday_description" rows="3"
                                                placeholder="Holiday Description"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="tts-package-policy">
                                    <div class="col-md-12">
                                        <div class="form-group form-mb-20">
                                            <label>Terms & Conditions *</label>
                                            <textarea class="form-control tts-editornote" type="textarea"
                                                name="policies" rows="3" placeholder="Terms & Conditions">
                                              <?= (!empty($setting_data['polices'])) ? $setting_data['polices'] : '' ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>

                                <div id="tts-payment-structure">
                                    <div class="col-md-12">
                                        <div class="form-group form-mb-20">
                                            <label>Payment Structure *</label>
                                            <textarea class="form-control tts-editornote" type="textarea"
                                                name="payment_structure" rows="3" placeholder="Payment Structure">
                                              <?= (!empty($setting_data['payment_structure'])) ? $setting_data['payment_structure'] : '' ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="tts-documentation">
                                    <div class="col-md-12">
                                        <div class="form-group form-mb-20">
                                            <label>Documentation *</label>
                                            <textarea class="form-control tts-editornote" type="textarea"
                                                name="documentation" rows="3" placeholder="Documentation">
                                    <?= (!empty($setting_data['documentation'])) ? $setting_data['documentation'] : '' ?>
                                </textarea>
                                        </div>
                                    </div>
                                </div>

                                <div id="tts-refund-policy">
                                    <div class="col-md-12">
                                        <div class="form-group form-mb-20">
                                            <label>Refund Policy *</label>
                                            <textarea class="form-control tts-editornote" type="textarea"
                                                name="refund_policy" rows="3" placeholder="Refund Policy">
                                              <?= (!empty($setting_data['refund_policy'])) ? $setting_data['refund_policy'] : '' ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="tts-cancellation-policy">
                                    <div class="col-md-12">
                                        <div class="form-group form-mb-20">
                                            <label>Cancellation Policy *</label>
                                            <textarea class="form-control tts-editornote" type="textarea"
                                                name="cancellation_policy" rows="3" placeholder="Cancellation Policy">
                                              <?= (!empty($setting_data['cancellation_policy'])) ? $setting_data['cancellation_policy'] : '' ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mt20">
                                    <h6 class="view_head text-center">SEO Details</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-mb-20">
                                        <label>Meta Robots *</label>
                                        <select class="form-select" name="meta_robots" placeholder="Meta Robots">
                                            <option value="INDEX, FOLLOW" selected>INDEX, FOLLOW</option>
                                            <option value="NOINDEX, FOLLOW">NOINDEX, FOLLOW</option>
                                            <option value="INDEX, NOFOLLOW">INDEX, NOFOLLOW</option>
                                            <option value="NOINDEX, NOFOLLOW">NOINDEX, NOFOLLOW</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group form-mb-20">
                                        <label> Meta Title * </label>
                                        <input class="form-control" type="text" name="meta_title"
                                            placeholder="Meta Title">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-mb-20">
                                        <label> Meta Keyword* </label>
                                        <textarea class="form-control" type="tex" name="meta_keyword"
                                            placeholder="Meta Keyword" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-mb-20">
                                        <label> Meta Description* </label>
                                        <textarea class="form-control" type="text" name="meta_description"
                                            placeholder="Meta Description" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="submit_type" id="submit_type" value="1">
                            <div class="row">
                                <div class="col-md-12 text-md-right">
                                    <button class="btn btn-primary" type="submit"
                                        onclick="document.getElementById('submit_type').value='1'">Save
                                    </button>
                                    <button class="btn btn-primary btn-primary-second" type="button"
                                        onclick="document.getElementById('submit_type').value='2'; this.type=submit">Save
                                        &
                                        Continue
                                    </button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>