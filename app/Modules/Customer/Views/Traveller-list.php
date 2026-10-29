<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-2">
                        <span> Traveller List</span>
                    </div>
                    <div class="tts-col-10 text_right">

                        <a href="<?php echo site_url('/customer/'); ?>" class="badge badge-wt" ><i class="tts-icon list "></i>Customer List</a>
                        <?php if (permission_access("Customer", "add_traveller")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='customer'
                                data-href="<?php echo site_url('customer/add-travellers-template/' . dev_encode($customer_id)); ?>"><i
                                    class="tts-icon add "></i> Add Traveller
                        </button>
                        <?php }?>
                        <?php if (permission_access("Customer", "traveller_status")) { ?>
                        <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i
                                    class="tts-icon swap"></i> Change Status
                        </button>
                        <?php }?>
                        <?php if (permission_access("Customer", "delete_traveller")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formtravellerlist')"><i class="tts-icon delete "></i> Delete
                        </button>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">

            <div class="card-body">

                <div class="responcive_table table_box_shadow">
                    <?php $trash_uri = "customer/remove-traveller"; ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formtravellerlist">
                        <table class="table-strip divName">
                            <thead>
                            <tr>
                                <?php if (permission_access("Customer", "delete_traveller") ||permission_access("Customer", "traveller_status")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php }?>

                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Modified Date</th>
                                <?php if (permission_access("Customer", "edit_traveller")) { ?>
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
                                        <?php if (permission_access("Customer", "delete_traveller") ||permission_access("Customer", "traveller_status")) { ?>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php }?>

                                        <td>
                                            <?php
                                            $title = ucfirst($data['title']);
                                            $name = ucfirst($data['first_name'] . ' ' . $data['last_name']);
                                            $full_name = $title . ' ' . $name;

                                            ?>

                                            <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true"
                                               data-controller='customer'
                                               data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('customer/traveller-details/') . dev_encode($data['id']); ?>"><?php echo $full_name; ?></a>
                                        </td>
                                        <td>
                                           <?php echo $data['email_id']; ?>
                                        </td>

                                        <td>
                                            <?php echo $data['mobile_no']; ?>
                                        </td>

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
                                        <?php if (permission_access("Customer", "edit_traveller")) { ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true" data-controller='customer' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/customer/edit-traveller-template/') . dev_encode($data['id']); ?>"><i class="tts-icon edit "></i></a>
                                        </td>
                                        <?php }?>

                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text_center'><b>No Travellers Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </form>

                    <div class="d-flex justify-content-end">
                        <div class="tts_row">
                            <div class="tts-col-6">
                                <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                    of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
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
        <form action="<?php echo site_url('customer/traveller-status-change'); ?>" method="post" tts-form="true"
              name="form_change_status">
            <div class="modal-header">
                <span class="close" onclick="ttsclosemodel(this)">&times;</span>
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
