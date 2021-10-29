<?php $session_data=$this->session->userdata('login'); if($ticket) { ?>
<div class="tile mb30">
    <div class="tile-title-w-btn">
        <div class="title">
        <h3><?= $title ?></h3>
        </div>
        <p><button class="btn btn-theme-grey btn-fab close-panel-button"><i class="feather icon-x"></i></button></p>
    </div>
    <div id="ticketsTab" class="tile-tabs">
        <ul>
            <li><a href="#ticketDetails"><?= $this->lang->line("text_ticket_info"); ?></a></li>
            <li><a href="#ticketReply"><?= $this->lang->line("text_reply_to_ticket"); ?></a></li>
        </ul>

        <div id="ticketDetails">
            <div class="tickets-list no-action">
                <div class="ticket-info">
                    <div class="media">
                        <div class="image-holder">
                            <?php if($ticket['profile_image']==NULL) { ?>
                                <img src="<?= base_url('assets/images/user.jpg'); ?>" alt="<?= $ticket['full_name']; ?>">
                            <?php }else { ?>
                                <img src="<?= base_url(); ?><?= $ticket['profile_image']; ?>" alt="<?= $ticket['full_name']; ?>">
                            <?php } ?>
                        </div>
                        <div class="media-body">
                            <?php if($ticket['full_name']!=NULL && $ticket['created_by']!=0 ) { ?>
                                <h6><?= $ticket['full_name']; ?></h6>
                            <?php } elseif($ticket['full_name']==NULL && $ticket['created_by']!=0 ) { ?>
                                <h6><?= $this->lang->line("text_undefined"); ?></h6>
                            <?php } elseif($ticket['full_name']==NULL && $ticket['created_by']==0 ) { ?>
                                <h6><?= $ticket['client_name']; ?> <span class="badge badge-pill badge-info"><?= $this->lang->line("text_guest"); ?></span></h6>
                            <?php } ?>
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
                <?php if($session_data['user_role_slug']!='agent'){ ?>
                <?php if($ticket['assigned_to']!=0) { ?>
                    <div class="assigned-to">
                        <div class="media">
                            <div class="image-holder">
                                <?php if($ticket['assigned_user_image']==NULL) { ?>
                                    <img src="<?= base_url('assets/images/user.jpg'); ?>" alt="<?= $ticket['assigned_user']; ?>">
                                <?php }else { ?>
                                    <img src="<?= base_url().$ticket['assigned_user_image']; ?>" alt="<?= $ticket['assigned_user']; ?>">
                                <?php } ?>
                            </div>
                            <div class="media-body">
                                <p><?= $this->lang->line("text_assigned_to"); ?></p>
                                <?php if($ticket['assigned_user']!=NULL) { ?>
                                    <h6><?= $ticket['assigned_user']; ?></h6>
                                <?php }else{?>
                                    <h6><?= $this->lang->line("text_undefined"); ?></h6>
                                <?php } ?>
                            </div>  
                        </div>
                    </div>
                <?php } ?>
                <?php } ?>
                
            </div>
            <div class="mt20 mb20">
                <h6 class="mb10"><?= $ticket['ticket_title']; ?></h6>
                <div class="mb10">
                    <?= $ticket['ticket_description']; ?>
                </div>
                <?php if($ticket['ticket_file']!=NULL) { ?>
                    <a href="<?= base_url('uploads/tickets/'); ?><?= $ticket['ticket_file']; ?>" class="btn btn-outline-theme-primary btn-oval" target="_blank"><i class="feather icon-download"></i> <?= $this->lang->line("text_download_attachment"); ?></a>
                <?php } ?>
            </div>
        </div>
        <div id="ticketReply">
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
                    <div class="mb10">
                        <?= $reply['reply_content']; ?>
                    </div>
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
</div>
<?php } ?>