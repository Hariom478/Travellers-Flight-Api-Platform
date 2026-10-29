<?php

namespace Modules\Payment\Controllers;

use App\Controllers\BaseController;
use Modules\Payment\Controllers\HDFC;
use Modules\Payment\Controllers\CASHFREE;
use App\Modules\Payment\Models\PaymentModel;


class Payment extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Payment";
        helper('Modules\Payment\Helpers\payment');
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->web_partner_class_id = web_partner_details['web_partner_class_id'];
        $this->payment_gateway_type = trim(whitelabel['payment_gateway_name']);
        $this->user_id = session()->get('wl_agent')['id'];
        $this->wl_agent_id = session()->get('wl_agent')['agent_id'];
        $this->wl_agent_details = session()->get('wl_agent');
        $this->booking_source = "Wl_b2b";
        $this->payment_gateway_name = whitelabel['payment_gateway_name'];
    }

    public function index()
    {
        $uri = service('uri');
        $payment_token = trim($uri->getSegment(3));
        $paymentdata = json_decode(dev_decode($payment_token), true);
        if (!$paymentdata) {
            return view('template/custom-error-layout', ['error_message' => 'Payment Record not found']);
        }
        $service = $paymentdata['service'];
        $booking_id = $paymentdata['booking_id'];
        $SearchTokenId = "";
        $PaymentModel = new PaymentModel();
        $convenience_fee_list = $PaymentModel->convenience_fee($service, $this->web_partner_class_id, $this->web_partner_id, $this->payment_gateway_type, $this->payment_gateway_name);
        if ($service == "flight") {
            $SearchTokenId = $paymentdata['SearchTokenId'];
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
        } else {
            $booking_data = $PaymentModel->get_booking_detail($service, $booking_id, $this->web_partner_id);
            if (!$booking_data) {
                return view('template/custom-error-layout', ['error_message' => 'Record not found']);
            }
            if (booking_time_out($booking_data['created'])) {
                return view('template/custom-error-layout', ['error_message' => 'Booking session expired']);
            }

            $total_price = $booking_data['total_price'];
        }
        $data = [
            'title' => $this->title,
            'convenience_fee_list' => $convenience_fee_list,
            'total_price' => $total_price,
            'service' => $service,
            'booking_id' => $booking_id,
            'payment_token' => $payment_token,
            'booking_data' => $booking_data,
            'search_token_id' => $SearchTokenId,
            'view' => "Payment\Views\index",
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
        if ($service == "flight") {
            $booking_id = $paymentdata['id'];
            $payment_mode = $paymentdata['mode'];
            $payable_amount = $paymentdata['fare'];
            $SearchTokenId = $paymentdata['search_token_id'];
            $PaymentModel = new PaymentModel();
            $booking_data = $PaymentModel->get_flight_booking_detail($service, $booking_id, $this->web_partner_id, $SearchTokenId);

            $bookingPrice = 0;

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
            $flightbooking_ref_number = array_column($booking_data, "booking_ref_number");
            $booking_ref_number = implode(",", $flightbooking_ref_number);
            $service_log = json_encode(array('PaxName' => $first_name . " " . $last_name, 'Sector' => $booking_data['OB']['origin'] . '-' . $booking_data['OB']['destination'] . '/ JourneyType' . $booking_data['OB']['journey_type'], 'TravelDate' => $booking_data['OB']['departure_date']));
            foreach ($booking_data as $booking_data) {
                $bookingPrice = $bookingPrice + $booking_data['total_price'];
            }
        } else {
            $booking_id = $paymentdata['id'];
            $payment_mode = $paymentdata['mode'];
            $payable_amount = $paymentdata['fare'];
            $PaymentModel = new PaymentModel();
            $booking_data = $PaymentModel->get_booking_detail($service, $booking_id, $this->web_partner_id);
            $bookingPrice = $booking_data['total_price'];
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
            $booking_ref_number = $booking_data['booking_ref_number'];
        }
        $webpartnerBalance = get_balance();
        if (isset($webpartnerBalance) && $webpartnerBalance < 0) {
            $url_param = http_build_query(array('message' => 'Please contact to  administrator', 'reference-no' => $booking_ref_number));
            return redirect()->to(site_url('payment/payment-error?' . $url_param));
        }
        if ($payment_mode == 'wallet') {
            $webpartnerBalance = get_balance();
            $walletbalance = wl_agent_get_balance();
            if ($webpartnerBalance >= $bookingPrice && $walletbalance > $bookingPrice) {
                if ($service == "flight") {
                    return Payment::FlightWallet($bookingids, array(), "Wallet");
                } else {
                    return Payment::wallet($booking_data['total_price'], $booking_id, $service, $service_log, "Wallet", 0);
                }
            } else {
                $url_param = http_build_query(array('message' => 'Please contact to  administrator', 'reference-no' => $booking_ref_number));
                return redirect()->to(site_url('payment/payment-error?' . $url_param));
            }
            $url_param = http_build_query(array('message' => 'Please contact to  administrator', 'reference-no' => $booking_ref_number));
            return redirect()->to(site_url('payment/payment-error?' . $url_param));
        } else {
            $PaymentModel = new PaymentModel();
            $paymentrecord = $PaymentModel->checkpayment_record('web_partner_payment_transaction', ['web_partner_id' => $this->web_partner_id, 'service' => $service, 'booking_ref_no' => $booking_id]);
            if (empty($paymentrecord)) {
                $credit_card_name = "";
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
                    'NotifyURL' => site_url('payment/notifyurl'),
                    'PaymentMode' => $payment_mode,
                    'Amount' => $payable_amount,
                    'Service' => $service,
                    'BookingId' => $booking_id,
                    'convenience_fee' => $convenience_fee,
                    'SavePaymentMode' => $SavePaymentMode,
                    'UserId' => $this->user_id,
                    'booking_ref_number' => $booking_ref_number,
                    'WebPartnerId' => $this->web_partner_id,
                    'ServiceLog' => $service_log,
                    'PaymentSource' => "Wl_b2b",
                    'wl_agent_id' => $this->wl_agent_id,
                    'CardName' => $credit_card_name,
                );
                $CASHFREE = new CASHFREE();
                $gatwayResponseData = $CASHFREE->request($request);
                if (isset($gatwayResponseData['Error']['ErrorCode']) && $gatwayResponseData['Error']['ErrorCode'] == 0) {
                    return redirect()->to($gatwayResponseData['Result']['paymentLink']);
                } else {
                    $Errormessag = $gatwayResponseData['Error']['ErrorMessage'];
                    $url_param = http_build_query(array('message' => $Errormessag, 'reference-no' => $booking_ref_number));
                    return redirect()->to(site_url('payment/payment-error?' . $url_param));
                }
            } else {
                $url_param = http_build_query(array('message' => 'Booking already in process or booking done with same details', 'reference-no' => $booking_ref_number));
                return redirect()->to(site_url('payment/payment-error?' . $url_param));
            }
        }
    }
    function notifyurl()
    {
    }


    protected function FlightWallet($booking_ids, $paymentInfo, $paymentBy)
    {
        $PayableAmount = isset($paymentInfo['PaidAmount']) ? $paymentInfo['PaidAmount'] : 0;
        if ($paymentBy == "Wallet") {
            $PaymentModel = new PaymentModel();
            $PayableAmount = 0;
            $booking_data = $PaymentModel->get_flight_booking_detail("flight", $booking_ids, $this->web_partner_id, "");
            foreach ($booking_data as $bookingInfo) {
                $first_name = $bookingInfo['first_name'];
                $last_name = $bookingInfo['last_name'];
                $PayableAmount = $bookingInfo['total_price'];
                $booking_id = $bookingInfo['id'];
                $service = "flight";
                $service_log = json_encode(array('PaxName' => $first_name . " " . $last_name, 'Sector' => $bookingInfo['origin'] . '-' . $bookingInfo['destination'] . '/ JourneyType' . $bookingInfo['journey_type'], 'TravelDate' => $bookingInfo['departure_date']));
                $extra_param = array();
                $agent_fare_break_up = json_decode($bookingInfo['agent_fare_break_up'], true);
                $extra_param['booking_ref_number'] = $bookingInfo['booking_ref_number'];
                $extra_param['agentBreakUpInfo'] = $agent_fare_break_up;
                $extra_param['webPartnerBreakUpInfo'] = json_decode($bookingInfo['web_partner_fare_break_up'], true);
                $extra_param['convenienceFee'] = isset($agent_fare_break_up['convenienceFee']) ? $agent_fare_break_up['convenienceFee'] : 0;
                $customerWalletAmountStatus = Payment::flight_agent_wallet($PayableAmount, $booking_id, $service, $service_log, $extra_param);
                if ($customerWalletAmountStatus['StatusCode'] != 0) {
                    return redirect()->to($customerWalletAmountStatus['Url']);
                }
            }
        }
        $booking_id = implode(",", $booking_ids);
        $return_url = site_url('flight/payment-status/');
        $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id, 'PayableAmount' => $PayableAmount)));
        return redirect()->to($return_url);
    }

    protected function wallet($PayableAmount, $booking_id, $service, $service_log, $paymentBy, $conveniencefee)
    {
        $walletbalanceAgent = wl_agent_get_balance();
        $walletbalance = get_balance();
        $PaymentModel = new PaymentModel();
        $booking_data = $PaymentModel->get_booking_detail($service, $booking_id, $this->web_partner_id);
        $agentPaidAmount = $booking_data['total_price'];
        $booking_ref_number = isset($booking_data['booking_ref_number']) ? $booking_data['booking_ref_number'] : $booking_id;
        $wepPartnerPayableAmount = $booking_data['web_partner_booking_total_price'];

        if ($walletbalance >= $wepPartnerPayableAmount && $walletbalanceAgent >= $agentPaidAmount) {
            $extra_param = array();
            $agent_fare_break_up = json_decode($booking_data['agent_fare_break_up'], true);
            $web_partner_fare_break_up = json_decode($booking_data['web_partner_fare_break_up'], true);
            $extra_param['booking_ref_number'] = $booking_data['booking_ref_number'];
            $extra_param['agentBreakUpInfo'] = $agent_fare_break_up;
            $extra_param['webPartnerBreakUpInfo'] = $web_partner_fare_break_up;
            $extra_param['convenienceFee'] = $conveniencefee;
            $PaymentModel = new PaymentModel();
            $paymentrecord = $PaymentModel->checkpayment_record('agent_account_log', ['booking_ref_no' => $booking_id, 'service' => $service, 'transaction_type' => 'debit']);
            if (empty($paymentrecord)) {
                $balance = $walletbalanceAgent - $agentPaidAmount;
                $web_partner_account_log = array(
                    'web_partner_id' => $this->web_partner_id,
                    'wl_agent_id' => $this->wl_agent_id,
                    'wl_agent_staff_id' => $this->user_id,
                    'debit' => $agentPaidAmount,
                    'balance' => $balance,
                    'remark' => 'Ticket Created Through Portal',
                    'service' => $service,
                    'service_log' => $service_log,
                    'extra_param' => json_encode($extra_param),
                    'transaction_type' => 'debit',
                    'booking_ref_no' => $booking_id,
                    'action_type' => 'booking',
                    'payment_mode' => 'wallet',
                    'created' => create_date()
                );
                $account_log_lastid = $PaymentModel->insertData('agent_account_log', $web_partner_account_log);
                if ($account_log_lastid) {
                    $payment_status = 'Successful';
                } else {
                    $payment_status = 'Failed';
                }
                $acc_ref_number = reference_number($account_log_lastid);
                $account_update_data = array('acc_ref_number' => $acc_ref_number);
                $PaymentModel->updateData('agent_account_log', ['id' => $account_log_lastid], $account_update_data);
                $updatepaymentdata = array('payment_mode' => 'Wallet', 'payment_status' => $payment_status, "conveniencefee" => $conveniencefee, "web_partner_fare_break_up" => json_encode($web_partner_fare_break_up), "agent_fare_break_up" => json_encode($agent_fare_break_up));
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


                if ($service == 'tourguide') {
                    $PaymentModel->updateData('tourguide_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('tourguide/payment-status/');
                }

                if ($service == 'activities') {
                    $PaymentModel->updateData('activities_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    $return_url = site_url('activities/payment-status/');
                }

                $return_url = $return_url . dev_encode(json_encode(array('booking_id' => $booking_id)));
                return redirect()->to($return_url);
            } else {
                $url_param = http_build_query(array('message' => 'Booking already in process or booking done with same details', 'reference-no' => $booking_ref_number));
                return redirect()->to(site_url('payment/payment-error?' . $url_param));
            }
        } else {
            $url_param = http_build_query(array('message' => 'Please contact to  administrator', 'reference-no' => $booking_ref_number));
            return redirect()->to(site_url('payment/payment-error?' . $url_param));
        }
    }
    protected function flight_agent_wallet($PayableAmount, $booking_id, $service, $service_log, $extra_param)
    {
        $walletbalance = wl_agent_get_balance();

        if ($walletbalance >= $PayableAmount) {
            $PaymentModel = new PaymentModel();
            $paymentrecord = $PaymentModel->checkpayment_record('agent_account_log', ['booking_ref_no' => $booking_id, 'service' => $service, 'transaction_type' => 'debit']);
            if (empty($paymentrecord)) {
                $balance = $walletbalance - $PayableAmount;
                $agent_account_log = array(
                    'web_partner_id' => $this->web_partner_id,
                    'wl_agent_id' => $this->wl_agent_id,
                    'debit' => $PayableAmount,
                    'balance' => $balance,
                    'remark' => 'Ticket Created Through Portal',
                    'service' => $service,
                    'service_log' => $service_log,
                    'transaction_type' => 'debit',
                    'booking_ref_no' => $booking_id,
                    'action_type' => 'booking',
                    'payment_mode' => 'wallet',
                    'wl_agent_staff_id' => $this->user_id,
                    'extra_param' => json_encode($extra_param),
                    'created' => create_date()
                );

                $account_log_lastid = $PaymentModel->insertData('agent_account_log', $agent_account_log);
                if ($account_log_lastid) {
                    $payment_status = 'Successful';
                } else {
                    $payment_status = 'Failed';
                }
                $acc_ref_number = reference_number($account_log_lastid);
                $account_update_data = array('acc_ref_number' => $acc_ref_number);
                $PaymentModel->updateData('agent_account_log', ['id' => $account_log_lastid], $account_update_data);
                $updatepaymentdata = array('payment_mode' => 'Wallet', 'payment_status' => $payment_status);
                $PaymentModel->updateData('flight_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                $url = "";
                if ($payment_status == 'Failed') {
                    $url_param = http_build_query(array('message' => 'Your wallet do not have sufficient balance', 'reference-no' => $booking_id));
                    $url = site_url('payment/payment-error?' . $url_param);
                    return array("StatusCode" => 1, "Url" => $url);
                }
                return array("StatusCode" => 0, "Url" => $url);
            } else {
                $url_param = http_build_query(array('message' => 'Booking already in process or booking done with same details', 'reference-no' => $booking_id));
                $url = site_url('payment/payment-error?' . $url_param);
                return array("StatusCode" => 1, "Url" => $url);
            }
        } else {
            $url_param = http_build_query(array('message' => 'Your wallet do not have sufficient balance', 'reference-no' => $booking_id));
            $url = site_url('payment/payment-error?' . $url_param);
            return array("StatusCode" => 1, "Url" => $url);
        }
    }


    public function response()
    {
        $method = strtoupper($this->request->getMethod());
        if ($method == 'POST') {
            $response = $response = $this->request->getPost();
            $CASHFREE = new CASHFREE();
            $payment_response = $CASHFREE->response($response);
            $PaymentModel = new PaymentModel();
            $payment_request = $PaymentModel->get_payment_detail($payment_response['order_id']);
            $booking_id = $payment_request['booking_ref_no'];
            $bookingIdrefrenceNumber = $booking_id;
            $service = $payment_request['service'];
            $service_log = $payment_request['service_log'];
            $bookingIdrefrenceNumber = $payment_request['booking_ref_number'];
            $extra_param['booking_ref_number'] = $bookingIdrefrenceNumber;
            if ($service == "flight") {
                $flightbookingIdrefrenceNumbers = explode(",", $booking_id);
                if (!is_array($flightbookingIdrefrenceNumbers)) {
                    $flightbookingIdrefrenceNumbers = array($flightbookingIdrefrenceNumbers);
                }
            }
            $extra_param['convenienceFee'] = $payment_request['convenience_fee'];
            if ($payment_response['payment_status'] == 'Successful') {
                $gateway_response = json_decode($payment_request['payment_response'], true);
                $checkTransaction = $PaymentModel->checkWalletRecharge($this->web_partner_id, $gateway_response['orderId']);
                if ($checkTransaction) {
                    $amount = $payment_response['amount'] - $payment_request['convenience_fee'];
                    $walletbalance = get_balance();
                    $balance = $walletbalance + $amount;

                    if ($this->payment_gateway_name == "Default") {
                        $web_partner_account_log = array(
                            'web_partner_id' => $this->web_partner_id,
                            'credit' => $amount,
                            'balance' => $balance,
                            'remark' => 'Online Booking Topup',
                            'transaction_id' => $payment_response['order_id'],
                            'payment_transaction_id' => $payment_request['id'],
                            'payment_mode' => $payment_request['payment_mode'],
                            'transaction_type' => 'credit',
                            'action_type' => 'recharge',
                            'service' => $payment_request['service'],
                            'extra_param' => json_encode($extra_param),
                            'booking_ref_no' => $payment_request['booking_ref_no'],
                            'created' => create_date()
                        );
                        $PaymentModel->insertData('web_partner_account_log', $web_partner_account_log);
                    }
                    $agentwalletbalance = wl_agent_get_balance();
                    $agentbalance = $agentwalletbalance + $amount;

                    $agent_account_log = array(
                        'web_partner_id' => $this->web_partner_id,
                        'wl_agent_id' => $this->wl_agent_id,
                        'wl_agent_staff_id' => $this->user_id,
                        'credit' => $amount,
                        'balance' => $agentbalance,
                        'remark' => 'Online Booking Topup',
                        'transaction_id' => $payment_response['order_id'],
                        'payment_transaction_id' => $payment_request['id'],
                        'payment_mode' => $payment_request['payment_mode'],
                        'transaction_type' => 'credit',
                        'service' => $payment_request['service'],
                        'booking_ref_no' => $payment_request['booking_ref_no'],
                        'action_type' => 'recharge',
                        'extra_param' => json_encode($extra_param),
                        'created' => create_date()
                    );

                    $PaymentModel->insertData('agent_account_log', $agent_account_log);
                    $paymentInfo = array("PaidAmount" => $payment_response['amount'], "convenience_fee" => $payment_request['convenience_fee'], "payment_status" => $payment_request['payment_status']);
                    if ($service == "flight") {
                        $booking_data = $PaymentModel->get_flight_booking_detail("flight", $flightbookingIdrefrenceNumbers, $this->web_partner_id, "");
                        $NumberofBooking = count($flightbookingIdrefrenceNumbers);
                        foreach ($booking_data as $bookingInfo) {
                            $applyconvenience_fee = round_value(($payment_request['convenience_fee'] / $NumberofBooking));
                            $paymentStatus = $payment_request['payment_status'];
                            $webPartnerFareBreakup = json_decode($bookingInfo['web_partner_fare_break_up'], true);
                            $agent_fare_break_up = json_decode($bookingInfo['agent_fare_break_up'], true);
                            $webPartnerFareBreakup['convenienceFee'] = $applyconvenience_fee;
                            $agent_fare_break_up['convenienceFee'] = $applyconvenience_fee;
                            $total_price = $bookingInfo['total_price'];
                            $updatepaymentdata = array('payment_mode' => 'Wallet', "total_price" => $total_price, "web_partner_fare_break_up" => json_encode($webPartnerFareBreakup), "agent_fare_break_up" => json_encode($agent_fare_break_up));
                            $PaymentModel->updateData('flight_booking_list', ['id' => $bookingInfo['id'], 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                        }
                        return Payment::FlightWallet($flightbookingIdrefrenceNumbers, $paymentInfo, "Wallet");
                    } else {
                        $booking_data = $PaymentModel->get_booking_detail($service, $booking_id, $this->web_partner_id);
                        $agent_fare_break_up = json_decode($booking_data['agent_fare_break_up'], true);
                        $web_partner_fare_break_up = json_decode($booking_data['web_partner_fare_break_up'], true);
                        $total_price = $booking_data['total_price'];
                        $paymentStatus = $payment_request['payment_status'];
                        $updatepaymentdata = array('payment_mode' => 'Wallet', "total_price" => $total_price, "web_partner_fare_break_up" => json_encode($web_partner_fare_break_up), "agent_fare_break_up" => json_encode($agent_fare_break_up), "conveniencefee" => $payment_request['convenience_fee']);

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

                        if ($service == 'tourguide') {
                            $PaymentModel->updateData('tourguide_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                            $return_url = site_url('tourguide/payment-status/');
                        }
                        if ($service == 'activities') {
                            $PaymentModel->updateData('activities_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                            $return_url = site_url('activities/payment-status/');
                        }
                        return Payment::wallet($amount, $booking_id, $service, $service_log, "Wallet", $payment_request['convenience_fee']);
                    }
                } else {
                    $url_param = http_build_query(array('message' => 'Something Wrong', 'reference-no' => $bookingIdrefrenceNumber));
                    return redirect()->to(site_url('payment/payment-error?' . $url_param));
                }
            } else {
                if ($service == "flight") {
                    $booking_data = $PaymentModel->get_flight_booking_detail("flight", $flightbookingIdrefrenceNumbers, $this->web_partner_id, "");
                    $NumberofBooking = count($flightbookingIdrefrenceNumbers);
                    foreach ($booking_data as $bookingInfo) {
                        $applyconvenience_fee = round_value(($payment_request['convenience_fee'] / $NumberofBooking));
                        $paymentStatus = $payment_request['payment_status'];
                        $webPartnerFareBreakup = json_decode($bookingInfo['web_partner_fare_break_up'], true);
                        $agent_fare_break_up = json_decode($bookingInfo['agent_fare_break_up'], true);
                        $webPartnerFareBreakup['convenienceFee'] = $applyconvenience_fee;
                        $agent_fare_break_up['convenienceFee'] = $applyconvenience_fee;
                        $total_price = $bookingInfo['total_price'];
                        $updatepaymentdata = array('payment_mode' => 'Online', "total_price" => $total_price, "web_partner_fare_break_up" => json_encode($webPartnerFareBreakup), "agent_fare_break_up" => json_encode($agent_fare_break_up));
                        $PaymentModel->updateData('flight_booking_list', ['id' => $bookingInfo['id'], 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                    }
                } else {
                    $booking_data = $PaymentModel->get_booking_detail($service, $booking_id, $this->web_partner_id);
                    $booking_ref_number = isset($booking_data['booking_ref_number']) ? $booking_data['booking_ref_number'] : $booking_id;
                    $agent_fare_break_up = json_decode($booking_data['agent_fare_break_up'], true);
                    $web_partner_fare_break_up = json_decode($booking_data['web_partner_fare_break_up'], true);
                    $total_price = $booking_data['total_price'];
                    $paymentStatus = $payment_request['payment_status'];
                    $updatepaymentdata = array('payment_mode' => 'Online', "total_price" => $total_price, "web_partner_fare_break_up" => json_encode($web_partner_fare_break_up), "agent_fare_break_up" => json_encode($agent_fare_break_up), "conveniencefee" => $payment_request['convenience_fee']);

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

                    if ($service == 'tourguide') {
                        $PaymentModel->updateData('tourguide_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                        $return_url = site_url('tourguide/payment-status/');
                    }

                    if ($service == 'activities') {
                        $PaymentModel->updateData('activities_booking_list', ['id' => $booking_id, 'web_partner_id' => $this->web_partner_id], $updatepaymentdata);
                        $return_url = site_url('activities/payment-status/');
                    }
                }
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
        $whereCondition = array("id" => $paymentdata, "status" => "pending", "web_partner_id" => $this->web_partner_id, "wl_agent_id" => $this->wl_agent_id, 'user_id' => $this->user_id);
        $makePaymeninfo = $PaymentModel->getMakePaymentDetails($whereCondition);
        if (!$makePaymeninfo) {
            return view('template/custom-error-layout', ['error_message' => 'Payment Record not found']);
        }
        if ($this->payment_gateway_name == 'Default') {
            $first_name = $this->web_partner_details['user_detail']['first_name'];
            $last_name = $this->web_partner_details['user_detail']['last_name'];
            $email_id = $this->web_partner_details['user_detail']['email_id'];
            $mobile_number = $this->web_partner_details['user_detail']['mobile_no'];
        } else {
            $first_name = $this->wl_agent_details['first_name'];
            $last_name = $this->wl_agent_details['last_name'];
            $email_id = $this->wl_agent_details['login_email'];
            $mobile_number = $this->wl_agent_details['mobile_no'];
        }


        $paymentModeArray = array(
            "RuPayCreditCard" => "CRDC",
            "AmericanExpressCreditCard" => "CRDC",
            "MastercardCreditCard" => "CRDC",
            "VisaCreditCard" => "CRDC",
            "CreditCard" => "CRDC",
            "DebitCard" => "DBCRD",
            "NetBanking" => "NBK",
            "UPIPayments" => "UPI",
        );

        $convenienceFeesModeArray = array(
            "RuPayCreditCard" => "credit_card",
            "AmericanExpressCreditCard" => "credit_card",
            "MastercardCreditCard" => "credit_card",
            "VisaCreditCard" => "credit_card",
            "DebitCard" => "debit_card",
            "NetBanking" => "net_banking",
            "UPIPayments" => "mobile_wallet",
            "CreditCard" => "credit_card",
        );


        $SavePaymentMode = get_cashfree_payment_mode($paymentModeArray[$makePaymeninfo['payment_mode']]);
        $convenience_fee_list = $PaymentModel->convenience_fee("Make_Payment", $this->web_partner_id);

        $convenienceFees_data = calculate_convenience_fee($convenience_fee_list, $convenienceFeesModeArray[$makePaymeninfo['payment_mode']], $makePaymeninfo['amount']);


        $request = array(
            'FirstName' => $first_name,
            'LastName' => $last_name,
            'Email' => $email_id,
            'MobileNumber' => $mobile_number,
            'Currency' => 'INR',
            'RedirectURL' => site_url('payment/makepaymentesponse'),
            'CancelURL' => site_url('payment/makepaymentesponse'),
            'NotifyURL' => site_url('payment/notifyurl'),
            'PaymentMode' => $paymentModeArray[$makePaymeninfo['payment_mode']],
            'Amount' => $makePaymeninfo['amount'] + $convenienceFees_data['conveniencefee'],
            'convenience_fee' => $convenienceFees_data['conveniencefee'],
            'SavePaymentMode' => $SavePaymentMode,
            'Service' => "Make_Payment",
            'CardName' => get_card_name($convenienceFeesModeArray[$makePaymeninfo['payment_mode']]),
            'BookingId' => $paymentdata,
            'booking_ref_number' => "AGENT" . $paymentdata,
            'UserId' => $this->user_id,
            'WebPartnerId' => $this->web_partner_id,
            'ServiceLog' => "",
            'PaymentSource' => "Wl_b2b",
            'wl_agent_id' => $this->wl_agent_id,
        );


        $CASHFREE = new CASHFREE();
        $gatwayResponseData = $CASHFREE->request($request);
        if (isset($gatwayResponseData['Error']['ErrorCode']) && $gatwayResponseData['Error']['ErrorCode'] == 0) {
            return redirect()->to($gatwayResponseData['Result']['paymentLink']);
        } else {
            $Errormessag = $gatwayResponseData['Error']['ErrorMessage'];
            $url_param = http_build_query(array('message' => $Errormessag));
            return redirect()->to(site_url('payment/payment-error?' . $url_param));
        }
    }

    public function makepaymentesponse()
    {
        $method = strtoupper($this->request->getMethod());
        if ($method == 'POST') {
            $response = $response = $this->request->getPost();
            $CASHFREE = new CASHFREE();
            $payment_response = $CASHFREE->response($response);
            $PaymentModel = new PaymentModel();
            $payment_request = $PaymentModel->get_payment_detail($payment_response['order_id']);
            $make_payment_id = $payment_request['booking_ref_no'];
            $service = $payment_request['service'];
            $service_log = $payment_request['service_log'];

            if ($payment_response['payment_status'] == 'Successful') {
                $gateway_response = json_decode($payment_request['payment_response'], true);
                $checkTransaction = $PaymentModel->checkWalletRecharge($this->web_partner_id, $gateway_response['orderId']);
                if ($checkTransaction) {
                    $amount = $payment_response['amount'] - $payment_request['convenience_fee'];
                    $walletbalance = get_balance();
                    $balance = $walletbalance + $amount;


                    $wl_walletbalance = wl_agent_get_balance();
                    $wl_balance = $wl_walletbalance + $amount;

                    $web_partner_account_log = array(
                        'web_partner_id' => $this->web_partner_id,
                        'credit' => $amount,
                        'balance' => $balance,
                        'remark' => 'Online Make Payment Topup',
                        'transaction_id' => $payment_response['order_id'],
                        'service' => "Make_Payment",
                        'payment_transaction_id' => $payment_request['id'],
                        'payment_mode' => $payment_request['payment_mode'],
                        'transaction_type' => 'credit',
                        'action_type' => 'recharge',
                        'wl_agent_id' => $this->wl_agent_id,
                        'created' => create_date()
                    );

                    if ($this->payment_gateway_name == 'Default') {
                        $PaymentModel->insertData('web_partner_account_log', $web_partner_account_log);
                        $PaymentModel->updateData("web_partner_make_payment", array("id" => $make_payment_id), array("status" => "approved", 'pg_transaction_id' => $payment_response['order_id'], "pg_order_id" => $payment_response['order_id']));
                    }
                    unset($web_partner_account_log['balance']);


                    $web_partner_account_log['balance'] = $wl_balance;
                    $web_partner_account_log['wl_agent_staff_id'] = $this->user_id;
                    $PaymentModel->insertData('agent_account_log', $web_partner_account_log);


                    $PaymentModel->updateData("wl_agent_make_payment", array("id" => $make_payment_id), array("status" => "approved", 'pg_transaction_id' => $payment_response['order_id'], "pg_order_id" => $payment_response['order_id']));


                    $message = array("StatusCode" => 0, "Message" => "Make payment request successfully created, Transaction id : " . $payment_response['order_id'], "Class" => "success_popup");
                    $Redirect_Url = site_url('accounts/payment-processing');
                    $this->session->setFlashdata('Message', $message);
                    return redirect()->to($Redirect_Url);
                } else {

                    $url_param = http_build_query(array('message' => 'Something is Wrong', 'reference-no' => $make_payment_id));
                    return redirect()->to(site_url('payment/payment-error?' . $url_param));
                }
            } else {

                if ($this->payment_gateway_name == 'Default') {
                    $PaymentModel->updateData("web_partner_make_payment", array("id" => $make_payment_id), array("status" => "rejected", 'pg_transaction_id' => $payment_response['order_id'], "pg_order_id" => $payment_response['order_id']));
                }
                $PaymentModel->updateData("wl_agent_make_payment", array("id" => $make_payment_id), array("status" => "rejected", 'pg_transaction_id' => $payment_response['order_id'], "pg_order_id" => $payment_response['order_id']));

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
