<section class="inner-banner">
    <div class="container">
        <h4><?= $this->lang->line("text_hello"); ?>, <?= $user['full_name'] ?></h4>
    </div>
</section>
<section class="inner-section">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-12">
                <div class="tile mb30">
                    <div class="tile-content">
                        <?php $this->load->view('site/user/sidebar'); ?>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-12">
                <div class="tile mb30">
                    <div class="tile-title-w-btn">
                        <div class="title">
                            <h3><i class="feather icon-home"></i> <?= $this->lang->line("text_dashboard"); ?><h3>
                        </div>
                        <p><a href="<?= base_url(); ?>user/submit-ticket" class="btn btn-theme-primary btn-oval"><i class="lni-plus"></i> <?= $this->lang->line("text_submit_ticket"); ?></a></p>
                    </div>
                    <div class="tile-content">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="dash-tile all-tickets">
                                    <h2><?= $this->lang->line("text_all_tickets"); ?></h2>
                                    <h4><?= $dashboard_data['total_tickets']; ?></h4>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="dash-tile new-tickets">
                                    <h2><?= $this->lang->line("text_new_tickets"); ?></h2>
                                    <h4><?= $dashboard_data['new_tickets']; ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="dash-tile proggress-tickets">
                                    <h2><?= $this->lang->line("text_inprogress_tickets"); ?></h2>
                                    <h4><?= $dashboard_data['inprogress_tickets']; ?></h4>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="dash-tile closed-tickets">
                                    <h2><?= $this->lang->line("text_closed_tickets"); ?></h2>
                                    <h4><?= $dashboard_data['closed_tickets']; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>