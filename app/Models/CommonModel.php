<?php

namespace App\Models;

use CodeIgniter\Model;

class CommonModel extends Model
{

    protected $primarykey = 'id';
    protected $protectFields = false;


    public function api_webpartner_setting($web_partner_id)
    {
        return $this->db->table("api_webpartner_setting")->select('*')->where('web_partner_id', $web_partner_id)->orderBy("id", "DESC")->get()->getRowArray();
    }

    public function gettingCountryCodeWithCountryName()
    {
        return $this->db->table("countries")->select('name as CountryName,iso2 as CountryCode')->orderBy("name", "ASC")->get()->getResultArray();
    }

    public function wl_agent_balance($web_partner_id, $wl_agent_id)
    {
        return $this->db->table("agent_account_log")->select('balance')->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $wl_agent_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }

    public function get_payment_detail($order_id)
    {
        $data = $this->db->table("super_admin_payment_transaction")->select('id,web_partner_id,user_id,wl_agent_id')->where('order_id', $order_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
        if (empty($data)) {
            $data = $this->db->table("web_partner_payment_transaction")->select('id,web_partner_id,user_id,wl_agent_id')->where('order_id', $order_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
        }
        return $data;
    }

    public function get_user_detail($wl_agent_id, $user_id, $web_partner_id)
    {
        return $this->db->table("agent")->select('agent.*,agent_users.*')
            ->join('agent_users', 'agent_users.agent_id = agent.id')->where(array('agent.id' => $wl_agent_id, 'agent.web_partner_id' => $web_partner_id))->limit(1)->get()->getRowArray();
    }

    public function super_admin_website_setting()
    {
        return $this->db->table("super_admin_website_setting")->select('id,cc_email,bcc_email,company_name,company_gst_no,video_url,gst_state_code,company_ci_no,pre_fix,address,country,state,city,pincode,currency,email_setting,sms_setting,timezone,support_no,support_email,logo,tollfree_no,whatsapp_no')->orderBy("id", "DESC")->get()->getRowArray();
    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }

    function updateData($tableName, $where, $data)
    {
        return $this->db->table($tableName)->where($where)->set($data)->update();
    }
    public function pages_list($web_partner_id)
    {
        $menuArray = array();
        $getmenuList = $this->db->table("pages_menu_list")->select('menu_name,menu_type,page_content')->where('web_partner_id', $web_partner_id)->get()->getResultArray();

        if ($getmenuList) {
            $page_contents = array_column($getmenuList, "page_content");

            $pageValue = array();
            foreach ($page_contents as $page_content) {
                $pagedata = explode(",", $page_content);
                $pageValue = array_merge($pageValue, $pagedata);
            }

            $pageValue = array_unique($pageValue);
            $Pagedata = $this->db->table("pages")->select('id,title,slug_url,custom_url')->whereIn('id', $pageValue)->where('web_partner_id', $web_partner_id)->where(['status' => 'active'])->get()->getResultArray();
            $dataId = array_column($Pagedata, "id");
            foreach ($getmenuList as $menuList) {
                $title = array_column($Pagedata, 'title', "id");
                $slug_url = array_column($Pagedata, 'slug_url', "id");
                $custom_url = array_column($Pagedata, 'custom_url', "id");
                $pageContentIds = explode(",", $menuList['page_content']);
                $pagedata = array();

                foreach ($pageContentIds as $pageContentId) {
                    if (array_key_exists($pageContentId, $title) && array_key_exists($pageContentId, $slug_url) && array_key_exists($pageContentId, $custom_url)) {
                        array_push($pagedata, array("id" => $pageContentId, "title" => $title[$pageContentId], "slug_url" => $slug_url[$pageContentId], "custom_url" => $custom_url[$pageContentId]));

                    }
                }
                $menuArray[$menuList['menu_type']]['menu_name'] = $menuList['menu_name'];
                $menuArray[$menuList['menu_type']]['page_content'] = $pagedata;
            }
        }
        return $menuArray;
    }

    public function webpartner_whitelabel_details_bydomain($webpartner_url)
    {
        return $this->db->table("whitelabel_webpartner_setting")->select('*')->whereIn('domain_name', $webpartner_url)->where('b2b_business', "active")->orderBy("id", "DESC")->get()->getRowArray();
    }

    public function webpartner_details($web_partner_id)
    {
        return $this->db->table("web_partner")->select('*')->where('status', "active")->where('id', $web_partner_id)->orderBy("id", "DESC")->get()->getRowArray();
    }

    public function web_partner_balance($web_partner_id)
    {
        return $this->db->table("web_partner_account_log")->select('balance')->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }

    public function get_staff_list($web_partner_id)
    {
        return $this->db->table('admin_users')->select('login_email as email_id,first_name,last_name,mobile_no')->where('primary_user', 1)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }

    public function check_agent_credit_limit($web_partner_id, $wl_agent_id)
    {
        return $this->db->table('agent_credit_log')->select('id,web_partner_id,wl_agent_id,credit_limit,credit_expire,credit_expire_date,remark')->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $wl_agent_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }
    function getInvoiceSuffixData($whereCondition, $otherParameter)
    {
        $field = '';
        if ($otherParameter['checkTaxableInvoce'] == 1) {
            $field = 'taxable_couter as couter,taxable_prefix as prefix,financial_year';
        } else {
            $field = 'nontaxable_couter as couter,nontaxable_prefix as prefix,financial_year';
        }
        $builder = $this->db->table('invoice_suffix_list')->select($field);
        $builder->where($whereCondition);
        $data = $builder->get()->getRowArray();
        if ($data) {
            $data['IsTaxableInvoice'] = $otherParameter['checkTaxableInvoce'];
            return $data;
        } else {

            $insertData['web_partner_id'] = $otherParameter['web_partner_id'];
            $insertData['financial_year'] = $otherParameter['financialYear'];
            $insertData['service'] = $otherParameter['service'];
            $insertData['taxable_prefix'] = $otherParameter['INVPrifix']['TaxablePrfix'];
            $insertData['nontaxable_prefix'] = $otherParameter['INVPrifix']['NONTaxablePrfix'];
            $insertData['taxable_couter'] = 1;
            $insertData['nontaxable_couter'] = 1;
            $insertData['invoice_type'] = $otherParameter['invoice_type'];
            $this->db->table('invoice_suffix_list')->insert($insertData);
            if ($otherParameter['checkTaxableInvoce'] == 1) {
                $data['prefix'] = $otherParameter['INVPrifix']['TaxablePrfix'];
                $data['couter'] = 1;
                $data['financial_year'] = $otherParameter['financialYear'];
            } else {
                $data['prefix'] = $otherParameter['INVPrifix']['NONTaxablePrfix'];
                $data['couter'] = 1;
                $data['financial_year'] = $otherParameter['financialYear'];
            }
            $data['IsTaxableInvoice'] = $otherParameter['checkTaxableInvoce'];
        }

        return $data;
    }
}