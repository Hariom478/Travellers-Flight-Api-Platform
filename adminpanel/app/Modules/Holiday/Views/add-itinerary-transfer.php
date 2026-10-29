<div class="row" id="package-itinerary-transfer-html_<?php echo $data_counter;?>_<?=$itinery_counter?>">
    <div class="col-md-12  mb_10">
        <div class=" sale_bar">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-lg-0">
                    <h5 class="m0">Add Transfer</h5>
                </div>
                <div class="col-md-8 text-end">
                    <button class="badge badge-wt" onclick="remove_more_items_services(this,'package-itinerary-transfer-html_<?=$data_counter.'_'.$itinery_counter?>')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group form-mb-20">
            <label>Transfer Type *</label>
            <input class="form-control" type="text" name="transfer[<?php echo $data_counter ?>][<?php echo $itinery_counter ?>][transfer_type]" placeholder="Transfer Type">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group form-mb-20">
            <label>Pickup Form/place *</label>
            <input class="form-control" type="text" name="transfer[<?php echo $data_counter ?>][<?php echo $itinery_counter ?>][pickup]" placeholder="Pickup Form/place">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group form-mb-20">
            <label>Drop to/place *</label>
            <input class="form-control" type="text" name="transfer[<?php echo $data_counter ?>][<?php echo $itinery_counter ?>][drop]" placeholder="Drop to/place">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group form-mb-20">
            <label>Pickup time *</label>
            <input class="form-control" type="text" name="transfer[<?php echo $data_counter ?>][<?php echo $itinery_counter ?>][pickuptime]" placeholder="Pickup time">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group form-mb-20">
            <label>Remark</label>
            <textarea class="form-control " type="textarea" name="transfer[<?php echo $data_counter ?>][<?php echo $itinery_counter ?>][remark]" rows="3" placeholder="Remark"></textarea>
        </div>
    </div>
</div>