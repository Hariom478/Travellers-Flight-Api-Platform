<div class="settings-header">
    <div class="container-fluid">
        <div class="row">
            <div class="tts-col-2">
                <img class="profile-image"
                    src="<?php echo root_url . 'uploads/agent/profile-pic/' . $company_details['profile_pic'] ?>">

            </div>

            <div class="tts-col-6">
                <div class="user-title">
                    <h3>
                        <?php echo ucfirst($profile_details['first_name']) . ' ' . ucfirst($profile_details['last_name']); ?>
                    </h3>
                    <h5>
                        <?php echo ucfirst('Agent'); ?>
                        at
                        <?php echo $company_details['company_name']; ?>
                    </h5>
                </div>
            </div>
            <div class="tts-col-4">
                <div class="profile-join-date">
                    <h5> Join Since
                        <?php echo date_created_format($profile_details['created']); ?>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-content-area">
    <div class="query-followup">
        <ul class="lm_navigation">

            <li class="lm_navLst <?php echo active_list_mod("Setting", "index"); ?>"><a
                    href="<?php echo site_url('setting'); ?>"><span>Company Settings</span></a></li>

            <li class="lm_navLst <?php echo active_list_mod("Setting", "user_management"); ?>"><a
                    href="<?php echo site_url('setting/user-management'); ?>"><span>User Management</span></a>
            </li>

        </ul>
    </div>
</div>