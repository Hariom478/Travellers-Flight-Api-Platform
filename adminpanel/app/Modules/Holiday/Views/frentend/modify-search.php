<div class="col-md-12 ">
    <div class="hotal-filter-card d-flex align-items-center justify-content-between d-none d-md-flex justify-content-md-between my-3 p-2">
        <ul class="modify_list  d-flex align-items-center">
            <li class="d-flex align-items-center">
                <h2><?php echo $search_data['slug'] ?></h2>
            </li>
        </ul>

        <ul class="modify_list1 d-flex align-items-center">
            <!--    <li class="d-flex align-items-center me-2">
                    <button type="button" class="btn btn-link border text-black" data-toggle="tooltip"
                            data-placement="top" title="Share">
                        <i class="fa fa-share-alt"></i>
                    </button>
                </li>-->
            <li>
                <button type="button" class="btn btn-link border" data-bs-toggle="modal"
                        data-bs-target="#tts-holiday-modify">
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
            <div class="modal-body modal_body">
                <div id="flight" class="tab-content current">
                    <div class="row">
                        <div class="tab-pane fade show active" id="tts__flight__oneway"
                             role="tabpanel" aria-labelledby="tts__flight__oneway-tab">
                            <form class="tts__form_wrapper" action="<?php echo site_url('holiday/search') ?>" name="holiday-form">
                                <div class="row no-gutters flight_search_border align-items-center">
                                    <div class="col-sm-11  p0">
                                        <div class="position-relative">
                                            <span class="tts__input__label">TO CITY / COUNTRY</span>
                                            <input type="hidden" value="<?php echo $_GET['tag']?>" name="tag" tts-holiday-tag="true">
                                            <input type="text" name="slug" class="form-control tts__input__input border-end-0" value="<?php echo trim($_GET['slug'])?>" holiday-destination="true" placeholder="Search Destinations Themes Holidays">
                                            <input type="hidden" value="<?php echo $_GET['id']?>" name="id" tts-holiday-id="true" >

                                        </div>
                                    </div>
                                    <div class="col-sm-1 p0">
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