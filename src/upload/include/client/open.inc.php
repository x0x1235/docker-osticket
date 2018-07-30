<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
$info=array();
if($thisclient && $thisclient->isValid()) {
    $info=array('name'=>$thisclient->getName(),
                'email'=>$thisclient->getEmail(),
                'phone'=>$thisclient->getPhoneNumber());
}

$info=($_POST && $errors)?Format::htmlchars($_POST):$info;

$form = null;
if (!$info['topicId']) {
    if (array_key_exists('topicId',$_GET) && preg_match('/^\d+$/',$_GET['topicId']) && Topic::lookup($_GET['topicId']))
        $info['topicId'] = intval($_GET['topicId']);
    else
        $info['topicId'] = $cfg->getDefaultTopicId();
}

$forms = array();
if ($info['topicId'] && ($topic=Topic::lookup($info['topicId']))) {
    foreach ($topic->getForms() as $F) {
        if (!$F->hasAnyVisibleFields())
            continue;
        if ($_POST) {
            $F = $F->instanciate();
            $F->isValidForClient();
        }
        $forms[] = $F;
    }
}

?>
<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header"><?php echo __('Open a New Ticket');?><br>
            <small class="page-header-meta"><?php echo __('Please fill in the form below to open a new ticket.');?></small>
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-md-9 col-lg-offset-4">

    <form id="ticketForm" method="post" action="open.php" enctype="multipart/form-data">
      <?php csrf_token(); ?>
      <input type="hidden" name="a" value="open">
      <table cellpadding="1" cellspacing="0" border="0">
        <tbody>
    <?php
            if (!$thisclient) {
                $uform = UserForm::getUserForm()->getForm($_POST);
                if ($_POST) $uform->isValid();
                $uform->render(false);
            }
            else { ?>
                <tr><td colspan="2"></td></tr>
            <tr><td><?php echo __('Email'); ?>:</td><td><?php
                echo $thisclient->getEmail(); ?></td></tr>
            <tr><td><?php echo __('Client'); ?>:</td><td><?php
                echo Format::htmlchars($thisclient->getName()); ?></td></tr>
            <?php } ?>
        </tbody>
        <tbody>
        <tr><td colspan="2"><hr />
            <div class="form-header" style="margin-bottom:0.5em">
            <b><?php echo __('Help Topic'); ?></b>
            </div>
        </td></tr>
        <tr>
            <td colspan="2">
                <select class="form-control" id="topicId" name="topicId" onchange="javascript:
                        var data = $(':input[name]', '#dynamic-form').serialize();
                        $.ajax(
                          'ajax.php/form/help-topic/' + this.value,
                          {
                            data: data,
                            dataType: 'json',
                            success: function(json) {
                              $('#dynamic-form').empty().append(json.html);
                              $(document.head).append(json.media);
                            }
                          });">
                    <option value="" selected="selected">&mdash; <?php echo __('Select a Help Topic');?> &mdash;</option>
                    <?php
                    if($topics=Topic::getPublicHelpTopics()) {
                        foreach($topics as $id =>$name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['topicId']==$id)?'selected="selected"':'', $name);
                        }
                    } else { ?>
                        <option value="0" ><?php echo __('General Inquiry');?></option>
                    <?php
                    } ?>
                </select>
                <font class="error">&nbsp;<?php echo $errors['topicId']; ?></font>
            </td>
        </tr>
        </tbody>
        <tbody id="dynamic-form">
            <?php foreach ($forms as $form) {
                include(CLIENTINC_DIR . 'templates/dynamic-form.tmpl.php');
            } ?>
        </tbody>
        <tbody>
        <?php
        if($cfg && $cfg->isCaptchaEnabled() && (!$thisclient || !$thisclient->isValid())) {
            if($_POST && $errors && !$errors['captcha'])
                $errors['captcha']=__('Please re-enter the text again');
            ?>
        <tr class="captchaRow">
            <td class="required"><?php echo __('CAPTCHA Text');?>:</td>
            <td>
                <span class="captcha"><img src="captcha.php" border="0" align="left"></span>
                &nbsp;&nbsp;
                <input id="captcha" type="text" name="captcha" size="6" autocomplete="off">
                <em><?php echo __('Enter the text shown on the image.');?></em>
                <font class="error">*&nbsp;<?php echo $errors['captcha']; ?></font>
            </td>
        </tr>
        <?php
        } ?>
        <tr><td colspan=2>&nbsp;</td></tr>
        </tbody>
      </table>

    <div class="row margin-bottom-50">
      <div class="col-lg-12" >
            <input class="btn btn-success" type="submit" value="<?php echo __('Create Ticket');?>">
            <input class="btn btn-warning" type="reset" name="reset" value="<?php echo __('Reset');?>">
            <!--<input class="btn btn-danger" type="button" name="cancel" value="<?php echo __('Cancel'); ?>" onclick="javascript:
                $('.richtext').each(function() {
                    var redactor = $(this).data('redactor');
                    if (redactor && redactor.opts.draftDelete)
                        redactor.deleteDraft();
                });
                window.location.href='index.php';">-->
        </div>
        </div>
    </form>
    </div>
</div>
