<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <?php if (permission_access("Agent", "add_agent_class")) { ?>
    <h5>Add Agent Class</h5>
    <?php }else{?>
        <h5>Agent Class List</h5>
    <?php }?>
</div>
<div class="vewmodelhed">
    <?php if (permission_access("Agent", "add_agent_class")) { ?>
    <form action="<?php echo site_url('agent/add-agent-class'); ?>" method="post" tts-form="true" name="add_class"
          enctype="multipart/form-data">

        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Agent Class</label>
                        <input class="form-control" type="text" name="class_name" placeholder="Agent Class">
                    </div>
                </div>
                <div class="tts-col-6">
                    <div class="form-group mt_20">
                        <label>&nbsp;</label>
                        <input class="badge badge-md badge-primary" type="submit" value="Save">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php }?>
    <div class="content ">
        <div class="page-content">

            <h6> Agent Class List</h6>

            <div class="page-content-area">

                <div class="card-body" style="padding: unset;">

                    <div class="responcive_table table_box_shadow">
                        <table class="table-strip divName">
                            <thead>
                            <tr>
                                <th>Agent Class</th>
                                <th>Created</th>
                                <?php if (permission_access("Agent", "delete_agent_class")) { ?>
                                <th>Action</th>
                                <?php }?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            if (!empty($list) && is_array($list)) {
                                foreach ($list as $data) {

                                    ?>

                                    <tr>
                                        <td>
                                            <?php echo ucfirst($data['class_name']); ?>
                                        </td>
                                        <td>
                                            <?php echo date_created_format($data['created_date']); ?>
                                        </td>
                                        <?php if (permission_access("Agent", "delete_agent_class")) { ?>
                                        <td>
                                            <a href="<?php echo site_url('agent/remove-agent-class/' . dev_encode($data['id'])); ?>"
                                               onclick="return confirm('Do you want to delete record !')"><i
                                                        class="tts-icon delete "></i></a>
                                        </td>
                                        <?php }?>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text_center'><b>No Agent Class Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
