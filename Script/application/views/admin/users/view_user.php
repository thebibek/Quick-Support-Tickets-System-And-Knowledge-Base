<div class="tile mb30">
    <div class="tile-title-w-btn">
        <div class="title">
        <h3><?= $title ?></h3>
        </div>
        <p><button class="btn btn-theme-grey btn-fab close-panel-button"><i class="lni-close"></i></button></p>
    </div>
    <div class="tile-content">
        <div class="users-list" id="actionCard<?= $user['id']; ?>">
            <div class="media">
                <div class="image-holder">
                    <?php if($user['profile_image']==NULL) { ?>
                        <img src="<?= base_url('assets/images/user.jpg'); ?>" alt="<?= $user['full_name']; ?>">
                    <?php }else{ ?>
                        <img src="<?= base_url().$user['profile_image']; ?>" alt="<?= $user['full_name']; ?>">
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
        </div>
        <ul class="list-group mt20">
            <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_email"); ?>
                <span>
                    <?= $user['email']; ?>
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_last_login"); ?>
                <span>
                    <?php $last_login = strtotime($user['last_login']); echo date("m/d/Y g:i A", $last_login); ?>
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_last_login_ip_address"); ?>
                <span>
                    <?php if($user['login_ip']!=NULL) {echo strip_tags($user['login_ip']);}else{echo "-";} ?>
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_last_login_agent"); ?>
                <span>
                    <?php if($user['login_agent']!=NULL) {echo strip_tags($user['login_agent']);}else{echo "-";} ?>
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_created_on"); ?>
                <span>
                    <?php $created_on = strtotime($user['created_on']); echo date("m/d/Y g:i A", $created_on); ?>
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center"> <?= $this->lang->line("text_updated_on"); ?>
                <span>
                    <?php $updated_on = strtotime($user['updated_on']); echo date("m/d/Y g:i A", $updated_on); ?>
                </span>
            </li>
        </ul>
        
    </div>
</div>

