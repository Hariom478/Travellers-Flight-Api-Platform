

<ul class="lm_navigation">
      <?php if (permission_access("Holiday", "holiday_theme_list")) { ?>
    <li class="lm_navLst <?php echo active_list_mod("Holiday", "themes_list"); ?>">
        <a href="<?php echo site_url('holiday/themes-list'); ?>"><span>Holiday Themes List</span></a>
    </li>
     <?php } ?>
      <?php if (permission_access("Holiday", "holiday_destination_list")) { ?>
    <li class="lm_navLst <?php echo active_list_mod("Holiday", "destination_list"); ?>">
        <a href="<?php echo site_url('holiday/destination-list'); ?>"><span>Holiday Destination List</span></a>
    </li>
     <?php } ?>
      <?php if (permission_access("Holiday", "holiday_inclusions_list")) { ?>
    <li class="lm_navLst <?php echo active_list_mod("Holiday", "inclusion_list"); ?>">
        <a href="<?php echo site_url('holiday/inclusion-list'); ?>"><span>Holiday Inclusion List</span></a>
    </li>
     <?php } ?>
      <?php if (permission_access("Holiday", "holiday_exclusion_list")) { ?>
    <li class="lm_navLst <?php echo active_list_mod("Holiday", "exclusion_list"); ?>">
        <a href="<?php echo site_url('holiday/exclusion-list'); ?>"><span>Holiday Exclusion List</span></a>
    </li>
     <?php } ?>
    <?php if (permission_access("Holiday", "holiday_setting")) { ?>
    <li class="lm_navLst <?php echo active_list_mod("Holiday", "holiday_settings"); ?>">
        <a href="<?php echo site_url('holiday/holiday-settings'); ?>"><span>Holiday Settings</span></a>
    </li>
     <?php } ?>
</ul>

