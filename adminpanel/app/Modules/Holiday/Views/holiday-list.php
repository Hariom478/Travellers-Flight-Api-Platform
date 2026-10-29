<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 ">
                        <h5 class="m-0"> Holiday List</h5>
                    </div>
                    <div class="col-md-8 text-end">


                        <?php if (permission_access("Holiday", "holiday_add")) { ?>

                        <a href="<?php echo site_url("holiday/add-holiday-list"); ?>" class="badge badge-wt"><i class="fa-solid fa-add"></i> Add Holiday</a>

                         <?php } ?>
                         <?php if (permission_access("Holiday", "holiday_change_status")) { ?>
                        <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i class="fa-solid fa-exchange"></i> Change Status
                        </button>
                        <?php } ?>


                        <?php if (permission_access("Holiday", "holiday_delete")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge onclick="confirm_delete('formagentlist')"><i class="fa-solid fa-trash"></i> Delete
                        </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('holiday'); ?>" method="GET" class="row" name="holiday-search" onsubmit="return searchvalidateForm()">
                        <div class="col-md-3">
                            <div class="form-group form-mb-20 ">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-select" onchange="tts_searchkey(this,'holiday-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="holiday_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'holiday_name') {
                                                                        echo "selected";
                                                                    } ?>>Package Name</option>
                                    <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                                    echo "selected";
                                                                } ?>>Date Range</option>
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

                                <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                            </div>
                        </div>

                        <? if (isset($search_bar_data['key'])) : ?>
                            <div class="col-md-3 mb-3">
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('holiday'); ?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>

                    </form>
                </div>

                <!----------End Search Bar ----------------->
                <div class="table-responsive">
                    <?php $trash_uri = "holiday/remove-holiday-list"; ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formagentlist">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                                <tr>
                                    <?php if (permission_access("Holiday", "holiday_delete") || permission_access("Holiday", "holiday_change_status")) { ?>
                                    <th><label><input type="checkbox" name="check_all" id="selectall" /></label>
                                    </th>
                                   <?php } ?>
                                    <th>Package Name</th>
                                    <th>Show On Home</th>
                                    <th>Starting Price Standard</th>
                                    <th>Starting Price Deluxe</th>
                                    <th>Starting Price Luxury</th>
                                    <th>Status</th>
                                    <th>Action</th>
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

                                        if ($data['show_on_home'] == 1) {
                                            $show_on_home = 'yes';
                                        } else {
                                            $show_on_home = 'no';
                                        }
                                ?>

                                        <tr>
                                        <?php if (permission_access("Holiday", "holiday_delete") || permission_access("Holiday", "holiday_change_status")) { ?>
                                            <td>
                                                <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                                            </td>
                                            <?php } ?>


                                            <td>
                                                <?php echo $data['holiday_name']; ?>
                                            </td>

                                            <td>
                                                <?php echo $show_on_home; ?>
                                            </td>
                                            <td>
                                                <?php echo $data['starting_price_standard']; ?>
                                            </td>
                                            <td><?php echo $data['starting_price_deluxe']; ?></td>
                                            <td><?php echo $data['starting_price_luxury']; ?></td>

                                            <td>
                                                <span class="<?php echo $class ?>">
                                                    <?php echo ucfirst($data['status']); ?>
                                                </span>
                                            </td>
                                             <?php if (permission_access("Holiday", "holiday_edit") || permission_access("Holiday", "holiday_gallery_list") || permission_access("Holiday", "holiday_itinerary_list") || permission_access("Holiday", "holiday_price_list")) { ?>
                                            <td>
                                                <button class="actbtn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="<?php echo site_url('/holiday/edit-holiday-list-view/') . dev_encode($data['id']); ?>">Edit Package</a></li>
                                                    <li><a class="dropdown-item" href="<?php echo site_url('/holiday/holiday-itinerary-list/') . dev_encode($data['id']); ?>">Package Itinerary</a></li>
                                                    <?php if ($data['book_online'] == 1 && $data['price_on_request'] != 1) : ?>
                                                        <li><a class="dropdown-item" href="<?php echo site_url('/holiday/holiday-price-list/') . dev_encode($data['id']); ?>">Package Prices</a></li>
                                                    <?php endif ?>
                                                    <li><a class="dropdown-item" href="javascript:void(0);" view-data-modal="true" data-controller='holiday' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/holiday/holiday-gallery/') . dev_encode($data['id']); ?>">Package Gallery</a></li>
                                                </ul>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                <?php }
                                } else {
                                    echo "<tr> <td colspan='11' class='text-center'><b>No Package Found</b></td></tr>";
                                } ?>
                            </tbody>
                        </table>

                    </form>


                </div>
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
</div>
</div>


<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('holiday/holiday-list-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
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
                    <button class="btn btn-primary" type="submit">Save</button>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- Show  status Modal content -->

<!-- status status change content -->
<div id="gallery_status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1076;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo site_url('holiday/holiday-gallery-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
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
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Show  status Modal content -->