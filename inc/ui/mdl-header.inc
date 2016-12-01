<?php 

    use \com\yuktix\lake\auth\Login as Login;
    $login = Login::tryLoginInSession();
    $gMDLMenus = array();

    if(!empty($login)) {
        array_push($gMDLMenus, ["name" => "Upload", "link" => "#" ]) ;
        if($login->customerAdmin) { 
            array_push($gMDLMenus, ["name" => "Admin Dashboard", "link" => "/admin/view/lake/list.php"]) ;
        }
        array_push($gMDLMenus, ["name" => "Logout", "link" => "/admin/logout.php"]) ;

    } else {
         array_push($gMDLMenus, ["name" => "Login", "link" => "/admin/login.php"]) ;
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
                    <?php foreach($gMDLMenus as $gMDLMenu)  { ?>
                    <li class="mdl-menu__item"><a href="<?php echo $gMDLMenu['link']; ?>"> <?php echo $gMDLMenu['name']; ?></a></li>
                    
                    <? } ?> 
                </ul>
            </div>

        </div>
    </header>

