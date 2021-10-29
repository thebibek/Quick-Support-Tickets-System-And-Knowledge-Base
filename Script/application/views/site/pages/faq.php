<!-- Sub Banner-->
<section class="pt10 pb10 bb1 br0 bl0 bt1 b-solid bc-grey">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <ul class="breadcrumb-default mt15 mb15">
                    <li><a href="<?= base_url(); ?>"><?= $this->lang->line("text_home"); ?></a></li>
                    <li class="active"><?= $this->lang->line("text_faq"); ?></li>
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
<!-- FAQ-->
<section class="pt40 pb60">
    <div class="container">
        <div class="mt20 mb20">
            <h3 class="mb5"><?= $this->lang->line("text_faq"); ?></h3>
            <h6 class="heading-thin text-theme-grey"><?= $this->lang->line("text_faq_full"); ?></h6>
        </div>
        <?php if (!empty($faqs)){ ?>
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                <!-- FAQ Categories-->
                <div class="faq-categories mb20 mt20" id="faq-categories">
                    <ul class="nav nav nav-pills flex-column">
                        <?php $i=1; foreach ($faqs as $faq) { ?>
                        <li class="nav-item"><a class="nav-link <?php if($i==1){ echo 'active'; } ?>" href="#<?= $faq['category_slug']; ?>"><?= $faq['category_name']; ?></a></li>
                        <?php $i++; } ?>
                    </ul>
                </div>
            </div>
            <!-- FAQ Items-->
            <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12" id="faq-items">

                <?php $j=1;foreach ($faqs as $faq) { ?>
                <div class="mb20 mt20" id="<?= $faq['category_slug']; ?>">
                    <h4 class="mb20"><?= $faq['category_name']; ?></h4>
                    <div class="faq-style-one" id="accordion_<?= $faq['category_slug']; ?>">
                        <?php if(!empty($faq['faqs'])){ $k=1; foreach($faq['faqs'] as $faq_item) { ?>
                        <div class="faq-holder">
                            <div class="faq-header" id="heading<?= $faq_item['id']; ?>">
                                <h5 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse<?= $faq_item['id']; ?>"
                                        aria-expanded="true" aria-controls="collapse<?= $faq_item['id']; ?>"><?= $faq_item['faq_title']; ?></a></h5>
                            </div>
                            <div class="collapse <?php if($j==1&&$k==1){ echo 'show'; } ?>" id="collapse<?= $faq_item['id']; ?>" aria-labelledby="heading<?= $faq_item['id']; ?>"
                                data-parent="#accordion_<?= $faq_item['slug']; ?>">
                                <div class="faq-body"><?= $faq_item['faq_description']; ?></div>
                            </div>
                        </div>
                        <?php $k++; } } else { ?>
                            <div class="text-center mt30 mb30">
                                <div class="mb5"><img src="<?= base_url();?>assets/images/faqs.svg" alt="FAQs"></div>
                                <p><?= $this->lang->line("text_no_faq_found"); ?></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php $j++; } ?>
            </div>
        </div>
        <?php } else { ?>
            <div class="text-center mt30 mb30">
                <div class="mb5"><img src="<?= base_url();?>assets/images/faqs.svg" alt="FAQs"></div>
                <p><?= $this->lang->line("text_no_faq_found"); ?></p>
            </div>
        <?php } ?>
    </div>
</section>