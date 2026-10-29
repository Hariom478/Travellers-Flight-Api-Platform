<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-2">
                        <span> Flight Settings</span>
                    </div>
                    <div class="tts-col-10 text_right">
                        <?php if (permission_access("FlightSettings", "add_airlines")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='flightsettings'
                                data-href="<?php echo site_url('flightsettings/add-airline-template') ?>"><i
                                    class="tts-icon add "></i> Add Airline
                        </button>
                        <?php }?>
                        <?php if (permission_access("FlightSettings", "delete_airlines")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formairlinelist')"><i class="tts-icon delete "></i> Delete
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
                    <li class="lm_navLst <?php echo active_list_mod("FlightSettings", "airlines_list"); ?>">
                        <a href="<?php echo site_url("flightsettings/flight-airlines-list"); ?>">
                            <span> Airlines List</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="card-body">
                <div class="tts_row mb_10">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('flightsettings/flight-airlines-list'); ?>" method="GET" class="tts-dis-content"
                          name="airline-search" onsubmit="return searchvalidateForm()">
                        <div class="tts-col-3">
                            <div class="form-group">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-control"
                                        onchange="tts_searchkey(this,'airline-search')" tts-validatation="Required"
                                        tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="<?php echo trim('airline_code');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'airline_code') {
                                        echo "selected";
                                    } ?>>Airline Code
                                    </option>
                                    <option value="<?php echo trim('airline_name');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'airline_name') {
                                        echo "selected";
                                    } ?> >Airline Name
                                    </option>

                                    <option value="<?php echo trim('airline_contact_no');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'airline_contact_no') {
                                        echo "selected";
                                    } ?> >Airline Contact No
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
                                    <a href="<?php echo site_url('flightsettings/flight-airlines-list'); ?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>
                    </form>
                </div>

                <!----------End Search Bar ----------------->
                <div class="responcive_table table_box_shadow">
                    <?php
                    $trash_uri = "flightsettings/remove-airline";
                    ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formairlinelist">
                        <table class="table-strip divName">
                            <thead>
                            <tr>
                                <?php if (permission_access("FlightSettings", "delete_airlines")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php }?>
                                <th>Airline Code</th>
                                <th>Airline Name</th>
                                <th>Airline Contact No</th>
                                <?php if (permission_access("FlightSettings", "edit_airlines")) { ?>
                                <th>Action</th>
                                <?php }?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($aiport_list) && is_array($aiport_list)) {
                                foreach ($aiport_list as $data) { ?>
                                    <tr>
                                        <?php if (permission_access("FlightSettings", "delete_airlines")) { ?>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php }?>
                                        <td>
                                            <?php echo $data['airline_code']; ?>
                                        </td>
                                        <td>
                                            <?php echo ucfirst($data['airline_name']); ?>
                                        </td>

                                        <td>
                                            <?php echo $data['airline_contact_no']; ?>
                                        </td>

                                        <?php if (permission_access("FlightSettings", "edit_airlines")) { ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true"
                                               data-controller='flightsettings'
                                               data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('/flightsettings/edit-airline-template/') . dev_encode($data['id']); ?>"><i
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

