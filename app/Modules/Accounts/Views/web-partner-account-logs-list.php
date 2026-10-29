<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="table_title">
                    <div class="sale_bar">
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="m-0"> Web Partner Account Logs</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="row m0">
                        <div class="col-12">
                            <!----------Start Search Bar ----------------->
                            <form action="<?php echo site_url('webpartneraccounts/get-web-partner-account-info'); ?>"
                                method="GET" class="row" name="web-partner-search"
                                onsubmit="return searchvalidateForm()">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> Search by /Companyid/Company Name/ Web Partner Name *</label>
                                        <input type="text" class="form-control" name="search-text" value="<?php if (isset($search_bar_data['search-text'])) {
                           echo trim($search_bar_data['search-text']);
                           } ?>" tts-get-web-partner-info="true" tts-validatation="Required"
                                            tts-error-msg="Please enter search type"
                                            placeholder="Search by /Companyid/Company Name/ Web Partner Name">
                                        <input type="hidden" name="key-value" tts-web-partner-info-id="true" value="<?php if (isset($search_bar_data['key-value'])) {
                           echo trim($search_bar_data['key-value']);
                           } ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                           echo $search_bar_data['from_date'];
                           } ?>" placeholder="Select From Date" class="form-control"
                                            tts-error-msg="Please select from date" tts-validatation="Required"
                                            readonly />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="hidden" name="export_excel" value="0">
                                        <input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                           echo $search_bar_data['to_date'];
                           } ?>" placeholder="Select To Date" class="form-control"
                                            tts-error-msg="Please select to date" tts-validatation="Required"
                                            readonly />
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label></label><br />
                                        <button type="submit" class="badge badge-md badge-primary"
                                            onclick="noExportExcel()">Search</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label></label><br />
                                        <button type="submit" class="badge badge-md badge-primary"
                                            onclick="exportExcel()">Export Excel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!----------End Search Bar ----------------->
                    <?php if ($account_logs) { ?>
                    <div class="card-body">
                        <div class="vewmodelhed mb_10">
                            <div class="row m0">
                                <div class="col-md-2">
                                    <div class="vi_mod_dsc">
                                        <span> Company Name</span>
                                        <span class="primary"> <b><?php echo $details['company_name']; ?></b> </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="vi_mod_dsc">
                                        <span>Pan Name</span>
                                        <span class="primary"> <b><?php echo $details['pan_name']; ?></b> </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="vi_mod_dsc">
                                        <span>Pan No.</span>
                                        <span class="primary"> <b><?php echo $details['pan_number'] ?> </b> </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="vi_mod_dsc">
                                        <span>Available Balance</span>
                                        <span class="primary"> <b><?php echo $available_balance; ?> </b> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="responcive_table table_box_shadow">
                            <table class="table-strip divName">
                                <thead>
                                    <tr>
                                        <th>Ref. Number</th>
                                        <th>Remark</th>
                                        <th>Credit</th>
                                        <th>Debit</th>
                                        <th>Balance</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                           if (!empty($account_logs) && is_array($account_logs)) {
                               foreach ($account_logs as $data) {
                           
                           ?>
                                    <tr>
                                        <td>
                                            <?php
                                 if ($data['action_type'] == 'booking') { ?>
                                            <a href="#"> <?php echo $data['acc_ref_number']; ?></a>
                                            <?php } else {
                                 echo $data['acc_ref_number'];
                                 } ?>
                                        </td>
                                        <td>
                                            <b>
                                                <?php
                                 if ($data['action_type'] == 'booking') {
                                     if ($data['service_log']) {
                                         $service_log = json_decode($data['service_log'], true);
                                 
                                         echo service_log($data['service'], $data['action_type'], $service_log);
                                     } else {
                                         echo ucfirst($data['service']) . ' ' . ucfirst($data['action_type']);
                                     }
                                 } else {
                                     echo ucfirst($data['action_type']);
                                 }
                                 ?>
                                            </b><br />
                                            <?php echo $data['remark']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['credit']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['debit']; ?>
                                        </td>
                                        <td><?php echo $data['balance']; ?></td>
                                        <td>
                                            <?php
                                 echo date_created_format($data['created']);
                                 ?>
                                        </td>
                                    </tr>
                                    <?php
                           }
                           } else {
                           echo "<tr> <td colspan='11' class='text_center'><b>No Account Logs Found</b></td></tr>";
                           } ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <div class="row">
                                    <div class="col-md-12 d-flex align-items-center justify-content-between">
                                        <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                            of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records
                                            found
                                        </p>
                                        <span>
                                            <?php if ($pager) : ?>
                                        <?= $pager->links() ?>
                                        <?php endif ?>
                                        </span>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php  } ?>
                </div>
            </div>
        </div>
</section>
<div id="view_agent" class="modal">
    <div class="modal-content" data-modal-view="view_modal_data"></div>
</div>