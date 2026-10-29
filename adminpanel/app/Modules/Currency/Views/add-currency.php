<div class="modal-header">
    <h5 class="modal-title">Add <?php echo ' Currency'; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?php echo site_url('currency/add-currency'); ?>" method="post" onsubmit="return validateForm()"
      tts-form="true" name="add_blogs">

    <div class="modal-body">
        <div class="row">
          

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12">Base Currency *</label>
                    <select class="form-select inputtext h42i fs12" name="base_currency" id="bank_details"
                            onchange="sct_change()" data-validation="required" putdata="true">
                        <option value="">Select Base Currency</option>
                        <?php
                        if (!empty($currency) && is_array($currency)) {
                            foreach ($currency as $value) { ?>
                                <option value="<?php echo $value['currency'] ?>"
                                        basecurrencyname="<?php echo $value['currency_name']; ?>"  basecurrencysymbol= "<?php echo $value['currency_symbol']; ?>">
                                    <?php echo $value['currency'] ?>
                                </option>
                            <?php }
                        } ?>
                    </select>
                </div>
            </div>


         



            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12">Convert Currency *</label>
                    <select class="form-select inputtext h42i fs12" name="convert_currency" id="currency_name"
                            onchange="currency_change()" data-validation="required" putdata="true">
                        <option value="">Select Convert Currency</option>
                        <?php
                        if (!empty($currency) && is_array($currency)) {
                            foreach ($currency as $value) { ?>
                                <option value="<?php echo $value['currency'] ?>"
                                convertcurrencyname="<?php echo $value['currency_name']; ?>"  convertcurrencysymbol="<?php echo $value['currency_symbol']; ?>">
                                    <?php echo $value['currency'] ?>
                                </option>
                            <?php }
                        } ?>
                    </select>
                </div>
            </div>





           
           

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12">Base Currency Name* </label>
                    <input class="form-control inputtext fs12" type="text" name="base_currency_name"
                           placeholder="base currency name" readonly>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12">Base Currency Symbol* </label>
                    <input class="form-control inputtext fs12" type="text" name="base_currency_symbol"
                           placeholder="base currency symbol" readonly>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12">Convert Currency Name* </label>
                    <input class="form-control inputtext fs12" type="text" name="convert_currency_name"
                           placeholder="convert currency name" readonly>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label class="fw_5 control-label fs12">Convert Currency Symbol* </label>
                    <input class="form-control inputtext fs12" type="text" name="convert_currency_symbol"
                           placeholder="convert currency symbol	" readonly>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Convertion Rate* </label>
                    <input class="form-control" type="text" name="convertion_rate" placeholder="Convertion Rate">
                </div>
            </div>

           

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>

<script>
    function sct_change() {
        var selectedOption = $("#bank_details option:selected");
        var baseCurrencyName = selectedOption.attr('basecurrencyname');
        var baseCurrencySymbol = selectedOption.attr('basecurrencysymbol');

        if (baseCurrencyName) {
            // Replace spaces with hyphens in the base currency name
            baseCurrencyName = baseCurrencyName.replace(/ /g, "-");
            $("[name='base_currency_name']").val(baseCurrencyName);
        }

        if (baseCurrencySymbol) {
            // Replace spaces with hyphens in the base currency symbol
            baseCurrencySymbol = baseCurrencySymbol.replace(/ /g, "-");
            $("[name='base_currency_symbol']").val(baseCurrencySymbol);
        }
    }
</script>

<script>
    function currency_change() {
        var selectedOption = $("#currency_name option:selected");
        var convertCurrencyName = selectedOption.attr('convertcurrencyname');
        var convertCurrencySymbol = selectedOption.attr('convertcurrencysymbol');

        if (convertCurrencyName) {
            // Replace spaces with hyphens in the convert currency name
            convertCurrencyName = convertCurrencyName.replace(/ /g, "-");
            $("[name='convert_currency_name']").val(convertCurrencyName);
        }

        if (convertCurrencySymbol) {
            // Replace spaces with hyphens in the convert currency symbol
            convertCurrencySymbol = convertCurrencySymbol.replace(/ /g, "-");
            $("[name='convert_currency_symbol']").val(convertCurrencySymbol);
        }
    }
</script>
