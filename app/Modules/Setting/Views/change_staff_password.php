<div class="modal-header">
    <h5>Change Passwords</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('setting/update-staff-change-password/'.dev_encode($edit_staff_id)); ?>" method="post" tts-form="true" name="formleadassign">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>New Passwords </label>
                    <input class="form-control" type="password" name="change_password" placeholder="Enter new password">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Confirm Passwords </label>
                    <input class="form-control" type="password" name="confirm_password" placeholder="Enter Confirm password">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="badge badge-md badge-primary" type="submit">Save</button>
    </div>
</form>