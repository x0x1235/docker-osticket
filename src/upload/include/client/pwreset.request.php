<?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$userid=Format::input($_POST['userid']);
?>
<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header"><?php echo __('Forgot My Password'); ?></h2>
    </div>
</div>
<div class="row row-grid loginSection">
    <form action="pwreset.php" method="post" id="clientLogin">
        <?php csrf_token(); ?>
        <input type="hidden" name="do" value="sendmail"/>
        <div class="col-md-8 col-md-offset-2 col-xs-12 text-center">
            <?php echo __(
                'Enter your username or email address in the form below and press the <strong>Send Email</strong> button to have a password reset link sent to your email account on file.');
            ?>
        </div>
        <div class="col-md-4 col-md-offset-4 col-xs-12 text-center">
            <div class="form-group">
                <label for="username"><strong><?php echo Format::htmlchars($banner); ?></strong></label>
                <input id="username" type="text" name="userid" size="30" value="<?php echo $userid; ?>">
            </div>
            <div class="form-group">
                <input class="btn btn-success" type="submit" value="<?php echo __('Send Email'); ?>">
            </div>
        </div>
    </form>
</div>
