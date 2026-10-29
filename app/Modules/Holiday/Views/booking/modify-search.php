<div class="col-md-12 ">
    <div class="hotal-filter-card mb-3 p-2">
        <ul class="modify_list row align-items-center">
            <li class="col-md-6 col-12 mb-0">
                <h5 class="m-0"><?php echo $search_data['slug'] ?></h5>
            </li>
            <li class="col-md-6 col-6">
                <button type="button" class="btn modifysearch" data-bs-toggle="modal" data-bs-target="#tts-holiday-modify">
                    <span class="text">modify search</span>
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="tts-holiday-modify" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content modal_content">
            <div class="modal-header modal_header">
                <h5 class="modal-title">Modify Search</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body modal_body tts_product_box">
                <div id="flight" class="tab-content current">
                    <div class="row">
                        <div class="tab-pane fade show active" id="tts__flight__oneway"
                             role="tabpanel" aria-labelledby="tts__flight__oneway-tab">
                            <form class="tts__form_wrapper" action="<?php echo site_url('holiday/search') ?>" name="holiday-form">
                                <div class="row no-gutters flight_search_border align-items-center m0">
                                    <div class="col-sm-11 p-0">
                                        <div class="position-relative">
                                            <label class="form-label">To City / Country</label>
                                            <input type="hidden" value="<?php echo $_GET['tag']?>" name="tag" tts-holiday-tag="true">
                                            <input type="text" name="slug" class="form-control tts__input__input border-end-0" value="<?php echo trim($_GET['slug'])?>" holiday-destination="true" placeholder="Search Destinations Themes Holidays">
                                            <input type="hidden" value="<?php echo $_GET['id']?>" name="id" tts-holiday-id="true" >

                                        </div>
                                    </div>
                                    <div class="col-sm-1 p-0">
                                        <button class="oneway_btn btn" onclick="return checkHolidaySearchValidation();">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>