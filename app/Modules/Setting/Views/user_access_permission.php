<div class="modal-header">
    <h5 lass="modal-title">User Access Lavel </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form name="user_permission_data" action="<?php echo site_url('setting/access_level_update'); ?>/<?php echo dev_encode($user_id); ?>"  tts-form="true"  method="Post">
    <div class="modal-body m0" style="padding-bottom: 15%;">
        <div id="lead_details" class=" current p0">
            <div class="row">
    <?php
 
    $user_access_list_data =json_decode($user_access_list->access_permission,true); 
   
    $permission_data=json_decode($user_access,true);
   

        if($permission_data) {
        foreach($permission_data as $keyval=>$access_module_data) {   

             $module_name = $keyval."_Module";
            if(isset($permission_data[$keyval][$module_name]) && $permission_data[$keyval][$module_name]=="active"){  ?>

                <div class="col-md-12 ">
                    <h6 class="view_head"> 
                    <label>  
                    <input type="hidden" name="<?php echo $keyval; ?>[<?php echo $module_name; ?>]" value="inactive"> 
                    <input name="<?php echo $keyval; ?>[<?php echo $module_name; ?>]" <?php  if(isset($user_access_list_data[$keyval][$module_name]) && $user_access_list_data[$keyval][$module_name]=="active") { echo "checked"; } ?> value="active" type="checkbox" data-main-module>
                    <?php  echo  ucwords(str_replace("_"," ",$keyval)); ?>  Module 
                   </label>                    
                        <div class="pull-right">
                            <input class="select-module" data-module="<?php echo $keyval; ?>" type="checkbox" id="flexCheck<?php echo $keyval; ?>">
                            <label for="flexCheck<?php echo $keyval; ?>">Select All</label>
                       </div>
                   </h6>
                </div>
                <?php foreach($access_module_data  as $key_value=>$status_data){ 
                
                if($key_value!=$keyval.'_Module') {
                ?>    
                <div class="col-md-4">
                    <div class="form-group">
                        <label>
                            <input type="hidden" name="<?php echo $keyval; ?>[<?php  echo $key_value; ?>]" value="inactive"> 
                            <input type="checkbox" name="<?php echo $keyval; ?>[<?php  echo $key_value; ?>]" <?php   if(isset($user_access_list_data[$keyval][$module_name])=="active" && $user_access_list_data[$keyval][$key_value] =="active"){ echo "checked"; } ?> value="active" class="<?php echo $keyval; ?>" data-permission-input> <?php  echo  ucwords(str_replace("_"," ",$key_value)); ?> 
                        </label>
                    </div>
                </div>

                <?php } } }  }  }  ?>
            </div>

            
        </div>
    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-md-12">
                <input class="btn btn-primary" type="submit" value="Update">
            </div>
        </div>
    </div>

</form>

<script>
$(function() {
    setTimeout(() => {
        var modules=[];
        $("[data-permission-input]").each(function(index, value) {
            modules.push($(value).attr('class'));
        });
        var uniquemodules = modules.filter((v, i, a) => a.indexOf(v) === i);
        uniquemodules.forEach(function(item) {
            var module =item;
            var check = ($(":input."+module).filter(":checked").length == $(":input."+module).length);
            $("#flexCheck"+module).prop('checked',check);
        });
        $("[data-main-module]").each(function(index, value) {
            var module=$(this).attr('name').split("[")[0];           
            if($(value).prop("checked")===false)
            { 
                $("."+module).prop('checked',false);
                $("#flexCheck"+module).prop('checked',false);
                $("."+module).attr("disabled", true);
                $("#flexCheck"+module).attr("disabled", true);
            } 
        });
    }, 100);
});
</script>