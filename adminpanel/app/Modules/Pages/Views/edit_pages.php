<div class="modal-header">
    <h5 class="modal-title">Edit Page</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('pages/edit-pages/' . dev_encode($id)); ?>" method="post" tts-form="true" name="edit_pages">

        <div class="modal-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label class="form-label">Title *</label>
                        <input class="form-control" type="text" name="title" placeholder="Title" value="<?php echo $details['title']; ?>"
                               onblur='tts_slug_url(this.value,"page-slug")'>
                    </div>
                </div>

                <div class="col-md-6" >
                    <div class="form-group form-mb-20">
                        <label class="form-label">Page Slug *</label>
                        <input class="form-control" type="text" name="slug_url" placeholder="Page Slug" id="page-slug"  value="<?php echo $details['slug_url']; ?>">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label class="form-label">Custom Url *</label>
                        <input class="form-control" type="text" name="custom_url" placeholder="Custom Url" value="<?php echo $details['custom_url']; ?>">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label class="form-label">Content *</label>
                        <textarea class="form-control tts-editornote" type="textarea" name="content" rows="3" placeholder="Content"><?php echo $details['content']; ?></textarea>
                    </div>
                </div>

                <div class="col-md-6 dnone">
                    <div class="form-group form-mb-20">
                        <label class="form-label"> Status *</label>
                        <select class="form-select" name="status" placeholder="Blog Status">
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

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label class="form-label">Meta Robots *</label>
                        <select class="form-select" name="meta_robots" placeholder="Meta Robots">
                            <option value="INDEX, FOLLOW" <?php if ($details['status'] == "INDEX, FOLLOW") {
                                echo "selected";
                            } ?>>INDEX, FOLLOW
                            </option>
                            <option value="NOINDEX, FOLLOW" <?php if ($details['status'] == "NOINDEX, FOLLOW") {
                                echo "selected";
                            } ?>>NOINDEX, FOLLOW
                            </option>
                            <option value="INDEX, NOFOLLOW" <?php if ($details['status'] == "INDEX, NOFOLLOW") {
                                echo "selected";
                            } ?>>INDEX, NOFOLLOW
                            </option>
                            <option value="NOINDEX, NOFOLLOW" <?php if ($details['status'] == "NOINDEX, NOFOLLOW") {
                                echo "selected";
                            } ?>>NOINDEX, NOFOLLOW
                            </option>
                        </select>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label class="form-label"> Meta Title * </label>
                        <input class="form-control" type="text" name="meta_title" placeholder="Meta Title"
                               value="<?php echo $details['meta_title']; ?>">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label class="form-label"> Meta Keyword* </label>
                        <textarea class="form-control" type="file" name="meta_keyword" placeholder="Meta Keyword"
                                  rows="2"><?php echo $details['meta_keyword']; ?></textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label class="form-label"> Meta Description* </label>
                        <textarea class="form-control" type="file" name="meta_description"
                                  placeholder="Meta Description"
                                  rows="2"><?php echo $details['meta_description']; ?></textarea>
                    </div>
                </div>


            </div>


        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>
