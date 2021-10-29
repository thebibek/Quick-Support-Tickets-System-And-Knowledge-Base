<div class="tile mb30">
    <div class="tile-title-w-btn">
        <div class="title">
        <h3><?= $title ?></h3>
        </div>
        <p><button class="btn btn-theme-grey btn-fab close-panel-button"><i class="feather icon-x"></i></button></p>
    </div>
    <div class="tile-content">
        <form id="addUserForm" action="#" method="POST">
            <div class="form-group">
                <label for="inputUserType"><?= $this->lang->line("text_users_type"); ?></label>
                <select id="inputUserType" class="form-control" name="user_type">
                    <option value="">--<?= $this->lang->line("text_select_users_type"); ?>--</option>
                    <?php if(isset($user_types) && $user_types!=NULL) { foreach($user_types as $type) { ?>
                    <option value="<?= $type['id'] ?>"><?= $type['role_name'] ?></option>
                    <?php } } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="inputFullname"><?= $this->lang->line("text_full_name"); ?></label>
                <input type="text" id="inputFullname" class="form-control" name="full_name" placeholder="<?= $this->lang->line("text_enter_full_name"); ?>">
            </div>
            <div class="form-group">
                <label for="inputEmail"><?= $this->lang->line("text_email"); ?></label>
                <input type="email" id="inputEmail" class="form-control" name="email" placeholder="<?= $this->lang->line("text_enter_email"); ?>">
            </div>
            
            <div class="form-group">
                <label for="inputPassword"><?= $this->lang->line("text_password"); ?></label>
                <input type="password" id="inputPassword" class="form-control" name="password" placeholder="<?= $this->lang->line("text_enter_password"); ?>">
            </div>
            <div class="form-group">
                <button id="createUserButton" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                    type="submit"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_create_user"); ?></span></button>
            </div>
        </form>
    </div>
</div>

