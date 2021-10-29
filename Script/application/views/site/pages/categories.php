<!-- Sub Banner-->
<section class="pt10 pb10 bb1 br0 bl0 bt1 b-solid bc-grey">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <ul class="breadcrumb-default mt15 mb15">
                    <li><a href="<?= base_url(); ?>"><?= $this->lang->line("text_home"); ?></a></li>
                    <li><a href="<?= base_url().'articles'; ?>"><?= $this->lang->line("text_articles"); ?></a></li>
                    <li class="active"><?= $category_data['category_name']; ?></li>
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
<!-- Archives-->
<section class="pt40 pb60">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-12">
                <?php if(!empty($categories)) { ?>
                <h5 class="mt20"><?= $this->lang->line("text_categories"); ?></h5>
                <div class="archives-categories mb20 mt20">
                    <ul class="nav nav nav-pills flex-column">
                        <?php foreach ($categories as $category){ ?>
                        <li class="nav-item"><a class="nav-link <?php if($category['slug']==$category_data['slug']){ echo 'active';} ?>" href="<?= base_url().'articles/category/'.$category['slug']; ?>"><?= $category['category_name']; ?><span
                                    class="article-number"><?= $category['num_articles']; ?></span></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php }else{ ?>
                    <p><?= $this->lang->line("text_no_categories_found"); ?></p>
                <?php } ?>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-12">
                <div class="mb30 mt20">
                    <h3 class="mb5"><?= $category_data['category_name']; ?></h3>
                    <h6 class="heading-thin text-theme-grey"><?= $category_data['category_description']; ?></h6>
                </div>
                <div class="item-list">
                    <div id="articlesList" data-category-id="<?= $category_data['id']; ?>"></div>
                    <div class="list-overlay" style="display:none;">
                        <div class="m-loader mr-2">
                            <svg class="m-circular" viewBox="25 25 50 50">
                            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/>
                            </svg>
                        </div>
                        <h3 class="l-text"><?= $this->lang->line("text_loading"); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Call to Actions-->
<?php
  $this->load->view('site/pages/call-to-action-join');
?>