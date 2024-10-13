<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "./assets/config.php";
// Fetch all entries from the `contact` table and count statuses
$contact_query = "SELECT * FROM contact";
$contact_result = mysqli_query($conn, $contact_query);

// Count total, read, and unread messages
$total_messages_query = "SELECT COUNT(*) AS total FROM contact";
$total_messages_result = mysqli_query($conn, $total_messages_query);
$total_count = mysqli_fetch_assoc($total_messages_result)['total'];

$read_messages_query = "SELECT COUNT(*) AS `read_count` FROM contact WHERE status = 'read'";
$read_messages_result = mysqli_query($conn, $read_messages_query);
$read_count = mysqli_fetch_assoc($read_messages_result)['read_count'];

$unread_messages_query = "SELECT COUNT(*) AS `unread_count` FROM contact WHERE status = 'unread'";
$unread_messages_result = mysqli_query($conn, $unread_messages_query);
$unread_count = mysqli_fetch_assoc($unread_messages_result)['unread_count'];


// Check if the user is logged in
if (!isset($_SESSION['admin_username']) || !isset($_SESSION['admin_name'])) {
    // If the session is not set, redirect to the login page
    header("Location: ./index.php");
    exit();
}



// Fetch admin profile details from the `admin` table
$admin_username = $_SESSION['admin_username'];
$query = "SELECT name, username, email FROM admin WHERE username = '$admin_username'";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);

// Fetch all entries from the `contact` table
$contact_query = "SELECT * FROM contact";
$contact_result = mysqli_query($conn, $contact_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <style type="text/css">
        body { margin-top: 20px; }
        .img-responsive {
    display: block;
    max-width: 50%;
    height: 50%;
}
    </style>
</head>
<body>
    <hr>
    <div class="container bootstrap snippets bootdey">
        <div class="row">
            <div class="col-sm-10">
                <h1>Welcome, <?php echo $_SESSION['admin_name']; ?></h1>
            </div>
            <div class="col-sm-2">
                <a href="#" class="pull-right"><img title="profile image" class="img-circle img-responsive" src="./static/shujaR.jpg"></a>
            </div>
        </div>

        <?php
        // Check if the update was successful
        if (isset($_GET['update']) && $_GET['update'] == 'success') {
            echo "<div id='updateAlert' class='alert alert-success'>Your details have been updated successfully!</div>";
        }
        // Check if the delete was successful
if (isset($_GET['delete'])) {
    if ($_GET['delete'] == 'success') {
        echo "<div id='deleteAlert' class='alert alert-success'>All contacts have been deleted successfully!</div>";
    } elseif ($_GET['delete'] == 'error') {
        echo "<div id='deleteAlert' class='alert alert-danger'>Error deleting contacts. Please try again.</div>";
    }
}
        ?>
        
        <div class="row">
            <div class="col-sm-3">
                <!-- Left side panel -->
                <ul class="list-group">
                    <li class="list-group-item text-muted">Profile</li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Name</strong></span> <?php echo $admin['name']; ?></li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Username</strong></span> <?php echo $admin['username']; ?></li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Email</strong></span> <?php echo $admin['email']; ?></li>
                </ul>

                <div class="panel panel-default">
                    <div class="panel-heading">Website <i class="fa fa-link fa-1x"></i></div>
                    <div class="panel-body"><a href="https://shujaurrahman.me/">https://shujaurrahman.me</a></div>
                </div>

                <ul class="list-group">
    <li class="list-group-item text-muted">Activity <i class="fa fa-dashboard fa-1x"></i></li>
    <li class="list-group-item text-right"><span class="pull-left"><strong>Total Messages</strong></span> <?php echo $total_count; ?></li>
    <li class="list-group-item text-right"><span class="pull-left"><strong>Read Messages</strong></span> <?php echo $read_count; ?></li>
    <li class="list-group-item text-right"><span class="pull-left"><strong>Unread Messages</strong></span> <?php echo $unread_count; ?></li>
</ul>
<div class="form-group">
    <div class="col-xs-12">
        <br>
        <form action="backend/delete_all_contacts.php" method="post" onsubmit="return confirm('Are you sure you want to delete all contacts?');">
            <button class="btn btn-sm btn-danger" type="submit"><i class="glyphicon glyphicon-trash"></i> Delete All Contacts</button>
        </form>
        <br>
        <form action="logout.php" method="post" style="display:inline;">
            <button class="btn btn-sm btn-warning" type="submit"><i class="glyphicon glyphicon-log-out"></i> Logout</button>
        </form>
    </div>
</div>


            </div>
            

            <div class="col-sm-9">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
                    <li><a href="#messages" data-toggle="tab">Messages</a></li>
                    <li><a href="#settings" data-toggle="tab">Settings</a></li>
                </ul>

                <div class="tab-content">
                    <!-- Home Tab -->
                    <div class="tab-pane active" id="home">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="items">
                                <?php
if (mysqli_num_rows($contact_result) > 0) {
    $i = 1;
    while ($row = mysqli_fetch_assoc($contact_result)) {
        // Format the date in 'd M Y, h:i A' format in Indian Time Zone
        $date = new DateTime($row['date'], new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
        $formatted_date = $date->format('d M, h:i A'); // Example: 14 Oct, 02:30 PM

        // Determine the status of the message
        $status = $row['status']; // Assuming status can be 'read' or 'unread'
        $status_text = $status === 'read' ? 'Read' : 'Unread';
        $status_class = $status === 'read' ? 'text-success' : 'text-danger'; // Styling classes

        echo "<tr onclick=\"markAsRead({$row['id']})\" style=\"cursor: pointer;\">
                <td>{$i}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['number']}</td>
                <td>{$row['message']}</td>
                <td>{$formatted_date}</td>
                <td class=\"{$status_class}\">{$status_text}</td>
              </tr>";
        $i++;
    }
}
?>

                                </tbody>
                            </table>
                        </div>
                        <hr>
                    </div>

                    <!-- Messages Tab -->
                    <div class="tab-pane" id="messages">
                        <h2>Messages</h2>
                        <ul class="list-group">
                            <li class="list-group-item text-muted">Inbox</li>
                            <li class="list-group-item text-right"><a href="#" class="pull-left">Here is a link to the latest summary report from the..</a> 2.13.2014</li>
                            <li class="list-group-item text-right"><a href="#" class="pull-left">Hi Joe, There has been a request on your account since that was..</a> 2.11.2014</li>
                            <!-- Add more messages as needed -->
                        </ul>
                    </div>

                    <!-- Settings Tab -->
                    <div class="tab-pane" id="settings">
                        <hr>
                        <form class="form" action="backend/update_profile.php" method="post" id="registrationForm">
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="first_name"><h4>First name</h4></label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="first name" title="enter your first name if any." value="<?php echo $admin['name']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="username"><h4>Username</h4></label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="username" title="enter your username" value="<?php echo $admin['username']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="email"><h4>Email</h4></label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="email" title="enter your email" value="<?php echo $admin['email']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="password"><h4>Password</h4></label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="password" title="enter your password.">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <br>
                                    <button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function () {
        // Hide the success alert after 3 seconds
        setTimeout(function() {
            $('#updateAlert').fadeOut('slow', function() {
                // After fading out the alert, update the URL to remove the 'update' parameter
                const url = new URL(window.location.href);
                url.searchParams.delete('update'); // Remove the 'update' parameter
                window.history.pushState({}, document.title, url); // Update the URL without reloading
            });
        }, 3000);
    });
    function markAsRead(messageId) {
    $.ajax({
        type: "POST",
        url: "backend/mark_read.php", // Create this PHP file to handle the request
        data: { id: messageId },
        success: function(response) {
            // Reload the page or update the UI as needed
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error("Error marking message as read:", error);
        }
    });
}

$(document).ready(function () {
    // Hide the delete alert after 3 seconds
    setTimeout(function() {
        $('#deleteAlert').fadeOut('slow', function() {
            // After fading out the alert, update the URL to remove the 'delete' parameter
            const url = new URL(window.location.href);
            url.searchParams.delete('delete'); // Remove the 'delete' parameter
            window.history.pushState({}, document.title, url); // Update the URL without reloading
        });
    }, 3000);
});


</script>


</body>
</html>
