<div class="container-fluid">    
    <div class="content ">
        <div class="page-content">
            <div class="table_title">
                <div class="sale_bar">
                    <div class="row">
                        <div class="col-4">
                            <span>Blog List</span>
                        </div>
                        <div class="col-8 text-end">
                            <?php if (permission_access("Blog", "blog_category_list")) { ?>

                            <a href="<?php echo site_url('/blog/blog-category-list/'); ?>" class="badge badge-wt">
                                <i class="fa fa-list"></i> Blog Category</a>
                            <?php } ?>

                            <?php if (permission_access("Blog", "add_blog")) { ?>

                                <button class="badge badge-wt" view-data-modal="true" data-controller='blogs'
                                        data-href="<?php echo site_url('blog/add-blog-template') ?>">
                                    <i class="fa fa-add "></i> Add Blog
                                </button>
                            <?php } ?>

                            <?php if (permission_access("Blog", "blog_status")) { ?>
                            <button class="badge badge-wt" onclick="confirm_change_status('status_change')">
                                <i class="fa fa-exchange"></i> Change Status
                            </button>
                            <?php } ?>

                            <?php if (permission_access("Blog", "delete_blog")) { ?>
                            <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                    onclick="confirm_delete('formbloglist')"><i class="fa fa-trash"></i> Delete
                            </button>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!----------Start Search Bar ----------------->
                        <form action="<?php echo site_url('blog'); ?>" method="GET" class="row"
                            name="blog-search" onsubmit="return searchvalidateForm()">
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Select key to search by *</label>
                                    <select name="key" class="form-select" onchange="tts_searchkey(this,'blog-search')"
                                            tts-validatation="Required" tts-error-msg="Please select search key">
                                        <option value="">Please select</option>
                                        <option value="post_title" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'post_title') {
                                            echo "selected";
                                        } ?>>Post Title
                                        </option>
                                        <option value="posted_by" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'posted_by') {
                                            echo "selected";
                                        } ?> >Posted By
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
                            <div class="col-3">
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
                            <div class="col-2">
                                <div class="form-group">
                                    <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date"
                                                                value="<?php if (isset($search_bar_data['from_date'])) {
                                                                    echo $search_bar_data['from_date'];
                                                                } ?>" placeholder="Select From Date" class="form-control"
                                                                tts-error-msg="Please select from date" readonly/>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date"
                                                                value="<?php if (isset($search_bar_data['to_date'])) {
                                                                    echo $search_bar_data['to_date'];
                                                                } ?>" placeholder="Select To Date" class="form-control"
                                                                tts-error-msg="Please select to date" readonly/>
                                </div>
                            </div>
                            <div class="col-1">
                                <div class="form-group">
                                    <label></label><br/>
                                    <button type="submit" class="badge badge-md badge-primary badge_search">Search</button>
                                </div>
                            </div>
                            <? if (isset($search_bar_data['key'])): ?>
                                <div class="col-2">
                                    <div class="search-reset-btn">
                                        <a href="<?php echo site_url('blog'); ?>">Reset Search</a>
                                    </div>
                                </div>
                            <? endif ?>
                        </form>
                    </div>

                    <!----------End Search Bar ----------------->

                    <div class="table-responsive">
                        <?php $trash_uri = "blog/remove-blog"; ?>
                        <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formbloglist">
                            <table class="table table-bordered table-hover">
                                <thead class="table-active">
                                <tr>

                                    <?php if (permission_access("Blog", "delete_blog") || permission_access("Blog", "blog_status")) { ?>
                                    <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                    </th>
                                    <?php }?>

                                    <th>Blog Image</th>
                                    <th>Blog Title</th>
                                    <th>Slug</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Post By</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                    <?php if (permission_access("Blog", "edit_blog")) { ?>
                                    <th>Action</th>
                                    <?php }?>

                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                if (!empty($blog_list) && is_array($blog_list)) {
                                    foreach ($blog_list as $blog_data) {

                                        if ($blog_data['status'] == 'active') {
                                            $status_class = 'active-status';
                                        } else {
                                            $status_class = 'inactive-status';
                                        }
                                        ?>

                                        <tr>
                                            <?php if (permission_access("Blog", "delete_blog") || permission_access("Blog", "blog_status")) { ?>
                                            <td>
                                                <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                            value="<?php echo $blog_data['id']; ?>"/></label>
                                            </td>
                                            <?php }?>

                                            <td>
                                                <img src="<?php echo root_url . "uploads/blog/thumbnail/" . $blog_data['post_images']; ?>"
                                                    alt="<?php echo $blog_data['post_title']; ?>" class="tts-blog-image" width="70">
                                            </td>

                                            <td>
                                                <?php echo $blog_data['post_title']; ?>
                                            </td>
                                            <td>
                                                <?php echo $blog_data['post_slug']; ?>
                                            </td>
                                            <td><?php echo ucfirst($blog_data['category_name']); ?></td>
                                            <td>

                                                <span class="<?php echo $status_class ?>">
                                                <?php echo ucfirst($blog_data['status']); ?>
                                                </span>

                                            </td>

                                            <td>
                                                <?php echo ucfirst($blog_data['posted_by']); ?>
                                            </td>


                                            <td><?php echo date_created_format($blog_data['created']); ?></td>
                                            <td><?php
                                                if (isset($blog_data['modified'])) {
                                                    echo date_created_format($blog_data['modified']);
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>

                                            <?php if (permission_access("Blog", "edit_blog")) { ?>
                                            <td>
                                                <a href="javascript:void(0);" view-data-modal="true" data-controller='blogs'
                                                data-id="<?php echo dev_encode($blog_data['id']); ?>"
                                                data-href="<?php echo site_url('/blog/edit-blog-template/') . dev_encode($blog_data['id']); ?>"><i
                                                            class="fa fa-edit "></i></a>
                                            </td>
                                            <?php }?>

                                        </tr>
                                    <?php }
                                } else {
                                    echo "<tr style='text-align: center;'> <td colspan='11' class='text-center'><b>No Blogs Found</b></td></tr>";
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
                    <h1 class="modal-title fs-5">Change Status</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo site_url('blog/blog-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
                <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group"> 
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
                        

