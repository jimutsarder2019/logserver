<?php
use yii\helpers\Url;
use app\components\ApplicationHelper;
$baseUrl = Url::base();

$company_name = ApplicationHelper::getCompanyName();
$favicon_logo = ApplicationHelper::getCompanyName('favicon');

$license_number = ApplicationHelper::getCompanyName('license_number');
$company_address = ApplicationHelper::getCompanyName('company_address');
$company_phone = ApplicationHelper::getCompanyName('company_phone');
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Multikart admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Multikart admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="<?=$baseUrl?>/<?=$favicon_logo?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?=$baseUrl?>/<?=$favicon_logo?>" type="image/x-icon">
    <title><?=$company_name?> | Log Server</title>

    <!-- Google font-->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,500;1,600;1,700;1,800;1,900&display=swap">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">


    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/vendors/font-awesome.css">

    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/vendors/themify-icons.css">

    <!-- slick icon-->
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/vendors/slick.css">
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/vendors/slick-theme.css">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/vendors/bootstrap.css">

    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/style.css">
	<style>
	.custom-theme{
	    display:none !important;
	}
	.sys-text, .log-text{
	    font-size:36px;
	}
	.sys-text{
	    color: #FF0000;
	}
	
	.log-text{
	    color: #000000;
	}
	
    #box {
		position: absolute;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: -1;
		background-color: #FFFFFF;
	}
	</style>
	
	<script>
	    let company_name = '<?=$company_name?>';
	    let base_url = '<?=$baseUrl?>';
	    let license_number = '<?=$license_number?>';
	    let company_address = '<?=$company_address?>';
	    let company_phone = '<?=$company_phone?>';
	</script>
</head>

<body>
    <div id="box"></div>
    <!-- page-wrapper Start-->
    <div class="page-wrapper">
        <div class="authentication-box">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 p-0 card-right" style="margin:0 auto;">
                        <div class="card tab2-card card-login" style="padding-left: 0px;">
                            <div class="card-body">
							    <?php print $content ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- latest jquery-->
    <script src="<?=$baseUrl?>/template/js/jquery-3.3.1.min.js"></script>

    <!-- Bootstrap js-->
    <script src="<?=$baseUrl?>/template/js/bootstrap.bundle.min.js"></script>

    <!-- feather icon js-->
    <script src="<?=$baseUrl?>/template/js/icons/feather-icon/feather.min.js"></script>
    <script src="<?=$baseUrl?>/template/js/icons/feather-icon/feather-icon.js"></script>

    <!-- Sidebar jquery-->
    <script src="<?=$baseUrl?>/template/js/sidebar-menu.js"></script>
    <script src="<?=$baseUrl?>/template/js/slick.js"></script>

    <!-- lazyload js-->
    <script src="<?=$baseUrl?>/template/js/lazysizes.min.js"></script>

    <!--right sidebar js-->
    <script src="<?=$baseUrl?>/template/js/chat-menu.js"></script>

    <!--script admin-->
    <script src="<?=$baseUrl?>/template/js/admin-script.js"></script>
    <script src="<?=$baseUrl?>/js/welcome.js"></script>
    <script>
        $('.single-item').slick({
            arrows: false,
            dots: true
        });
		
		    $('#box').particleground({
        dotColor : '#808080',
        lineColor : '#808080',
        parallax : true
    });
    </script>

</body>

</html>