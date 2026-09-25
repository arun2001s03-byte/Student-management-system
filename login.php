<?php
session_start();
include "db.php";
$error = "";

if(isset ($_POST["login"]))
    {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        if($username == "" || $password == "")
            {
                $error = "Please enter username and password.";
            }
        else
            {
                
                $sql = "SELECT username FROM students WHERE username = '$username' AND password = '$password'";
                $result = mysqli_query($conn, $sql);
        
                if($result && mysqli_num_rows($result) == 1)
                    {
                        $row = mysqli_fetch_assoc($result);
                        $_SESSION["username"] = $row["username"];
                        header("Location: dashboard.php");
                        exit();
                    }
                else 
                    {
                        $error = "Invalid username or password.";
                    }
            }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>

<body>
    <div class="main_sec">
        <div class="col-lg-5 d-flex align-items-center flex-wrap position-relative">
            <div class="logo">
                <h2>Student Login</h2>
            </div>

            <div class="form_grp_lgn position-relative">
                <h3>Sign In</h3>
                <p>Manage all your accounts in one place</p>

                <form class="frm_cls w-100" method="post">
                    <div class="input_frm_lgn">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="username" placeholder="Username" class="form-control">
                    </div>

                    <div class="input_frm_lgn">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" class="form-control">
                    </div>

                    <?php if ($error != "") {echo "<p style='color:red;'>$error</p>";}?>

                    <div class="input_frm02 d-flex justify-content-center mt-2">
                        <button type="submit" name="login" class="comn_btn_2">
                            Login
                            <i class="fa-solid fa-arrow-right-long ms-2"></i>
                        </button>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="button" class="btn btn-outline-primary btn-lg fw-bold" onclick="location.href='register.php'">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>