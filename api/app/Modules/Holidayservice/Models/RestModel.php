<?php

namespace App\Modules\Holidayservice\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class RestModel extends Model
{
    function GetAmendmentInfo($tableName, $Where, $fieldName)
    {
        return $this->db->table($tableName)->select($fieldName)->where($Where)->get()->getRowArray();
    }

    function updateUserData($tableName, $whereCondition, $updateData)
    {
        $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }

    function get_book_detail($array_condition)
    {
        return $this->db->table('holiday_booking_list')->select('id,web_partner_id,booking_ref_number,booking_status,tts_search_token')->where($array_condition)->get()->getRowArray();
    }

    public function holiday_list_search($search, $tag,$web_partner_id)
    {
        if ($tag == 'Destinations') {
            $builder= $this->db->table('holiday_list')->select("holiday_list.id,holiday_list.holiday_name as Name, holiday_list.holiday_slug as Slug, holiday_list.starting_price_standard as StandardPrice,holiday_list.starting_price_deluxe as DeluxePrice,holiday_list.starting_price_luxury as LuxuryPrice,holiday_list.advance_value as AdvanceValue,holiday_list.advance_days as AdvanceDays,holiday_list.price_on_request as PriceOnRequest, holiday_list.holiday_image as HolidayImage,holiday_list.holiday_themes_id as Themes,holiday_list.holiday_destination_id as Destinations,holiday_list.holiday_includes as Includes, holiday_list.holiday_description as Description,
            concat('[', group_concat(JSON_OBJECT('id',holiday_itinerary.id,'itinerary_title',holiday_itinerary.itinerary_title,'city',holiday_itinerary.city,'holiday_list_id',holiday_itinerary.holiday_list_id,'itinerary_description',holiday_itinerary.itinerary_description,'day_meal_plan',holiday_itinerary.day_meal_plan,'itinerary_image',holiday_itinerary.itinerary_image,'flight_data',holiday_itinerary.flight_data,'hotel_data',holiday_itinerary.hotel_data,'transfer_data',holiday_itinerary.transfer_data) separator ','), ']') as itinerary")
                ->where('holiday_list.status', 'active')
                ->where('find_in_set("' . $search . '", holiday_list.holiday_destination_id) <> 0');
                $builder->groupStart();
                $builder->where(["holiday_list.web_partner_id"=>$web_partner_id]);
                /* $builder->orWhere(["holiday_list.web_partner_id"=>0]); */
                $builder->groupEnd();
               return  $builder->join("holiday_itinerary", 'holiday_itinerary.holiday_list_id = holiday_list.id', 'Left')
                ->groupBy('holiday_list.id')->orderBy('holiday_list.starting_price_standard', 'ASC')
                ->get()->getResultArray();
        } elseif ($tag == 'Themes') {
            $builder = $this->db->table('holiday_list')->select("holiday_list.id,holiday_list.holiday_name as Name, holiday_list.holiday_slug as Slug, holiday_list.starting_price_standard as StandardPrice,holiday_list.starting_price_deluxe as DeluxePrice,holiday_list.starting_price_luxury as LuxuryPrice,holiday_list.advance_value as AdvanceValue,holiday_list.advance_days as AdvanceDays,holiday_list.price_on_request as PriceOnRequest, holiday_list.holiday_image as HolidayImage,holiday_list.holiday_themes_id as Themes,holiday_list.holiday_destination_id as Destinations,holiday_list.holiday_includes as Includes, holiday_list.holiday_description as Description,
            concat('[', group_concat(JSON_OBJECT('id',holiday_itinerary.id,'itinerary_title',holiday_itinerary.itinerary_title,'city',holiday_itinerary.city,'holiday_list_id',holiday_itinerary.holiday_list_id,'itinerary_description',holiday_itinerary.itinerary_description,'day_meal_plan',holiday_itinerary.day_meal_plan,'itinerary_image',holiday_itinerary.itinerary_image,'flight_data',holiday_itinerary.flight_data,'hotel_data',holiday_itinerary.hotel_data,'transfer_data',holiday_itinerary.transfer_data) separator ','), ']') as itinerary")
                ->where('holiday_list.status', 'active')
                ->where('find_in_set("' . $search . '", holiday_list.holiday_themes_id) <> 0');
                $builder->groupStart();
                $builder->where(["holiday_list.web_partner_id"=>$web_partner_id]);
                /* $builder->orWhere(["holiday_list.web_partner_id"=>0]); */
                $builder->groupEnd();
                return $builder->join("holiday_itinerary", 'holiday_itinerary.holiday_list_id = holiday_list.id', 'Left')
                ->groupBy('holiday_list.id')->orderBy('holiday_list.starting_price_standard', 'ASC')
                ->get()->getResultArray();
        } else {

            $builder = $this->db->table('holiday_list')->select("holiday_list.id,holiday_list.holiday_name as Name, holiday_list.holiday_slug as Slug, holiday_list.starting_price_standard as StandardPrice,holiday_list.starting_price_deluxe as DeluxePrice,holiday_list.starting_price_luxury as LuxuryPrice,holiday_list.advance_value as AdvanceValue,holiday_list.advance_days as AdvanceDays,holiday_list.price_on_request as PriceOnRequest, holiday_list.holiday_image as HolidayImage,holiday_list.holiday_themes_id as Themes,holiday_list.holiday_destination_id as Destinations,holiday_list.holiday_includes as Includes, holiday_list.holiday_description as Description,
            concat('[', group_concat(JSON_OBJECT('id',holiday_itinerary.id,'itinerary_title',holiday_itinerary.itinerary_title,'city',holiday_itinerary.city,'holiday_list_id',holiday_itinerary.holiday_list_id,'itinerary_description',holiday_itinerary.itinerary_description,'day_meal_plan',holiday_itinerary.day_meal_plan,'itinerary_image',holiday_itinerary.itinerary_image,'flight_data',holiday_itinerary.flight_data,'hotel_data',holiday_itinerary.hotel_data,'transfer_data',holiday_itinerary.transfer_data) separator ','), ']') as itinerary")
                ->where('holiday_list.status', 'active')
                ->where("holiday_list.id=$search");
                $builder->groupStart();
                $builder->where(["holiday_list.web_partner_id"=>$web_partner_id]);
                /* $builder->orWhere(["holiday_list.web_partner_id"=>0]); */
                $builder->groupEnd();
                return $builder->join("holiday_itinerary", 'holiday_itinerary.holiday_list_id = holiday_list.id', 'Left')
                ->groupBy('holiday_list.id')->orderBy('holiday_list.starting_price_standard', 'ASC')
                ->get()->getResultArray();
        }

    }


    public function offers_list($web_partner_id)
    {
        return $this->db->table('super_admin_offers')->select('id,title,description,service,url,image')
            ->where('status', 'active')->where('service', 'holiday')->limit(9)->orderBy('id', 'DESC')->get()->getResultArray();
    }

    public function holiday_themes_list($web_partner_id)
    {
        $builder = $this->db->table('holiday_themes')->select('holiday_themes.id,holiday_themes.theme_name as Name,holiday_themes.theme_slug as Slug,holiday_themes.theme_image as Image,holiday_themes.starting_price as StartingPrice,holiday_themes.theme_icon as Icon')
            ->where('holiday_themes.status', 'active')->where('holiday_themes.show_on_home', 1);
            $builder->groupStart();
                $builder->where(["holiday_themes.web_partner_id"=>$web_partner_id]);
                /* $builder->orWhere(["holiday_themes.web_partner_id"=>0]); */
                $builder->groupEnd();
            $holiday_themes =       $builder->limit(20)->orderBy("holiday_themes.id", "DESC")
            ->get()->getResultArray();


        foreach ($holiday_themes as $key => $data) {
            $id = $data['id'];
            $total_packages = $this->db->table('holiday_list')->select('COUNT(id) as total_packages')->where('status', 'active')->where('find_in_set("' . $id . '", holiday_themes_id) <> 0')->get()->getRowArray();;
            $holiday_themes[$key]['TotalPackages'] = $total_packages['total_packages'];
            $holiday_themes[$key]['Image'] =  $holiday_themes[$key]['Image'];
            if ($data['Icon']) {
                $holiday_themes[$key]['Icon'] =  $data['Icon'];
            } else {
                $holiday_themes[$key]['Icon'] = '';
            }
        }

        return $holiday_themes;
    }
    
    public function holiday_themes_by_slug($web_partner_id,$slug)
    {
        $builder = $this->db->table('holiday_themes')->select('holiday_themes.id,holiday_themes.theme_name as Name,holiday_themes.theme_slug as Slug,holiday_themes.theme_image as Image,holiday_themes.starting_price as StartingPrice,holiday_themes.theme_icon as Icon, holiday_themes.meta_title as MetaTitle,holiday_themes.meta_keyword as Metakeyword,holiday_themes.meta_description as MetaDescription,holiday_themes.meta_robots as MetaRobots')
            ->where(['holiday_themes.status'=>'active','holiday_themes.show_on_home'=>1,'holiday_themes.theme_slug'=>$slug]);
            $builder->groupStart();
                $builder->where(["holiday_themes.web_partner_id"=>$web_partner_id]);
                /* $builder->orWhere(["holiday_themes.web_partner_id"=>0]); */
                $builder->groupEnd();
            $holiday_themes =       $builder->limit(1)->orderBy("holiday_themes.id", "DESC")
            ->get()->getRowArray();
        return $holiday_themes;
    }

    public function holiday_destination_by_slug($web_partner_id,$slug)
    {
        $builder = $this->db->table('holiday_destination')->select('holiday_destination.id,holiday_destination.destination_name as Name,holiday_destination.destination_slug as Slug,holiday_destination.destination_image as Image,holiday_destination.starting_price as StartingPrice,holiday_destination.destination_type as DestinationType, holiday_destination.meta_title as MetaTitle,holiday_destination.meta_keyword as Metakeyword,holiday_destination.meta_description as MetaDescription,holiday_destination.meta_robots as MetaRobots')
            ->where(['holiday_destination.status'=>'active','holiday_destination.show_on_home'=>1,'holiday_destination.destination_slug'=>$slug]);
            $builder->groupStart();
                $builder->where(["holiday_destination.web_partner_id"=>$web_partner_id]);
                /* $builder->orWhere(["holiday_themes.web_partner_id"=>0]); */
                $builder->groupEnd();
            $holiday_destination =       $builder->limit(1)->orderBy("holiday_destination.id", "DESC")
            ->get()->getRowArray();
        return $holiday_destination;
    }

    public function holiday_list($web_partner_id,$limit = 8)
    {
        $builder  =   $this->db->table('holiday_list')->select("holiday_list.id,holiday_list.holiday_name as Name,holiday_list.holiday_slug as Slug,
        holiday_list.starting_price_standard as StandardPrice,holiday_list.holiday_image as Image,holiday_list.holiday_destination_id as DestinationId,,holiday_list.holiday_themes_id as ThemeId,
        holiday_list.holiday_includes as Includes,holiday_list.holiday_inclusion_id as InclusionId,holiday_list.starting_price_deluxe as DeluxePrice,
        holiday_list.starting_price_luxury as LuxuryPrice,
        concat('[', group_concat(JSON_OBJECT('id',holiday_itinerary.id,'itinerary_title',holiday_itinerary.itinerary_title,
        'city',holiday_itinerary.city,'holiday_list_id',holiday_itinerary.holiday_list_id) separator ','), ']') as itinerary")
            ->where(['holiday_list.status'=>'active']);
            $builder->groupStart();
            $builder->where(["holiday_list.web_partner_id"=>$web_partner_id]);
            /* $builder->orWhere(["holiday_list.web_partner_id"=>0]); */
            $builder->groupEnd();
            return $builder->where('holiday_list.show_on_home', 1)->limit($limit)->orderBy("holiday_list.id", "DESC")
            ->join("holiday_itinerary", 'holiday_itinerary.holiday_list_id = holiday_list.id', 'Left')
            ->groupBy('holiday_list.id')
            ->get()->getResultArray();
    }

    public function holiday_details($holiday_slug,$web_partner_id)
    {
        $builder  = $this->db->table('holiday_list')->select("holiday_list.id,holiday_list.holiday_name as Name,holiday_list.location_data, holiday_list.holiday_slug as Slug, holiday_list.starting_price_standard as StandardPrice,holiday_list.starting_price_deluxe as DeluxePrice,holiday_list.starting_price_luxury as LuxuryPrice,holiday_list.advance_value as AdvanceValue,holiday_list.advance_days as AdvanceDays,holiday_list.price_on_request as PriceOnRequest, holiday_list.holiday_image as HolidayImage,holiday_list.holiday_themes_id as Themes,holiday_list.holiday_destination_id as Destinations,holiday_list.holiday_includes as Includes,
        holiday_list.holiday_inclusion_id as Inclusion,holiday_list.holiday_exclusion_id as Exclusion, holiday_list.holiday_description as Description, holiday_list.policies as Policies,holiday_list.passport_required_adult as AdultPassportRequired,holiday_list.passport_required_child as ChildPassportRequired, holiday_list.pan_card_required_adult as AdultPanCardRequired, holiday_list.pan_card_required_child as ChildPanCardRequired, holiday_list.book_online as BookOnline,holiday_list.meta_title as MetaTitle,holiday_list.meta_keyword as Metakeyword,holiday_list.meta_description as MetaDescription,
        holiday_list.meta_robots as MetaRobots, holiday_list.payment_structure as PaymentStructure, holiday_list.documentation as Documentation,holiday_list.cancellation_policy as CancellationPolicy,holiday_list.refund_policy as RefundPolicy,
        
      concat('[', group_concat(JSON_OBJECT('id',holiday_itinerary.id,'itinerary_title',holiday_itinerary.itinerary_title,'city',holiday_itinerary.city,'holiday_list_id',holiday_itinerary.holiday_list_id,'itinerary_description',holiday_itinerary.itinerary_description,'day_meal_plan',holiday_itinerary.day_meal_plan,'itinerary_image',holiday_itinerary.itinerary_image,'flight_data',holiday_itinerary.flight_data,'hotel_data',holiday_itinerary.hotel_data,'transfer_data',holiday_itinerary.transfer_data) separator ','), ']') as itinerary")
            ->where('holiday_list.status', 'active');
            $builder->groupStart();
            $builder->where(["holiday_list.web_partner_id"=>$web_partner_id]);
            /* $builder->orWhere(["holiday_list.web_partner_id"=>0]); */
            $builder->groupEnd();
            $builder->where('holiday_list.holiday_slug', $holiday_slug);
           return  $builder->join("holiday_itinerary", 'holiday_itinerary.holiday_list_id = holiday_list.id', 'Left')
            ->groupBy('holiday_list.id')
            ->get()->getRowArray();
    }

    public function holiday_inclusions($ids,$web_partner_id)
    {
        $ids = explode(',', $ids);
        $builder =  $this->db->table('holiday_inclusion')->select("inclusion_title as Title")->where('holiday_inclusion.status', 'active')->whereIn('id', $ids);
        $builder->groupStart();
        $builder->where(["holiday_inclusion.web_partner_id"=>$web_partner_id]);
       /*  $builder->orWhere(["holiday_inclusion.web_partner_id"=>0]); */
        $builder->groupEnd();
        return $builder->get()->getResultArray();

    }

    public function holiday_exclusion($ids,$web_partner_id)
    {
        $ids = explode(',', $ids);
        $builder =  $this->db->table('holiday_exclusion')->select("exclusion_title as Title")->where('holiday_exclusion.status', 'active')->whereIn('id', $ids);
        $builder->groupStart();
        $builder->where(["holiday_exclusion.web_partner_id"=>$web_partner_id]);
        /* $builder->orWhere(["holiday_exclusion.web_partner_id"=>0]); */
        $builder->groupEnd();
        return $builder->get()->getResultArray();

    }

    public function holiday_destination($ids,$web_partner_id)
    {
        $ids = explode(',', $ids);

        $builder  =   $this->db->table('holiday_destination')->select("destination_name as Name, destination_image as Image")->where('holiday_destination.status', 'active')->whereIn('id', $ids);
        $builder->groupStart();
        $builder->where(["holiday_destination.web_partner_id"=>$web_partner_id]);
       /*  $builder->orWhere(["holiday_destination.web_partner_id"=>0]); */
        $builder->groupEnd();
       return  $builder->get()->getResultArray();
    }

    public function holiday_themes($ids,$web_partner_id)
    {
        $ids = explode(',', $ids);

        $builder = $this->db->table('holiday_themes')->select("theme_name as Name,theme_image as Image ,holiday_themes.theme_icon as Icon")->where('holiday_themes.status', 'active')->whereIn('id', $ids);
        $builder->groupStart();
        $builder->where(["holiday_themes.web_partner_id"=>$web_partner_id]);
       /*  $builder->orWhere(["holiday_themes.web_partner_id"=>0]); */
        $builder->groupEnd();
       return  $builder->get()->getResultArray();
    }

    public function holiday_gallery($id,$web_partner_id)
    {

        $builder =  $this->db->table('holiday_gallery')->select("images,image_title as Title")->where('holiday_gallery.status', 'active')->where('holiday_list_id', $id);    
        $builder->groupStart();
        $builder->where(["holiday_gallery.web_partner_id"=>$web_partner_id]);
      /*   $builder->orWhere(["holiday_gallery.web_partner_id"=>0]); */
        $builder->groupEnd();
       return  $builder->get()->getResultArray();
    }

    public function holiday_price($holiday_id,$web_partner_id)
    {
        $builder  =   $this->db->table('holiday_price')->select("id,start_date as StartDate,end_date as EndDate, standard_adult_price as StandardAdultPrice,standard_child_price as StandardChildPrice,
        standard_extra_bad_price as StandardExtraBadPrice,deluxe_adult_price  as DeluxeAdultPrice,deluxe_child_price as DeluxeChildPrice,deluxe_extra_bad_price as DeluxeExtraBedPrice,
        luxury_adult_price as LuxuryAdultPrice,luxury_child_price as LuxuryChildPrice,luxury_extra_bad_price as LuxuryExtraBadPrice")->where('holiday_list_id', $holiday_id);
       /*  $builder->groupStart();
        $builder->where(["holiday_price.web_partner_id"=>$web_partner_id]);
        $builder->orWhere(["holiday_price.web_partner_id"=>0]);
        $builder->groupEnd(); */
        return $builder->get()->getResultArray();
    }

    public function holiday_details_block($holiday_id,$web_partner_id)
    {
        $builder  =   $this->db->table('holiday_list')->select("holiday_list.id,holiday_list.holiday_name as Name, holiday_list.holiday_slug as Slug, holiday_list.starting_price_standard as StandardPrice,holiday_list.starting_price_deluxe as DeluxePrice,holiday_list.starting_price_luxury as LuxuryPrice,holiday_list.advance_value as AdvanceValue,holiday_list.advance_days as AdvanceDays,holiday_list.price_on_request as PriceOnRequest, holiday_list.holiday_image as HolidayImage,holiday_list.holiday_themes_id as Themes,holiday_list.holiday_destination_id as Destinations,holiday_list.holiday_includes as Includes,
        holiday_list.holiday_inclusion_id as Inclusion,holiday_list.holiday_exclusion_id as Exclusion, holiday_list.holiday_description as Description, holiday_list.policies as Policies,holiday_list.passport_required_adult as AdultPassportRequired,holiday_list.passport_required_child as ChildPassportRequired, holiday_list.pan_card_required_adult as AdultPanCardRequired, holiday_list.pan_card_required_child as ChildPanCardRequired, holiday_list.book_online as BookOnline,holiday_list.meta_title as MetaTitle,holiday_list.meta_keyword as Metakeyword,holiday_list.meta_description as MetaDescription,
        holiday_list.meta_robots as MetaRobots, holiday_list.payment_structure as PaymentStructure, holiday_list.documentation as Documentation,holiday_list.cancellation_policy as CancellationPolicy,holiday_list.refund_policy as RefundPolicy,
        
      concat('[', group_concat(JSON_OBJECT('id',holiday_itinerary.id,'itinerary_title',holiday_itinerary.itinerary_title,'city',holiday_itinerary.city,'holiday_list_id',holiday_itinerary.holiday_list_id,'itinerary_description',holiday_itinerary.itinerary_description,'day_meal_plan',holiday_itinerary.day_meal_plan,'itinerary_image',holiday_itinerary.itinerary_image,'flight_data',holiday_itinerary.flight_data,'hotel_data',holiday_itinerary.hotel_data,'transfer_data',holiday_itinerary.transfer_data) separator ','), ']') as itinerary")
            ->where('holiday_list.status', 'active')->where('holiday_list.id', $holiday_id);
            $builder->groupStart();
            $builder->where(["holiday_list.web_partner_id"=>$web_partner_id]);
            /* $builder->orWhere(["holiday_list.web_partner_id"=>0]); */
            $builder->groupEnd();
            return $builder->join("holiday_itinerary", 'holiday_itinerary.holiday_list_id = holiday_list.id', 'Left')
            ->groupBy('holiday_list.id')
            ->get()->getRowArray();
    }

    public function holiday_price_block($holiday_id, $price_id)
    {
        return $this->db->table('holiday_price')->select("id,start_date as StartDate,end_date as EndDate, standard_adult_price as StandardAdultPrice,standard_child_price as StandardChildPrice,
        standard_extra_bad_price as StandardExtraBadPrice,deluxe_adult_price  as DeluxeAdultPrice,deluxe_child_price as DeluxeChildPrice,deluxe_extra_bad_price as DeluxeExtraBadPrice,
        luxury_adult_price as LuxuryAdultPrice,luxury_child_price as LuxuryChildPrice,luxury_extra_bad_price as LuxuryExtraBadPrice")->where('id', $price_id)->where('holiday_list_id', $holiday_id)->get()->getRowArray();
    }

    function insert_tts_holiday_logs($web_partner_id, $tts_search_token, $request, $response, $service, $holiday_list_id = null, $tts_index_response = null)
    {
        $insertlog = array(
            'web_partner_id' => $web_partner_id,
            'tts_search_token' => $tts_search_token,
            'request' => json_encode($request),
            'response' => json_encode($response),
            'holiday_list_id' => $holiday_list_id,
            'service' => $service,
            'created' => strtotime(Time::now())
        );
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_holiday_log');
        $builder->insert($insertlog);
    }

    function get_block_data($tts_search_token)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_holiday_log');
        $builder->select('response,service');
        $builder->where(['tts_search_token' => $tts_search_token]);
        $builder->orderBy("id", "DESC");
        return $builder->get()->getRowArray();
    }

    function get_block_detail_data($tts_search_token)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_holiday_log');
        $builder->select('response,service');
        $builder->where(['tts_search_token' => $tts_search_token, 'service' => 'blockholiday']);
        $builder->orderBy("id", "DESC");
        return $builder->get()->getRowArray();
    }


    function getbookingDetailByToken($BookingId, $fields)
    {
        $builder = $this->db->table('holiday_booking_list');
        $builder->where("id", $BookingId);
        $builder->select($fields);
        return $builder->get()->getRowArray();
    }

    function getTravellerDetailById($booking_id)
    {
        $builder = $this->db->table('holiday_booking_travelers');
        $builder->where("holiday_booking_id", $booking_id);
        $builder->select('*');
        return $builder->get()->getResultArray();
    }

    function GetpartnerInfo($tableName, $Where, $fieldName)
    {
        return $this->db->table($tableName)->select($fieldName)->where($Where)->get()->getRowArray();

    }

    function super_admin_detail($fieldName)
    {
        $builder = $this->db->table('super_admin_website_setting');
        $builder->select($fieldName);
        return $builder->get()->getRowArray();
    }


    function getWebpartnerBookingAccountInfo($fieldName, $where)
    {
        $builder = $this->db->table('web_partner_account_log');
        $builder->select($fieldName);
        $builder->where($where);
        return $builder->get()->getRowArray();
    }

    function getBookingAccountInfo($tableName,$fieldName,$where){
        $builder = $this->db->table($tableName);
        $builder->select($fieldName);
        $builder->where($where);
        return $builder->get()->getRowArray();
    }


    function get_super_admin_markup($web_partner_class_id, $Themes, $Destination)
    {


        $builder = $this->db->table("super_admin_holiday_markup")->select("markup_type,value,max_limit,display_markup");
        $builder->where('find_in_set("' . $web_partner_class_id . '", web_partner_class_id) <> 0');
        $builder->where('find_in_set("' . $Themes . '", theme_id) <> 0');
        $builder->where('find_in_set("' . $Destination . '", destination_id) <> 0');
        $builder->where("status", "active");

        return $builder->get()->getRowArray();
    }

    function get_super_admin_discount($web_partner_class_id, $Themes, $Destination)
    {

        $builder = $this->db->table("super_admin_holiday_discount")->select("discount_type,value,max_limit,extra_discount");
        $builder->where('find_in_set("' . $web_partner_class_id . '", web_partner_class_id) <> 0');
        $builder->where('find_in_set("' . $Themes . '", theme_id) <> 0');
        $builder->where('find_in_set("' . $Destination . '", destination_id) <> 0');
        $builder->where("status", "active");

        return $builder->get()->getRowArray();
    }






    function get_super_admin_markup_inarray($web_partner_class_id, $Themes, $Destination)
    {

        $Themes = explode(',', $Themes);
        $Destination = explode(',', $Destination);

        $builder = $this->db->table("super_admin_holiday_markup")->select("markup_type,value,max_limit,display_markup,destination_id,theme_id");
        $builder->where('find_in_set("' . $web_partner_class_id . '", web_partner_class_id) <> 0');
        $builder->where("status", "active");

        $query =  $builder->get()->getRowArray();

        $return_data='';
        if ($query){

            $destination_id = explode(',',$query['destination_id']);

            $theme_id = explode(',',$query['theme_id']);

            $Destination_array=array_intersect($destination_id,$Destination);

            $Themes_array=array_intersect($theme_id,$Themes);

            if (!empty($Destination_array) && !empty($Themes_array)){
                $return_data = $query;
            }
        }

        return $return_data;
    }

    function get_super_admin_discount_inarray($web_partner_class_id, $Themes, $Destination)
    {
        $Themes = explode(',', $Themes);
        $Destination = explode(',', $Destination);
        $builder = $this->db->table("super_admin_holiday_discount")->select("discount_type,value,max_limit,extra_discount,destination_id,theme_id");
        $builder->where('find_in_set("' . $web_partner_class_id . '", web_partner_class_id) <> 0');

        $builder->where("status", "active");

        $query =  $builder->get()->getRowArray();

        $return_data='';
        if ($query){

            $destination_id = explode(',',$query['destination_id']);

            $theme_id = explode(',',$query['theme_id']);

            $Destination_array=array_intersect($destination_id,$Destination);

            $Themes_array=array_intersect($theme_id,$Themes);

            if (!empty($Destination_array) && !empty($Themes_array)){
                $return_data = $query;
            }
        }

        return $return_data;
    }



    function get_super_admin_gst_state_code()
    {
        $builder = $this->db->table('super_admin_website_setting');
        $builder->select('gst_state_code');
        return $builder->get()->getRowArray();
    }

    function get_booking_info($array_condition)
    {
        return $this->db->table('holiday_booking_list')->select('id,web_partner_id,tts_search_token')->where($array_condition)->get()->getRowArray();
    }

    function getaccountLogCreditNote($web_partner_id, $refund_account_id)
    {
        return $this->db->table('web_partner_account_log')->select('id,web_partner_id,acc_ref_number,created')->where('web_partner_id', $web_partner_id)->where('id', $refund_account_id)->get()->getRowArray();
    }

    function getaccountLogWlCreditNote($tableName,$web_partner_id,$refund_account_id){
        return $this->db->table($tableName)->select('id,web_partner_id,acc_ref_number,invoice_number,created')->where('web_partner_id', $web_partner_id)->where('id', $refund_account_id)->get()->getRowArray();
    }

    function getwhiteLabelSetting($web_partner_id)
    {
        $builder  =  $this->db->table('whitelabel_webpartner_setting');
        $builder->select('domain_name,is_direct_website');
        if($web_partner_id){
            $builder->where('web_partner_id',$web_partner_id);
        }
       return $result   = $builder->get()->getRowArray();
    }
}
