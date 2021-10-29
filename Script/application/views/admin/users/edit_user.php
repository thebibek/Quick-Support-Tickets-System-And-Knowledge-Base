<div class="tile mb30">
    <div class="tile-title-w-btn">
        <div class="title">
        <h3><?= $title ?></h3>
        </div>
        <p><button class="btn btn-theme-grey btn-fab close-panel-button"><i class="feather icon-x"></i></button></p>
    </div>
    <div class="tile-content">
        <form id="editUserForm" action="#" method="POST">
            <div class="form-group">
                <label for="inputUserType"><?= $this->lang->line("text_users_type"); ?></label>
                <select id="inputUserType" class="form-control" name="user_type">
                    <option value="">--<?= $this->lang->line("text_select_users_type"); ?>--</option>
                    <?php if(isset($user_types) && $user_types!=NULL) { foreach($user_types as $type) { ?>
                    <option value="<?= $type['id'] ?>" <?php if($type['id']==$user['user_role_id']) { echo "selected"; } ?>><?= $type['role_name'] ?></option>
                    <?php } } ?>
                </select>
                <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
            </div>
            <div class="form-group">
                <label for="inputFullname"><?= $this->lang->line("text_full_name"); ?></label>
                <input type="text" id="inputFullname" class="form-control" name="full_name" placeholder="<?= $this->lang->line("text_enter_full_name"); ?>" value="<?= $user['full_name']; ?>">
            </div>
            <div class="form-group">
                <button id="updateUserButton" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                    type="submit"><span class="ladda-label"><i class="feather icon-save"></i> <?= $this->lang->line("text_update_user"); ?></span></button>
            </div>
        </form>
    </div>
</div>

