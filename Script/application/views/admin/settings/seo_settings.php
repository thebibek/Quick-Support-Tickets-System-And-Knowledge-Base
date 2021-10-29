<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-search"></i> <?= $this->lang->line("text_seo_settings"); ?></h1>
        <p><?= $this->lang->line("text_seo_settings_subtitle"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/settings/permissions'); ?>"><?= $this->lang->line("text_settings"); ?></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_seo_settings"); ?></li>
    </ul>
</div>
<div class="tile mb30">
    <div class="tile-title">
        <h3><i class="feather icon-settings"></i> <?= $this->lang->line("text_settings"); ?></h3>
    </div>
    <div class="tile-content">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-12">
                <?php $this->load->view('admin/settings/sidebar'); ?>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-12">
                <form id="seoSettingsForm" action="#" method="POST">
                    <div class="form-group">
                        <label for="inputMetaTitle"><?= $this->lang->line("text_meta_title"); ?></label>
                        <input type="text" id="inputMetaTitle" class="form-control" name="meta_title" placeholder="<?= $this->lang->line("text_enter_meta_title"); ?>" value="<?= $meta_title; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputMetaDescription"><?= $this->lang->line("text_meta_description"); ?></label>
                        <input type="text" id="inputMetaDescription" class="form-control" name="meta_description" placeholder="<?= $this->lang->line("text_enter_meta_description"); ?>" value="<?= $meta_description; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputMetaKeywords"><?= $this->lang->line("text_meta_keywords"); ?></label>
                        <input type="text" id="inputMetaKeywords" class="form-control" name="meta_keywords" placeholder="<?= $this->lang->line("text_enter_meta_keywords"); ?>" value="<?= $meta_keywords; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputGoogleAnalytics"><?= $this->lang->line("text_google_analytics"); ?></label>
                        <textarea  id="inputGoogleAnalytics" class="form-control" name="google_analytics" placeholder="<?= $this->lang->line("text_enter_google_analytics"); ?>"><?= $google_analytics; ?></textarea>
                    </div>
                    <div class="form-group">
                        <button id="saveButton" class="btn btn-lg btn-oval btn-theme-secondary ladda-button" data-style="expand-right" data-size="xs"
                                    type="submit"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_save_settings"); ?></span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>