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
                <div class="image-holder"><img src="<?= base_url('assets/images/'); ?>category.jpg" alt="image"></div>
                <div class="media-body">
                    <h6><?= $category['category_name'];?></h6>
                    <p><i class="feather icon-help-circle text-theme-secondary"></i> <?= $category['num_faqs'];?> <?= $this->lang->line("text_faq"); ?></p>
                </div>
            </div>
        </div>
        <p>
        <?= $category['category_description']; ?>
        </p>
    </div>
</div>
<?php } ?>

