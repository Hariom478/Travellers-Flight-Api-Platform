<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="m-0"> <?php echo $title . ' '; ?>List</h5>
                    </div>
                    <div class="col-md-8 text-md-end">

                         <?php if (permission_access("Setting", "add_bank_account")) { ?>
                            <button class="badge badge-wt" view-data-modal="true" data-controller='bankaccounts'
                                    data-href="<?php echo site_url('bankaccounts/add-account-template')  ?>"><i
                                        class="fa-solid fa-add "></i> Add Bank Account
                            </button>
                        <?php } ?>

                        <?php if (permission_access("Setting", "delete_bank_account")) { ?>
                            <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                    onclick="confirm_delete('formbankaccountslist')"><i class="fa-solid fa-trash"></i> Delete
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

                    $trash_uri = "bankaccounts/remove-account";
                    ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formbankaccountslist">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>
                               <?php if(permission_access("Setting", "delete_bank_account")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label> </th>
                               <?php } ?>
                                <th>Bank Name</th>
                                <th>Branch Name</th>
                                <th>Account Holder Name</th>
                                <th>Account No</th>
                                <th>IFSC Code</th>
                                <th>SWIFT Code</th>
                                <th>Created Date</th>
                                <?php if (permission_access("Setting", "edit_bank_account")) { ?>
                                <th>Action</th>
                                <?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            if (!empty($list) && is_array($list)) {
                                foreach ($list as $data) { ?>
                                    <tr>
                                       <?php if (permission_access("Setting", "delete_bank_account")) { ?>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php } ?>
                                        <td>
                                            <?php echo ucfirst($data['bank_name']); ?></a>
                                        </td>
                                        <td><?php echo ucfirst($data['branch_name']); ?></td>
                                        <td><?php echo ucfirst($data['account_holder_name']); ?></td>
                                        <td> <?php echo $data['account_no']; ?></td>
                                        <td><?php echo $data['ifsc_code']; ?></td>
                                        <td><?php echo $data['swift_code']; ?></td>
                                        <td><?php echo date_created_format($data['created']); ?></td>
                                        <?php if (permission_access("Setting", "edit_bank_account")) { ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true"
                                               data-controller='bankaccounts' data-id="<?php echo dev_encode($data['id']);  ?>"
                                               data-href="<?php echo site_url('/bankaccounts/edit-bank-template/') . dev_encode($data['id']);  ?>"><i
                                                        class="fa-solid fa-edit "></i></a>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text-center'><b>No Bank Account Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </form>
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
