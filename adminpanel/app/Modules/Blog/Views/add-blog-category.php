<div class="modal-header">
   <h1 class="modal-title fs-5"><?php echo 'Add New Blog Category'; ?></h1>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">
   <form action="<?php echo site_url('blog/add-blogs-category'); ?>" method="post"  tts-form="true" name="add_blogs" enctype="multipart/form-data">
      <div class="modal-body">
         <div class="row">
            <div class="col-6">
               <div class="form-group">
                  <label>Category Name *</label>
                  <input class="form-control" type="text" name="category_name" placeholder="Blog Category Name" onblur='tts_slug_url(this.value,"category-slug")' >
               </div>
            </div>
            <div class="col-6">
               <div class="form-group">
                  <label>Category Slug *</label>
                  <input class="form-control" type="text" name="category_slug" placeholder="Category Slug" id="category-slug">
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
                  <label>Status *</label>
                  <select class="form-select" name="status" placeholder="Blog Status">
                     <option value="active" selected>Active</option>
                     <option value="inactive"> Inactive</option>
                  </select>
               </div>
            </div>
            <div class="col-12">
               <div class="form-group">
                  <label>Description *</label>
                  <textarea class="form-control tts-editornote" type="textarea" name="description" rows="3" placeholder=" Description"></textarea>
               </div>
            </div>
            <div class="col-6">
               <div class="form-group">
                  <label>Meta Robots *</label>
                  <select class="form-select" name="meta_robots" placeholder="Meta Robots">
                     <option value="INDEX, FOLLOW" selected>INDEX, FOLLOW</option>
                     <option value="NOINDEX, FOLLOW">NOINDEX, FOLLOW</option>
                     <option value="INDEX, NOFOLLOW">INDEX, NOFOLLOW</option>
                     <option value="NOINDEX, NOFOLLOW">NOINDEX, NOFOLLOW</option>
                  </select>
               </div>
            </div>
            <div class="col-6">
               <div class="form-group">
                  <label> Meta Title * </label>
                  <input class="form-control" type="text" name="meta_title" placeholder="Meta Title">
               </div>
            </div>
            <div class="col-12">
               <div class="form-group">
                  <label> Meta Keyword* </label>
                  <textarea class="form-control" type="file" name="meta_keyword" placeholder="Meta Keyword" rows="2"></textarea>
               </div>
            </div>
            <div class="col-12">
               <div class="form-group">
                  <label> Meta Description* </label>
                  <textarea class="form-control" type="file" name="meta_description" placeholder="Meta Description" rows="2"></textarea>
               </div>
            </div>
         </div>
      </div> 
      <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
   </form>
</div>