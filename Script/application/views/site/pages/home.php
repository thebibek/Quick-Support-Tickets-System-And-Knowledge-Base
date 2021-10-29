<!-- Main Banner-->
<section class="home-banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    <h2 class="heading-light mb20"><?= $this->lang->line("text_main_title"); ?></h2>
                    <h5 class="heading-light heading-thin mb50"><?= $this->lang->line("text_main_sub_title"); ?></h5>
                </div>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-lg-8">
                <!-- Search Form-->
                <div class="search-holder mt20 mb20">
                    <form class="search-form" id="mainSearch" method="GET" action="<?= base_url() ?>search">
                        <div class="input-group input-group-lg">
                            <input class="form-control" type="text" id="InputSearch" placeholder="<?= $this->lang->line("text_search_here"); ?>" name="s">
                            <div class="input-group-append">
                                <button class="btn btn-light btn-flat" type="submit"><i
                                        class="lni-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="search-suggestions slideDownIn" id="searchSuggestions">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="archives-holder">
                                    <div class="archives-title mb10">
                                        <h3><?= $this->lang->line("text_suggested_articles"); ?></h3>
                                    </div>
                                    <div class="archives-items">
                                        <ul id="searchResults"></ul>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Articles -->
<section class="pt60 pb60">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col col-lg-8">
                <div class="main-title text-center mb20">
                    <h2 class="mb20"><?= $this->lang->line("text_explore_topics"); ?></h2>
                    <h5><?= $this->lang->line("text_explore_topics_info"); ?></h5>
                </div>
            </div>
        </div>
        <?php
            //Columns must be a factor of 12 (1,2,3,4,6,12)
            $numOfCols = 3;
            $rowCount = 0;
            $bootstrapColWidth = 12 / $numOfCols;
        ?>
        <?php if(!empty($article_data)) { ?>
        <div class="row">
            <?php foreach ($article_data as $data){ ?>
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="archives-holder mt20 mb20">
                    <div class="archives-title mb10">
                        <h3><?= $data['category_name']; ?></h3>
                    </div>
                    <div class="archives-items">
                        <?php if(isset($data['articles']) && $data['articles']!=NULL) { ?>
                            <ul>
                                <?php foreach($data['articles'] as $article) { ?>
                                <li><a href="<?= base_url().'article/'.$article['slug']; ?>"><?= $article['article_title']; ?> </a></li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </div>
                    <div class="archives-footer mt20"><a class="btn btn-outline-theme-secondary btn-block"
                            href="<?= base_url().'articles/category/'.$data['category_slug']; ?>"><?= $this->lang->line("text_explore_all"); ?> (<?= $data['num_articles']; ?>)</a></div>
                </div>
            </div>
            <?php
                    $rowCount++;
                    if($rowCount % $numOfCols == 0) echo '</div><div class="row">';
                }
            ?>
        </div>
        <?php } else { ?>
            <div class="text-center mt30 mb30">
                <div class="mb5"><img src="<?= base_url();?>assets/images/categories.svg" alt="Categories"></div>
                <p><?= $this->lang->line("text_categories_not_found"); ?></p>
            </div>
        <?php } ?>
    </div>
</section>
<!-- Call to Actions-->
<?php
  $this->load->view('site/pages/call-to-action-join');
?>