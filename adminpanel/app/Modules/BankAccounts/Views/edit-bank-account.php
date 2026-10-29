<div class="modal-header">
    <h1 class="modal-title fs-5">Edit <?php echo 'Bank Account';?></h1>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


    <form action="<?php echo site_url('bankaccounts/edit-account/'. dev_encode($id)); ?>" method="post" tts-form="true" name="edit_bankaccounts">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Bank Name *  </label>
                        <input class="form-control" type="text" name="bank_name" value="<?php echo $details['bank_name']?>" placeholder="Bank Name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Branch Name * </label>
                        <input class="form-control" type="text" name="branch_name" value="<?php echo $details['branch_name']?>" placeholder="Branch Name">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Account Holder Name * </label>
                        <input class="form-control" type="text"  name="account_holder_name" value="<?php echo $details['account_holder_name']?>" placeholder="Account Holder Name">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Account No * </label>
                        <input class="form-control" type="text"  name="account_no" value="<?php echo $details['account_no']?>" placeholder="Account No">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">IFSC Code * </label>
                        <input class="form-control" type="text"  name="ifsc_code" value="<?php echo $details['ifsc_code']?>" placeholder="IFSC Code">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">SWIFT Code </label>
                        <input class="form-control" type="text"  name="swift_code" value="<?php echo $details['swift_code']?>" placeholder="SWIFT Code">
                    </div>
                </div>


            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
