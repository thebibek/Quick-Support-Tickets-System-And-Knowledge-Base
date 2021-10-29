<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-mail"></i> <?= $this->lang->line("text_email_settings"); ?></h1>
        <p><?= $this->lang->line("text_email_settings_subtitle"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/settings/permissions'); ?>"><?= $this->lang->line("text_settings"); ?></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_email_settings"); ?></li>
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
                <form id="emailSettingsForm" action="#" method="POST">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="inputMailFromTitle"><?= $this->lang->line("text_mail_from_title"); ?></label>
                                <input type="text" id="inputMailFromTitle" class="form-control" name="mail_from_title" placeholder="<?= $this->lang->line("text_enter_mail_from_title"); ?>" value="<?= $mail_from_title; ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputMailDriver"><?= $this->lang->line("text_mail_driver"); ?></label>
                                <select id="inputMailDriver" class="form-control" name="mail_driver">
                                    <option value="">--<?= $this->lang->line("text_select_one"); ?>--</option>
                                    <option value="MAIL" <?php if($mail_driver=='MAIL'){ echo "selected"; } ?>>Mail</option>
                                    <option value="SMTP" <?php if($mail_driver=='SMTP'){ echo "selected"; } ?>>SMTP</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputMailPort"><?= $this->lang->line("text_mail_port"); ?></label>
                                <input type="text" id="inputMailPort" class="form-control" name="mail_port" placeholder="<?= $this->lang->line("text_enter_mail_port"); ?>" value="<?= $mail_port; ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputMailPassword"><?= $this->lang->line("text_mail_password"); ?></label>
                                <input type="password" id="inputMailPassword" class="form-control" name="mail_password" placeholder="<?= $this->lang->line("text_enter_mail_password"); ?>" value="<?= $mail_password; ?>">
                            </div>
                            
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="inputMailFromEmail"><?= $this->lang->line("text_mail_from_mail"); ?></label>
                                <input type="email" id="inputMailFromEmail" class="form-control" name="mail_from_email" placeholder="<?= $this->lang->line("text_enter_mail_from_mail"); ?>" value="<?= $mail_from_email; ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputMailHost"><?= $this->lang->line("text_mail_host"); ?></label>
                                <input type="text" id="inputMailHost" class="form-control" name="mail_host" placeholder="<?= $this->lang->line("text_enter_mail_host"); ?>" value="<?= $mail_host; ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputMailUsername"><?= $this->lang->line("text_mail_username"); ?></label>
                                <input type="text" id="inputMailUsername" class="form-control" name="mail_username" placeholder="<?= $this->lang->line("text_enter_mail_username"); ?>" value="<?= $mail_username; ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputMailEncryption"><?= $this->lang->line("text_mail_encryption"); ?></label>
                                <select id="inputMailEncryption" class="form-control" name="mail_encryption">
                                    <option value="" <?php if($mail_encryption==''){ echo "selected"; } ?>>None</option>
                                    <option value="SSL" <?php if($mail_encryption=='SSL'){ echo "selected"; } ?>>SSL</option>
                                    <option value="TSL" <?php if($mail_encryption=='TSL'){ echo "selected"; } ?>>TSL</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <button id="saveButton" class="btn btn-lg btn-oval btn-theme-secondary ladda-button" data-style="expand-right" data-size="xs"
                                            type="submit"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_save_settings"); ?></span></button>
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>