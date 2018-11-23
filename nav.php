<?php 

?>
<header class="header-desktop3 d-none d-lg-block">
    <div class="section__content section__content--p35">
        <div class="header3-wrap">
            <div class="header__logo">
                <a href="espace_membres.php">
                    <img src="./assets/images/logo-dog-blanc_03.png" alt="CoolAdmin">
                </a>
            </div>
            <div class="header__navbar">
                <ul class="list-unstyled">
                    <li class="has-sub">
                        <a href="espace_membres.php">
                        <?php if($user->get_power()<=100){ 
                              echo '
                            <i class="fas fa-users"></i>Utilisateurs
                        </a>'; 
                        } else {
                            echo ' <i class="fas fa-users"></i>Profil
                            </a>';
                        } ?> 
                        <ul class="header3-sub-list list-unstyled">
                        <?php if($user->get_power()<=100){ 
                              echo 
                            '<li>
                                <a href="espace_membres.php">Dashboard des Users</a>
                            </li>
                            <li>
                                <a href="create_user.php">Création d\'User</a>
                            </li>';
                        } ?>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="espace_listingcard.php">
                            <i class="fas fa-book"></i>Cartes
                        </a>
                        <ul class="header3-sub-list list-unstyled">
                        <?php if($user->get_power()<=100){ 
                              echo 
                            '<li>
                                <a href="espace_card.php">Lisiting des Cartes</a>
                            </li>
                            <li>
                                <a href="create_card.php">Création de Cartes</a>
                            </li>';
                        } ?>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="espace_membres.php">
                            <i class="fas fa-bookmark"></i>Decks
                        </a>
                        <ul class="header3-sub-list list-unstyled">
                            <li>
                                <a href="espace_membres.php">Dashboard des Decks</a>
                            </li>
                            <li>
                                <a href="create_membres.php">Création de Deck</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="espace_membres.php">
                            <i class="fas fa-flag"></i>Factions
                        </a>
                        <ul class="header3-sub-list list-unstyled">
                        <?php if($user->get_power()<=100){
                           echo'
                            <li>
                                <a href="espace_membres.php">Dashboard des Factions</a>
                            </li>
                            <li>
                                <a href="create_membres.php">Création des Héros</a>
                            </li>';
                        }?>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="espace_game.php">
                            <i class="fas fa-tablet"></i>Jouer
                        </a>
                    </li>
                    <li class="has-sub">
                        <a href="espace_membres.php?del">
                            <i class="zmdi zmdi-power"></i>Déconnection
                        </a>
                    </li>
            </div>
                <div class="account-wrap">
                    <div class="account-item account-item--style2 clearfix js-item-menu">
                        <div class="image">
                            <img src="./assets/images/avatar.jpg" >
                        </div>
                        <div class="content">
                            <a class="js-acc-btn" href="#"><?php if(isset($user)) echo $user->get_firstname().' '. $user->get_name() ; ?></a>
                        </div>
                        <div class="account-dropdown js-dropdown">
                            <div class="info clearfix">
                                <div class="image">
                                    <a href="#">
                                        <img src="./assets/images/avatar.jpg" alt="John Doe">
                                    </a>
                                </div>
                            </div>
                            <div class="account-dropdown__body">
                                <div class="account-dropdown__item">
                                    <a href="espace_membres.php">
                                        <i class="zmdi zmdi-account"></i>Account</a>
                                </div>
                                <div class="account-dropdown__item">
                                    <a href="setting_membres.php">
                                        <i class="zmdi zmdi-settings"></i>Setting</a>
                                </div>
                                <div class="account-dropdown__footer">
                                    <a href="?del">
                                     <i class="zmdi zmdi-power"></i>Logout</a>
                                 </div>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>    
