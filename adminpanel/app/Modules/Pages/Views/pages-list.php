<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="m-0"> Pages List</h5>
                    </div>
                    <div class="col-md-8 text-md-end">

                        <?php if(permission_access_error("Page", "add_page")) { ?>
                            <button class="badge badge-wt" view-data-modal="true" data-controller='pages' data-href="<?php echo site_url('pages/add-pages-template')?>"><i class="fa-solid fa-add "></i> Add Page </button>
                        <?php }  ?>
                        <?php if(permission_access_error("Page", "change_status")) { ?>
                             <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i class="fa-solid fa-exchange"></i> Change Status</button>
                        <?php }  ?>
                        <?php if(permission_access_error("Page", "delete_page")) { ?>
                             <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge onclick="confirm_delete('formpagelist')"><i class="fa-solid fa-trash"></i> Delete  </button>
                        <?php }  ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="card">


            <div class="card-body">
             
                    <!----------Start Search Bar ----------------->
                    <form class="row g-3 mb-3" action="<?php echo site_url('pages'); ?>" method="GET" class="tts-dis-content"
                          name="page-search" onsubmit="return searchvalidateForm()">
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label class="form-label">Select key to search by *</label>
                                <select name="key" class="form-select"
                                        onchange="tts_searchkey(this,'page-search')" tts-validatation="Required"
                                        tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="title" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'title') {
                                        echo "selected";
                                    } ?>>Title
                                    </option>
                                    <option value="status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'status') {
                                        echo "selected";
                                    } ?> >Status
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
                            <div class="form-group form-mb-20">
                                <label class="form-label">From Date</label><input type="text" data-searchbar-from="true" name="from_date"
                                                               value="<?php if (isset($search_bar_data['from_date'])) {
                                                                   echo $search_bar_data['from_date'];
                                                               } ?>" placeholder="Select From Date" class="form-control"
                                                               tts-error-msg="Please select from date"/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label class="form-label">To Date</label><input type="text" data-searchbar-to="true" name="to_date"
                                                             value="<?php if (isset($search_bar_data['to_date'])) {
                                                                 echo $search_bar_data['to_date'];
                                                             } ?>" placeholder="Select To Date" class="form-control"
                                                             tts-error-msg="Please select to date"/>
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
                                    <a href="<?php echo site_url('pages'); ?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>
                       
                    </form>
              
                <!----------End Search Bar ----------------->

                <div class="table-responsive">
                    <?php $trash_uri = "pages/remove-pages"; ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formpagelist">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>
                                <?php if(permission_access_error("Page", "delete_page")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label></th>
                                <?php } ?>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Modified Date</th>
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
                                    ?>

                                    <tr>
                                        <?php if(permission_access_error("Page", "delete_page")) { ?>
                                            <td>
                                                <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>"/></label>
                                            </td>
                                        <?php } ?>
                                        <td>
                                            <?php echo $data['title']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['slug_url']; ?>
                                        </td>

                                        <td>
                                            <span class="<?php echo $class; ?>"><?php echo ucfirst($data['status']); ?></span>
                                        </td>

                                        <td><?php echo date_created_format($data['created']); ?></td>
                                        <td><?php
                                            if (isset($data['modified'])) {
                                                echo date_created_format($data['modified']);
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php if(permission_access_error("Page", "edit_page")) { ?>
                                                <a href="javascript:void(0);" view-data-modal="true" data-controller='pages' data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('/pages/edit-pages-template/') . dev_encode($data['id']); ?>"><i class="fa-solid fa-edit "></i></a>
                                            <?php } ?>

                                            <a href="<?php echo root_url.''.$data['slug_url'];?>" target="_blank" class="ms-2"><i class="fa-solid fa-eye"></i></a>

                                        </td>
                                       
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='7' class='text-center'><b>No Pages Found</b></td></tr>";
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
<div id="status_change" class="modal fade" tabindex="-1"  aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Change Status</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('pages/pages-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
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
