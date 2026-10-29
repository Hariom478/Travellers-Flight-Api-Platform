<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row">
                    <div class="col-2">
                        <span> Blog Category List</span>
                    </div>
                    <div class="col-10 text-end">

                        <a href="<?php echo site_url('/blog'); ?>" class="badge badge-wt"><i class="fa fa-list"></i> Blog
                            List</a>

                        <?php if (permission_access("Blog", "add_blog_category")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='blogs'
                                data-href="<?php echo site_url('blog/add-blog-category-template') ?>"><i
                                    class="fa fa-add"></i> Add Blog Category

                        </button>
                        <?php } ?>

                        <?php if (permission_access("Blog", "blog_category_status")) { ?>
                        <button class="badge badge-wt" onclick="confirm_change_status('status_change')"> <i class="fa fa-exchange"></i> Change Status</button>
                        <?php } ?>

                        <?php if (permission_access("Blog", "delete_blog_category")) { ?>
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

                <div class="table-responsive">
                    <?php

                    $trash_uri = "blog/remove-blog-category";
                    ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formbloglist">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>
                                <?php if (permission_access("Blog", "blog_category_status") || permission_access("Blog", "delete_blog_category")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php }?>

                                <th>Category Image</th>
                                <th>Category Title</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Modified Date</th>
                                <?php if (permission_access("Blog", "edit_blog_category")) { ?>
                                <th>Action</th>
                                <?php }?>

                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            if (!empty($data_list) && is_array($data_list)) {
                                foreach ($data_list as $data) {

                                    if($data['status'] =='active'){
                                        $status_class ='active-status';
                                    }else{
                                        $status_class ='inactive-status';
                                    }
                                    ?>

                                    <tr>
                                        <?php if (permission_access("Blog", "blog_category_status") || permission_access("Blog", "delete_blog_category")) { ?>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php }?>

                                        <td>
                                            <img src="<?php echo root_url . "uploads/blog/thumbnail/" . $data['category_img']; ?>"
                                                 alt="<?php echo $data['category_img']; ?>" class="tts-blog-image" width="70">
                                        </td>

                                        <td><?php echo ucfirst($data['category_name']); ?></td>
                                        <td>

                                            <span class="<?php echo $status_class?>">
                                            <?php echo ucfirst($data['status']); ?>
                                            </span>

                                        </td>


                                        <td><?php
                                            if ($data['created']) {
                                                echo date_created_format($data['created']);
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($data['modified']) {
                                                echo date_created_format($data['modified']);
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <?php if (permission_access("Blog", "edit_blog_category")) { ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true" data-controller='blogs'
                                               data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('/blog/edit-blog-category-template/') . dev_encode($data['id']); ?>"><i
                                                        class="fa fa-edit"></i></a>

                                        </td>
                                        <?php }?>

                                    </tr>
                                <?php }
                            } else {
                                echo "<tr style='text-align: center;'> <td colspan='11' class='text_center'><b>No Blogs Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="row pagiantion_row align-items-center"> 
                    <div class="col-md-6 mb-3 mb-lg-0">
                        <p class="pagiantion_text">Page  <?= $pager->getCurrentPage() ?> of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
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
            <form action="<?php echo site_url('blog/blog-category-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
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