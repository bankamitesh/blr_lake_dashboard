 <?php 

    use \com\yuktix\lake\auth\Login as Login;
    $login = Login::tryLoginInSession();
    $gMDLMenus = array();

    if(!empty($login)) {
        array_push($gMDLMenus, ["name" => "Citizen Dashboard", "link" => "#" ]) ;
        array_push($gMDLMenus, ["name" => "Public Website", "link" => "#" ]) ;

        if($login->customerAdmin) { 
            array_push($gMDLMenus, ["name" => "Admin Dashboard", "link" => "/admin/lake/list.php"]) ;
        }
        array_push($gMDLMenus, ["name" => "Logout", "link" => "/app/logout.php"]) ;

    } else {
         array_push($gMDLMenus, ["name" => "Login", "link" => "/app/login.php"]) ;
    }

?>

 <div class="mdl-layout__drawer">
        <span class="mdl-layout__title">&nbsp;</span>
        <nav class="mdl-navigation">
         <?php foreach($gMDLMenus as $gMDLMenu)  { ?>
            <a class="mdl-navigation__link"  href="<?php echo $gMDLMenu['link']; ?>"> <?php echo $gMDLMenu['name']; ?></a>
                    
            <? } ?> 
       
        </nav>
        
    </div>
    
