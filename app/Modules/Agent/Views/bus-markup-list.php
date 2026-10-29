<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-2">
                        <span> Bus Markup </span>
                    </div>
                    <div class="tts-col-10 text_right">
                        <?php if (permission_access("Agent", "add_bus_markup")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='agent'
                                data-href="<?php echo site_url('agent/bus-markup-view') ?>"><i
                                    class="tts-icon add "></i> Add Bus Markup
                        </button>
                        <?php }?>
                        <?php if (permission_access("Agent", "bus_markup_status")) { ?>
                        <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i
                                    class="tts-icon swap"></i> Change Status
                        </button>
                        <?php }?>
                        <?php if (permission_access("Agent", "delete_bus_markup")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formmarkuplist')"><i class="tts-icon delete "></i> Delete
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
                    <form action="<?php echo site_url('agent/bus-markup'); ?>" method="GET" class="tts-dis-content" name="markup-search" onsubmit="return searchvalidateForm()">
                        <div class="tts-col-3">
                            <div class="form-group">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-control" onchange="tts_searchkey(this,'markup-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="markup_type" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='markup_type'){ echo "selected";} ?>  >Markup Type</option>
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
                                <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if(isset($search_bar_data['from_date'])){ echo $search_bar_data['from_date']; } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                            </div>
                        </div>
                        <div class="tts-col-2">
                            <div class="form-group">
                                <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if(isset($search_bar_data['to_date'])){ echo $search_bar_data['to_date']; } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
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
                                    <a href="<?php echo site_url('agent/bus-markup');?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>
                    </form>
                </div>

                <!----------End Search Bar ----------------->

                <div class="responcive_table table_box_shadow">
                    <?php
                    $trash_uri = "agent/remove-bus-markup";
                    ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formmarkuplist">
                        <table class="table-strip divName">
                            <thead>
                            <tr>
                                <?php if (permission_access("Agent", "delete_bus_markup") ||permission_access("Agent", "bus_markup_status")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php }?>
                                <th>Agent Class</th>
                                <th>Markup Type</th>
                                <th>Min Range</th>
                                <th>Max Range</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Modified</th>
                                <?php if (permission_access("Agent", "edit_bus_markup")) { ?>
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
                                        <?php if (permission_access("Agent", "delete_bus_markup") ||permission_access("Agent", "flight_bus_status")) { ?>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php }?>



                                        <td><?php echo ucfirst($data['class_name']); ?></td>
                                        <td>
                                            <?php echo ucfirst($data['markup_type']); ?>
                                        </td>

                                        <td>
                                            <?php echo $data['min_range']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['max_range']; ?>
                                        </td>


                                        <td>
                                            <?php echo $data['value']; ?>
                                        </td>

                                        <td>
                                            <span class="<?php echo $class ?>">
                                            <?php echo ucfirst($data['status']); ?>
                                            </span>
                                        </td>

                                        <td><?php echo date_created_format($data['created']); ?></td>
                                        <td><?php
                                            if (isset($data['modified'])) {
                                                echo date_created_format($data['modified']);
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>

                                        <?php if (permission_access("Agent", "edit_bus_markup")) { ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true" data-controller='agent' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/agent/edit-bus-markup-template/') . dev_encode($data['id']); ?>"><i class="tts-icon edit "></i></a>

                                        </td>
                                        <?php }?>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text_center'><b>No Bus Markup Found</b></td></tr>";
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
        <form action="<?php echo site_url('agent/bus-markup-status-change'); ?>" method="post" tts-form="true"
              name="form_change_status">
            <div class="modal-header">
                <span class="close" onclick="ttsclosemodel('status_change')">&times;</span>
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

<!-- Show Detail Lead Modal content -->
<div id="view_agent" class="modal">
    <div class="modal-content" data-modal-view="view_modal_data"></div>
</div>
