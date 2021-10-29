<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		//Load Libraries
        $this->load->library(array('pagination'));
        //pagination settings
		$this->perPage = 10;
		//load helper for language
        $this->load->helper('language');
		//load models
		$this->load->model(array('faq_model','article_model','ticket_model'));
		
	}

	//Home Page
	public function index()
	{
		$data['title']=$this->lang->line("text_home");
		$article_data=array();
		//get all categories
		$categories = $this->article_model->get_categories($params = array());
		if(isset($categories)&&$categories!=NULL){
			$i=0;
			foreach($categories as $category){
				$article_data[$i]['category_id']=$category['id'];
				$article_data[$i]['category_name']=$category['category_name'];
				$article_data[$i]['category_slug']=$category['slug'];
				$article_data[$i]['category_description']=$category['category_description'];
				$article_data[$i]['category_icon']=$category['category_icon'];
				$article_data[$i]['category_ordering']=$category['ordering'];
				$article_data[$i]['num_articles']=$category['num_articles'];

				$keyword=NULL;
				$category=$category['id'];
				$status='PUBLISHED';
				if(!empty($keyword)){
					$conditions['search']['keyword'] = $keyword;
				}
				if(!empty($category)){
					$conditions['search']['category'] = $category;
				}
				if(!empty($status)){
					$conditions['search']['status'] = $status;
				}
				//set start and limit
				$conditions['start'] = 0;
				$conditions['limit'] = 5;
				$conditions['keep_order'] = TRUE;
				$article_data[$i]['articles']=$this->article_model->get_articles($conditions);;
				$i++;
			}
		}
		$data['article_data']=$article_data;
		$data['sub_view'] = $this->load->view('site/pages/home', $data, TRUE);
        $this->load->view('site/_layout', $data); 
	}

	//Get Search Suggestions AJAX
	public function get_search_suggestions(){
        $conditions['search']['keyword'] = $this->input->post('keyword');;
        $conditions['search']['category'] = NULL;
        $conditions['search']['status'] = 'PUBLISHED';
        $conditions['keep_order'] = TRUE;
        //get all articles
		$articles = $this->article_model->get_articles($conditions);
		if($articles){
			//response
			$success = TRUE;
			$message = '';
		}else{
			//response
			$success = FALSE;
			$message = 'No Suggestions Found!';
		}
		
        $json_array = array('success' => $success, 'message' => $message,'results'=>$articles);
        echo json_encode($json_array);
        exit();
	}

	//Search Result Page
	public function search(){
		$data['title']=$this->lang->line("text_search_result");
		$keyword=$this->input->get('s', TRUE);
		$data['keyword']=$keyword;
		$data['sub_view'] = $this->load->view('site/pages/search-result', $data, TRUE);
        $this->load->view('site/_layout', $data); 
	}

	//Search Result AJAX
	public function search_result_ajax($page=0){
		// Row position
        if($page != 0){
            $page = ($page-1) * $this->perPage;
		}
		$conditions['search']['keyword'] = $this->input->post('keyword');;
        $conditions['search']['category'] = NULL;
        $conditions['search']['status'] = 'PUBLISHED';
        $conditions['keep_order'] = TRUE;
        //get articles count
		$articles=$this->article_model->get_articles($conditions);
        if($articles){
            $articlesCount=count($articles);
        }else{
            $articlesCount=0;
        }
        //set start and limit
        $conditions['start'] = $page;
        $conditions['limit'] = $this->perPage;
        //get all articles
        $articles = $this->article_model->get_articles($conditions);
        //get pagination confing
        $config=$this->pagination_config($base_url=base_url().'pages/search_result_ajax',$total_rows=$articlesCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $data['articles']=$articles;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('site/pages/search_result_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
	}

	//Knowledge Base Page
	public function articles(){
		$data['title']=$this->lang->line("text_knowledge_base");
		//get all categories
		$categories = $this->article_model->get_categories($params = array());
		$data['categories']=$categories;
		$data['sub_view'] = $this->load->view('site/pages/knowledge-base', $data, TRUE);
        $this->load->view('site/_layout', $data); 
	}

	//Article Categories Page
	public function categories($category_slug=NULL){
		if($category_slug!=NULL){
			//get category by slug
			$category=$this->article_model->get_category_by_slug($category_slug);
			if($category){
				$data['title']=$category['category_name'];
				$data['category_data']=$category;
				//get all categories
				$categories = $this->article_model->get_categories($params = array());
				$data['categories']=$categories;
				$data['sub_view'] = $this->load->view('site/pages/categories', $data, TRUE);
				$this->load->view('site/_layout', $data); 
			}else{
				//redirect to articles
				redirect('articles', 'refresh');
			}
			
		}else{
			//redirect to articles
			redirect('articles', 'refresh');
		}
		
	}

	//List Articles AJAX
	public function list_articles_ajax($page=0){
		// Row position
        if($page != 0){
            $page = ($page-1) * $this->perPage;
		}
		$keyword=NULL;
        $category=$this->input->post('category');
        $status='PUBLISHED';
        if(!empty($keyword)){
            $conditions['search']['keyword'] = $keyword;
        }
        if(!empty($category)){
            $conditions['search']['category'] = $category;
        }
        if(!empty($status)){
            $conditions['search']['status'] = $status;
        }
		//get articles count
		$articles=$this->article_model->get_articles($conditions);
        if($articles){
            $articlesCount=count($articles);
        }else{
            $articlesCount=0;
        }
        //set start and limit
        $conditions['start'] = $page;
        $conditions['limit'] = $this->perPage;
        $conditions['keep_order'] = TRUE;
        //get all articles
        $articles = $this->article_model->get_articles($conditions);
        //get pagination confing
        $config=$this->pagination_config($base_url=base_url().'pages/list_articles_ajax',$total_rows=$articlesCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $data['articles']=$articles;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('site/pages/list_articles_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
	}

	//View Article
	public function article($article_slug=NULL){
		if($article_slug!=NULL){
			//get article by slug
			$article=$this->article_model->get_article_by_slug($article_slug);
			if($article){
				$data['title']=$article['article_title'];
				$data['article_data']=$article;
				//get article vote counts
				$vote_counts=$this->article_model->get_article_vote_counts($article['id']);
				$data['vote_counts']=$vote_counts;
				//get all categories
				$categories = $this->article_model->get_categories($params = array());
				$data['categories']=$categories;
				//get recent articles
				$recent_articles = $this->article_model->get_recent_articles();
				$data['recent_articles']=$recent_articles;
				$data['sub_view'] = $this->load->view('site/pages/article', $data, TRUE);
				$this->load->view('site/_layout', $data); 
			}else{
				//redirect to articles
				redirect('articles', 'refresh');
			}
			
		}else{
			//redirect to articles
			redirect('articles', 'refresh');
		}
	}

	//FAQ Page
	public function faq(){
		$data['title']=$this->lang->line("text_faq");
		//get all faqs
		$faqs = $this->faq_model->list_faqs();
		$data['faqs']=$faqs;
		$data['sub_view'] = $this->load->view('site/pages/faq', $data, TRUE);
        $this->load->view('site/_layout', $data); 
	}

	//Submit Ticket - Guest
    public function submit_ticket(){
		//check if guest ticket is enabled
		$allow_guest_ticket_submission=$this->load->get_var('allow_guest_ticket_submission');
		if($allow_guest_ticket_submission!=1 || $allow_guest_ticket_submission!='1'){
			//redirect to home
			redirect('/', 'refresh');
		}

        if($this->input->post()){
            //form validation
			 $this->form_validation->set_rules('full_name','Full Name','trim|required' );
             $this->form_validation->set_rules('email','Email','trim|required');
             $this->form_validation->set_rules('ticket_title','Ticket Title','trim|required');
             $this->form_validation->set_rules('ticket_description','Ticket Description','trim|required');
             $this->form_validation->set_rules('category','Ticket Category','trim|required');
             $this->form_validation->set_rules('priority','Ticket Priority','trim|required');
            if ($this->form_validation->run() == FALSE) {
                $success = FALSE;
                $message = validation_errors();
            }else{
                $post_data = array(
                    'client_name' => $this->input->post('full_name'),
                    'client_email' => $this->input->post('email'),
                    'ticket_title' => $this->input->post('ticket_title'),
                    'ticket_description' => $this->input->post('ticket_description'),
                    'category_id' => $this->input->post('category'),
                    'priority' => $this->input->post('priority'),
                    'status' => 0,
                );

                //upload config
                $config['upload_path'] = 'uploads/tickets/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|doc|docx|pdf';
                $config['encrypt_name'] = TRUE;
                $config['overwrite'] = TRUE;
                $config['max_size'] = '2048'; //2 MB
                //Upload Ticket File
                if(isset($_FILES['attachment']['name'])){
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('attachment')) {
                        $success = FALSE;
                        $message = $this->upload->display_errors();
                        $json_array = array('success' => $success, 'message' => $message);
                        echo json_encode($json_array);
                        exit();
                    } else {
                        $upload_data=$this->upload->data();
                        $post_data['ticket_file']=$upload_data['file_name'];
                    }
                }

                //XXS Clean
                $post_data = $this->security->xss_clean($post_data);
                $result = $this->ticket_model->create_ticket($post_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
					try{
						//Send Mail if Settings enabled
						$email_notify_new_ticket=$this->load->get_var('email_notify_new_ticket');
						if($email_notify_new_ticket==1 || $email_notify_new_ticket=="1"){
							$to=$this->load->get_var('site_email');
							$site_name=$this->load->get_var('site_name');
							$data_set=array(
								'sitename'=>$site_name,
								'fullname'=>$this->input->post('full_name'),
								'ticket_title'=>$this->input->post('ticket_title'),
								'ticket_description'=>$this->input->post('ticket_description'),
							);
							$email_content=$this->generate_email('new_ticket',$data_set);
							$subject=$this->get_email_subject($slug='new_ticket');
							@$this->sendEmail($to, $subject, $email_content);
						}
						
					}catch(Exception $e){

					}

                    $success = TRUE;
                    $message = $this->lang->line("alert_ticket_created");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_ticket_not_created");
                }
            }

            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
        $data['title']=$this->lang->line("text_submit_ticket");
         //get tickets categories
        $categories = $this->ticket_model->get_categories($conditions=array());
        $data['categories']=$categories;
        $data['sub_view'] = $this->load->view('site/pages/submit_ticket', $data, TRUE);
        $this->load->view('site/_layout', $data);
	}
	
	//Contact
	public function contact(){
		if($this->input->post()){
            //form validation
			 $this->form_validation->set_rules('full_name','Full Name','trim|required' );
             $this->form_validation->set_rules('email','Email','trim|required');
             $this->form_validation->set_rules('subject','Subject','trim|required');
             $this->form_validation->set_rules('message','Message','trim|required');
            if ($this->form_validation->run() == FALSE) {
                $success = FALSE;
                $message = validation_errors();
            }else{
				$full_name=$this->input->post('full_name');
				$email=$this->input->post('email');
				$subject=$this->input->post('subject');
				$message=$this->input->post('message');
				try{
					$to=$this->load->get_var('site_email');
					$site_name=$this->load->get_var('site_name');
					$data_set=array(
						'sitename'=>$site_name,
						'fullname'=>$full_name,
						'subject'=>$subject,
						'email'=>$email,
						'message'=>$message,
					);
					$email_content=$this->generate_email('site_contact',$data_set);
					$subject=$this->get_email_subject($slug='site_contact');
					@$this->sendEmail($to, $subject, $email_content);
					$success = TRUE;
                    $message = $this->lang->line("alert_message_sent");
				}catch(Exception $e){
					$success = FALSE;
                    $message = $this->lang->line("alert_message_not_sent");
				}
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
		$data['title']=$this->lang->line("text_contact");
        $data['sub_view'] = $this->load->view('site/pages/contact', $data, TRUE);
        $this->load->view('site/_layout', $data);
	}

	//Switch Language
    public function switch_language($language = ""){
        $language = ($language != "") ? $language : "english";
        $site_languages=$this->config->item('site_language');
        $current_language=$site_languages[$language];
        $this->session->set_userdata('app_language', $current_language);
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
	}
	
	//get all language data
    public function get_all_language_keys(){
        $all_lang_array=$this->lang->language;
        $json_array = array('languages' => $all_lang_array);
        echo json_encode($json_array);
        exit();
    }

}
