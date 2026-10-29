<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0">  <?php echo $title . ' '; ?>List</h5>
                    </div>
                    <div class="col-md-8 text-end">
                        <?php //if (permission_access_error("Feedback", "add_feedback")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='feedback'
                                data-href="<?php echo site_url('supplier-management/add-flight-api-mgt-template') ?>">
                                <i class="fa-solid fa-add"></i> Add Flight API Management
                        </button>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='feedback'
                                data-href="<?php echo site_url('supplier-management/api-flight-supplier-mgt-template') ?>">
                                <i class="fa-solid fa-add"></i> API Supplier
                        </button>
                        <?php //} ?>
                        <?php //if (permission_access_error("Feedback", "feedback_status")) { ?>
                            <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i
                                        class="fa-solid fa-exchange"></i> Change Status
                            </button>
                        <?php //} ?>
                        <?php //if (permission_access_error("Feedback", "delete_feedback")) { ?>
                            <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                    onclick="confirm_delete('formbloglist')"><i class="fa-solid fa-trash"></i> Delete
                            </button>
                        <?php //} ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">
            <div class="card-body">

                <div class="row">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('supplier-management'); ?>" method="GET" class="tts-dis-content row mb-3"
                          name="feedback-search" onsubmit="return searchvalidateForm()">
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-control" onchange="tts_searchkey(this,'feedback-search')"
                                        tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="air_type" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'air_type') {
                                        echo "selected";
                                    } ?>>Air Type
                                    </option>
                                    <option value="search_type" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'search_type') {
                                        echo "selected";
                                    } ?> >Search Type
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
                            <div class="form-group form-mb-20">
                                <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date"
                                                               value="<?php if (isset($search_bar_data['from_date'])) {
                                                                   echo $search_bar_data['from_date'];
                                                               } ?>" placeholder="Select From Date" class="form-control"
                                                               tts-error-msg="Please select from date" readonly/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date"
                                                             value="<?php if (isset($search_bar_data['to_date'])) {
                                                                 echo $search_bar_data['to_date'];
                                                             } ?>" placeholder="Select To Date" class="form-control"
                                                             tts-error-msg="Please select to date" readonly/>
                            </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <div class="form-group form-mb-20">
                                
                                <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        
                        <? if (isset($search_bar_data['key'])): ?>
                            <div class="col-md-3 mb-3">
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('supplier-management'); ?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>
                       
                    </form>
                </div>

                <!----------End Search Bar ----------------->
               
                <?php $trash_uri = "supplier-management/remove-flight-api-mgt"; ?>
                <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formbloglist">
                     <div class="table-responsive">    
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>
                                <?php //if (permission_access_error("Feedback", "delete_feedback") || permission_access_error("Feedback", "feedback_status")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php //}?>

                                <th>API Supplier</th>
                                <th>Air Type</th>
                                <th>Search Type</th>
                                <th>Allowed Airline</th>
                                <th>Excluded Airline</th>
                                <th>Status</th>
                                <th>Created</th>
                                <?php //if (permission_access_error("Feedback", "edit_feedback")) { ?>
                                <th>Action</th>
                                <?php //}?>

                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            if (!empty($list) && is_array($list)) {
                                foreach ($list as $data) {
                                    if ($data['status'] == 'active') {
                                        $status_class = 'active-status';
                                    } else {
                                        $status_class = 'inactive-status';
                                    }
                                    ?>

                                    <tr>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>

                                        <td>
                                            <?php echo($data['supplier_name']); ?>
                                        </td>

                                        <td><?php echo($data['air_type']); ?></td>
                                        <td><?php echo($data['search_type']); ?></td>

                                        <td><?php echo($data['allowed_airline']); ?></td>
                                        <td><?php echo($data['excluded_airline']); ?></td>

                                        <td>

                                            <span class="<?php echo $status_class ?>">
                                            <?php echo ucfirst($data['status']); ?>
                                            </span>

                                        </td>


                                        <td><?php echo date_created_format($data['created']); ?></td>
                                        <?php //if (permission_access_error("Feedback", "edit_feedback")) { ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true"
                                               data-controller='feedback'
                                               data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('/supplier-management/edit-flight-api-mgt-template/') . dev_encode($data['id']); ?>"><i
                                                        class="fa-solid fa-edit"></i></a>
                                        </td>
                                        <?php //}?>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text-center'><b>No Data Found</b></td></tr>";
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
</div>
</div>


<!-- status status change content -->
<div id="status_change" class="modal fade"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">    
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('supplier-management/flight-api-mgt-status-change'); ?>" method="post" tts-form="true"
                  name="form_change_status">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-mb-20">

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
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" >Save</button>
                  
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Show Detail feedback Modal content -->
<div id="view_feedback" class="modal">
    <div class="modal-content" data-modal-view="view_modal_data"></div>
</div>