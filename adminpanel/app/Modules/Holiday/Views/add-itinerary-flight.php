<div class="row" id="package-itinerary-flight-html_<?php echo $data_counter;?>_<?=$itinery_counter?>">
    <div class="col-md-12  mb_10">
        <div class="sale_bar">
            <div class="row align-items-center">
                <div class="col-md-4 ">
                    <h5 class="m0">Add Flight</h5>
                </div>
                <div class="col-md-8 text-end">
                    <button class="badge badge-wt"
                            onclick="remove_more_items_services(this,'package-itinerary-flight-html_<?=$data_counter.'_'.$itinery_counter?>')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group form-mb-20">
            <label>Origin *</label>
            <input class="form-control" type="text" tts-get-holiday-airport="true" name="flight[<?php echo $data_counter?>][<?php echo $itinery_counter ?>][origin]"
                   placeholder="Origin">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group form-mb-20">
            <label>Destination *</label>
            <input class="form-control" type="text" tts-get-holiday-airport="true" name="flight[<?php echo $data_counter?>][<?php echo $itinery_counter ?>][destination]"
                   placeholder="Destination">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group form-mb-20">
            <label>Airline *</label>
            <input class="form-control" tts-get-airline="true" type="text" name="flight[<?php echo $data_counter?>][<?php echo $itinery_counter ?>][airline]"
                   placeholder="Airline code">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group form-mb-20">
            <label>Departure Time *</label>
            <input class="form-control" type="text" name="flight[<?php echo $data_counter?>][<?php echo $itinery_counter ?>][depart_time]"
                   placeholder="Departure Time">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group form-mb-20">
            <label>Arrival Time *</label>
            <input class="form-control" type="text" name="flight[<?php echo $data_counter?>][<?php echo $itinery_counter ?>][arrival_time]"
                   placeholder="Arrival Time">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group form-mb-20">
            <label>Remark </label>
            <textarea class="form-control " type="textarea"
                      name="flight[<?php echo $data_counter?>][<?php echo $itinery_counter ?>][remark]"
                      rows="3" placeholder="Remark"></textarea>
        </div>
    </div>
</div>
