<?php
include "db.php";

$errors         = [];
$name           = "";
$phone          = "";
$email          = "";
$username       = "";
$password       = "";
$age            = "";
$qualification  = [];
$hobbies        = [];
$gender         = "";
$about          = "";
$filename       = "";

if(isset($_POST["register"]))
{
    // GET FORM VALUES
    $name           = trim($_POST["name"]);
    $phone          = trim($_POST["phone"]);
    $email          = trim($_POST["email"]);
    $username       = trim($_POST["username"]);
    $password       = $_POST["pass"];
    $age            = $_POST["age"];
    $qualification  = $_POST["qualification"];
    $hobbies        = $_POST["hobbies"];
    $gender         = $_POST["gender"];
    $about          = trim($_POST["about"]);

    // VALIDATION
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

    if(!isset($_FILES["profile"]) ||$_FILES["profile"]["error"] != 0)
    {
        $errors[] = "Please select a profile picture.";
    }

    if(empty($about))
    {
        $errors[] = "Please enter something about yourself.";
    }

    // IF NO ERRORS
    if(empty($errors))
    {
        // CREATE UPLOADS FOLDER
        if(!is_dir("uploads"))
        {
            mkdir("uploads");
        }

        // PROFILE IMAGE UPLOAD
        $filename = $_FILES["profile"]["name"];
        $tempname = $_FILES["profile"]["tmp_name"];

        if(!move_uploaded_file($tempname, "uploads/" . $filename))
        {
            $errors[] = "Failed to upload profile picture.";
        }

        // CONVERT ARRAY TO STRING
        $qualification_data = implode(", ", $qualification);
        $hobbies_data = implode(", ", $hobbies);

        // INSERT DATA INTO STUDENTS TABLE
        $sql = "INSERT INTO students
        (name,phone,email,username,password,age,qualification,hobbies,gender,profile,about)
        VALUES('$name','$phone','$email','$username','$password','$age','$qualification_data','$hobbies_data','$gender',
        '$filename','$about')";

        $result = mysqli_query($conn, $sql);

        // CHECK DATABASE RESULT
        if($result)
        {
            $message_type = "success";
            $message = "Registration Successful";
        }
        else
        {
            $message_type = "danger";
            $message = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-primary">
    <div class="container py-5">
        <div class="card shadow-lg mx-auto" style="max-width:900px;">
            <!-- HEADER -->
            <div class="card-header bg-primary text-white text-center">
                <h2 class="mb-0">Student Registration Form</h2>
            </div>

            <div class="card-body">
                <!-- REGISTRATION FORM -->
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="Name" class="form-label"> Name </label>
                        <input type="text" id="Name" name="name" class="form-control" placeholder="Your Name"
                        value="<?php echo htmlspecialchars($name); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="ph" class="form-label"> Phone Number</label>
                        <input type="tel" id="ph" name="phone" class="form-control" maxlength="10"
                        placeholder="Phone Number" value="<?php echo htmlspecialchars($phone); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="Email" class="form-label">Email </label>
                        <input type="email" id="Email" name="email" class="form-control" placeholder="Your Email"
                        value="<?php echo htmlspecialchars($email); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="Username" class="form-label">Username</label>
                        <input type="text" id="Username" name="username" class="form-control" placeholder="Username"
                        value="<?php echo htmlspecialchars($username); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="pwd" class="form-label"> Password</label>
                        <input type="password" id="pwd" name="pass" class="form-control" placeholder="Password">
                    </div>

                    <div class="mb-3">
                        <label for="age" class="form-label">Age</label>
                        <select id="age" name="age" class="form-select">
                            <option value=""> Select Age</option>
                            <?php for ($i = 5; $i <= 40; $i++) { ?>
                                <option value="<?php echo $i; ?>" 
                                <?php echo $age == $i ? "selected" : ""; ?>>
                                <?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="qul" class="form-label"> Qualification</label>
                        <select id="qul" name="qualification[]" class="form-select" multiple size="6">
                            <option value ="BCA"    <?php if (in_array("BCA", $qualification)) echo "selected"; ?>>BCA</option>
                            <option value ="B.Com"  <?php if (in_array("B.Com", $qualification)) echo "selected"; ?>>B.Com</option>
                            <option value ="BBA"    <?php if (in_array("BBA", $qualification)) echo "selected"; ?>>BBA</option>
                            <option value ="MCA"    <?php if (in_array("MCA", $qualification)) echo "selected"; ?>>MCA</option>
                            <option value ="MSC"    <?php if (in_array("MSC", $qualification)) echo "selected"; ?>>MSC</option>
                            <option value ="M.Com"  <?php if (in_array("M.Com", $qualification)) echo "selected"; ?>>M.Com</option>
                        </select>
                        <small class="text-muted">Hold Ctrl to select multiple </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"> Hobbies</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="reading" name="hobbies[]"
                            value="Reading" <?php if (in_array("Reading", $hobbies)) echo "checked"; ?>>
                            <label class="form-check-label" for="reading">Reading </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="swimming" name="hobbies[]"
                            value="Swimming" <?php if (in_array("Swimming", $hobbies)) echo "checked"; ?>>
                            <label class="form-check-label" for="swimming"> Swimming</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="music" name="hobbies[]" value="Music"
                            <?php if (in_array("Music", $hobbies)) echo "checked"; ?>>
                            <label class="form-check-label" for="music"> Music</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="driving" name="hobbies[]"
                            value="Driving" <?php if (in_array("Driving", $hobbies)) echo "checked"; ?>>
                            <label class="form-check-label" for="driving">Driving</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="male" name="gender" value="Male"
                            <?php if ($gender == "Male") echo "checked"; ?>>
                            <label class="form-check-label" for="male">Male</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="female" name="gender" value="Female"
                            <?php if ($gender == "Female") echo "checked"; ?>>
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="myfile" class="form-label"> Profile Picture</label>
                        <input type="file" id="myfile" name="profile" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="about" class="form-label">About Me</label>
                        <textarea id="about" name="about" class="form-control" rows="4"
                        placeholder="Tell us about yourself"><?php echo htmlspecialchars($about); ?></textarea>
                    </div>

                    <!-- REGISTER BUTTON -->
                    <div class="text-center">
                    <button type="submit" name="register" class="btn btn-success px-5"> Register</button>
                    </div>
                </form>

                <!-- ERROR MESSAGES -->
                <?php
                if (isset($_POST["register"]) && !empty($errors))
                {
                    echo '<div class="alert alert-danger mt-4">';
                    echo '<h5>Please fix the following:</h5>';
                    foreach ($errors as $error)
                    {
                        echo "• " . htmlspecialchars($error) . "<br>";
                    }
                    echo '</div>';
                }

                // SUCCESS MESSAGE
                if (isset($_POST["register"]) && empty($errors) && isset($result) && $result)
                {
                ?>
                    <div class="alert alert-success mt-4">
                        <h4 class="mb-0">Registration Successful!</h4>
                        <p class="mb-0">Your details have been submitted successfully.</p>
                    </div>
                <?php
                }
                ?>

                <!-- DATABASE ERROR -->
                <?php
                if (isset($message_type) && $message_type == "danger")
                {
                    echo '<div class="alert alert-danger mt-4">';
                    echo htmlspecialchars($message ?? "");
                    echo '</div>';
                }
                ?>
                <div class="d-grid mt-3">
                <button type="button" class="btn btn-outline-primary btn-lg fw-bold" onclick="location.href='login.php'">Back to Login</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>