<section class="inner-banner">
    <div class="container">
        <h4><?= $this->lang->line("text_hello"); ?>, <?= $user['full_name'] ?></h4>
    </div>
</section>
<section class="inner-section">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-12">
                <div class="tile mb30">
                    <div class="tile-content">
                        <?php $this->load->view('site/user/sidebar'); ?>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-12">
                <div class="tile mb30">
                    <div class="tile-title">
                        <div class="title">
                            <h3><i class="feather icon-lock"></i> <?= $this->lang->line("text_change_password"); ?><h3>
                        </div>
                    </div>
                    <div class="tile-content">
                        <form id="updatePasswordForm" action="#" method="POST">
                            <div class="form-group">
                                <label for="inputOldPassword"><?= $this->lang->line("text_old_password"); ?></label>
                                <input class="form-control" id="inputOldPassword" type="password" name="old_password"
                                    placeholder="<?= $this->lang->line("text_enter_old_password"); ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputNewPassword"><?= $this->lang->line("text_new_password"); ?></label>
                                <input class="form-control" id="inputNewPassword" type="password" name="new_password"
                                    placeholder="<?= $this->lang->line("text_enter_new_password"); ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputConfirmNewPassword"><?= $this->lang->line("text_confirm_new_password"); ?></label>
                                <input class="form-control" id="inputConfirmNewPassword" type="password" name="confirm_new_password"
                                    placeholder="<?= $this->lang->line("text_confirm_new_password"); ?>">
                            </div>
                            <div class="form-group">
                                <button id="updatePasswordButton" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                                    type="submit"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_change_password"); ?></span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>