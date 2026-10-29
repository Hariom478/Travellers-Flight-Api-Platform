<div class="modal-header">
   <h5 class="modal-title">Add Holiday Theme</h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('holiday/add-themes'); ?>" method="post" tts-form="true" name="add_slider" enctype="multipart/form-data">
   <div class="modal-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Theme Name *</label>
               <input class="form-control" type="text" name="theme_name" placeholder="Theme Name" onblur='tts_slug_url(this.value,"holiday-theme-slug")'>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Theme Slug *</label>
               <input class="form-control" type="text" name="theme_slug" placeholder="Theme Slug" id="holiday-theme-slug">
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Starting Price </label>
               <input class="form-control" type="text" name="starting_price" placeholder="Starting Price">
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Image * </label>
               <input class="form-control" type="file" name="theme_image" placeholder="Image">
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Theme Icon * </label>
               <input class="form-control" type="file" name="theme_icon" placeholder="Image">
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Status *</label>
               <select class="form-select" name="status" placeholder="Status">
                  <option value="active" selected>Active</option>
                  <option value="inactive"> Inactive</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label class="mt20">
               <input type="checkbox" name="show_on_home" value="1" class="Lead"> Show On Home
               </label>
            </div>
         </div>
         <div class="col-md-12">
            <div class="form-group form-mb-20">
               <label>Description </label>
               <textarea class="form-control tts-editornote" type="textarea" name="description" rows="3" placeholder="Description"></textarea>
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
   <div class="modal-footer">
      <button class="btn btn-primary" type="submit">Save</button>
   </div>
</form>
<script>
   $(document).ready(function() {
      setTimeout(() => {
         $('.note-editable').height(200);
      }, 50);
   });
</script>