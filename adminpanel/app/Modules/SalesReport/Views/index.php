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
            <div class="query-followup">
                <ul class="lm_navigation">
                <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['flight_module']) && $whitelabel_setting_data['flight_module'] == "active") : ?>
                    <?php
                    if (permission_access("Accounts", "flight_report_list")) { ?>
                        <li class="lm_navLst <?php if ($service == 'Flight') {  echo 'active';  } ?>">
                            <a href="<?php echo site_url("sale-result?q=Flight"); ?>"> <span>Flight Report</span> </a>
                        </li>
                    <?php } ?>
                    <?php endif; ?>
                    <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['hotel_module']) && $whitelabel_setting_data['hotel_module'] == "active") : ?>
                    <?php
                    if (permission_access("Accounts", "hotel_report_list")) { ?>

                        <li class="lm_navLst <?php if ($service == 'Hotel') {  echo 'active'; } ?>">
                            <a href="<?php echo site_url("sale-result?q=Hotel"); ?>">
                                <span> Hotel Report</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php endif; ?>
                    <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['bus_module']) && $whitelabel_setting_data['bus_module'] == "active") : ?>
                        <?php 
                        if (permission_access("Accounts", "bus_report_list")) { ?>

                            <li class="lm_navLst <?php if ($service == 'Bus') {  echo 'active';  } ?>">
                                <a href="<?php echo site_url("sale-result?q=Bus"); ?>">
                                    <span> Bus Report</span>
                                </a>
                            </li>
                        <?php } ?>
                    <?php endif; ?>
                    <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['holiday_module']) && $whitelabel_setting_data['holiday_module'] == "active") : ?>
                    <?php
                    if (permission_access("Accounts", "holiday_report_list")) { ?>
                        <li class="lm_navLst <?php if ($service == 'Holiday') {  echo 'active';  } ?>">
                            <a href="<?php echo site_url("sale-result?q=Holiday"); ?>">
                                <span> Holiday Report</span>
                            </a>
                        </li>

                    <?php } ?>
                    <?php endif; ?>
                    <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['visa_module']) && $whitelabel_setting_data['visa_module'] == "active") : ?>
                    <?php if (permission_access("Accounts", "visa_report_list")) { ?>
                        <li class="lm_navLst <?php if ($service == 'Visa') {  echo 'active';  } ?>">
                            <a href="<?php echo site_url("sale-result?q=Visa"); ?>">
                                <span> Visa Report</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php endif; ?>

                    <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['car_module']) && $whitelabel_setting_data['car_module'] == "active") : ?>
                    <?php if (permission_access("Accounts", "car_report_list")) { ?>

                        <li class="lm_navLst <?php if ($service == 'Car') {  echo 'active';  } ?>">
                            <a href="<?php echo site_url("sale-result?q=Car"); ?>">
                                <span> Car Report</span>
                            </a>
                        </li> 
                    <?php } ?>
                    <?php endif; ?>
                    <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['cruise_module']) && $whitelabel_setting_data['cruise_module'] == "active") : ?>
                    <?php if (permission_access("Accounts", "cruise_report_list")) { ?>
                        <li class="lm_navLst <?php if ($service == 'Cruise') {  echo 'active';  } ?>">
                            <a href="<?php echo site_url("sale-result?q=Cruise"); ?>">
                                <span> Cruise Report</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php endif; ?>
                </ul>
            </div>
            <?php echo $html_view; ?>
        </div>
    </div>
</div>