<div class="app-title">
    <div class="title-holder">
        <h1><i class="feather icon-layout"></i> <?= $this->lang->line("text_email_templates"); ?></h1>
        <p><?= $this->lang->line("text_email_templates_subtitle"); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>"><i class="feather icon-home"></i></a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/settings/permissions'); ?>"><?= $this->lang->line("text_settings"); ?></a></li>
        <li class="breadcrumb-item"><?= $this->lang->line("text_email_templates"); ?></li>
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
                <div id="templatesList"></div>
                <button type="submit" class="d-none"></button>
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
<div id="sidePanel" class="side-panel">
    <div class="side-panel-content-holder">
        <div class="side-panel-loader"  id="sidePanelLoader"><div class="loader-ripple"><div></div><div></div></div></div>
        <div class="side-panel-content"  id="sidePanelContent"></div>
    </div>
</div>