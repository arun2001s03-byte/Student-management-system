<?php
require "check_login.php";
require "db.php";

$message = "";
$error   = "";
$errors  = [];

if(!isset($_GET["id"]))
{
    die("Student ID not found.");
}

$id = (int) $_GET["id"];

$name          = "";
$phone         = "";
$email         = "";
$username      = "";
$password      = "";
$age           = "";
$qualification = [];
$hobbies       = [];
$gender        = "";
$about         = "";
$profile       = "";

$result = mysqli_query($conn, "SELECT * FROM students WHERE id = $id");

if(!$result || mysqli_num_rows($result) != 1)
{
    die("Student not found.");
}

$student = mysqli_fetch_assoc($result);

$name       = $student["name"];
$phone      = $student["phone"];
$email      = $student["email"];
$username   = $student["username"];
$password   = $student["password"];
$age        = $student["age"];

if($student["qualification"] != "")
{
    $qualification = explode(", ", $student["qualification"]);
}

if($student["hobbies"] != "")
{
    $hobbies = explode(", ", $student["hobbies"]);
}

$gender  = $student["gender"];
$about   = $student["about"];
$profile = $student["profile"];

if(isset($_POST["update"]))
{
    $name          = trim($_POST["name"]);
    $phone         = trim($_POST["phone"]);
    $email         = trim($_POST["email"]);
    $username      = trim($_POST["username"]);
    $password      = trim($_POST["pass"]);
    $age           = trim($_POST["age"]);
    $qualification = isset($_POST["qualification"]) ? $_POST["qualification"] : [];
    $hobbies       = isset($_POST["hobbies"]) ? $_POST["hobbies"] : [];
    $gender        = isset($_POST["gender"]) ? $_POST["gender"] : "";
    $about         = trim($_POST["about"]);

    if(empty($name))
    {
        $errors[] = "Please enter your name.";
    }

    if(empty($phone))
    {
        $errors[] = "Please enter your phone number.";
    }
    elseif(!preg_match("/^[0-9]{10}$/", $phone))
    {
        $errors[] = "Phone number must contain exactly 10 digits.";
    }

    if(empty($email))
    {
        $errors[] = "Please enter your email.";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $errors[] = "Please enter a valid email.";
    }

    if(empty($username))
    {
        $errors[] = "Please enter your username.";
    }

    if(empty($password))
    {
        $errors[] = "Please enter your password.";
    }
    elseif(strlen($password) < 6)
    {
        $errors[] = "Password must contain at least 6 characters.";
    }

    if(empty($age))
    {
        $errors[] = "Please select your age.";
    }

    if(empty($qualification))
    {
        $errors[] = "Please select at least one qualification.";
    }

    if(empty($hobbies))
    {
        $errors[] = "Please select at least one hobby.";
    }

    if(empty($gender))
    {
        $errors[] = "Please select your gender.";
    }

    if(empty($about))
    {
        $errors[] = "Please enter something about yourself.";
    }

    $filename = $profile;

    if(isset($_FILES["profile"]) && $_FILES["profile"]["error"] == 0)
    {
        if(!is_dir("uploads"))
        {
            mkdir("uploads", 0777, true);
        }

        $filename = $_FILES["profile"]["name"];
        $tempname = $_FILES["profile"]["tmp_name"];

        if(!move_uploaded_file($tempname, "uploads/" . $filename))
        {
            $errors[] = "Failed to upload profile picture.";
            $filename = $profile;
        }
    }

    if(empty($errors))
    {
        $qualification_data = implode(", ", $qualification);
        $hobbies_data       = implode(", ", $hobbies);

        $name               = mysqli_real_escape_string($conn, $name);
        $phone              = mysqli_real_escape_string($conn, $phone);
        $email              = mysqli_real_escape_string($conn, $email);
        $username           = mysqli_real_escape_string($conn, $username);
        $password           = mysqli_real_escape_string($conn, $password);
        $age                = mysqli_real_escape_string($conn, $age);
        $qualification_data = mysqli_real_escape_string($conn, $qualification_data);
        $hobbies_data       = mysqli_real_escape_string($conn, $hobbies_data);
        $gender             = mysqli_real_escape_string($conn, $gender);
        $filename           = mysqli_real_escape_string($conn, $filename);
        $about              = mysqli_real_escape_string($conn, $about);

        $sql = "UPDATE students SET name='$name',phone='$phone',email='$email',username='$username',password='$password',age='$age',qualification='$qualification_data',hobbies='$hobbies_data',gender='$gender',profile='$filename',about='$about' WHERE id=$id";

        $result = mysqli_query($conn, $sql);

        if($result)
        {
            if($profile != "" && $filename != $profile)
            {
                $oldfile = "uploads/" . $profile;

                if(file_exists($oldfile))
                {
                    unlink($oldfile);
                }
            }

            header("Location: student-list.php?msg=updated");
            exit();
        }
        else
        {
            $message = "Database Error: " . mysqli_error($conn);
        }
    }
}

$activePage = "students";
$breadcrumbLabel = "Edit Student";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>

<body>

<div class="bg_dashboard h-screen">
    <div class="row">

        <?php include "sidebar.php"; ?>

        <div class="right_bar">

            <?php include "header.php"; ?>

            <div class="row mt-4">
                <div class="grids gap-6 mt-1">
                    <div class="intro-y col-span-12 border-1 shadow-md">
                        <div class="intro-y box mt-3">

                            <div class="d-flex items-center mb-2 com_hd"><h2>Edit Student</h2></div>
                            <div class="p-3">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6 mt-3">
                                            <div class="input-form">
                                                <label class="form-label w-full d-flex justify-content-between align-items-center"> Name <span class="text-gray">Required</span> </label>
                                                <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                                    value="<?php echo htmlspecialchars($name); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <div class="input-form">
                                                <label class="form-label w-full d-flex justify-content-between align-items-center"> Phone Number<span class="text-gray">Required</span></label>

                                                <input type="tel" name="phone" class="form-control" maxlength="10" placeholder="Enter Phone Number"
                                                    value="<?php echo htmlspecialchars($phone); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <div class="input-form">
                                                <label class="form-label w-full d-flex justify-content-between align-items-center"> Email<span class="text-gray">Required</span></label>
                                                <input type="email" name="email" class="form-control" placeholder="Enter Email"
                                                    value="<?php echo htmlspecialchars($email); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <div class="input-form">
                                                <label class="form-label w-full d-flex justify-content-between align-items-center">Username<span class="text-gray">Required</span></label>
                                                <input type="text"
                                                    name="username" class="form-control" placeholder="Enter Username"
                                                    value="<?php echo htmlspecialchars($username); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <div class="input-form">
                                                <label class="form-label w-full d-flex justify-content-between align-items-center"> Password<span class="text-gray">Required</span></label>

                                                <input type="password" name="pass" class="form-control" placeholder="Enter Password"
                                                 value="<?php echo htmlspecialchars($password); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <div class="input-form">
                                                <label class="form-label"> Age </label>
                                                <select name="age" class="form-control">
                                                    <option value="">Select Age</option>
                                                    <?php for ($i = 5; $i <= 40; $i++) { ?>
                                                        <option value="<?php echo $i; ?>"
                                                            <?php if ($age == $i) echo "selected"; ?>>
                                                            <?php echo $i; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <div class="input-form">
                                                <label class="form-label"> Qualification </label>

                                                <select name="qualification[]" class="form-control" multiple size="6">
                                                    <option value="BCA" <?php if (in_array("BCA", $qualification)) echo "selected"; ?>>BCA</option>

                                                    <option value="B.Com<?php if (in_array("B.Com", $qualification)) echo "selected"; ?>>B.Com </option>

                                                    <option value="BBA" <?php if (in_array("BBA", $qualification)) echo "selected"; ?>>BBA </option>

                                                    <option value="MCA"<?php if (in_array("MCA", $qualification)) echo "selected"; ?>>MCA </option>

                                                    <option value="MSC" <?php if (in_array("MSC", $qualification)) echo "selected"; ?>> MSC</option>

                                                    <option value="M.Com"<?php if (in_array("M.Com", $qualification)) echo "selected"; ?>>M.Com</option>
                                                </select>
                                                     <small class="text-muted">Hold Ctrl to select multiple</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <div class="input-form">
                                                <label class="form-label"> Gender</label>
                                                <div class="d-flex gap-3 mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gender" value="Male" id="male"
                                                            <?php if ($gender == "Male") echo "checked"; ?>>
                                                        <label class="form-check-label" for="male"> Male</label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gender" value="Female" id="female"
                                                            <?php if ($gender == "Female") echo "checked"; ?>>
                                                        <label class="form-check-label" for="female"> Female</label></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <div class="input-form">
                                                <label class="form-label"> Hobbies</label>
                                                <div class="d-flex gap-3 mt-2 flex-wrap">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="hobbies[]" value="Reading" id="reading"
                                                            <?php if (in_array("Reading", $hobbies)) echo "checked"; ?>>
                                                        <label class="form-check-label" for="reading"> Reading</label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="hobbies[]" value="Swimming" id="swimming"
                                                            <?php if (in_array("Swimming", $hobbies)) echo "checked"; ?>>
                                                        <label class="form-check-label" for="swimming"> Swimming</label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="hobbies[]"  value="Music"  id="music" <?php if (in_array("Music", $hobbies)) echo "checked"; ?>>
                                                        <label class="form-check-label" for="music"> Music</label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="hobbies[]" value="Driving" id="driving"
                                                            <?php if (in_array("Driving", $hobbies)) echo "checked"; ?>>
                                                        <label class="form-check-label" for="driving"> Driving</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <div class="input-form">
                                                <label class="form-label"> Profile Picture</label>
                                                <?php if ($profile != "") { ?>
                                                    <div class="mb-3">
                                                        <img src="uploads/<?php echo htmlspecialchars($profile); ?>"
                                                            alt="Profile Picture" width="90" height="90"
                                                            style="border-radius:50%;object-fit:cover;">
                                                    </div>

                                                <?php } ?>
                                                <input type="file" name="profile" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <div class="input-form">
                                                <label class="form-label"> About Me</label>
                                                <textarea name="about" class="form-control" style="min-height:100px"
                                                 placeholder="Tell us about yourself"><?php echo htmlspecialchars($about); ?></textarea>
                                            </div>
                                        </div>

                                    </div>

                                    <button type="submit" name="update" class="btn btn-primary mt-4"> Update </button>

                                </form>

                                <?php if (!empty($errors)) { ?>
                                    <div class="alert alert-danger mt-4">
                                        <h5>Please fix the following:</h5>
                                        <?php foreach ($errors as $error) { ?>
                                            <?php echo htmlspecialchars($error); ?><br>
                                        <?php } ?>
                                    </div>

                                <?php } ?>

                                <?php if ($message != "") { ?>
                                    <div class="alert alert-danger mt-4">
                                        <?php echo htmlspecialchars($message); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>