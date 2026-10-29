<div class="modal-header">
    <h5 class="modal-title">Add New Page</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('pages/add-pages'); ?>" method="post" tts-form="true" name="add_pages">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label class="form-label">Title *</label>
                        <input class="form-control" type="text" name="title" placeholder="Title" onblur="tts_slug_url(this.value,'page-slug')">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label class="form-label">Page Slug *</label>
                        <input class="form-control" type="text" name="slug_url" placeholder="Page Slug" id="page-slug">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label class="form-label">Custom Url *</label>
                        <input class="form-control" type="text" name="custom_url" placeholder="Custom Url">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label class="form-label">Content </label>
                        <textarea class="form-control tts-editornote" type="textarea" name="content" rows="3"
                                  placeholder="Content"></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label class="form-label">Status *</label>
                        <select class="form-select" name="status" placeholder="Blog Status">
                            <option value="active" selected>Active</option>
                            <option value="inactive"> Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label class="form-label">Meta Robots *</label>
                        <select class="form-select" name="meta_robots" placeholder="Meta Robots">
                            <option value="INDEX, FOLLOW" selected>INDEX, FOLLOW</option>
                            <option value="NOINDEX, FOLLOW">NOINDEX, FOLLOW</option>
                            <option value="INDEX, NOFOLLOW">INDEX, NOFOLLOW</option>
                            <option value="NOINDEX, NOFOLLOW">NOINDEX, NOFOLLOW</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label class="form-label"> Meta Title * </label>
                        <input class="form-control" type="text" name="meta_title" placeholder="Meta Title">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label class="form-label"> Meta Keyword* </label>
                        <textarea class="form-control" type="text" name="meta_keyword" placeholder="Meta Keyword"
                                  rows="2"></textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label class="form-label"> Meta Description* </label>
                        <textarea class="form-control" type="text" name="meta_description"
                                  placeholder="Meta Description" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>  
    
