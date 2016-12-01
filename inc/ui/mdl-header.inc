<?php 



    use \com\yuktix\lake\auth\Login as Login;
    $login = Login::tryLoginInSession();
    $gHeaderMenus = array();

    $uploadLink = ["name" => "Upload", "link" => "#" ] ;
    $loginLink = ["name" => "Login", "link" => "/admin/login.php" ] ;
    $logoutLink = ["name" => "Logout", "link" => "/admin/logout.php" ] ;
    $adminLink = ["name" => "Admin Dashboard", "link" => "/admin/view/lake/list.php" ] ;

    if(!empty($login)) {

        array_push($gHeaderMenus, $uploadLink) ;
        if($login->customerAdmin) { 
            array_push($gHeaderMenus, $adminLink) ;
        }

        array_push($gHeaderMenus, $logoutLink) ;

    } else {
         array_push($gHeaderMenus, $loginLink) ;
    } 



?>

<header class="mdl-layout__header">
        <div class="mdl-layout-icon"></div>
        <div class="mdl-layout__header-row">
            <span class="mdl-layout__title">Bangalore Lake Dashboard</span>
            <div class="mdl-layout-spacer"></div>

            <div class="avatar-dropdown">
                <button id="site-toolbar-account" class="mdl-button mdl-js-button mdl-button--icon">
                    <i class="material-icons">account_circle</i>
                </button>
               
                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="site-toolbar-account">
                    <?php foreach($gHeaderMenus as $gMenu)  { ?>
                    <li class="mdl-menu__item"><a href="<?php echo $gMenu['link']; ?>"> <?php echo $gMenu['name']; ?></a></li>
                    
                    <? } ?> 
                </ul>
            </div>

        </div>
    </header>

