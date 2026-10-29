<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class HotelMarkupModel extends Model
{
    protected $table = 'web_partner_hotel_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function super_admin_hotel_markup_list($web_partner_id)
    {
        return $this->select('web_partner_hotel_markup.*')->where('web_partner_id',$web_partner_id)
            ->orderBy("web_partner_hotel_markup.id", "DESC")->paginate(40);
    }

    public function super_admin_hotel_markup_details($id)
    {
        return $this->select('*')->where("id", $id)->get()->getRowArray();
    }


    public function remove_markup($id)
    {
        return $this->select('*')->whereIN("id", $id)->delete();
    }

    public function status_change($ids, $data)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->set($data)->update();
    }

    public function web_partner_class()
    {

        return $this->db->table("web_partner_class")->select('id,class_name')->orderBy("id", "DESC")->get()->getResultArray();


    }

    function search_data($web_partner_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['web_partner_hotel_markup.created >=' => $from_date, 'web_partner_hotel_markup.created <=' => $to_date];

                return $this->select('web_partner_hotel_markup.*')
                    ->where($array)->where('web_partner_id',$web_partner_id)
                    ->orderBy("web_partner_hotel_markup.id", "DESC")->paginate(40);

            } else {
                $array = ['super_admin_hotel_markup.created >=' => $from_date, 'super_admin_hotel_markup.created <=' => $to_date];

                return $this->select('web_partner_hotel_markup.*')->where('web_partner_id',$web_partner_id)
                    ->like(trim($data['key']), trim($data['value']))
                    ->orderBy("web_partner_hotel_markup.id", "DESC")->paginate(40);

            }
        } else {

            return $this->select('web_partner_hotel_markup.*')->where('web_partner_id',$web_partner_id)
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("web_partner_hotel_markup.id", "DESC")->paginate(40);

        }
    }

    public function super_admin_hotel_discount_list()
    {

        $SuperAdminHotelDiscountModel = new SuperAdminHotelDiscountModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $SuperAdminHotelDiscountModel->search_data($this->request->getGet());
        } else {
            $lists = $SuperAdminHotelDiscountModel->super_admin_hotel_discount_list();
        }

        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "SuperAdminMarkupDiscount\Views\super-admin-hotel-discount-list",
            'pager' => $SuperAdminHotelDiscountModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/default-layout', $data);


    }

    public function super_admin_hotel_discount_view()
    {

        $ApiSupplierModel = new ApiSupplierModel();
        $SuperAdminHotelDiscountModel = new SuperAdminHotelDiscountModel();
        $data = [
            'title' => $this->title,
            'web_partner_class' => $SuperAdminHotelDiscountModel->web_partner_class(),
            'ApiSupplierModel' => $ApiSupplierModel->supplier_list()
        ];
        $add_blog_view = view('Modules\SuperAdminMarkupDiscount\Views\add-super-admin-hotel-discount', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);

    }

    public function add_super_admin_hotel_discount()
    {

        $validate = new Validation();
        $rules = $this->validate($validate->super_admin_hotel_discount_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $SuperAdminHotelDiscountModel = new SuperAdminHotelDiscountModel();
            $data = $this->request->getPost();
            $data['created'] = create_date();
            $added_data = $SuperAdminHotelDiscountModel->insert($data);

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Hotel Discount Successfully Added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Hotel Discount not  Added", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }

    }

    public function super_admin_hotel_discount_status_change()
    {

        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $SuperAdminHotelDiscountModel = new SuperAdminHotelDiscountModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $SuperAdminHotelDiscountModel->status_change($ids, $data);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "hotel discount status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "hotel discount status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }

    }


    public function edit_admin_hotel_discount_template()
    {

        $id = dev_decode($this->request->uri->getSegment(3));

        $SuperAdminHotelDiscountModel = new SuperAdminHotelDiscountModel();
        $details = $SuperAdminHotelDiscountModel->super_admin_hotel_discount_details($id);

        $ApiSupplierModel = new ApiSupplierModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
            'web_partner_class' => $SuperAdminHotelDiscountModel->web_partner_class(),
            'ApiSupplierModel' => $ApiSupplierModel->supplier_list()

        ];

        $details = view('Modules\SuperAdminMarkupDiscount\Views\edit-super-admin-hotel-discount', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);


    }

    public function edit_admin_hotel_discount()
    {

        $id = dev_decode($this->request->uri->getSegment(3));
        $validate = new Validation();
        $rules = $this->validate($validate->super_admin_hotel_discount_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $SuperAdminHotelDiscountModel = new SuperAdminHotelDiscountModel();
            $data = $this->request->getPost();
            $data['modified'] = create_date();

            $added_data = $SuperAdminHotelDiscountModel->where("id", $id)->set($data)->update();
            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "hotel discount successfully updated", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "hotel discount not updated", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }

    }

    public function remove_super_admin_hotel_discount()
    {

        $SuperAdminHotelDiscountModel = new SuperAdminHotelDiscountModel();
        $ids = $this->request->getPost('checklist');

        $delete = $SuperAdminHotelDiscountModel->remove_discount($ids);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "hotel discount successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
        } else {
            $message = array("StatusCode" => 2, "Message" => "hotel discount not deleted", "Class" => "error_popup", "Reload" => "true");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);

    }
}