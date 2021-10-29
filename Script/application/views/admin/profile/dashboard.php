<?php 
$session_data=$this->session->userdata('login');
?>
<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-home"></i> <?= $this->lang->line("text_dashboard"); ?></h1>
        <p><?= $this->lang->line("text_dashboard_subtitle"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="lni-home"></i></li>
        <li class="breadcrumb-item"><a href="#"></i> <?= $this->lang->line("text_dashboard"); ?></a></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="widget-small secondary coloured-icon mb30"><i class="icon feather icon-file-text"></i>
            <div class="info">
                <h4></i><?= $this->lang->line("text_articles"); ?></h4>
                <p><b><?= $number_of_articles; ?></b></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-small secondary coloured-icon mb30"><i class="icon feather icon-life-buoy"></i>
            <div class="info">
                <h4><?= $this->lang->line("text_tickets"); ?></h4>
                <p><b><?= $number_of_tickets; ?></b></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-small secondary coloured-icon mb30"><i class="icon feather icon-help-circle"></i>
            <div class="info">
                <h4><?= $this->lang->line("text_faq"); ?></h4>
                <p><b><?= $number_of_faqs; ?></b></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-small secondary coloured-icon mb30"><i class="icon feather icon-users"></i>
            <div class="info">
                <h4><?= $this->lang->line("text_users"); ?></h4>
                <p><b><?= $number_of_users; ?></b></p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="tile mb30">
            <div class="tile-title">
                <h3><?= $this->lang->line("text_latest_articles"); ?></h3>
            </div>
            <div class="tile-content">
                <?php if (!empty($articles)): foreach ($articles as $article): ?>
                <div class="article-list no-action" id="actionCard<?= $article['id']; ?>">
                    <div class="media">
                        <div class="image-holder"><img src="<?= base_url('assets/images/article.jpg'); ?>" alt="article"></div>
                        <div class="media-body">
                            <h6><?= $article['article_title']; ?></h6>
                            <p><i class="feather icon-briefcase text-theme-secondary"></i> <?= $article['category_name']; ?></p>
                            <?php if($article['status']==0) { ?>
                                <span class="badge badge-pill badge-danger"><?= $this->lang->line("text_unpublished"); ?></span>
                            <?php }elseif($article['status']==1) { ?>
                                <span class="badge badge-pill badge-success"><?= $this->lang->line("text_published"); ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="article-votes no-action">
                        <ul>
                            <li><span class="badge badge-pill badge-success"><i class="lni-thumbs-up"></i> <?= $article['usefull']; ?></span><span class="label"> <?= $this->lang->line("text_likes"); ?></span></li>
                            <li><span class="badge badge-pill badge-danger"><i class="lni-thumbs-down"></i> <?= $article['unusefull']; ?></span><span class="label"> <?= $this->lang->line("text_dislikes"); ?></span></li>
                        </ul>
                    </div>
                    
                </div>
                <?php endforeach;
                    else:
                ?>
                <div class="text-center mt30 mb30">
                    <div class="mb5"><img src="<?= base_url();?>assets/images/articles.svg" alt="Articles"></div>
                    <p><?= $this->lang->line("text_not_found_articles"); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">   
        <div class="tile mb30">
            <div class="tile-title">
                <h3><?= $this->lang->line("text_latest_tickets"); ?></h3>
            </div>
            <div class="tile-content">
                <?php if (!empty($tickets)): foreach ($tickets as $ticket): ?>
                <div class="tickets-list no-action" id="actionCard<?= $ticket['id']; ?>">
                    
                    <div class="ticket-info">
                        <div class="media">
                            <div class="image-holder">
                                <?php if($ticket['profile_image']==NULL) { ?>
                                    <img src="<?= base_url('assets/images/ticket.jpg'); ?>" alt="<?= $ticket['full_name']; ?>">
                                <?php }else { ?>
                                    <img src="<?= base_url().$ticket['profile_image']; ?>" alt="<?= $ticket['full_name']; ?>">
                                <?php } ?>
                            </div>
                            <div class="media-body">
                                <h6><?= $ticket['ticket_title']; ?></h6>
                                <p><i class="feather icon-briefcase text-theme-secondary"></i> <?= $ticket['category_name']; ?></p>
                                <?php if($ticket['status']==0) { ?>
                                    <span class="badge badge-pill badge-success"><?= $this->lang->line("text_new"); ?></span>
                                <?php }elseif($ticket['status']==1) { ?>
                                    <span class="badge badge-pill badge-warning"><?= $this->lang->line("text_in_progress"); ?></span>
                                <?php }elseif($ticket['status']==2) { ?>
                                    <span class="badge badge-pill badge-danger"><?= $this->lang->line("text_closed"); ?></span>
                                <?php } ?>
                                <?php if($ticket['priority']=="L") { ?>
                                    <span class="badge badge-pill badge-secondary"><?= $this->lang->line("text_low"); ?></span>
                                <?php }elseif($ticket['priority']=="M") { ?>
                                    <span class="badge badge-pill badge-secondary"><?= $this->lang->line("text_medium"); ?></span>
                                <?php }elseif($ticket['priority']=="H") { ?>
                                    <span class="badge badge-pill badge-secondary"><?= $this->lang->line("text_high"); ?></span>
                                <?php }elseif($ticket['priority']=="U") { ?>
                                    <span class="badge badge-pill badge-secondary"><?= $this->lang->line("text_urgent"); ?></span>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                    <?php if($session_data['user_role_slug']!='agent'){ ?>
                    <?php if($ticket['assigned_to']!=0) { ?>
                    <div class="assigned-to">
                        <div class="media">
                            <div class="image-holder">
                                <?php if($ticket['assigned_user_image']==NULL) { ?>
                                    <img src="<?= base_url('assets/images/user.jpg'); ?>" alt="<?= $ticket['assigned_user']; ?>">
                                <?php }else { ?>
                                    <img src="<?= base_url().$ticket['assigned_user_image']; ?>" alt="<?= $ticket['assigned_user']; ?>">
                                <?php } ?>
                            </div>
                            <div class="media-body">
                                <p><?= $this->lang->line("text_assigned_to"); ?></p>
                                <?php if($ticket['assigned_user']!=NULL) { ?>
                                    <h6><?= $ticket['assigned_user']; ?></h6>
                                <?php }else{?>
                                    <h6><?= $this->lang->line("text_undefined"); ?></h6>
                                <?php } ?>
                            </div>  
                        </div>
                    </div>
                    <?php } ?>
                    <?php } ?>
                </div>
                <?php endforeach;
                    else:
                ?>
                <div class="text-center mt30 mb30">
                    <div class="mb5"><img src="<?= base_url();?>assets/images/tickets.svg" alt="Tickets"></div>
                    <p><?= $this->lang->line("text_no_tickets_found"); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>