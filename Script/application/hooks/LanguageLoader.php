<?php
class LanguageLoader
{
    
    function initialize() {
        
        $ci =& get_instance();
        $ci->load->helper('language');
        $language = $ci->session->userdata('app_language');
        if ($language) {
            $ci->lang->load('app',$language['name']);
        } else {
            $site_languages=$ci->config->item('site_language');
            $current_language=$site_languages['english'];
            $ci->session->set_userdata('app_language', $current_language);
            $ci->lang->load('app','english');
            

        }
    }
}