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
                            <h3><i class="feather icon-life-buoy"></i> <?= $this->lang->line("text_view_ticket"); ?><h3>
                        </div>
                        <p><a href="<?= base_url(); ?>user/tickets" class="btn btn-theme-primary btn-oval"><i class="lni-list"></i> <?= $this->lang->line("text_back_to_list"); ?></a></p>
                    </div>
                    <div class="tile-content">
                        <?php $session_data=$this->session->userdata('login'); ?>
                        <div class="tickets-list no-action">
                            <div class="ticket-info">
                                <div class="media">
                                    <div class="image-holder">
                                        <img src="<?= base_url(); ?>assets/images/ticket.jpg" alt="<?= $ticket['category_name']; ?>">
                                    </div>
                                    <div class="media-body">
                                        <h6><?= $ticket['ticket_title']; ?></h6>
                                        <p><i class="feather icon-briefcase text-theme-secondary"></i> <?= $ticket['category_name']; ?></p>
                                        <?php if($ticket['status']==0) { ?>
                                            <span class="badge badge-pill badge-success"><?= $this->lang->line("text_new"); ?></span>
                                        <?php }elseif($ticket['status']==1) { ?>
                                            <span class="badge badge-pill badge-warning"><?= $this->lang->line("text_in_progress"); ?></span>
                                        <?php }elseif($ticket['status']==2) { ?>
                                            <span class="badge badge-pill badge-danger"><?= $this->lang->line("text_closed"); ?></span>
                                        <?php } ?>
                                        <?php if($ticket['priority']=="L") { ?>
                                            <span class="badge badge-pill badge-secondary"><?= $this->lang->line("text_low"); ?></span>
                                        <?php }elseif($ticket['priority']=="M") { ?>
                                            <span class="badge badge-pill badge-secondary"><?= $this->lang->line("text_medium"); ?></span>
                                        <?php }elseif($ticket['priority']=="H") { ?>
                                            <span class="badge badge-pill badge-secondary"><?= $this->lang->line("text_high"); ?></span>
                                        <?php }elseif($ticket['priority']=="U") { ?>
                                            <span class="badge badge-pill badge-secondary"><?= $this->lang->line("text_urgent"); ?></span>
                                        <?php } ?>
                                        <span class="badge badge-pill badge-light"><?php $created_on = strtotime($ticket['created_on']); echo date("m/d/Y g:i A", $created_on); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt20 mb20">
                            <p><?= $ticket['ticket_description']; ?></p>
                            <?php if($ticket['ticket_file']!=NULL) { ?>
                                <a href="<?= base_url('uploads/tickets/'); ?><?= $ticket['ticket_file']; ?>" class="btn btn-outline-theme-primary btn-oval" target="_blank"><i class="feather icon-download"></i> <?= $this->lang->line("text_download_attachment"); ?></a>
                            <?php } ?>
                        </div>
                        
                    </div>
                    <div class="tile-title">
                        <div class="title">
                            <h3><?= $this->lang->line("text_replies"); ?></h3>
                        </div>
                    </div>
                    <div class="tile-content">
                        <?php if (!empty($replies)): foreach ($replies as $reply): ?>
                        <div class="tickets-list">
                            <div class="ticket-info">
                                <div class="media">
                                    <div class="image-holder">
                                        <?php if($reply['profile_image']==NULL) { ?>
                                            <img src="<?= base_url('assets/images/user.jpg'); ?>" alt="<?= $reply['full_name']; ?>">
                                        <?php }else { ?>
                                            <img src="<?= base_url().$reply['profile_image']; ?>" alt="<?= $reply['full_name']; ?>">
                                        <?php } ?>
                                    </div>
                                    <div class="media-body">
                                        <h6><?php if($reply['full_name']!=NULL) { echo strip_tags($reply['full_name']); }else{ echo 'Undefined';} ?></h6>
                                        <p><i class="feather icon-user text-theme-secondary"></i> <?php if($reply['role_name']!=NULL) { echo strip_tags($reply['role_name']); }else{ echo 'Undefined';} ?></p>
                                        <span class="badge badge-pill badge-light"><?php $created_on = strtotime($reply['created_on']); echo date("m/d/Y g:i A", $created_on); ?></span>
                                    </div>
                                </div>
                                <div class="mt10 mb10">
                                    <p><?= $reply['reply_content']; ?></p>
                                    <?php if($reply['reply_file']!=NULL) { ?>
                                        <a href="<?= base_url('uploads/tickets/'); ?><?= $reply['reply_file']; ?>" class="btn btn-outline-theme-primary btn-oval" target="_blank"><i class="feather icon-download"></i> <?= $this->lang->line("text_download_attachment"); ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;
                            else:
                        ?>
                        <p><?= $this->lang->line("text_no_replies_found"); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="tile-title">
                        <div class="title">
                            <h3><?= $this->lang->line("text_reply_to_ticket"); ?></h3>
                        </div>
                    </div>
                    <div class="tile-content">
                        <form id="replyTicketForm" action="#" method="POST">
                            <div class="form-group">
                                <label for="inputReply"><?= $this->lang->line("text_reply"); ?></label>
                                <textarea id="inputReply" class="summertext" name="reply_content" placeholder="Enter Reply"></textarea>
                                <input type="hidden" name="ticket_id" value="<?= $ticket['id']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputAttachment"><?= $this->lang->line("text_attachment"); ?></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="attachment" id="inputAttachment">
                                    <label class="custom-file-label" for="inputAttachment"><?= $this->lang->line("text_choose_file"); ?></label>
                                </div>
                                <small><?= $this->lang->line("text_ticket_attachment_info"); ?></small>
                            </div>
                            <div class="form-group">
                                <button id="replyTicketButton" class="btn btn-theme-secondary btn-lg btn-oval ladda-button" data-style="expand-right" data-size="xs"
                                                type="submit"><span class="ladda-label"><i class="feather icon-repeat"></i> <?= $this->lang->line("text_reply_to_ticket"); ?></span></button>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>