<?php
namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use App\Modules\Payment\Models\PaymentModel;

class CASHFREE extends BaseController
{
    public function __construct()
    {
        $this->gatewayInfo = json_decode(whitelabel['payment_gateway_setting'], true);
        $this->secretKey = $this->gatewayInfo['secret_key'];
        $this->appId = $this->gatewayInfo['app_id'];
        $this->PaymentBaseURL = $this->gatewayInfo['url'] . "/api/v1/order/create";
        $this->APIStatusURL = $this->gatewayInfo['url'] . "/api/v1/order/info/status";
    }
    public function request($request)
    {

        $tid = rand(1000, 99999999) . time();
        $order_id = 'PTTS' . rand(1000, 99999999) . time();
        $payment_option = get_cashfree_payment_mode($request['PaymentMode']);
        $card_type = $request['PaymentMode'];
        $data = array(
            "appId" => $this->appId,
            "secretKey" => $this->secretKey,
            "orderId" => $order_id,
            "orderAmount" => round($request['Amount'], 2),
            "orderCurrency" => $request['Currency'],
            "orderNote" => $request['Service'],
            "customerName" => $request['FirstName'] . ' ' . $request['LastName'],
            "customerPhone" => $request['MobileNumber'],
            "customerEmail" => $request['Email'],
            "returnUrl" => $request['RedirectURL'],
            "notifyUrl" => $request['NotifyURL'],
            "paymentModes" => $payment_option,

        );

        $payment_log = array(
            'web_partner_id' => $request['WebPartnerId'],
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
            'booking_ref_number' => $request['booking_ref_number'],
            'convenience_fee' => $request['convenience_fee'],
            'payment_mode' => $request['SavePaymentMode'],
            'payment_getway_name' => 'CASHFREE',
            'wl_agent_id' => $request['wl_agent_id'],
            'user_id' => $request['UserId'],
            'payment_source' => $request['PaymentSource'],
            'service_log' => $request['ServiceLog'],
            'created' => create_date()
        );
        //  pr($payment_log);
        //         die;
        $PaymentModel = new PaymentModel();
        $PaymentModel->insertData('web_partner_payment_transaction', $payment_log);
        $redirecturl = CASHFREE::Createorder($this->PaymentBaseURL, $data);
        $redirecturl = json_decode($redirecturl, true);
        if ($redirecturl['status'] == "OK" && isset($redirecturl['paymentLink'])) {
            $response = [
                'Error' => array("ErrorCode" => 0, "ErrorMessage" => ''),
                'Result' => array("paymentLink" => $redirecturl['paymentLink'])
            ];
            return $response;
        } else {
            $errorMessage = isset($redirecturl['reason']) ? $redirecturl['reason'] : "Error";
            return $response = [
                'Error' => array("ErrorCode" => 101, "ErrorMessage" => $errorMessage)
            ];
        }
    }

    public function response($response)
    {
        $orderId = $response["orderId"];
        $orderAmount = $response["orderAmount"];
        $referenceId = $response["referenceId"];
        $txStatus = $response["txStatus"];
        $paymentMode = $response["paymentMode"];
        $txMsg = $response["txMsg"];
        $txTime = $response["txTime"];
        $signature = $response["signature"];
        $signaturedatadata = $orderId . $orderAmount . $referenceId . $txStatus . $paymentMode . $txMsg . $txTime;
        $hash_hmac = hash_hmac('sha256', $signaturedatadata, $this->secretKey, true);
        $computedSignature = base64_encode($hash_hmac);


        $payment_status = '';
        if ($computedSignature == $response['signature'] and $response['txStatus'] == "SUCCESS") {
            $payment_status = 'Successful';
        } else {
            $payment_status = 'Failed';
        }

        $status_api_response = CASHFREE::check_status($response['orderId']);
        if ($status_api_response['status']) {
            if ($status_api_response['status'] == 'OK' && $status_api_response['orderStatus'] == 'PAID') {
                $payment_status = 'Successful';
            } else {
                $payment_status = 'Failed';
            }
        }

        $updatepaymentdata = array('status_api_response' => json_encode($status_api_response), 'payment_response' => json_encode($response), 'payment_status' => $payment_status);
        $PaymentModel = new PaymentModel();
        $PaymentModel->updateData('web_partner_payment_transaction', array('order_id' => $response["orderId"]), $updatepaymentdata);
        return array('payment_status' => $payment_status, 'order_id' => $response["orderId"], 'amount' => $response["orderAmount"]);
        //return $saveresponse;
    }

    public function check_status($order_no)
    {
        $merchant_json_data = array(
            "appId" => $this->appId,
            "secretKey" => $this->secretKey,
            "orderId" => $order_no,
        );
        $merchant_data = ($merchant_json_data);
        $url = $this->APIStatusURL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $merchant_data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    function Createorder($url, $requestData)
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $requestData,
            )
        );
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }


}