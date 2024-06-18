var sideBarIsOpen = true;

        toggleBtn.addEventListener( 'click', (event) => {
            event.preventDefault();

            if (sideBarIsOpen) {
                dashboard_sidebar.style.width = '10%';
                dashboard_sidebar.style.transition = '0.5s all';
                dashboard_content_container.style.width = '90%';
                dashboard_logo.style.fontSize = '30px';
                userImage.style.fontSize = '30px';

                menuIcons = document.getElementsByClassName('menuText');
                for(var i=0; i < menuIcons.length;i++) {
                    menuIcons[i].style.display = 'none';
                }
                document.getElementsByClassName('dashboard_menu_lists')[0].style.textAlign = 'center';
                console.log(menuIcons);
                sideBarIsOpen = false;
            } else {
                dashboard_sidebar.style.width = '20%';
                dashboard_content_container.style.width = '80%';
                dashboard_logo.style.fontSize = '30px';
                userImage.style.fontSize = '60px';

                menuIcons = document.getElementsByClassName('menuText');
                for(var i=0; i < menuIcons.length;i++) {
                    menuIcons[i].style.display = 'inline-block';
                }
                document.getElementsByClassName('dashboard_menu_lists')[0].style.textAlign = 'left';
                console.log(menuIcons);
                sideBarIsOpen = true;
            }
        });

        // Submenu / hide function.
        document.addEventListener('click', function (e) {
            // console.log(e);
            let clickedEl = e.target;

            if (clickedEl.classList.contains('showHideSubMenu')) {
                let subMenu = clickedEl.closest('li').querySelector('.subMenus');
                let mainMenuIcon = clickedEl.closest('li').querySelector('.mainMenuIconArrow');

                // console.log(mainMenuIcon);
                // let targetMenu = clickedEl.dataset.submenu;
                // console.log(targetMenu);
                // alert('main menu')

                // check if there is submenu
                if (subMenu != null) {
                    // let subMenu = document.getElementById(targetMenu);
                    
                    if(subMenu.style.display === 'block') {
                        subMenu.style.display = 'none';
                        mainMenuIcon.classList.remove('fa-angle-down');
                        mainMenuIcon.classList.add('fa-angle-left');
                    }
                    else {
                        subMenu.style.display = 'block';
                        mainMenuIcon.classList.add('fa-angle-down');
                        mainMenuIcon.classList.remove('fa-angle-left');
                    }
                }
            }
        });
        //     console.log(targetMenu);
        // console.log(document.querySelectorAll('.listMainMenuLink'))