<?php
/**
 * -----------------------------IMPORTANT-------------------------------
 * Programmer should NOT change or add any code without having a better
 * understanding how MY_CONTROLLER and Its methods been used
 * ---------------------------------------------------------------------
 *
 * My_Controller will be used for all the CRUD operations in the system.
 *
 * All the other models should be extend form My_Model
 * Most of the common operations been written in the My_Model so that
 * programmer can easily call methods in My_Model Class for all most
 * all Database Communication and minimize the coding in their projects.
 *
 */
class MY_Controller extends CI_Controller
{
    function __construct(){
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->helper('date');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('user_agent');
        $this->load->model('settings_model');
        //set app config values
        $app_data=$this->get_config();
        if(isset($app_data) && $app_data!=NULL){
            foreach($app_data as $val){
                $global_data[$val['config_name']] = $val['config_value'];
            }
        }
        $user_data = $this->session->userdata('login');
        if($user_data!=NULL){
            $user_id=$user_data['user_id'];
            $user_data=$this->settings_model->get_user($user_id);
            $global_data['user_data']=$user_data;
        }
        $global_data['permissions']=$this->get_permissions();
        
        $this->load->vars($global_data);
        
    }

    //get all app config values
    public function get_config(){
        $result=$this->settings_model->get_config();
        return $result;
    }

    //check permission
    public function permitted($permission_slug=NULL){
        if($permission_slug!=NULL || !$permission_slug!=''){
            $user_data = $this->session->userdata('login');
            if($user_data!=NULL){
                $user_id=$user_data['user_id'];
                $user_role=$user_data['user_role_slug'];
                $check_permission = $this->settings_model->check_permission($user_id,$user_role,$permission_slug);
                if($check_permission==TRUE){
                    return TRUE;
                }else{
                    return FALSE;
                }
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

    //get all permissions
    public function get_permissions(){
        $permissions=array();
        $user_data = $this->session->userdata('login');
        if($user_data!=NULL){
            if($user_data['is_admin_login']==TRUE){
                $user_id=$user_data['user_id'];
                $user_role=$user_data['user_role_slug'];
                $permission_data =$this->settings_model->get_permissions();
                if(sizeof($permission_data)>0){
                    foreach($permission_data as $data){
                        $permissions[$data['permission_slug']]=$this->settings_model->check_permission($user_id,$user_role,$data['permission_slug']);
                    }
                    return $permissions;
                }else{
                    return $permissions;
                }
            }else{
                return $permissions;
            }
        }else{
            return $permissions;
        }
    }

    //check admin login
    public function is_admin_login(){
        $user_data = $this->session->userdata('login');
        if($user_data!=NULL){
            if($user_data['is_admin_login']==FALSE){
                //redirect to logout
		        redirect('admin/logout', 'refresh');
            }
        }else{
            //redirect to logout
		    redirect('admin/logout', 'refresh');
        }
    }

    //check site login
    public function is_site_login(){
        $user_data = $this->session->userdata('login');
        if($user_data!=NULL){
            if($user_data['is_site_login']==FALSE){
                //redirect to logout
		        redirect('logout', 'refresh');
            }
        }else{
            //redirect to logout
		    redirect('logout', 'refresh');
        }
    }

    //get user id
    public function get_user_id(){
        $user_data = $this->session->userdata('login');
        return $user_data['user_id'];
    }

    //get user type
    public function get_user_type(){
        $user_data = $this->session->userdata('login');
        return $user_data['user_role_slug'];
    }


    // this function will be called to send emails with
    public function sendEmail($to, $subject, $message)
    {
        $mail_driver=$this->load->get_var('mail_driver');
        if($mail_driver=='MAIL'){
            //if not using email settings, use default configuration
            $config = Array(
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'wordwrap' => TRUE
            );
        }elseif($mail_driver=='SMTP'){
            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => $this->load->get_var('mail_host'),
                'smtp_port' => intval($this->load->get_var('mail_port')),
                'smtp_user' => $this->load->get_var('mail_username'),
                'smtp_pass' => $this->load->get_var('mail_password'),
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'wordwrap' => TRUE
            );
        }
        
        $site_logo=$this->load->get_var('site_logo');
        if($site_logo!=NULL || $site_logo!=''){
            $logo=base_url().'uploads/site/'.$site_logo;
        }else{
            $logo=base_url() . 'assets/images/admin-logo.png';
        }

        $fromEmail=$this->load->get_var('mail_from_email');
        $fromName = $this->load->get_var('mail_from_title');
        //set application logo
        $data['logo'] = $logo;
        //set application header
        $data['application_title'] = $fromName;
        //get application footer
        $data['application_footer'] = $fromName;
        //set email content
        $data['email_content'] = $message;
        //call to an email template and set data to email
        $email_body = $this->load->view('email/template_mail', $data, true);
        //load email library
        $this->load->library('email', $config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");
        $this->email->from($fromEmail, $fromName);
        $this->email->to($to);
        $this->email->subject($subject.' - '.$fromName);
        $this->email->message($email_body);
        if($this->email->send())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //this function will be called to send MASS emails
     public function sendMassEmail($to, $subject, $message)
    {
        foreach($to as $recipient)
        {
            if($this->sendEmail($recipient, $subject, $message)){
                //echo 'Email Send';
            }else{
                //echo 'NOT Send';
            }
        }
    }

    // this function will be called to get configuration of pagination
    public function pagination_config($base_url,$total_rows,$per_page){
        // Pagination Configuration
        $config['base_url'] = $base_url;
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['full_tag_open'] = "<ul class='pagination ci-pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_link'] = '<i class="feather icon-chevrons-left"></i> '.$this->lang->line("text_first");
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = $this->lang->line("text_last").' <i class="feather icon-chevrons-right"></i>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="feather icon-chevron-left"></i> '.$this->lang->line("text_previous");
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = $this->lang->line("text_next").' <i class="feather icon-chevron-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        return $config;
    }

    // this function will be called to get client ip address
    public function get_user_ip()
	{
		// Get real visitor IP behind CloudFlare network
		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
				$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
				$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}

		return $ip;
    }
    
    // this function will be called to get user agent
    public function get_user_agent()
    {
        if ($this->agent->is_browser())
        {
                $agent = $this->agent->browser().' '.$this->agent->version();
        }
        elseif ($this->agent->is_robot())
        {
                $agent = $this->agent->robot();
        }
        elseif ($this->agent->is_mobile())
        {
                $agent = $this->agent->mobile();
        }
        else
        {
                $agent = 'Unidentified User Agent';
        }
        return $this->agent->platform().'-'.$agent;
    }
    
    //generate token
    public function get_token($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max)];
        }
        return $token;
    }

    //random crypto generation
    function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    //generate article content
    public function generate_article_content($article_content){
        $doc = new DOMDocument();
        @$doc->loadHTML($article_content);
        $tags = $doc->getElementsByTagName('img');
        if(!empty($tags)){
            foreach ($tags as $tag) {
                $image_source=$tag->getAttribute('src');
                $image_type = mb_substr($image_source, 0, 4);
                if($image_type=='data'){
                    define('UPLOAD_DIR', 'uploads/articles/');
                    $img = $image_source;
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace('data:image/jpeg;base64,', '', $img);
                    $img = str_replace('data:image/gif;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $image_data = base64_decode($img);
                    $file = UPLOAD_DIR . uniqid() .time(). '.png';
                    file_put_contents($file, $image_data);
                    $article_image = base_url().$file;
                    $article_content=str_replace($image_source,$article_image,$article_content);
                    
                }

            }
        }
        return $article_content;
    }

    //Generate email content
    public function generate_email($template_slug,$data_set=array()){
        $template = $this->settings_model->get_email_template_by_slug($template_slug);
        if($template){
            $template_content=$template['template_content'];
            foreach($data_set as $key => $val){
                $replace_value='{'.$key.'}';
                $template_content=str_replace($replace_value,$val,$template_content);
            }
            return $template_content;
        }else{
            return 'Invalid Email Content';
        }
    }

    //Get email subject
    public function get_email_subject($template_slug){
        $template = $this->settings_model->get_email_template_by_slug($template_slug);
        if($template){
            $template_name=$template['template_name'];
            return $template_name;
        }else{
            return 'Invalid Subject';
        }
    }


}