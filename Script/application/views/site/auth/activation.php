<!-- Sub Banner-->
<section class="pt10 pb10 bb1 br0 bl0 bt1 b-solid bc-grey">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <ul class="breadcrumb-default mt15 mb15">
                    <li><a href="<?= base_url(); ?>"><?= $this->lang->line("text_home"); ?></a></li>
                    <li class="active"><?= $this->lang->line("text_activation"); ?></li>
                </ul>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="search-holder mt10 mb10">
                    <form class="sub-search-form" method="GET" action="<?= base_url() ?>search">
                        <div class="input-group">
                            <input class="form-control" name="s" type="text" placeholder="<?= $this->lang->line("text_search_here"); ?>">
                            <div class="input-group-append">
                                <button class="btn btn-light btn-flat" type="submit"><i class="lni-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Activation -->
<section class="pt60 pb60 mb100">
    <div class="container mb100">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-5 mx-auto">
                <!-- Card Starts-->
                <div class="card card-signin mt20 mb20">
                    <div class="card-body">
                        <h5 class="card-title text-center"><?= $this->lang->line("text_activation"); ?></h5>
                        <form id="activationForm" method="POST" class="form-signin">
                            <div class="form-group">
                                <label for="inputActivationCode"><?= $this->lang->line("text_activation_code"); ?></label>
                                <input type="text" id="inputActivationCode" class="form-control" name="activation_code" placeholder="<?= $this->lang->line("text_enter_activation_code"); ?>" autofocus="">
                                <small class="text-theme-grey"><?= $this->lang->line("text_activation_code_info"); ?></small>
                                <input type="hidden" id="userId" name="user_id" value="<?= $user_id; ?>">
                            </div>
                            <button id="activationButton" class="btn btn-lg btn-oval btn-theme-primary btn-block text-uppercase ladda-button" data-style="zoom-in" data-size="l"
                                type="submit"><span class="ladda-label"><?= $this->lang->line("text_activate"); ?></span></button>
                        </form>
                    </div>
                </div>
                <!-- Card Ends-->
            </div>
        </div>
    </div>
</section>