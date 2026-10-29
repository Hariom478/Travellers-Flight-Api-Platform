<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="m-0">Credit Notes</h5>
                    </div>
                </div>
            </div>
        </div>


        <div class="page-content-area card-body">

            <!----------Start Search Bar ----------------->


                <form action="<?php echo site_url('webpartneraccounts/credit-notes-cruise'); ?>" method="GET"
                      class="row tts-dis-content" id="sales-export" name="markup-search"
                      onsubmit="return searchvalidateForm()">


                    <div class="col-md-3">
                        <div class="form-group form-mb-20">
                            <label>Select key to search by *</label>
                            <select name="key" class="form-select"
                                    onchange="tts_searchkey(this,'markup-search')"
                                    tts-error-msg="Please select search key">
                                <option value="">Please select</option>
                                <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_ref_number') {
                                    echo "selected";

                                } ?>>Booking Ref Number
                                </option>
                                <option value="first_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'first_name') {
                                    echo "selected";

                                } ?>>First Name
                                </option>
                                <option value="last_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'last_name') {
                                    echo "selected";

                                } ?>>Last Name
                                </option>

                                <option value="booking_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_status') {
                                    echo "selected";

                                } ?>>Booking Status
                                </option>
                                <option value="payment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'payment_status') {
                                    echo "selected";

                                } ?>>Payment Status
                                </option>
                                <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                    echo "selected";

                                } ?>>Date Range
                                </option>
                            </select>
                        </div>
                        <input type="hidden" name="key-text"
                               value="<?php if (isset($search_bar_data['key-text'])) {
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
                            <input type="text" name="value" placeholder="Value"
                                   value="<?php if (isset($search_bar_data['value'])) {
                                       echo $search_bar_data['value'];

                                   } ?>"
                                   class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                echo "disabled";

                            } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                            } else {

                                echo '';

                            } ?> tts-error-msg="Please enter value"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-mb-20">
                            <label>From Date</label><input type="text" data-searchbar-from="true"
                                                           name="from_date" id="from_date"
                                                           value="<?php if (isset($search_bar_data['from_date'])) {
                                                               echo $search_bar_data['from_date'];

                                                           } else {
                                                               echo date('d M Y');
                                                           } ?>" placeholder="Select From Date"
                                                           class="form-control"
                                                           tts-error-msg="Please select from date" readonly/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-mb-20">
                            <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date"
                                                         id="to_date"
                                                         value="<?php if (isset($search_bar_data['to_date'])) {
                                                             echo $search_bar_data['to_date'];

                                                         } else {
                                                             echo date('d M Y');
                                                         } ?>" placeholder="Select To Date" class="form-control"
                                                         tts-error-msg="Please select to date" readonly/>
                        </div>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <div class="form-group form-mb-20">
                            <button type="submit" data-url="<?php echo site_url('webpartneraccounts/credit-notes-cruise'); ?>"
                                    praveen-method="get"
                                    class="badge badge-md badge-primary export-praveen badge_search">Search <i
                                        class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <? if (isset($search_bar_data['key'])) : ?>
                            <div class="search-reset-btn">
                                <a href="<?php echo site_url('webpartneraccounts/credit-notes-cruise'); ?>">Reset Search</a>
                            </div>
                        <? endif ?>
                    </div>

                </form>


            <!----------End Search Bar ----------------->
            <?php if ($account_logs) { ?>


                <div class="table-responsive">

                    <table class="table table-bordered table-hover">
                        <thead class="table-active">
                        <tr>

                            <th>Cr. Number</th>
                            <th>Booking Ref. No</th>
                            <th>Description</th>
                            <th>Passenger</th>
                            <th>Cruise Details</th>
                            <th>Confirmation</th>
                            <th>Created</th>
                            <th>View Credit Note</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        if (!empty($account_logs) && is_array($account_logs)) {
                            foreach ($account_logs as $data) {

                                $service_log = json_decode($data['service_log'],true);

                                $amendment_charges = json_decode($data['amendment_charges'], true);


                                ?>

                                <tr>
                                    <td>
                                        <?php
                                        if ($data['action_type'] == 'booking') { ?>
                                            <a href="<?php echo service_log_link($data['service'], $data['booking_ref_no']) ?>"> <?php echo $data['acc_ref_number']; ?></a>
                                        <?php } else {
                                            echo $data['acc_ref_number'];
                                        } ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo site_url('/cruise/cruise-booking-details/') . $data['booking_ref_number']; ?>"><?php echo $data['booking_ref_number']; ?></a>
                                    </td>
                                    <td>
                                        <b>Fare: INR <?php echo $data['total_price']; ?></b> <br>
                                        <b>Cancellation Charges:
                                            INR <?php echo $amendment_charges['Charge'] + $amendment_charges['ServiceCharge'] + $amendment_charges['GST']['TotalGSTAmount']; ?></b>
                                        <br>
                                        <b>Refund: INR <?php echo $amendment_charges['Refund']; ?></b> <br>
                                    </td>

                                    <td><?php echo $service_log['PaxName']; ?></td>
                                    <td>
                                        <b>Country:</b> <?php echo $data['cruise_line_name']; ?><br/>
                                        <b>Type:</b> <?php echo $data['ship_name']; ?><br/>

                                        <b>Travel:</b> <?php echo $data['sailing_date']; ?>
                                    </td>
                                    <td>
                                        <b></b><?php echo $data['confirmation_no']; ?><br/>
                                    </td>
                                    <td>
                                        <?php
                                        echo date_created_format($data['created']);
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo site_url('cruise/get-credit-note?type=CreditNote&booking_ref_number=' . $data['booking_ref_number']) ?>&traveller_ref_number=<?php echo '' ?>"><i
                                                    class="fa-solid fa-eye"></i> View</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr> <td colspan='11' class='text_center'><b>No Account Logs Found</b></td></tr>";
                        } ?>
                        </tbody>
                    </table>
                </div>

                <div class="row pagiantion_row align-items-center mb-3 mb-lg-0">
                    <div class="col-md-6">
                        <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                            of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records
                            found </p>
                    </div>
                    <div class="col-md-6">
                        <?php if ($pager) : ?>
                            <?= $pager->links() ?>
                        <?php endif ?>
                    </div>
                </div>

            <?php } ?>
        </div>

    </div>


    <div id="view_agent" class="modal">
        <div class="modal-content" data-modal-view="view_modal_data"></div>
    </div>