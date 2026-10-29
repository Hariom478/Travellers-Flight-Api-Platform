<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-2">
                        <span> Slider List</span>
                    </div>
                    <div class="tts-col-10 text_right">

                        <?php if (permission_access("Slider", "add_slider")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='slider'
                                data-href="<?php echo site_url('slider/add-slider-template') ?>"><i
                                    class="tts-icon add "></i> Add Slider
                        </button>
                        <?php }?>

                        <?php if (permission_access("Slider", "slider_status")) { ?>
                        <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i
                                    class="tts-icon swap"></i> Change Status
                        </button>
                        <?php }?>
                        <?php if (permission_access("Slider", "delete_slider")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formsliderlist')"><i class="tts-icon delete "></i> Delete
                        </button>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">


            <div class="card-body">

                <div class="responcive_table table_box_shadow">
                    <?php $trash_uri = "slider/remove-slider"; ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formsliderlist">
                        <table class="table-strip divName">
                            <thead>
                            <tr>
                                <?php if (permission_access("Slider", "delete_slider") || permission_access("Slider", "slider_status")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php }?>

                                <th>Slider Image</th>
                                <th>Image Text1</th>
                                <th>Image Text2</th>
                                <th>Button Text</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Modified Date</th>
                                <?php if (permission_access("Slider", "edit_slider")) { ?>
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
                                    ?>

                                    <tr>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>

                                        <td>
                                            <img src="<?php echo root_url . "uploads/sliders/thumbnail/" . $data['slider_image']; ?>"
                                                 alt="<?php echo $data['slider_text1']; ?>" class="tts-blog-image">
                                        </td>

                                        <td>
                                            <?php echo ucfirst($data['slider_text1']); ?>
                                        </td>
                                        <td>
                                            <?php echo ucfirst($data['slider_text2']); ?>
                                        </td>
                                        <td><?php echo ucfirst($data['url_button_text']); ?></td>
                                        <td><?php echo ucfirst($data['image_category']); ?></td>

                                        <td>

                                            <span class="<?php echo $class ?>">
                                            <?php echo ucfirst($data['status']); ?>
                                            </span>

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
                                        <?php if (permission_access("Slider", "edit_slider")) { ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true" data-controller='slider'
                                               data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('/slider/edit-slider-template/') . dev_encode($data['id']); ?>"><i
                                                        class="tts-icon edit "></i></a>

                                        </td>
                                        <?php }?>

                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text_center'><b>No Slider Found</b></td></tr>";
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


<!-- status status change content -->
<div id="status_change" class="modal">
    <div class="top-model-content">
        <form action="<?php echo site_url('slider/slider-status-change'); ?>" method="post" tts-form="true"
              name="form_change_status">
            <div class="modal-header">
                <span class="close" onclick="ttsclosemodel('status_change')">&times;</span>
                <h5>Change Status</h5>
            </div>
            <div class="modal-body">
                <div class="tts_row">
                    <div class="tts-col-12">
                        <div class="form-group">

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
            <div class="top-model-footer">
                <div class="tts_row">
                    <div class="tts-col-12">
                        <button class="badge badge-md badge-primary" type="submit" value="Save">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Show Detail Blogs Modal content -->
<div id="view_slider" class="modal">
    <div class="modal-content" data-modal-view="view_modal_data"></div>
</div>
