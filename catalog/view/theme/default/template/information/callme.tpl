<div id="callBack" style="display: none;" data-loaded="1">
    <div class="bootbox modal fade bootbox-alert in" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="callback-buttonclose" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="bootbox-body">
                        <form action="" id="ajax2login">
                            <div class="heading">
                                <span><i class="fa fa-phone"></i><?php echo $entry_title_call; ?></span>
                            </div>
                            <fieldset>
                                <div class="form-group required row">
                                    <label class="col-sm-3 control-label" for="callback-name"><?php echo $entry_name; ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" value="" id="callback-name" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group required row">
                                    <label class="col-sm-3 control-label" for="callback-phone"><?php echo $entry_phone; ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="phone" value="" id="callback-phone" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group nore row">
                                    <label class="col-sm-3 control-label" for="callback-comment"><?php echo $entry_comment; ?></label>
                                    <div class="col-sm-9">
                                        <textarea name="comment" rows="5" id="callback-comment" class="form-control"></textarea>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-bb-handler="ok" id="callback-submit" type="button" class="btn btn-primary"><?php echo $entry_send; ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade in"></div>
</div>