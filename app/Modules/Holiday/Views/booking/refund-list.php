<section class="dashboard-section d-flex align-items-center ">
    <div class="container">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="m-0"> Refund List</h5>
                    </div>

                </div>
            </div>
            <div class="page-content-area">
                <div class="card-body">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('holiday/refunds'); ?>" method="GET" class="tts-dis-content" name="markup-search" onsubmit="return searchvalidateForm()">
                        <div class="row gy-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Select key to search by *</label>
                                    <select name="key" class="form-select" onchange="tts_searchkey(this,'markup-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                        <option value="">Please select</option>
                                        <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == '=booking_ref_number') {
                                                                                echo "selected";
                                                                            } ?>>Booking Ref. No.
                                        </option>
                                        <option value="amendment_type" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'amendment_type') {
                                                                            echo "selected";
                                                                        } ?>>Amendment Type
                                        </option>
                                        <option value=" booking_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_status') {
                                                                            echo "selected";
                                                                        } ?>>Booking Status
                                        </option>
                                        <option value="amendment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'amendment_status') {
                                                                                echo "selected";
                                                                            } ?>>Amendment Status
                                        </option>
                                        <option value="id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'id') {
                                                                echo "selected";
                                                            } ?>>Amendment Id
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
                                <div class="form-group">
                                    <label class="form-label"><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
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
                                <div class="form-group">
                                    <label class="form-label">From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                                                                                                                                                            echo $search_bar_data['from_date'];
                                                                                                                                                        } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                                                                                                                                    echo $search_bar_data['to_date'];
                                                                                                                                                } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                                </div>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-search">Search <i class="fa-solid fa-search"></i></button>
                                </div>
                            </div>
                            <? if (isset($search_bar_data['key'])) : ?>
                                <div class="col-md-2">
                                    <div class="search-reset-btn">
                                        <a href="<?php echo site_url('holiday/refunds'); ?>">Reset Search</a>
                                    </div>
                                </div>
                            <? endif ?>
                        </div>
                    </form>

                    <!----------End Search Bar ----------------->
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                                <tr>
                                    <th>Booking Reference Number</th>

                                    <th>Amendment Id</th>
                                    <th>Refund Type</th>
                                    <th>Refund Amount</th>
                                    <th>Refund Status</th>
                                    <th>Package</th>
                                    <th>Type</th>
                                    <th>Travel Date</th>

                                    <th>Remark</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($list) && is_array($list)) {
                                    foreach ($list as $data) {
                                        if ($data['refund_status'] == 'Close') {
                                            $class = 'tts-text-success';
                                        } else {
                                            $class = 'tts-text-danger';
                                        }


                                ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo site_url('/holiday/holiday-booking-details/') . $data['booking_ref_number']; ?>" target="<?php echo target ?>"><?php echo $data['booking_ref_number']; ?></a>
                                            </td>

                                            <td>
                                                <a href="<?php echo site_url('/holiday/amendments-details/') . $ticketData = dev_encode($data['id']); ?>" target="<?php echo target ?>"><i class="tts-icon eye"> <?php echo ucfirst($data['id']); ?></i></a>
                                            </td>
                                            <td><?php echo ucfirst($data['amendment_type']); ?></td>
                                            <td> ₹ <?php echo ucfirst($data['refund_amount']); ?></td>
                                            <td>
                                                <span class="<?php echo $class; ?>"> <?php echo ucfirst($data['refund_status']); ?></span>
                                            </td>
                                            <td><?php echo ucwords($data['package_name']); ?></td>
                                            <td><?php echo ucwords($data['package_category']); ?></td>
                                            <td><?php echo ($data['travel_date']); ?></td>

                                            <td><?php echo ucfirst($data['account_remark']); ?></td>

                                            <td><?php echo date_created_format($data['created']); ?> </td>
                                        </tr>
                                <?php }
                                } else {
                                    echo "<tr> <td colspan='14' class='text_center'><b>No Refund Found</b></td></tr>";
                                } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="row pagiantion_row">
                        <div class="col-md-6">
                            <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found
                            </p>
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
</section>