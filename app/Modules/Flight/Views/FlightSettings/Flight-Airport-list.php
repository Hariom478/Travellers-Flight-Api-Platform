<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-2">
                        <span> Flight Settings</span>
                    </div>
                    <div class="tts-col-10 text_right">
                       <?php if (permission_access("FlightSettings", "add_airport")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='flightsettings'
                                data-href="<?php echo site_url('flightsettings/add-airport-template') ?>"><i
                                    class="tts-icon add "></i> Add Airport
                        </button>
                        <?php }?>

                        <?php if (permission_access("FlightSettings", "delete_airport")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formairportlist')"><i class="tts-icon delete "></i> Delete
                        </button>
                        <?php }?>

                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">
            <div class="query-followup">
                <ul class="lm_navigation">
                    <li class="lm_navLst <?php echo active_list_mod("FlightSettings", "index"); ?>">
                        <a href="<?php echo site_url("flightsettings"); ?>"> <span> Airports List</span> </a>
                    </li>
                   <?php if (permission_access("FlightSettings", "airlines_list")) { ?>
                    <li class="lm_navLst <?php echo active_list_mod("FlightSettings", "airlines_list"); ?>">
                        <a href="<?php echo site_url("flightsettings/flight-airlines-list"); ?>">
                            <span>Airlines List</span>
                        </a>
                    </li>
                    <?php }?>
                </ul>
            </div>

            <div class="card-body">
                <div class="tts_row mb_10">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('flightsettings'); ?>" method="GET" class="tts-dis-content" name="airport-search" onsubmit="return searchvalidateForm()">
                        <div class="tts-col-3">
                            <div class="form-group">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-control"
                                        onchange="tts_searchkey(this,'airport-search')" tts-validatation="Required"
                                        tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="<?php echo trim('code');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'code') {
                                        echo "selected";
                                    } ?>>Airport Code
                                    </option>
                                    <option value="<?php echo trim('name');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'name') {
                                        echo "selected";
                                    } ?> >Airport Name
                                    </option>

                                    <option value="<?php echo trim('city_code');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'city_code') {
                                        echo "selected";
                                    } ?> >City Code
                                    </option>

                                    <option value="<?php echo trim('country_name');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'country_name') {
                                        echo "selected";
                                    } ?> >Country Name
                                    </option>

                                    <option value="<?php echo trim('country_code');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'country_code') {
                                        echo "selected";
                                    } ?> >Country Code
                                    </option>
                                </select>
                            </div>
                            <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                                echo trim($search_bar_data['key-text']);
                            } ?>">
                        </div>
                        <div class="tts-col-3">
                            <div class="form-group">
                                <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                                        echo $search_bar_data['key-text'] . " *";
                                    } else {
                                        echo "Value";
                                    } ?> </label>
                                <input type="text" name="value" placeholder="Value"
                                       value="<?php if (isset($search_bar_data['value'])) {
                                           echo $search_bar_data['value'];
                                       } ?>"
                                       class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                    echo "disabled";
                                } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                } else {
                                    echo 'tts-validatation="Required"';
                                } ?> tts-error-msg="Please enter value"/>
                            </div>
                        </div>

                        <div class="tts-col-1">
                            <div class="form-group">
                                <label></label><br/>
                                <button type="submit" class="badge badge-md badge-primary">Search</button>
                            </div>
                        </div>
                        <? if (isset($search_bar_data['key'])): ?>
                            <div class="tts-col-1">
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('flightsettings'); ?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>
                    </form>
                </div>

                <!----------End Search Bar ----------------->

                <div class="responcive_table table_box_shadow">
                    <?php
                    $trash_uri = "flightsettings/remove-airport";
                    ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formairportlist">
                        <table class="table-strip divName">
                            <thead>
                            <tr>
                                <?php if (permission_access("FlightSettings", "delete_airport")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php }?>
                                <th>Airport Code</th>
                                <th>Airport Name</th>
                                <th>City Code</th>
                                <th>Country Name</th>
                                <th>Country Code</th>
                                <?php if (permission_access("FlightSettings", "edit_airport")) { ?>
                                <th>Action</th>
                                <?php }?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($airport_list) && is_array($airport_list)) {
                                foreach ($airport_list as $data) { ?>
                                    <tr>
                                        <?php if (permission_access("FlightSettings", "delete_airport")) { ?>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php }?>
                                        <td>
                                            <?php echo $data['code']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['name']; ?>
                                        </td>
                                        <td><?php echo $data['city_code']; ?></td>
                                        <td>
                                            <?php echo $data['country_name']; ?>
                                        </td>

                                        <td>
                                            <?php echo $data['country_code']; ?>
                                        </td>
                                        <?php if (permission_access("FlightSettings", "edit_airport")) { ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true"
                                               data-controller='flightsettings'
                                               data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('/flightsettings/edit-airport-template/') . dev_encode($data['id']); ?>"><i
                                                        class="tts-icon edit "></i></a>
                                        </td>
                                        <?php }?>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text_center'><b>No Airport Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </form>

                    <div class="d-flex justify-content-end">
                        <div class="tts_row">
                            <div class="tts-col-6">
                                <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                    of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
                            </div>
                            <div class="tts-col-6">
                                <?php if ($pager) : ?>
                                    <?= $pager->links() ?>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
