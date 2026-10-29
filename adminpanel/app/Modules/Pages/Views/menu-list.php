<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="m-0"> Menu Management</h5>
                    </div>
                    <div class="col-md-8 text-md-end">
                    <?php if(permission_access_error("Page", "view_menu_label")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='pages'
                                data-href="<?php echo site_url('pages/menu-labels') ?>"><i
                                    class="fa-solid fa-add "></i> Menu Labels
                        </button>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php  $selectedpageId = array_column($menu_selected_list,"id"); ?>
        <div class="page-content-area">
            <div class="card-body">
                <form method="post" action="<?php echo site_url("pages/menu-list"); ?>">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-3">
                            <label>Select a menu to edit :</label>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-mb-20">
                                <select class="form-select" name="menu_type" tts-call-select-menu="true">
                                    <?php if (!empty($menu_type_list) && is_array($menu_type_list)) {
                                        foreach ($menu_type_list as $menu_data) {

                                            ?>
                                            <option value="<?php echo $menu_data['id'] ?>" <?php if ($menu_data['id'] == $menu_type_id) {
                                                echo "selected";
                                            } ?>><?php echo $menu_data['menu_name'] ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group form-mb-20">
                                <button class="badge badge-md badge-primary badge_search" type="submit">Select Current Menu</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-5">
                        <div class="left-sidebar-leads box_shadow">
                            <div class="view_head mb_10 d-md-flex align-items-center justify-content-between">
                                
                           
                                <div class="">
                                    <input class="tts-select-pages" tts-pages-module="select-all-pages"
                                           type="checkbox" id="flexCheck">
                                    <label for="flexCheck" class="m-0">Select All</label>
                                </div>
                            
                            </div>
                            <?php if (!empty($page_list) && is_array($page_list)) { ?>
                                <form action="<?php echo site_url('pages/update-menu'); ?>" method="post" onsubmit="return menu_validation()"
                                      name="tts-pages" class="row m-0 ">
                                    <input type="hidden" name="menu_type_id" value="<?php echo $menu_type_id; ?>"
                                           tts-menu-type-id="true">
                                    <?php foreach ($page_list as $key => $list) {
                                        ?>
                                        <div class="col-md-12">
                                            <div class="form-group form-mb-20">
                                       
                                                <div>
                                                    <input type="checkbox"
                                                           name="pages[<?php echo $list['id']; ?>]"
                                                           value="<?php echo $list['id']; ?>" class="select-all-pages"
                                                           data-permission-input =  "true"  menu-page-select =  "true" <?php echo   in_array($list['id'], $selectedpageId)?"checked":""; ?>  > <?php echo $list['title']; ?>
                                                </div>
                                           
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if(permission_access_error("Page", "add_menu")) { ?>
                                    <div class="col-md-12 mb_10 ">
                                        <input class="btn btn-primary" type="submit"
                                               value="Add Menu Pages">
                                    </div>
                                    <?php } ?>

                                </form>
                            <?php } else { ?>
                                <p class="text-center">No data found</p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="left-sidebar-leads box_shadow">
                            <div class="view_head mb_10 d-md-flex align-items-center justify-content-between">
                               
                                <div class="">
                                    <strong>Menu List (drag & drop to reordered menu)</strong>
                                </div>
                                <div class="">
                                    <div class="pull-right">
                                        <input class="tts-select-menu" tts-menu-module="select-all-menu"
                                               type="checkbox" id="flexCheckMenu">
                                        <label for="flexCheckMenu" class="m-0">Select All</label>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($menu_selected_list) && is_array($menu_selected_list)) { ?>
                                <form action="<?php echo site_url('pages/remove-menu'); ?>" method="post"
                                      name="tts-pages-menu" class="row m-0">
                                    <div class="col-md-12">
                                        <input type="hidden" name="menu_type_id" value="<?php echo $menu_type_id; ?>"
                                           tts-menu-type-id="true">
                                           <ul id="Menusortable">
                                    <?php foreach ($menu_selected_list as $list) {
                                        ?>
                                      
                                            <li class="ui-state-default" id  =  "<?php echo $list['id']; ?>">
                                                    <input type="checkbox"
                                                           name="pages_remove[]"
                                                           value="<?php echo $list['id']; ?>" class="select-all-menu"
                                                           data-permission-input> <?php echo $list['title']; ?>
                                                           </li>
                                   
                                    <?php } ?>
                                    </ul>

                                    <?php if(permission_access_error("Page", "remove_menu")) { ?>
                                    <div class="mb_10 pull-right ml-5">
                                        <input class="btn btn-primary" name="remove" type="submit"
                                               value="Remove Menu Pages">
                                    </div>
                                    <?php } ?>
                                    </div>

                                </form>
                            <?php } else { ?>
                                <p class="text-center">No data found</p>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on("click", ".tts-select-pages", function (e) {
        var module = $(this).attr('tts-pages-module');
        $("." + module).prop('checked', $(this).prop("checked"));
    });

    $(document).on("click", ".tts-select-menu", function (e) {
        var module = $(this).attr('tts-menu-module');
        $("." + module).prop('checked', $(this).prop("checked"));
    });

    $(document).on("change", "[tts-call-select-menu]", function (event) {
        var selected_val = $(this).find(":selected").val();
        $("[tts-menu-type-id]").val(selected_val);
    });

    function menu_validation(){

        if ($('[menu-page-select]:checked').length == 0) {
            alert("Please Select  at least one Record");
            return false;
        }else {
            return true;
        }
    }



</script>
<script>
  $( function() {
    $( "#Menusortable" ).sortable({
        update: function( event, ui ) {
    var sortedmenuIDs = $( "#Menusortable" ).sortable( "toArray");
    var menuid  =  $("[tts-menu-type-id]").val();
    $.ajax({
                url:  site_url + 'pages/sort-menu',
                method: "post",
                data: {menuid:menuid,sortedmenuIDs:sortedmenuIDs},
                cache: false,
                success: function (resp) 
                {
                    $("[data-message]").addClass(resp.Class).attr('onClick', "this.classList.add('hide')").html(resp.Message);

                },
                error: function (res) {
                    alert("Unexpected error! Try again.");
                }
            });
        },
  })
});
 
  </script>