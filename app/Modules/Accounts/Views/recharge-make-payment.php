<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="table_title">
                    <div class="sale_bar">
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="m-0">Online Make Payment</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <!----------End Search Bar ----------------->
                            <div class="table-resposive">
                                <form action="<?php echo site_url("accounts/make-payment-processing"); ?>" method="POST"
                                    tts-form="true" name="accounts-make-payment" enctype="multipart/form-data">
                                    <table class="table table-bordered">
                                        <tbody class="lead_details">
                                            <tr>
                                                <th><span class=" item-text-head"><b>Payment Mode *</b></span></th>
                                                <td>
                                                    <select name="payment_mode" class="form-select"
                                                        tts-validatation="Required"
                                                        tts-error-msg="Please select payment mode"
                                                        onchange="tts_makepaymentpaymentMode(this,'accounts-make-payment')">
                                                        <option value="">Please Select Payment Mode</option>
                                                        <option value="CreditCard">Credit Card</option>
                                                        <option value="DebitCard">Debit Card</option>
                                                        <option value="NetBanking">Net Banking</option>
                                                        <option value="UPIPayments">Pay Through UPI</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><span class=" item-text-head"><b>Amount*</b></span></th>
                                                <td>
                                                    <input class="form-control" type="text" name="amount"
                                                        tts-validatation="Required" tts-error-msg="Please enter amount"
                                                        placeholder="Amount" maxlength="6">
                                                    <p convenience-fee-value="true" class="text-success"></p>
                                                </td>
                                            </tr>
                                            <tr cheque-draft-utr-number="true" class="offline-make-payment">
                                                <th><span class=" item-text-head"
                                                        cheque-draft-utr-number-name="true"><b>Cheque
                                                            Number*</b></span>
                                                </th>
                                                <td>
                                                    <input class="form-control" type="text"
                                                        name="cheque_draft_utr_number" tts-validatation="Required"
                                                        tts-error-msg="Please enter cheque number"
                                                        placeholder="Cheque Number" cheque-draft-utr-number-box="true">
                                                </td>
                                            </tr>
                                            <tr class="offline-make-payment">
                                                <th><span class=" item-text-head"><b>Date*</b></span></th>
                                                <td>
                                                    <input class="form-control" type="text" name="date"
                                                        tts-validatation="Required" tts-error-msg="Please select date"
                                                        placeholder="Date" nolim-calendor="true">
                                                </td>
                                            </tr>
                                            <tr class="offline-make-payment">
                                                <th><span class=" item-text-head"><b>Bank*</b></span></th>
                                                <td>
                                                    <input class="form-control" type="text" name="bank"
                                                        tts-validatation="Required"
                                                        tts-error-msg="Please enter bank name" placeholder="Bank  Name">
                                                </td>
                                            </tr>
                                            <tr class="offline-make-payment">
                                                <th><span class=" item-text-head"><b>Branch*</b></span></th>
                                                <td>
                                                    <input class="form-control" type="text" name="branch"
                                                        tts-validatation="Required"
                                                        tts-error-msg="Please enter branch name"
                                                        placeholder="Branch Name">
                                                </td>
                                            </tr>
                                            <tr class="offline-make-payment">
                                                <th><span class=" item-text-head"><b>Our Bank Account *</b></span>
                                                </th>
                                                <td>
                                                    <select name="company_bank_account" class="form-select"
                                                        tts-validatation="Required"
                                                        tts-error-msg="Please select our bank account">
                                                        <option value="" selected="selected">Choose</option>
                                                        <?php if ($superAdminBankAccountinfo) {
                                             foreach ($superAdminBankAccountinfo as $bank_account_info) { ?>
                                                        <option value="<?php echo $bank_account_info['id'] ?>">
                                                            <?php echo $bank_account_info['bank_name'] .
                                                "(" . $bank_account_info['branch_name'] . ")" . "-" . $bank_account_info['account_no']; ?>
                                                        </option>
                                                        <?php }
                                             } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <!-- <tr class="offline-make-payment">
                                    <th><span
                                       class=" item-text-head"><b>Bank Transaction ID/UTR </b></span></th>
                                    <td>
                                       <input class="form-control" type="text" name="bank_transaction_id"
                                          placeholder="Bank Transaction ID">
                                    </td>
                                    </tr> -->
                                            <tr>
                                                <th><span class=" item-text-head"><b>Remark*</b></span></th>
                                                <td>
                                                    <textarea class="form-control" type="text" name="remark"
                                                        placeholder="Remark" rows="2" spellcheck="false"
                                                        tts-validatation="Required"
                                                        tts-error-msg="Please enter remark"></textarea>
                                                </td>
                                            </tr>
                                            <tr upload-cheque-file="true" class="uplolad-file">
                                                <th><span class=" item-text-head"><b>Upload files</b></span>
                                                </th>
                                                <td>
                                                    <input type="file" class="form-control" type="file"
                                                        name="upload_file" placeholder="Upload Cheque" rows="2"
                                                        spellcheck="false" tts-validatation="Required"
                                                        tts-error-msg="Please upload Cheque">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <td><input class="badge badge-md badge-primary" type="submit"
                                                        value="Submit">
                                                </td>
                                            </tr>


                                </form>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$(".offline-make-payment").hide();
$("[upload-cheque-file]").hide();

function tts_makepaymentpaymentMode(thisval, formname) {
    var selectedValue = thisval.value;
    var form = $("form[name=" + formname + "]");
    var conveniencefeevalue = document.querySelector("[convenience-fee-value]");

    conveniencefeevalue.innerHTML = "";
    if (selectedValue) {
        if (selectedValue == 'CreditCard' || selectedValue == 'DebitCard' || selectedValue == 'NetBanking' ||
            selectedValue == 'UPIPayments') {
            $("[upload-cheque-file]").hide();
            if (selectedValue == 'CreditCard') {
                let credit_card = '';

                if (selectedValue == 'CreditCard') {
                    credit_card = 'credit_card';
                    selectedValue = credit_card;
                }


                const convfeetypearray = {
                    credit_card: credit_card,
                    DebitCard: "debit_card",
                    NetBanking: "net_banking",
                    UPIPayments: "mobile_wallet"
                };


                var convfeetypeValue = convfeetypearray[[selectedValue]];


                var convfeeArray = '<?php echo json_encode($ConvenienceFeeInfo); ?>';
                var convfeeArray = JSON.parse(convfeeArray);
                var convfeetype = convfeeArray[[convfeetypeValue] + "_type"];
                var convfeevalue = convfeeArray[[convfeetypeValue] + "_value"];
                if (convfeetype == 'fixed') {
                    var convfee = "INR " + convfeevalue + "  Convenience fees Apply";
                } else {
                    var convfee = convfeevalue + " % Convenience fees Apply";
                }
                conveniencefeevalue.innerHTML = convfee;
            } else {
                const convfeetypearray = {
                    credit_card: '',
                    DebitCard: "debit_card",
                    NetBanking: "net_banking",
                    UPIPayments: "mobile_wallet"
                };

                var convfeetypeValue = convfeetypearray[[selectedValue]];

                var convfeeArray = '<?php echo json_encode($ConvenienceFeeInfo); ?>';
                var convfeeArray = JSON.parse(convfeeArray);
                var convfeetype = convfeeArray[[convfeetypeValue] + "_type"];
                var convfeevalue = convfeeArray[[convfeetypeValue] + "_value"];
                if (convfeetype == 'fixed') {
                    var convfee = "INR " + convfeevalue + "  Convenience fees Apply";
                } else {
                    var convfee = convfeevalue + " % Convenience fees Apply";
                }
                conveniencefeevalue.innerHTML = convfee;
            }

        }

        if (selectedValue == 'Draft' || selectedValue == 'Cheque' || selectedValue == 'NEFT' || selectedValue ==
            'RTGS' || selectedValue == "Transfer" || selectedValue == "Cash") {
            $("[upload-cheque-file]").show();
            $(".form-control", form).attr("tts-validatation", "required");
            $(".offline-make-payment").show();
            var queryselector = document.querySelector("[cheque-draft-utr-number]");
            var queryselectoruploadchequefile = document.querySelector("[upload-cheque-file]");
            var queryselectorbox = document.querySelector("[cheque-draft-utr-number-box]");
            var queryselectorlabel = document.querySelector("[cheque-draft-utr-number-name]");
            if (selectedValue == 'Draft') {
                queryselectorlabel.innerHTML = "<b>Draft Number</b>";
                queryselectorbox.setAttribute("placeholder", "Draft Number");
                queryselectorbox.setAttribute("tts-error-msg", "Please enter draft number");
                if (queryselectoruploadchequefile.classList.contains('d-none')) {
                    queryselectoruploadchequefile.classList.remove('d-none');
                }
                if (queryselector.classList.contains('d-none')) {
                    queryselector.classList.remove('d-none');
                }
            } else if (selectedValue == 'NEFT' || selectedValue == 'RTGS') {
                queryselectorlabel.innerHTML = "<b>UTR Number / Transaction ID</b>";
                queryselectorbox.setAttribute("placeholder", "UTR Number / Transaction ID");
                queryselectorbox.setAttribute("tts-error-msg", "Please enter utr number");
                /* if (!queryselectoruploadchequefile.classList.contains('d-none')) {
                    queryselectoruploadchequefile.classList.add('d-none');
                } */
                if (queryselector.classList.contains('d-none')) {
                    queryselector.classList.remove('d-none');
                }
            } else if (selectedValue == "Transfer" || selectedValue == "Cash") {
                /* if (!queryselectoruploadchequefile.classList.contains('d-none')) {
                    queryselectoruploadchequefile.classList.add('d-none');
                } */
                if (!queryselector.classList.contains('d-none')) {
                    queryselector.classList.add('d-none');
                }
            } else {
                queryselectorlabel.innerHTML = "<b>Cheque Number</b>";
                queryselectorbox.setAttribute("placeholder", "Cheque Number");
                queryselectorbox.setAttribute("tts-error-msg", "Please enter cheque number");
                if (queryselectoruploadchequefile.classList.contains('d-none')) {
                    queryselectoruploadchequefile.classList.remove('d-none');
                }
                if (queryselector.classList.contains('d-none')) {
                    queryselector.classList.remove('d-none');
                }
            }

        } else {
            $(".offline-make-payment").hide();
            $(".form-control", form).removeAttr("tts-validatation");
            if (selectedValue == 'ATMCard') {
                $("[name='upload_file']", form).attr("tts-validatation", "required");
            }
            $("[name='amount']", form).attr("tts-validatation", "required");
            $("[name='remark']", form).attr("tts-validatation", "required");

        }


    }
}
</script>