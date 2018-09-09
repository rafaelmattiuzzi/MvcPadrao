<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_SITE('assets/css/bootstrap.min.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_SITE('assets/css/style_site.css'); ?>" />
</head>
<body>
	

	<?php $this->loadViewInTemplate($local, $viewName, $viewData); ?>

	<script type="text/javascript">var BASE_SITE = '<?php echo BASE_SITE(); ?>';</script>
	<script type="text/javascript" src="<?php echo BASE_SITE('assets/js/jquery.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_SITE('assets/js/bootstrap.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_SITE('assets/js/script_site.js'); ?>"></script>
</body>
</html>