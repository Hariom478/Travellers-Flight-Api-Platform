<div class="modal-header">
   <h1 class="modal-title fs-5"><?php echo 'Edit Blog Category'; ?></h1>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


<div class="vewmodelhed">
<form action="<?php echo site_url('blog/edit-blogs-category/' . dev_encode($category_id)); ?>" method="post"
           tts-form="true" name="add_blogs" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label> Category name *</label>
                        <input class="form-control" type="text" name="category_name"
                               placeholder="Blog Category name" value="<?php echo $category_details['category_name']?>"  onblur='tts_slug_url(this.value,"category-slug")' >
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label>Category Slug *</label>
                        <input class="form-control" type="text" name="category_slug" value="<?php echo $category_details['category_slug']?>" placeholder="Category Slug" id="category-slug">
                    </div>
                </div>


                <div class="col-6">
                    <div class="form-group">
                        <label> Category Image * </label>
                        <input class="form-control" type="file" name="category_img" placeholder="Blog Category Image">
                    </div>
                </div>


                <div class="col-6">
                    <div class="form-group">
                        <label>Category Status *</label>
                        <select class="form-select" name="status" placeholder="Blog Status">
                            <option value="active" <?php if ($category_details['status'] == "active") {
                                echo "selected";
                            } ?>>Active
                            </option>
                            <option value="inactive" <?php if ($category_details['status'] == "inactive") {
                                echo "selected";
                            } ?>> Inactive
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label>Description *</label>
                        <textarea class="form-control tts-editornote" type="textarea" name="description" rows="3" placeholder=" Description"><?php echo $category_details['description']?></textarea>
                    </div>
                </div>


                <div class="col-6">
                    <div class="form-group">
                        <label>Meta Robots *</label>
                        <select class="form-select" name="meta_robots" placeholder="Meta Robots">
                            <option value="INDEX, FOLLOW" <?php if ($category_details['status'] == "INDEX, FOLLOW") {
                                echo "selected";
                            } ?>>INDEX, FOLLOW
                            </option>
                            <option value="NOINDEX, FOLLOW" <?php if ($category_details['status'] == "NOINDEX, FOLLOW") {
                                echo "selected";
                            } ?>>NOINDEX, FOLLOW
                            </option>
                            <option value="INDEX, NOFOLLOW" <?php if ($category_details['status'] == "INDEX, NOFOLLOW") {
                                echo "selected";
                            } ?>>INDEX, NOFOLLOW
                            </option>
                            <option value="NOINDEX, NOFOLLOW" <?php if ($category_details['status'] == "NOINDEX, NOFOLLOW") {
                                echo "selected";
                            } ?>>NOINDEX, NOFOLLOW
                            </option>
                        </select>
                    </div>
                </div>


                <div class="col-6">
                    <div class="form-group">
                        <label> Meta Title * </label>
                        <input class="form-control" type="text" name="meta_title" placeholder="Meta Title"
                               value="<?php echo $category_details['meta_title']; ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label> Meta Keyword* </label>
                        <textarea class="form-control" type="file" name="meta_keyword" placeholder="Meta Keyword"
                                  rows="2"><?php echo $category_details['meta_keyword']; ?></textarea>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label> Meta Description* </label>
                        <textarea class="form-control" type="file" name="meta_description"
                                  placeholder="Meta Description" rows="2" ><?php echo $category_details['meta_description']; ?></textarea>
                    </div>
                </div>


            </div>


        </div> 

        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>








 