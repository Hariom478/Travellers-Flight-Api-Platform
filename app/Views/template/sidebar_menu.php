<?php
if (isset(web_partner_details['whitelabel_user']) && web_partner_details['whitelabel_user'] == "active") {
    $whitelabel_user_status = "active";
} else {
    $whitelabel_user_status = "inactive";
}

$whitelabel_setting_data = whitelabel;
?>
<nav class="sidebar">
    <div class="sidebar-header">
        <a href="<?php echo site_url("flight"); ?>" class="sidebar-brand">
            Admin<span></span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body ps">
        <ul class="nav">
            <!-- <li class="nav-item nav-category">Main</li> -->
            <li class="nav-item <?php echo active_nav('Dashboard') ?>">
                <a href="<?php echo site_url("dashboard"); ?>" class="nav-link">
                    <i class="fa-solid fa-dashboard"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            <!-- <li class="nav-item nav-category"> Setting</li> -->


            <li class="nav-item">
                <a href="<?php echo site_url("setting"); ?>" class="nav-link">
                    <i class="fa-solid fa-gear"></i>
                    <span class="link-title">Website Settings</span>
                </a>
            </li>


            <!-- <li class="nav-item nav-category">Accounts</li> -->


            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#accounts" role="button" aria-expanded="false"
                   aria-controls="accounts">
                    <i class="fa-solid fa-file-invoice"></i>
                    <span class="link-title">Accounts</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-down link-arrow">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <div class="collapse" id="accounts">
                    <ul class="nav sub-menu">

                        <li class="nav-item">
                            <a href="<?php echo site_url("accounts/payment-processing"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Make Payment</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("accounts/payment-history"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Payment History</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("bankaccounts"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Bank Accounts</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("agent-account/get-web-partner-account-info"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Account Logs</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("sale-result"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Sales Report</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("agent-account/flight-credit-notes"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Flight Credit Notes</a>
                        </li>

                        <?php // } ?>
                    </ul>
                </div>
            </li>
            </li>

            <!-- <li class="nav-item nav-category">Services</li> -->


            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#flight" role="button" aria-expanded="false"
                   aria-controls="flight">
                    <i class="fa-solid fa-plane"></i>
                    <span class="link-title">Flight</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-down link-arrow">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <div class="collapse" id="flight">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="<?php echo site_url("flight/bookings"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Flight Booking List</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url("flight/booking-calender"); ?>" class="nav-link"><i class="fa fa-circle"></i> Flight
                                Booking Calender</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url("markup-discount/flight-markup"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Flight Markup</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("flight/flight-amendments"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Flight Amendment nList</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("flight/flight-refunds"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Flight Refund List</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#hotel" role="button" aria-expanded="false"
                   aria-controls="hotel">
                    <i class="fa-solid fa-building"></i>
                    <span class="link-title">Hotel</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-down link-arrow">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <div class="collapse" id="hotel">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="<?php echo site_url("hotel/bookings"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Hotel Booking List</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("hotel/hotel-amendments"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Hotel Amendment List</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url("hotel/hotel-refunds"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Hotel Refund List</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#bus" role="button" aria-expanded="false"
                   aria-controls="bus">
                    <i class="fa-solid fa-bus"></i>
                    <span class="link-title">Bus</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-down link-arrow">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <div class="collapse" id="bus">
                    <ul class="nav sub-menu">

                        <li class="nav-item">
                            <a href="<?php echo site_url("bus/bus-booking-list"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Bus Booking List</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("bus/amendments"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Bus Amendment List</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url("bus/refunds"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Bus Refund List</a>
                        </li>

                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#holiday" role="button" aria-expanded="false"
                   aria-controls="holiday">
                   <i class="fa-solid fa-route"></i>
                    <span class="link-title">Holiday</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-down link-arrow">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <div class="collapse" id="holiday">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="<?php echo site_url("holiday/booking-list"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Holiday Booking List</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url("holiday/amendments"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Holiday Amendment List</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url("holiday/refunds"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Holiday Refund List</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("holiday"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Holiday Markup</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url("holiday"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Holiday Discount</a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#car" role="button" aria-expanded="false"
                   aria-controls="car">
                    <i class="fa-solid fa-car"></i>
                    <span class="link-title">Car</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-down link-arrow">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <div class="collapse" id="car">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="<?php echo site_url("car/car-bookings-list"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Car Booking List</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url("car/amendments"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Car Amendment nList</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url("car/refunds"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i>Car Refund List</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#visa" role="button" aria-expanded="false"
                   aria-controls="visa">
                    <i class="fa-brands fa-cc-visa"></i>
                    <span class="link-title">Visa</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-down link-arrow">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <div class="collapse" id="visa">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="<?php echo site_url("visa/booking-list"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Visa Booking List</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("visa/amendments"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Visa Amendment List</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url("visa/refunds"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Visa Refund List</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#cruise" role="button" aria-expanded="false"
                   aria-controls="cruise">
                    <i class="fa-solid fa-ship"></i>
                    <span class="link-title">Cruise</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-down link-arrow">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <div class="collapse" id="cruise">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="<?php echo site_url("cruise"); ?>" class="nav-link"><i class="fa-solid fa-circle"></i> Cruise Booking List</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
        </div>
    </div>
</nav>