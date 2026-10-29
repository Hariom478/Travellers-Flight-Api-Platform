<?php $trash_uri = "holiday/remove-holiday-gallery"; ?>
<form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formlist">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-active">
            <tr>
                <th><label><input type="checkbox" name="check_all"
                                  id="selectall"/></label>
                </th>
                <th>Image</th>
                <th>Title</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody>
            <?php

            if (!empty($list) && is_array($list)) {
                foreach ($list as $data) {
                    if ($data['status'] == 'active') {
                        $status_class = 'active-status';
                    } else {
                        $status_class = 'inactive-status';
                    }
                    ?>
                    <tr>
                        <td>
                            <label><input type="checkbox" name="checklist[]"
                                          class="checkbox"
                                          value="<?php echo $data['id']; ?>"/></label>
                        </td>
                        <td>
                            <img src="<?php echo root_url . "uploads/holiday/thumbnail/" . $data['images']; ?>"
                                 alt="<?php echo $data['image_title']; ?>"
                                 class="tts-blog-image">
                        </td>
                        <td>
                            <?php echo ucfirst($data['image_title']); ?>
                        </td>
                        <td>
                            <div class="<?php echo $status_class ?>">
                                <?php echo ucfirst($data['status']); ?>
                            </div>
                        </td>

                        <td>
                            <?php echo date_created_format($data['created']); ?>
                        </td>
                    </tr>
                <?php }
            } else {
                echo "<tr> <td colspan='11' class='text-center'><b>No Holiday Gallery Found</b></td></tr>";
            } ?>
            </tbody>
        </table>
    </div>
</form>