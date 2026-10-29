<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Edit <?php echo 'Markup '; ?></h5>
</div>

<div class="vewmodelhed">

    <form action="<?php echo site_url('agent/edit-bus-markup/' . dev_encode($id)); ?>" method="post" tts-form="true"
          name="add_flight_markup">

        <div class="modal-body">
            <div class="tts_row">

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Markup Type *</label>
                        <select class="form-control" name="markup_type" placeholder="Markup Type">
                            <option value="fixed" <?php if ($details['markup_type'] == "fixed") {
                                echo "selected";
                            } ?>>Fixed</option>
                            <option value="percent" <?php if ($details['markup_type'] == "percent") {
                                echo "selected";
                            } ?> >Percent</option>
                        </select>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Min Range *</label>
                        <input class="form-control" type="text" name="min_range" value="<?php echo $details['min_range']?>" placeholder="Min Range">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Max Range *</label>
                        <input class="form-control" type="text" name="max_range" value="<?php echo $details['max_range']?>" placeholder="Max Range">
                    </div>
                </div>


                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Value *</label>
                        <input class="form-control" type="text" value="<?php echo $details['value']?>" name="value" placeholder="Value">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Agent Class *</label>
                        <select class="form-control" name="agent_class" placeholder="Agent Class">
                            <option value="" >Select agent class</option>
                            <?php if($agent_class){ foreach ($agent_class as $class){?>
                                <option value="<?php echo $class['id']?>" <?php if ($details['agent_class'] ==  $class['id']) {
                                    echo "selected";
                                } ?> ><?php echo $class['class_name']?></option>
                            <?php }}?>
                        </select>
                    </div>
                </div>


                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-control" name="status" placeholder="Status">
                            <option value="active" <?php if ($details['status'] == "active") {
                                echo "selected";
                            } ?>>Active
                            </option>
                            <option value="inactive" <?php if ($details['status'] == "inactive") {
                                echo "selected";
                            } ?>> Inactive
                            </option>
                        </select>
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

