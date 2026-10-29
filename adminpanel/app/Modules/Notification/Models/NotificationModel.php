<?php

namespace App\Modules\Notification\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'webpartner_notification';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function notification_list()
    {
        return  $this->select('*')->orderBy('id', 'DESC')->paginate(40);
    }

    public function notification_details($id)
    {
        return  $this->select('*')->where("id",$id)->get()->getRowArray();
    }

    function search_data($data)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('*')->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('*')->orderBy('id', 'DESC')->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('*')->orderBy('id', 'DESC')->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }

    public function remove_notification($id)
    {
        return  $this->select('*')->whereIn("id",$id)->delete();
    }

    public function notification_status_change($ids, $data)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->set($data)->update();
    }
}


