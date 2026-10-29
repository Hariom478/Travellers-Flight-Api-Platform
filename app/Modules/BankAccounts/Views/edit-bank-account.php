<div class="modal-header">
    <span class="close" onclick="ttsclosemodel('view_bankaccounts')">&times;</span>
    <h5>Edit <?php echo 'Bank Account';?></h5>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('bankaccounts/edit-account/'. dev_encode($id)); ?>" method="post" tts-form="true" name="edit_bankaccounts">

        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Bank Name *  </label>
                        <input class="form-control" type="text" name="bank_name" value="<?php echo $details['bank_name']?>" placeholder="Bank Name">
                    </div>
                </div>
                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Branch Name * </label>
                        <input class="form-control" type="text" name="branch_name" value="<?php echo $details['branch_name']?>" placeholder="Branch Name">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Account Holder Name * </label>
                        <input class="form-control" type="text"  name="account_holder_name" value="<?php echo $details['account_holder_name']?>" placeholder="Account Holder Name">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Account No * </label>
                        <input class="form-control" type="text"  name="account_no" value="<?php echo $details['account_no']?>" placeholder="Account No">
                    </div>
                </div>
                <div class="tts-col-6">
                    <div class="form-group">
                        <label>IFSC Code * </label>
                        <input class="form-control" type="text"  name="ifsc_code" value="<?php echo $details['ifsc_code']?>" placeholder="IFSC Code">
                    </div>
                </div>
                <div class="tts-col-6">
                    <div class="form-group">
                        <label>SWIFT Code </label>
                        <input class="form-control" type="text"  name="swift_code" value="<?php echo $details['swift_code']?>" placeholder="SWIFT Code">
                    </div>
                </div>


            </div>
        </div>
        <div class="modal-footer">
            <div class="tts_row">
                <div class="tts-col-12">
                    <input class="badge badge-md badge-primary" type="submit" value="Save">
                </div>
            </div>
        </div>
    </form>
</div>