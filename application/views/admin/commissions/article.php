<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?php echo form_open($this->uri->uri_string(), ['id' => 'article-form']); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>
                        <?php if (isset($article)) { ?>
                        <br />
                        <small>
                            <a href="<?php echo admin_url('commissions/view/' . $article->slug); ?>"
                                target="_blank"><?php echo admin_url('commissions/view/' . $article->slug); ?></a>
                        </small>
                        <br />
                        <?php } ?>
                    </h4>
                    <div>
                        <?php if (isset($article)) { ?>
                        <p>

                            <?php if (has_permission('knowledge_base', '', 'create')) { ?>
                            <a href="<?php echo admin_url('commissions/article'); ?>"
                                class="btn btn-success pull-right"><?php echo _l('new_commission'); ?></a>
                            <?php } ?>
                            <?php if (has_permission('knowledge_base', '', 'delete')) { ?>
                            <a href="<?php echo admin_url('commissions/delete_article/' . $article->articleid); ?>"
                                class="btn btn-danger _delete pull-right mright5"><?php echo _l('delete'); ?></a>
                            <?php } ?>
                        <div class="clearfix"></div>
                        </p>
                        <?php } ?>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <?php $value = (isset($article) ? $article->subject : ''); ?>
                        <?php $attrs = (isset($article) ? [] : ['autofocus' => true]); ?>
                        <?php echo render_input('subject', 'kb_article_add_edit_subject', $value, 'text', $attrs); ?>
                        <div class="form-group">
                            <label for="amount">
                                <small class="req text-danger">* </small>
                                <?php echo _l('commission_add_amount','',false); ?>
                            </label>
                            <div class="input-group" data-toggle="tooltip"
                                title="<?php echo isset($article) ? '' : _l('commission_amount_tooltip'); ?>">
                                <input type="number" class="form-control" name="amount"
                                    value="<?php echo $article->amount ?? ''; ?>"
                                    <?php echo isset($article) ? '' : 'autofocus' ?>
                                >
                            </div>
                        </div>
                        <p class="bold"><?php echo _l('commission_description'); ?></p>
                        <?php $contents = ''; if (isset($article)) {
                         $contents      = $article->description;
                     } ?>
                        <?php echo render_textarea('description', '', $contents, [], [], '', 'tinymce tinymce-manual'); ?>
                    </div>
                    <?php if ((has_permission('knowledge_base', '', 'create') && !isset($article)) || has_permission('knowledge_base', '', 'edit') && isset($article)) { ?>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <?php echo _l('submit'); ?>
                        </button>
                    </div>
                    <?php } ?>
                </div>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php $this->load->view('admin/knowledge_base/group'); ?>
<?php init_tail(); ?>
<script>
$(function() {
    init_editor('#description', {
        append_plugins: 'stickytoolbar'
    });
    appValidateForm($('#article-form'), {
        subject: 'required',
        amount: 'required',
        articlegroup: 'required'
    });
});
</script>
</body>

</html>