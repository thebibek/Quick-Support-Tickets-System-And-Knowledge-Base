<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
    function __construct(){
        parent::__construct();
        //check if backend login
        $this->is_admin_login();
        //load language
        $this->lang->load('app','english');
        //load models
		$this->load->model(array('user_model','settings_model','article_model','ticket_model'));
    }

    //profile
	public function index()
	{
        $data['title']=$this->lang->line("text_profile");
        //get user by id
        $user_id=$this->get_user_id();
        $user = $this->user_model->get_user($user_id);
        $data['user']=$user;
        $data['sub_view'] = $this->load->view('admin/profile/view_profile', $data, TRUE);
        $this->load->view('admin/_layout', $data);
    }

    //dashboard
	public function dashboard()
	{
        $data['title']=$this->lang->line("text_dashboard");
        $data['number_of_articles']=$this->settings_model->count_articles();
        $data['number_of_tickets']=$this->settings_model->count_tickets();
        $data['number_of_faqs']=$this->settings_model->count_faqs();
        $data['number_of_users']=$this->settings_model->count_users();
        //get latest articles
        $articles = $this->article_model->get_articles(array('start'=>0,'limit'=>5));
        $data['articles']=$articles;
        //get all tickets
        $conditions['search']['user_type'] = $this->get_user_type();
        $conditions['search']['user_id'] = $this->get_user_id();
        //set start and limit
        $conditions['start'] = 0;
        $conditions['limit'] = 5;
        $tickets = $this->ticket_model->get_tickets($conditions);
        $data['tickets']=$tickets;
        $data['sub_view'] = $this->load->view('admin/profile/dashboard', $data, TRUE);
        $this->load->view('admin/_layout', $data);
    }

    //update profile
    public function update_profile(){
        if($this->input->post()){
            $this->form_validation->set_rules('full_name','Full Name','trim|required' );
            $this->form_validation->set_rules('email','Email','trim|required');
            $this->form_validation->set_rules('mobile','Mobile','trim|required');

            if ($this->form_validation->run() == FALSE) {
                $success = FALSE;
                $message = validation_errors();

            }else{
                $photo = $this->input->post('profile_image');
                $user_id=$this->get_user_id();
                $update_data = array(
                    'full_name' => $this->input->post('full_name'),
                    'email' => $this->input->post('email'),
                    'mobile' => $this->input->post('mobile'),
                    'updated_by' => $user_id,
                );
                //XXS Clean
                $update_data = $this->security->xss_clean($update_data);
                $result = $this->user_model->update_profile($user_id,$update_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_profile_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_profile_not_updated");
                }elseif($result['status']==FALSE &&$result['label']=='EXIST'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_profile_email_exist");
                }
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }

    //update profile image
    public function update_profile_image(){
        if($this->input->post()){
            $photo = $this->input->post('profile_image');
            if ($photo != NULL) {
                define('UPLOAD_DIR', 'uploads/profile/');
                $img = $photo;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $image_data = base64_decode($img);
                $file = UPLOAD_DIR . uniqid() . '.png';
                file_put_contents($file, $image_data);
                $profile_image = $file;

            }else{
                $profile_image = null;
            }
            $user_id=$this->get_user_id();
            $update_data = array(
                'profile_image' => $profile_image,
                'updated_by' => $user_id,
            );
            $result = $this->user_model->update_user($user_id,$update_data);
            if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                $success = TRUE;
                $message = $this->lang->line("alert_profile_image_updated");
            }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                $success = FALSE;
                $message = $this->lang->line("alert_profile_image_not_updated");
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }

    //change password
    public function change_password(){
        if($this->input->post()){
            $this->form_validation->set_rules('old_password','Old Password','trim|required' );
            $this->form_validation->set_rules('new_password','New Password','trim|required');
            $this->form_validation->set_rules('confirm_new_password','Confirm New Password','trim|required');

            if ($this->form_validation->run() == FALSE) {
                $success = FALSE;
                $message = validation_errors();

            }else{
                $user_id=$this->get_user_id();
                $old_password=$this->input->post('old_password');
                $new_password=$this->input->post('new_password');
                $result = $this->user_model->change_password($user_id,$old_password,$new_password);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_password_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_password_not_updated");
                }elseif($result['status']==FALSE &&$result['label']=='INVALID'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_old_password_incorrect");
                }
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
        $data['title']=$this->lang->line("text_change_password");
        //get user by id
        $user_id=$this->get_user_id();
        $user = $this->user_model->get_user($user_id);
        $data['user']=$user;
        $data['sub_view'] = $this->load->view('admin/profile/change_password', $data, TRUE);
        $this->load->view('admin/_layout', $data);
    }

    //switch language
    public function switch_language($language = ""){
        $language = ($language != "") ? $language : "english";
        $site_languages=$this->config->item('site_language');
        $current_language=$site_languages[$language];
        $this->session->set_userdata('app_language', $current_language);
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }
    
}