<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Virtual Debit Amount</h5>
</div>
<div class="vewmodelhed">
    <div class="vewmodelhed">

        <div class="tts_row m0">
            <div class="tts-col-3">
                <div class="vi_mod_dsc">
                    <span>Name</span>
                    <span class="primary"> <b><?php echo $details['title'] . ' ' . $details['first_name'] . ' ' . $details['last_name']; ?></b> </span>
                </div>
            </div>
            <div class="tts-col-4">
                <div class="vi_mod_dsc">
                    <span>Email </span>
                    <span class="primary"> <b><?php echo $details['email_id'] ?> </b> </span>
                </div>
            </div>
            <div class="tts-col-3">
                <div class="vi_mod_dsc">
                    <span>Mobile No</span>
                    <span class="primary"> <b><?php echo $details['mobile_no']; ?> </b> </span>
                </div>
            </div>
            <div class="tts-col-2">
                <div class="vi_mod_dsc">
                    <span>Available Balance</span>
                    <span class="primary">
                        <b>
                            <?php
                            if(isset( $details['balance'])) {
                                echo $details['balance'];
                            }
                            ?>
                        </b>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <form action="<?php echo site_url('customer/virtual-debit/' . dev_encode($details['id'])); ?>" method="post" tts-form="true" name="add_debit"
          enctype="multipart/form-data">

        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Debit Amount*</label>
                        <input class="form-control" type="text" name="debit" placeholder="Debit Amount">
                    </div>
                </div>

                <div class="tts-col-8">
                    <div class="form-group">
                        <label>Remark* </label>
                        <textarea class="form-control" type="file" name="remark" placeholder="Remark" rows="1" spellcheck="false"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="tts_row">
                <div class="tts-col-12">
                    <input class="badge badge-md badge-primary" type="submit" value="Update Cash Amount">
                </div>
            </div>
        </div>
    </form>
</div>  
    
