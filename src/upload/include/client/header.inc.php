<?php
$title=($cfg && is_object($cfg) && $cfg->getTitle())
    ? $cfg->getTitle() : 'osTicket :: '.__('Support Ticket System');
$signin_url = ROOT_PATH . "login.php"
    . ($thisclient ? "?e=".urlencode($thisclient->getEmail()) : "");
$signout_url = ROOT_PATH . "logout.php?auth=".$ost->getLinkToken();

header("Content-Type: text/html; charset=UTF-8");
if (($lang = Internationalization::getCurrentLanguage())) {
    $langs = array_unique(array($lang, $cfg->getPrimaryLanguage()));
    $langs = Internationalization::rfc1766($langs);
    header("Content-Language: ".implode(', ', $langs));
}
?>
<!DOCTYPE html>
<html<?php
if ($lang
        && ($info = Internationalization::getLanguageInfo($lang))
        && (@$info['direction'] == 'rtl'))
    echo ' dir="rtl" class="rtl"';
if ($lang) {
    echo ' lang="' . $lang . '"';
}
?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo Format::htmlchars($title); ?></title>
    <meta name="description" content="customer support platform">
    <meta name="keywords" content="osTicket, Customer support system, support ticket system">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--	<link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/osticket.css?9ae093d" media="screen"/>-->
    <!--<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/theme.css?9ae093d" media="screen"/>-->
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/print.css?9ae093d" media="print"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>scp/css/typeahead.css?9ae093d"
         media="screen" />
    <link type="text/css" href="<?php echo ROOT_PATH; ?>css/ui-lightness/jquery-ui-1.10.3.custom.min.css?9ae093d"
        rel="stylesheet" media="screen" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/thread.css?9ae093d" media="screen"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/redactor.css?9ae093d" media="screen"/>
    <!--<link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/font-awesome.min.css?9ae093d"/>-->
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/flags.css?9ae093d"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/rtl.css?9ae093d"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/select2.min.css?9ae093d"/>

    <!-- Extended version CSS -->
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>ext_css/bootstrap.min.css" media="screen"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>ext_css/bootstrap-dialog.css" media="screen"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>ext_font-awesome/css/font-awesome.min.css" media="screen"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>ext_css/ext_base.css" media="screen"/>



    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery-1.11.2.min.js?9ae093d"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery-ui-1.10.3.custom.min.js?9ae093d"></script>
    <script src="<?php echo ROOT_PATH; ?>js/osticket.js?9ae093d"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/filedrop.field.js?9ae093d"></script>
    <script src="<?php echo ROOT_PATH; ?>scp/js/bootstrap-typeahead.js?9ae093d"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/redactor.min.js?9ae093d"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/redactor-plugins.js?9ae093d"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/redactor-osticket.js?9ae093d"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/select2.min.js?9ae093d"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/fabric.min.js?9ae093d"></script>

    <!-- Extended version JS -->
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>ext_js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>ext_js/bootstrap-dialog.js"></script>
    <!--<script type="text/javascript" src="<?php echo ROOT_PATH; ?>ext_js/contact_me.js"></script>-->
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>ext_js/html5shiv.js"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>ext_js/respond.min.js"></script>

    <script>
        $(document).ready(function() {
            $('input[type!=button]').addClass('form-control');
            $('input[type!=submit]').addClass('form-control');
            $('input[type=submit]').removeClass('form-control');
            $('input[type=button]').removeClass('form-control');
            $('input[type=reset]').removeClass('form-control');
            $('input[type!=button]').attr('size',31);
            $('input[type=text]').attr('size',31);
            $('input[type=tel]').attr('size',31);
            $('input[type=email]').attr('size',31);
            $('input.form-control').wrap('<div class="form-group"></div>');

        });
    </script>

    <?php
    if($ost && ($headers=$ost->getExtraHeaders())) {
        echo "\n\t".implode("\n\t", $headers)."\n";
    }

    // Offer alternate links for search engines
    // @see https://support.google.com/webmasters/answer/189077?hl=en
    if (($all_langs = Internationalization::getConfiguredSystemLanguages())
        && (count($all_langs) > 1)
    ) {
        $langs = Internationalization::rfc1766(array_keys($all_langs));
        $qs = array();
        parse_str($_SERVER['QUERY_STRING'], $qs);
        foreach ($langs as $L) {
            $qs['lang'] = $L; ?>
        <link rel="alternate" href="//<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>?<?php
            echo http_build_query($qs); ?>" hreflang="<?php echo $L; ?>" />
<?php
        } ?>
        <link rel="alternate" href="//<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>"
            hreflang="x-default" />
<?php
    }
    ?>
</head>
<body>
    <!--<div id="container" class="container">-->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="branding">
                <div class="container branding-logo">
                    <div class="pull-right flush-right">
                <p>
                 <?php
                    if ($thisclient && is_object($thisclient) && $thisclient->isValid()
                        && !$thisclient->isGuest()) {
                     echo Format::htmlchars($thisclient->getName()).'&nbsp;|';
                     ?>
                    <a href="<?php echo ROOT_PATH; ?>profile.php"><?php echo __('Profile'); ?></a> |
                    <a href="<?php echo ROOT_PATH; ?>tickets.php"><?php echo sprintf(__('Tickets <b>(%d)</b>'), $thisclient->getNumTickets()); ?></a> -
                    <a href="<?php echo $signout_url; ?>"><?php echo __('Sign Out'); ?></a>
                <?php
                } elseif($nav) {
                    if ($cfg->getClientRegistrationMode() == 'public') { ?>
                        <?php echo __('Guest User'); ?> | <?php
                    }
                    if ($thisclient && $thisclient->isValid() && $thisclient->isGuest()) { ?>
                        <a href="<?php echo $signout_url; ?>"><?php echo __('Sign Out'); ?></a><?php
                    }
                    elseif ($cfg->getClientRegistrationMode() != 'disabled') { ?>
                        <!--<a href="<?php echo $signin_url; ?>"><?php echo __('Sign In'); ?></a>-->
    <?php
                    }
                } ?>
                </p>
                    <p>
                <?php
                if (($all_langs = Internationalization::getConfiguredSystemLanguages())
                    && (count($all_langs) > 1)
                ) {
            $qs = array();
            parse_str($_SERVER['QUERY_STRING'], $qs);
            foreach ($all_langs as $code=>$info) {
                list($lang, $locale) = explode('_', $code);
                $qs['lang'] = $code;
                ?>
                <a class="flag flag-<?php echo strtolower($locale ?: $info['flag'] ?: $lang); ?>"
                    href="?<?php echo http_build_query($qs);
                    ?>" title="<?php echo Internationalization::getLanguageDescription($code); ?>">&nbsp;</a>
                <?php }
                } ?>
                    </p>
            </div>

            <a id="logo" href="<?php echo ROOT_PATH; ?>index.php"
            title="<?php echo __('Support Center'); ?>">
                <span class="valign-helper"></span>
                <img src="<?php echo ROOT_PATH; ?>logo.php" border=0 alt="<?php
                echo $ost->getConfig()->getTitle(); ?>">
            </a>
        </div>
            </div>

        <?php
            if($nav){ ?>
                <div class="container">
                    <div class="navbar-header">
                        <button style="float:left" type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                            <span class="sr-only">Navigation ON|OFF</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <?php
                        if ($thisclient && $thisclient->isValid() && $thisclient->isGuest()) { ?>
                            <a style="float:right" class="navbar-brand hidden-sm" href="<?php echo $signout_url; ?>"><?php echo __('Sign Out'); ?></a><?php
                        }
                        elseif ($cfg->getClientRegistrationMode() != 'disabled') { ?>
                            <a style="float:right" class="navbar-brand hidden-sm" href="login.php"><?php echo __('Sign In');?></a>
                        <?php } ?>
                        <!--<a href="index.php">Ticketing System</a>-->
                    </div>
                    <div class="collapse navbar-collapse" id="navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <?php
                            if($nav && ($navs=$nav->getNavLinks()) && is_array($navs)){
                                foreach($navs as $name =>$nav) {
                                    echo sprintf('<li><a class="%s %s" href="%s">%s</a></li>%s',$nav['active']?'active':'',$name,(ROOT_PATH.$nav['href']),$nav['desc'],"\n");
                                }
                            } ?>
                        </ul>
                    </div>
                </div>
            <?php
            }else{ ?>
             <hr>
            <?php
            } ?>
        </nav>
        <div class="page-wrapper">
        <div class="container content">

         <?php if($errors['err']) { ?>
            <div id="msg_error" class="alert alert-danger alert-dismissible text-center spacer-top" role="alert"><?php echo $errors['err']; ?></div>
         <?php }elseif($msg) { ?>
            <div id="msg_notice" class="alert alert-danger alert-dismissible text-center spacer-top" role="alert"><?php echo $msg; ?></div>
         <?php }elseif($warn) { ?>
            <div id="msg_warning" class="alert alert-danger alert-dismissible text-center spacer-top" role="alert"><?php echo $warn; ?></div>
         <?php } ?>
