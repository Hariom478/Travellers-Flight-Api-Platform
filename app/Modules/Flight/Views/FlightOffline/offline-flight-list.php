<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-2">
                        <span> Flight Offline</span>
                    </div>
                    <div class="tts-col-10 text_right">

                        <?php if (permission_access("FlightOffline", "add_flight_offline")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='flightoffline'
                                data-href="<?php echo site_url('flightoffline/flight-offline-view') ?>"><i
                                    class="tts-icon add "></i> Add Flight Offline
                        </button>
                        <?php }?>
                        <?php if (permission_access("FlightOffline", "flight_offline_status")) { ?>
                        <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i
                                    class="tts-icon swap"></i> Change Status
                        </button>
                        <?php }?>
                        <?php if (permission_access("FlightOffline", "delete_flight_offline")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formflightofflinelist')"><i class="tts-icon delete "></i> Delete
                        </button>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">
            <div class="card-body">
                <div class="tts_row mb_10">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('flightoffline'); ?>" method="GET" class="tts-dis-content" name="flight-offline-list" onsubmit="return searchvalidateForm()">
                        <div class="tts-col-3">
                            <div class="form-group">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-control" onchange="tts_searchkey(this,'flight-offline-list')" tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="airline_code" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='airline_code'){ echo "selected";} ?>>Airline Code</option>

                                    <option value="date-range" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range'){ echo "selected";} ?>>Date Range</option>
                                </select>
                            </div>
                            <input type="hidden" name="key-text" value="<?php if(isset($search_bar_data['key-text'])){ echo trim($search_bar_data['key-text']); } ?>">
                        </div>
                        <div class="tts-col-3">
                            <div class="form-group">
                                <label><?php if(isset($search_bar_data['key']) && $search_bar_data['key']!='date-range') { echo $search_bar_data['key-text']. " *"; } else { echo "Value"; } ?> </label>
                                <input type="text" name="value" placeholder="Value"  value="<?php if(isset($search_bar_data['value'])){ echo $search_bar_data['value']; } ?>" class="form-control" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') { echo "disabled"; } ?> <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') {  } else { echo 'tts-validatation="Required"'; } ?>   tts-error-msg="Please enter value" />
                            </div>
                        </div>
                        <div class="tts-col-2">
                            <div class="form-group">
                                <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if(isset($search_bar_data['from_date'])){ echo $search_bar_data['from_date']; } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly/>
                            </div>
                        </div>
                        <div class="tts-col-2">
                            <div class="form-group">
                                <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if(isset($search_bar_data['to_date'])){ echo $search_bar_data['to_date']; } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly/>
                            </div>
                        </div>
                        <div class="tts-col-1">
                            <div class="form-group">
                                <label></label><br />
                                <button type="submit" class="badge badge-md badge-primary">Search</button>
                            </div>
                        </div>
                        <? if(isset($search_bar_data['key'])): ?>
                            <div class="tts-col-1">
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('flightoffline');?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>
                    </form>
                </div>

                <!----------End Search Bar ----------------->

                <div class="responcive_table table_box_shadow">
                    <?php
                    $trash_uri = "flightoffline/remove-flight-offline";
                    ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formflightofflinelist">
                        <table class="table-strip divName">
                            <thead>
                            <tr>
                                <?php if (permission_access("FlightOffline", "delete_flight_offline") || permission_access("FlightOffline", "flight_offline_status")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php }?>

                                <th>Airline</th>

                                <th>From Airport Code</th>
                                <th>To Airport Code</th>
                                <th>Is Hold</th>
                                <th>Is Offline</th>
                                <th>Status</th>
                                <?php if (permission_access("FlightOffline", "edit_flight_offline")) { ?>
                                <th>Action</th>
                                <?php }?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($list) && is_array($list)) {
                                foreach ($list as $data) {
                                    if ($data['status'] == 'active') {
                                        $class = 'active-status';
                                    } else {
                                        $class = 'inactive-status';
                                    }
                                    ?>
                                    <tr>
                                        <?php if (permission_access("FlightOffline", "delete_flight_offline") || permission_access("FlightOffline", "flight_offline_status")) { ?>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php }?>

                                        <td>
                                           <?php echo $data['airline_code'].' '.'('.$data['airline_name'].')'; ?>
                                        </td>


                                        <td>
                                            <?php echo ucfirst($data['from_airport_code']); ?>
                                        </td>

                                        <td>
                                            <?php echo $data['to_airport_code']; ?>
                                        </td>

                                        <td>
                                            <?php echo $data['is_hold']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['is_offline']; ?>
                                        </td>
                                        <td>
                                            <span class="<?php echo $class ?>">
                                            <?php echo ucfirst($data['status']); ?>
                                            </span>
                                        </td>
                                        <?php if (permission_access("FlightOffline", "edit_flight_offline")) { ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true" data-controller='flightoffline' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/flightoffline/edit-flight-offline-view/') . dev_encode($data['id']); ?>"><i class="tts-icon edit "></i></a>

                                        </td>
                                        <?php }?>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text_center'><b>No Flight Offline Found</b></td></tr>";
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

<!-- status status change content -->
<div id="status_change" class="modal">
    <div class="top-model-content">
        <form action="<?php echo site_url('flightoffline/flight-offline-status-change'); ?>" method="post" tts-form="true"
              name="form_change_status">
            <div class="modal-header">
                <span class="close" onclick="ttsclosemodel(this)">&times;</span>
                <h5>Change Status</h5>
            </div>
            <div class="modal-body">
                <div class="tts_row">
                    <div class="tts-col-12">
                        <div class="form-group">

                            <select class="form-control" name="status">
                                <option value="" selected="selected">Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <input type="hidden" name="checkedvalue">
                        </div>
                    </div>
                </div>
            </div>
            <div class="top-model-footer">
                <div class="tts_row">
                    <div class="tts-col-12">
                        <button class="badge badge-md badge-primary" type="submit" value="Save">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Show  status Modal content -->


