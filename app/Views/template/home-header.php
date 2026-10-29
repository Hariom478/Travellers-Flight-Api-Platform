<?php if (!session()->get('wl_agent')) { ?>
   <section class="header sticky-top d-flex align-items-center d-none">
      <div class="container">
         <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="<?php echo site_url(); ?>">
               <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo'] ?>" class="img-fluid">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
               <i class="fa fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
               <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                     <a class="nav-link" href="tel: <?php echo web_partner_details['support_no'] ?>" class="img-fluid"><i class="fa fa-phone"></i> 24x7 :
                        <?php echo web_partner_details['support_no'] ?>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="mailto:<?php echo web_partner_details['support_email'] ?>" class="img-fluid"><i class="fa fa-envelope"></i>
                        <?php echo web_partner_details['support_email'] ?>
                     </a>
                  </li>
               </ul>
            </div>
         </nav>
      </div>    
   </section>
<?php } else { ?>

   <?php //pr(session()->get('wl_agent')); ?>
   <div class="header sticky-top ">
      <nav class="navbar navbar-expand-lg navbar_header">
         <div class="container">
            <a class="navbar-brand navbar-logo" id="logo" href="<?php echo site_url("flight"); ?>">
            <?php if (session()->get('wl_agent') && !empty(session()->get('wl_agent')['company_logo'])) : ?>
                     <img src="<?php echo root_url . 'uploads/agent/logo/' . session()->get('wl_agent')['company_logo']; ?>" class="img-fluid tts-logo-ragular" style="display: none;" alt="home-logo.png" />
                     <img src="<?php echo root_url . 'uploads/agent/logo/' . session()->get('wl_agent')['company_logo']; ?>" class="img-fluid tts-logo-white" alt="home-logo.png" />
                  <?php else : ?>
                     <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo']; ?>" class="img-fluid tts-logo-ragular" style="display: none;" alt="home-logo.png" />
                     <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo']; ?>" class="img-fluid tts-logo-white" alt="home-logo.png" />
                  <?php endif; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <i class="fa fa-bars"></i>
            </button>
            <?php $walletBalance = walletBalance(); ?>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
               <div class="ms-auto">
                  <ul class="topbar-nav">
                     <li class="topbar__list">Hello
                     <?php echo session()->get('wl_agent')['company_name']; ?>
                        ID:- <?php echo session()->get('wl_agent')['company_id']; ?>
                     </li>
                     <li class="topbar__list">
                        <div class="dropdown">
                           <span data-bs-toggle="dropdown" aria-expanded="false">My Balance : ₹
                              <?php echo wl_agent_get_balance(); ?>
                           </span>
                           <ul class="dropdown-menu megamenu" data-popper-placement="bottom-start">
                              <li>
                                 <a class="dropdown-item">Deposit Balance : <span>₹ <?= $walletBalance['DepositeAmount'] ?></span></a>
                              </li>
                              <li>
                                 <a class="dropdown-item">Credit Balance : <span>₹ <?= $walletBalance['CreditLimit'] ?></span></a>
                              </li>
                              <li>
                                 <a class="dropdown-item">Dues : <span>₹ <?= $walletBalance['DueAmount'] ?></span></a>
                              </li>
                              <li>
                                 <a class="dropdown-item">Credit Status : <span><?= $walletBalance['CreditStatus'] ?></span></a>
                              </li>
                           </ul>
                        </div>
                     </li>
                     <li class="topbar__list"><a href=" <?php echo site_url("accounts/payment-processing"); ?>">Recharge</a></li>
                     <li class="topbar__list"><a href=" <?php echo 'https://stage.liamtra.com/'; ?>"target="_blank">Liamtra</a></li>
                     <li class="topbar__list"><a href=" <?php echo site_url("signout"); ?>">Logout</a></li>
                  </ul>
                 
                  <ul class="navbar-nav navbar-list  mb-2 mb-lg-0">
                  <?php  if (whitelabel['flight_module'] == 'active') { ?>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo active_header("Flight"); ?>" href="<?php echo site_url("/flight"); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Flight </a>
                        <ul class="dropdown-menu megamenu ">
                           <li><a class="dropdown-item" href="<?php echo site_url("flight"); ?>">Search</a></li>
                           <?php if(permission_access("Flight","flight_booking_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("flight/bookings"); ?>">Flight Booking</a></li>
                           <?php } ?>
                           <?php if(permission_access("Flight","flight_calender")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("flight/booking-calender"); ?>">Flight Calender</a></li>
                           <?php } ?>
                           <?php if(permission_access("Flight","flight_amendment_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("flight/flight-amendments"); ?>">Flight Amendments</a> </li>
                           <?php } ?>
                           <?php if(permission_access("Flight","flight_credit_notes")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("flight/flight-credit-notes"); ?>">Flight Credit Notes</a></li>
                           <?php } ?>
                           <?php if(permission_access("Flight","flight_markup_list")){?>

                           <li><a class="dropdown-item" href="<?php echo site_url("markup-discount/flight-markup"); ?>">Flight Markup</a></li>
                           <?php } ?>
                           <?php if(permission_access("Flight","flight_group_ticket")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("groupticket"); ?>">Group Ticket</a></li>
                           <?php } ?>
                        </ul>
                     </li>
                     <?php } ?>

                     <?php if (whitelabel['hotel_module'] == 'active') { ?>

                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo active_header("Hotel"); ?>" href="<?php echo site_url("/hotel"); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">Hotel</a>
                        <ul class="dropdown-menu megamenu ">
                           <li><a class="dropdown-item" href="<?php echo site_url("hotel"); ?>">Search</a></li>
                           <?php if(permission_access("Hotel","hotel_booking_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("hotel/bookings"); ?>">Hotel Booking</a></li>
                           <?php } ?>
                           <?php if(permission_access("Hotel","hotel_amendment_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("hotel/hotel-amendments"); ?>">Hotel Amendments</a> </li>
                           <?php } ?>
                           <?php if(permission_access("Hotel","hotel_markup_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("markup-discount/hotel-markup"); ?>">Hotel Markup</a></li>
                           <?php } ?>
                           <?php if(permission_access("Hotel","hotel_group_ticket")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("groupticket"); ?>">Group Ticket</a></li>
                           <?php } ?>
                        </ul>
                     </li>

                     <?php } ?>
                     <?php if (whitelabel['bus_module'] == 'active') { ?>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo active_header("Bus"); ?>" href="<?php echo site_url('bus') ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">Bus</a>
                        <ul class="dropdown-menu megamenu ">
                           <li><a class="dropdown-item" href="<?php echo site_url("bus"); ?>">Search</a></li>
                           <?php if(permission_access("Bus","bus_booking_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("bus/bus-booking-list"); ?>">Bus Booking</a></li>
                           <?php } ?>
                           <?php if(permission_access("Bus","bus_amendment_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("bus/amendments"); ?>">Bus Amendments</a> </li>
                           <?php } ?>
                           <?php if(permission_access("Bus","bus_markup_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("markup-discount/bus-markup"); ?>">Bus Markup</a></li>
                           <?php } ?>
                           <?php if(permission_access("Bus","bus_group_ticket")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("groupticket"); ?>">Group Ticket</a></li>
                           <?php } ?>
                        </ul>
                     </li>
                     <?php } ?>
                     <?php if (whitelabel['holiday_module'] == 'active') { ?>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo active_header("HolidayBooking"); ?>" href="<?php echo site_url('holiday') ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">Holidays</a>
                        <ul class="dropdown-menu megamenu ">
                           <li><a class="dropdown-item" href="<?php echo site_url("holiday"); ?>">Search</a></li>
                           <?php if(permission_access("Holiday","holiday_booking_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("holiday/booking-list"); ?>">Holiday Booking</a></li>
                           <?php } ?>
                           <?php if(permission_access("Holiday","holiday_amendment_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("holiday/amendments"); ?>">Holiday Amendments</a></li>
                           <?php } ?>
                           <?php if(permission_access("Holiday","holiday_refund_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("holiday/refunds"); ?>">Holiday Refunds</a></li>
                           <?php } ?>
                           <?php if(permission_access("Holiday","holiday_markup_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("markup-discount/holiday-markup"); ?>">Holiday Markup</a></li>
                           <?php } ?>
                        </ul>
                     </li>
                     <?php } ?>
                     <?php if (whitelabel['visa_module'] == 'active') { ?>
                        <li class="nav-item"><a class="nav-link <?php echo active_header("VisaBooking"); ?>" href="<?php echo site_url('visa') ?>" class="img-fluid">Visa</a></li> 
                     <?php } ?>


                     <?php if (whitelabel['car_module'] == 'active') { ?>
                     <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle <?php echo active_header("CarBooking"); ?>" href="<?php echo site_url('car') ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">Car</a>
                        <ul class="dropdown-menu megamenu ">
                           <li><a class="dropdown-item" href="<?php echo site_url("car"); ?>">Search</a></li>
                           <?php if(permission_access("CarExtranet","car_booking_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("car/car-bookings-list"); ?>">Car Booking</a></li>
                           <?php } ?>
                           <?php if(permission_access("CarExtranet","car_amendment_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("car/amendments"); ?>">Car Amendments</a></li>
                           <?php } ?>
                           <?php if(permission_access("CarExtranet","car_markup_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("car/car-markup-list"); ?>">Car Markup List</a></li>
                           <?php } ?>
                         
                        </ul>
                     </li>
                     <?php } ?>

                     <?php if (whitelabel['tourguide_module'] == 'active') { ?>

                     <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle <?php echo active_header("TourGuideBooking"); ?>" href="<?php echo site_url('tourguide') ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">TourGuide</a>
                        <ul class="dropdown-menu megamenu ">
                            <li><a class="dropdown-item" href="<?php echo site_url("tourguide"); ?>">Search</a></li>
                            <?php if(permission_access("TourGuide","tourguide_booking_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("tourguide/tour-guide-booking-list"); ?>">TourGuide Booking</a></li>
                           <?php } ?>
                           <?php if(permission_access("TourGuide","tour_amendment_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("tourguide/amendment-lists"); ?>">TourGuide Amendments</a></li>
                           <?php } ?>
                           <?php if(permission_access("TourGuide","tourguide_markup_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("markup-discount/tourguide-markup"); ?>">TourGuide Markup</a></li>
                           <?php } ?>
                         
                        </ul>
                     </li>
                     <?php } ?>
                     <?php if (whitelabel['activities_module'] == 'active') { ?>
                     <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle <?php echo active_header("Activities"); ?>" href="<?php echo site_url('activities') ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">Activities</a>
                        <ul class="dropdown-menu megamenu ">
                            <li><a class="dropdown-item" href="<?php echo site_url("activities"); ?>">Search</a></li>
                            <?php if(permission_access("Activities","activities_booking_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("activities/get-booking-list"); ?>">Activities Booking</a></li>
                           <?php } ?>
                           <?php if(permission_access("Activities","activities_amendment_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("activities/amendments"); ?>">Activities Amendments</a></li>
                           <?php } ?>
                           <?php if(permission_access("Activities","activities_markup_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("markup-discount/activities-markup-list");?>">Activities Markup</a></li>
                           <?php } ?>
                         
                        </ul>
                     </li>
                     <?php } ?>



                     <?php if (whitelabel['cruise_module'] == 'active') { ?>

                     <li class="nav-item dropdown"><a class="nav-link dropdown-toggle <?php echo active_header("CruiseBooking"); ?>" href="<?php echo site_url('cruise') ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">Cruise</a>
                        <ul class="dropdown-menu megamenu ">
                           <li><a class="dropdown-item" href="<?php echo site_url("cruise"); ?>">Search</a></li>
                           <?php if(permission_access("Cruise","cruise_booking_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("cruise/booking-list"); ?>">Cruise Booking</a></li>
                           <?php } ?>
                           <?php if(permission_access("Cruise","cruise_amendment_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("cruise/amendments"); ?>">Cruise Amendments</a> </li>
                           <?php } ?>
                           <?php if(permission_access("Cruise","cruise_refund_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("cruise/refunds"); ?>">Cruise Refund</a> </li>
                           <?php } ?>
                           <?php if(permission_access("Cruise","cruise_markup_list")){?>
                           <li><a class="dropdown-item" href="<?php echo site_url("markup-discount/cruise-markup"); ?>">Cruise Markup</a></li>
                           <?php } ?>
                        </ul>
                     </li>
                     <?php } ?>
                   
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Accounts </a>
                        <ul class="dropdown-menu megamenu ">
                        <?php if(permission_access("Accounts","add_offline_make_payment")){?>

                           <li>
                              <a href="<?php echo site_url("accounts/payment-processing"); ?>" class="dropdown-item">Recharge</a>
                           </li>
                           <?php } ?>

                           <?php if(permission_access("Accounts","payment_history_list")){?>

                           <li>
                              <a href="<?php echo site_url("accounts/payment-history"); ?>" class="dropdown-item">Recharge History</a>
                           </li>
                           <?php } ?>

                           <?php if(permission_access("Accounts","agent_account_log_list")){?>
                           <li>
                              <a href="<?php echo site_url("agent-account/get-agent-account-info"); ?>" class="dropdown-item"> Account Ledger</a>
                           </li>
                           <?php } ?>
                           <?php if (0) : ?>
                              <li>
                                 <a href="<?php echo site_url("sale-result"); ?>" class="dropdown-item"> Sales Report</a>
                              </li>
                           <?php endif; ?>

                           <?php if(permission_access("Accounts","sels_and_mis_list")){?>

                           <li>
                              <a href="<?php echo site_url("sale-result/service-report"); ?>" class="dropdown-item">Sales & MIS</a>
                           </li>
                           <?php } ?>

                           <!--  <li>
                            <a href="<?php echo site_url("agent-account/flight-credit-notes"); ?>" class="dropdown-item"> Flight Credit Notes</a>
                        </li> -->

                        <?php if(permission_access("Accounts","agent_debit_logs")){?>

                           <li>
                              <a href="<?php echo site_url("agent-account/get-agent-debit-info"); ?>" class="dropdown-item"> Debit Notes</a>
                           </li>

                           <?php } ?>

                        <?php if(permission_access("Accounts","agent_invoice")){?>

                           <li>
                              <a href="<?php echo site_url("invoice"); ?>" class="dropdown-item"></i> Invoice</a>
                           </li>
                           <?php }?>

                           <?php // } 
                           ?>
                        </ul>
                     </li>
                     <li class="nav-item dropdown logoutbtn">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class="fa fa-user"></i>
                           <?php echo substr(ucfirst(session()->get('wl_agent')['first_name']) . ' ' . ucfirst(session()->get('wl_agent')['last_name']), 0, 100); ?>
                        </a>
                        <ul class="dropdown-menu megamenu ">
                           <li class="dropdown-header"><strong>USER ID : <?php echo session()->get('wl_agent')['company_id']; ?> </strong></li>
                           <li>
                              <hr class="dropdown-divider">
                           </li>
                           <li><a class="dropdown-item" href="<?php echo site_url("dashboard"); ?>"> <i class="fa fa-dashboard"></i> Dashboard </a></li>
                           <li><a class="dropdown-item" href="<?php echo site_url("setting/profile"); ?>"> <i class="fa fa-user"></i> Profile </a></li>
                           <li><a class="dropdown-item" href="<?php echo site_url("setting"); ?>"><i class="fa fa-gear"></i> Setting</a></li>
                           <li><a class="dropdown-item" href="<?php echo site_url("signout"); ?>"> <i class="fa fa-sign-out"></i> Logout </a></li>
                        </ul>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </nav>
   </div>
<?php } ?>