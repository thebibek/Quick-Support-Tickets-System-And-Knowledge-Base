<?php $method=$this->router->fetch_method(); ?>
<div class="profile-image-holder" id="profileImageHolder">
    <div class="profile-image">
        
            <?php if($user['profile_image']!=null) { ?>
            <img class="oval" id="profileAvatar" src="<?= base_url().$user['profile_image']; ?>" alt="avatar">
            <?php }else { ?>
            <img class="oval" id="profileAvatar" src="<?= base_url() ?>assets/images/user.jpg" alt="avatar">
            <?php } ?>
        <label class="label" data-toggle="tooltip" title="Change your avatar">
            <div class="image-edit-icon"><i class="lni-pencil-alt"> </i></div>    
            <input type="file" class="sr-only" id="inputProfileImage" name="image" accept="image/*">
        </label>
    </div>
</div>
<div class="progress profile-image-progress" style="display:none;">
    <div class="progress-bar profile-image-progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
</div>
<ul class="side-menu">

    <li <?php if($method=="index") { ?> class="active" <?php } ?>>
        <a href="<?= base_url('admin/profile'); ?>"><i class="feather icon-user"></i> <?= $this->lang->line("text_profile"); ?></a>
    </li>
    <li <?php if($method=="change_password") { ?> class="active" <?php } ?>>
        <a href="<?= base_url('admin/change-password'); ?>"><i class="feather icon-lock"></i> <?= $this->lang->line("text_change_password"); ?></a>
    </li>
    <li>
        <a href="<?= base_url('admin/logout'); ?>"><i class="feather icon-log-out"></i> <?= $this->lang->line("text_logout"); ?></a>
    </li>
</ul>
<div class="modal fade" id="modalProfileImage" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel"><?= $this->lang->line("text_crop_image"); ?></h5>
          </div>
          <div class="modal-body">
            <div class="img-container">
              <img id="profileImage" src="<?= base_url() ?>assets/images/user.jpg" alt="image">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-theme-secondary btn-oval" data-dismiss="modal"><?= $this->lang->line("text_cancel"); ?></button>&nbsp;
            <button type="button" class="btn btn-theme-primary btn-oval" id="crop"><?= $this->lang->line("text_crop"); ?></button>
          </div>
        </div>
      </div>
    </div>
