<?php if ($edit_detail) { 
    ?>
<div class="modal-header">
    <span class="close" onclick="ttsclosemodel('view_lead')">&times;</span>
    <h5>Edit CRM Option</h5>
</div>
<form name="edit_user" action="<?php echo site_url('setting/update_common_data'); ?>/<?php echo dev_encode($common_option_id); ?>" tts-form="true" enctype="multipart/form-data" method="Post">
    <div class="modal-body m0" style="padding-bottom: 15%;">
    <div id="lead_details" class=" current p0">
        <div class="tts_row">
             
        <div class="tts-col-4 pr0">
                        <div class="form-group">
                            <label> Key Option</label>
                            <select class="form-control" name="key_source">
                                <option> Select </option>
                                <option value="lead_source" <?php if($edit_detail->key_source=="lead_source"){  echo "selected"; } ?>> Lead Source </option>
                                <option value="lead_status" <?php if($edit_detail->key_source=="lead_status"){  echo "selected"; } ?>> Lead Status </option>
                                <option value="lead_priorty" <?php if($edit_detail->key_source=="lead_priorty"){  echo "selected"; } ?>> Lead Priority </option>
                            </select>
                        </div>
                    </div>
                    <div class="tts-col-6">
                        <div class="form-group">
                            <label>Option Value </label>
                            <input class="form-control" type="text" value="<?php echo $edit_detail->value; ?>" name="value" placeholder="value">
                        </div>
                    </div>
           
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="tts_row">
        <div class="tts-col-12">
            <input class="badge badge-md badge-primary" type="submit" value="Update">
        </div>
    </div>
</div>

                            </form>
<?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";
}?>