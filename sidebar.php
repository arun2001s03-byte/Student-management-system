<!-- MOBILE HEADER -->
<div class="mobile-header d-flex align-items-center justify-content-between">
    <div class="mob_logo">
        <span class="text-white fw-bold">Student Portal</span>
    </div>

    <div class="mob_menu">
        <button id="menu-toggle"><i class="fa-solid fa-bars text-white"></i></button>

        <div id="side-menu" class="side-menu">
            <button id="close-menu" class="close-btn">&times;</button>
            <div class="navigation_sec mt-4">

                <ul>
                    <li class="<?php echo ($activePage == "dashboard") ? "active_mnu" : ""; ?>">
                        <a href="dashboard.php"><i class="fa-solid fa-border-all"></i><span class="hd_tb">Dashboard</span></a>
                    </li>

                    <li class="<?php echo ($activePage == "students") ? "active_mnu" : ""; ?>">
                        <a href="student-list.php"><i class="fa-solid fa-users"></i><span class="hd_tb">Student List</span></a>
                    </li>

                    <li>
                        <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i><span class="hd_tb">Logout</span></a>
                    </li>
                </ul>

            </div>
        </div>
        <div id="overlay" class="overlay"></div>
    </div>

</div>


<!-- LEFT SIDEBAR -->

<div class="left_bar">
    <div class="logo_dash">
        <a href="dashboard.php"
            class="intro-x d-flex gap-2 align-items-center ps-3">
            <span class="text-white text-lg">Student Portal</span>
        </a>

    </div>

    <div class="navigation_sec mt-4">
        <ul>
            <li class="<?php echo ($activePage == "dashboard") ? "active_mnu" : ""; ?>">
                <a href="dashboard.php"><i class="fa-solid fa-border-all"></i><span class="hd_tb">Dashboard</span></a>
            </li>

            <li class="<?php echo ($activePage == "students") ? "active_mnu" : ""; ?>">
                <a href="student-list.php"><i class="fa-solid fa-users"></i><span class="hd_tb"> Student List</span></a>
            </li>

            <li>
                <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i><span class="hd_tb">Logout</span></a>
            </li>

        </ul>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function()
{
    const menuToggle = document.getElementById("menu-toggle");
    const sideMenu = document.getElementById("side-menu");
    const overlay = document.getElementById("overlay");
    const closeMenu = document.getElementById("close-menu");

    function openMenu()
    {
        sideMenu.style.left = "0";
        overlay.classList.add("show");
    }

    function closeMenuFunc()
    {
        sideMenu.style.left = "-250px";
        overlay.classList.remove("show");
    }

    menuToggle.addEventListener("click", openMenu);
    closeMenu.addEventListener("click", closeMenuFunc);
    overlay.addEventListener("click", closeMenuFunc);
});
</script>