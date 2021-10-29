<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-user"></i> <?= $this->lang->line("text_profile"); ?></h1>
        <p><?= $this->lang->line("text_profile_settings"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_profile"); ?></li>
    </ul>
</div>
<div class="row">
    <div class="col-xl-3 col-lg-4 col-md-12">
        <div class="tile mb30">
            <div class="tile-content">
                <?php $this->load->view('admin/profile/sidebar'); ?>
            </div>
        </div>
    </div>
    <div class="col-xl-9 col-lg-8 col-md-12">
        <div class="tile mb30">
            <div class="tile-title">
                <div class="title">
                    <h3><i class="feather icon-user"></i> <?= $this->lang->line("text_profile"); ?><h3>
                </div>
            </div>
            <div class="tile-content">
                <form id="updateProfileForm" action="#" method="POST">
                    <div class="form-group">
                        <label for="inputFullname"><?= $this->lang->line("text_full_name"); ?></label>
                        <input type="text" id="inputFullname" class="form-control" name="full_name" placeholder="<?= $this->lang->line("text_enter_full_name"); ?>" value="<?= $user['full_name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail"><?= $this->lang->line("text_email"); ?></label>
                        <input type="email" id="inputEmail" class="form-control" name="email" placeholder="<?= $this->lang->line("text_enter_email"); ?>" value="<?= $user['email'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputMobile"><?= $this->lang->line("text_mobile"); ?></label>
                        <input type="text" id="inputMobile" class="form-control" name="mobile" placeholder="<?= $this->lang->line("text_enter_mobile"); ?>" value="<?= $user['mobile'] ?>">
                    </div>
                    <div class="form-group">
                        <button id="updateProfileButton" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                            type="submit"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_update_profile"); ?></span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>