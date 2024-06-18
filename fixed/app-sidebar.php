<div class="dashboard_sidebar" id="dashboard_sidebar">
            <h3 class="dashboard_logo" id="dashboard_logo">Medicard</h3>
            <div class="dashboard_sidebar_user">
                <img src="../imspics/FDP.png" alt="User image." id="userImage">
                <span><?= $user['first_name'].' '.$user['last_name'] ?></span>
            </div>
            <div class="dashboard_sidebar_menus">
                <ul class="dashboard_menu_lists">
                    <!-- <li class="menuActive"> -->
                    <li class="listMainMenu">
                        <a href="../starting/dashboard.php"><i class="fa fa-dashboard menuIcons"></i><span class="menuText">Dashboard</span></a>
                    </li>
                    <li class="listMainMenu showHideSubMenu">
                        <a href="javascript:void(0);" class="showHideSubMenu">
                            <i class="fa fa-truck showHideSubMenu"></i>
                            <span class="menuText showHideSubMenu">Products </span>
                            <i class="fa fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                        </a>
                        
                        <ul class="subMenus">
                            <li>
                            <a href="../products/product-view.php" class="subMenuLink"><i class="fa fa-circle-o"></i>View Product</a>
                            </li>
                            <li>
                            <a href="../products/product-add.php" class="subMenuLink"><i class="fa fa-circle-o"></i>Add Product</a>
                            </li>
                            <li>
                            <a href="../products/product-order.php" class="subMenuLink"><i class="fa fa-circle-o"></i>Order Product</a>
                            </li>
                        </ul>
                    </li>
                    <li class="listMainMenu showHideSubMenu">
                        <a href="javascript:void(0);" class="showHideSubMenu">
                            <i class="fa fa-truck showHideSubMenu"></i>
                            <span class="menuText showHideSubMenu">Suppliers </span>
                            <i class="fa fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                        </a>
                        
                        <ul class="subMenus">
                            <li>
                            <a href="../suppliers/supplier-view.php" class="subMenuLink"><i class="fa fa-circle-o"></i>View Supplier</a>
                            </li>
                            <li>
                            <a href="../suppliers/supplier-add.php" class="subMenuLink"><i class="fa fa-circle-o"></i>Add Supplier</a>
                            </li>
                        </ul>
                    </li>

                    <li class="listMainMenu showHideSubMenu">
                        <a href="javascript:void(0);" class="showHideSubMenu">
                            <i class="fa fa-user-plus showHideSubMenu"></i>
                            <span class="menuText showHideSubMenu">Admins </span>
                            <i class="fa fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                        </a>
                        
                        <ul class="subMenus">
                            <li>
                            <a href="../admins/users-view.php" class="subMenuLink"><i class="fa fa-circle-o"></i>View Users</a>
                            </li>
                            <li>
                            <a href="../admins/users-add.php" class="subMenuLink"><i class="fa fa-circle-o"></i>Add Users</a>
                            </li>
                        </ul>
                    </li>

                    <li class="listMainMenu showHideSubMenu">
                        <a href="javascript:void(0);" class="showHideSubMenu">
                            <i class="fa fa-user-plus showHideSubMenu"></i>
                            <span class="menuText showHideSubMenu">RFID Citizens </span>
                            <i class="fa fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                        </a>
                        
                        <ul class="subMenus">
                            <li>
                            <a href="../rfid/user data.php" class="subMenuLink"><i class="fa fa-circle-o"></i>Citizen Data</a>
                            </li>
                            <li>
                            <a href="../rfid/registration.php" class="subMenuLink"><i class="fa fa-circle-o"></i>Citizen Registration</a>
                            </li>
                            <li>
                            <a href="../rfid/read tag.php" class="subMenuLink"><i class="fa fa-circle-o"></i>Read Citizen RFID Tag ID</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>