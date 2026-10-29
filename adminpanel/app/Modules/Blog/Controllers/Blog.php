<?php

namespace Modules\Blog\Controllers;

use App\Modules\Blog\Models\BlogModel;
use App\Modules\Blog\Models\CategoryModel;
use App\Controllers\BaseController;
use Modules\Blog\Config\Validation;


class Blog extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Blog";
        $this->folder_name = 'blog';

        $admin_cookie_data=admin_cookie_data();

        $this->web_partner_id =$admin_cookie_data['admin_user_details']['web_partner_id'];
        $this->user_id = $admin_cookie_data['admin_user_details']['id'];
        $this->whitelabel_user=$admin_cookie_data['admin_comapny_detail']['whitelabel_user'];
        $whitelabel_setting_data=$admin_cookie_data['whitelabel_setting_data'];
        if ($this->whitelabel_user != "active" || $whitelabel_setting_data['b2c_business']!="active") {
            access_denied();
        }
        if(permission_access_error("Blog","Blog_Module")) {

        }
    }

    public function index()
    {
    
        $blogModel = new BlogModel();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $blogModel->search_data($this->web_partner_id, $this->request->getGet());
        } else {
            $lists = $blogModel->blog_list($this->web_partner_id);
        }

        $data = [
            'title' => $this->title,
            'blog_list' => $lists,
            'view' => "Blog\Views\blog-list",
            'pager' => $blogModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }

    public function add_blog_view()
    {
        if (permission_access_error("Blog", "add_blog")) {
            $blogModel = new BlogModel();

            $data = [
                'blog_category_list' => $blogModel->blog_category_list($this->web_partner_id),
                'blog_tag' => []
            ];
            $add_blog_view = view('Modules\Blog\Views\add-blog', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_blog()
    {
        if (permission_access("Blog", "add_blog")) {
            $validate = new Validation();
            $rules = $this->validate($validate->blogs_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BlogsModel = new BlogModel();
                $data = $this->request->getPost();


                $value = $data['post_slug'];
                $existingValue = $BlogsModel->CheckUniqueSlug($value, $this->web_partner_id);
                if ($existingValue) {
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["post_slug" => " Blog slug already exists"]);
                    return $this->response->setJSON($data_validation);
                }


                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'post_images';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 360, 'height' => 200);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data['post_images'] = $image_upload['file_name'];
                    $added_data = $BlogsModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Blog Successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Blog not  added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function edit_blog_view()
    {
        if (permission_access_error("Blog", "edit_blog")) {
            $blog_id = dev_decode($this->request->uri->getSegment(3));
            $BlogsModel = new BlogModel();
            $data = [
                'title' => 'BLOG',
                'blog_id' => $blog_id,
                'blog_details' => $BlogsModel->blog_list_details($blog_id, $this->web_partner_id),
                'blog_category_list' => $BlogsModel->blog_category_list($this->web_partner_id),
            ];
            $blog_details = view('Modules\Blog\Views\edit_blog', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function edit_blogs()
    {
        if (permission_access("Blog", "edit_blog")) {

            $blog_id = dev_decode($this->request->uri->getSegment(3));
            $field_name = 'post_images';

            $validate = new Validation();

            $post_images = $this->request->getFile($field_name);
            if ($post_images->getName() == '') {
                unset($validate->blogs_validation[$field_name]);
            }

            $rules = $this->validate($validate->blogs_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BlogsModel = new BlogModel();
                $data = $this->request->getPost();

                $previous_data = $BlogsModel->blog_list_details($blog_id, $this->web_partner_id);

                if ($previous_data['post_slug'] != $data['post_slug']) {
                    $value = $data['post_slug']; 
                    $existingValue = $BlogsModel->CheckUniqueSlug($value, $this->web_partner_id);
                    if ($existingValue) {
                        $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["post_slug" => "Blog slug already exists"]);
                        return $this->response->setJSON($data_validation);
                    }
                }

             
                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 360, 'height' => 200);
                    $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                    if ($image_upload['status_code'] == 0) {

                        if ($previous_data[$field_name]) {
                            if (file_exists(FCPATH."../uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                unlink(FCPATH."../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                unlink(FCPATH."../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                            }
                        }

                        $data['modified'] = create_date();
                        $data[$field_name] = $image_upload['file_name'];
                        $added_data = $BlogsModel->where("id", $blog_id)->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "Blog Successfully Edit", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "Blog not  Edit", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {

                    $data['modified'] = create_date();
                    $data['post_images'] = $previous_data[$field_name];
                    $added_data = $BlogsModel->where("id", $blog_id)->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Blog Successfully Edit", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Blog not  Edit", "Class" => "error_popup", "Reload" => "true");
                    }
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function remove_blog()
    {
        if (permission_access("Blog", "delete_blog")) {
            $BlogsModel = new BlogModel();
            $blog_ids = $this->request->getPost('checklist');

            $field_name = 'post_images';

            foreach ($blog_ids as $id) {
                $blog_details = $BlogsModel->delete_image($id, $this->web_partner_id);

                if ($blog_details[$field_name]) {
                    if (file_exists(FCPATH."../uploads/$this->folder_name/" . $blog_details[$field_name])) {
                        unlink(FCPATH."../uploads/$this->folder_name/" . $blog_details[$field_name]);
                        unlink(FCPATH."../uploads/$this->folder_name/thumbnail/" . $blog_details[$field_name]);
                    }
                }

                $blog_delete = $BlogsModel->remove_blog($id, $this->web_partner_id);
            }

            if ($blog_delete) {
                $message = array("StatusCode" => 0, "Message" => "Blog  Successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Blog  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function blog_status_change()
    {

        if (permission_access("Blog", "blog_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->blog_status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BlogModel = new BlogModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $BlogModel->blog_status_change($this->web_partner_id, $ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Blog status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Blog status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function blog_category_list()
    {
        if (permission_access_error("Blog", "blog_category_list")) {
            $categoryModel = new CategoryModel();
            $data = [
                'title' => "Blog Category",
                'data_list' => $categoryModel->blog_category_list($this->web_partner_id),
                'view' => "Blog\Views\blog-category-list",
                'pager' => $categoryModel->pager
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_blog_category_view()
    {
        if (permission_access_error("Blog", "add_blog_category")) {
            $add_blog_view = view('Modules\Blog\Views\add-blog-category');
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_blog_category()
    {
        if (permission_access("Blog", "add_blog_category")) {
            $validate = new Validation();
            $rules = $this->validate($validate->category_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $categoryModel = new CategoryModel();
                $data = $this->request->getPost(); 


                $value = $data['category_slug'];
                $existingValue = $categoryModel->CheckUniqueSlug($value, $this->web_partner_id);
                if ($existingValue) {
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["category_slug" => "Blog category slug already exists"]);
                    return $this->response->setJSON($data_validation);
                }

                #use getFile() for single image or getFiles() for multiple image
                $file = $this->request->getFile('category_img');
                $field_name = 'category_img';
                $resizeDim = array('width' => 360, 'height' => 200);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);

                if ($image_upload['status_code'] == 0) {
                    $data['created'] = time();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data['category_img'] = $image_upload['file_name'];
                    $added_data = $categoryModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Blog Category Successfully added", "Class" => "success_popup");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Blog Category not  added", "Class" => "error_popup",);
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function edit_blog_category_view()
    {
        if (permission_access_error("Blog", "edit_blog_category")) {
            $category_id = dev_decode($this->request->uri->getSegment(3));
            $categoryModel = new CategoryModel();
            $data = [
                'title' => 'BLOG CATEGORY',
                'category_id' => $category_id,
                'category_details' => $categoryModel->blog_category_details($category_id, $this->web_partner_id),
            ];
            $blog_details = view('Modules\Blog\Views\edit-blog-category', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_blogs_category()
    {
        if (permission_access("Blog", "edit_blog_category")) {
            $field_name = 'category_img';
            $blog_id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();

            $category_images = $this->request->getFile($field_name);
            if ($category_images->getName() == '') {
                unset($validate->category_validation[$field_name]);
            }
            $rules = $this->validate($validate->category_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CategoryModel = new CategoryModel();
                $data = $this->request->getPost();
   

                $previous_data = $CategoryModel->blog_category_details($blog_id, $this->web_partner_id);

                if ($previous_data['category_slug'] != $data['category_slug']) {
                    $value = $data['category_slug']; 
                    $existingValue = $CategoryModel->CheckUniqueSlug($value, $this->web_partner_id);
                    if ($existingValue) {
                        $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["category_slug" => "Blog category slug already exists"]);
                        return $this->response->setJSON($data_validation);
                    }
                }



                #use getFile() for single image or getFiles() for multiple image
                $file = $this->request->getFile('category_img');
                $previous_data = $CategoryModel->blog_category_details($blog_id, $this->web_partner_id);
                $field_name = 'category_img';
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 360, 'height' => 200);
                    $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                    if ($image_upload['status_code'] == 0) {

                        if ($previous_data[$field_name]) {
                            if (file_exists(FCPATH."../uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                unlink(FCPATH."../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                unlink(FCPATH."../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                            }
                        }

                        $data['modified'] = time();
                        $data['category_img'] = $image_upload['file_name'];
                        $added_data = $CategoryModel->where("id", $blog_id)->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "Blog Category Successfully Edit", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "Blog not  Edit", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {

                    $data['modified'] = time();
                    $data['category_img'] = $previous_data['category_img'];
                    $added_data = $CategoryModel->where("id", $blog_id)->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Blog Category Successfully Edit", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Blog not  Edit", "Class" => "error_popup", "Reload" => "true");
                    }
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function remove_blog_category()
    {

        if (permission_access("Blog", "delete_blog_category")) {
            $categoryModel = new CategoryModel();
            $blog_ids = $this->request->getPost('checklist');

            $field_name = 'category_img';

            foreach ($blog_ids as $id) {
                $blog_details = $categoryModel->delete_image($id, $this->web_partner_id);

                if ($blog_details[$field_name]) {
                    if (file_exists(FCPATH."../uploads/$this->folder_name/" . $blog_details[$field_name])) {
                        unlink(FCPATH."../uploads/$this->folder_name/" . $blog_details[$field_name]);
                        unlink(FCPATH."../uploads/$this->folder_name/thumbnail/" . $blog_details[$field_name]);
                    }
                }

                $blog_delete = $categoryModel->remove_category($id, $this->web_partner_id);
            }

            if ($blog_delete) {
                $message = array("StatusCode" => 0, "Message" => "Blog  Category Successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Blog Category  Not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function blog_category_status_change()
    {

        if (permission_access("Blog", "blog_category_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->category_status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CategoryModel = new CategoryModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $CategoryModel->category_status_change($this->web_partner_id, $ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Category status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Category status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

}
