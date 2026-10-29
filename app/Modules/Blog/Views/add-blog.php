<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Add New Blog</h5>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('blog/add-blogs'); ?>" method="post" tts-form="true" name="add_blogs" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Blog Title *</label>
                        <input class="form-control" type="text" name="post_title" placeholder="Blog Title" onblur='tts_slug_url(this.value,"blog-slug")'>
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Blog Slug *</label>
                        <input class="form-control" type="text" name="post_slug" placeholder="Blog Slug" id  = "blog-slug">
                    </div>
                </div>




                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Blog Category *</label>
                        <select class="form-control select_search" name="category_id" placeholder="Category Name">
                            <option value  ="">Select Category </option>
                            <?php if ($blog_category_list) {
                                foreach ($blog_category_list as $blog_category_data) { ?>

                                    <option value="<?php echo $blog_category_data['id']; ?>"> <?php echo $blog_category_data['category_name']; ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label> Blog Image * </label>
                        <input class="form-control" type="file" name="post_images" placeholder="Post Image">
                    </div>
                </div>

                <div class="tts-col-12">
                    <div class="form-group">
                        <label> Blog Post  Description *</label>
                        <textarea class="form-control tts-editornote" type="textarea" name="post_desc" rows="3" placeholder="Blog Post Description"></textarea>
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Blog Status *</label>
                        <select class="form-control" name="status" placeholder="Blog Status">
                            <option value="active" selected>Active</option>
                            <option value="inactive"> Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Posted By *</label>
                        <input class="form-control" type="text" name="posted_by" placeholder="Posted By" >
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Meta Robots *</label>
                        <select class="form-control" name="meta_robots" placeholder="Meta Robots">
                            <option value="INDEX, FOLLOW" selected>INDEX, FOLLOW</option>
                            <option value="NOINDEX, FOLLOW">NOINDEX, FOLLOW</option>
                            <option value="INDEX, NOFOLLOW">INDEX, NOFOLLOW</option>
                            <option value="NOINDEX, NOFOLLOW">NOINDEX, NOFOLLOW</option>
                        </select>
                    </div>
                </div>



                <div class="tts-col-6">
                    <div class="form-group">
                        <label> Meta Title * </label>
                        <input class="form-control" type="text" name="meta_title" placeholder="Meta Title">
                    </div>
                </div>
                <div class="tts-col-12">
                    <div class="form-group">
                        <label> Meta Keyword* </label>
                        <textarea class="form-control" type="file" name="meta_keyword" placeholder="Meta Keyword" rows="2"></textarea>
                    </div>
                </div>
                <div class="tts-col-12">
                    <div class="form-group">
                        <label> Meta Description* </label>
                        <textarea class="form-control" type="file" name="meta_description" placeholder="Meta Description" rows="2"></textarea>
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
    
