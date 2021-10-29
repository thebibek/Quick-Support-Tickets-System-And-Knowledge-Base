<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-sliders"></i> <?= $this->lang->line("text_app_settings"); ?></h1>
        <p><?= $this->lang->line("text_app_settings_subtitle"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/settings/permissions'); ?>"><?= $this->lang->line("text_settings"); ?></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_app_settings"); ?></li>
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
                <form id="appSettingsForm" action="#" method="POST">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_email_notify_ticket_create"); ?>
                            <span>
                                <div class="toggle lg">
                                    <label>
                                    <input type="checkbox" name="email_notify_new_ticket" <?php if($email_notify_new_ticket=='1') { ?>checked<?php } ?>><span class="button-indecator"></span>
                                    </label>
                                </div>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_email_notify_ticket_assign"); ?>
                            <span>
                                <div class="toggle lg">
                                    <label>
                                    <input type="checkbox" name="email_notify_assign_ticket" <?php if($email_notify_assign_ticket=='1') { ?>checked<?php } ?>><span class="button-indecator"></span>
                                    </label>
                                </div>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_password_notify_user_create"); ?>
                            <span>
                                <div class="toggle lg">
                                    <label>
                                    <input type="checkbox" name="send_password_created_new_user" <?php if($send_password_created_new_user=='1') { ?>checked<?php } ?>><span class="button-indecator"></span>
                                    </label>
                                </div>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_allow_guest_ticket_submission"); ?>
                            <span>
                                <div class="toggle lg">
                                    <label>
                                    <input type="checkbox" name="allow_guest_ticket_submission" <?php if($allow_guest_ticket_submission=='1') { ?>checked<?php } ?>><span class="button-indecator"></span>
                                    </label>
                                </div>
                            </span>
                        </li>
                    </ul>
                    <div class="row mt20">
                        <div class="col-lg-12 col-md-12 col-sm-12">
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