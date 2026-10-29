<?php

namespace App\Modules\TBOFlight\Models;

use CodeIgniter\Model;

class TBOFlightModel extends Model
{
    function fetch_auth_token($date,$api_mode)
    {
        $db= \Config\Database::connect('api');
        $builder = $db->table('tbo_auth_token');
        $builder->select('token_id');
        $builder->where('api_mode', $api_mode);
        $builder->where('created', $date);
        $builder->where('service', "Flight");
        return $builder->get()->getRowArray();
    }
    function getDataAPI($tableName,$where,$whereIn,$row,$result,$selectField,$PNR=null)
    {
    $db= \Config\Database::connect('api');
     $builder  =  $db->table('common_flight_log');
     $builder->select($selectField);
     $builder->orderBy("id","DESC");
     
     if($PNR != null)
     {
         $builder->where('pnr',$PNR);
     }
     
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
    function insert_update_data($table_name,$data)
    {
        $db= \Config\Database::connect('api');
        $db->table($table_name)->replace($data);
    }

    function insert_data($table_name,$data)
    {
        $db= \Config\Database::connect('api');
        $db->table($table_name)->insert($data);
    }

    function insert_flight_logs($web_partner_id,$tts_search_token,$request,$response,$service,$api_supplier,$created,$PNR=null)
    {
        $insertlog=array(
                            'web_partner_id'   => $web_partner_id,
                            'tts_search_token' => $tts_search_token,
                            'request'          => json_encode($request),
                            'response'         => json_encode($response),
                            'service'          => $service,
                            'api_supplier'     => $api_supplier,
                            'created'          => $created,
                            'pnr'              => $PNR,
                        );
        $db= \Config\Database::connect('api');
        $db->table('common_flight_log')->insert($insertlog);
    }

    function updateUserData($tableName,$whereCondition,$updateData)
    {
      $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }

    function updatePaxData($tableName,$updateData,$field)
    {
      $this->db->table($tableName)->updateBatch($updateData,$field);
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
  function getDataResultType($tableName,$whereCondition,$field)
  {
   return  $this->db->table($tableName)->select($field)->where($whereCondition)->get()->getResultArray();
  }

}


