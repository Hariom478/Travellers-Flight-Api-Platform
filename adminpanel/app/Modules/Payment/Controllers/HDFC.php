<?php



namespace Modules\Payment\Controllers;



use App\Controllers\BaseController;

use App\Modules\Payment\Models\PaymentModel;



class HDFC extends BaseController

{

    private $MerchantId;
    private $WorkingKey;
    private $AccessCode;
    private $PaymentBaseURL;
    private $APIStatusURL;

    /**
     * CCAvenue/HDFC credentials and endpoints come from adminpanel/app/.env.
     *   hdfc.mode = Live  -> live payment gateway (production server)
     *   hdfc.mode = Test  -> test gateway (stage / local)
     * See the "HDFC / CCAVENUE PAYMENT GATEWAY" block in adminpanel/app/.env.example.
     */
    public function __construct()
    {
        parent::__construct();

        $mode = strtolower(trim((string) env('hdfc.mode', 'Test'))) === 'live' ? 'Live' : 'Test';

        if ($mode === 'Live') {
            $this->PaymentBaseURL = 'https://secure.ccavenue.com/';
            $this->APIStatusURL   = 'https://api.ccavenue.com';
        } else {
            $this->PaymentBaseURL = 'https://test.ccavenue.com';
            $this->APIStatusURL   = 'https://apitest.ccavenue.com';
        }

        $this->MerchantId = (string) env('hdfc.merchantId');
        $this->WorkingKey = (string) env('hdfc.workingKey');
        $this->AccessCode = (string) env('hdfc.accessCode');
    }




    public function request($request)

    {

        $tid = rand(1000, 99999999) . time();

        $order_id =  time();



        $payment_option = 'OPT' . $request['PaymentMode'];

        $card_type = $request['PaymentMode'];



        if (isset($request['CardName'])){

            $CardName = $request['CardName'];

        }else{

            $CardName= "";

        }



        $data = array(

            'tid' => $tid,
            'merchant_id' => $this->MerchantId,
            'order_id' => $order_id,
            'amount' => $request['Amount'],
            'currency' => $request['Currency'],
            'redirect_url' => $request['RedirectURL'],
            'cancel_url' => $request['CancelURL'],
            'language' => 'EN',
            'card_name'=>$CardName,
            'billing_name' => $request['FirstName'] . ' ' . $request['LastName'],
            'billing_email' => $request['Email'],
            'billing_tel' => $request['MobileNumber'],
            'payment_option' => $payment_option,
            'card_type' => $card_type,
            'merchant_param1' => $request['Udf1'],
            'merchant_param2' => $request['Udf2'],
            'merchant_param3' => $request['Udf3'],
            'merchant_param4' => $request['Udf4'],
            'merchant_param5' => $request['Udf5']
        );



        $merchant_data = '2';

        foreach ($data as $key => $value) {

            $merchant_data .= $key . '=' . $value . '&';

        }

        $encrypted_data = HDFC::encrypt($merchant_data, $this->WorkingKey);

        $PaymentModel = new PaymentModel();

        $servicePrefix = $PaymentModel->super_admin_booking_pre_fix_code($request['Service']);

        $payment_log = array(

            'web_partner_id' => $request['WebPartnerId'],

            'user_id' => $request['UserId'],

            'transaction_id' => $tid,

            'order_id' => $order_id,

            'payment_status' => 'Processing',

            'service' => $request['Service'],

            'booking_ref_no' => $request['BookingId'],

            'amount' => $request['Amount'],

            'payment_request' => json_encode($data),

            'customer_name' => $request['FirstName'] . ' ' . $request['LastName'],

            'mobile_number' => $request['MobileNumber'],

            'email_id' => $request['Email'],

            'convenience_fee' => $request['convenience_fee'],

            'payment_mode' => $request['SavePaymentMode'],

            'booking_prefix' => isset($servicePrefix['pre_fix'])?$servicePrefix['pre_fix']:null,

            'payment_getway_name' => 'HDFC',

            'service_log' => $request['ServiceLog'],

            'created' => create_date()

        );



        $PaymentModel->insertData('super_admin_payment_transaction', $payment_log);



        $data = [

            'title' => 'Payment',

            'encrypted_data' => $encrypted_data,

            'access_code' => $this->AccessCode,

            'payment_url' => $this->PaymentBaseURL,

            'view' => "Payment\Views\hdfc",

        ];

        return view('template/default-layout', $data);

    }



    public function response($response)

    {

        $encResponse = $response["encResp"];

        $rcvdString = HDFC::decrypt($encResponse, $this->WorkingKey);

        $order_status = "";

        $decryptValues = explode('&', $rcvdString);

        $dataSize = sizeof($decryptValues);

        $saveresponse = array();

        for ($i = 0; $i < $dataSize; $i++) {

            $information = explode('=', $decryptValues[$i]);

            $saveresponse[$information[0]] = $information[1];

            if ($i == 3) $order_status = $information[1];

        }



        $payment_status = '';

        if ($order_status === "Success") {

            $payment_status = 'Successful';

        } else {

            $payment_status = 'Failed';

        }



        $status_api_response = HDFC::check_status($saveresponse['order_id'], $saveresponse['tracking_id']);



        if (isset($status_api_response['Order_Status_Result'])) {

            if (isset($status_api_response['Order_Status_Result']['order_bank_response'])) {

                if($status_api_response['Order_Status_Result']['order_status'] == 'Shipped' || $status_api_response['Order_Status_Result']['order_status'] == 'Successful')

                {

                    $payment_status = 'Successful';

                } else {

                    $payment_status = 'Failed';

                }



            } else {

                $payment_status = 'Failed';

            }

        }



        $updatepaymentdata = array('status_api_response' => json_encode($status_api_response), 'payment_response' => json_encode($saveresponse), 'payment_status' => $payment_status);

        $PaymentModel = new PaymentModel();

        $PaymentModel->updateData('super_admin_payment_transaction', array('order_id' => $saveresponse['order_id']), $updatepaymentdata);

        return array('payment_status' => $payment_status, 'order_id' => $saveresponse['order_id'], 'amount' => $saveresponse['amount'], 'transaction_id' => $saveresponse['tracking_id']);

        //return $saveresponse;

    }



    public function check_status($order_no, $reference_no)

    {

        $merchant_json_data = array(

            'order_no' => $order_no,

            'reference_no' => $reference_no

        );



        $merchant_data = json_encode($merchant_json_data);

        $encrypted_data = HDFC::encrypt($merchant_data, $this->WorkingKey);

        $final_data = 'enc_request=' . $encrypted_data . '&access_code=' . $this->AccessCode . '&command=orderStatusTracker&request_type=JSON&response_type=JSON';

        $url = $this->APIStatusURL . "/apis/servlet/DoWebTrans";



        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $final_data);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);

        curl_close($ch);

        $status = '';

        $information = explode('&', $result);

        $dataSize = sizeof($information);

        for ($i = 0; $i < $dataSize; $i++) {

            $info_value = explode('=', $information[$i]);

            if ($info_value[0] == 'enc_response') {

                $status = HDFC::decrypt(trim($info_value[1]), $this->WorkingKey);

            }

        }

        return json_decode($status, true);

    }



    function encrypt($plainText, $key)

    {

        $key = HDFC::hextobin(md5($key));

        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);

        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);

        $encryptedText = bin2hex($openMode);

        return $encryptedText;

    }



    function decrypt($encryptedText, $key)

    {

        $key = HDFC::hextobin(md5($key));

        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);

        $encryptedText = HDFC::hextobin($encryptedText);

        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);

        return $decryptedText;

    }



    function pkcs5_pad($plainText, $blockSize)

    {

        $pad = $blockSize - (strlen($plainText) % $blockSize);

        return $plainText . str_repeat(chr($pad), $pad);

    }



    function hextobin($hexString)

    {

        $length = strlen($hexString);

        $binString = "";

        $count = 0;

        while ($count < $length) {

            $subString = substr($hexString, $count, 2);

            $packedString = pack("H*", $subString);

            if ($count == 0) {

                $binString = $packedString;

            } else {

                $binString .= $packedString;

            }

            $count += 2;

        }

        return $binString;

    }





}

