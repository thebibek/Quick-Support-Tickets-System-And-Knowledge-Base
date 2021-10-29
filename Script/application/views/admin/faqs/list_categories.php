<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-briefcase"></i> <?= $this->lang->line("text_faq_categories"); ?></h1>
        <p><?= $this->lang->line("text_faq_categories_subtitle"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/faqs'); ?>"><?= $this->lang->line("text_faq"); ?></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_faq_categories"); ?></li>
    </ul>
</div>
<div class="row">
    <div class="col-12">
        <div class="tile mb30">
            <div class="tile-title-w-btn">
                <div class="title">
                    <h3><i class="feather icon-briefcase"></i> <?= $this->lang->line("text_faq_categories"); ?></h3>
                </div>
                <?php if($permissions['add_faq_category']==TRUE) { ?>
                    <p><button type="button" id="addCategoryButton" class="btn btn-theme-primary btn-oval trigger-button"><i class="lni-plus"></i> <?= $this->lang->line("text_add_category"); ?></button></p>
                <?php } ?>    
            </div>
            <div id="categoryTab" class="tile-tabs">
                <ul>
                    <?php if($permissions['list_faq_categories']==TRUE) { ?>
                        <li><a href="#categoriesList"><?= $this->lang->line("text_categories_list"); ?></a></li>
                    <?php } ?> 
                    <?php if($permissions['faq_category_ordering']==TRUE) { ?>
                        <li><a href="#categoriesOrdering" id="categoriesOrderingButton"><?= $this->lang->line("text_categories_ordering"); ?></a></li>
                    <?php } ?> 
                </ul>
                <?php if($permissions['list_faq_categories']==TRUE) { ?>
                    <div id="categoriesList"></div>
                <?php } ?>
                <?php if($permissions['faq_category_ordering']==TRUE) { ?>
                    <div id="categoriesOrdering"></div>
                <?php } ?>
            </div>
            <div class="tile-overlay" style="display: none;">
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

<div id="sidePanel" class="side-panel">
    <div class="side-panel-content-holder">
        <div class="side-panel-loader"  id="sidePanelLoader"><div class="loader-ripple"><div></div><div></div></div></div>
        <div class="side-panel-content"  id="sidePanelContent"></div>
    </div>
</div>