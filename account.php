<?php
    	$servername="127.0.0.1";
        $username="root";
        $password="";
        $database='c_m_s';
        $conn=new mysqli($servername,$username,$password,$database);
        // check connection
        if(!$conn)
        {
            echo "Connection error: ". mysqli_connect_error();
        }
    session_start();
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM staff WHERE Staffid ='MAR1234' ";
    $result = mysqli_query($conn, $sql);
    $staff = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>MAR Couriers</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="bootstrap.css">
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="index_styles.css">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body style="font-family: Arial, Helvetica, sans-serif;Background-image:url('bbg3.jpg'); background-attachment:fixed;">
        <div ><img src="logo.png" id="logo" style="height: 100px !important; margin-top: 10px !important;"  ></div>
        <nav class="navbar navbar-toggleable-md navbar-expand-lg navbar-default navbar-light mb-10" style="background-color: rgba(255, 255, 255, 0.8);">
            <div class="container">
                <button class="navbar-toggler text-dark" data-toggle="collapse" data-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <div class="navbar-nav  " style="margin: 0 auto; font-size: large;">
                        <a class="nav-item nav-link text-dark mr-5  " href="staff.php">Back</a>
                        <a class="nav-item nav-link text-dark" href="logout.php" >Logout</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="background"></div>    
        <div class="container text-center" style="width : 50%; background-color: rgba(255, 255, 255, 0.7); margin-top: 10px; border-radius:15px; padding: 5px;">
            <h3 >Account Details</h3>
            <img src="userlogo2.png" style="margin:0 auto; height: 140px; width: 140px; margin-bottom: 15px;">
            <table class="text-left table table-bordered table-striped" >
                <tr><td style="font-weight:bold;">Name</td><td><?php echo $staff['name']; ?></td></tr>
                <tr><td style="font-weight:bold;">Staff ID</td><td><?php echo $staff['Staffid']; ?></td></tr>
                <tr><td style="font-weight:bold;">Designation</td><td><?php echo $staff['designation']; ?></td></tr>
                <tr><td style="font-weight:bold;">Gender</td><td><?php echo $staff['gender']; ?></td></tr>
                <tr><td style="font-weight:bold;">DOB</td><td><?php echo $staff['DOB']; ?></td></tr>
                <tr><td style="font-weight:bold;">DOJ</td><td><?php echo $staff['DOJ']; ?></td></tr>
                <tr><td style="font-weight:bold;">Email</td><td><?php echo $staff['Email']; ?></td></tr>
                <tr><td style="font-weight:bold;">Mobile</td><td><?php echo $staff['mobile']; ?></td></tr>
                <tr><td style="font-weight:bold;">Credits</td><td><?php echo $staff['credits']; ?></td></tr>
            </table>
        </div>
        <div class="container-fluid text-center mt-5" style="background-color: rgba(255, 255, 255, 0.7); padding: 20px; position: relative;">
            <div class="i-bar" style="display: flex; flex-direction: row; flex-wrap: wrap; justify-content:center; margin-bottom: 2em;">
                <a class="fa fa-facebook " href="#" style="border: none; text-decoration: none;  margin: 0em 1em; color:black; font-size: xx-large;"></a>
                <a class="fa fa-instagram" href="#" style="border: none; text-decoration: none;  margin: 0em 1em; color:black; font-size: xx-large;"></a>
                <a class="fa fa-envelope " href="#" style="border: none; text-decoration: none;  margin: 0em 1em; color:black; font-size: xx-large;"></a>
            </div>
            <p class="credit" style="font-size: 20px; font-stretch: 3px; text-align: center; color: black;">© MAR COURIERS</p>
        </div>
    </body>
</html>