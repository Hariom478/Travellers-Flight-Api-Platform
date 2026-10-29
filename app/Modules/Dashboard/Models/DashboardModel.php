<?php

namespace App\Modules\Dashboard\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{


    public function flight_booking_count($web_partner_id,$wl_agent_id)
    {
        return count($this->db->table('flight_booking_list')->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $wl_agent_id)->where('booking_source', 'Wl_b2b')->get()->getResultArray());
    }

    public function hotel_booking_count($web_partner_id,$wl_agent_id)
    {
        return count($this->db->table('hotel_booking_list')->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $wl_agent_id)->where('booking_source', 'Wl_b2b')->get()->getResultArray());
    }

    public function bus_booking_count($web_partner_id,$wl_agent_id)
    {
        return count($this->db->table('bus_booking_list')->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $wl_agent_id)->where('booking_source', 'Wl_b2b')->get()->getResultArray());
    }

    public function holiday_booking_count($web_partner_id,$wl_agent_id)
    {
        return count($this->db->table('holiday_booking_list')->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $wl_agent_id)->where('booking_source', 'Wl_b2b')->get()->getResultArray());
    }

    public function visa_booking_count($web_partner_id,$wl_agent_id)
    {
        return count($this->db->table('visa_booking_list')->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $wl_agent_id)->where('booking_source', 'Wl_b2b')->get()->getResultArray());
    }

    public function car_booking_count($web_partner_id,$wl_agent_id)
    {
        return count($this->db->table('car_booking_list')->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $wl_agent_id)->where('booking_source', 'Wl_b2b')->get()->getResultArray());
    }





    function getServiceCountInfo($tablename, $where, $select, $whereIn = array())
    {
      
        $builder  =  $this->db->table($tablename);
        if (!empty($select)) {
            $builder->select($select);
        }
        if (!empty($where)) {
            $builder->where($where);
        }
        if (!empty($whereIn)) {
            foreach ($whereIn as $key => $value) {
                $builder->whereIn($key, $value);
            }
        }
      
       
        return  $builder->get()->getResultArray();
    }











}


