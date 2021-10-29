-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2020 at 11:02 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `support`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `article_title` varchar(500) CHARACTER SET latin1 NOT NULL,
  `slug` varchar(600) CHARACTER SET latin1 NOT NULL,
  `article_excerpt` varchar(1000) CHARACTER SET latin1 NOT NULL,
  `article_description` text CHARACTER SET latin1 NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1-Published, 0-Not Published',
  `ordering` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `article_categories`
--

CREATE TABLE `article_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(256) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `category_description` varchar(1000) NOT NULL,
  `category_icon` varchar(500) NOT NULL,
  `ordering` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `article_votes`
--

CREATE TABLE `article_votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1-Userfull, 0-Not Userfull',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `config_type` varchar(100) NOT NULL,
  `config_name` varchar(50) NOT NULL,
  `config_value` varchar(1000) NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `config_type`, `config_name`, `config_value`, `updated_on`) VALUES
(1, 'site', 'site_name', 'QuickSupport', '2020-01-17 12:07:59'),
(2, 'site', 'site_email', 'quicksupport@example.com', '2020-01-17 12:07:59'),
(3, 'site', 'site_phone', '+01987654321', '2020-01-17 12:07:59'),
(4, 'site', 'site_logo', '009b8da58f138d433936b95f4518264b.png', '2019-11-13 13:06:12'),
(5, 'site', 'site_favicon', 'c443956c76fe972aa5bb26cd3da36074.png', '2019-11-13 13:06:12'),
(6, 'social', 'facebook', 'https://www.facebook.com/', '2020-01-17 08:51:24'),
(7, 'social', 'twitter', 'https://www.facebook.com/', '2020-01-17 08:51:24'),
(8, 'social', 'instagram', 'https://www.instagram.com/', '2020-01-17 08:51:24'),
(9, 'social', 'linkedin', 'https://www.linkedin.com/', '2020-01-17 08:51:24'),
(10, 'social', 'google_plus', 'https://aboutme.google.com', '2020-01-17 08:51:24'),
(11, 'social', 'youtube', 'https://www.youtube.com/', '2020-01-17 08:51:24'),
(12, 'social', 'github', 'https://github.com/', '2020-01-17 08:51:24'),
(13, 'seo', 'meta_title', 'Home | QuickSupport', '2020-01-17 08:54:42'),
(14, 'seo', 'meta_description', 'Support Tickets Sytem, Exclusively Made for Support Tickets and Knowledge Bases', '2020-01-17 08:54:42'),
(15, 'seo', 'meta_keywords', 'Support System, Ticket System, Knowledge Base', '2020-01-17 08:54:42'),
(16, 'seo', 'google_analytics', '', '2020-01-17 08:54:42'),
(17, 'app', 'email_notify_new_ticket', '1', '2020-01-17 11:33:06'),
(18, 'app', 'email_notify_assign_ticket', '1', '2020-01-17 11:33:06'),
(19, 'app', 'send_password_created_new_user', '1', '2020-01-17 11:33:06'),
(20, 'app', 'allow_guest_ticket_submission', '1', '2020-01-17 11:33:06'),
(21, 'app', 'email_notify_reply_ticket', '1', '2020-02-18 00:00:00'),
(22, 'email', 'mail_from_email', 'noreply@example.com', '2020-04-11 07:50:21'),
(23, 'email', 'mail_host', 'mail.example.com', '2020-04-11 07:50:21'),
(24, 'email', 'mail_username', '_mailer@example.com', '2020-04-11 07:50:21'),
(25, 'email', 'mail_port', '465', '2020-04-11 07:50:21'),
(26, 'email', 'mail_password', '1234567890', '2020-04-11 07:50:21'),
(27, 'email', 'mail_encryption', 'SSL', '2020-04-11 07:50:21'),
(28, 'email', 'mail_driver', 'MAIL', '2020-04-11 07:50:21'),
(29, 'email', 'mail_from_title', 'QuickSupport', '2020-04-11 07:50:21');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `template_name` varchar(500) NOT NULL,
  `slug` varchar(600) NOT NULL,
  `template_variables` varchar(1000) NOT NULL,
  `template_content` text NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `template_name`, `slug`, `template_variables`, `template_content`, `updated_by`, `updated_on`) VALUES
(1, 'Forgot Password', 'forgot_password', '{fullname},{reset_link}', '<p>Hello {fullname}!</p><p>Follow the below link to Reset Your Password. </p><p>{reset_link}</p>', 1, '2020-04-11 07:41:25'),
(2, 'Reset Password', 'reset_password', '{fullname}', '<p>Hello {fullname}!</p><p>Your Password is successfully Reset.', 1, '2020-03-13 00:00:00'),
(3, 'Registration', 'registration', '{fullname},{activation_code},{activation_button}', '<p>Hello {fullname}!</p><p>You are successfully registered. Your activation code is. </p><h4>{activation_code}</h4>\r\n<p>{activation_button}</p>', 1, '2020-04-11 08:35:38'),
(4, 'New Ticket', 'new_ticket', '{sitename},{fullname},{ticket_title},{ticket_description}', '<p>Hello {sitename}!</p><p>A new ticket generated by {fullname}. </p><h4>{ticket_title}</h4><p>{ticket_description}</p>', 1, '2020-04-11 08:43:41'),
(5, 'Contact Form', 'site_contact', '{sitename},{fullname},{subject},{email},{message}', '<p>Hello {sitename}!</p><p>You have message from {fullname},</p><p>Subject : {subject},</p><p>Email : <a href=\"mailto:{email}\">{email}</a><br>Message: {message}<br></p>', 1, '2020-04-11 08:50:14'),
(6, 'Reply to Ticket', 'reply_ticket', '{fullname},{ticket_title},{reply_content}', '<p>Hello {fullname}!</p><p>You have reply for a Ticket. </p><h4>{ticket_title}</h4><p>{reply_content}</p>', 1, '2020-04-11 08:58:05'),
(7, 'Ticket Assigned', 'assign_ticket', '{fullname},{ticket_title},{ticket_description}', '<p>Hello {fullname}!</p><p>You have assigned with a Ticket. </p><h4>{ticket_title}</h4><p>{ticket_description}</p>', 1, '2020-04-08 12:38:53'),
(8, 'New User', 'user_creation', '{fullname},{sitename},{password}', '<p>Hello {fullname}!</p><p>You have registered in {sitename}</p><p>You can access the application to below password:</p><h3>{password}</h3>', 1, '2020-04-11 09:18:29');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `faq_title` varchar(500) NOT NULL,
  `slug` varchar(550) NOT NULL,
  `faq_description` text NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1-Active, 0-Inactive',
  `ordering` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE `faq_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(256) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `category_description` varchar(1000) NOT NULL,
  `ordering` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission_name` varchar(500) NOT NULL,
  `permission_slug` varchar(500) NOT NULL,
  `permission_info` varchar(1000) NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission_name`, `permission_slug`, `permission_info`, `updated_on`) VALUES
(1, 'List Articles', 'list_articles', 'List all Articles', '2019-04-12 00:00:00'),
(2, 'View Article', 'view_article', 'View Single Article', '2019-04-12 00:00:00'),
(3, 'Add Article', 'add_article', 'Create New Articles', '2019-04-12 00:00:00'),
(4, 'Edit Article', 'edit_article', 'Edit and Update Articles', '2019-04-12 00:00:00'),
(5, 'Publishing Articles', 'publishing_article', 'Publish or Unpublish Article from Site', '2019-04-12 00:00:00'),
(6, 'Delete Article', 'delete_article', 'Delete Article from Site', '2019-04-12 00:00:00'),
(7, 'Article Ordering', 'article_ordering', 'Update Article Orders', '2019-04-12 00:00:00'),
(8, 'List Article Categories', 'list_article_categories', 'List All Article Categories', '2019-05-13 00:00:00'),
(9, 'View Article Category', 'view_article_category', 'View Single Article Category', '2019-05-13 00:00:00'),
(10, 'Add Article Category', 'add_article_category', 'Add New Article Category', '2019-05-13 00:00:00'),
(11, 'Edit Article Category', 'edit_article_category', 'Edit and Update Article Category', '2019-05-13 00:00:00'),
(12, 'Delete Article Category', 'delete_article_category', 'Delete Article Category From Site', '2019-05-13 00:00:00'),
(13, 'Article Category Ordering', 'article_category_ordering', 'Update Article Category Orders', '2019-05-13 00:00:00'),
(14, 'List Tickets', 'list_tickets', 'List All Tickets', '2019-05-13 00:00:00'),
(15, 'View and Reply Ticket', 'view_reply_ticket', 'View and Reply to Ticket', '2019-05-13 00:00:00'),
(16, 'Assign Ticket', 'assign_ticket', 'Assing Ticket to User', '2019-05-13 00:00:00'),
(17, 'Delete Ticket', 'delete_ticket', 'Delete Ticket from Site', '2019-05-13 00:00:00'),
(18, 'Ticket Completion', 'ticket_completion', 'Mark Ticket as Completed or Not Completed', '2019-05-13 00:00:00'),
(19, 'List Ticket Categories', 'list_ticket_categories', 'List All Ticket Categories', '2019-05-13 00:00:00'),
(20, 'View Ticket Category', 'view_ticket_category', 'View Single Ticket Category', '2019-05-13 00:00:00'),
(21, 'Add Ticket Category', 'add_ticket_category', 'Add New Ticket Category', '2019-05-13 00:00:00'),
(22, 'Edit Ticket Category', 'edit_ticket_category', 'Edit and Update Ticket Category', '2019-05-13 00:00:00'),
(23, 'Delete Ticket Category', 'delete_ticket_category', 'Delete Ticket Category From Site', '2019-05-13 00:00:00'),
(24, 'Ticket Category Ordering', 'ticket_category_ordering', 'Update Ticket Category Orders', '2019-05-13 00:00:00'),
(25, 'List FAQs', 'list_faqs', 'List All FAQs', '2019-05-13 00:00:00'),
(26, 'View FAQ', 'view_faq', 'View Single FAQ', '2019-05-13 00:00:00'),
(27, 'Add FAQ', 'add_faq', 'Add New FAQ', '2019-05-13 00:00:00'),
(28, 'Edit FAQ', 'edit_faq', 'Edit and Update FAQ', '2019-05-13 00:00:00'),
(29, 'FAQ Publishing', 'faq_publishing', 'Publish or Unpublish FAQ', '2019-05-13 00:00:00'),
(30, 'Delete FAQ', 'delete_faq', 'Delete FAQ From Site', '2019-05-13 00:00:00'),
(31, 'FAQ Ordering', 'faq_ordering', 'Update Order of FAQ', '2019-05-13 00:00:00'),
(32, 'List FAQ Categories', 'list_faq_categories', 'List All FAQ Categories', '2019-05-13 00:00:00'),
(33, 'View FAQ Category', 'view_faq_category', 'View Single FAQ Category', '2019-05-13 00:00:00'),
(34, 'Add FAQ Category', 'add_faq_category', 'Add New FAQ Category', '2019-05-13 00:00:00'),
(35, 'Edit FAQ Category', 'edit_faq_category', 'Edit and Update FAQ Category', '2019-05-13 00:00:00'),
(36, 'Delete FAQ Category', 'delete_faq_category', 'Delete FAQ Category From Site', '2019-05-13 00:00:00'),
(37, 'FAQ Category Ordering', 'faq_category_ordering', 'Update FAQ Category Orders', '2019-05-13 00:00:00'),
(38, 'List Users', 'list_users', 'List All Users', '2019-05-13 00:00:00'),
(39, 'View User', 'view_user', 'View a Single User', '2019-05-13 00:00:00'),
(40, 'Add User', 'add_user', 'Add New User', '2019-05-13 00:00:00'),
(41, 'Edit User', 'edit_user', 'Edit and Update User', '2019-05-13 00:00:00'),
(42, 'Delete User', 'delete_user', 'Delete User From Site', '2019-05-13 00:00:00'),
(43, 'User Blocking', 'user_blocking', 'Blocking and Unblocking of User', '2019-05-13 00:00:00'),
(44, 'User Permissions', 'user_permissions', 'User-Based Permission', '2019-05-13 00:00:00'),
(45, 'Site Settings', 'site_settings', 'Update Site Information', '2019-05-13 00:00:00'),
(46, 'Social Media Settings', 'social_media_settings', 'Update Social Media Links', '2019-05-13 00:00:00'),
(47, 'SEO Settings', 'seo_settings', 'Update Search Enngine Optimization Settings', '2019-05-13 00:00:00'),
(48, 'Role Permissions', 'role_permissions', 'Role Based Permissions', '2019-05-13 00:00:00'),
(49, 'App Settings', 'app_settings', 'Basic App Settings', '2019-05-13 00:00:00'),
(50, 'Email Settings', 'email_settings', 'Manage Email Settings', '2020-03-13 00:00:00'),
(51, 'Email Templates', 'email_templates', 'Manage Email Templates', '2020-03-13 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `is_permitted` tinyint(4) NOT NULL COMMENT '0-Not Permitted, 1 - Permitted',
  `updated_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `is_permitted`, `updated_on`) VALUES
(1, 2, 1, 1, '2019-05-14 05:04:41'),
(2, 2, 2, 1, '2019-05-14 05:04:41'),
(3, 2, 3, 1, '2019-05-14 05:04:41'),
(4, 2, 4, 1, '2019-05-14 05:04:41'),
(5, 2, 5, 1, '2019-05-14 05:04:41'),
(6, 2, 6, 1, '2019-05-14 05:04:41'),
(7, 2, 7, 1, '2019-05-14 05:04:41'),
(8, 2, 8, 1, '2019-05-14 05:04:41'),
(9, 2, 9, 1, '2019-05-14 05:04:41'),
(10, 2, 10, 1, '2019-05-14 05:04:41'),
(11, 2, 11, 1, '2019-05-14 05:04:41'),
(12, 2, 12, 1, '2019-05-14 05:04:41'),
(13, 2, 13, 1, '2019-05-14 05:04:41'),
(14, 2, 14, 1, '2019-05-14 05:04:41'),
(15, 2, 15, 1, '2019-05-14 05:04:41'),
(16, 2, 16, 1, '2019-05-14 05:04:41'),
(17, 2, 17, 1, '2019-05-14 05:04:41'),
(18, 2, 18, 1, '2019-05-14 05:04:41'),
(19, 2, 19, 1, '2019-05-14 05:04:41'),
(20, 2, 20, 1, '2019-05-14 05:04:41'),
(21, 2, 21, 1, '2019-05-14 05:04:41'),
(22, 2, 22, 1, '2019-05-14 05:04:41'),
(23, 2, 23, 1, '2019-05-14 05:04:41'),
(24, 2, 24, 1, '2019-05-14 05:04:41'),
(25, 2, 25, 1, '2019-05-14 05:04:41'),
(26, 2, 26, 1, '2019-05-14 05:04:41'),
(27, 2, 27, 1, '2019-05-14 05:04:41'),
(28, 2, 28, 1, '2019-05-14 05:04:41'),
(29, 2, 29, 1, '2019-05-14 05:04:41'),
(30, 2, 30, 1, '2019-05-14 05:04:41'),
(31, 2, 31, 1, '2019-05-14 05:04:41'),
(32, 2, 32, 1, '2019-05-14 05:04:41'),
(33, 2, 33, 1, '2019-05-14 05:04:41'),
(34, 2, 34, 1, '2019-05-14 05:04:41'),
(35, 2, 35, 1, '2019-05-14 05:04:41'),
(36, 2, 36, 1, '2019-05-14 05:04:41'),
(37, 2, 37, 1, '2019-05-14 05:04:41'),
(38, 2, 38, 1, '2019-05-14 05:04:41'),
(39, 2, 39, 1, '2019-05-14 05:04:41'),
(40, 2, 40, 1, '2019-05-14 05:04:41'),
(41, 2, 41, 1, '2019-05-14 05:04:41'),
(42, 2, 42, 1, '2019-05-14 05:04:41'),
(43, 2, 43, 1, '2019-05-14 06:06:55'),
(44, 2, 44, 0, '2019-05-14 06:06:46'),
(45, 2, 45, 0, '2019-05-14 06:06:31'),
(46, 2, 46, 0, '2019-05-14 06:06:33'),
(47, 2, 47, 0, '2019-05-14 06:06:36'),
(48, 2, 48, 0, '2019-05-14 06:06:38'),
(49, 2, 49, 0, '2019-05-14 06:06:40'),
(50, 3, 1, 1, '2019-05-14 05:05:00'),
(51, 3, 2, 1, '2019-05-14 05:05:00'),
(52, 3, 3, 0, '2019-05-14 06:03:14'),
(53, 3, 4, 0, '2019-05-14 06:03:15'),
(54, 3, 5, 0, '2019-05-14 06:03:18'),
(55, 3, 6, 0, '2019-05-14 05:43:35'),
(56, 3, 7, 0, '2019-05-14 06:03:27'),
(57, 3, 8, 1, '2019-05-14 05:05:00'),
(58, 3, 9, 1, '2019-05-14 05:05:00'),
(59, 3, 10, 0, '2019-05-14 06:03:39'),
(60, 3, 11, 0, '2019-05-14 06:03:44'),
(61, 3, 12, 0, '2019-05-14 06:03:46'),
(62, 3, 13, 0, '2019-05-14 06:03:48'),
(63, 3, 14, 1, '2019-05-14 05:05:00'),
(64, 3, 15, 1, '2019-05-14 05:05:00'),
(65, 3, 16, 0, '2019-05-14 06:04:06'),
(66, 3, 17, 0, '2019-05-14 06:04:13'),
(67, 3, 18, 1, '2019-05-14 05:05:00'),
(68, 3, 19, 1, '2019-05-14 05:05:00'),
(69, 3, 20, 1, '2019-05-14 05:05:00'),
(70, 3, 21, 0, '2019-05-14 06:04:35'),
(71, 3, 22, 0, '2019-05-14 06:04:36'),
(72, 3, 23, 0, '2019-05-14 06:04:38'),
(73, 3, 24, 0, '2019-05-14 06:04:40'),
(74, 3, 25, 1, '2019-05-14 05:05:00'),
(75, 3, 26, 1, '2019-05-14 05:05:00'),
(76, 3, 27, 0, '2019-05-14 06:05:09'),
(77, 3, 28, 0, '2019-05-14 06:05:10'),
(78, 3, 29, 0, '2019-05-14 06:05:11'),
(79, 3, 30, 0, '2019-05-14 06:05:12'),
(80, 3, 31, 0, '2019-05-14 06:05:22'),
(81, 3, 32, 1, '2019-05-14 05:05:00'),
(82, 3, 33, 1, '2019-05-14 05:05:00'),
(83, 3, 34, 0, '2019-05-14 06:05:28'),
(84, 3, 35, 0, '2019-05-14 06:05:29'),
(85, 3, 36, 0, '2019-05-14 06:05:32'),
(86, 3, 37, 0, '2019-05-14 06:05:33'),
(87, 3, 38, 0, '2019-05-14 06:05:53'),
(88, 3, 39, 0, '2019-05-14 06:05:55'),
(89, 3, 40, 0, '2019-05-14 06:05:56'),
(90, 3, 41, 0, '2019-05-14 06:06:06'),
(91, 3, 42, 0, '2019-05-14 06:06:07'),
(92, 3, 43, 0, '2019-05-14 06:06:11'),
(93, 3, 44, 0, '2019-05-14 06:06:12'),
(94, 3, 45, 0, '2019-05-14 06:06:30'),
(95, 3, 46, 0, '2019-05-14 06:06:32'),
(96, 3, 47, 0, '2019-05-14 06:06:34'),
(97, 3, 48, 0, '2019-05-14 06:06:37'),
(98, 3, 49, 0, '2019-05-14 06:06:39'),
(99, 2, 50, 0, '2020-04-08 13:20:37'),
(100, 2, 51, 0, '2020-04-11 13:20:37'),
(101, 3, 50, 0, '2020-04-11 13:22:11'),
(102, 3, 51, 0, '2020-04-11 13:22:11');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `client_name` varchar(256) NOT NULL COMMENT 'Uses Only When Guest Ticket',
  `client_email` varchar(500) NOT NULL COMMENT 'Uses Only When Guest Ticket',
  `ticket_title` varchar(500) NOT NULL,
  `ticket_description` text NOT NULL,
  `ticket_file` varchar(500) NOT NULL,
  `assigned_to` int(11) NOT NULL DEFAULT 0,
  `priority` enum('L','M','H','U') NOT NULL COMMENT 'L-Low, M-Medium, H-High, U-Urgent',
  `status` tinyint(4) NOT NULL COMMENT '0-New,1-In Progress, 2-Closed',
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_categories`
--

CREATE TABLE `ticket_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(256) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `category_description` varchar(1000) NOT NULL,
  `ordering` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `reply_content` text NOT NULL,
  `reply_file` varchar(500) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_role_id` int(11) NOT NULL,
  `full_name` varchar(500) NOT NULL,
  `email` varchar(256) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `password` varchar(500) NOT NULL,
  `activation_code` varchar(50) NOT NULL,
  `reset_token` varchar(50) NOT NULL,
  `login_agent` varchar(500) NOT NULL,
  `last_login` datetime NOT NULL,
  `login_ip` varchar(20) NOT NULL,
  `profile_image` varchar(250) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0-Inactive,1-Active,2-Blocked',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_role_id`, `full_name`, `email`, `mobile`, `password`, `activation_code`, `reset_token`, `login_agent`, `last_login`, `login_ip`, `profile_image`, `status`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 1, 'Quick Support', 'admin@example.com', '+01987654321', '$2y$10$f3SepbOag1dDP/gHeyYujOMXTEt9os75xdK5QcqZaBPCzT4ouGRIO', '', '', 'Windows 7-Firefox 72.0', '2020-01-24 08:50:16', '::1', 'uploads/profile/5e21a35661a64.png', 1, 1, '2019-04-10 00:00:00', 1, '2020-01-17 12:06:46');

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE `users_roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `role_slug` varchar(20) CHARACTER SET latin1 NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_roles`
--

INSERT INTO `users_roles` (`id`, `role_name`, `role_slug`, `updated_on`) VALUES
(1, 'Administrator', 'admin', '2019-04-10 00:00:00'),
(2, 'Support Manager', 'manager', '2019-04-10 00:00:00'),
(3, 'Support Agent', 'agent', '2019-04-10 00:00:00'),
(4, 'Customer', 'customer', '2019-04-16 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `is_permitted` tinyint(4) NOT NULL COMMENT '0-Not Permitted, 1 - Permitted',
  `updated_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_categories`
--
ALTER TABLE `article_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_votes`
--
ALTER TABLE `article_votes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq_categories`
--
ALTER TABLE `faq_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_roles`
--
ALTER TABLE `users_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `article_categories`
--
ALTER TABLE `article_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `article_votes`
--
ALTER TABLE `article_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_categories`
--
ALTER TABLE `faq_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_roles`
--
ALTER TABLE `users_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
