<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-shield"></i> <?= $this->lang->line("text_permissions"); ?></h1>
        <p><?= $this->lang->line("text_permissions_subtitle"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/settings/permissions'); ?>"><?= $this->lang->line("text_settings"); ?></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_permissions"); ?></li>
    </ul>
</div>
<div class="tile mb30">
    <div class="tile-title">
        <h3><i class="feather icon-settings"></i> <?= $this->lang->line("text_settings"); ?></h3>
    </div>
    <div class="tile-content">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-12">
                <?php $this->load->view('admin/settings/sidebar'); ?>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-12">
                <form id="filterPermissionForm" class="mb20" action="#" method="POST">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <div class="form-group">
                                <input type="text" id="inputKeyword" class="form-control" name="keyword" placeholder="<?= $this->lang->line("text_enter_keyword"); ?>">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-8 col-sm-12">
                            <div class="form-group">
                                <button id="filterButton" type="submit" class="btn btn-theme-secondary btn-lg btn-block btn-oval ladda-button" data-style="expand-right" data-size="xs"><span class="ladda-label"><i class="feather icon-filter"></i> <?= $this->lang->line("text_filter_permissions"); ?></span></button>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="permissionsList"></div>
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