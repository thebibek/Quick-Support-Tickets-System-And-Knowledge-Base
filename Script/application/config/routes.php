<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'pages';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

//Site Pages
$route['search'] = 'pages/search';
$route['articles'] = 'pages/articles';
$route['articles/category/(:any)'] = 'pages/categories/$1';
$route['article/(:any)'] = 'pages/article/$1';
$route['faq'] = 'pages/faq';
$route['submit-ticket'] = 'pages/submit_ticket';
$route['contact'] = 'pages/contact';
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['register'] = 'auth/register';
$route['activation/(:any)'] = 'auth/activation/$1';
$route['forgot-password'] = 'auth/forgot_password';
$route['reset-password/(:any)'] = 'auth/reset_password/$1';
$route['reset_password_action'] = 'auth/reset_password_action';
$route['switch/(:any)'] = 'pages/switch_language/$1';

//Admin Authentication
$route['admin/login'] = 'admin/auth/login';
$route['admin/logout'] = 'admin/auth/logout';
$route['admin/forgot-password'] = 'admin/auth/forgot_password';
$route['admin/reset-password/(:any)'] = 'admin/auth/reset_password/$1';
//Admin Profile
$route['admin'] = 'admin/profile/dashboard';
$route['admin/profile'] = 'admin/profile/index';
$route['admin/change-password'] = 'admin/profile/change_password';
$route['admin/switch/(:any)'] = 'admin/profile/switch_language/$1';
//Admin Articles
$route['admin/articles'] = 'admin/articles/list_articles';
$route['admin/article/view'] = 'admin/articles/view_article';
$route['admin/article/add'] = 'admin/articles/add_article';
$route['admin/article/create'] = 'admin/articles/create_article';
$route['admin/article/edit'] = 'admin/articles/edit_article';
$route['admin/article/update'] = 'admin/articles/update_article';
$route['admin/article/ordering'] = 'admin/articles/article_ordering';
$route['admin/article/update_ordering'] = 'admin/articles/update_article_ordering';
$route['admin/article/publish'] = 'admin/articles/publish_article';
$route['admin/article/unpublish'] = 'admin/articles/unpublish_article';
$route['admin/article/delete'] = 'admin/articles/delete_article';
$route['admin/article/categories'] = 'admin/articles/categories';
$route['admin/article/category/view'] = 'admin/articles/view_category';
$route['admin/article/category/add'] = 'admin/articles/add_category';
$route['admin/article/category/create'] = 'admin/articles/create_category';
$route['admin/article/category/edit'] = 'admin/articles/edit_category';
$route['admin/article/category/update'] = 'admin/articles/update_category';
$route['admin/article/category/ordering'] = 'admin/articles/categories_ordering';
$route['admin/article/category/update_ordering'] = 'admin/articles/update_categories_ordering';
$route['admin/article/category/delete'] = 'admin/articles/delete_category';
//Admin Tickets
$route['admin/tickets'] = 'admin/tickets/list_tickets';
$route['admin/ticket/view'] = 'admin/tickets/view_ticket';
$route['admin/ticket/assign'] = 'admin/tickets/assign_ticket';
$route['admin/ticket/reply'] = 'admin/tickets/reply_to_ticket';
$route['admin/ticket/delete'] = 'admin/tickets/delete_ticket';
$route['admin/ticket/completed'] = 'admin/tickets/mark_ticket_completed';
$route['admin/ticket/incompleted'] = 'admin/tickets/mark_ticket_incompleted';
$route['admin/ticket/categories'] = 'admin/tickets/categories';
$route['admin/ticket/category/add'] = 'admin/tickets/add_category';
$route['admin/ticket/category/create'] = 'admin/tickets/create_category';
$route['admin/ticket/category/view'] = 'admin/tickets/view_category';
$route['admin/ticket/category/edit'] = 'admin/tickets/edit_category';
$route['admin/ticket/category/update'] = 'admin/tickets/update_category';
$route['admin/ticket/category/ordering'] = 'admin/tickets/categories_ordering';
$route['admin/ticket/category/update_ordering'] = 'admin/tickets/update_categories_ordering';
$route['admin/ticket/category/delete'] = 'admin/tickets/delete_category';
//Admin FAQ
$route['admin/faqs'] = 'admin/faq/list_faqs';
$route['admin/faq/view'] = 'admin/faq/view_faq';
$route['admin/faq/add'] = 'admin/faq/add_faq';
$route['admin/faq/create'] = 'admin/faq/create_faq';
$route['admin/faq/edit'] = 'admin/faq/edit_faq';
$route['admin/faq/update'] = 'admin/faq/update_faq';
$route['admin/faq/ordering'] = 'admin/faq/faq_ordering';
$route['admin/faq/update_ordering'] = 'admin/faq/update_faq_ordering';
$route['admin/faq/publish'] = 'admin/faq/publish_faq';
$route['admin/faq/unpublish'] = 'admin/faq/unpublish_faq';
$route['admin/faq/delete'] = 'admin/faq/delete_faq';
$route['admin/faq/categories'] = 'admin/faq/categories';
$route['admin/faq/category/view'] = 'admin/faq/view_category';
$route['admin/faq/category/add'] = 'admin/faq/add_category';
$route['admin/faq/category/create'] = 'admin/faq/create_category';
$route['admin/faq/category/edit'] = 'admin/faq/edit_category';
$route['admin/faq/category/update'] = 'admin/faq/update_category';
$route['admin/faq/category/ordering'] = 'admin/faq/categories_ordering';
$route['admin/faq/category/update_ordering'] = 'admin/faq/update_categories_ordering';
$route['admin/faq/category/delete'] = 'admin/faq/delete_category';
//Admin Users
$route['admin/users'] = 'admin/users/list_users';
$route['admin/user/view'] = 'admin/users/view_user';
$route['admin/user/add'] = 'admin/users/add_user';
$route['admin/user/create'] = 'admin/users/create_user';
$route['admin/user/edit'] = 'admin/users/edit_user';
$route['admin/user/update'] = 'admin/users/update_user';
$route['admin/user/block'] = 'admin/users/block_user';
$route['admin/user/unblock'] = 'admin/users/unblock_user';
$route['admin/user/delete'] = 'admin/users/delete_user';
//Admin Settings
$route['admin/settings/site'] = 'admin/settings/site_settings';
$route['admin/settings/social-media'] = 'admin/settings/social_media_settings';
$route['admin/settings/seo'] = 'admin/settings/seo_settings';
$route['admin/settings/permissions'] = 'admin/settings/permissions';
$route['admin/settings/app'] = 'admin/settings/app_settings';
$route['admin/settings/email'] = 'admin/settings/email_settings';
$route['admin/settings/email-templates'] = 'admin/settings/email_templates';

