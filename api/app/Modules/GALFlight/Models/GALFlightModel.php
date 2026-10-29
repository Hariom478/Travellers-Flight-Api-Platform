<?php

namespace App\Modules\GALFlight\Models;

use CodeIgniter\Model;

class GALFlightModel extends Model
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
    function getData($tableName,$where,$whereIn,$row,$result,$selectField)
    {
    $db= \Config\Database::connect('api');
     $builder  =  $db->table('common_flight_log');
     $builder->select($selectField);
     $builder->orderBy("id","DESC");
     if($where){
        $builder->where($where);
     }
     if($whereIn){
        $builder->whereIn($whereIn);
     }
     if($row){
        return   $builder->get()->getRowArray();
     }
     if($result){
        return   $builder->get()->getResultArray();
     }
    }
   
}


