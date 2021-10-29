<?php if (!empty($users)): foreach ($users as $user): ?>
<div class="users-list" id="actionCard<?= $user['id']; ?>">
    <div class="media">
        <div class="image-holder">
            <?php if($user['profile_image']==NULL) { ?>
                <img src="<?= base_url('assets/images/'); ?>user.jpg" alt="<?= $user['full_name']; ?>">
            <?php }else{ ?>
                <img src="<?= base_url(); ?><?= $user['profile_image']; ?>" alt="<?= $user['full_name']; ?>">
            <?php } ?>
        </div>
        <div class="media-body">
            <h6><?= $user['full_name']; ?></h6>
            <p><i class="feather icon-user text-theme-secondary"></i> <?= $user['role_name']; ?></p>
            <?php if($user['status']==0) { ?>
                <span class="badge badge-pill badge-danger"><?= $this->lang->line("text_inactive"); ?></span>
            <?php }elseif($user['status']==1) { ?>
                <span class="badge badge-pill badge-success"><?= $this->lang->line("text_active"); ?></span>
            <?php }elseif($user['status']==2) { ?>
                <span class="badge badge-pill badge-warning"><?= $this->lang->line("text_blocked"); ?></span>
            <?php } ?>
        </div>
    </div>
    <div class="action-dropdown">
        <div class="btn-group">
            <button type="button" class="btn btn-light btn-fab dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            <div class="dropdown-menu dropdown-menu-right">
                <?php if($permissions['view_user']==TRUE) { ?>
                    <button class="dropdown-item" type="button" id="viewButton" data-id="<?= $user['id']; ?>"><i class="feather icon-eye"></i> <?= $this->lang->line("text_view"); ?></button>
                <?php } ?>
                <?php if($permissions['edit_user']==TRUE) { ?>
                    <?php if($user['role_slug']!='admin') { ?>
                        <button class="dropdown-item" type="button" id="editButton" data-id="<?= $user['id']; ?>"><i class="feather icon-edit"></i> <?= $this->lang->line("text_edit"); ?></button>
                    <?php } ?>
                <?php } ?>
                <?php if($permissions['user_blocking']==TRUE) { ?>
                    <?php if($user['role_slug']!='admin') { ?>
                        <?php if($user['status']==1) { ?>
                            <button class="dropdown-item" type="button" id="blockButton" data-id="<?= $user['id']; ?>"><i class="feather icon-user-x"></i> <?= $this->lang->line("text_block"); ?></button>
                        <?php } ?>
                        <?php if($user['status']==2) { ?>
                            <button class="dropdown-item" type="button" id="unblockButton" data-id="<?= $user['id']; ?>"><i class="feather icon-user-check"></i> <?= $this->lang->line("text_unblock"); ?></button>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
                <?php if($permissions['delete_user']==TRUE) { ?>
                    <?php if($user['role_slug']!='admin') { ?>
                        <button class="dropdown-item" type="button" id="deleteButton" data-id="<?= $user['id']; ?>"><i class="feather icon-trash"></i> <?= $this->lang->line("text_delete"); ?></button>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach;
    else:
?>
<div class="text-center mt30 mb30">
    <div class="mb5"><img src="<?= base_url();?>assets/images/users.svg" alt="Users"></div>
    <p><?= $this->lang->line("text_no_users_found"); ?></p>
</div>
<?php endif; ?>
<div id="pagination" class="mt10"><?= $pagination; ?></div>
