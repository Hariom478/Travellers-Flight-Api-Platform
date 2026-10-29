<div class="row" id="package-itinerary-hotel-html_<?php echo $data_counter;?>_<?=$itinery_counter?>">
    <div class="col-md-12 mb_10">
        <div class=" sale_bar">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <span>Add Hotel</span>
                </div>
                <div class="col-md-8 text-end">
                    <button class="badge badge-wt" onclick="remove_more_items_services(this,'package-itinerary-hotel-html_<?=$data_counter.'_'.$itinery_counter?>')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group form-mb-20">
                <label>Hotel Name *</label>
                <input class="form-control" type="text" name="hotel[<?php echo $data_counter ?>][<?php echo $itinery_counter ?>][hotel_name]" placeholder="Hotel Name">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-mb-20">
                <label>Hotel City *</label>
                <input class="form-control" type="text" name="hotel[<?php echo $data_counter ?>][<?php echo $itinery_counter ?>][hotel_city]" placeholder="Hotel City">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group form-mb-20">
                <label>Hotel Address *</label>
                <input class="form-control" type="text" name="hotel[<?php echo $data_counter ?>][<?php echo $itinery_counter ?>][hotel_address]" placeholder="Hotel Address">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group form-mb-20">
                <label>Remark </label>
                <textarea class="form-control " type="textarea" name="hotel[<?php echo $data_counter ?>][<?php echo $itinery_counter ?>][remark]" rows="3" placeholder="Enter Remark"></textarea>
            </div>
        </div>
    </div>
</div>