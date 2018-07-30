<?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$email=Format::input($_POST['luser']?:$_GET['e']);
$passwd=Format::input($_POST['lpasswd']?:$_GET['t']);

$content = Page::lookupByType('banner-client');

if ($content) {
    list($title, $body) = $ost->replaceTemplateVariables(
        array($content->getName(), $content->getBody()));
} else {
    $title = __('Sign In');
    $body = __('To better serve you, we encourage our clients to register for an account and verify the email address we have on record.');
}

?>
<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header"><?php echo Format::display($title); ?><br>
            <small class="page-header-meta"><?php echo Format::display($body); ?></small>
        </h2>
    </div>
</div>

<div class="row row-grid loginSection">
    <form action="login.php" method="post" id="clientLogin">
        <?php csrf_token(); ?>

        <div class="col-md-4 col-md-offset-4 col-xs-12 text-center">
            <strong><?php echo Format::htmlchars($errors['login']); ?></strong>
            <div class="form-group">
                <input id="username" placeholder="<?php echo __('Email or Username'); ?>" type="text" name="luser" size="30" value="<?php echo $email; ?>" class="nowarn">
            </div>
            <div class="form-group">
                <input id="passwd" placeholder="<?php echo __('Password'); ?>" type="password" name="lpasswd" size="30" value="<?php echo $passwd; ?>" class="nowarn"></td>
            </div>
            <div class="form-group">
                <input class="btn btn-success" type="submit" value="<?php echo __('Sign In'); ?>">
                <?php if ($suggest_pwreset) { ?>
                <a style="padding-top:4px;display:inline-block;" href="pwreset.php"><?php echo __('Forgot My Password'); ?></a>
                <?php } ?>
            </div>
        </div>

        <div class="row row-grid">
            <?php

            $ext_bks = array();
            foreach (UserAuthenticationBackend::allRegistered() as $bk)
                if ($bk instanceof ExternalAuthentication)
                    $ext_bks[] = $bk;

            if (count($ext_bks)) {
                foreach ($ext_bks as $bk) { ?>
            <div class="external-auth"><?php $bk->renderExternalLink(); ?></div><?php
                }
            }
            if ($cfg && $cfg->isClientRegistrationEnabled()) {
                if (count($ext_bks)) echo '<hr style="width:70%"/>'; ?>
                <div class="col-md-8 col-md-offset-2 col-xs-12 text-center">
                <?php echo __('Not yet registered?'); ?> <a href="account.php?do=create"><?php echo __('Create an account'); ?></a>
                </div>
            <?php } ?>
                <div class="col-md-8 col-md-offset-2 col-xs-12 text-center">
                <b><?php echo __("I'm an agent"); ?></b> —
                <a href="<?php echo ROOT_PATH; ?>scp/"><?php echo __('sign in here'); ?></a>
                </div>

            <div class="col-md-8 col-md-offset-2 col-xs-12 text-center">
                <?php
                if ($cfg->getClientRegistrationMode() != 'disabled'
                    || !$cfg->isClientLoginRequired()) {
                    echo sprintf(__('If this is your first time contacting us or you\'ve lost the ticket number, please %s open a new ticket %s'),
                        '<a href="open.php">', '</a>');
                } ?>
            </div>
        </div>
    </form>
</div>

