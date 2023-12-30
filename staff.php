<?php 
    include("db_connect.php");
    $name = '';
    date_default_timezone_set('Asia/Kolkata');
    session_start();

    $sql = "SELECT * FROM staff WHERE StaffID='cc1234'";
    $result = mysqli_query($conn, $sql);
    $staff = mysqli_fetch_all($result);
 
    $sname = $sadd = $scity = $sstate = $scontact = $rname = $radd = $rcity = $rstate =  $rcontact = $wgt = '';
    $status = array('disp' => '', 'ship' => '', 'out' => '', 'del' => '');
    $inp_tid = '';
    $disable_del = $disable_out = $disable_ship = '';
    $errors = array('req' => '');
    if(isset($_POST['submit'])){
        if(empty($_POST['sname'])){
            $errors['req'] = '*Required Field';
        }else{
            $sname = $_POST['sname'];
        }
        if(empty($_POST['sadd'])){
            $errors['req'] = '*Required Field';
        }else{
            $sadd = $_POST['sadd'];
        }
        if(empty($_POST['scity'])){
            $errors['req'] = '*Required Field';
        }else{
            $scity = $_POST['scity'];
        }
        if(empty($_POST['sstate'])){
            $errors['req'] = '*Required Field';
        }else{
            $sstate = $_POST['sstate'];
        }
        if(empty($_POST['scontact'])){
            $errors['req'] = '*Required Field';
        }else{
            $scontact = $_POST['scontact'];
        }
        if(empty($_POST['rname'])){
            $errors['req'] = '*Required Field';
        }else{
            $rname = $_POST['rname'];
        }
        if(empty($_POST['radd'])){
            $errors['req'] = '*Required Field';
        }else{
            $radd = $_POST['radd'];
        }        
        if(empty($_POST['rcity'])){
            $errors['req'] = '*Required Field';
        }else{
            $rcity = $_POST['rcity'];
        }
        if(empty($_POST['rstate'])){
            $errors['req'] = '*Required Field';
        }else{
            $rstate = $_POST['rstate'];
        }
        if(empty($_POST['rcontact'])){
            $errors['req'] = '*Required Field';
        }else{
            $rcontact = $_POST['rcontact'];
        }
        if(empty($_POST['wgt'])){
            $errors['req'] = '*Required Field';
        }else{
            $wgt = $_POST['wgt'];
        }
        if(array_filter($errors)){
            //echo errors
        }else{
            $price = 0;
            $sql  = "SELECT * FROM pricing WHERE State_1 = '$sstate' AND State_2 = '$rstate'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                $pricing = mysqli_fetch_assoc($result);
                $price = $pricing['cost'] * $wgt;
                
                $sql = "INSERT INTO parcel (Staffid, s_name, s_add, s_city, s_state, s_contact, r_name, r_add, r_city, r_state, r_contact, weight_kg, price) VALUES ('$id', '$sname', '$sadd', '$scity', '$sstate', $scontact, '$rname', '$radd', '$rcity', '$rstate', $rcontact, $wgt, $price) ";
                if(mysqli_query($conn, $sql)){
                    $tid = mysqli_insert_id($conn);
                    $_SESSION['tid'] = $tid;
                    header("Location: receipt.php");                    
                }else{
                    echo "Error : " . mysqli_error($conn);
                }
            }else{
                echo '<script type="text/javascript">';
                echo "setTimeout(function () { swal('Service Not Available', 'CC Couriers will reach your place soon !!', 'info');";
                echo '}, 1000);</script>';
            }
        }
    }
    if(isset($_POST['sel_order'])){
        if(empty($_POST['inp_tid'])){
            $errors['status'] = '*Required Field';
        }else{
            $inp_tid = $_POST['inp_tid'];
        }
        $sql = "SELECT * FROM status WHERE trackingid = '$inp_tid'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result)){
            $del_status = mysqli_fetch_assoc($result);
            $status['disp'] = $del_status['dispatched'];
            $status['ship'] = $del_status['shipped'];
            $status['out'] = $del_status['out_for_delivery'];
            $status['del'] = $del_status['delivered'];
            $inp_tid = $del_status['trackingid'];
            $_SESSION['up_tid'] = $inp_tid;
            if(!is_null($status['del'])){
                $disable_del = $disable_out = $disable_ship = "disabled";
            }elseif(!is_null($status['out'])){
                $disable_out = $disable_ship = "disabled";
            }elseif(!is_null($status['ship'])){
                $disable_ship = "disabled";
            }
            if(is_null($status['ship'])){
                $disable_del = $disable_out = "disabled";
            }elseif(is_null($status['out'])){
                $disable_del = "disabled";
            }
        }else{
            $errors['status'] = 'Enter a valid tracking ID';
            //echo "Error : " . mysqli_error($conn);
        }
    }
    if(isset($_POST['update'])){
        $checked = $_POST['status_upd'];
        //echo 'value : '.$checked;
        if($checked == 'delivered'){
            $sql = "UPDATE status SET delivered=CURRENT_TIMESTAMP WHERE TrackingID='$inp_tid' ";
        }elseif($checked == 'out_for_delivery'){
            $sql = "UPDATE status SET out_for_delivery=CURRENT_TIMESTAMP WHERE trackingid='$inp_tid' ";
        }elseif($checked == 'shipped'){
            $sql = "UPDATE status SET shipped=CURRENT_TIMESTAMP WHERE trackingid='$inp_tid' ";
        }
        if(mysqli_query($conn, $sql)){

        }else{
            echo 'Error : '. mysqli_error($conn);
        }

    }
    $sql = "SELECT * FROM arrived";
    $result = mysqli_query($conn, $sql);
    $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $sql = "SELECT * FROM delivered";
    $result = mysqli_query($conn, $sql);
    $delivered = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
       
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="bootstrap.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="index_styles.css">
        <link rel="icon" type="png" sizes="32x32" href="favicon-32x32.png">
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    </head>
    <body style="font-family: Tahoma, sans-serif; Background-image:url('bbg3.jpg'); background-attachment:fixed;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-10"><img src="logo.png" id="logo" style="height: 100px !important; margin-top: 10px !important; margin-left:10px !important; " ></div>
                <div class="col-2 dropdown">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" >
                        <img src="userlogo2.png" id="logo" style="height: 85px !important;" >
                        <span><?php echo $name ?></span>
                    </button>
                    <ul class="dropdown-menu text-center" id="dd-menu">
                        <li><div><a href="account.php" style="color: black; text-decoration: none;">Account</a></div></li>
                        <li><div><a href="logout.php" style="color: black; text-decoration: none;">Logout</a></div></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="background"></div> 
        
        <div class="container mt-10">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="ins-tab" data-toggle="tab" href="#ins" role="tab" aria-controls="ins" aria-selected="true" style="color: black;">New Order</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="update-tab" data-toggle="tab" href="#update" role="tab" aria-controls="update" aria-selected="false" style="color: black;">Update Order</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="cons-tab" data-toggle="tab" href="#cons" role="tab" aria-controls="cons" aria-selected="false" style="color: black;">Consignments</a>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active pt-3" id="ins" role="tabpanel" aria-labelledby="ins-tab">
                    <div class="container">
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" class="form" method="POST">
                            <div class="row text-center">
                            <div class="col-md-6 p-3" style="background-color: rgba(255, 255, 255, 0.7);">
                                <h3 class="mb-3">Sender's Details</h3>
                                <div class="form-group text-left pl-5">
                                    <label>Name    : </label>
                                    <input type="text" name="sname" style="border-radius: 8px;">
                                    <label class="text-danger"><?php echo $errors['req'];?></label>
                                </div>
                                <div class="form-group text-left pl-5">
                                    <label>Address : </label>
                                    <input type="text" name="sadd" style="border-radius: 8px;">
                                    <label class="text-danger"><?php echo $errors['req'];?></label>
                                </div>
                                <div class="form-group text-left pl-5">
                                    <label>City    : </label>
                                    <input type="text" name="scity" style="border-radius: 8px;"> 
                                    <label class="text-danger"><?php echo $errors['req'];?></label>
                                </div>
                                <div class="form-group text-left pl-5">
                                    <label>State : </label>
                                    <input type="text" name="sstate" style="border-radius: 8px;">
                                    <label class="text-danger"><?php echo $errors['req'];?></label>
                                </div>
                                <div class="form-group text-left pl-5">
                                    <label>Contact : </label>
                                    <input type="text" name="scontact" style="border-radius: 8px;">
                                    <label class="text-danger"><?php echo $errors['req'];?></label>
                                </div>
                            </div>
                            <div class="col-md-6 p-3" style="background-color: rgba(255, 255, 255, 0.7);">
                                <h3 class="mb-3">Receiver's Details</h3>
                                <div class="form-group text-left pl-5">
                                    <label>Name : </label>
                                    <input type="text" name="rname" style="border-radius: 8px;">
                                    <label class="text-danger"><?php echo $errors['req'];?></label>
                                </div>
                                <div class="form-group text-left pl-5">
                                    <label>Address : </label>
                                    <input type="text" name="radd" style="border-radius: 8px;">
                                    <label class="text-danger"><?php echo $errors['req'];?></label>
                                </div>
                                <div class="form-group text-left pl-5">
                                    <label>City : </label>
                                    <input type="text" name="rcity" style="border-radius: 8px;">
                                    <label class="text-danger"><?php echo $errors['req'];?></label>
                                </div>
                                <div class="form-group text-left pl-5">
                                    <label>State : </label>
                                    <input type="text" name="rstate" style="border-radius: 8px;">
                                    <label class="text-danger"><?php echo $errors['req'];?></label>
                                </div>
                                <div class="form-group text-left pl-5">
                                    <label>Contact : </label>
                                    <input type="text" name="rcontact" style="border-radius: 8px;" >
                                    <label class="text-danger"><?php echo $errors['req'];?></label>
                                </div>
                                <div class="form-group text-left pl-5">
                                    <label>Weight : </label>
                                    <input type="text" name="wgt" style="border-radius: 8px;">
                                    <label class="text-danger"><?php echo $errors['req'];?></label>
                                </div>
                                <input type="submit" name="submit" value="Place order" class="bt bt-primary">
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="update" role="tabpanel" aria-labelledby="update-tab">
                    <div class="container mt-10">
                        <div class="row">
                            <div class="col-4 p-4 text-center pt-0" style="background-color: rgba(255, 255, 255, 0.7); margin-top: 20px;">
                                 <form action="" method="POST" class="form">
                                    <div class="form-group">
                                        <label style="font-size: 20px;">Tracking ID : </label>
                                        <input type="text" style="border-radius: 8px;" name="inp_tid" value="<?php echo $_SESSION['up_tid'] ?? $status['trackingid']??'' ; ?>">
                                        <label class="text-danger"><?php echo $errors['status']??'';?></label>
                                    </div>
                                    <input type="submit" name="sel_order" class="btn btn-light text-center" value="Select" style="font-size: 20px;">
                                </form>
                            </div>
                            <div class="col-8 p-4 " style="background-color: rgba(255, 255, 255, 0.7); margin-top: 20px; ">
                                <h3 class="display-6 text-center pb-2 mb-3" style="border-bottom: 2px solid black;">Order Details</h3>
                                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="form">
                                    <div class="form-group">
                                        <label>Tracking ID : </label>
                                        <label><?php echo $_SESSION['up_tid'] ?? $status['trackingid']??'' ; ?></label>
                                    </div>
                                    <div class="form-group">
                                        <input type='checkbox' name='status_upd' value ="dispatched" disabled>  
                                        <label>Dispatched  </label>
                                        <?php echo $status['disp']; ?>
                                    </div>
                                    <div class="form-group">
                                        <input type='checkbox' name='status_upd' value ="shipped" <?php echo $disable_ship ?>>
                                        <label>Shipped </label>
                                        <?php echo $status['ship']; ?>
                                    </div>
                                    <div class="form-group">
                                        <input type='checkbox' name='status_upd' value ="out_for_delivery" <?php echo $disable_out ?>>
                                        <label>Out for Delivery  </label>
                                        <?php echo $status['out']; ?>
                                    </div>
                                    <div class="form-group">
                                        <input type='checkbox' name='status_upd' value ="delivered" <?php echo $disable_del ?>>
                                        <label>Delivered  </label>
                                        <?php echo $status['del']; ?>
                                    </div>
                                    <input type="submit" name="update" value="Update Details" class="btn btn-light">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="cons" role="tabpanel" aria-labelledby="cons-tab">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                        <li class="nav-item" role="presentation">
                          <a class="nav-link active" id="arr-tab" data-toggle="tab" href="#arr" role="tab" aria-controls="arr" aria-selected="true" style="color: black;">Arrived</a>
                        </li>
                        <li class="nav-item" role="presentation">
                          <a class="nav-link" id="del-tab" data-toggle="tab" href="#del" role="tab" aria-controls="del" aria-selected="false" style="color: black;">Delivered</a>
                        </li>
                      </ul>
                      <div class="tab-content b-0" id="myTabContent">
                        <div class="tab-pane fade show active" id="arr" role="tabpanel" aria-labelledby="arr-tab">
                            <table class="table table-hover table-bordered table-striped table-hover" style="background-color: rgba(255, 255, 255, 0.8);">
                                <thead class="thead-dark">
                                    <tr class="table-info"><td>TrackingID</td><td>Sender</td><td>Receiver</td><td>Weight</td><td>Price</td><td>Dispatched</td><td>Shipped</td><td>Out for delivery</td><td>Delivered</td></tr>                    
                                </thead>
                                <tbody>
                                    <?php foreach($arr as $order): ?>
                                    <tr>
                                        <td><?php echo $order['trackingID'];?></td>
                                     
                                        <td><?php echo $order['s_name'].', '.$order['s_add'].', '.$order['s_City'].', '.$order['s_state'].' - '.$order['s_Contact'];?></td>
                                        <td><?php echo $order['r_name'].', '.$order['r_add'].', '.$order['r_city'].', '.$order['r_state'].' - '.$order['r_contact'];?></td>
                                        <td><?php echo $order['weight_kg'];?></td>
                                        <td><?php echo $order['price'];?></td>
                                        <td><?php echo $order['dispatched_time'];?></td>
                                        <td><?php echo $order['shipped'];?></td>
                                        <td><?php echo $order['out_for_delivery'];?></td>
                                        <td><?php echo $order['delivered'];?></td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="del" role="tabpanel" aria-labelledby="del-tab">
                        <table class="table table-hover table-bordered table-striped table-hover" style="background-color: rgba(255, 255, 255, 0.8);" >
                                <thead class="thead-dark">
                                    <tr class="table-info"><td>TrackingID</td><td>Sender</td><td>Receiver</td><td>Weight</td><td>Price</td><td>Dispatched</td><td>Shipped</td><td>Out for delivery</td><td>Delivered</td></tr>                    
                                </thead>
                                <tbody>
                                    <?php foreach($delivered as $order): ?>
                                    <tr>
                                    <td><?php echo $order['trackingid'];?></td>
                                        
                                        <td><?php echo $order['s_name'].', '.$order['s_add'].', '.$order['s_City'].', '.$order['s_state'].' - '.$order['s_Contact'];?></td>
                                        <td><?php echo $order['r_name'].', '.$order['r_add'].', '.$order['r_city'].', '.$order['r_state'].' - '.$order['r_contact'];?></td>
                                        <td><?php echo $order['weight_kg'];?></td>
                                        <td><?php echo $order['price'];?></td>
                                        <td><?php echo $order['dispatched_time'];?></td>
                                        <td><?php echo $order['shipped'];?></td>
                                        <td><?php echo $order['out_for_delivery'];?></td>
                                        <td><?php echo $order['delivered'];?></td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                      </div>
                      
                </div>
              </div>
              <script>
                $(function() { 
                    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                        localStorage.setItem('lastTab', $(this).attr('href'));
                    });
                    var lastTab = localStorage.getItem('lastTab');
                    if (lastTab) {
                        $('[href="' + lastTab + '"]').tab('show');
                    }   
                });
              </script>
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