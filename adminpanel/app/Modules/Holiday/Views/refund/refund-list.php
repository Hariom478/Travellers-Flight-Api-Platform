<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="m0"> Refund List</h5>
                    </div>
                    <div class="col-md-8 text-end">
                    </div>
                </div>
            </div>
            <div class="page-content-area">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!----------Start Search Bar ----------------->
                        <form action="<?php echo site_url('holiday/refunds'); ?>" method="GET" class="tts-dis-content row g-3 mb-3" name="flight-refunds-search" onsubmit="return searchvalidateForm()">
                            <?php $markup_used_for = get_active_whitelable_business();  ?>
                            <?php if ($markup_used_for) : ?>
                                <div class="col-md-2">
                                    <div class="form-group form-mb-20">
                                        <label>Business Type*</label>
                                        <select class="form-select" agent-customer="true" name="booking_source" onchange='checkbookingSource(this.value)' tts-validatation="Required" tts-error-msg="Please select Business Type">
                                            <option value="">Select</option>

                                            <?php
                                            $LoopOutSite = array(); // Initialize
                                            foreach ($markup_used_for as $key => $data) {
                                                $LoopOutSite[] = $key; ?>
                                                <option value="<?php echo $key ?>" <?php if (isset($search_bar_data['booking_source'])) {
                                                                                        if ($search_bar_data['booking_source'] == $key) {
                                                                                            echo "selected";
                                                                                        }
                                                                                    } ?>><?php echo $key ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif ?>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Select key to search by*</label>
                                    <select name="key" class="form-select" onchange="tts_searchkey(this,'flight-refunds-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                        <option value="">Please select</option>
                                        <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_ref_number') {
                                                                                echo "selected";
                                                                            } ?>>Booking Ref Number
                                        </option>
                                        <option value="refund_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'refund_status') {
                                                                            echo "selected";
                                                                        } ?>>Refund Status
                                        </option>

                                        <option value="id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'id') {
                                                                echo "selected";
                                                            } ?>>Amendment Id
                                        </option>
                                    </select>
                                </div>
                                <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                                                                                echo trim($search_bar_data['key-text']);
                                                                            } ?>">
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                                                echo $search_bar_data['key-text'] . "";
                                            } else {
                                                echo "Value";
                                            } ?> </label>
                                    <input type="text" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {
                                                                                                    echo $search_bar_data['value'];
                                                                                                } ?>" class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                                                                                                echo "disabled";
                                                                                                                            } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                                                } else {
                                                                                    /*  echo 'tts-validatation="Required"'; */
                                                                                } ?>>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                                    echo $search_bar_data['from_date'];
                                } else {
                                    echo date('d M Y');
                                } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                                </div>
                            </div>
                            <input type="hidden" name="export_excel" value="0">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                                                                                                                echo $search_bar_data['to_date'];
                                                                                                                            } else {
                                                                                                                                echo date('d M Y');
                                                                                                                            } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                                </div>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <div class="form-group">

                                    <button type="submit" class="btn btn-primary" onclick="noExportExcel()">Search <i class="fa-solid fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <!--<div class="col-md-2">
                               <div class="form-group">
                                  <label></label><br />
                                  <button type="submit" class="badge badge-md badge-primary" onclick="exportExcel()">Export Excel</button>
                               </div>
                            </div>-->
                            <? if (isset($search_bar_data['key'])) : ?>
                                <div class="col-md-2">
                                    <div class="search-reset-btn">
                                        <a href="<?php echo site_url('holiday/refunds'); ?>">Reset Search</a>
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
                                    <th>Booking Ref. No.</th>
                                    <th>Amendment Id</th>
                                    <th>Refund Type</th>
                                    <th>Refund Amount</th>
                                    <th>Refund Status</th>
                                    <th>Package</th>
                                    <th>Type</th>
                                    <th>Travel Date</th>
                                    <th>Booking Source</th>
                                    <th>Admin Staff</th>
                                    <th>Remark</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($list) && is_array($list)) {
                                    foreach ($list as $data) {
                                        if ($data['refund_status'] == 'Close') {
                                            $class = 'text-success';
                                        } else {
                                            $class = 'text-danger';
                                        }
                                        if ($data['booking_source'] == "Wl_b2b") {
                                            $company_name = $data['agent_name'];
                                        } else {
                                            $company_name = $data['customer_name'];
                                        }


                                ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo site_url('/holiday/holiday-booking-details/') . $data['booking_ref_number']; ?>" target="<?php echo target ?>"><?php echo $data['booking_ref_number']; ?></a>
                                            </td>


                                            <td>
                                                <a href="<?php echo site_url('/holiday/amendments-details/') . $ticketData = dev_encode($data['id']); ?>" target="<?php echo target ?>"><i class="fa fa-eye"> </i> <?php echo ucfirst($data['id']); ?></a>
                                            </td>

                                            <td><?php echo ucfirst($data['amendment_type']); ?></td>
                                            <td> ₹ <?php echo ucfirst($data['refund_amount']); ?></td>
                                            <td>
                                                <span class="<?php echo $class; ?>" <?php if ($data['refund_status'] == 'Open') { ?> onclick='flight_refund_close_modal("<?php echo dev_encode($data["id"]); ?>","<?php echo ucfirst($company_name) ?>")' <?php } ?>> <?php echo ucfirst($data['refund_status']); ?></span>
                                            </td>
                                            <td><?php echo ucwords($data['package_name']); ?></td>
                                            <td><?php echo ucwords($data['package_category']); ?></td>
                                            <td><?php echo ($data['travel_date']); ?></td>
                                            <td class="text-center">
                                                <?php if ($data['booking_source'] == "Wl_b2b") : ?>
                                                    <span><?php echo service_booking_source($data['booking_source'] ?? ""); ?> </span><br />
                                                    <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true" data-controller='agent' data-id="<?php echo dev_encode($data['wl_agent_id']); ?>" data-href="<?php echo site_url('agent/agent-details/') . dev_encode($data['wl_agent_id']); ?>"> <?php echo (isset($data['agent_name']) && !empty($data['agent_name'])) ? $data['agent_name'] : 'NA' ?></a>
                                                <?php else : ?>
                                                    <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true" data-controller='customer' data-id="<?php echo dev_encode($data['wl_customer_id']); ?>" data-href="<?php echo site_url('customer/customer-details/') . dev_encode($data['wl_customer_id']); ?>"> <span><?php echo service_booking_source($data['booking_source'] ?? ""); ?> </span></a>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $data['staff_name']; ?></td>
                                            <td><?php echo ucfirst($data['account_remark']); ?></td>

                                            <td><?php echo date_created_format($data['created']); ?> </td>
                                        </tr>
                                <?php }
                                } else {
                                    echo "<tr class='text-center'> <td colspan='14' class='text-center'><b>No Refund Found</b></td></tr>";
                                } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="row pagiantion_row align-items-center">
                        <div class="col-md-6 mb-3 mb-lg-0">
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
    <div id="flight_refund_close" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Close Refund Status</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo site_url('holiday/refund-close'); ?>" method="post" tts-form="true" name="flight_refund_close">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p>Agent Name / Customer Name : <strong class="company_name tts_agent_company"></strong></p>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Refund Status</label>
                                        <select class="form-select" name="status">
                                            <option value="Close">Close</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="amendment_id" class="amendment_id">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remark*</label>
                                        <textarea class="form-control" name="account_remark" rows="3" cols="15"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Refund</button>
                    </div>
                </form>
            </div>
        </div>
    </div>