<?php
require "check_login.php";
require "db.php";

if(!isset($_GET["id"]))
{
    die("Student ID not found.");
}

$id = (int) $_GET["id"];
$result = mysqli_query($conn, "SELECT * FROM students WHERE id = $id");

if(!$result || mysqli_num_rows($result) != 1)
{
    die("Student not found.");
}

$student = mysqli_fetch_assoc($result);

if($student["profile"] != "")
{
    $profile = "uploads/" . $student["profile"];
}
else
{
    $profile = "images/user.jpg";
}

$pageTitle       = "Student Profile";
$activePage      = "students";
$breadcrumbLabel = "Student Profile";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
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
                <div class="d-flex justify-content-end align-items-center">
                    <div class="w-full w-sm-auto d-flex mt-4 mt-sm-0">
                        <a href="student-list.php" class="d-flex align-items-center btn btn-warning shadow-md">
                            <span class="ms-2"><i class="fa-solid fa-arrow-left me-1"></i>Back</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row mt-1 mx-0">
                <div class="col-md-12">
                    <div class="intro-y box px-3 py-4 mt-3">
                        <div class="d-flex flex-column-992 align-items-center">
                            <div class="col-md-4 d-flex flex-wrap px-3 align-items-center justify-content-center">
                                <div class="w-20 h-20 image-fit position-relative">
                                    <img class="rounded-full" src="<?php echo htmlspecialchars($profile); ?>"
                                        style="width:80px;height:80px;object-fit:cover;">
                                </div>

                                <div class="ms-3 text-center-1435">

                                    <div class="w-24 truncate fw-500 fs-5 text-black">
                                        <?php echo htmlspecialchars($student["name"]); ?>
                                    </div>

                                    <div class="text-slate-500">
                                        ID : <?php echo htmlspecialchars($student["id"]); ?>
                                    </div>

                                    <div class="text-slate-500">
                                        Age : <?php echo htmlspecialchars($student["age"]); ?>
                                    </div>

                                    <div class="text-slate-500">
                                        Gender : <?php echo htmlspecialchars($student["gender"]); ?>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4 mt-3 mt-lg-0 d-flex flex-column px-4 border-l pt-3 pt-lg-0 border-start border-end">
                                <div class="fw-500 text-left mt-lg-2 text-black">Contact Details</div>
                                <div class="d-flex flex-column justify-content-center align-items-center align-items-md-start mt-3 aln_itm">

                                    <div class="truncate d-flex align-items-center"> <i class="fa-regular fa-envelope me-2"></i>
                                        <?php echo htmlspecialchars($student["email"]); ?>
                                    </div>

                                    <div class="truncate d-flex align-items-center mt-2"> <i class="fa-solid fa-phone me-2"></i>
                                        <?php echo htmlspecialchars($student["phone"]); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mt-3 mt-lg-0 d-flex flex-column px-4 border-l pt-3 pt-lg-0">
                                <div class="fw-500 text-left mt-lg-2 text-black aln_itm">Details</div>
                                <div class="d-flex flex-column justify-content-center align-items-center align-items-lg-start mt-2 aln_itm">

                                    <div class="truncate d-flex align-items-center">
                                        <span class="text-slate-500">Username</span>
                                        <i class="fa-solid fa-arrow-right mx-2"></i>
                                        <?php echo htmlspecialchars($student["username"]); ?>
                                    </div>

                                    <div class="truncate d-flex align-items-center">
                                        <span class="text-slate-500">Qualification</span>
                                        <i class="fa-solid fa-arrow-right mx-2"></i>
                                        <?php echo htmlspecialchars($student["qualification"]); ?>
                                    </div>

                                    <div class="truncate d-flex align-items-center">
                                        <span class="text-slate-500">Hobbies</span>
                                        <i class="fa-solid fa-arrow-right mx-2"></i>
                                        <?php echo htmlspecialchars($student["hobbies"]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="intro-y box">
                        <div class="d-flex items-center mb-2 com_hd"><h2>Student Details</h2></div>

                        <div class="px-3">
                            <div class="d-flex flex-column">
                                <table class="table table-sm">

                                    <tbody>
                                        <tr>
                                            <td width="40%">
                                                <span class="text-slate-500">Name</span>
                                            </td>
                                            <td>: <?php echo htmlspecialchars($student["name"]); ?></td>
                                        </tr>

                                        <tr>
                                            <td width="40%">
                                                <span class="text-slate-500">Username</span>
                                            </td>
                                            <td>: <?php echo htmlspecialchars($student["username"]); ?></td>
                                        </tr>

                                        <tr>
                                            <td width="40%">
                                                <span class="text-slate-500">Age</span>
                                            </td>
                                            <td>: <?php echo htmlspecialchars($student["age"]); ?></td>
                                        </tr>

                                        <tr>
                                            <td width="40%">
                                                <span class="text-slate-500">Gender</span>
                                            </td>
                                            <td>: <?php echo htmlspecialchars($student["gender"]); ?></td>
                                        </tr>

                                        <tr>
                                            <td width="40%">
                                                <span class="text-slate-500">Qualification</span>
                                            </td>
                                            <td>: <?php echo htmlspecialchars($student["qualification"]); ?></td>
                                        </tr>

                                        <tr>
                                            <td width="40%">
                                                <span class="text-slate-500">Hobbies</span>
                                            </td>
                                            <td>: <?php echo htmlspecialchars($student["hobbies"]); ?></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="intro-y box">
                        <div class="d-flex items-center mb-2 com_hd"> <h2>Contact Details</h2></div>
                        <div class="px-3">
                            <div class="d-flex flex-column">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td width="40%">
                                                <span class="text-slate-500">Email</span>
                                            </td>
                                            <td>: <?php echo htmlspecialchars($student["email"]); ?></td>
                                        </tr>

                                        <tr>
                                            <td width="40%">
                                                <span class="text-slate-500">Phone</span>
                                            </td>
                                            <td>: <?php echo htmlspecialchars($student["phone"]); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="intro-y box">
                        <div class="d-flex items-center mb-2 com_hd"><h2>About Me</h2> </div>
                        <div class="px-4 py-3">
                            <div class="d-flex flex-column">
                                <?php echo nl2br(htmlspecialchars($student["about"])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>