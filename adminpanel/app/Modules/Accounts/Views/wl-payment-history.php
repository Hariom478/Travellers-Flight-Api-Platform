<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center gy-4">
                    <div class="col-md-3">
                        <h5 class="m-0">Payment History</h5>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="page-content-area">


            <div class="card-body">
              
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('accounts/wl-payment-history'); ?>" method="GET"
                          class="tts-dis-content row mb-3"
                          name="blog-search" onsubmit="return searchvalidateForm()">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Select key to search by *</label>
                                <select name="key" class="form-select" onchange="tts_searchkey(this,'blog-search')"
                                        tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>

                                    <option value="status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'status') {
                                        echo "selected";
                                    } ?>>Status
                                    </option>

                                    <option value="bank_transaction_id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'bank_transaction_id') {
                                        echo "selected";
                                    } ?>>Transaction Id
                                    </option>
                                    <option value="amount" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'amount') {
                                        echo "selected";
                                    } ?> >Amount
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
                                <label class="form-label">From Date</label><input type="text" data-searchbar-from="true" name="from_date"
                                                               value="<?php if (isset($search_bar_data['from_date'])) {
                                                                   echo $search_bar_data['from_date'];
                                                               } ?>" placeholder="Select From Date" class="form-control"
                                                               tts-error-msg="Please select from date" readonly/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">To Date</label><input type="text" data-searchbar-to="true" name="to_date"
                                                             value="<?php if (isset($search_bar_data['to_date'])) {
                                                                 echo $search_bar_data['to_date'];
                                                             } ?>" placeholder="Select To Date" class="form-control"
                                                             tts-error-msg="Please select to date" readonly/>
                            </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <div class="form-group">
                                
                                <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <? if (isset($search_bar_data['key'])): ?>
                            <div class="col-md-2">
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('accounts/wl-payment-history'); ?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>
                    </form>
              

                <div class="table-responsive">


                    <table class="table table-bordered table-hover">
                        <thead class="table-active">
                        <tr>
                            <th>Sr No.</th>
                            <th>Company Name</th>
                            <th>Pan Name</th>
                            <th>Pan No.</th>
                            <th>Amount</th>
                            <th>PG Info</th>
                            <th>Payment Date</th>
                            <th>Transaction Id</th>
                            <th>Status</th>
                            <th>Mode</th>
                            <th>Admin Remark</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($list) && is_array($list)) {
                            foreach ($list as $key => $data) {
                                if ($data['status'] == 'approved') {
                                    $class = 'active-status';
                                } else if ($data['status'] == 'rejected') {
                                    $class = 'inactive-status';
                                } else if ($data['status'] == 'pending') {
                                    $class = 'pending-status';
                                }

                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $data['company_name']; ?></td>
                                    <td><?php echo $data['first_name'] . ' ' . $data['last_name']; ?></td>
                                    <td><?php echo $data['pan_number']; ?></td>
                                    <td>
                                        <i class="fa fa-inr"
                                           aria-hidden="true"></i> <?php echo custom_money_format($data['amount']); ?></a>
                                    </td>
                                    <td>
                                        <?php echo $data['pg_order_id'] != "" && $data['pg_order_id'] != null ? "Order Id :" . $data['pg_order_id'] . "<br/>" : ""; ?>
                                        <?php echo $data['pg_transaction_id'] != "" && $data['pg_transaction_id'] != null ? "Tranaction Id :" . $data['pg_transaction_id'] . "<br/>" : ""; ?>
                                    </td>
                                    <td><?php if ($data['payment_date']) {
                                            echo timestamp_to_date($data['payment_date']);
                                        } ?></td>
                                    <td><?php echo $data['bank_transaction_id']; ?></td>
                                    <td>
                           <span class="<?php echo $class ?>" <?php if ($data['status'] == 'pending') { ?> onclick='payment_status_modal("<?php echo dev_encode($data["id"]); ?>","<?php echo ucfirst($data['company_name']) ?>")' <?php } ?>>
                           <?php echo ucfirst($data['status']); ?>
                           </span>
                                    </td>
                                    <td>
                                        <?php echo $data['payment_mode']; ?>
                                        <?php if ($data['file_name']){ ?> <br/>
                                        <a href="<?php echo root_url . "uploads/make_payment/" . $data['file_name']; ?>"
                                           target="_blank">
                                            <?php echo 'View ' . $data['payment_mode']; ?>
                                            <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo $data['admin_remark']; ?>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" view-data-modal="true" data-controller='accounts'
                                           data-id="<?php echo dev_encode($data['id']); ?>"
                                           data-href="<?php echo site_url('/accounts/wl-payment-history-detail/') . dev_encode($data['id']); ?>">View
                                            Details </a>
                                    </td>
                                </tr>
                            <?php }
                        } else {
                            echo "<tr> <td colspan='11' class='text-center'><b>No Data Found</b></td></tr>";
                        } ?>
                        </tbody>
                    </table>
                </div>
                 <div class="row pagiantion_row align-items-center gy-4">
                        <div class="col-md-6">
                            <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
                        </div>
                        <div class="col-md-6">
                            <?php if ($pager) : ?>
                                <?= $pager->links() ?><?php endif ?>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div id="payment_status_changexxx" class="modal fade"  tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="<?php echo site_url('accounts/payment-status-change'); ?>" method="post" tts-form="true"
                  name="form_password_changef">
                <div class="modal-header">
                    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
                    <h5>Change Status</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-mb-20">
                                <p>Company Name : <strong class="company_name tts_agent_company"></strong></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-mb-20">
                                <select class="form-select" name="status">
                                    <option value="" selected>Select Payment Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                                <input type="hidden" name="checkedvalue">
                            </div>
                        </div>
                        <input type="hidden" name="payment_id" class="payment_id">
                        <div class="col-md-12">
                            <div class="form-group form-mb-20">
                                <label>Remark*</label>
                                <textarea class="form-control" name="admin_remark" rows="3" cols="15"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" >Save</button>
                    
                </div>
            </form>
        </div>
    </div>
</div>

<div id="payment_status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">


            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Payment Status Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?php echo site_url('accounts/wl-payment-status-change'); ?>" method="post" tts-form="true"
                  name="form_password_change">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-mb-20">
                                <p>Company Name : <strong class="company_name tts_agent_company"></strong></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-mb-20">
                                <select class="form-select" name="status">
                                    <option value="" selected>Select Payment Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                                <input type="hidden" name="checkedvalue">
                            </div>
                        </div>
                        <input type="hidden" name="payment_id" class="payment_id">
                        <div class="col-md-12">
                            <div class="form-group form-mb-20">
                                <label>Remark*</label>
                                <textarea class="form-control" name="admin_remark" rows="3" cols="15"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Change Status</button>

                </div>
            </form>
        </div>
    </div>
</div>