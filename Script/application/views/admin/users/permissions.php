<?php if($permissions['user_permissions']==TRUE) { ?>
<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-shield"></i> <?= $this->lang->line("text_permissions"); ?></h1>
        <p><?= $this->lang->line("text_users_and_permissions"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/users'); ?>"><?= $this->lang->line("text_users"); ?></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_permissions"); ?></li>
    </ul>
</div>
<div class="row">
    <div class="col-xl-3 col-lg-12 col-md-12">
        <div class="tile mb30">
            <div class="tile-title">
                <h3><i class="feather icon-filter"></i> <?= $this->lang->line("text_filters"); ?></h3>
            </div>
            <div class="tile-content">
                <form id="filterPermissionForm" class="mb20" action="#" method="POST">
                    <div class="form-group">
                        <label for="inputUser"><?= $this->lang->line("text_user"); ?></label>
                        <select id="inputUser" class="form-control" name="user">
                            <option value="">--<?= $this->lang->line("text_select_user"); ?>--</option>
                            <?php if(isset($users) && $users!=NULL) { foreach($users as $user) { ?>
                            <option value="<?= $user['id'] ?>"><?= $user['full_name'] ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="inputSearch"><?= $this->lang->line("text_search"); ?></label>
                        <input type="text" id="inputSearch" class="form-control" name="keyword" placeholder="<?= $this->lang->line("text_enter_keyword"); ?>">
                    </div>
                    <div class="form-group">
                        <button id="filterButton" type="submit" class="btn btn-theme-secondary btn-lg btn-block btn-oval ladda-button" data-style="expand-right" data-size="xs"><span class="ladda-label"><i class="feather icon-filter"></i> <?= $this->lang->line("text_filter_permissions"); ?></span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-9 col-lg-12 col-md-12">
        <div class="tile mb30">
            <div class="tile-title">
                <h3><i class="feather icon-shield"></i> <?= $this->lang->line("text_permissions"); ?></h3>
            </div>
            <div class="tile-content">
                <div id="permissionsList">
                    <div class="text-center mt30 mb30">
                        <div class="mb5"><img src="<?= base_url();?>assets/images/permissions.svg" alt="Permissions"></div>
                        <p><?= $this->lang->line("text_user_permissions_info"); ?></p>
                    </div>
                </div>
            </div>
            <div class="tile-overlay" style="display: none;">
                <div class="m-loader mr-2">
                <svg class="m-circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/>
                </svg>
                </div>
                <h3 class="l-text"><?= $this->lang->line("text_loading"); ?></h3>
            </div>
        </div>
    </div>
</div>
<?php } ?>