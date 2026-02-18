<?php 
session_start();
include('includes/config.php');
error_reporting(0);

// SweetAlert messages
$swalType = "";
$swalTitle = "";
$swalText = "";
$swalRedirect = "";

if(isset($_POST['submit']))
{
  $fromdate  = $_POST['fromdate'];
  $todate    = $_POST['todate']; 
  $message   = $_POST['message'];
  $useremail = $_SESSION['login'];
  $status    = 0;
  $vhid      = $_GET['vhid'];
  $bookingno = mt_rand(100000000, 999999999);

  $ret="SELECT * FROM tblbooking 
        WHERE (
          :fromdate BETWEEN date(FromDate) AND date(ToDate) 
          OR :todate BETWEEN date(FromDate) AND date(ToDate) 
          OR date(FromDate) BETWEEN :fromdate AND :todate
        ) 
        AND VehicleId=:vhid";

  $query1 = $dbh->prepare($ret);
  $query1->bindParam(':vhid',$vhid, PDO::PARAM_STR);
  $query1->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
  $query1->bindParam(':todate',$todate,PDO::PARAM_STR);
  $query1->execute();

  if($query1->rowCount()==0)
  {
    $sql="INSERT INTO tblbooking(BookingNumber,userEmail,VehicleId,FromDate,ToDate,message,Status) 
          VALUES(:bookingno,:useremail,:vhid,:fromdate,:todate,:message,:status)";

    $query = $dbh->prepare($sql);
    $query->bindParam(':bookingno',$bookingno,PDO::PARAM_STR);
    $query->bindParam(':useremail',$useremail,PDO::PARAM_STR);
    $query->bindParam(':vhid',$vhid,PDO::PARAM_STR);
    $query->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
    $query->bindParam(':todate',$todate,PDO::PARAM_STR);
    $query->bindParam(':message',$message,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    $query->execute();

    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId)
    {
      $swalType = "success";
      $swalTitle = "Booking Sent!";
      $swalText = "Your booking request has been submitted successfully.";
      $swalRedirect = "my-booking.php";
    }
    else 
    {
      $swalType = "error";
      $swalTitle = "Booking Failed";
      $swalText = "Something went wrong. Please try again.";
      $swalRedirect = "";
    }
  }  
  else
  {
    $swalType = "warning";
    $swalTitle = "Not Available";
    $swalText = "Car already booked for these days. Please choose different dates.";
    $swalRedirect = "";
  }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>

<title>ProModel Car Rental  | Vehicle Details</title>
<!--Bootstrap -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
<!--Custome Style -->
<link rel="stylesheet" href="assets/css/style.css" type="text/css">
<!--OWL Carousel slider-->
<link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
<!--slick-slider -->
<link href="assets/css/slick.css" rel="stylesheet">
<!--bootstrap-slider -->
<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
<!--FontAwesome Font Style -->
<link href="assets/css/font-awesome.min.css" rel="stylesheet">

<!-- SWITCHER -->
<link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="assets/images/favicon-icon/favicon.png">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
</head>
<body>

<!-- Start Switcher -->
<?php include('includes/colorswitcher.php');?>
<!-- /Switcher -->  

<!--Header-->
<?php include('includes/header.php');?>
<!-- /Header --> 

<!--Listing-Image-Slider-->
<?php 
$vhid=intval($_GET['vhid']);
$sql = "SELECT tblvehicles.*,tblbrands.BrandName,tblbrands.id as bid 
        FROM tblvehicles 
        JOIN tblbrands ON tblbrands.id=tblvehicles.VehiclesBrand 
        WHERE tblvehicles.id=:vhid";
$query = $dbh->prepare($sql);
$query->bindParam(':vhid',$vhid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
{  
$_SESSION['brndid']=$result->bid;  
?>  

<section id="listing_img_slider">
  <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" alt="image" width="900" height="560"></div>
  <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage2);?>" class="img-responsive" alt="image" width="900" height="560"></div>
  <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage3);?>" class="img-responsive" alt="image" width="900" height="560"></div>
  <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage4);?>" class="img-responsive"  alt="image" width="900" height="560"></div>
  <?php if($result->Vimage5!=""){ ?>
    <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage5);?>" class="img-responsive" alt="image" width="900" height="560"></div>
  <?php } ?>
</section>
<!--/Listing-Image-Slider-->

<!--Listing-detail-->
<section class="listing-detail">
  <div class="container">
    <div class="listing_detail_head row">
      <div class="col-md-9">
        <h2><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></h2>
      </div>
      <div class="col-md-3">
        <div class="price_info">
          <p>$<?php echo htmlentities($result->PricePerDay);?> </p>Per Day
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-9">
        <div class="main_features">
          <ul>
            <li><i class="fa fa-calendar" aria-hidden="true"></i>
              <h5><?php echo htmlentities($result->ModelYear);?></h5>
              <p>Reg.Year</p>
            </li>
            <li><i class="fa fa-cogs" aria-hidden="true"></i>
              <h5><?php echo htmlentities($result->FuelType);?></h5>
              <p>Fuel Type</p>
            </li>
            <li><i class="fa fa-user-plus" aria-hidden="true"></i>
              <h5><?php echo htmlentities($result->SeatingCapacity);?></h5>
              <p>Seats</p>
            </li>
          </ul>
        </div>

        <div class="listing_more_info">
          <div class="listing_detail_wrap"> 
            <ul class="nav nav-tabs gray-bg" role="tablist">
              <li role="presentation" class="active"><a href="#vehicle-overview" aria-controls="vehicle-overview" role="tab" data-toggle="tab">Vehicle Overview</a></li>
              <li role="presentation"><a href="#accessories" aria-controls="accessories" role="tab" data-toggle="tab">Accessories</a></li>
            </ul>
            
            <div class="tab-content"> 
              <div role="tabpanel" class="tab-pane active" id="vehicle-overview">
                <p><?php echo htmlentities($result->VehiclesOverview);?></p>
              </div>

              <div role="tabpanel" class="tab-pane" id="accessories"> 
                <table>
                  <thead>
                    <tr><th colspan="2">Accessories</th></tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Air Conditioner</td>
                      <td><?php echo ($result->AirConditioner==1) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>"; ?></td>
                    </tr>
                    <tr>
                      <td>AntiLock Braking System</td>
                      <td><?php echo ($result->AntiLockBrakingSystem==1) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>"; ?></td>
                    </tr>
                    <tr>
                      <td>Power Steering</td>
                      <td><?php echo ($result->PowerSteering==1) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>"; ?></td>
                    </tr>
                    <tr>
                      <td>Power Windows</td>
                      <td><?php echo ($result->PowerWindows==1) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>"; ?></td>
                    </tr>
                    <tr>
                      <td>CD Player</td>
                      <td><?php echo ($result->CDPlayer==1) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>"; ?></td>
                    </tr>
                    <tr>
                      <td>Leather Seats</td>
                      <td><?php echo ($result->LeatherSeats==1) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>"; ?></td>
                    </tr>
                    <tr>
                      <td>Central Locking</td>
                      <td><?php echo ($result->CentralLocking==1) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>"; ?></td>
                    </tr>
                    <tr>
                      <td>Power Door Locks</td>
                      <td><?php echo ($result->PowerDoorLocks==1) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>"; ?></td>
                    </tr>
                    <tr>
                      <td>Brake Assist</td>
                      <td><?php echo ($result->BrakeAssist==1) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>"; ?></td>
                    </tr>
                    <tr>
                      <td>Driver Airbag</td>
                      <td><?php echo ($result->DriverAirbag==1) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>"; ?></td>
                    </tr>
                    <tr>
                      <td>Passenger Airbag</td>
                      <td><?php echo ($result->PassengerAirbag==1) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>"; ?></td>
                    </tr>
                    <tr>
                      <td>Crash Sensor</td>
                      <td><?php echo ($result->CrashSensor==1) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-close'></i>"; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
<?php }} ?>
      </div>
      
      <!--Side-Bar-->
      <aside class="col-md-3">
        <div class="share_vehicle">
          <p>Share: 
            <a href="#https://www.facebook.com/profile.php?id=61584374474714&mibextid=ZbWKwL"><i class="fa fa-facebook-square" aria-hidden="true"></i></a> 
            <a href="https://x.com/ProModelCar2026?t=VpwPsg66cFlDKyJ0LcPZBA&s=09"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
            <a href="https://www.instagram.com/promodelcar_2026?igsh=cmV6Nmp2dG9naWtl"><i class="fa fa-instagram" aria-hidden="true"></i></a>
          </p>
        </div>

        <div class="sidebar_widget">
          <div class="widget_heading">
            <h5><i class="fa fa-envelope" aria-hidden="true"></i>Book Now</h5>
          </div>

          <form method="post">
            <div class="form-group">
              <label>From Date:</label>
              <input type="date" class="form-control" name="fromdate" required>
            </div>
            <div class="form-group">
              <label>To Date:</label>
              <input type="date" class="form-control" name="todate" required>
            </div>
            <div class="form-group">
              <textarea rows="4" class="form-control" name="message" placeholder="Message" required></textarea>
            </div>

            <?php if($_SESSION['login']) { ?>
              <div class="form-group">
                <input type="submit" class="btn" name="submit" value="Book Now">
              </div>
            <?php } else { ?>
              <a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal">Login For Book</a>
            <?php } ?>
          </form>
        </div>
      </aside>
      <!--/Side-Bar--> 
    </div>
    
    <div class="space-20"></div>
    <div class="divider"></div>
    
    <!--Similar-Cars-->
    <div class="similar_cars">
      <h3>Similar Cars</h3>
      <div class="row">
<?php 
$bid=$_SESSION['brndid'];
$sql="SELECT tblvehicles.VehiclesTitle,tblbrands.BrandName,tblvehicles.PricePerDay,tblvehicles.FuelType,tblvehicles.ModelYear,tblvehicles.id,tblvehicles.SeatingCapacity,tblvehicles.VehiclesOverview,tblvehicles.Vimage1 
      FROM tblvehicles 
      JOIN tblbrands ON tblbrands.id=tblvehicles.VehiclesBrand 
      WHERE tblvehicles.VehiclesBrand=:bid";
$query = $dbh->prepare($sql);
$query->bindParam(':bid',$bid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
{ ?>      
        <div class="col-md-3 grid_listing">
          <div class="product-listing-m gray-bg">
            <div class="product-listing-img">
              <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>">
                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" alt="image" />
              </a>
            </div>
            <div class="product-listing-content">
              <h5><a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></a></h5>
              <p class="list-price">$<?php echo htmlentities($result->PricePerDay);?></p>
              <ul class="features_list">
                <li><i class="fa fa-user" aria-hidden="true"></i><?php echo htmlentities($result->SeatingCapacity);?> seats</li>
                <li><i class="fa fa-calendar" aria-hidden="true"></i><?php echo htmlentities($result->ModelYear);?> model</li>
                <li><i class="fa fa-car" aria-hidden="true"></i><?php echo htmlentities($result->FuelType);?></li>
              </ul>
            </div>
          </div>
        </div>
<?php }} ?>       
      </div>
    </div>
    <!--/Similar-Cars--> 
  </div>
</section>
<!--/Listing-detail--> 

<!--Footer -->
<?php include('includes/footer.php');?>
<!-- /Footer--> 

<!--Back to top-->
<div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>

<!--Login-Form -->
<?php include('includes/login.php');?>

<!--Register-Form -->
<?php include('includes/registration.php');?>

<!--Forgot-password-Form -->
<?php include('includes/forgotpassword.php');?>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/interface.js"></script> 
<script src="assets/switcher/js/switcher.js"></script>
<script src="assets/js/bootstrap-slider.min.js"></script> 
<script src="assets/js/slick.min.js"></script> 
<script src="assets/js/owl.carousel.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(!empty($swalType)){ ?>
<script>
Swal.fire({
  icon: '<?php echo $swalType; ?>',
  title: '<?php echo $swalTitle; ?>',
  text: '<?php echo $swalText; ?>',
  confirmButtonText: 'OK',
  confirmButtonColor: '<?php echo ($swalType=="success") ? "#28a745" : (($swalType=="warning") ? "#f0ad4e" : "#dc3545"); ?>'
}).then(() => {
  <?php if(!empty($swalRedirect)){ ?>
    window.location = '<?php echo $swalRedirect; ?>';
  <?php } ?>
});
</script>
<?php } ?>

</body>
</html>
