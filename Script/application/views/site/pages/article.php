<?php $login_data = $this->session->userdata('login'); ?>
<!-- Sub Banner-->
<section class="pt10 pb10 bb1 br0 bl0 bt1 b-solid bc-grey">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <ul class="breadcrumb-default mt15 mb15">
                    <li><a href="<?= base_url(); ?>"><?= $this->lang->line("text_home"); ?></a></li>
                    <li><a href="<?= base_url().'articles'; ?>"><?= $this->lang->line("text_articles"); ?></a></li>
                    <li><a href="<?= base_url().'articles/category/'.$article_data['category_slug']; ?>"><?= $article_data['category_name']; ?></a></li>
                    <li class="active"><?= $article_data['article_title']; ?></li>
                </ul>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="search-holder mt10 mb10">
                    <form class="sub-search-form" method="GET" action="<?= base_url() ?>search">
                        <div class="input-group">
                            <input class="form-control" name="s" type="text" placeholder="<?= $this->lang->line("text_search_here"); ?>">
                            <div class="input-group-append">
                                <button class="btn btn-light btn-flat" type="submit"><i class="lni-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Article-->
<section class="pt40 pb60">
    <div class="container">
        <div class="row">
            <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">
                <div class="mb30 mt20">
                    <h3 class="mb5"><?= $article_data['article_title']; ?></h3>
                    <h6 class="heading-thin text-theme-grey"><?= $article_data['article_excerpt']; ?></h6>
                </div>
                <div class="author-meta mt30 mb30">
                    <div class="media">
                        <?php if($article_data['profile_image']==NULL) { ?>
                            <img class="mr20 rounded-circle" src="<?= base_url(); ?>assets/images/user.jpg" alt="<?= $article_data['full_name']; ?>">
                        <?php }else{ ?>
                            <img class="mr20 rounded-circle" src="<?= base_url().$article_data['profile_image']; ?>" alt="<?= $article_data['full_name']; ?>">
                        <?php } ?>
                        <div class="media-body mt10">
                            <h5><?= $article_data['full_name']; ?></h5>
                            <p class="text-theme-grey"><?= $this->lang->line("text_created"); ?>: <?php $created_on = strtotime($article_data['created_on']); echo date("m/d/Y g:i A", $created_on); ?> - <?= $this->lang->line("text_updated"); ?>: <?php $updated_on = strtotime($article_data['updated_on']); echo date("m/d/Y g:i A", $updated_on); ?></p>
                        </div>
                    </div>
                </div>
                <div class="mt30 mb30">
                    <?= html_entity_decode($article_data['article_description']); ?>
                </div>
                <div class="mt20 mb20 pt40 pb40 text-center bb1 bt1 br1 bl1 b-solid bc-grey">
                    <h4 class="mb15 mt15"><?= $this->lang->line("text_was_article_helpfull"); ?></h4>
                    <button data-article-id="<?= $article_data['id']; ?>" data-vote="Y" class="btn btn-outline-theme-secondary btn-oval ml5 mr5 vote-button"><span class="feather icon-thumbs-up"></span> <?= $this->lang->line("text_yes"); ?></button>
                    <button data-article-id="<?= $article_data['id']; ?>" data-vote="N" class="btn btn-outline-theme-secondary btn-oval ml5 mr5 vote-button"><span class="feather icon-thumbs-down"> </span> <?= $this->lang->line("text_no"); ?></button>
                    <p class="mt15 mb15 text-theme-secondary"><strong id="helpCount"><?= $vote_counts['up_votes']; ?></strong> <?= $this->lang->line("text_out_of"); ?> <strong id="totalCount"><?= $vote_counts['total_votes']; ?></strong> <?= $this->lang->line("text_marked_helpfull"); ?></p>
                    <p class="mt15 mb15"><?= $this->lang->line("text_have_more_questions"); ?> <a href="<?= base_url(); ?>contact"><?= $this->lang->line("text_contact_us"); ?></a></p>
                </div>
                
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                <div class="sidebar">
                    <div class="sidebar-title">
                        <h3><?= $this->lang->line("text_recent_articles"); ?></h3>
                    </div>
                    <div class="sidebar-content">
                        <?php if($recent_articles){ ?>
                        <ul class="recent-articles">
                            <?php foreach($recent_articles as $article) { ?>
                            <li>
                                <a class="recent-article-title" href="<?php echo base_url().'article/'.$article['slug']; ?>"><h5><?= $article['article_title']; ?></h5></a>
                                <a class="recent-article-category" href="<?php echo base_url().'articles/category/'.$article['category_slug']; ?>"><i class="lni-briefcase"></i> <?= $article['category_name']; ?></a>    
                                <div class="recent-article-date"><i class="lni-calendar"></i> <?php $created_on = strtotime($article['created_on']); echo date("m/d/Y g:i A", $created_on); ?></div>
                            </li>
                            <?php } ?>
                        </ul>
                        <?php }else{ ?>
                            <p><?= $this->lang->line("text_no_articles_found"); ?></p>
                        <?php } ?>
                    </div>
                    <div class="sidebar-title">
                        <h3><?= $this->lang->line("text_categories"); ?></h3>
                    </div>
                    <div class="sidebar-content">
                        <?php if(!empty($categories)) { ?>
                        <ul class="list-links">
                            <?php foreach ($categories as $category){ ?>
                            <li>
                                <a href="<?php echo base_url().'articles/category/'.$category['slug']; ?>"><?= $category['category_name']; ?> <span class="list-counts"><?= $category['num_articles']; ?></span></a>
                            </li>
                            <?php } ?>
                        </ul>
                        <?php }else{ ?>
                            <p><?= $this->lang->line("text_no_categories_found"); ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>