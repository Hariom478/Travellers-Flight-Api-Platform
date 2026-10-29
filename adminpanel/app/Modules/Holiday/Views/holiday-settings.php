<?php if (permission_access("Holiday", "holiday_setting")) { ?>
<div class="content">
    <div class="page-content">
        <div class="card">
         <?php echo view('\Modules\Holiday\Views\menu-bar'); ?>
            <div class="card-body">
                <div class="col-md-12">
                    <form action="<?= base_url('holiday/holiday-settings') ?>" tts-form="true" method="post" name="holiday_settings">
                        <div class="row">
                            <?php 
                            foreach ($datalist as $key => $value) : ?>
                                <div class="col-md-12">
                                    <div class="form-group form-mb-20">
                                        <label><?= $value ?> *</label>
                                        <textarea class="form-control tts-editornote" type="textarea" name="<?=$key?>" rows="3" placeholder="<?= $value ?>" style="display: none;"><?php if(isset($getData[$key])) { echo $getData[$key]; } ?></textarea>
                                        <input type="hidden" name="key_source[]" value="<?= $key ?>">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary" type="submit">save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>