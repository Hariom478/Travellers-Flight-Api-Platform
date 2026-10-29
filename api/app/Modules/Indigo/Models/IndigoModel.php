<?php
namespace App\Modules\Indigo\Models;
use CodeIgniter\Model;

class IndigoModel extends Model
{

    function insert_flight_logs($web_partner_id,$tts_search_token,$request,$response,$service,$api_supplier,$created)
    {
        $insertlog=array(
                            'web_partner_id'   => $web_partner_id,
                            'tts_search_token' => $tts_search_token,
                            'request'          => $request,
                            'response'         => $response,
                            'service'          => $service,
                            'api_supplier'     => $api_supplier,
                            'created'          => $created
                        );
        $db= \Config\Database::connect('api');
        $db->table('common_flight_log')->insert($insertlog);
    }

    function updateUserData($tableName,$whereCondition,$updateData)
    {
      $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }

    function verify_booking_detail($array_condition)
    {
         $BookingDetail =$this->db->table('flight_booking_list')->select('*')->where($array_condition)->get()->getRowArray();
         if($BookingDetail) {
            $PassengerDetails = $this->db->table('flight_booking_travelers')->select('*')->where('flight_booking_id', $BookingDetail['id'])->get()->getResultArray();
            $BookingDetail['PassengerDetails'] = $PassengerDetails;
            $AccountDetails =$this->db->table('web_partner_account_log')->select('acc_ref_number,debit,created')->where(['booking_ref_no'=>$BookingDetail['id'],'service'=>'flight'])->get()->getRowArray();
            $BookingDetail['AccountDetails']=$AccountDetails;
         }
         return $BookingDetail;
    }

    function get_api_account_id($array_condition)
    {
        return $this->db->table('api_account_id')->select('username,password')->orderBy('id', 'DESC')->where($array_condition)->get()->getRowArray();
    }
   
}


