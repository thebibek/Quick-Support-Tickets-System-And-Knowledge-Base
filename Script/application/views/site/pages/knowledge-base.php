<!-- Sub Banner-->
<section class="pt10 pb10 bb1 br0 bl0 bt1 b-solid bc-grey">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <ul class="breadcrumb-default mt15 mb15">
                    <li><a href="<?= base_url(); ?>"><?= $this->lang->line("text_home"); ?></a></li>
                    <li class="active"><?= $this->lang->line("text_knowledge_base"); ?></li>
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
<!-- Archives -->
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
        <?php if(!empty($categories)) { ?>
        <div class="row">
            <?php foreach ($categories as $category){ ?>
            <div class="col-lg-<?= $bootstrapColWidth; ?> col-md-12">
                <div class="iconic-box-2 mt20 mb20 text-center">
                    <div class="box-iconic box-rounded iconic-image mb20">
                        <?php if($category['category_icon']==NULL) { ?>
                            <img src="<?= base_url(); ?>assets/images/category.jpg" alt="<?= $category['category_name']; ?>">
                        <?php } else { ?>
                            <img src="<?= base_url(); ?>uploads/categories/<?= $category['category_icon']; ?>" alt="<?= $category['category_name']; ?>">
                        <?php } ?>
                    </div>
                    <div class="box-content">
                        <h5 class="mb5"><?= $category['category_name']; ?></h5>
                        <p class="mb10"><?= $category['category_description']; ?></p><a
                            class="read" href="<?= base_url().'articles/category/'.$category['slug']; ?>"><?= $this->lang->line("text_view_all"); ?> (<?= $category['num_articles']; ?>) <i class="ti-angle-right"></i></a>
                    </div>
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