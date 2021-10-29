<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-users"></i> <?= $this->lang->line("text_users"); ?></h1>
        <p><?= $this->lang->line("text_users_subtitle"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_users"); ?></li>
    </ul>
</div>
<div class="row">
    <div class="col-xl-3 col-lg-12 col-md-12">
        <div class="tile mb30">
            <div class="tile-title">
                <h3><i class="feather icon-filter"></i> <?= $this->lang->line("text_filters"); ?></h3>
            </div>
            <div class="tile-content">
                <form id="filterUsersForm" action="#" method="POST">
                    <div class="form-group">
                        <label for="inputSearch"><?= $this->lang->line("text_keyword"); ?></label>
                        <input type="text" id="inputSearch" class="form-control" name="keyword" placeholder="<?= $this->lang->line("text_enter_part_username"); ?>">
                    </div>
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
                        <label for="inputSatus"><?= $this->lang->line("text_status"); ?></label>
                        <select id="inputSatus" class="form-control" name="status">
                            <option value="">--<?= $this->lang->line("text_select_status"); ?>--</option>
                            <option value="INACTIVE"><?= $this->lang->line("text_inactive"); ?></option>
                            <option value="ACTIVE"><?= $this->lang->line("text_active"); ?></option>
                            <option value="BLOCKED"><?= $this->lang->line("text_blocked"); ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button id="filterButton" type="submit" class="btn btn-theme-secondary btn-lg btn-block btn-oval ladda-button" data-style="expand-right" data-size="xs"><span class="ladda-label"><i class="feather icon-filter"></i> <?= $this->lang->line("text_filter_users"); ?></span></button>
                    </div>
                </form>  
            </div>
        </div>
    </div>
    <div class="col-xl-9 col-lg-12 col-md-12">
        <div class="tile mb30">
            <div class="tile-title-w-btn">
                <div class="title">
                    <h3><i class="feather icon-users"></i> <?= $this->lang->line("text_users"); ?></h3>
                </div>
                <?php if($permissions['add_user']==TRUE) { ?>
                    <p><button type="button" id="addUserButton" class="btn btn-theme-primary btn-oval trigger-button"><i class="lni-plus"></i> <?= $this->lang->line("text_add_user"); ?></button></p>
                <?php } ?>
            </div>
            <div class="tile-content">
                <?php if($permissions['list_users']==TRUE) { ?>
                <div id="usersList"></div>
                <?php } ?>
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

<div id="sidePanel" class="side-panel">
    <div class="side-panel-content-holder">
        <div class="side-panel-loader"  id="sidePanelLoader"><div class="loader-ripple"><div></div><div></div></div></div>
        <div class="side-panel-content"  id="sidePanelContent"></div>
    </div>
</div>