<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="table_title">
            <div class="sale_bar">
                <div class="row">
                    <div class="tts-col-4">
                        <span> Flight Amendments List</span>
                    </div>
                    <div class="tts-col-8 text_right">

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row mb_10">
                        <!----------Start Search Bar ----------------->
                        <form action="<?php echo site_url('flight/flight-amendments'); ?>" method="GET"
                              class="row" name="markup-search" onsubmit="return searchvalidateForm()">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Select key to search by *</label>
                                    <select name="key" class="form-control"
                                            onchange="tts_searchkey(this,'markup-search')" tts-validatation="Required"
                                            tts-error-msg="Please select search key">
                                        <option value="">Please select</option>

                                        <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_ref_number') {
                                            echo "selected";
                                        } ?>>Booking Ref Number
                                        </option>


                                        <option value="ticket_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'ticket_number') {
                                            echo "selected";
                                        } ?>>Ticket No
                                        </option>
                                        <option value="pnr" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'pnr') {
                                            echo "selected";
                                        } ?>>PNR
                                        </option>

                                        <option value="booking_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_status') {
                                            echo "selected";
                                        } ?>>Booking Status
                                        </option>
                                        <option value="amendment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'amendment_status') {
                                            echo "selected";
                                        } ?>>Amendment Status
                                        </option>

                                        <option value="flight_amendment.id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'flight_amendment.id') {
                                            echo "selected";
                                        } ?>>Amendment Id
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>From Date</label><input type="text" data-searchbar-from="true"
                                                                   name="from_date"
                                                                   value="<?php if (isset($search_bar_data['from_date'])) {
                                                                       echo $search_bar_data['from_date'];
                                                                   } ?>" placeholder="Select From Date"
                                                                   class="form-control"
                                                                   tts-error-msg="Please select from date" readonly/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date"
                                                                 value="<?php if (isset($search_bar_data['to_date'])) {
                                                                     echo $search_bar_data['to_date'];
                                                                 } ?>" placeholder="Select To Date" class="form-control"
                                                                 tts-error-msg="Please select to date" readonly/>
                                </div>
                            </div>
                            <div class="col-md-1 align-self-end">
                                <div class="form-group">
                                   
                                    <button type="submit" class="badge badge-md badge-primary">Search</button>
                                </div>
                            </div>
                            <? if (isset($search_bar_data['key'])) : ?>
                                <div class="col-md-1">
                                    <div class="search-reset-btn">
                                        <a href="<?php echo site_url('flight/flight-amendments'); ?>">Reset Search</a>
                                    </div>
                                </div>
                            <? endif ?>
                        </form>
                    </div>


                    <!----------End Search Bar ----------------->

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>
                                <th>Booking Reference Number</th>
                                <th>Amendment Id</th>
                                <th>Amendment Type</th>
                                <th>Amendment Status</th>
                                <th>Journey Type</th>
                                <th>Sector</th>
                                <th>Departure Date</th>
                                <th>Airline Code</th>
                                <th>Pnr</th>
                                <th>Booking Status</th>
                                <th> Remark</th>

                                <th>Generate By</th>

                                <th>Created</th>
                                <th>Summary</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($list) && is_array($list)) {
                                foreach ($list as $data) {
                                    if ($data['booking_status'] == 'Confirmed') {
                                        $class = 'tts-text-success';
                                    } else {
                                        $class = 'tts-text-danger';
                                    }

                                    if ($data['payment_status'] == 'Successful') {
                                        $payment_class = 'tts-text-success';
                                    } else {
                                        $payment_class = 'tts-text-danger';
                                    }
                                    if ($data['amendment_status'] == 'approved') {
                                        $amendment_status = 'tts-text-success';
                                    } else {
                                        $amendment_status = 'tts-text-danger';
                                    }
                                    ?>
                                    <tr>

                                        <td>
                                            <a href="<?php echo site_url('/flight/details/') . $data['booking_ref_number']; ?>"
                                               target="_blank"><?php echo $data['booking_ref_number']; ?></a></td>
                                        <td><?php echo ucfirst($data['id']); ?></td>
                                        <td><?php echo ucfirst($data['amendment_type']); ?></td>
                                        <td>
                                            <span class="<?php echo $amendment_status ?>"  <?php if ($data['amendment_status'] == 'requested') { ?> onclick='amendment_status_modal("<?php echo dev_encode($data["id"]); ?>","<?php echo ucfirst($data["company_name"]) ?>")' <?php } ?>> <?php echo ucfirst($data['amendment_status']); ?></span>
                                        </td>
                                        <td><?php echo ucfirst($data['journey_type']); ?></td>
                                        <td><?php echo ucfirst($data['origin']) . "-" . ucfirst($data['destination']); ?></td>
                                        <td><?php echo display_custom_date_format($data['departure_date']); ?></td>
                                        <td>  <?php echo $data['validating_airline_code']; ?></td>
                                        <td><?php echo $data['pnr']; ?></td>
                                        <td>
                                                <span class="<?php echo $class ?>">
                                                    <?php echo ucfirst($data['booking_status']); ?>
                                                </span>
                                        </td>

                                        <td><?php echo $data['remark_from_web_partner']; ?></td>

                                        <td><?php echo $data['staff_name']; ?></td>



                                        <td><?php echo date_created_format($data['created']); ?>  </td>
                                        <td>
                                            <a href="<?php echo site_url('/flight/amendment-details/') . $ticketData = dev_encode($data['id']); ?>"
                                               target="_blank"><i class="tts-icon eye"> View</i></a>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='14' class='text_center'><b>No Amendments Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>
                    </div>
                     <div class="row">
                            <div class="col-8">
                                <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                    of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
                            </div>
                            <div class="col-4">
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
</section>    


<div id="amendment_status_change" class="modal">
        <div class="top-model-content">
            <form action="<?php echo site_url('flight/amendment-status-change'); ?>" method="post" tts-form="true"
                  name="form_password_change">
                <div class="modal-header">
                    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
                    <h5>Amendment Status Change</h5>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="tts-col-12">
                            <div class="form-group">
                                <p style="padding: 11px;">Web Partner : <strong
                                            class="company_name tts_agent_company"></strong></p>
                            </div>

                            <div class="tts-col-12">
                                <div class="form-group">
                                    <select class="form-control" name="status">
                                        <option value="" selected>Select Amendment Status</option>
                                        <option value="requested">Requested</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>


                            <input type="hidden" name="amendment_id" class="amendment_id">
                            <div class="tts-col-12">
                                <div class="form-group">
                                    <label>Remark*</label>
                                    <textarea class="form-control" name="admin_remark" rows="3" cols="15"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="top-model-footer">
                    <div class="row">
                        <div class="tts-col-12">
                            <button class="badge badge-md badge-primary" type="submit" value="Save">Change Status
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>