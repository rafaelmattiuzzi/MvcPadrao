<!DOCTYPE html>
<html lang="pt-BR">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title> Página não encontrada</title>
<link rel='shortcur icon' href="<?php echo base_site('assets/images/errors/404-icon.png'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
	*{
		margin:0;
		padding:0;
	}
    .box{
        background-color: #CCC;
        width: 50vw;
        margin: 20px auto;
        text-align: center;
        border: 1px solid #BBB;
        border-radius: 10px;
        box-shadow: 8px 8px 7px #888888;
    }
    .image_error img{
        height:300px;
        margin:20px auto;
    }
    .error_msg{
        font-size: 3em;
        color: #ffca4e;
        margin-bottom:20px;
        text-shadow:1px 1.5px #606060;
    }
    .text {
    	font-size:1.2em;
    	margin-bottom:20px;
        color:#3c3c3c;
    }
</style>
</head>
<body>
    
    <div class="box">
        <p class="image_error"><img src="<?php echo base_site('assets/images/errors/erro404.png'); ?>"></p>
        <p class="error_msg">Página não encontrada!</p>
        <p class="text">A página que você está tentando acessar não existe.</p>
    </div>
</body>
</html> 