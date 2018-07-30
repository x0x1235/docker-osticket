<?php
$info = $_POST;
if (!isset($info['timezone']))
    $info += array(
        'backend' => null,
    );
if (isset($user) && $user instanceof ClientCreateRequest) {
    $bk = $user->getBackend();
    $info = array_merge($info, array(
        'backend' => $bk::$id,
        'username' => $user->getUsername(),
    ));
}
$info = Format::htmlchars(($errors && $_POST)?$_POST:$info);

?>
<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header"><?php echo __('Account Registration'); ?><br>
            <small class="page-header-meta"><?php echo __(
                    'Use the forms below to create or update the information we have on file for your account'
                ); ?></small>
        </h2>
    </div>
</div>
<div class="row">

    <form action="account.php" method="post">
        <div class="col-md-5 col-md-offset-1">
              <?php csrf_token(); ?>
              <input type="hidden" name="do" value="<?php echo Format::htmlchars($_REQUEST['do']
                ?: ($info['backend'] ? 'import' :'create')); ?>" />
            <?php
            $cf = $user_form ?: UserForm::getInstance();
            $cf->render(false, false, array('mode' => 'create'));
            ?>
        </div>
        <div class="col-md-5 col-md-offset-1">
            <table class="padded">
                <tbody>

                    <tr>
                        <td colspan="2">
                            <div><h3><?php echo __('Preferences'); ?></h3>
                            </div>
                        </td>
                    </tr>
                        <tr>
                            <td width="180">
                                <?php echo __('Time Zone');?>:
                            <br>
                                <?php
                                $TZ_NAME = 'timezone';
                                $TZ_TIMEZONE = $info['timezone'];
                                include INCLUDE_DIR.'staff/templates/timezone.tmpl.php'; ?>
                                <div class="error"><?php echo $errors['timezone']; ?></div>
                            </td>
                        </tr>
                    <tr>
                        <td colspan=2">
                            <div><hr><h3><?php echo __('Access Credentials'); ?></h3></div>
                        </td>
                    </tr>
                    <?php if ($info['backend']) { ?>
                    <tr>
                        <td width="180">
                            <?php echo __('Login With'); ?>:
                        </td>
                        <td>
                            <input type="hidden" name="backend" value="<?php echo $info['backend']; ?>"/>
                            <input type="hidden" name="username" value="<?php echo $info['username']; ?>"/>
                    <?php foreach (UserAuthenticationBackend::allRegistered() as $bk) {
                        if ($bk::$id == $info['backend']) {
                            echo $bk->getName();
                            break;
                        }
                    } ?>
                        </td>
                    </tr>
                    <?php } else { ?>
                    <tr>
                        <td>
                            <?php echo __('Create a Password'); ?>:

                            <input type="password" name="passwd1" value="<?php echo $info['passwd1']; ?>">
                            &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd1']; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo __('Confirm New Password'); ?>:

                            <input type="password" name="passwd2" value="<?php echo $info['passwd2']; ?>">
                            &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd2']; ?></span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-8 col-md-offset-2 col-xs-12 text-center">
            <div class="form-group">
                <input type="submit" value="Register" class="btn btn-success"/>
                <input type="button" value="Cancel" class="btn btn-danger" onclick="javascript:
                        window.location.href='index.php';"/>
            </div>
        </div>
    </form>
</div>
<?php if (!isset($info['timezone'])) { ?>
<!-- Auto detect client's timezone where possible -->
<script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jstz.min.js?9ae093d"></script>
<script type="text/javascript">
$(function() {
    var zone = jstz.determine();
    $('#timezone-dropdown').val(zone.name()).trigger('change');
});
</script>
<?php }
