<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0">Super Admin Visa Discount</h5>
                    </div>
                    <div class="col-md-8 text-md-right">
                        <?php if (permission_access("Visa", "add_visa_discount")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='flight'
                                data-href="<?php echo site_url('markup-discount/super-admin-visa-discount-view') ?>"><i
                                    class="fa-solid fa-add"></i> Add Visa Discount
                        </button>
                        <?php }?>
                        <?php if (permission_access("Visa", "visa_discount_status")) { ?>
                        <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i
                                    class="fa-solid fa-exchange"></i> Change Status
                        </button>
                        <?php }?>
                        <?php if (permission_access("Visa", "delete_visa_discount")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formmarkuplist')"><i class="fa-solid fa-trash"></i> Delete
                        </button>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">
            <div class="card-body">
                <div class="row ">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('markup-discount/super-admin-visa-discount-list'); ?>" method="GET" class="tts-dis-content" name="markup-search" onsubmit="return searchvalidateForm()">
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-control" onchange="tts_searchkey(this,'markup-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="visa_country_id" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='visa_country_id'){ echo "selected";} ?>>Visa Country</option>
                                    <option value="visa_type_id" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='visa_type_id'){ echo "selected";} ?>  >Visa Type</option>

                                    <option value="discount_type" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='discount_type'){ echo "selected";} ?>  >Discount Type</option>
                                    <option value="date-range" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range'){ echo "selected";} ?>>Date Range</option>
                                </select>
                            </div>
                            <input type="hidden" name="key-text" value="<?php if(isset($search_bar_data['key-text'])){ echo trim($search_bar_data['key-text']); } ?>">
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label><?php if(isset($search_bar_data['key']) && $search_bar_data['key']!='date-range') { echo $search_bar_data['key-text']. " *"; } else { echo "Value"; } ?> </label>
                                <input type="text" name="value" placeholder="Value"  value="<?php if(isset($search_bar_data['value'])){ echo $search_bar_data['value']; } ?>" class="form-control" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') { echo "disabled"; } ?> <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') {  } else { echo 'tts-validatation="Required"'; } ?>   tts-error-msg="Please enter value" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if(isset($search_bar_data['from_date'])){ echo $search_bar_data['from_date']; } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if(isset($search_bar_data['to_date'])){ echo $search_bar_data['to_date']; } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly/>
                            </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <div class="form-group form-mb-20">
                               
                                <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                            </div>
                        </div>
                         <div class="col-md-2">
                        <? if(isset($search_bar_data['key'])): ?>
                           
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('markup-discount/super-admin-visa-discount-list');?>">Reset Search</a>
                                </div>
                           
                        <? endif ?>
                         </div>
                    </form>
                </div>

                <!----------End Search Bar ----------------->

                
                    <?php
                    $trash_uri = "markup-discount/remove-super-admin-visa-discount";
                    ?>
                <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formmarkuplist">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>
                                <?php if (permission_access("Visa", "delete_visa_discount") || permission_access("Visa", "visa_discount_status")) { ?>

                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php }?>
                                <th>Web Partner Class</th>
                                <th>Visa Country</th>
                                <th>Visa Type</th>
                                <th>Value</th>
                                <th>Max Limit</th>
                                <th>Discount Type</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Modified</th>
                                <?php if (permission_access("Visa", "edit_visa_discount")) { ?>
                                <th>Action</th>
                                <?php }?>
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
                                    $class_id = explode(',', $data['web_partner_class_id']);

                                    $partner_class='';
                                    if (!empty($web_partner_class)) {
                                        foreach ($class_id as $id) {

                                            $partner_class .= ucfirst($web_partner_class[$id]) . ',';

                                        }
                                    }
                                    $partner_class = rtrim($partner_class, ',');



                                    $visa_type_id = explode(',', $data['visa_type_id']);

                                    $visa_title='';
                                    if (!empty($visa_type_list)) {
                                        foreach ($visa_type_id as $id) {

                                            $visa_title .= ucfirst($visa_type_list[$id]) . ',';

                                        }
                                    }
                                    $visa_title = $visa_title.'<br/>';

                                    ?>
                                    <tr>
                                        <?php if (permission_access("Visa", "delete_visa_discount") || permission_access("Visa", "visa_discount_status")) { ?>

                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php }?>
                                        <td><?php echo $partner_class; ?></td>
                                        <td>
                                            <?php echo $data['country_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $visa_title; ?>
                                        </td>
                                        <td><?php echo $data['value']; ?></td>
                                        <td><?php echo $data['max_limit']; ?></td>
                                        <td>
                                            <?php echo $data['discount_type']; ?>
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
                                        <?php if (permission_access("Visa", "edit_visa_discount")) { ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true" data-controller='visa' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/markup-discount/edit-admin-visa-discount-template/') . dev_encode($data['id']); ?>"><i class="tts-icon edit "></i></a>
                                        </td>
                                        <?php }?>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text_center'><b>No Visa Discount Found</b></td></tr>";
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


<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">    
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo site_url('markup-discount/super-admin-visa-discount-status-change'); ?>" method="post" tts-form="true"
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
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show  status Modal content -->

