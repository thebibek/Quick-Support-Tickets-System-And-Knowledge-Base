<?php if(isset($permissions_list) && $permissions_list!=NULL) {  ?>
<div class="table-responsive">
    
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col"><?= $this->lang->line("text_permission_name"); ?></th>
                <th scope="col" class="text-right"><?= $this->lang->line("text_action"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($permissions_list as $permission) { ?>
            <tr>
                <td>
                    <?= $permission['name']; ?><br>
                    <small class="text-muted"><?= $permission['info']; ?></small>
                </td>
                <td class="text-right">
                    <div class="toggle lg">
                        <label>
                            <input type="checkbox" class="toggle-button" data-permission-id="<?= $permission['id'] ?>" data-user-id="<?= $user_id; ?>" data-role-id="<?= $role_id; ?>" <?php if($permission['permission']==TRUE) { ?>checked<?php } ?>><span class="button-indecator"></span>
                        </label>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php } else { ?>
    <div class="text-center mt30 mb30">
        <div class="mb5"><img src="<?= base_url();?>assets/images/permissions.svg" alt="Permissions"></div>
        <p><?= $this->lang->line("text_no_permission_found"); ?></p>
    </div>
<?php } ?>
<div id="pagination" class="mt10"><?= $pagination; ?></div>