<?php if(isset($permissions_list) && $permissions_list!=NULL) {  ?>
<div class="table-responsive">
    
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col"><?= $this->lang->line("text_permission"); ?></th>
                <th scope="col"><?= $this->lang->line("text_support_agent"); ?></th>
                <th scope="col"><?= $this->lang->line("text_support_manager"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($permissions_list as $permission) { ?>
            <tr>
                <td>
                    <?= $permission['name']; ?><br>
                    <small class="text-muted"><?= $permission['info']; ?></small>
                </td>
                <td>
                    <?php if(isset($permission['agent']) && $permission['agent']!=NULL) { ?>
                        <div class="toggle lg">
                            <label>
                                <input type="checkbox" class="toggle-button" data-role-permission-id="<?= $permission['agent']['id'] ?>" <?php if($permission['agent']['is_permitted']==1) { ?>checked<?php } ?>><span class="button-indecator"></span>
                            </label>
                        </div>
                    <?php } ?>
                </td>
                <td>
                    <?php if(isset($permission['manager']) && $permission['manager']!=NULL) { ?>
                        <div class="toggle lg">
                            <label>
                                <input type="checkbox" class="toggle-button" data-role-permission-id="<?= $permission['manager']['id'] ?>" <?php if($permission['manager']['is_permitted']==1) { ?>checked<?php } ?>><span class="button-indecator"></span>
                            </label>
                        </div>
                    <?php } ?>
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