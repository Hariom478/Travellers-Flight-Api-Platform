<section class="dashboard-section d-flex align-items-center ">
    <div class="container">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="m-0">Credit Notes</h5>
                    </div>
                </div>
            </div>      
            <div class="card">
              <div class="card-body">
                <!----------Start Search Bar ----------------->
                <form action="<?php echo site_url('flight/flight-credit-notes'); ?>" method="GET"
                    class="tts-dis-content row g-3 mb-3" name="markup-search" onsubmit="return searchvalidateForm()">
                    <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Select key to search by *</label>
                            <select name="key" class="form-select" onchange="tts_searchkey(this,'markup-search')"
                                tts-validatation="Required" tts-error-msg="Please select search key">
                                <option value="">Please select</option>
                                <option value="acc_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'acc_ref_number') {
                                    echo "selected";
                                } ?> >Cr. Number</option>
                                <option value="first_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'first_name') {
                                    echo "selected";
                                } ?> >First Name</option>

                                <option value="ticket_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'ticket_number') {
                                    echo "selected";
                                } ?> >Ticket Number</option>

                                <option value="pnr" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'pnr') {
                                    echo "selected";
                                } ?> >PNR</option>

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
                        <div class="form-group">
                            <label class="form-label">
                                <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                                    echo $search_bar_data['key-text'] . " *";
                                } else {
                                    echo "Value";
                                } ?>
                            </label>
                            <input type="text" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {
                                echo $search_bar_data['value'];
                            } ?>" class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                echo "disabled";
                            } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                            } else {
                                echo 'tts-validatation="Required"';
                            } ?> tts-error-msg="Please enter value"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">From Date</label><input type="text" data-searchbar-from="true"
                                name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                                    echo $search_bar_data['from_date'];
                                } else {
                                    echo date('d M Y');
                                } ?>" placeholder="Select From Date" class="form-control"
                                tts-error-msg="Please select from date" readonly />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">To Date</label><input type="text" data-searchbar-to="true"
                                name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                    echo $search_bar_data['to_date'];
                                } else {
                                    echo date('d M Y');
                                } ?>" placeholder="Select To Date" class="form-control"
                                tts-error-msg="Please select to date" readonly />
                        </div>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <div class="form-group">

                            <button type="submit" class="badge badge-md badge-primary badge_search">Search <i
                                    class="fa-solid fa-search"></i></button>
                        </div>
                    </div>
                    <? if (isset($search_bar_data['key'])): ?>
                    <div class="col-md-2">
                        <div class="search-reset-btn">
                            <a href="<?php echo site_url('flight/flight-credit-notes'); ?>">Reset Search</a>
                        </div>
                    </div>
                    <? endif ?>
                </div>
                </form>
                <!----------End Search Bar ----------------->
                <?php if ($account_logs) { ?>          
                    <div class="table-responsive  mt-3">

                        <table class="table table-bordered table-hover">
                            <thead class="table-hover">
                                <tr>
                                    <th>Cr. Number</th>
                                    <th>Booking Ref. No</th>
                                    <th>Description</th>
                                    <th>Passenger</th>
                                    <th>Flight Details</th>
                                    <th>PNR/TicketNo</th>
                                    <th>Created</th>
                                    <th>View Credit Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if (!empty($account_logs) && is_array($account_logs)) {
                                    foreach ($account_logs as $data) {
                                        $fare = json_decode($data['fare'], true);

                                        //pr($fare);
                            
                                        $total_fare = $fare['BaseFare'] + $fare['BaggageCharges'] + $fare['MealCharges'] + $fare['SeatCharges'] + $fare['ServiceCharges'] + $fare['Tax'];

                                        $amendment_charges = json_decode($data['amendment_charges'], true)
                                            ?>

                                <tr>
                                    <td>

                                        <?php
                                        if ($data['action_type'] == 'booking') { ?>
                                        <a
                                            href="<?php echo service_log_link($data['service'], $data['booking_ref_number']) ?>">
                                            <?php echo $data['booking_ref_number']; ?>
                                        </a>
                                        <?php } else {
                                            echo $data['acc_ref_number'];
                                        } ?>
                                    </td>
                                    <td> <a href="<?php echo site_url('/flight/details/') . $data['booking_ref_number']; ?>"
                                            target="_blank">
                                            <?php echo $data['booking_ref_number']; ?>
                                        </a></td>
                                    <td>

                                        <b>Fare: INR
                                            <?php echo $total_fare; ?>
                                        </b> <br>
                                        <b>Meal: INR
                                            <?php echo $amendment_charges['MealCharge']; ?>
                                        </b> <br>
                                        <b>Seat: INR
                                            <?php echo $amendment_charges['SeatCharge']; ?>
                                        </b> <br>
                                        <b>Baggage: INR
                                            <?php echo $amendment_charges['BaggageCharge']; ?>
                                        </b> <br>
                                        <b>Cancellation Charges: INR
                                            <?php echo $amendment_charges['Charge'] + $amendment_charges['ServiceCharge'] + $amendment_charges['GST']['TotalGSTAmount']; ?>
                                        </b> <br>
                                        <b>Refund: INR
                                            <?php echo $amendment_charges['Refund']; ?>
                                        </b> <br>
                                    </td>

                                    <td>
                                        <?php echo $data['title'] . ' ' . $data['first_name'] . ' ' . $data['last_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $data['origin'] . '-' . $data['destination'] . ' | ' . $data['airline_code']; ?>
                                    </td>
                                    <td>
                                        <b>PNR: </b>
                                        <?php echo $data['pnr']; ?><br />
                                        <b>TicketNo: </b>
                                        <?php echo $data['ticket_number']; ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo date_created_format($data['created']);
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo site_url('flight/get-credit-note?type=CreditNote&booking_ref_number=' . $data['booking_ref_number']) ?>&traveller_ref_number=<?php echo $data['TravelerId'] ?>"
                                            target="_blank"><i class="tts-icon eye"> View</i></a>
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
                    <div class="row pagiantion_row align-items-center">
                        <div class="col-md-6">
                            <p class="pagiantion_text">Page
                                <?= $pager->getCurrentPage() ?>
                                of
                                <?= $pager->getPageCount() ?>, total
                                <?= $pager->getTotal() ?> records
                                found
                            </p>
                        </div>
                        <div class="col-md-6">
                            <?php if ($pager): ?>
                            <?= $pager->links() ?>
                            <?php endif ?>
                        </div>
                    </div>
                
                <?php } ?>
            </div>
            </div>
       </div>
    </div>
</section>


    <div id="view_agent" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content" data-modal-view="view_modal_data"></div>
        </div>
    </div>