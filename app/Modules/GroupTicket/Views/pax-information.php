<style>
    .red-button {
        background-color: red;
        color: white;
    }
</style>

<div class="tts-d-content" id="tts-passenger-html">

    <div class="row">

        <div class="col-md-12 mb-3">

            <h6 class="card-title m-0">Passenger <?= ucwords($type) ?> <?= $count + 1 ?></h6>

        </div>

        <div class="col-md-3">

            <div class="form-group form-mb-20">

                <label class="form-label">Title</label>

                <select class="form-select" name="pax[<?= $type ?>][<?= $count ?>][title]" placeholder="Preferred Class">

                    <option value="Mr">Mr</option>

                    <option value="Mrs">Mrs</option>

                    <option value="Other">Other</option>



                </select>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="form-group form-mb-20">

                <label class="form-label">First Name *</label>

                <input type="text" class="form-control" placeholder="First Name" name="pax[<?= $type ?>][<?= $count ?>][first_name]">

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="form-group form-mb-20">

                <label class="form-label">Last Name *</label>

                <input type="text" class="form-control" placeholder="Last Name" name="pax[<?= $type ?>][<?= $count ?>][last_name]">

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="form-group form-mb-20">

                <label class="form-label">Date of birth *</label>

                <input type="text" class="form-control" placeholder="D.O.B" <?= $type ?>_dob_date="true" name="pax[<?= $type ?>][<?= $count ?>][dob]">

            </div>

        </div>

    </div>

    <?php if ($flightType == "international") : ?>

        <div class="row">

            <div class="col-md-12 mb-3">

                <h6 class="card-title m-0">Passport Details</h6>

            </div>

            <div class="col-md-3">

                <div class="form-group form-mb-20">

                    <label class="form-label">Country *</label>

                    <select name="pax[<?= $type ?>][<?= $count ?>][country]" class="form-select">

                        <option value="">Country</option>

                        <?php if ($dial_code) {

                            foreach ($dial_code as $code) { ?>

                                <option value="<?php echo $code['iso2']; ?>"><?php echo $code['name']; ?>

                                </option>

                        <?php }

                        } ?>

                    </select>

                </div>

            </div>

            <div class="col-md-3 mb-3">

                <div class="form-group form-mb-20">

                    <label class="form-label">Passport Number *</label>

                    <input type="text" class="form-control" placeholder="Passport Number" name="pax[<?= $type ?>][<?= $count ?>][passport_no]">

                </div>

            </div>

            <div class="col-md-3 mb-3">

                <div class="form-group form-mb-20">

                    <label class="form-label">Passport Issue *</label>

                    <input type="text" class="form-control" placeholder="Passport Issue" name="pax[<?= $type ?>][<?= $count ?>][issue_date]" flight_pass_issue="true">

                </div>

            </div>

            <div class="col-md-3 mb-3">

                <div class="form-group form-mb-20">

                    <label class="form-label">Passport Expiry *</label>

                    <input type="text" class="form-control" placeholder="Passport Expiry" name="pax[<?= $type ?>][<?= $count ?>][expiry_date]" flight_pass_expiry="true">

                </div>

            </div>

        </div>

    <?php endif; ?>

    <div class="col-md-1">
        <button type="button" class=" btn tts_remove_Passenger red-button"
            data-remove-attribute="<?= $type; ?>">Remove</button>
    </div>

</div>