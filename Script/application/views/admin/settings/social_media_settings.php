<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-share-2"></i> <?= $this->lang->line("text_social_media_settings"); ?></h1>
        <p><?= $this->lang->line("text_social_media_settings_subtitle"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/settings/permissions'); ?>"><?= $this->lang->line("text_settings"); ?></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_social_media_settings"); ?></li>
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
                <form id="socialMediaSettingsForm" action="#" method="POST">
                    <div class="form-group">
                        <label for="inputFacebook"><?= $this->lang->line("text_facebook"); ?></label>
                        <input type="text" id="inputFacebook" class="form-control" name="facebook" placeholder="<?= $this->lang->line("text_facebook_link"); ?>" value="<?= $facebook; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputTwitter"><?= $this->lang->line("text_twitter"); ?></label>
                        <input type="text" id="inputTwitter" class="form-control" name="twitter" placeholder="<?= $this->lang->line("text_twitter_link"); ?>" value="<?= $twitter; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputInstagram"><?= $this->lang->line("text_instagram"); ?></label>
                        <input type="text" id="inputInstagram" class="form-control" name="instagram" placeholder="<?= $this->lang->line("text_instagram_link"); ?>" value="<?= $instagram; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputLinkedin"><?= $this->lang->line("text_linkedin"); ?></label>
                        <input type="text" id="inputLinkedin" class="form-control" name="linkedin" placeholder="<?= $this->lang->line("text_linkedin_link"); ?>" value="<?= $linkedin; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputGooglePlus"><?= $this->lang->line("text_google_plus"); ?></label>
                        <input type="text" id="inputGooglePlus" class="form-control" name="google-plus" placeholder="<?= $this->lang->line("text_google_plus_link"); ?>" value="<?= $google_plus; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputYoutube"><?= $this->lang->line("text_youtube"); ?></label>
                        <input type="text" id="inputYoutube" class="form-control" name="youtube" placeholder="<?= $this->lang->line("text_youtube_link"); ?>" value="<?= $youtube; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputGithub"><?= $this->lang->line("text_github"); ?></label>
                        <input type="text" id="inputGithub" class="form-control" name="github" placeholder="<?= $this->lang->line("text_github_link"); ?>" value="<?= $github; ?>">
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
