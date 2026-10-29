<div class="settings-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-2">
                <img class="profile-image" src="<?php echo site_url(''); ?>webroot/img/img_avatar.png">
            </div>
            <div class="col-md-6">
                <div class="user-title">
                    <h3><?php echo ucfirst($profile_details['first_name']) . ' ' . ucfirst($profile_details['last_name']); ?></h3>
                    <h5> <?php echo ucfirst($profile_details['designation']); ?>
                        at <?php echo $company_details['company_name']; ?> </h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="profile-join-date">
                    <h5> Join Since <?php echo $profile_details['job_joining_date']; ?> </h5>
                </div>
            </div>
        </div>
    </div>
</div>
<ul class="lm_navigation">
<li class="lm_navLst <?php echo active_list_mod("Setting", "index"); ?>"><a
            href="<?php echo site_url('setting'); ?>"><span>Company Settings</span></a></li>

<?php if (permission_access("Setting", "user_list")) { ?>
    <li class="lm_navLst <?php echo active_list_mod("Setting", "user_management"); ?>"><a
                href="<?php echo site_url('setting/user-management'); ?>"><span>User Management</span></a>
    </li>
<?php } ?>
<li class="lm_navLst <?php echo active_list_mod("Setting", "profile"); ?>"><a href="<?php echo site_url('setting/profile'); ?>"><span>Profile Settings</span></a></li>
</ul>