<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="m-0"> <?php echo $title . ' '; ?>List</h5>
                    </div>
                    <div class="col-md-8 text-end">
                        <?php if (permission_access("Setting", "add_convenience_fee") && strtolower($payment_gateway) != "default") { ?>
                            <button class="badge badge-wt" view-data-modal="true" data-controller='bankaccounts'
                                    data-href="<?php echo site_url('convenience-fee/add-convenience-template') ?>"><i
                                        class="fa-solid fa-add "></i> Add Convenience Fee
                            </button>
                        <?php  } ?>
                        <?php if (permission_access("Setting", "delete_convenience_fee")) { ?>
                            <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                    onclick="confirm_delete('formconveniencelist')"><i class="fa-solid fa-trash"></i>
                                Delete
                            </button>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="card"> 
            <div class="card-body"> 
                <div class="table-responsive">
                    <?php  $trash_uri = "convenience-fee/remove-convenience"; ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formconveniencelist">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>
                                <?php if (permission_access("Setting", "delete_convenience_fee")) { ?>
                                    <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                    </th>
                                <?php } ?>
                                <th>Convenience Fee For</th>
                                <th>Payment Gateway</th>
                                <th>Agent Class</th>
                                <th>Service</th>
                                <th>Debit Card</th>
                                <th>Credit Card</th>
                                <th>Net Banking</th>
                                <th>UPI</th>
                                <th>Mobile Wallet</th>
                                <th>Created Date</th>
                                <?php if (permission_access("Setting", "edit_convenience_fee")) { ?>
                                    <th>Action</th>
                                <?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            if (!empty($list) && is_array($list)) {

                                foreach ($list as $data) {  
                                    
                                    $class_id = explode(',', $data['agent_class_id']);
                                    $partner_class = implode(', ', array_map('ucfirst', array_intersect_key($agent_class_list, array_flip($class_id))));
                                
                                    ?>

                                    <tr>
                                        <?php if (permission_access("Setting", "delete_convenience_fee")) { ?>
                                            <td>
                                                <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                              value="<?php echo $data['id']; ?>"/></label>
                                            </td>
                                        <?php } ?>
                                        <td><?php echo ucfirst($data['convenience_fee_for']); ?></td>
                                        <td>
                                            <?php echo($data['payment_getway']); ?></a>
                                        </td>
                                        <td><?php echo($partner_class); ?></td>
                                        <td><?php echo str_replace("_", " ", $data['service']); ?></td>

                                        <td><?php echo ucfirst($data['debit_card_type']) . ' | ' . ($data['debit_card_value']); ?></td>
                                        <td><?php echo ucfirst($data['credit_card_type']) . ' | ' . ($data['credit_card_value']); ?></td>


                                        <td>
                                            <?php echo ucfirst($data['net_banking_type']) . ' | ' . $data['net_banking_value']; ?>
                                        </td>


                                        <td>
                                            <?php echo ucfirst($data['upi_type']) . ' | ' . $data['upi_value']; ?>
                                        </td>


                                        <td>
                                            <?php echo ucfirst($data['mobile_wallet_type']) . ' | ' . $data['mobile_wallet_value']; ?>
                                        </td>


                                        <td><?php echo date_created_format($data['created']); ?></td>

                                        <?php if (permission_access("Setting", "edit_convenience_fee")) { ?>
                                            <td>
                                                <a href="javascript:void(0);" view-data-modal="true"
                                                   data-controller='bankaccounts'
                                                   data-id="<?php echo dev_encode($data['id']); ?>"
                                                   data-href="<?php echo site_url('/convenience-fee/edit-convenience-template/') . dev_encode($data['id']); ?>"><i
                                                            class="fa-solid fa-edit "></i></a>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='15' class='text-center'><b>No Convenience Fee Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </form>


                </div>
                <div class="row pagiantion_row">
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