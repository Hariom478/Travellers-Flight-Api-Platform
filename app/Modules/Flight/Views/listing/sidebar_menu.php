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
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item <?php echo active_nav('Dashboard') ?>">
                <a href="<?php echo site_url("dashboard"); ?>" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-box link-icon">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            <li class="nav-item nav-category"> Setting</li>
            <?php if (permission_access("Setting", "Setting_Module")) { ?>

                <li class="nav-item">
                    <a href="<?php echo site_url("setting"); ?>" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-message-square link-icon">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span class="link-title">Website Settings</span>
                    </a>
                </li>
            <?php } ?>

            <li class="nav-item nav-category">Accounts</li>


            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#accounts" role="button" aria-expanded="false"
                   aria-controls="accounts">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-mail link-icon">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <span class="link-title">Accounts</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-down link-arrow">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <div class="collapse" id="accounts">
                    <ul class="nav sub-menu">
                        <?php //if (permission_access("Accounts", "Accounts_Module")) { ?>
                        <li class="nav-item">
                            <a href="<?php echo site_url("accounts/payment-processing"); ?>" class="nav-link">Make
                                Payment</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("accounts/payment-history"); ?>" class="nav-link">Payment
                                History</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("bankaccounts"); ?>" class="nav-link">Bank Accounts</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("webpartneraccounts/get-web-partner-account-info"); ?>"
                               class="nav-link">Account Logs</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("sale-result"); ?>" class="nav-link">Sales Report</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo site_url("webpartneraccounts/flight-credit-notes"); ?>" class="nav-link">Flight Credit Notes</a>
                        </li>

                        <?php // } ?>
                    </ul>
                </div>
            </li>
            </li>

            <li class="nav-item nav-category">Services</li>
            <?php if (permission_access("Flight", "Flight_Module") || permission_access("FlightOffline", "FlightOffline_Module")) { ?>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#flight" role="button" aria-expanded="false"
                       aria-controls="flight">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-mail link-icon">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <span class="link-title">Flight</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down link-arrow">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <div class="collapse" id="flight">
                        <ul class="nav sub-menu">
                            <?php if (permission_access("Flight", "Flight_Module")) { ?>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/bookings"); ?>" class="nav-link">Flight
                                        Booking List</a>
                                </li>
                                <?php

                                if ($whitelabel_user_status == 'active' && $whitelabel_setting_data['b2c_business'] == "active") { ?>
                                    <?php if (permission_access("Flight", "flight_markup_list_b2c")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("flight/flight-markup-b2c"); ?>"
                                               class="nav-link">Flight
                                                Markup</a>
                                        </li>
                                    <?php } ?>
                                    <?php if (permission_access("Flight", "flight_discount_list_b2c")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("flight/flight-discount-b2c"); ?>"
                                               class="nav-link">Flight Discount</a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>

                            <?php } ?>
                        </ul>
                    </div>
                </li>

            <?php } ?>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#hotel" role="button" aria-expanded="false"
                   aria-controls="hotel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-mail link-icon">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
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
                            <a href="<?php echo site_url("hotel/bookings"); ?>" class="nav-link">Hotel Booking List</a>
                        </li>
                        <?php

                        if ($whitelabel_user_status == 'active' && $whitelabel_setting_data['b2c_business'] == "active") { ?>


                            <li class="nav-item">
                                <a href="<?php echo site_url("hotel"); ?>" class="nav-link">Hotel Markup</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo site_url("hotel"); ?>" class="nav-link">Hotel Discount</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#bus" role="button" aria-expanded="false"
                   aria-controls="bus">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-mail link-icon">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
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
                            <a href="<?php echo site_url("bus/bus-booking-list"); ?>" class="nav-link">Bus Booking
                                List</a>
                        </li>
                        <?php

                        if ($whitelabel_user_status == 'active' && $whitelabel_setting_data['b2c_business'] == "active") { ?>
                            <li class="nav-item">
                                <a href="<?php echo site_url("bus"); ?>" class="nav-link">Bus Markup</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo site_url("bus"); ?>" class="nav-link">Bus Discount</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#holiday" role="button" aria-expanded="false"
                   aria-controls="holiday">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-mail link-icon">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
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
                            <a href="<?php echo site_url("holiday/booking-list"); ?>" class="nav-link">Holiday Booking
                                List</a>
                        </li>
                        <?php

                        if ($whitelabel_user_status == 'active' && $whitelabel_setting_data['b2c_business'] == "active") { ?>


                            <li class="nav-item">
                                <a href="<?php echo site_url("holiday"); ?>" class="nav-link">Holiday Markup</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo site_url("holiday"); ?>" class="nav-link">Holiday Discount</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#car" role="button" aria-expanded="false"
                   aria-controls="car">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-mail link-icon">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
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
                            <a href="<?php echo site_url("car"); ?>" class="nav-link">Car Booking List</a>
                        </li>
                        <?php

                        if ($whitelabel_user_status == 'active' && $whitelabel_setting_data['b2c_business'] == "active") { ?>
                            <li class="nav-item">
                                <a href="<?php echo site_url("car"); ?>" class="nav-link">Car Booking List</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo site_url("car"); ?>" class="nav-link">Car Markup</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo site_url("car"); ?>" class="nav-link">Car Discount</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#visa" role="button" aria-expanded="false"
                   aria-controls="visa">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-mail link-icon">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
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
                            <a href="<?php echo site_url("visa/booking-list"); ?>" class="nav-link">Visa Booking
                                List</a>
                        </li>
                        <?php

                        if ($whitelabel_user_status == 'active' && $whitelabel_setting_data['b2c_business'] == "active") { ?>
                            <li class="nav-item">
                                <a href="<?php echo site_url("visa"); ?>" class="nav-link">Visa Booking List</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo site_url("visa"); ?>" class="nav-link">Visa Markup</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo site_url("visa"); ?>" class="nav-link">Visa Discount</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#cruise" role="button" aria-expanded="false"
                   aria-controls="cruise">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-mail link-icon">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
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
                            <a href="<?php echo site_url("cruise"); ?>" class="nav-link">Cruise Booking List</a>
                        </li>
                        <?php

                        if ($whitelabel_user_status == 'active' && $whitelabel_setting_data['b2c_business'] == "active") { ?>

                            <li class="nav-item">
                                <a href="<?php echo site_url("cruise"); ?>" class="nav-link">Cruise Markup</a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo site_url("cruise"); ?>" class="nav-link">Cruise Discount</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>
            <?php
            if ($whitelabel_user_status == "active") { ?>
                <li class="nav-item nav-category">Query Management</li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#query" role="button" aria-expanded="false"
                       aria-controls="query">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-mail link-icon">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <span class="link-title">Query</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down link-arrow">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <div class="collapse" id="query">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="<?php echo site_url("holiday/query-list"); ?>" class="nav-link">Holiday Query
                                    List</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo site_url("leads"); ?>" class="nav-link">Cruise Query List</a>
                            </li>
                        </ul>
                    </div>
                </li>
            <?php } ?>

            <?php
            if ($whitelabel_user_status == "active") {
                if (permission_access("Customer", "Customer_Module")) { ?>
                    <li class="nav-item nav-category">Customer Management</li>
                    <li class="nav-item">
                        <a href="<?php echo site_url("customer"); ?>" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-message-square link-icon">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span class="link-title">Customer</span>
                        </a>
                    </li>
                <?php }
            } ?>

            <?php
            if ($whitelabel_user_status == "active" && $whitelabel_setting_data['b2b_business'] == "active") {
                if (permission_access("Agent", "Agent_Module")) { ?>
                    <li class="nav-item nav-category">Agent Management</li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#agent" role="button" aria-expanded="false"
                           aria-controls="agent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-mail link-icon">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <span class="link-title">Agent</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="agent">
                            <ul class="nav sub-menu">


                                <li class="nav-item">
                                    <a href="<?php echo site_url("agent"); ?>" class="nav-link">Agent List</a>
                                </li>
                                <?php if (permission_access("Agent", "add_agent")) { ?>
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" view-data-modal="true" data-controller='agent'
                                           data-href="<?php echo site_url('agent/add-agent-template') ?>"
                                           class="nav-link">Add
                                            New Agent</a>
                                    </li>
                                <?php } ?>

                                <?php

                                if ($whitelabel_setting_data['flight_module'] == "active") { ?>
                                    <?php if (permission_access("Agent", "flight_markup_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("agent/flight-markup"); ?>" class="nav-link">Flight
                                                Markup</a>
                                        </li>
                                    <?php } ?>
                                    <?php if (permission_access("Agent", "flight_discount_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("agent/flight-discount"); ?>" class="nav-link">Flight
                                                Discount</a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>

                                <?php

                                if ($whitelabel_setting_data['hotel_module'] == "active") { ?>
                                    <?php if (permission_access("Agent", "hotel_markup_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("leads"); ?>" class="nav-link">Hotel Markup</a>
                                        </li>
                                    <?php } ?>
                                    <?php if (permission_access("Agent", "hotel_discount_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("tasks"); ?>" class="nav-link">Hotel
                                                Discount</a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($whitelabel_setting_data['bus_module'] == "active") { ?>
                                    <?php if (permission_access("Agent", "bus_markup_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("agent/bus-markup"); ?>" class="nav-link">Bus
                                                Markup</a>
                                        </li>
                                    <?php } ?>
                                    <?php if (permission_access("Agent", "bus_discount_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("agent/bus-discount"); ?>" class="nav-link">Bus
                                                Discount</a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($whitelabel_setting_data['car_module'] == "active") { ?>
                                    <?php if (permission_access("Agent", "car_markup_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("leads"); ?>" class="nav-link">Car Markup</a>
                                        </li>
                                    <?php } ?>
                                    <?php if (permission_access("Agent", "car_discount_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("tasks"); ?>" class="nav-link">Car Discount</a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>

                                <?php if ($whitelabel_setting_data['visa_module'] == "active") { ?>
                                    <?php if (permission_access("Agent", "visa_markup_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("leads"); ?>" class="nav-link">Visa Markup</a>
                                        </li>
                                    <?php } ?>
                                    <?php if (permission_access("Agent", "visa_discount_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("tasks"); ?>" class="nav-link">Visa
                                                Discount</a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($whitelabel_setting_data['holiday_module'] == "active") { ?>
                                    <?php if (permission_access("Agent", "holiday_markup_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("leads"); ?>" class="nav-link">Holidays
                                                Markup</a>
                                        </li>
                                    <?php } ?>
                                    <?php if (permission_access("Agent", "holiday_discount_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("tasks"); ?>" class="nav-link">Holidays
                                                Discount</a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($whitelabel_setting_data['cruise_module'] == "active") { ?>
                                    <?php if (permission_access("Agent", "cruise_markup_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("leads"); ?>" class="nav-link">Cruise
                                                Markup</a>
                                        </li>
                                    <?php } ?>
                                    <?php if (permission_access("Agent", "cruise_discount_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("tasks"); ?>" class="nav-link">Cruise
                                                Discount</a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                <?php }
            } ?>
            <?php if ($whitelabel_user_status == "active") { ?>
                <li class="nav-item nav-category">Coupon Management</li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#coupon" role="button" aria-expanded="false"
                       aria-controls="coupon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-mail link-icon">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <span class="link-title">Coupon</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down link-arrow">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <div class="collapse" id="coupon">
                        <ul class="nav sub-menu">

                            <li class="nav-item">
                                <a href="<?php echo site_url("leads"); ?>" class="nav-link">Flight Coupon List</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo site_url("leads"); ?>" class="nav-link">Hotel Coupon List</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo site_url("tasks"); ?>" class="nav-link">Bus Coupon List</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo site_url("tasks"); ?>" class="nav-link">Holiday Coupon List</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo site_url("tasks"); ?>" class="nav-link">Cruise Coupon List</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo site_url("tasks"); ?>" class="nav-link">Visa Coupon List</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo site_url("tasks"); ?>" class="nav-link">Car Coupon List</a>
                            </li>
                        </ul>
                    </div>
                </li>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active") { ?>
                <li class="nav-item nav-category">Extra Services</li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#page" role="button" aria-expanded="false"
                       aria-controls="page">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-mail link-icon">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <span class="link-title">Page</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down link-arrow">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <div class="collapse" id="page">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="<?php echo site_url("pages"); ?>" class="nav-link">Page List</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo site_url("pages/"); ?>" class="nav-link">Menu</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <?php if (permission_access("Slider", "Slider_Module")) { ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url("slider"); ?>" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-message-square link-icon">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span class="link-title">Slider</span>
                        </a>
                    </li>
                <?php } ?>


                <?php if (permission_access("Newsletter", "Newsletter_Module")) { ?>

                    <li class="nav-item">
                        <a href="<?php echo site_url("newsletter"); ?>" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-message-square link-icon">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span class="link-title">Newsletters</span>
                        </a>
                    </li>
                <?php } ?>


                <?php if (permission_access("Career", "Career_Module")) { ?>

                    <li class="nav-item">
                        <a href="<?php echo site_url("newsletter"); ?>" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-message-square link-icon">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span class="link-title">Career</span>
                        </a>
                    </li>
                <?php } ?>


                <?php if (permission_access("Feedback", "Feedback_Module")) { ?>

                    <li class="nav-item">
                        <a href="<?php echo site_url("feedback"); ?>" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-message-square link-icon">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span class="link-title">Feedback</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if (permission_access("Log", "Log_Module")) { ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url("logs"); ?>" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-message-square link-icon">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span class="link-title">Logs</span>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php
            if ($whitelabel_user_status == "active") {
                if (permission_access("Blog", "Blog_Module")) { ?>
                    <li class="nav-item nav-category">Blog Management</li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#blog" role="button" aria-expanded="false"
                           aria-controls="blog">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-mail link-icon">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <span class="link-title">Blog</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="blog">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("blog"); ?>" class="nav-link">Blog List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("blog/blog-category-list"); ?>" class="nav-link">Blog
                                        Category List</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php }
            } ?>
        </ul>
        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
        </div>
    </div>
</nav>