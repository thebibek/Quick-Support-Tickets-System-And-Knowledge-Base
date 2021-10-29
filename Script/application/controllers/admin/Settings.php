<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {
    function __construct(){
        parent::__construct();
        //check if backend login
        $this->is_admin_login();
        //Load Libraries
        $this->load->library(array('pagination'));
        //pagination settings
        $this->perPage = 10;
    }
    //site settings
	public function site_settings()
	{
        $data['title']=$this->lang->line("text_site_settings");
        if($this->permitted('site_settings')){
            if($this->input->post()){
                $this->form_validation->set_rules('site_title','Site Title','trim|required' );
                $this->form_validation->set_rules('site_email','Site Email','trim|required|valid_email');
                $this->form_validation->set_rules('site_phone','Site Phone','trim|required');

                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();

                }else{
                    $post_data = array(
                        'site_name' => $this->input->post('site_title'),
                        'site_email' => $this->input->post('site_email'),
                        'site_phone' => $this->input->post('site_phone'),
                    );
                    //upload config
                    $config['upload_path'] = 'uploads/site/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|ico';
                    $config['encrypt_name'] = TRUE;
                    $config['overwrite'] = TRUE;
                    $config['max_size'] = '1024'; //1 MB
                    //Upload Site Logo
                    if(isset($_FILES['site_logo']['name'])){
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('site_logo')) {
                            $success = FALSE;
                            $message = $this->upload->display_errors();
                            $json_array = array('success' => $success, 'message' => $message);
                            echo json_encode($json_array);
                            exit();
                        } else {
                            $upload_data=$this->upload->data();
                            $post_data['site_logo']=$upload_data['file_name'];
                        }
                    }
                    //Upload Site Favivon
                    if(isset($_FILES['site_favicon']['name'])){
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('site_favicon')) {
                            $success = FALSE;
                            $message = $this->upload->display_errors();
                            $json_array = array('success' => $success, 'message' => $message);
                            echo json_encode($json_array);
                            exit();
                        } else {
                            $upload_data=$this->upload->data();
                            $post_data['site_favicon']=$upload_data['file_name'];
                        }
                    }
                    //XXS Clean
                    $post_data = $this->security->xss_clean($post_data);
                    $result = $this->settings_model->save_config($post_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_site_settings_updated");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_site_settings_not_updated");
                    }
                }
                $json_array = array('success' => $success, 'message' => $message);
                echo json_encode($json_array);
                exit();
            }
            $data['sub_view'] = $this->load->view('admin/settings/site_settings', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $this->load->view('admin/_layout', $data);
    }
    
    //social media settings
	public function social_media_settings()
	{
        $data['title']=$this->lang->line("text_social_media_settings");
        if($this->permitted('social_media_settings')){
            if($this->input->post()){
                $post_data = array(
                    'facebook' => $this->input->post('facebook'),
                    'twitter' => $this->input->post('twitter'),
                    'instagram' => $this->input->post('instagram'),
                    'linkedin' => $this->input->post('linkedin'),
                    'google_plus' => $this->input->post('google-plus'),
                    'youtube' => $this->input->post('youtube'),
                    'github' => $this->input->post('github')               
                );
                //XXS Clean
                $post_data = $this->security->xss_clean($post_data);
                $result = $this->settings_model->save_config($post_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_social_media_settings_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_social_media_settings_not_updated");
                }
                $json_array = array('success' => $success, 'message' => $message);
                echo json_encode($json_array);
                exit();
            }
            $data['sub_view'] = $this->load->view('admin/settings/social_media_settings', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $this->load->view('admin/_layout', $data);
    }

    //seo settings
	public function seo_settings()
	{
        $data['title']=$this->lang->line("text_seo_settings");
        if($this->permitted('seo_settings')){
            if($this->input->post()){
                $this->form_validation->set_rules('meta_title','Meta Title','trim|required' );
                $this->form_validation->set_rules('meta_description','Meta Description','trim|required');
                $this->form_validation->set_rules('meta_keywords','Meta Keywords','trim|required');
    
                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();
    
                }else{
                    $post_data = array(
                        'meta_title' => $this->input->post('meta_title'),
                        'meta_description' => $this->input->post('meta_description'),
                        'meta_keywords' => $this->input->post('meta_keywords'),
                        'google_analytics' => $this->input->post('google_analytics'),
                        
                    );
                    //XXS Clean
                    $post_data = $this->security->xss_clean($post_data);
                    $result = $this->settings_model->save_config($post_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_seo_settings_updated");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_seo_settings_not_updated");
                    }
                }
                $json_array = array('success' => $success, 'message' => $message);
                echo json_encode($json_array);
                exit();
            }
            $data['sub_view'] = $this->load->view('admin/settings/seo_settings', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $this->load->view('admin/_layout', $data);
    }

    //role based permission
    public function permissions(){
        $data['title']=$this->lang->line("text_permissions");
        if($this->permitted('role_permissions')){
            if($this->input->post()){
                $post_data = array(
                    'role_permission_id' => $this->input->post('role_permission_id')
                    
                );
                //XXS Clean
                $post_data = $this->security->xss_clean($post_data);
                $result = $this->settings_model->change_permission($post_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_permission_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_permission_not_updated");
                }
                $json_array = array('success' => $success, 'message' => $message);
                echo json_encode($json_array);
                exit();
            }
            $data['sub_view'] = $this->load->view('admin/settings/permissions', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $this->load->view('admin/_layout', $data);
    }

    //ajax paginate permissions
    public function list_permissions_ajax($page=0){
        $conditions = array();
        // Row position
        if($page != 0){
            $page = ($page-1) * $this->perPage;
        }
        $keyword=$this->input->post('keyword');
        if(!empty($keyword)){
            $conditions['search']['keyword'] = $keyword;
        }
        //get permissions count
        $permissions=$this->settings_model->get_permissions($conditions);
        if($permissions){
            $permissionsCount=count($permissions);
        }else{
            $permissionsCount=0;
        }
        //set start and limit
        $conditions['start'] = $page;
        $conditions['limit'] = $this->perPage;
        //get all permissions
        $permissions = $this->settings_model->get_permissions($conditions);
        //get pagination confing
        $config=$this->pagination_config($base_url=base_url().'settings/list_permissions_ajax',$total_rows=$permissionsCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $permissions_list=array();
        if(isset($permissions) && $permissions!=NULL){
            $i=0;
            foreach($permissions as $permission){
                $permissions_list[$i]['id'] = $permission['id'];
                $permissions_list[$i]['name'] = $permission['permission_name'];
                $permissions_list[$i]['slug'] = $permission['permission_slug'];
                $permissions_list[$i]['info'] = $permission['permission_info'];
                $permissions_list[$i]['agent'] = $this->settings_model->get_role_permissions($role='agent',$permission_slug=$permission['permission_slug']);
                $permissions_list[$i]['manager'] = $this->settings_model->get_role_permissions($role='manager',$permission_slug=$permission['permission_slug']);
                $i++;
            }
        }
        $data['permissions_list']=$permissions_list;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('admin/settings/list_permissions_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
    }

    //app settings
	public function app_settings()
	{
        $data['title']=$this->lang->line("text_app_settings");
        if($this->permitted('app_settings')){
            if($this->input->post()){
                $post_data = array(
                    'email_notify_new_ticket' => (int)(bool)$this->input->post('email_notify_new_ticket'),
                    'email_notify_assign_ticket' => (int)(bool)$this->input->post('email_notify_assign_ticket'),
                    'send_password_created_new_user' => (int)(bool)$this->input->post('send_password_created_new_user'),          
                    'allow_guest_ticket_submission' => (int)(bool)$this->input->post('allow_guest_ticket_submission')          
                );
                //XXS Clean
                $post_data = $this->security->xss_clean($post_data);
                $result = $this->settings_model->save_config($post_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_app_settings_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_app_settings_not_updated");
                }
                $json_array = array('success' => $success, 'message' => $message);
                echo json_encode($json_array);
                exit();
            }
            $data['sub_view'] = $this->load->view('admin/settings/app_settings', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $this->load->view('admin/_layout', $data);
    }
    
    //Email Settings
    public function email_settings(){
        $data['title']=$this->lang->line("text_email_settings");
        if($this->permitted('email_settings')){
            if($this->input->post()){
                $this->form_validation->set_rules('mail_from_title','Mail From Title','trim|required' );
                $this->form_validation->set_rules('mail_from_email','Mail From Email','trim|required|valid_email');
                $this->form_validation->set_rules('mail_driver','Mail Driver','trim|required');

                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();

                }else{
                    $post_data = array(
                        'mail_from_title' => $this->input->post('mail_from_title'),
                        'mail_from_email' => $this->input->post('mail_from_email'),
                        'mail_driver' => $this->input->post('mail_driver'),
                        'mail_host' => $this->input->post('mail_host'),
                        'mail_port' => $this->input->post('mail_port'),
                        'mail_username' => $this->input->post('mail_username'),
                        'mail_password' => $this->input->post('mail_password'),
                        'mail_encryption' => $this->input->post('mail_encryption')
                        
                    );
                    //XXS Clean
                    $post_data = $this->security->xss_clean($post_data);
                    $result = $this->settings_model->save_config($post_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_email_settings_updated");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_email_settings_not_updated");
                    }
                }
                $json_array = array('success' => $success, 'message' => $message);
                echo json_encode($json_array);
                exit();
            }
            $data['sub_view'] = $this->load->view('admin/settings/email_settings', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        
        $this->load->view('admin/_layout', $data);
    }

    //Email Templates
    public function email_templates(){
        if($this->permitted('email_templates')){
            $data['title']=$this->lang->line("text_email_templates");
            $data['sub_view'] = $this->load->view('admin/settings/email_templates', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $this->load->view('admin/_layout', $data);
    }

    //List Email Templates - AJAX
    public function list_email_templates($page=0){
        $conditions=array();
        // Row position
        if($page != 0){
            $page = ($page-1) * $this->perPage;
        }
        //get templates count
        $templates=$this->settings_model->get_email_templates($conditions);
        if($templates){
            $templatesCount=count($templates);
        }else{
            $templatesCount=0;
        }
        //set start and limit
        $conditions['start'] = $page;
        $conditions['limit'] = $this->perPage;

        //get all templates
        $templates=$this->settings_model->get_email_templates($conditions);
        //get pagination confing
        $config=$this->pagination_config($base_url=base_url().'admin/settings/list_email_templates',$total_rows=$templatesCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $data['templates']=$templates;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('admin/settings/email_templates_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
    }

    //Edit Email Template - Load View
    public function edit_template(){
        if($this->permitted('email_templates')){
            if($this->input->post()){
                $data['title']=$this->lang->line("text_edit_email_template");
                $template_id = $this->input->post('template_id');
                //get template by id
                $result = $this->settings_model->get_email_template($template_id);
                $data['template']=$result;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/settings/edit-email-template',$data,TRUE);
                
            }
        }else{
            $content = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
    }

    //Edit Email Template - Update
    public function update_template(){
        if($this->input->post()){
            //check validation
            $this->form_validation->set_rules('template_name','Template Name','trim|required');
            $this->form_validation->set_rules('template_content','Template Content','trim|required');
            if ($this->form_validation->run() == FALSE) {
                $success = FALSE;
                $message = validation_errors();
            }else{
                $template_id=$this->input->post('template_id');
                $update_data = array(
                    'template_name' => $this->input->post('template_name'),
                    'template_content' => $this->input->post('template_content'),
                    'updated_by' => $this->get_user_id(),
                    'updated_on' => date('Y-m-d H:i:s')
                );
                //XXS Clean
                $update_data = $this->security->xss_clean($update_data);
                $result = $this->settings_model->update_email_template($template_id,$update_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_update_template_success");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_update_template_error");
                }
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }
    
}