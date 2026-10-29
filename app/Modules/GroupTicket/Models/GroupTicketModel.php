<?php



namespace App\Modules\GroupTicket\Models;



use CodeIgniter\Model;



class GroupTicketModel extends Model

{

    protected $table = 'flight_group_ticket_request';

    protected $primarykey = 'id';

    protected $protectFields = false;





    function get_dial_code()

    {

        return $this->db->table('countries')->select('phonecode,name,iso2')->get()->getResultArray();

    }

    function get_groupticket_list($web_partner_id,$agent_id){
        return $this->select('*')
        ->orderBy('flight_group_ticket_request.id', 'DESC')->where(["web_partner_id" => $web_partner_id, "agent_id" => $agent_id])->paginate(10);
    }

    function search_group_ticket($web_partner_id,$agent_id,$data){

        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['flight_group_ticket_request.created >=' => $from_date, 'flight_group_ticket_request.created <=' => $to_date];
                return $this->select('flight_group_ticket_request.*')

                    ->orderBy('flight_group_ticket_request.id', 'DESC')->where(["flight_group_ticket_request.web_partner_id" => $web_partner_id, "agent_id" => $agent_id])->where($array)->paginate(10);
            } else {
                $array = ['flight_group_ticket_request.created >=' => $from_date, 'flight_group_ticket_request.created <=' => $to_date];
                return $this->select('agent_flight_markup.*')

                    ->orderBy('flight_group_ticket_request.id', 'DESC')->where(["flight_group_ticket_request.web_partner_id" => $web_partner_id, "agent_id" => $agent_id])->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('flight_group_ticket_request.*')

                ->orderBy('flight_group_ticket_request.id', 'DESC')->where(["flight_group_ticket_request.web_partner_id" => $web_partner_id, "agent_id" => $agent_id])->like(trim($data['key']), trim($data['value']))->paginate(10);
        }
    }

    function group_ticket_detail($web_partner_id,$ticket_id){
        return $this->select('*')->where(['web_partner_id'=>$web_partner_id,'id'=>$ticket_id])->get()->getRowArray();
    }

}