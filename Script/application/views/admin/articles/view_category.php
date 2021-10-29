<?php if($category) { ?>
<div class="tile mb30">
    <div class="tile-title-w-btn">
        <div class="title">
        <h3><?= $title; ?></h3>
        </div>
        <p><button class="btn btn-theme-grey btn-fab close-panel-button"><i class="feather icon-x"></i></button></p>
    </div>
    <div class="tile-content">
        <div class="category-list mb20">
            <div class="media">
                <div class="image-holder">
                    <?php if($category['category_icon']!=NULL) { ?>
                        <img src="<?= base_url('uploads/categories/'); ?><?= $category['category_icon'] ?>" alt="<?= $category['category_name'];?>">
                    <?php }else{ ?>
                        <img src="<?= base_url('assets/images/category.jpg'); ?>" alt="<?= $category['category_name'];?>">
                    <?php } ?>
                </div>
                <div class="media-body">
                    <h6><?= $category['category_name'];?></h6>
                    <p><i class="feather icon-file-text text-theme-secondary"></i> <?= $category['num_articles'];?> <?= $this->lang->line("text_articles"); ?></p>
                </div>
            </div>
        </div>
        <p>
        <?= $category['category_description']; ?>
        </p>
    </div>
</div>
<?php } ?>

