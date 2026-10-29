<div class="modal-header">
   <?php if (permission_access("Agent", "add_agent_class")) { ?>
    <h5 class="modal-title">Add Agent Class</h5>
    <?php } else{?>
        <h5 class="modal-title">Agent Class List</h5>
    <?php } ?>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
         <?php if (permission_access("Agent", "add_agent_class")) { ?>
            <form action="<?php echo site_url('agent/add-agent-class'); ?>" method="post" tts-form="true" name="add_class">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Agent Class</label>
                                <input class="form-control" type="text" name="class_name" placeholder="Agent Class">
                            </div>
                        </div>
                        <div class="col-md-6 align-self-center">
                             <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </div>
            </form>
            <?php } ?>
        
            <h6> Agent Class List</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-active">
                    <tr>
                        <th>Agent Class</th>
                        <th>Created</th>
                        <th>Modified</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($list) && is_array($list)) {
                        foreach ($list as $key=>$data) { ?>
                            <tr>
                                <td>
                                    <form action="<?php echo site_url('agent/edit-agent-class/'); ?><?php echo dev_encode($data['id']); ?>" method="post" tts-form="true" name="update_class_<?php echo $key; ?>">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <input type="text" class="form-control" name="class_name" value="<?php echo $data['class_name']; ?>">
                                            </div>
                                            <?php if(permission_access("Agent", "edit_agent_class")) { ?>
                                            <div class="col-4">
                                                    <button class="btn btn-primary" type="submit">Update</button>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <?php echo date_created_format($data['created_date']); ?>
                                </td>
                                <td>
                                    <?php
                                    if (isset($data['modified'])) {
                                        echo date_created_format($data['modified']);
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                
                            </tr>
                            
                        <?php }  } 
                        else {
                        echo "<tr> <td colspan='11' class='text-center'><b>No Agent Class Found</b></td></tr>";
                            } ?>
                    </tbody>
                </table>
           
</div>

    
