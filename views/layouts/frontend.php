<?php
use yii\helpers\Url;
use app\components\ApplicationHelper;
use yii\bootstrap5\Html;

$baseUrl = Url::base();

$company_name = ApplicationHelper::getCompanyName();
$company_dashboard_logo = ApplicationHelper::getCompanyName('user_logo');

$license_number = ApplicationHelper::getCompanyName('license_number');
$company_address = ApplicationHelper::getCompanyName('company_address');
$company_phone = ApplicationHelper::getCompanyName('company_phone');

$favicon_logo = ApplicationHelper::getCompanyName('favicon');
$user_picture = ApplicationHelper::getLoginUserInfo();
$isAdmin = ApplicationHelper::isAdmin();
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
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/vendors/flag-icon.css">
	
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/vendors/date-picker.css">

    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/vendors/icofont.css">

    <!-- Prism css-->
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/vendors/prism.css">

    <!-- Chartist css -->
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/vendors/chartist.css">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/vendors/bootstrap.css">

    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/template/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/custom.css">
	
	
	
	<style>
	.demo-li{
	    display:none !important;
	}
	.sys-text, .log-text{
	    font-size:36px;
	}
	.sys-text{
	    color: #FF0000;
	}
	
	.log-text{
	    color: #FFFFFF;
	}
	
	
	
	
	
	
	
	.search-area .search-input[type=text] {
	  padding: 10px;
	  font-size: 17px;
	  border: 1px solid grey;
	  float: left;
	  width: 80%;
	  background: #f1f1f1;
	}

	.search-area button {
	  float: left;
	  width: 20%;
	  padding: 10px;
	  background: #ff4c3b;
	  color: white;
	  font-size: 17px;
	  border: 1px solid grey;
	  border-left: none;
	  cursor: pointer;
	}

	.search-area button:hover {
	  background: #D3D3D3;
	}

	.search-area::after {
	  content: "";
	  clear: both;
	  display: table;
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

    <!-- page-wrapper Start-->
    <div class="page-wrapper">

        <!-- Page Header Start-->
        <div class="page-main-header">
            <div class="main-header-right row">
                <div class="main-header-left d-lg-none w-auto">
                    <div class="logo-wrapper">
                        <a href="<?=$baseUrl;?>">
							<?=@$company_name?>
                        </a>
                    </div>
                </div>
                <div class="mobile-sidebar w-auto">
                    <div class="media-body text-end switch-sm">
                        <label class="switch">
                            <a href="javascript:void(0)">
                                <i id="sidebar-toggle" data-feather="align-left"></i>
                            </a>
                        </label>
                    </div>
                </div>
                <div class="nav-right col">
                    <ul class="nav-menus">
                        <li>
                            <div class="form-inline search-form-v2">
                                <div class="search-area">
                                    <input name="search" type="text" class="search-input global_search" placeholder="Search..">  
									<button type="submit" class="global_search_btn"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()">
                                <i data-feather="maximize-2"></i>
                            </a>
                        </li>
                       
                        <li class="onhover-dropdown">
                            <div class="media align-items-center">
									<img height="50px" class="align-self-center pull-right img-50 blur-up lazyloaded"
                                    src="<?=$baseUrl?>/<?=@$company_dashboard_logo?>" alt="header-user">
                                <div class="dotted-animation">
                                    <span class="animate-circle"></span>
                                    <span class="main-circle"></span>
                                </div>
                            </div>
                            <ul style="display:none" class="profile-dropdown onhover-show-div p-20 profile-dropdown-hover">
                                <li>
                                    <a href="<?=$baseUrl ?>/?r=users/update">
                                        <i data-feather="user"></i>Edit Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="<?=$baseUrl ?>/?r=settings/update">
                                        <i data-feather="settings"></i>Settings
                                    </a>
                                </li>
								
								
								<?php
								
								    if(!Yii::$app->user->isGuest){
										print '<li>'
											. Html::beginForm(['/site/logout'])
											. Html::submitButton(
												'<i data-feather="log-out"></i> Logout (' . Yii::$app->user->identity->username . ')',
												['class' => 'logout-btn']
											)
											. Html::endForm()
											. '</li>';
									}	
								
						        ?>
                            </ul>
                        </li>
                    </ul>
                    <div class="d-lg-none mobile-toggle pull-right">
                        <i data-feather="more-horizontal"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header Ends -->

        <!-- Page Body Start-->
        <div class="page-body-wrapper">

            <!-- Page Sidebar Start-->
            <div class="page-sidebar">
                <div class="main-header-left d-none d-lg-block">
                    <div class="logo-wrapper">
                        <a href="<?=$baseUrl?>">
                            <?=$company_name?>
                        </a>
                    </div>
                </div>
                <div class="sidebar custom-scrollbar">
                    <a href="javascript:void(0)" class="sidebar-back d-lg-none d-block"><i class="fa fa-times"
                            aria-hidden="true"></i></a>
							
					<?php if(!Yii::$app->user->isGuest){ ?>
						<div class="sidebar-user">
							<?php if(@$user_picture){ ?>
							    <img class="img-60" src="<?=$baseUrl?>/<?=@$user_picture?>" alt="#">
							<?php }else{ ?>
							    <img class="img-60" src="<?=$baseUrl?>/template/images/dashboard/user3.jpg" alt="#">
							<?php } ?>
							<div>
								<h6 class="f-14"><?=Yii::$app->user->identity->username;?></h6>
							</div>
						</div>
					<?php } ?>
                    <ul class="sidebar-menu">
					

                        <li>
                            <a class="sidebar-header" href="<?=$baseUrl ?>/?r=dashboard/index">
                                <i data-feather="home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
						
						<li>
                            <a class="sidebar-header" href="<?=$baseUrl ?>/?r=router/index">
                                <i data-feather="wifi"></i>
                                <span>Router</span>
                            </a>
                        </li>
						
						<li>
                            <a class="sidebar-header" href="<?=$baseUrl ?>/?r=syslog/index">
                                <i data-feather="search"></i>
                                <span>Active Log</span>
                            </a>
                        </li>
						
						<li>
                            <a class="sidebar-header" href="<?=$baseUrl ?>/?r=syslog/search">
                                <i data-feather="search"></i>
                                <span>Log Search</span>
                            </a>
                        </li>
						
						<li>
                            <a class="sidebar-header" href="<?=$baseUrl ?>/?r=syslog/report">
                                <i data-feather="download"></i>
                                <span>Download Log</span>
                            </a>
                        </li>
						
						<li>
                            <a class="sidebar-header" href="<?=$baseUrl ?>/?r=report-backup/index">
                                <i data-feather="download"></i>
                                <span>Request Generated Log</span>
                            </a>
                        </li>



                        <li>
                            <a class="sidebar-header" href="javascript:void(0)">
                                <i data-feather="users"></i>
                                <span>Panel Users</span>
                                <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="sidebar-submenu">
							    <li>
                                    <a href="<?=$baseUrl ?>/?r=users/update">
                                        <i class="fa fa-circle"></i>Update Your Account
                                    </a>
                                </li>
								<li>
                                    <a href="<?=$baseUrl ?>/?r=login-history/index">
                                        <i class="fa fa-circle"></i>Login History
                                    </a>
                                </li>
                            </ul>
                        </li>
						
						<?php if($isAdmin){ ?>
						<li>
                            <a class="sidebar-header" href="javascript:void(0)">
                                <i data-feather="users"></i>
                                <span>Users</span>
                                <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="sidebar-submenu">
							    <li>
									<a class="sidebar-header" href="<?=$baseUrl ?>/?r=users/create">
									<i data-feather="user-plus"></i><span>Add User</span></a>
								</li>
								<li>
									<a class="sidebar-header" href="<?=$baseUrl ?>/?r=users/index">
									<i data-feather="users"></i><span>User List</span></a>
								</li>
                            </ul>
                        </li>
					   <?php } ?>
                        

						<?php if($isAdmin){ ?>
						<li>
                            <a class="sidebar-header" href="javascript:void(0)">
                                <i data-feather="users"></i>
                                <span>Settings</span>
                                <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="sidebar-submenu">
							    <li>
									<a class="sidebar-header" href="<?=$baseUrl ?>/?r=settings/update">
									<i data-feather="user-plus"></i><span>Account</span></a>
								</li>
								<li>
									<a class="sidebar-header" href="<?=$baseUrl ?>/?r=settings/email">
									<i data-feather="users"></i><span>SMTP</span></a>
								</li>
                            </ul>
                        </li>
						<?php } ?>

                        <li>
                            <a class="sidebar-header" href="<?=$baseUrl ?>/?r=site/system"><i
                                    data-feather="archive"></i><span>System Information</span></a>
                        </li>
						
						
						<?php
								
							if(!Yii::$app->user->isGuest){
								print '<li>'
									. Html::beginForm(['/site/logout'])
									. Html::submitButton(
										'<i data-feather="key"></i> Logout (' . Yii::$app->user->identity->username . ')',
										['class' => 'sidebar-header logout-custom']
									)
									. Html::endForm()
									. '</li>';
							}	
						
						?>
                    </ul>
                </div>
            </div>
            <!-- Page Sidebar Ends-->

            <!-- Right sidebar Start-->
            <div class="right-sidebar" id="right_side_bar">
                <div>
                    <div class="container p-0">
                        <div class="modal-header p-l-20 p-r-20">
                            <div class="col-sm-8 p-0">
                                <h6 class="modal-title font-weight-bold">FRIEND LIST</h6>
                            </div>
                            <div class="col-sm-4 text-end p-0">
                                <i class="me-2" data-feather="settings"></i>
                            </div>
                        </div>
                    </div>
                    <div class="friend-list-search mt-0">
                        <input type="text" placeholder="search friend">
                        <i class="fa fa-search"></i>
                    </div>
                    <div class="p-l-30 p-r-30 friend-list-name">
                        <div class="chat-box">
                            <div class="people-list friend-list">
                                <ul class="list">
                                    <li class="clearfix">
                                        <img class="rounded-circle user-image blur-up lazyloaded"
                                            src="<?=$baseUrl?>/template/images/dashboard/user.jpg" alt="">
                                        <div class="status-circle online"></div>
                                        <div class="about">
                                            <div class="name">Vincent Porter</div>
                                            <div class="status">Online</div>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <img class="rounded-circle user-image blur-up lazyloaded"
                                            src="<?=$baseUrl?>/template/images/dashboard/user1.jpg" alt="">
                                        <div class="status-circle away"></div>
                                        <div class="about">
                                            <div class="name">Ain Chavez</div>
                                            <div class="status">28 minutes ago</div>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <img class="rounded-circle user-image blur-up lazyloaded"
                                            src="<?=$baseUrl?>/template/images/dashboard/user2.jpg" alt="">
                                        <div class="status-circle online"></div>
                                        <div class="about">
                                            <div class="name">Kori Thomas</div>
                                            <div class="status">Online</div>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <img class="rounded-circle user-image blur-up lazyloaded"
                                            src="<?=$baseUrl?>/template/images/dashboard/user3.jpg" alt="">
                                        <div class="status-circle online"></div>
                                        <div class="about">
                                            <div class="name">Erica Hughes</div>
                                            <div class="status">Online</div>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <img class="rounded-circle user-image blur-up lazyloaded"
                                            src="<?=$baseUrl?>/template/images/dashboard/user3.jpg" alt="">
                                        <div class="status-circle offline"></div>
                                        <div class="about">
                                            <div class="name">Ginger Johnston</div>
                                            <div class="status">2 minutes ago</div>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <img class="rounded-circle user-image blur-up lazyloaded"
                                            src="<?=$baseUrl?>/template/images/dashboard/user5.jpg" alt="">
                                        <div class="status-circle away"></div>
                                        <div class="about">
                                            <div class="name">Prasanth Anand</div>
                                            <div class="status">2 hour ago</div>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <img class="rounded-circle user-image blur-up lazyloaded"
                                            src="<?=$baseUrl?>/template/images/dashboard/designer.jpg" alt="">
                                        <div class="status-circle online"></div>
                                        <div class="about">
                                            <div class="name">Hileri Jecno</div>
                                            <div class="status">Online</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right sidebar Ends-->

            <?php print $content; ?>

            <!-- footer start-->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
					    <div class="col-md-12 footer-copyright text-start">
                            <p class="mb-0">Support: <i class="fa fa-phone"></i> <a href="tel:+8801617622600">01617622600</a>, <a href="tel:+8809610203060">09610203060</a> <i class="fa fa-envelope"></i> <a href="mailto:support@cloudhub.com.bd">support@cloudhub.com.bd</a></p>
                        </div>
                        <div class="col-md-6 footer-copyright text-start">
                            <p class="mb-0">Copyright <?= date('Y') ?> © <?=$company_name?> All rights reserved.</p>
                        </div>
                        <div class="col-md-6 pull-right text-end">
                            <p class=" mb-0">Design & Developed by <a target="_blank" href="https://cloudhub.com.bd/"><img width="50" height="30" src="<?=$baseUrl?>/image/powered_by.png"></a></p>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- footer end-->
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
	
	<script src="<?=$baseUrl?>/template/js/datepicker/datepicker.js"></script>
    <script src="<?=$baseUrl?>/template/js/datepicker/datepicker.en.js"></script>
    <script src="<?=$baseUrl?>/template/js/datepicker/datepicker.custom.js"></script>

    <!--chartist js-->
    <script src="<?=$baseUrl?>/template/js/chart/chartist/chartist.js"></script>

    <!--chartjs js-->
    <script src="<?=$baseUrl?>/template/js/chart/chartjs/chart.min.js"></script>

    <!-- lazyload js-->
    <script src="<?=$baseUrl?>/template/js/lazysizes.min.js"></script>

    <!--copycode js-->
    <script src="<?=$baseUrl?>/template/js/prism/prism.min.js"></script>
    <script src="<?=$baseUrl?>/template/js/clipboard/clipboard.min.js"></script>
    <script src="<?=$baseUrl?>/template/js/custom-card/custom-card.js"></script>

    <!--counter js-->
    <script src="<?=$baseUrl?>/template/js/counter/jquery.waypoints.min.js"></script>
    <script src="<?=$baseUrl?>/template/js/counter/jquery.counterup.min.js"></script>
    <script src="<?=$baseUrl?>/template/js/counter/counter-custom.js"></script>

    <!--peity chart js-->
    <script src="<?=$baseUrl?>/template/js/chart/peity-chart/peity.jquery.js"></script>

    <!-- Apex Chart Js -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!--sparkline chart js-->
    <script src="<?=$baseUrl?>/template/js/chart/sparkline/sparkline.js"></script>

    <!--Customizer admin-->
    <script src="<?=$baseUrl?>/template/js/admin-customizer.js"></script>

    <!--dashboard custom js-->
    <script src="<?=$baseUrl?>/template/js/dashboard/default.js"></script>

    <!--right sidebar js-->
    <script src="<?=$baseUrl?>/template/js/chat-menu.js"></script>

    <!--height equal js-->
    <script src="<?=$baseUrl?>/template/js/height-equal.js"></script>

    <!-- lazyload js-->
    <script src="<?=$baseUrl?>/template/js/lazysizes.min.js"></script>
    <script src="<?=$baseUrl?>/template/js/xlsx.js"></script>
    <!--script admin-->
    <script src="<?=$baseUrl?>/template/js/admin-script.js"></script>
    <script src="<?=$baseUrl?>/js/backend.js"></script>
</body>

</html>