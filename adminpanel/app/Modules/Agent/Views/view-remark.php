<?php
if ($data) { ?>

    <div class="modal-header">
        <h5 class="modal-title">
            <? echo 'Remark'; ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
    </div>

    <div class="modal-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-active">
                    <tr>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php
                            if ($data['action_type'] == 'booking') {
                                if ($data['service_log']) {
                                    $service_log = json_decode($data['service_log'], true);

                                    echo "Booking Info : " . service_log($data['service'], $data['action_type'], $service_log);
                                } else {
                                    echo "Booking Info : " . ucfirst($data['service']) . ' ' . ucfirst($data['action_type']);
                                }
                            } else {
                                echo "Action Type: " . ucfirst($data['action_type']);
                            }
                            ?>
                            </b><br />
                            <?php if ($data['web_partner_staff_name'] != "") { ?>
                                <b>
                                    <?php echo "Update By: " . ucfirst($data['web_partner_staff_name']); ?>
                                </b></b><br />
                            <?php } ?>
                            <?php echo $data['remark']; ?>
                            <br />
                            <?php echo "<b>Transaction Type  : </b>" . ucfirst($data['transaction_type']); ?>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
        <form
            action="<?php echo site_url('agent/account-update-log-remark/' . dev_encode($data['id'] . "-" . $data['wl_agent_id'])); ?>"
            method="post" tts-form="true" name="update_account_log_remark">

            <div class="row">


                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label>Remark* </label>
                        <textarea class="form-control" type="file" name="remark" placeholder="Remark" rows="3"
                            spellcheck="false"
                            value="<?php echo $data['remark']; ?>"><?php echo $data['remark']; ?></textarea>
                    </div>
                </div>

                <input type="hidden" name="action_type" value="<?php echo $data['action_type']; ?>">
                <?php $invoiceText = "Credit Note No"; ?>
                <?php if ($data['action_type'] == 'booking') {
                    $invoiceText = "Invoice No"; ?>
                <?php } ?>
                <?php if ($data['action_type'] == 'booking' || $data['action_type'] == 'refund') { ?>
                    <div class="col-md-4">
                        <div class="form-group form-mb-20">
                            <label>
                                <?php echo $invoiceText; ?>*
                            </label>
                            <input class="form-control" type="text" name="invoice_number"
                                placeholder="<?php echo $invoiceText; ?>" spellcheck="false"
                                value="<?php echo $data['invoice_number']; ?>">
                        </div>
                    </div>
                <?php } ?>
            </div>
          

            <? if (permission_access("Agent", "update_remark")): ?>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <? endif ?>

        </form>
    <?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";

} ?>