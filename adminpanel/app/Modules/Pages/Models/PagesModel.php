<?php

namespace App\Modules\Pages\Models;

use CodeIgniter\Model;

class PagesModel extends Model
{
    protected $table = 'pages';
    protected $primarykey = 'id';
    protected $protectFields = false;




    public function pages_list($web_partner_id)
    {
        return  $this->select('id,title,slug_url,status,created,modified')->where(["web_partner_id"=>$web_partner_id])->paginate(40);
    }

    public function pages_list_details($id,$web_partner_id)
    {
        return  $this->select('*')->where(["id"=>$id,"web_partner_id"=>$web_partner_id])->get()->getRowArray();
    }

    public function remove_pages($blog_ids,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$blog_ids)->where(["web_partner_id"=>$web_partner_id])->delete();
    }

    public function pages_status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(["web_partner_id"=>$web_partner_id])->set($data)->update();
    }

    public function menu_page_list($web_partner_id)
    {
        return  $this->select('id,title,slug_url,custom_url')->where(['status'=>'active',"web_partner_id"=>$web_partner_id])->paginate(40);
    }


    public function CheckUniqueSlug($value,$web_partner_id)
    {
      return $this->db->table("pages")->select('id,web_partner_id,title,slug_url')->where(['slug_url'=>$value,'web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }

  

    function search_data($data,$web_partner_id)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['created >='=> $from_date,'created <='=> $to_date,"web_partner_id"=>$web_partner_id];
                return  $this->select('*')->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date,"web_partner_id"=>$web_partner_id];
                return  $this->select('*')->orderBy('id', 'DESC')->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('*')->orderBy('id', 'DESC')->like(trim($data['key']),trim($data['value']))->where(["web_partner_id"=>$web_partner_id])->paginate(40);
        }
    }

    private function pages_list_selected_menu($ids,$web_partner_id)
    {
        if($ids!=null) {
        $ids=$order= explode(",", $ids);
        $order = sprintf('FIELD(id, %s)', implode(', ', $order));
        return $this->select('id,web_partner_id,title,slug_url')
            ->whereIn("id",$ids)->where(["web_partner_id"=>$web_partner_id])->orderBy("$order")
          ->get()->getResultArray();
        }
        return  array();
    }

    function menu_selected_list($web_partner_id)
    {
        $builder =$this->db->table('pages_menu_list');
        $builder->select('page_content');
        $builder->where('is_selected', 1)->where(["web_partner_id"=>$web_partner_id]);
        $data =  $builder->get()->getRowArray();
        $data['page_content']  =  isset($data['page_content'])?$data['page_content']:"";
        return PagesModel::pages_list_selected_menu($data['page_content'],$web_partner_id);
    }

    function selected_menu_id($web_partner_id)
    {
        $builder = $this->db->table('pages_menu_list');
        $builder->select('id');
        $builder->where('is_selected', 1)->where(["web_partner_id"=>$web_partner_id]);
        $data = $builder->get()->getRowArray();
        $data['id']  =  isset($data['id'])?$data['id']:"";    
        return $data['id'];
    }

    function menu_selected_list_by_id($id,$web_partner_id)
    {
        $builder =$this->db->table('pages_menu_list');
        $builder->select('page_content');
        $builder->where('id', $id)->where(["web_partner_id"=>$web_partner_id]);
        $data =  $builder->get()->getRowArray();
        $data['page_content']  =  isset($data['page_content'])?$data['page_content']:"";    
        return PagesModel::pages_list_selected_menu($data['page_content'],$web_partner_id);
    }

    function menu_type_list($web_partner_id)
    {
        $builder =$this->db->table('pages_menu_list');
        $builder->select('menu_name,is_selected,id');
        $builder->where(["web_partner_id"=>$web_partner_id]);
        return $builder->get()->getResultArray();
    }

    function updateUserData($tableName,$id, $updateData,$web_partner_id)
    {
        $updateDataPrevious = [
            'is_selected'=>null
        ];
        $this->db->table($tableName)->where(["web_partner_id"=>$web_partner_id])->update($updateDataPrevious);
        $this->db->table($tableName)->where(['id'=>$id,"web_partner_id"=>$web_partner_id])->update($updateData);

        return true;
    }

    function remove_menu_list_by_id($id,$web_partner_id)
    {
        $builder = $this->db->table('pages_menu_list');
        $builder->select('page_content');
        $builder->where('id', $id);
        $builder->where(["web_partner_id"=>$web_partner_id]);
        $data = $builder->get()->getRowArray();
        return $data;
    }

    function selected_menu_labels($web_partner_id)
    {
        $builder = $this->db->table('pages_menu_list');
        $builder->select('id,menu_name');
        $builder->where(["web_partner_id"=>$web_partner_id]);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function selected_menu_labels_superadmin()
    {
        $builder = $this->db->table('pages_menu_list');
        $builder->select('menu_type,menu_name');
        $builder->where(["web_partner_id"=>null]);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function updateMenuLabelData($tableName,$id, $updateData,$web_partner_id)
    {

        $this->db->table($tableName)->where('id',$id)->where(["web_partner_id"=>$web_partner_id])->update($updateData);

        return true;
    }
    function insertBatchData($tableName,$insertData)
    {
        $this->db->table($tableName)->insertBatch($insertData);
    }
    public function get_page_details($slug)
    {
        return  $this->select('*')->where("slug_url",$slug)->get()->getRowArray();
    }
}


