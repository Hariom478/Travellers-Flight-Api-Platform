<div class="modal-header">
    <h5 class="modal-title">Manage Credit LImit</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

  <form action="<?php echo site_url('agent/virtual-creditlimit/' . dev_encode($details['agent_id'])); ?>" method="post" tts-form="true" name="add_topup" enctype="multipart/form-data">  
<div class="modal-body">
        <div class="row m0">
            <div class="col-md-4">
                <div class="vi_mod_dsc">
                    <span>Name</span>
                    <span class="primary"> <b><?php echo $details['first_name'] . " ". $details['last_name'] ; ?></b> </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="vi_mod_dsc">
                    <span>Company Name</span>
                    <span class="primary"> <b><?php echo $details['company_name'] ?> </b> </span>
                </div>
            </div>
           
            <div class="col-md-4">
                <div class="vi_mod_dsc">
                    <span>Credit Balance</span>
                    <span class="primary">
                        <b>
                            <?php
                            if(isset( $details['balance'])) {
                                echo custom_money_format($details['balance']);
                            } else {
                                echo "N/A";
                            }
                            ?> 
                        </b>
                    </span>
                </div>
            </div>
        </div>
    
     <hr>
        
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Credit Limit*</label>
                        <input class="form-control" type="text" value="<?=(isset($details['balance']))?$details['balance']:''?>" name="credit_limit" placeholder="Credit Limit">
                    </div>
                </div>
                 <?php
                   $expire = "";
                   if(isset($details['credit_expire']) && $details['credit_expire'] == 'Yes'){
                     $expire='<span class="text-danger">(<b>Credit Limit Expired</b>)</span>';
                   }
                 ?>
                <div class="col-md-4">
                    <div class="form-group">
                    <label>Credit Expire*</label>
                    <select name="credit_expire" class="form-select">
                                    <option value="No" <?php echo   isset($details['credit_expire']) && $details['credit_expire'] == 'No'?"selected":"";?>>No</option>
                                    <option value="Yes" <?php echo   isset($details['credit_expire']) && $details['credit_expire'] == 'Yes'?"selected":"";?>>Yes</option>
                                </select>  
                            </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Credit Expire Date</label> <?=$expire;?>
                        <input class="form-control" type="text" harish-upload-import-from-date="true" name="credit_expire_date" value="<?=(isset($details['credit_expire_date']))?$details['credit_expire_date']:''?>" placeholder="Credit Expire Date">
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label>Remark* </label>
                        <textarea class="form-control" type="file" name="remark" placeholder="Remark" rows="2" spellcheck="false"></textarea>
                    </div>
                </div>
            </div>
    </div> 
    <div class="modal-footer">  
            <button type="submit" class="badge badge-md badge-primary badge_search">Set Credit Limit </i></button>
        
     </div>



</form>    