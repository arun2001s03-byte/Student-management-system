<?php
$username       = $_SESSION["username"];
$username_safe  = mysqli_real_escape_string($conn, $username);

$userResult = mysqli_query($conn,"SELECT id, name, username, profile FROM students WHERE username = '$username_safe'");
$user       = $userResult ? mysqli_fetch_assoc($userResult) : false;

if($user)
{
    $userId       = $user["id"];
    $userName     = $user["name"];
    $userUsername = $user["username"];

    if($user["profile"] != "")
    {
        $userProfile = "uploads/" . $user["profile"];
    }
    else
    {
        $userProfile = "images/user.jpg";
    }
}
else
{
    $userId       = "";
    $userName     = $username;
    $userUsername = $username;
    $userProfile  = "images/user.jpg";
}

?>

<div class="top_bar d-flex justify-content-between align-items-center">
    <ol class="breadcrumb mt-2 mb-3">
        <li class="breadcrumb-item"><a href="dashboard.php">Student Portal</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>


    <!-- PROFILE -->
    <div class="user onhover-dropdown">
        <span>
            <img src="<?php echo htmlspecialchars($userProfile); ?>"
            alt="Profile" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
        </span>

        <div class="onhover-div onhover-div-login">
            <ul class="user-box-name">
                <li class="fst_drp_li">

                    <div class="font-medium">
                        <?php echo htmlspecialchars($userName); ?>
                    </div>

                    <div class="text-small">
                        <?php echo htmlspecialchars($userUsername); ?>
                    </div>

                </li>


                <li class="product-box-contain">
                    <a href="profile.php?id=<?php echo $userId; ?>"><i class="fa-regular fa-user me-2"></i>Profile</a>
                </li>


                <li class="product-box-contain">
                    <a href="edit_profile.php"><i class="fa-solid fa-pen me-2"></i>Edit Profile</a>
                </li>


                <li class="product-box-contain">
                    <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Logout</a>
                </li>
            </ul>
            
        </div>
    </div>
</div>