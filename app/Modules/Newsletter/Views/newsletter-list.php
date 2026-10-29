
<div class="content">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-2">
                        <span> <?php echo $title; ?> List</span>
                    </div>
                    <div class="tts-col-10 text_right">
                        <?php if (permission_access("Newsletter", "add_newsletter")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='newsletters'  data-href="<?php echo site_url('newsletter/add-newsletter-template')?>"> <i class="tts-icon add "></i> Add Newsletters </button>
                        <?php }?>

                        <?php if (permission_access("Newsletter", "newsletter_export")) { ?>
                        <?php if ($pager->getTotal()!=0) : ?>
                        <button class="badge badge-wt" onclick="ttsopenmodel('tts_export_modal')"> <i class="tts-icon export"></i> Export </button>
                        <?php endif ?>
                        <?php }?>
                        <?php if (permission_access("Newsletter", "newsletter_import")) { ?>
                        <button class="badge badge-wt" onclick="ttsopenmodel('tts_import_modal')"> <i class="tts-icon import"></i> Import </button>
                        <?php }?>

                        <?php if (permission_access("Newsletter", "delete_newsletter")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge onclick="confirm_delete('formbloglist')"> <i class="tts-icon delete "></i> Delete </button>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">
            <div class="card-body">
            <div class="tts_row mb_10">
                <!----------Start Search Bar ----------------->
                <form action="<?php echo site_url('newsletter'); ?>" method="GET" class="tts-dis-content" name="newsletter-search" onsubmit="return searchvalidateForm()">
                        <div class="tts-col-3">
                            <div class="form-group">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-control" onchange="tts_searchkey(this,'newsletter-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="name" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='name'){ echo "selected";} ?>>Name</option>
                                    <option value="email" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='email'){ echo "selected";} ?>  >Email ID</option>
                                    <option value="date-range" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range'){ echo "selected";} ?>>Date Range</option>
                                </select>
                            </div>
                            <input type="hidden" name="key-text" value="<?php if(isset($search_bar_data['key-text'])){ echo trim($search_bar_data['key-text']); } ?>">
                        </div>
                        <div class="tts-col-3">
                            <div class="form-group">
                                <label><?php if(isset($search_bar_data['key']) && $search_bar_data['key']!='date-range') { echo $search_bar_data['key-text']. " *"; } else { echo "Value"; } ?> </label>
                                <input type="text" name="value" placeholder="Value"  value="<?php if(isset($search_bar_data['value'])){ echo $search_bar_data['value']; } ?>" class="form-control" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') { echo "disabled"; } ?> <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') {  } else { echo 'tts-validatation="Required"'; } ?>   tts-error-msg="Please enter value" />
                            </div>
                        </div>
                        <div class="tts-col-2">
                            <div class="form-group">
                                <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if(isset($search_bar_data['from_date'])){ echo $search_bar_data['from_date']; } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                            </div>
                        </div>
                        <div class="tts-col-2">
                            <div class="form-group">
                                <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if(isset($search_bar_data['to_date'])){ echo $search_bar_data['to_date']; } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                            </div>
                        </div>
                        <div class="tts-col-1">
                            <div class="form-group">
                                <label></label><br />
                                <button type="submit" class="badge badge-md badge-primary">Search</button>
                            </div>
                        </div>
                        <? if(isset($search_bar_data['key'])): ?>
                        <div class="tts-col-1">
                            <div class="search-reset-btn">
                                <a href="<?php echo site_url('newsletter');?>">Reset Search</a>
                            </div>
                        </div>
                        <? endif ?>
                    </form>    
                </div>

                <!----------End Search Bar ----------------->
                
                <div class="responcive_table">
                    <?php

                    $trash_uri = "newsletter/remove-newsletter";
                    ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formbloglist">
                        <table class="table-strip divName">
                            <thead>
                            <tr>
                                <?php if (permission_access("Newsletter", "delete_newsletter")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall" /></label></th>
                                <?php }?>
                                <th>Name</th>
                                <th>Email ID</th>
                                <th>Created Date</th>
                                <?php if (permission_access("Newsletter", "edit_newsletter")) { ?>
                                <th>Action</th>
                                <?php }?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            if (!empty($newsletter_list) && is_array($newsletter_list)) {
                                foreach ($newsletter_list as $data) { ?>

                                    <tr>
                                        <?php if (permission_access("Newsletter", "delete_newsletter")) { ?>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                                        </td>
                                        <?php }?>
                                        <td>
                                            <?php echo ucfirst($data['name']); ?>
                                        </td>

                                        <td><?php echo ($data['email']); ?></td>

                                        <td><?php echo date_created_format($data['created']); ?></td>

                                        <?php if (permission_access("Newsletter", "edit_newsletter")) { ?>
                                            <td>
                                                <a href="javascript:void(0);" view-data-modal="true" data-controller='newsletters' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/newsletter/edit-newsletter-template/') . dev_encode($data['id']); ?>"><i class="tts-icon edit "></i></a>
                                            </td>
                                        <?php }?>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text_center'><b>No Newsletter Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </form>

                    <div class="d-flex justify-content-end">
                        <div class="tts_row">
                            <div class="tts-col-6">
                                <p class="pagiantion_text">Page  <?= $pager->getCurrentPage() ?> of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
                            </div>
                            <div class="tts-col-6">
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




<!-- start export modal content -->
<div id="tts_export_modal" class="modal">
    <div class="top-model-content">
        <form action="<?php echo site_url('newsletter/export-newsletter'); ?>" method="post" tts-form="true">
            <div class="modal-header">
                <span class="close" onclick="ttsclosemodel(this)">&times;</span>
                <h5>Export Data</h5>
            </div>
            <div class="modal-body">
                <div class="tts_row">
                    <div class="tts-col-6 pr0">
                        <div class="form-group">
                            <label>From Date *</label>
                            <input class="form-control" type="text" name="from_date" data-export-from="true" placeholder="Select From Date" readonly>
                        </div>
                    </div>
                    <div class="tts-col-6 pl0">
                        <div class="form-group">
                            <label>To Date *</label>
                            <input class="form-control borl0" type="text" name="to_date" data-export-to="true" placeholder="Select To Date" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="top-model-footer">
                <div class="tts_row">
                    <div class="tts-col-12">
                        <button class="badge badge-md badge-primary" type="submit" value="Save">Export</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- end export modal content -->


<!-- start import modal content -->
<div id="tts_import_modal" class="modal">
    <div class="top-model-content">
       <form action="<?php echo site_url();?>newsletter/import-newsletter" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-header">
                <span class="close" onclick="ttsclosemodel(this)">&times;</span>
                <h5>Import Data</h5>
            </div>
            <div class="modal-body">
                <div class="tts_row">
                    <div class="tts-col-12">
                        <div class="form-group">
                            <label>Select File *</label>
                            <input type="file" class="form-control" name="fileimport" accept=".xlsx, .xls, .csv">
                        </div>
                        <p><b>Note :</b> Maybe take time to upload file depending on file size. </p>
                    </div>
                  
                </div>
            </div>
            <div class="top-model-footer">
                <div class="tts_row">
                    <div class="tts-col-12">
                        <button class="badge badge-md badge-primary" type="submit" value="Save">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- end import modal content -->


