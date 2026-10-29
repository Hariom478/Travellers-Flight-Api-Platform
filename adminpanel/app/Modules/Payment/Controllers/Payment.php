<?php



namespace Modules\Payment\Controllers;



use App\Controllers\BaseController;

use Modules\Payment\Controllers\HDFC;

use App\Modules\Payment\Models\PaymentModel;



class Payment extends BaseController

{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)

    {

        parent::initController($request, $response, $logger);

        $this->title = "Payment";
        helper('Modules\Payment\Helpers\payment');
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];
        $this->web_partner_id = admin_Cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_class_id =  $this->admin_comapny_detail['web_partner_class_id'];
        $this->gateway_name =  "HDFC";
        $this->user_id = admin_Cookie_data()['admin_user_details']['id'];
    }



    public function index()
    {
        $uri = service('uri');
        $payment_token = trim($uri->getSegment(3));
        $paymentdata = json_decode(dev_decode($payment_token), true);
    pr($paymentdata);exit();
        if (!$paymentdata) {
            return view('template/custom-error-layout', ['error_message' => 'Payment Record not found']);
        }
        $PaymentModel = new PaymentModel();
        $service = $paymentdata['service'];
        $booking_id = $paymentdata['booking_id'];
        $booking_data = $PaymentModel->get_booking_detail($service, $booking_id, $this->web_partner_id);
        $total_price = $booking_data['total_price'];
        $convenience_fee_list = $PaymentModel->convenience_fee($service,$this->web_partner_class_id,$this->gateway_name,$total_price);
        if (!$booking_data) {
            return view('template/custom-error-layout', ['error_message' => 'Record not found']);

        }
        if (booking_time_out($booking_data['created'])) {
            return view('template/custom-error-layout', ['error_message' => 'Booking session expired']);
        }
        
        $data = [
            'title' => $this->title,
            'convenience_fee_list' => $convenience_fee_list,
            'total_price' => $total_price,
            'service' => $service,
            'booking_id' => $booking_id,
            'payment_token' => $payment_token,
            'view' => "Payment\Views\index",
        ];
        return view('template/default-layout', $data);
    }

    public function flightPayment()
    {
        $uri = service('uri');
        $payment_token = trim($uri->getSegment(3));
        $paymentdata = json_decode(dev_decode($payment_token), true);
        if (!$paymentdata) {
            return view('template/custom-error-layout', ['error_message' => 'Payment Record not found']);
        }
        $service = $paymentdata['service'];
        $booking_id = $paymentdata['booking_id'];
        $SearchTokenId = $paymentdata['SearchTokenId'];
        $PaymentModel = new PaymentModel();
        
        $booking_data = $PaymentModel->get_flight_booking_detail($service, $booking_id, $this->web_partner_id, $SearchTokenId);
        if (empty($booking_data)) {
            return view('template/custom-error-layout', ['error_message' => 'Record not found']);
        }
        if (booking_time_out($booking_data['OB']['created'])) {
            return view('template/custom-error-layout', ['error_message' => 'Booking session expired']);
        }
        $total_price = $booking_data['OB']['total_price'];
        if (isset($booking_data['IB']['total_price'])) {
            $total_price = $total_price + $booking_data['IB']['total_price'];
        }
        $convenience_fee_list = $PaymentModel->convenience_fee($service,$this->web_partner_class_id,$this->gateway_name,$total_price);
        $data = [
            'title' => $this->title,
            'convenience_fee_list' => $convenience_fee_list,
            'total_price' => $total_price,
            'service' => $service,
            'booking_id' => $booking_id,
            'payment_token' => $payment_token,
            'booking_data' => $booking_data,
            'search_token_id' => $SearchTokenId,
            'view' => "Payment\Views/flight_payment",
        ];
        return view('template/default-layout', $data);
    }
    public function proceed_payment()
    {
        $uri = service('uri');
        $payment_token = trim($uri->getSegment(3));
        $paymentdata = json_decode(dev_decode($payment_token), true);
        if (!$paymentdata) {
            return view('template/custom-error-layout', ['error_message' => 'Payment Record not found']);
        }
        $service = $paymentdata['service'];
        $booking_id = $paymentdata['id'];
        $payment_mode = $paymentdata['mode'];
        $payable_amount = $paymentdata['fare'];
        $PaymentModel = new PaymentModel();
        $booking_data = $PaymentModel->get_booking_detail($service, $booking_id, $this->web_partner_id);
        if (!$booking_data) {
            return view('template/custom-error-layout', ['error_message' => 'Record not found']);
        }
        if (booking_time_out($booking_data['created'])) {
            return view('template/custom-error-layout', ['error_message' => 'Booking session expired']);
        }
        $first_name = $booking_data['first_name'];
        $last_name = $booking_data['last_name'];
        $email_id = $booking_data['email_id'];
        $mobile_number = $booking_data['mobile_number'];
        $service_log = $booking_data['service_log'];
        if ($payment_mode == 'wallet') {
            return Payment::wallet($payable_amount, $booking_id, $service, $service_log);
        } else {
            $PaymentModel = new PaymentModel();
            $paymentrecord = $PaymentModel->checkpayment_record('super_admin_payment_transaction', ['web_partner_id' => $this->web_partner_id, 'user_id' => $this->user_id, 'service' => $service, 'booking_ref_no' => $booking_id]);
            if (empty($paymentrecord)) {
                $creditArray = array(
                    "RuPayCreditCard" => "rupay_credit_card",
                    "AmericanExpressCreditCard" => "american_express_credit_card",
                    "MastercardCreditCard" => "mastercard_credit_card",
                    "VisaCreditCard" => "visa_credit_card",
                );
                $credit_card_name = "";
                if ($paymentdata['mode'] == "CRDC") {
                    $credit_card_name = get_card_name($creditArray[$paymentdata['card_name']]);
                }
                $convenience_fee = $paymentdata['cfee'];
                $SavePaymentMode = get_payment_mode($paymentdata['mode']);
                $request = array(
                    'FirstName' => $first_name,
                    'LastName' => $last_name,
                    'Email' => $email_id,
                    'MobileNumber' => $mobile_number,
                    'Currency' => 'INR',
                    'RedirectURL' => site_url('payment/response'),
                    'CancelURL' => site_url('payment/response'),
                    'PaymentMode' => $payment_mode,
                    'Amount' => $payable_amount,
                    'Service' => $service,
                    'BookingId' => $booking_id,
                    'convenience_fee' => $convenience_fee,
                    'SavePaymentMode' => $SavePaymentMode,
                    'BookingId' => $booking_id,
                    'UserId' => $this->user_id,
                    'WebPartnerId' => $this->web_partner_id,
                    'ServiceLog' => $service_log,
                    'CardName' => $credit_card_name,
                    'Udf1' => $service,
                    'Udf2' =>  $this->admin_comapny_detail['company_name'],
                    'Udf3' =>"",
                    'Udf4' =>"",
                    'Udf5' => $SavePaymentMode
                );
                $HDFC = new HDFC();
                return $HDFC->request($request);
            } else {

                $url_param = http_build_query(array('message' => 'Booking already in process or booking done with same details', 'reference-no' => $booking_id));

                return redirect()->to(site_url('payment/payment-error?' . $url_param));

            }

        }
    }
    public function flight_proceed_payment()
    {
        $uri = service('uri');
        $payment_token = trim($uri->getSegment(3));
        $paymentdata = json_decode(dev_decode($payment_token), true);
        if (!$paymentdata) {
            return view('template/custom-error-layout', ['error_message' => 'Payment Record not found']);
        }
        $service = $paymentdata['service'];
        $booking_id = $paymentdata['id'];
        $payment_mode = $paymentdata['mode'];
        $payable_amount = $paymentdata['fare'];
        $SearchTokenId = $paymentdata['search_token_id'];
        $PaymentModel = new PaymentModel();
        $booking_data = $PaymentModel->get_flight_booking_detail($service, $booking_id, $this->web_partner_id, $SearchTokenId);
        if (empty($booking_data)) {
            return view('template/custom-error-layout', ['error_message' => 'Record not found']);
        }
        if (booking_time_out($booking_data['OB']['created'])) {
            return view('template/custom-error-layout', ['error_message' => 'Booking session expired']);
        }
        $bookingids = array_values($paymentdata['id']);
        $booking_id = implode(',', $bookingids);
        $first_name = $booking_data['OB']['first_name'];
        $last_name = $booking_data['OB']['last_name'];
        $email_id = $booking_data['OB']['email_id'];
        $mobile_number = $booking_data['OB']['mobile_number'];
        $flightbooking_ref_number  =  array_column($booking_data,"booking_ref_number");
        $flightbooking_ref_number = implode(",", $flightbooking_ref_number);
        $service_log = json_encode(array('PaxName' => $first_name . " " . $last_name, 'Sector' => $booking_data['OB']['origin'] . '-' . $booking_data['OB']['destination'] . '/ JourneyType' . $booking_data['OB']['journey_type'], 'TravelDate' => $booking_data['OB']['departure_date']));
        if ($payment_mode == 'wallet') {
            return Payment::FlightWallet($payable_amount, $booking_id);
        } else {
            $PaymentModel = new PaymentModel();
            $paymentrecord = $PaymentModel->checkpayment_record('super_admin_payment_transaction', ['web_partner_id' => $this->web_partner_id, 'user_id' => $this->user_id, 'service' => $service, 'booking_ref_no' => $booking_id]);
            if (empty($paymentrecord)) {
                $creditArray = array(
                    "RuPayCreditCard" => "rupay_credit_card",
                    "AmericanExpressCreditCard" => "american_express_credit_card",
                    "MastercardCreditCard" => "mastercard_credit_card",
                    "VisaCreditCard" => "visa_credit_card",
                );
                $credit_card_name = "";
                if ($paymentdata['mode'] == "CRDC") {
                    $credit_card_name = get_card_name($creditArray[$paymentdata['card_name']]);
                }
                $convenience_fee = $paymentdata['cfee'];
                $SavePaymentMode = get_payment_mode($paymentdata['mode']);
                $request = array(
                    'FirstName' => $first_name,
                    'LastName' => $last_name,
                    'Email' => $email_id,
                    'MobileNumber' => $mobile_number,
                    'Currency' => 'INR',
                    'RedirectURL' => site_url('payment/response'),
                    'CancelURL' => site_url('payment/response'),
                    'PaymentMode' => $payment_mode,
                    'Amount' => $payable_amount,
                    'Service' => $service,
                    'BookingId' => $booking_id,
                    'UserId' => $this->user_id,
                    'convenience_fee' => $convenience_fee,
                    'SavePaymentMode' => $SavePaymentMode,
                    'WebPartnerId' => $this->web_partner_id,
                    'ServiceLog' => $service_log,
                    'CardName' => $credit_card_name,
                    'Udf1' => $service,
                    'Udf2' =>  $this->admin_comapny_detail['company_name'],
                    'Udf3' =>"",
                    'Udf4' =>"",
                    'Udf5' => $SavePaymentMode
                );
                $HDFC = new HDFC();
                return $HDFC->request($request);
            } else {
                $url_param = http_build_query(array('message' => 'Booking already in process or booking done with same details', 'reference-no' => $flightbooking_ref_number));
                return redirect()->to(site_url('payment/payment-error?' . $url_param));
            }
        }
    }
    protected function FlightWallet($PayableAmount, $booking_id)
    {
        $return_url = site_url('flight/payment-status/');
        $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id, 'PayableAmount' => $PayableAmount)));
        return redirect()->to($return_url);
    }
    protected function wallet($PayableAmount, $booking_id, $service, $service_log)
    {
        $walletbalance = get_balance();
        if ($walletbalance >= $PayableAmount) {
            $PaymentModel = new PaymentModel();
            $IsDomestic  =  1;
            $paymentrecord = $PaymentModel->checkpayment_record('web_partner_account_log', ['booking_ref_no' => $booking_id, 'service' => $service, 'transaction_type' => 'debit']);
            if (empty($paymentrecord)) {
                $service_log =  json_decode($service_log,true);
                if(isset($service_log['IsDomestic'])){
                    $IsDomestic  =  $service_log['IsDomestic'];
                    unset($service_log['IsDomestic']);
                    $service_log =  json_encode($service_log);
                }else{
                    $service_log =  json_encode($service_log); 
                }
              
                $balance = $walletbalance - $PayableAmount;
                $web_partner_account_log = array(
                    'web_partner_id' => $this->web_partner_id,
                    'debit' => $PayableAmount,
                    'balance' => round_value($balance),
                    'remark' => 'Ticket Created Through Portal',
                    'service' => $service,
                    'service_log' => $service_log,
                    'transaction_type' => 'debit',
                    'booking_ref_no' => $booking_id,
                    'action_type' => 'booking',
                    'created' => create_date()
                );
                $account_log_lastid = $PaymentModel->insertData('web_partner_account_log', $web_partner_account_log);
                if ($account_log_lastid) {
                    $payment_status = 'Successful';
                } else {
                    $payment_status = 'Failed';
                }
                
                $acc_ref_number = reference_number($account_log_lastid,ucfirst($service),$IsDomestic);
                $account_update_data = array('acc_ref_number' => $acc_ref_number);
                $PaymentModel->updateData('web_partner_account_log', ['id' => $account_log_lastid], $account_update_data);
                $updatepaymentdata = array('payment_mode' => 'API_Wallet', 'payment_status' => $payment_status);
                if ($service == 'bus') {
                    $PaymentModel->updateData('bus_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('bus/payment-status/');
                }
                if ($service == 'hotel') {
                    $PaymentModel->updateData('hotel_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('hotel/payment-status/');
                }
                if ($service == 'visa') {
                    $PaymentModel->updateData('visa_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('visa/payment-status/');
                }
                if ($service == 'car') {
                    $PaymentModel->updateData('car_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('car/payment-status/');
                }
                if ($service == 'holiday') {
                    $PaymentModel->updateData('holiday_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('holiday/payment-status/');
                }
                if ($service == 'cruise') {
                    $PaymentModel->updateData('cruise_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('cruise/payment-status/');
                }
                $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id)));
                return redirect()->to($return_url);
            } else {
                $url_param = http_build_query(array('message' => 'Booking already in process or booking done with same details', 'reference-no' => $booking_id));
                return redirect()->to(site_url('payment/payment-error?' . $url_param));
            }
        } else {
            $url_param = http_build_query(array('message' => 'Agency account do not have sufficient balance', 'reference-no' => $booking_id));
            return redirect()->to(site_url('payment/payment-error?' . $url_param));
        }
    }
    public function response()
    {
        $method = strtoupper($this->request->getMethod());
        if ($method == 'POST') {
            $response = $response = $this->request->getPost();
            $HDFC = new HDFC();
            $payment_response = $HDFC->response($response);
            $PaymentModel = new PaymentModel();
            $payment_request = $PaymentModel->get_payment_detail($payment_response['order_id']);
            $booking_id = $payment_request['booking_ref_no'];
            $bookingIdrefrenceNumber = $booking_id;
            $service = $payment_request['service'];
            $service_log = $payment_request['service_log'];
            if($service=="flight"){
                $flightbookingIdrefrenceNumbers  =  explode(",", $booking_id);
                $flightBookingrefrennumberArray  =  array();
                if(!is_array($flightbookingIdrefrenceNumbers))
                {
                    $flightbookingIdrefrenceNumbers =  array($flightbookingIdrefrenceNumbers);}
                    if($flightbookingIdrefrenceNumbers){
                    foreach($flightbookingIdrefrenceNumbers as $bookingrefrenceNumber){
                        $flightBookingrefrennumberArray[] =  $payment_request['booking_prefix'].$bookingrefrenceNumber;
                    }
                    $bookingIdrefrenceNumber =  implode(",",$flightBookingrefrennumberArray);
                                        }
            }
            if ($payment_response['payment_status'] == 'Successful') {
                $gateway_response = json_decode($payment_request['payment_response'],true);
            $checkTransaction  =  $PaymentModel->checkWalletRecharge($this->web_partner_id,$gateway_response['tracking_id']);
                if($checkTransaction) {
                $amount = $payment_response['amount'] - $payment_request['convenience_fee'];
                $walletbalance = get_balance();
                $balance = $walletbalance + $amount;
                $web_partner_account_log = array(
                    'web_partner_id' => $this->web_partner_id,
                    'credit' => $amount,
                    'balance' => round_value($balance),
                    'remark' => 'Online Booking Topup',
                    'transaction_id' => $payment_response['transaction_id'],
                    'payment_transaction_id' => $payment_request['id'],
                    'payment_mode' => $payment_request['payment_mode'],
                    'transaction_type' => 'credit',
                    'action_type' => 'recharge',
                    'created' => create_date()
                );
                $PaymentModel->insertData('web_partner_account_log', $web_partner_account_log);
                if ($service == "flight") {
                    return Payment::FlightWallet($amount, $booking_id);
                } else {
                    return Payment::wallet($amount, $booking_id, $service, $service_log);
                }
            }
            else{
                $url_param = http_build_query(array('message' => 'Something is Wrong', 'reference-no' => $bookingIdrefrenceNumber));
                return redirect()->to(site_url('payment/payment-error?' . $url_param));
            }
            } else {
                $url_param = http_build_query(array('message' => 'Payment is failed', 'reference-no' => $bookingIdrefrenceNumber));
                return redirect()->to(site_url('payment/payment-error?' . $url_param));
            }
            $data = [
                'title' => $this->title,
                'view' => "Payment\Views\payment-loading",
            ];
            return view('template/default-layout', $data);
        }
    }
    public function payment_error()
    {
        $message = $this->request->getVar('message');
        $reference_no = $this->request->getVar('reference-no');
        $data = [
            'title' => 'Payment Error',
            'message' => $message,
            'reference_no' => $reference_no,
            'view' => "Payment\Views\payment-error",
        ];
        return view('template/default-layout', $data);
    }
    public function makePayment()
    {
        $uri = service('uri');
        $payment_token = trim($uri->getSegment(3));
        $paymentdata = dev_decode($payment_token);
        if (!$paymentdata) {
            return view('template/custom-error-layout', ['error_message' => 'Payment Record not found']);
        }
        $PaymentModel = new PaymentModel();
        $whereCondition = array("id" => $paymentdata, "status" => "pending", "web_partner_id" => $this->web_partner_id);
        $makePaymeninfo = $PaymentModel->getMakePaymentDetails($whereCondition);
        if (!$makePaymeninfo) {
            return view('template/custom-error-layout', ['error_message' => 'Payment Record not found']);
        }
        $first_name = $this->web_partner_details['first_name'];
        $last_name = $this->web_partner_details['last_name'];
        $email_id = $this->web_partner_details['login_email'];
        $mobile_number = $this->web_partner_details['mobile_no'];
        $paymentModeArray = array(
            "RuPayCreditCard" => "CRDC",
            "AmericanExpressCreditCard" => "CRDC",
            "MastercardCreditCard" => "CRDC",
            "VisaCreditCard" => "CRDC",
            "CreditCard" => "CRDC",
            "DebitCard" => "DBCRD",
            "NetBanking" => "NBK",
            "UPIPayments" => "UPI"
        );
        $convenienceFeesModeArray = array(
            "RuPayCreditCard" => "rupay_credit_card",
            "AmericanExpressCreditCard" => "american_express_credit_card",
            "MastercardCreditCard" => "mastercard_credit_card",
            "VisaCreditCard" => "visa_credit_card",
            "DebitCard" => "debit_card",
            "NetBanking" => "net_banking",
            "UPIPayments" => "mobile_wallet"
        );
        $SavePaymentMode = get_payment_mode($paymentModeArray[$makePaymeninfo['payment_mode']]);
        $convenience_fee_list = $PaymentModel->convenience_fee("Make_Payment",$this->web_partner_class_id,$this->gateway_name,$makePaymeninfo['amount']);
        $convenienceFees_data = calculate_convenience_fee($convenience_fee_list, $convenienceFeesModeArray[$makePaymeninfo['payment_mode']], $makePaymeninfo['amount']);
        $request = array(
            'FirstName' => $first_name,
            'LastName' => $last_name,
            'Email' => $email_id,
            'MobileNumber' => $mobile_number,
            'Currency' => 'INR',
            'RedirectURL' => site_url('payment/makepaymentesponse'),
            'CancelURL' => site_url('payment/makepaymentesponse'),
            'PaymentMode' => $paymentModeArray[$makePaymeninfo['payment_mode']],
            'Amount' => $makePaymeninfo['amount'] + $convenienceFees_data['conveniencefee'],
            'convenience_fee' => $convenienceFees_data['conveniencefee'],
            'SavePaymentMode' => $SavePaymentMode,
            'Service' => "Make_Payment",
            'CardName' => get_card_name($convenienceFeesModeArray[$makePaymeninfo['payment_mode']]),
            'BookingId' => $paymentdata,
            'UserId' => $this->user_id,
            'WebPartnerId' => $this->web_partner_id,
            'ServiceLog' => "",
            'Udf1' => "Upload",
            'Udf2' =>  $this->admin_comapny_detail['company_name'],
            'Udf3' =>"",
            'Udf4' =>"",
            'Udf5' => $SavePaymentMode

        );
        $HDFC = new HDFC();
        return $HDFC->request($request);
    }
    public function makepaymentesponse()
    {
        $method = strtoupper($this->request->getMethod());
        if ($method == 'POST') {

            $response =  $this->request->getPost();

            $HDFC = new HDFC();

            $payment_response = $HDFC->response($response);



            $PaymentModel = new PaymentModel();

            $payment_request = $PaymentModel->get_payment_detail($payment_response['order_id']);



            $make_payment_id = $payment_request['booking_ref_no'];

            $service = $payment_request['service'];

            $service_log = $payment_request['service_log'];
            $gateway_response = json_decode($payment_request['payment_response'],true);
            if ($payment_response['payment_status'] == 'Successful') {
             $checkTransaction  =  $PaymentModel->checkWalletRecharge($this->web_partner_id,$gateway_response['tracking_id']);
                if($checkTransaction) {
         

                $amount = $payment_response['amount'] - $payment_request['convenience_fee'];

                $walletbalance = get_balance();

                $balance = $walletbalance + $amount;



                $web_partner_account_log = array(

                    'web_partner_id' => $this->web_partner_id,

                    'credit' => $amount,

                    'balance' => round_value($balance),

                    'remark' => 'Online Make Payment Topup',

                    'service' => "Make_Payment",

                    'transaction_id' => $payment_response['transaction_id'],

                    'payment_transaction_id' => $payment_request['id'],

                    'payment_mode' => $payment_request['payment_mode'],

                    'transaction_type' => 'credit',

                    'action_type' => 'recharge',

                    'created' => create_date()

                );

                $PaymentModel->insertData('web_partner_account_log', $web_partner_account_log);

                $PaymentModel->updateData("web_partner_make_payment", array("id" => $make_payment_id), array("status" => "approved",'pg_transaction_id'=>$payment_response['transaction_id'],"pg_order_id"=>$payment_response['order_id']));

                $message = array("StatusCode" => 0, "Message" => "Make payment request successfully created, Transaction id : " . $payment_response['transaction_id'], "Class" => "success_popup");

                $Redirect_Url = site_url('accounts/payment-processing');

                $this->session->setFlashdata('Message', $message);

                return redirect()->to($Redirect_Url);
            }   else{
               
                $url_param = http_build_query(array('message' => 'Something is Wrong', 'reference-no' => $make_payment_id));
                return redirect()->to(site_url('payment/payment-error?' . $url_param));
            }

            } else {

                $PaymentModel->updateData("web_partner_make_payment", array("id" => $make_payment_id), array("status" => "rejected",'pg_transaction_id'=>$payment_response['transaction_id'],"pg_order_id"=>$payment_response['order_id']));

                $url_param = http_build_query(array('message' => 'Payment is failed', 'reference-no' => $make_payment_id));

                return redirect()->to(site_url('payment/payment-error?' . $url_param));

            }



            $data = [

                'title' => $this->title,

                'view' => "Payment\Views\payment-loading",

            ];

            return view('template/default-layout', $data);

        }

    }

}