<?php $method=$this->router->fetch_method(); ?>
<ul class="side-menu">
    <?php if($permissions['site_settings']==TRUE) { ?>
        <li <?php if($method=="site_settings") { ?> class="active" <?php } ?>>
            <a href="<?= base_url('admin/settings/site'); ?>"><i class="feather icon-monitor"></i> <?= $this->lang->line("text_site_settings"); ?></a>
        </li>
    <?php } ?>
    <?php if($permissions['social_media_settings']==TRUE) { ?>
        <li <?php if($method=="social_media_settings") { ?> class="active" <?php } ?>>
            <a href="<?= base_url('admin/settings/social-media'); ?>"><i class="feather icon-share-2"></i> <?= $this->lang->line("text_social_media_settings"); ?></a>
        </li>
    <?php } ?>
    <?php if($permissions['seo_settings']==TRUE) { ?>
        <li <?php if($method=="seo_settings") { ?> class="active" <?php } ?>>
            <a href="<?= base_url('admin/settings/seo'); ?>"><i class="feather icon-search"></i> <?= $this->lang->line("text_seo_settings"); ?></a>
        </li>
    <?php } ?>
    <?php if($permissions['role_permissions']==TRUE) { ?>
        <li <?php if($method=="permissions") { ?> class="active" <?php } ?>>
            <a href="<?= base_url('admin/settings/permissions'); ?>"><i class="feather icon-shield"></i> <?= $this->lang->line("text_permissions"); ?></a>
        </li>
    <?php } ?>
    <?php if($permissions['app_settings']==TRUE) { ?>
        <li <?php if($method=="app_settings") { ?> class="active" <?php } ?>>
            <a href="<?= base_url('admin/settings/app'); ?>"><i class="feather icon-sliders"></i> <?= $this->lang->line("text_app_settings"); ?></a>
        </li>
    <?php } ?>
    <?php if($permissions['email_settings']==TRUE) { ?>
        <li <?php if($method=="email_settings") { ?> class="active" <?php } ?>>
            <a href="<?= base_url('admin/settings/email'); ?>"><i class="feather icon-mail"></i> <?= $this->lang->line("text_email_settings"); ?></a>
        </li>
    <?php } ?>
    <?php if($permissions['email_templates']==TRUE) { ?>
        <li <?php if($method=="email_templates") { ?> class="active" <?php } ?>>
            <a href="<?= base_url('admin/settings/email-templates'); ?>"><i class="feather icon-layout"></i> <?= $this->lang->line("text_email_templates"); ?></a>
        </li>
    <?php } ?>
</ul>