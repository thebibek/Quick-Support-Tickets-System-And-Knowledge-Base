<?php 
$login_data = $this->session->userdata('login'); 
$app_language = $this->session->userdata('app_language');
$class_name=$this->router->fetch_class(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $title; ?> - <?= $site_name; ?></title>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <base href="<?= base_url(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="<?= $meta_keywords; ?>">
    <meta name="description" content="<?= $meta_description; ?>">
    <!-- Favicon-->
    <?php if($site_favicon!=NULL) { ?>
    <link rel="icon" type="image/x-icon" href="<?= base_url(); ?>uploads/site/<?= $site_favicon; ?>">
    <?php }else { ?>
    <link rel="icon" type="image/x-icon" href="<?= base_url(); ?>assets/images/favicon.png">
    <?php } ?>
    <!-- Google Font-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700&display=swap" rel="stylesheet"> 
    <!-- Bootstrap-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/bootstrap/css/bootstrap.min.css">
    <!-- LineIcons-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/fonts/LineIcons/LineIcons.css">
    <!-- Feather Font-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/fonts/feather-font/css/iconfont.css">
    <!-- Ladda Loader-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/ladda/ladda-themeless.min.css">
    <!-- Toast-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/toast/jquery.toast.min.css">
    <!-- Sweet Alert-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/sweetalert2/sweetalert2.min.css">
    <!-- Cropper Js-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/cropperjs/cropper.min.css">
    <!-- Summernote-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendors/summernote/summernote-bs4.css">
    <!-- Style -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/site.css">
    <!-- Google Analytics -->
    <script type="text/javascript">
        <?= $google_analytics; ?>
    </script>
</head>
<body>
    <!--Main Header-->
    <nav class="navbar navbar-main navbar-hover navbar-expand-lg navbar-light bg-theme-white">
        <div class="container">
            <?php if($site_logo!=NULL) { ?>
                <a class="navbar-brand" href="<?= base_url(); ?>"><img src="<?= base_url(); ?>uploads/site/<?= $site_logo; ?>" alt="<?= $site_name; ?>"></a>
            <?php }else { ?>
                <a class="navbar-brand" href="<?= base_url(); ?>"><img src="<?= base_url(); ?>assets/images/admin-logo.png" alt="<?= $site_name; ?>"></a>
            <?php } ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span
                    class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    
                    <li class="nav-item"><a class="nav-link" href="<?= base_url(); ?>articles"><?= $this->lang->line("text_knowledge_base"); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url(); ?>faq"><?= $this->lang->line("text_faq"); ?></a></li>
                    <?php if(isset($login_data) && $login_data['is_site_login']==TRUE){ ?>
                        <li class="nav-item"><a class="nav-link nav-button filled" href="<?= base_url(); ?>user/submit-ticket"><?= $this->lang->line("text_submit_ticket"); ?></a></li>
                    <?php }else{ ?>
                        <?php if($allow_guest_ticket_submission==1 || $allow_guest_ticket_submission=='1') { ?>
                            <li class="nav-item"><a class="nav-link nav-button filled" href="<?= base_url(); ?>submit-ticket"><?= $this->lang->line("text_submit_ticket"); ?></a></li>
                        <?php } ?>
                    <?php } ?>
                    <?php if(!isset($login_data)){ ?>
                        <li class="nav-item"><a class="nav-link nav-button bordered" href="<?= base_url(); ?>login"><?= $this->lang->line("text_login"); ?></a></li>
                    <?php } ?>
                    <?php if(isset($login_data) && $login_data['is_site_login']==FALSE){ ?>
                        <li class="nav-item"><a class="nav-link nav-button bordered" href="<?= base_url(); ?>login"><?= $this->lang->line("text_login"); ?></a></li>
                    <?php } ?>
                    <?php if(isset($login_data) && $login_data['is_site_login']==TRUE){ ?>
                    <!-- Dropdown -->
                    <li class="nav-item dropdown" id="userMenu">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardropuser" data-toggle="dropdown">
                        <?= $this->lang->line("text_hello"); ?>, <?= $login_data['full_name'] ?>
                        </a>
                        <div class="dropdown-menu slideDownIn">
                            <a class="dropdown-item" href="<?= base_url('user'); ?>"><i class="feather icon-home"></i> <?= $this->lang->line("text_dashboard"); ?></a>
                            <a class="dropdown-item" href="<?= base_url('user/tickets'); ?>"><i class="feather icon-life-buoy"></i> <?= $this->lang->line("text_tickets"); ?></a>
                            <a class="dropdown-item" href="<?= base_url('user/profile'); ?>"><i class="feather icon-user"></i> <?= $this->lang->line("text_profile"); ?></a>
                            <a class="dropdown-item" href="<?= base_url('user/change-password'); ?>"><i class="feather icon-lock"></i> <?= $this->lang->line("text_change_password"); ?></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('logout'); ?>"><i class="feather icon-log-out"></i> <?= $this->lang->line("text_logout"); ?></a>
                        </div>
                    </li>
                    <?php } ?>
                    <!-- Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardroplanguage" data-toggle="dropdown"><?php echo strtoupper($app_language['shortcode']); ?></a>
                        <div class="dropdown-menu slideDownIn">
                            <a class="dropdown-item" href="<?= base_url('switch/english'); ?>">EN - <?= $this->lang->line("text_english"); ?></a>
                            <a class="dropdown-item" href="<?= base_url('switch/french'); ?>">FR - <?= $this->lang->line("text_french"); ?></a>
                        </div>
                        
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- *****CONTENT***** -->
    <main class="main-content">
        <?= $sub_view; ?>
    </main>
    <!--Main Footer-->
    <footer class="main-footer pt20 pb20">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-12">
                    <div class="copyright-holder mt5 mb5">
                        <p>&copy; <?= date("Y"); ?> <?= $site_name; ?></p>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12">
                    <div class="social-holder mt5 mb5">
                        <ul>
                            <?php if($facebook!=NULL) { ?>
                                <li><a href="<?= $facebook ?>" target="_blank"><i class="lni-facebook"></i></a></li>
                            <?php } ?>
                            <?php if($twitter!=NULL) { ?>
                                <li><a href="<?= $twitter ?>" target="_blank"><i class="lni-twitter"></i></a></li>
                            <?php } ?>
                            <?php if($instagram!=NULL) { ?>
                                <li><a href="<?= $instagram ?>" target="_blank"><i class="lni-instagram"></i></a></li>
                            <?php } ?>
                            <?php if($linkedin!=NULL) { ?>
                                <li><a href="<?= $linkedin ?>" target="_blank"><i class="lni-linkedin"></i></a></li>
                            <?php } ?>
                            <?php if($google_plus!=NULL) { ?>
                                <li><a href="<?= $google_plus ?>" target="_blank"><i class="lni-google-plus"></i></a></li>
                            <?php } ?>
                            <?php if($youtube!=NULL) { ?>
                                <li><a href="<?= $youtube ?>" target="_blank"><i class="lni-youtube"></i></a></li>
                            <?php } ?>
                            <?php if($github!=NULL) { ?>
                                <li><a href="<?= $github ?>" target="_blank"><i class="lni-github"></i></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12">
                    <div class="link-holder mt5 mb5">
                        <ul>
                            <li><a href="<?= base_url(); ?>articles"><?= $this->lang->line("text_knowledge_base"); ?></a></li>
                            <li><a href="<?= base_url(); ?>faq"><?= $this->lang->line("text_faq"); ?></a></li>
                            <li><a href="<?= base_url(); ?>contact"><?= $this->lang->line("text_contact"); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- jQuery -->
    <script src="<?= base_url(); ?>assets/vendors/jquery/jquery.min.js"></script>
    <!--Popper-->
    <script src="<?= base_url(); ?>assets/vendors/popper.js/popper.min.js"></script>
    <!--Bootstrap-->
    <script src="<?= base_url(); ?>assets/vendors/bootstrap/js/bootstrap.min.js"></script>
    <!-- Jquery Validation-->
    <script src="<?= base_url(); ?>assets/vendors/jquery-validation/jquery.validate.min.js"></script>
    <!--Toast-->
    <script src="<?= base_url(); ?>assets/vendors/toast/jquery.toast.min.js"></script>
    <!--Sweet Alert-->
    <script src="<?= base_url(); ?>assets/vendors/sweetalert2/sweetalert2.all.min.js"></script>
    <!--Ladda Loader-->
    <script src="<?= base_url(); ?>assets/vendors/ladda/spin.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/ladda/ladda.min.js"></script>
    <!-- Cropper JS-->
    <script src="<?= base_url(); ?>assets/vendors/cropperjs/cropper.min.js"></script>
    <!--Summernote-->
    <script src="<?= base_url(); ?>assets/vendors/summernote/summernote-bs4.min.js"></script>
    <!-- Custom Js-->
    <script src="<?= base_url(); ?>assets/js/site/core.js"></script>
    <!-- Custom Js Based on Controller-->
    <script src="<?= base_url(); ?>assets/js/site/<?= $class_name ?>.js"></script>
</body>

</html>