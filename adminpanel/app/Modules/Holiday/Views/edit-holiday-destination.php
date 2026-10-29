
 <div class="modal-header">
        <h5 class="modal-title">Edit Holiday Destination</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <form action="<?php echo site_url('holiday/edit-destination/').dev_encode($id); ?>" method="post" tts-form="true" name="edit_holiday_theme"
          enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Destination Name *</label>
                        <input class="form-control" type="text" name="destination_name" value="<?php echo $details['destination_name']?>" placeholder="Destination Name" onblur='tts_slug_url(this.value,"holiday-destination-slug")'>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Destination Slug *</label>
                        <input class="form-control" type="text" name="destination_slug" value="<?php echo $details['destination_slug']?>" placeholder="Destination Slug" id="holiday-destination-slug">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Starting Price </label>
                        <input class="form-control" type="text" name="starting_price" value="<?php echo $details['starting_price']?>" placeholder="Starting Price">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Image * </label>
                        <input class="form-control" type="file" name="destination_image" placeholder="Image">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Status *</label>
                        <select class="form-select" name="status" placeholder="Status">
                            <option value="active" <?php if ($details['status'] == "active") {  echo "selected";  } ?>>Active
                            </option>
                            <option value="inactive" <?php if ($details['status'] == "inactive") {
                                echo "selected";
                            } ?>> Inactive
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group form-mb-20">
                    <label>Destination Type *</label>
                    <select class="form-select" name="destination_type" placeholder="Destination"> 
                        <option value="domestic" <?php if ($details['destination_type'] == "domestic") {  echo "selected";  } ?>> Domestic</option>
                        <option value="international" <?php if ($details['destination_type'] == "international") {  echo "selected";  } ?>>International</option>
                    </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-mb-20">
                        <label>Country / Continents *</label>
                        <select class="form-select" name="destination_country" placeholder="Status">
                            <option value="" selected>Select</option>
                            <option value="africa" <?php if ($details['destination_country'] == "africa") {  echo "selected";  } ?>>Africa</option>
                            <option value="asia" <?php if ($details['destination_country'] == "asia") {  echo "selected";  } ?>> Asia</option>
                            <option value="europe" <?php if ($details['destination_country'] == "europe") {  echo "selected";  } ?>> Europe</option>
                            <option value="middle east" <?php if ($details['destination_country'] == "middle east") {  echo "selected";  } ?>> Middle East</option>
                            <option value="america" <?php if ($details['destination_country'] == "america") {  echo "selected";  } ?>> America</option>
                            <option value="island" <?php if ($details['destination_country'] == "island") {  echo "selected";  } ?>> Island</option>
                            <option value="india" <?php if ($details['destination_country'] == "india") {  echo "selected";  } ?>> India</option>
                            <option value="pacific" <?php if ($details['destination_country'] == "pacific") {  echo "selected";  } ?>> Pacific</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label class="mt20">
                            <input type="checkbox" name="show_on_home" value="1" class="Lead" <?php  if($details['show_on_home'] ==1){ echo 'checked';}?>>Show On Home
                        </label>
                    </div>
                </div>

                
                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label>Description </label>
                        <textarea class="form-control tts-editornote" type="textarea" name="description" rows="3" placeholder="Description"><?php echo $details['description']?></textarea>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                    <label>Meta Robots *</label>
                    <select class="form-select" name="meta_robots" placeholder="Meta Robots">
                        <option value="INDEX, FOLLOW"  <?php if ($details['meta_robots'] == "INDEX, FOLLOW") {  echo "selected";  } ?> >INDEX, FOLLOW</option>
                        <option value="NOINDEX, FOLLOW"  <?php if ($details['meta_robots'] == "NOINDEX, FOLLOW") {  echo "selected";  } ?> >NOINDEX, FOLLOW</option>
                        <option value="INDEX, NOFOLLOW"  <?php if ($details['meta_robots'] == "INDEX, NOFOLLOW") {  echo "selected";  } ?> >INDEX, NOFOLLOW</option>
                        <option value="NOINDEX, NOFOLLOW"  <?php if ($details['meta_robots'] == "NOINDEX, NOFOLLOW") {  echo "selected";  } ?> >NOINDEX, NOFOLLOW</option>
                    </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                    <label> Meta Title * </label>
                    <input class="form-control" type="text" value="<?php echo $details['meta_title']?>" name="meta_title" placeholder="Meta Title">
                    </div>
                </div>   
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label> Meta Keyword* </label>
                    <textarea class="form-control" type="text" name="meta_keyword" placeholder="Meta Keyword" rows="2"><?php echo $details['meta_keyword']?></textarea>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label> Meta Description* </label>
                    <textarea class="form-control" type="text" name="meta_description" placeholder="Meta Description" rows="2"><?php echo $details['meta_description']?></textarea>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
    <script>
   $(document).ready(function() {
       setTimeout(() => {
           $('.note-editable').height(200);
       }, 50);
   });
</script>

