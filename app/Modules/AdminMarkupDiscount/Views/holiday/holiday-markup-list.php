<section class="dashboard-section d-flex align-items-center ">
    <div class="container">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0">Holiday Markup</h5>
                    </div>
                    <div class="col-md-8 text-end">
                        <?php if (permission_access("Holiday", "holiday_markup_add")) { ?>
                            <button class="btn-sm btn btn-secondary" view-data-modal="true" data-controller='flight' data-href="<?php echo site_url('markup-discount/holiday-markup-view') ?>"><i class="fa-solid fa-add"></i> Add Holiday Markup
                            </button>
                        <?php } ?>
                        <?php if (permission_access("Holiday", "holiday_markup_change_status")) { ?>
                            <button class="btn-sm btn btn-primary" onclick="confirm_change_status('status_change')"><i class="fa-solid fa-exchange"></i> Change Status
                            </button>
                        <?php } ?>
                        <?php if (permission_access("Holiday", "holiday_markup_delete")) { ?>
                            <button class="btn-sm btn btn-danger danger disable_badge" tts-disable_badge onclick="confirm_delete('formmarkuplist')"><i class="fa-solid fa-trash"></i> Delete
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <!----------Start Search Bar ----------------->
                <form action="<?php echo site_url('markup-discount/holiday-markup'); ?>" method="GET" name="markup-search" onsubmit="return searchvalidateForm()">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-select" onchange="tts_searchkey(this,'markup-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>

                                    <option value="markup_type" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'markup_type') {
                                                                    echo "selected";
                                                                } ?>>Markup Type
                                    </option>
                                    <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                                    echo "selected";
                                                                } ?>>Date Range
                                    </option>
                                </select>
                            </div>
                            <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                                                                            echo trim($search_bar_data['key-text']);
                                                                        } ?>">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                                            echo $search_bar_data['key-text'] . " *";
                                        } else {
                                            echo "Value";
                                        } ?> </label>
                                <input type="text" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {
                                                                                                echo $search_bar_data['value'];
                                                                                            } ?>" class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                                                                                            echo "disabled";
                                                                                                                        } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                                                                                                } else {
                                                                                                                                    echo 'tts-validatation="Required"';
                                                                                                                                } ?> tts-error-msg="Please enter value" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                                                                                                                                    echo $search_bar_data['from_date'];
                                                                                                                                } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                                                                                                            echo $search_bar_data['to_date'];
                                                                                                                        } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                            </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <div class="form-group form-mb-20">
                                <button type="submit" class="btn btn-primary btn-search">Search <i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <? if (isset($search_bar_data['key'])) : ?>
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('markup-discount/holiday-markup'); ?>">Reset
                                        Search</a>
                                </div>
                            <? endif ?>
                        </div>
                    </div>
                </form>
                <!----------End Search Bar ----------------->
                <?php
                $trash_uri = "markup-discount/remove-holiday-markup";
                ?>
                <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formmarkuplist">
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                                <tr>
                                    <?php if (permission_access("Holiday", "holiday_markup_delete") || permission_access("Holiday", "holiday_markup_change_status")) { ?>
                                        <th><label><input type="checkbox" name="check_all" id="selectall" /></label>
                                        </th>
                                    <?php } ?>
                                    <th>Value</th>
                                    <th>Max Limit</th>
                                    <th>Markup Type</th>

                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Modified</th>
                                    <?php if (permission_access("Holiday", "holiday_markup_edit")) { ?>
                                        <th>Action</th>
                                    <?php } ?>
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
                                            <?php if (permission_access("Holiday", "holiday_markup_delete") || permission_access("Holiday", "holiday_markup_change_status")) { ?>

                                                <td>
                                                    <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                                                </td>
                                            <?php  } ?>
                                            <td><?php echo $data['value']; ?></td>
                                            <td><?php echo $data['max_limit']; ?></td>
                                            <td>
                                                <?php echo ucfirst($data['markup_type']); ?>
                                            </td>



                                            <td>
                                                <span class="<?php echo $class ?>">
                                                    <?php echo ucfirst($data['status']); ?>
                                                </span>
                                            </td>

                                            <td>
                                                <?php echo date_created_format($data['created']); ?>
                                            </td>

                                            <td>
                                                <?php
                                                if (isset($data['modified'])) {
                                                    echo date_created_format($data['modified']);
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                            <?php if (permission_access("Holiday", "holiday_markup_edit")) { ?>
                                                <td>
                                                    <a href="javascript:void(0);" view-data-modal="true" data-controller='bus' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/markup-discount/edit-holiday-markup-template/') . dev_encode($data['id']); ?>"><i class="fa-solid fa-edit"></i></a>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                <?php }
                                } else {
                                    echo "<tr> <td colspan='11' class='text-center'><b>No  Markup Found</b></td></tr>";
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </form>



                <div class="row pagiantion_row align-items-center">
                    <div class="col-md-6 mb-3 mb-lg-0">
                        <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                            of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
                    </div>
                    <div class="col-md-6">
                        <?php if ($pager) : ?>
                            <?= $pager->links() ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('markup-discount/holiday-markup-status-change'); ?>" method="post" tts-form="true" name="form_change_status">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-mb-20">

                                <select class="form-select" name="status">
                                    <option value="" selected="selected">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <input type="hidden" name="checkedvalue">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" value="Save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show  status Modal content -->