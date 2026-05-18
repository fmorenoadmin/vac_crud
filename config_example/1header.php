<script type="text/javascript">
	//---------------------------------------------------
	const is_logged_in = <?= ((isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) || ($uid > 0)) ? 1 : 0; ?>;
	//---------------------------------------------------
</script>
<title><?= $pagina.TIT; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- marca -->
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="Frank Moreno, admin@fmorenoadmin.com.pe">
<!-- favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="<?= URL; ?>apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?= URL; ?>favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?= URL; ?>favicon-16x16.png">
<link rel="manifest" href="<?= URL; ?>site.webmanifest">
<!-- end favicon -->
<!-- styles -->
<!-- end styles -->
<!-- Toastr -->
<link rel="stylesheet" href="<?= PLUG; ?>toastr/toastr.min.css" />
<link rel="stylesheet" href="<?= PLUG; ?>sweetalert2/sweetalert2.min.css" />
<script type="text/javascript" src="<?= PLUG; ?>toastr/toastr.min.js"></script>
<!-- Sweealert -->
<script type="text/javascript" src="<?= PLUG; ?>sweetalert2/sweetalert2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<!-- 0mens -->
<?php include_once($rut.CONF."0mens.php"); ?>
<!-- turnstile 
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit" async defer></script>-->