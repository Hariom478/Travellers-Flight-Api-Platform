<?php
if (isset(admin_cookie_data()['admin_comapny_detail']['whitelabel_user']) && admin_cookie_data()['admin_comapny_detail']['whitelabel_user'] == "active") {
    $whitelabel_user_status = "active";
} else {
    $whitelabel_user_status = "inactive";
}
$whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];
?>

<nav class="sidebar">
    <div class="sidebar-header">
        <a href="<?php echo site_url("dashboard"); ?>" class="sidebar-brand">
            Admin<span></span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="sidebar-body ps">
        <ul class="nav" id="navigation">
            <li class="nav-item p_active <?php echo active_nav('Dashboard') ?>">
                <a href="<?php echo site_url("dashboard"); ?>" class="nav-link">
                    <i class="fa fa-dashboard"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Setting", "Setting_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#settings" role="button" aria-expanded="false"
                            aria-controls="settings">
                            <i class="fa fa-cog"></i>
                            <span class="link-title">Settings</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="settings">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("setting"); ?>" class="nav-link"><i class="fa fa-circle"></i>
                                        Company Settings</a>
                                </li>

                                <?php if (permission_access("Setting", "user_list")) { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("setting/user-management"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> User Management</a>
                                    </li>
                                <?php } ?>

                                <?php if (permission_access("Setting", "convenience_fee_list")) { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("convenience-fee"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Convenience Fee</a>
                                    </li>
                                <?php } ?>

                                <?php if (isset($whitelabel_setting_data['b2b_business']) && $whitelabel_setting_data['b2b_business'] == "active") { ?>
                                    <?php if (permission_access("Setting", "bank_account_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("bankaccounts"); ?>" class="nav-link"><i
                                                    class="fa fa-circle"></i> Bank Account</a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>

                                <?php if (permission_access("Setting", "offline_supplier_list")) { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("offline-issue-supplier"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Offline Supplier</a>
                                    </li>
                                <?php } ?>

                                <?php if (isset($whitelabel_setting_data['is_direct_website']) && $whitelabel_setting_data['is_direct_website'] == "active") { ?>
                                    <?php if (permission_access("Setting", "airport_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("flightsettings"); ?>" class="nav-link"><i
                                                    class="fa fa-circle"></i> Flight Airport List</a>
                                        </li>
                                    <?php } ?>

                                    <?php if (permission_access("Setting", "airlines_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("flightsettings/flight-airlines-list"); ?>" class="nav-link"><i
                                                    class="fa fa-circle"></i> Flight Airline List</a>
                                        </li>
                                    <?php } ?>



                                    <?php if (permission_access("Setting", "flight_fare_type_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("flightfaretype"); ?>" class="nav-link"><i
                                                    class="fa fa-circle"></i> Flight Fare Type</a>
                                        </li>
                                    <?php } ?>

                                <?php } ?>

                                <li class="nav-item">
                                    <a href="<?php echo site_url("notification"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Notification</a>
                                </li>


                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>


            <?php if ($whitelabel_user_status == "active" && ((isset($whitelabel_setting_data['b2c_business']) && $whitelabel_setting_data['b2c_business'] == "active") || (isset($whitelabel_setting_data['b2b_business']) && $whitelabel_setting_data['b2b_business'] == "active"))) { ?>

                <?php if (permission_access("Accounts", "Accounts_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#accounts" role="button" aria-expanded="false"
                            aria-controls="accounts">
                            <i class="fa fa-user"></i>
                            <span class="link-title">Accounts Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="accounts">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("accounts/wl-payment-history"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Payment History</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("sale-result"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Sales Report</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("webpartneraccounts/credit-notes"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i>Credit Notes</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>
            <!-- <li class="nav-item nav-category">Services</li> -->
            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['flight_module']) && $whitelabel_setting_data['flight_module'] == "active") { ?>
                <?php if (permission_access("Flight", "Flight_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#flight" role="button" aria-expanded="false"
                            aria-controls="flight">
                            <i class="fa fa-plane"></i>
                            <span class="link-title">Flight Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="flight">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/flight-markup"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Markup</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/flight-discount"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Discount</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/bookings"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Booking List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/booking-calender"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Booking Calender</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/flight-amendments"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Amendment List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/flight-refunds"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Refund List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight-ticket-upload"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Upload Ticket</a>
                                </li>


                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight-top-routes"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Top Routes</a>
                                </li>
                                <?php if (isset($whitelabel_setting_data['is_direct_website']) && $whitelabel_setting_data['is_direct_website'] == "active") { ?>

                                    <li class="nav-item">
                                        <a href="<?php echo site_url("flightoffline"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Offline Flight</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("supplier-management"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Supplier Management </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="<?php echo site_url("flight-ticket-import"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Import PNR </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['flight_extranet_module']) && $whitelabel_setting_data['flight_extranet_module'] == "active") { ?>
                <?php if (permission_access("FlightExtranet", "FlightExtranet_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#private-fare" role="button" aria-expanded="false"
                            aria-controls="FlightExtranet">
                            <i class="fa fa-plane"></i>
                            <span class="link-title">Flight Extranet Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="private-fare">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("private-fare/add-private-fare-page"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Add Private Fare </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("private-fare/private-fare-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Private Fare List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("private-fare/fare-rule"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Add Fare Rule</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("private-fare/fare-rule-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Fare Rule List</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>







            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['holiday_module']) && $whitelabel_setting_data['holiday_module'] == "active") { ?>
                <?php if (permission_access("Holiday", "Holiday_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#holiday" role="button" aria-expanded="false"
                            aria-controls="holiday">
                            <i class="fa fa-umbrella"></i>
                            <span class="link-title">Holiday Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="holiday">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("holiday/holiday-markup-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Holiday Markup</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("holiday/holiday-discount-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Holiday Discount</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("holiday/add-holiday-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Add Holiday List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("holiday"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i>Holiday List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("holiday/booking-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Holiday Booking List </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("holiday/amendments"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Holiday Amendments List </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("holiday/refunds"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Holiday Refunds List </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("holiday/settings"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Holiday Settings</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("holiday/query-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Holiday Query List</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>













            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['b2b_business']) && $whitelabel_setting_data['b2b_business'] == "active") { ?>
                <?php if (permission_access("Agent", "Agent_Module")) { ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url("agent"); ?>" class="nav-link">
                            <i class="fa-solid fa-users"></i>
                            <span class="link-title">Agent Management</span>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>





            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Slider", "Slider_Module")) { ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url("slider"); ?>" class="nav-link">
                            <i class="fa-solid fa-images"></i>
                            <span class="link-title">Slider Management</span>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Offers", "Offers_Module")) { ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url("offers"); ?>" class="nav-link">
                            <i class="fa fa-gift"></i>
                            <span class="link-title">Offers Management</span>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>



            <?php if (0) { ?>
                <?php if ($whitelabel_user_status == "active") { ?>
                    <?php if (permission_access("Query", "Query_Module")) { ?>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#Querymanagement" role="button"
                                aria-expanded="false" aria-controls="Querymanagement">
                                <i class="fa fa-tasks" aria-hidden="true"></i>
                                <span class="link-title">Query Management</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-chevron-down link-arrow">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </a>
                            <div class="collapse" id="Querymanagement">
                                <ul class="nav sub-menu">
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("query"); ?>" class="nav-link"><i class="fa fa-circle"></i>
                                            <span>Contact us List</span></a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    <?php } ?>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['b2c_business']) && $whitelabel_setting_data['b2c_business'] == "active") { ?>
                <?php if (permission_access("Blog", "Blog_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#blog" role="button" aria-expanded="false"
                            aria-controls="blog">
                            <i class="fa fa-blog"></i>
                            <span class="link-title">Blog Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="blog">
                            <ul class="nav sub-menu">
                                <?php if (permission_access("Blog", "blog_category_list")) { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("blog/blog-category-list"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Blog Category List</a>
                                    </li>
                                <?php } ?>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("blog"); ?>" class="nav-link"><i class="fa fa-circle"></i> Blog
                                        List</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Page", "Page_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#PageManagement" role="button" aria-expanded="false"
                            aria-controls="PageManagement">
                            <i class="fa fa-tasks" aria-hidden="true"></i>
                            <span class="link-title">Page Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="PageManagement">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("pages"); ?>" class="nav-link"><i class="fa fa-circle"></i>
                                        <span>Page List</span></a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("pages/menu-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> <span>Menu</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if (0) { ?>
                <?php if ($whitelabel_user_status == "active") { ?>
                    <?php if (permission_access("Newsletter", "Newsletter_Module")) { ?>
                        <li class="nav-item">
                            <a href="<?php echo site_url("newsletter"); ?>" class="nav-link"> <i class="fa fa-newspaper"
                                    aria-hidden="true"></i> <span class="link-title">Newsletter List</span> </a>
                        </li>
                    <?php } ?>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Logs", "Logs_Module")) { ?>
                    <li class="nav-item <?php echo active_nav('logs') ?>">
                        <a href="<?php echo site_url("logs"); ?>" class="nav-link"> <i class="fa-solid fa-history"></i> <span
                                class="link-title">Logs Management</span></a>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    </div>
</nav>