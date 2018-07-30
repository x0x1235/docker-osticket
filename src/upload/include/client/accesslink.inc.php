<?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$email=Format::input($_POST['lemail']?$_POST['lemail']:$_GET['e']);
$ticketid=Format::input($_POST['lticket']?$_POST['lticket']:$_GET['t']);

if ($cfg->isClientEmailVerificationRequired())
    $button = __("Email Access Link");
else
    $button = __("View Ticket");
?>
<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header"><?php echo __('Check Ticket Status'); ?><br>
<small class="page-header-meta"><?php
echo __('Please provide your email address and a ticket number.');?><br>
    <?php
if ($cfg->isClientEmailVerificationRequired())
    echo ' '.__('An access link will be emailed to you.');
else
    echo ' '.__('This will sign you in to view your ticket.');
?></small>
        </h2>
    </div>
</div>
<form action="login.php" method="post" id="clientLogin">
    <?php csrf_token(); ?>
<div class="row row-grid">

    <div class="col-md-4 col-md-offset-4 col-xs-12 text-center">

        <div class="login-box">
            <div><strong><?php echo Format::htmlchars($errors['login']); ?></strong></div>
            <div class="form-group">
                <label for="email"><?php echo __('Email Address'); ?>:
                <input id="email" placeholder="<?php echo __('e.g. john.doe@osticket.com'); ?>" type="text"
                    name="lemail" size="30" value="<?php echo $email; ?>" class="nowarn"></label>
            </div>
                <div class="form-group">
                <label for="ticketno"><?php echo __('Ticket Number'); ?>:
                <input id="ticketno" type="text" name="lticket" placeholder="<?php echo __('e.g. 051243'); ?>"
                    size="30" value="<?php echo $ticketid; ?>" class="nowarn"></label>
            </div>
            <div class="form-group">
                <input class="btn btn-success" type="submit" value="<?php echo $button; ?>">
            </p>
        </div>

    </div>
</div>
</form>

<div class="row margin-bottom-50">
    <div class="col-md-12 text-center">
        <div class="instructions">
            <?php if ($cfg && $cfg->getClientRegistrationMode() !== 'disabled') { ?>
                <?php echo __('Have an account with us?'); ?>
                <a href="login.php"><?php echo __('Sign In'); ?></a> <?php
                if ($cfg->isClientRegistrationEnabled()) { ?>
                    <?php echo sprintf(__('or %s register for an account %s to access all your tickets.'),
                        '<a href="account.php?do=create">','</a>');
                }
            }?>
        </div><br>
        <?php
        if ($cfg->getClientRegistrationMode() != 'disabled'
            || !$cfg->isClientLoginRequired()) {
            echo sprintf(
            __("If this is your first time contacting us or you've lost the ticket number, please %s open a new ticket %s"),
                '<a href="open.php">','</a>');
        } ?>
    </div>
</div>
