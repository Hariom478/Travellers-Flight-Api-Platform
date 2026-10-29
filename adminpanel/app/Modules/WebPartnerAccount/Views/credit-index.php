<style>
    .error-message {
        top: unset !important;
    }

    .query-followup {
        text-align: center;
        background: #fff;
        border-bottom: 1px solid #e4e4e4;
    }

    .lm_navigation {
        position: relative;
        display: inline-flex;
        padding: 0 10px;
        text-align: center;
    }

    .lm_navLst.active:before {
        content: "";
        width: 100%;
        position: absolute;
        left: 0;
        bottom: 0;
        height: 3px;
        background: #0167ff;
    }

    .lm_navLst a {
        padding: 15px 15px;
        font-weight: 600;
        font-size: 15px;
    }
</style>

<?php
if (isset(admin_cookie_data()['admin_comapny_detail']['whitelabel_user']) && admin_cookie_data()['admin_comapny_detail']['whitelabel_user'] == "active") {
    $whitelabel_user_status = "active";
} else {
    $whitelabel_user_status = "inactive";
}
$whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];
 
?>

<div class="content ">
    <div class="page-content">
        <div class="page-content-area">
        <?php if ( $whitelabel_user_status == "active" && ((isset($whitelabel_setting_data['b2c_business']) && $whitelabel_setting_data['b2c_business'] == "active") || (isset($whitelabel_setting_data['b2b_business']) && $whitelabel_setting_data['b2b_business'] == "active"))) { ?>
            <div class="query-followup">
                <ul class="lm_navigation">
                    <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['flight_module']) && $whitelabel_setting_data['flight_module'] == "active") : ?>
                        <li class="lm_navLst <?php if ($service == 'Flight') {  echo 'active'; } ?>">
                            <a href="<?php echo site_url("webpartneraccounts/credit-notes"); ?>"> <span>Flight Credit Note</span> </a>
                        </li>
                    <?php endif; ?>

                    <?php if($whitelabel_user_status == "active" && isset($whitelabel_setting_data['hotel_module']) && $whitelabel_setting_data['hotel_module'] == "active") { ?>  
                        <li class="lm_navLst <?php if ($service == 'Hotel') {  echo 'active'; } ?>">
                            <a href="<?php echo site_url("webpartneraccounts/credit-notes-hotel"); ?>"> <span> Hotel Credit Note</span> </a>
                        </li>
                    <?php } ?>

                    <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['bus_module']) && $whitelabel_setting_data['bus_module'] == "active") : ?>
                        <li class="lm_navLst <?php if ($service == 'Bus') { echo 'active';  } ?>">
                            <a href="<?php echo site_url("webpartneraccounts/credit-notes-bus"); ?>"> <span> Bus Credit Note</span> </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['holiday_module']) && $whitelabel_setting_data['holiday_module'] == "active") : ?>
                        <li class="lm_navLst <?php if ($service == 'Holiday') {  echo 'active'; } ?>">
                            <a href="<?php echo site_url("webpartneraccounts/credit-notes-holiday"); ?>"> <span> Holiday Credit Note</span>  </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['visa_module']) && $whitelabel_setting_data['visa_module'] == "active") : ?>
                        <li class="lm_navLst <?php if ($service == 'Visa') {  echo 'active'; } ?>">
                            <a href="<?php echo site_url("webpartneraccounts/credit-notes-visa"); ?>"> <span> Visa Credit Note</span> </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['car_module']) && $whitelabel_setting_data['car_module'] == "active") : ?>
                        <li class="lm_navLst <?php if ($service == 'Car') {  echo 'active'; } ?>">
                            <a href="<?php echo site_url("webpartneraccounts/credit-notes-car"); ?>">  <span> Car Credit Note</span> </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['cruise_module']) && $whitelabel_setting_data['cruise_module'] == "active") : ?>
                        <li class="lm_navLst <?php if ($service == 'Cruise') {  echo 'active';  } ?>">
                            <a href="<?php echo site_url("webpartneraccounts/credit-notes-cruise"); ?>">  <span> Cruise Credit Note</span> </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php echo $html_view; ?>
        </div>
        <?php } ?>
    </div>
</div>