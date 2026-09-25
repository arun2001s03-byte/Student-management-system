<?php
include 'db.php';
include 'check_login.php';

$message = "";
$error = "";

if(isset($_GET["msg"]))
{
    if($_GET["msg"] == "updated")
    {
        $message = "Student updated successfully.";
    }
}

if(isset($_GET["delete"]))
{
    $id = (int) $_GET["delete"];

    $result = mysqli_query($conn, "SELECT username, profile FROM students WHERE id = $id");
    $student = $result ? mysqli_fetch_assoc($result) : false;

    if(!$student)
    {
        $error = "Student not found.";
    }
    elseif($student["username"] == $_SESSION["username"])
    {
        $error = "You cannot delete your own account.";
    }
    else
    {
        if($student["profile"] != "")
        {
            $file = __DIR__ . "/uploads/" . $student["profile"];

            if(file_exists($file))
            {
                unlink($file);
            }
        }

        if(mysqli_query($conn, "DELETE FROM students WHERE id = $id"))
        {
            $message = "Student deleted successfully.";
        }
        else
        {
            $error = "Error deleting student.";
        }
    }
}

$name  = isset($_GET["name"]) ? trim($_GET["name"]) : "";
$email = isset($_GET["email"]) ? trim($_GET["email"]) : "";
$phone = isset($_GET["phone"]) ? trim($_GET["phone"]) : "";

$where = "";

if($name != "")
{
    $name   = mysqli_real_escape_string($conn, $name);
    $where .= " and name like '%".$name."%'";
}

if($email != "")
{
    $email  = mysqli_real_escape_string($conn, $email);
    $where .= " and email = '".$email."'";
}

if($phone != "")
{
    $phone  = mysqli_real_escape_string($conn, $phone);
    $where .= " and phone = '".$phone."'";
}

$sql      = "SELECT * FROM students where 1=1 ".$where." ORDER BY id DESC";
$students = mysqli_query($conn, $sql);

$pageTitle       = "Manage Students";
$activePage      = "students";
$breadcrumbLabel = "Students List";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
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

            <div class="mb-4">
                <h2>Student List</h2>
            </div>

            <div class="box p-4">

            <form method="get" class="row g-2 mb-4">

                    <div class="col-md-3">
                        <input type="text" name="name" class="form-control"
                            placeholder="Search Name"
                            value="<?php echo htmlspecialchars($name); ?>">
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="phone" class="form-control"
                            placeholder="Search Phone"
                            value="<?php echo htmlspecialchars($phone); ?>">
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="email" class="form-control"
                            placeholder="Search Email"
                            value="<?php echo htmlspecialchars($email); ?>">
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass"></i> Search</button>

                        <a href="student-list.php" class="btn btn-secondary">Reset</a>
                    </div>

            </form>

                <?php if($message != "") { ?>
                    <div class="alert alert-success">
                        <?php echo $message; ?>
                    </div>
                <?php } ?>

                <?php if($error != "") { ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>

                

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if($students && mysqli_num_rows($students) > 0): ?>

                                <?php while($student = mysqli_fetch_assoc($students)): ?>

                                    <?php
                                    if($student["profile"] != "")
                                    {
                                        $profile = "uploads/" . $student["profile"];
                                    }
                                    else
                                    {
                                        $profile = "https://ui-avatars.com/api/?background=1e40af&color=fff&name="
                                        . urlencode($student["name"]);
                                    }
                                    ?>

                                    <tr>

                                        <td>
                                            <img src="<?php echo htmlspecialchars($profile); ?>"
                                                alt="<?php echo htmlspecialchars($student["name"]); ?>"
                                                width="42" height="42" style="border-radius:50%;object-fit:cover;">
                                        </td>

                                        <td><?php echo htmlspecialchars($student["name"]); ?></td>

                                        <td><?php echo htmlspecialchars($student["username"]); ?></td>

                                        <td><?php echo htmlspecialchars($student["email"]); ?></td>

                                        <td> <?php echo $student["phone"] != "" ? htmlspecialchars($student["phone"]) : "-"; ?></td>

                                        <td> <?php echo $student["gender"] != "" ? htmlspecialchars($student["gender"]) : "-"; ?></td>

                                        <td class="actions">

                                            <a href="profile.php?id=<?php echo $student["id"]; ?>"
                                                class="btn btn-success btn-sm" title="View Profile">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            
                                            <a href="edit-student.php?id=<?php echo $student["id"]; ?>"
                                                class="btn btn-primary btn-sm"
                                                title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <a href="student-list.php?delete=<?php echo $student["id"]; ?>"
                                                class="btn btn-danger btn-sm"
                                                title="Delete"
                                                onclick="return confirm('Delete this student?');">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>

                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="7" class="empty">No students found.</td>
                                </tr>

                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>