<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-monitor"></i> <?= $this->lang->line("text_site_settings"); ?></h1>
        <p><?= $this->lang->line("text_site_settings_subtitle"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/settings/permissions'); ?>"><?= $this->lang->line("text_settings"); ?></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_site_settings"); ?></li>
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
                <form id="siteSettingsForm" enctype="multipart/form-data" accept-charset="utf-8" action="#" method="POST">
                    <div class="form-group">
                        <div class="site-logo-holder">
                            <?php if($site_logo==NULL) { ?>
                                <img id="siteLogoImg" src="<?= base_url(); ?>assets/images/admin-logo.png" alt="<?= $site_name; ?>">
                            <?php } else { ?>
                                <img id="siteLogoImg" src="<?= base_url(); ?>uploads/site/<?= $site_logo; ?>" alt="<?= $site_name; ?>">
                            <?php } ?>
                        </div>
                        <label for="inputSiteLogo"><?= $this->lang->line("text_site_logo"); ?></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="site_logo" id="inputSiteLogo">
                            <label class="custom-file-label" for="inputSiteLogo"><?= $this->lang->line("text_choose_file"); ?></label>
                        </div>
                        <small><?= $this->lang->line("text_site_logo_info"); ?></small>
                    </div>
                    <div class="form-group">
                        <div class="site-favicon-holder">
                            <?php if($site_favicon==NULL) { ?>
                                <img id="siteFaviconImg" src="<?= base_url(); ?>assets/images/favicon.png" alt="<?= $site_name; ?>">
                            <?php } else { ?>
                                <img id="siteFaviconImg" src="<?= base_url(); ?>uploads/site/<?= $site_favicon; ?>" alt="<?= $site_name; ?>">
                            <?php } ?>
                        </div>
                        <label for="inputSiteFavicon"><?= $this->lang->line("text_site_favicon"); ?></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="site_favicon" id="inputSiteFavicon">
                            <label class="custom-file-label" for="inputSiteFavicon"><?= $this->lang->line("text_choose_file"); ?></label>
                        </div>
                        <small><?= $this->lang->line("text_site_favicon_info"); ?></small>
                    </div>
                    <div class="form-group">
                        <label for="inputSiteTitle"><?= $this->lang->line("text_site_title"); ?></label>
                        <input type="text" id="inputSiteTitle" class="form-control" name="site_title" placeholder="<?= $this->lang->line("text_enter_site_title"); ?>" value="<?= $site_name; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputSiteEmail"><?= $this->lang->line("text_site_email"); ?></label>
                        <input type="email" id="inputSiteEmail" class="form-control" name="site_email" placeholder="<?= $this->lang->line("text_enter_site_email"); ?>" value="<?= $site_email; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputSitePhone"><?= $this->lang->line("text_site_phone"); ?></label>
                        <input type="text" id="inputSitePhone" class="form-control" name="site_phone" placeholder="<?= $this->lang->line("text_enter_site_phone"); ?>" value="<?= $site_phone; ?>">
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