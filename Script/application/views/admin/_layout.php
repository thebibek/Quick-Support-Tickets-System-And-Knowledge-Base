<?php
    $class_name=$this->router->fetch_class();
    $method_name=$this->router->fetch_method();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <base href="<?= base_url(); ?>">
    <title><?= $title; ?> - <?= $site_name; ?></title>
    <!-- Favicon-->
    <?php if($site_favicon!=NULL) { ?>
    <link rel="icon" type="image/x-icon" href="<?= base_url(); ?>uploads/site/<?= $site_favicon; ?>">
    <?php }else { ?>
        <link rel="icon" type="image/x-icon" href="<?= base_url(); ?>assets/images/favicon.png">
    <?php } ?>
    <!-- Google Font-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700&display=swap" rel="stylesheet"> 
    <!-- Jquery UI-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/jquery-ui/jquery-ui.theme.min.css">
    <!-- Bootstrap-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/bootstrap/css/bootstrap.min.css">
    <!-- LineIcons-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/fonts/LineIcons/LineIcons.css">
    <!-- Feather Font-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/fonts/feather-font/css/iconfont.css">
    <!-- Summernote-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/summernote/summernote-bs4.css">
    <!-- Toast-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/toast/jquery.toast.min.css">
    <!-- Responsive Tabs-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/responsive-tabs/css/responsive-tabs.css">
    <!-- Ladda Loader-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/ladda/ladda-themeless.min.css">
    <!-- Cropper Js-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/cropperjs/cropper.min.css">
    <!-- Style-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/dashboard.css">
</head>

<body class="app sidebar-mini">

    
    <!-- *****PAGE LOADER*****  -->
    <div class="page-loader" id="page-loader">
        <div class="ripple-loader"><div></div><div></div></div>
    </div>
    <!-- *****HEADER***** -->
    <header class="app-header">
        <a class="app-header-logo" href="<?= base_url(); ?>admin">
            <?php if($site_logo!=NULL) { ?>
                <img src="<?= base_url(); ?>uploads/site/<?= $site_logo ?>" alt="<?= $site_name ?>" >
            <?php }else { ?>
                <img src="<?= base_url(); ?>assets/images/admin-logo.png" alt="<?= $site_name ?>" >
            <?php } ?>
        </a>
        <!-- Sidebar toggle button--><a class="app-sidebar-toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
        <!-- Navbar Right Menu-->
        <ul class="app-nav">
            <!-- User Menu-->
            <li class="dropdown"><a class="app-nav-item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i
                        class="feather icon-user"></i></a>
                <ul class="dropdown-menu settings-menu dropdown-menu-right">
                    <li><a class="dropdown-item" href="<?= base_url('admin/profile'); ?>"><i class="lni-user text-theme-secondary"></i>
                    <?= $this->lang->line('text_profile'); ?></a></li>
                    <li><a class="dropdown-item" href="<?= base_url('admin/logout'); ?>"><i class="lni-exit text-theme-secondary"></i>
                    <?= $this->lang->line('text_logout'); ?></a></li>
                </ul>
            </li>
            <!-- Language Menu-->
            <li class="dropdown"><a class="app-nav-item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="feather icon-flag"></i></a>
                <ul class="dropdown-menu settings-menu dropdown-menu-right">
                    <li><a class="dropdown-item" href="<?= base_url('admin/switch/english'); ?>">
                            EN - <?= $this->lang->line('text_english'); ?></a></li>
                    <li><a class="dropdown-item" href="<?= base_url('admin/switch/french'); ?>">
                            FR - <?= $this->lang->line('text_french'); ?></a></li>
                </ul>
            </li>
        </ul>
    </header>
    <!-- *****SIDEBAR*****  -->
    <div class="app-sidebar-overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
        <div class="app-sidebar-user">
            <?php if($user_data['profile_image']!=NULL) { ?>
                <img class="app-sidebar-user-avatar" src="<?= base_url().$user_data['profile_image']; ?>" alt="<?= $user_data['full_name'] ?>">
            <?php } else { ?>
                <img class="app-sidebar-user-avatar" src="<?= base_url('assets/images'); ?>/user.jpg" alt="<?= $user_data['full_name'] ?>">
            <?php } ?>
            <div>
                <p class="app-sidebar-user-name"><?= $user_data['full_name'] ?></p>
                <p class="app-sidebar-user-designation"><?= $user_data['role_name'] ?></p>
            </div>
        </div>
        <ul class="app-menu">
            <li><a class="app-menu-item <?php if($class_name=="profile" && $method_name="dashboard"){ echo "active"; } ?>" href="<?= base_url('admin/'); ?>"><i class="app-menu-icon feather icon-home"></i><span
                        class="app-menu-label"><?= $this->lang->line('text_dashboard'); ?></span></a></li>
            <?php if($permissions['list_articles']==TRUE || $permissions['list_article_categories']==TRUE) { ?>
            <li class="treeview <?php if($class_name=="articles"){ echo "is-expanded"; } ?>"><a class="app-menu-item <?php if($class_name=="articles"){ echo "active"; } ?>" href="#" data-toggle="treeview"><i class="app-menu-icon feather icon-file-text"></i><span
                        class="app-menu-label"><?= $this->lang->line('text_knowledge_base'); ?></span><i class="treeview-indicator lni-chevron-right"></i></a>
                <ul class="treeview-menu">
                    <?php if($permissions['list_articles']==TRUE) { ?>
                        <li><a class="treeview-item <?php if($class_name=="articles" && $method_name=="list_articles"){ echo "active"; } ?>" href="<?= base_url('admin/articles/'); ?>"><i class="feather icon-chevron-right"></i> <?= $this->lang->line('text_articles'); ?></a></li>
                    <?php } ?>
                    <?php if($permissions['list_article_categories']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="articles" && $method_name=="categories"){ echo "active"; } ?>" href="<?= base_url('admin/article/categories'); ?>"><i class="feather icon-chevron-right"></i> <?= $this->lang->line('text_article_categories'); ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <?php if($permissions['list_tickets']==TRUE || $permissions['list_ticket_categories']==TRUE) { ?>
            <li class="treeview <?php if($class_name=="tickets"){ echo "is-expanded"; } ?>"><a class="app-menu-item <?php if($class_name=="tickets"){ echo "active"; } ?>" href="#" data-toggle="treeview"><i class="app-menu-icon feather icon-life-buoy"></i><span
                        class="app-menu-label"><?= $this->lang->line('text_tickets'); ?></span><i class="treeview-indicator lni-chevron-right"></i></a>
                <ul class="treeview-menu">
                    <?php if($permissions['list_tickets']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="tickets" && $method_name=="list_tickets"){ echo "active"; } ?>" href="<?= base_url('admin/tickets/'); ?>"><i class="feather icon-chevron-right"></i>
                    <?= $this->lang->line('text_list_tickets'); ?></a></li>
                    <?php } ?>
                    <?php if($permissions['list_ticket_categories']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="tickets" && $method_name=="categories"){ echo "active"; } ?>" href="<?= base_url('admin/ticket/categories'); ?>"><i class="feather icon-chevron-right"></i> <?= $this->lang->line('text_ticket_categories'); ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <?php if($permissions['list_faqs']==TRUE || $permissions['list_faq_categories']==TRUE) { ?>
            <li class="treeview <?php if($class_name=="faq"){ echo "is-expanded"; } ?>"><a class="app-menu-item <?php if($class_name=="faq"){ echo "active"; } ?>" href="#" data-toggle="treeview"><i class="app-menu-icon feather icon-help-circle"></i><span
                        class="app-menu-label"><?= $this->lang->line('text_faq'); ?></span><i class="treeview-indicator lni-chevron-right"></i></a>
                <ul class="treeview-menu">
                    <?php if($permissions['list_faqs']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="faq" && $method_name=="list_faqs"){ echo "active"; } ?>" href="<?= base_url('admin/faqs/'); ?>"><i class="feather icon-chevron-right"></i>
                    <?= $this->lang->line('text_list_faq'); ?></a></li>
                    <?php } ?>
                    <?php if($permissions['list_faq_categories']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="faq" && $method_name=="categories"){ echo "active"; } ?>" href="<?= base_url('admin/faq/categories'); ?>"><i class="feather icon-chevron-right"></i> <?= $this->lang->line('text_faq_categories'); ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <?php if($permissions['list_users']==TRUE || $permissions['user_permissions']==TRUE) { ?>
            <li class="treeview <?php if($class_name=="users"){ echo "is-expanded"; } ?>"><a class="app-menu-item <?php if($class_name=="users"){ echo "active"; } ?>" href="#" data-toggle="treeview"><i class="app-menu-icon feather icon-users"></i><span
                        class="app-menu-label"><?= $this->lang->line('text_users'); ?></span><i class="treeview-indicator lni-chevron-right"></i></a>
                <ul class="treeview-menu">
                    <?php if($permissions['list_users']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="users" && $method_name=="list_users"){ echo "active"; } ?>" href="<?= base_url('admin/users'); ?>"><i class="feather icon-chevron-right"></i>
                    <?= $this->lang->line('text_list_users'); ?></a></li>
                    <?php } ?>
                    <?php if($permissions['user_permissions']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="users" && $method_name=="permissions"){ echo "active"; } ?>" href="<?= base_url('admin/users/permissions'); ?>"><i class="feather icon-chevron-right"></i>
                    <?= $this->lang->line('text_user_permissions'); ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <?php if($permissions['site_settings']==TRUE || $permissions['social_media_settings']==TRUE || $permissions['seo_settings']==TRUE || $permissions['role_permissions']==TRUE || $permissions['app_settings']==TRUE) { ?>
            <li class="treeview <?php if($class_name=="settings"){ echo "is-expanded"; } ?>"><a class="app-menu-item <?php if($class_name=="settings"){ echo "active"; } ?>" href="#" data-toggle="treeview"><i class="app-menu-icon feather icon-settings"></i><span
                        class="app-menu-label"><?= $this->lang->line('text_settings'); ?></span><i class="treeview-indicator lni-chevron-right"></i></a>
                <ul class="treeview-menu">
                    <?php if($permissions['site_settings']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="settings" && $method_name=="site_settings"){ echo "active"; } ?>" href="<?= base_url('admin/settings/site'); ?>"><i class="feather icon-chevron-right"></i>
                    <?= $this->lang->line('text_site_settings'); ?></a></li>
                    <?php } ?>
                    <?php if($permissions['social_media_settings']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="settings" && $method_name=="social_media_settings"){ echo "active"; } ?>" href="<?= base_url('admin/settings/social-media'); ?>"><i class="feather icon-chevron-right"></i>
                    <?= $this->lang->line('text_social_media_settings'); ?></a></li>
                    <?php } ?>
                    <?php if($permissions['seo_settings']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="settings" && $method_name=="seo_settings"){ echo "active"; } ?>" href="<?= base_url('admin/settings/seo'); ?>"><i class="feather icon-chevron-right"></i>
                    <?= $this->lang->line('text_seo_settings'); ?></a></li>
                    <?php } ?>
                    <?php if($permissions['role_permissions']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="settings" && $method_name=="permissions"){ echo "active"; } ?>" href="<?= base_url('admin/settings/permissions'); ?>"><i class="feather icon-chevron-right"></i>
                    <?= $this->lang->line('text_permissions'); ?></a></li>
                    <?php } ?>
                    <?php if($permissions['app_settings']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="settings" && $method_name=="app_settings"){ echo "active"; } ?>" href="<?= base_url('admin/settings/app'); ?>"><i class="feather icon-chevron-right"></i>
                    <?= $this->lang->line('text_app_settings'); ?></a></li>
                    <?php } ?>
                    <?php if($permissions['email_settings']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="settings" && $method_name=="email_settings"){ echo "active"; } ?>" href="<?= base_url('admin/settings/email'); ?>"><i class="feather icon-chevron-right"></i>
                    <?= $this->lang->line('text_email_settings'); ?></a></li>
                    <?php } ?>
                    <?php if($permissions['email_templates']==TRUE) { ?>
                    <li><a class="treeview-item <?php if($class_name=="settings" && $method_name=="email_templates"){ echo "active"; } ?>" href="<?= base_url('admin/settings/email-templates'); ?>"><i class="feather icon-chevron-right"></i>
                    <?= $this->lang->line('text_email_templates'); ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </aside>
    <!-- *****CONTENT***** -->
    <main class="app-content">
        <?= $sub_view; ?>
    </main>
    <!-- jQuery -->
    <script src="<?= base_url(); ?>assets/vendors/jquery/jquery.min.js"></script>
    <!--JQuery UI-->
    <script src="<?= base_url(); ?>assets/vendors/jquery-ui/jquery-ui.min.js"></script>
    <!--Popper-->
    <script src="<?= base_url(); ?>assets/vendors/popper.js/popper.min.js"></script>
    <!--Bootstrap-->
    <script src="<?= base_url(); ?>assets/vendors/bootstrap/js/bootstrap.min.js"></script>
    <!--Side Reveal-->
    <script src="<?= base_url(); ?>assets/vendors/slidereveal/jquery.slidereveal.min.js"></script>
    <!--Summernote-->
    <script src="<?= base_url(); ?>assets/vendors/summernote/summernote-bs4.min.js"></script>
    <!--Toast-->
    <script src="<?= base_url(); ?>assets/vendors/toast/jquery.toast.min.js"></script>
    <!--Responsive Tabs-->
    <script src="<?= base_url(); ?>assets/vendors/responsive-tabs/js/jquery.responsiveTabs.min.js"></script>
    <!-- Jquery Validation-->
    <script src="<?= base_url(); ?>assets/vendors/jquery-validation/jquery.validate.min.js"></script>
    <!--Ladda Loader-->
    <script src="<?= base_url(); ?>assets/vendors/ladda/spin.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/ladda/ladda.min.js"></script>
    <!-- Cropper JS-->
    <script src="<?= base_url(); ?>assets/vendors/cropperjs/cropper.min.js"></script>
    <!-- Custom Js-->
    <script src="<?= base_url(); ?>assets/js/admin/core.js"></script>
    <!-- Custom Js Based on Controller-->
    <script src="<?= base_url(); ?>assets/js/admin/<?= $class_name ?>.js"></script>
</body>

</html>