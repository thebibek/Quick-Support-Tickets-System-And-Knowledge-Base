<!-- Sub Banner-->
<section class="pt10 pb10 bb1 br0 bl0 bt1 b-solid bc-grey">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <ul class="breadcrumb-default mt15 mb15">
                    <li><a href="<?= base_url(); ?>"><?= $this->lang->line("text_home"); ?></a></li>
                    <li class="active"><?= $this->lang->line("text_reset_password"); ?></li>
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
<!-- Reset Password -->
<section class="pt60 pb60 mb100">
    <div class="container mb100">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-5 mx-auto">
                <!-- Card Starts-->
                <div class="card card-signin mt20 mb20">
                    <div class="card-body">
                        <h5 class="card-title text-center"><?= $this->lang->line("text_forgot_password_link"); ?></h5>
                        <form id="resetPasswordForm" method="POST" class="form-signin">
                            <div class="form-group">
                                <input class="form-control" id="inputPassword" type="password"
                                    placeholder="<?= $this->lang->line("text_enter_new_password"); ?>" name="password" maxlength="20">
                                <input type="hidden" name="token" value="<?= $token; ?>">
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="inputConfirmPassword" type="password"
                                    placeholder="<?= $this->lang->line("text_confirm_new_password"); ?>" name="confirm_password" maxlength="20">
                            </div>
                            <button id="resetPasswordButton" class="btn btn-lg btn-oval btn-theme-primary btn-block text-uppercase ladda-button" data-style="zoom-in" data-size="l"
                                type="submit"><span class="ladda-label"><?= $this->lang->line("text_reset_password"); ?></span></button>
                        </form>
                    </div>
                </div>
                <!-- Card Ends-->
            </div>
        </div>
    </div>
</section>