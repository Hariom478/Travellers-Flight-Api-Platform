<?php

namespace Modules\Holidayservice\Config;

class Validation
{
    public $block_holiday_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'HolidayId' => ['rules' => 'trim|required'],
        'TravelDate' => ['rules' => 'trim|required'],
        'HolidayCategory' => ['rules' => 'trim|required'],
        'PriceId' => ['rules' => 'trim|required'],
        'SearchTokenId' => ['rules' => 'trim|required']
    ];

    public $details_holiday_validation = [
        'SearchTokenId' => ['rules' => 'trim|required']
    ];

    public $cancel_validation = [
        'BookingKey' => ['rules' => 'trim|required|numeric'],
        'Remarks' => ['rules' => 'trim|required'],
    ];
    public $generate_html_voucher_invoice = [
        'HtmlType' => ['rules' => 'trim|required|in_list[Ticket,AgencyInvoice,CustomerInvoice,CreditNote]'],
        'BookingId' => ['rules' => 'trim|required'],
        'SearchTokenId' => ['rules' => 'trim|required'],
        'UserType' => ['rules' => 'trim|required|in_list[WebPartner,Admin,wl-customer,wl-agent-customer,wl-agent]'],
        'ViewService' => ['rules' => 'trim|required|in_list[Email,View,Pdf]'],
        'ViewSize' => ['rules' => 'trim'],
        'RequestBy' => ['rules' => 'trim|in_list[WebPartner,Agent]'],
    ];


    public $amendment_validation_admin = [
        'WebPartnerId' => ['rules' => 'trim|required|numeric'],

        'BookingId' => ['rules' => 'trim|required|numeric'],
        'AmendmentId' => ['rules' => 'trim|permit_empty|numeric'],
        'AmendmentStatus' => ['rules' => 'trim|permit_empty|in_list[success,rejected]'],
      /*  'Type' => ['rules' => 'trim|required|in_list[cancellation,full_refund,reissue,correction,reissue_quotation,cancellation_quotation]'],*/
        'Remarks' => ['rules' => 'trim|required'],
        'RequesterInfo.*' => ['rules' => 'trim|required'],
    ];


    public function amendment_validation($input)
    {
        $amendment_validation = [
            'BookingId' => ['rules' => 'trim|required|numeric'],
            'Type' => ['rules' => 'trim|required|in_list[cancellation,full_refund,correction,reissue_quotation,cancellation_quotation]'],
            'Remarks' => ['rules' => 'trim|required'],
            'RequesterInfo.Requester' => ['rules' => 'trim|required|in_list[WebPartner,SuperAdmin,Whitelabel,WhitelabelB2C]']
        ];

        if ($input['RequesterInfo']['Requester'] == "SuperAdmin") {
            $amendment_validation['AmendmentStatus'] = ['rules' => 'trim|required|in_list[approved,rejected]'];
            $amendment_validation['AmendmentId'] = ['rules' => 'trim|required'];
        }
        if ($input['RequesterInfo']['Requester'] == "WebPartner" || $input['RequesterInfo']['Requester'] == "Whitelabel"  || $input['RequesterInfo']['Requester'] == "WhitelabelB2C") {
            $amendment_validation['RequesterInfo.RequesterId'] = ['rules' => 'trim|required'];
        }




        return $amendment_validation;
    }
}
