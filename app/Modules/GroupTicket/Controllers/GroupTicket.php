<?php



namespace Modules\GroupTicket\Controllers;



use App\Modules\GroupTicket\Models\GroupTicketModel;



use App\Controllers\BaseController;

use Modules\GroupTicket\Config\Validation;





class GroupTicket extends BaseController

{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)

    {

        parent::initController($request, $response, $logger);

        $this->title = "GroupTicket";



        $this->web_partner_details = web_partner_details;

        $this->web_partner_id = web_partner_details['id'];

        $this->wl_agent_id = session()->get('wl_agent')['id'];

        $this->booking_source ="Wl_b2b";

        /*if(permission_access_error("Holiday","Holiday_Module")) {



        }*/

    }

    /**

     * Group Ticket Page Show

     */

    public function index(){

        $data = [

            'title' => $this->title,

            'view' => "GroupTicket\Views\group-ticket",

        ];

        return view('template/home-layout', $data);

    }



    public function paxInfo(){

        $GroupTicketModel = new GroupTicketModel();

        $data = $this->request->getPost();

        $data['dial_code'] = $GroupTicketModel->get_dial_code();

        echo view('Modules\GroupTicket\Views\pax-information',$data);

    }



    public function generateTicket(){

        $data = $this->request->getPost();

        $validation = new Validation();

        $rules = $this->validate($validation->pax_validation($data));

        if (!$rules) {

            $errors = $this->validation->getErrors();

            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));

            return $this->response->setJSON($data_validation);

        } else {

            $GroupTicketModel = new GroupTicketModel();

            $is_domestic = ($data['flight_type'] == 'domestic')?'1':'0';

            $journeyType = ($data['journey_type'] == "oneway")?'OneWay':'RoundTrip';

            $group_ticket = array(

                'web_partner_id'=>$this->web_partner_id,

                'agent_id'=> $this->wl_agent_id,

                'journey_type' => $journeyType,

                'origin' => $data['origin_airport_code'],

                'destination' => $data['destination_airport_code'],

                'departure_date' => $data['depart_date'],

                'cabin_class' => $data['cabin_class'],

                'is_domestic' => $is_domestic,

                'origin_preference_airline_code' => $data['onward_airline_prefrence'],

                'origin_preference_flight_number' => $data['origin_preference_flight_number'],

                'business_type' => $data['user_type'],

                'contact_email' => $data['contact_email'],

                'contact_no' => $data['contact_number'],

                'address' => $data['address'],

                'city' => $data['city'],

                'state' => $data['state'],

                'country' => $data['country'],

                'pincode' => $data['pincode'],

                'remark' => $data['remark'],

                'status' => 'pending',

                'pax_info' => json_encode($data['pax']),

                'created' => create_date()

            );

            if($data['journey_type'] == "roundtrip"){

                $group_ticket['return_date'] = $data['return_date'];

                $group_ticket['destination_preference_airline_code'] = $data['destination_airline_prefrence'];

                $group_ticket['destination_preference_flight_number'] = $data['destination_preference_flight_number'];

            }



            $added_data = $GroupTicketModel->insert($group_ticket);



            if ($added_data) {

                $message = array("StatusCode" => 0, "Message" => "Group Ticket Request Successfully Sent", "Class" => "success_popup", "Reload" => "true");

            } else {

                $message = array("StatusCode" => 2, "Message" => "Group Ticket Request not Sent", "Class" => "error_popup", "Reload" => "true");

            }



            $this->session->setFlashdata('Message', $message);

            return $this->response->setJSON($message);



        }

    }


    public function group_ticket_list(){
        $GroupTicketModel = new GroupTicketModel();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $list = $GroupTicketModel->search_group_ticket($this->web_partner_id,$this->wl_agent_id,$this->request->getGet());
        }else{
          $list = $GroupTicketModel->get_groupticket_list($this->web_partner_id,$this->wl_agent_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $list,
            'search_bar_data' => $this->request->getGet(),
            'view' => "GroupTicket\Views\group-ticket-list",
            'pager' => $GroupTicketModel->pager,
        ];
        return view('template/default-layout', $data);
    }

    public function group_ticket_view(){
        $id = dev_decode($this->request->uri->getSegment(3));
        $GroupTicketModel = new GroupTicketModel();
        $ticket_detail = $GroupTicketModel->group_ticket_detail($this->web_partner_id,$id);
        $key = array('journey_type'=>'Journey Type','origin'=>'Origin','destination'=>'Destination','departure_date'=>'Departure Date','return_date'=>'Return Date','cabin_class'=>'Cabin Class','origin_preference_airline_code'=>'Origin Preference Airline Code','destination_preference_airline_code'=>'Destination Preference Airline Code','origin_preference_flight_number'=>'Origin Preference Flight Number','destination_preference_flight_number'=>'Destination Preference Flight Number','business_type'=>'Business Type','contact_email'=>'Contact Email Id','contact_no'=>'Contact Number','address'=>'Address','city'=>'City','state'=>'State','country'=>'Country','pincode'=>'Pincode','remark'=>'Remark','status'=>'Status');
        $details =view('Modules\GroupTicket\Views\ticket_detail',['detail'=>$ticket_detail,'keyArray'=>$key]);

        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
    }


}