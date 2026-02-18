<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login'])==0){ 
    header('location:index.php');
    exit();
}

// SweetAlert variables
$swalType = "";
$swalTitle = "";
$swalText = "";
$swalRedirect = "";

if(isset($_POST['updatepass'])){
    $password = md5($_POST['password']);
    $newpassword = md5($_POST['newpassword']);
    $email = $_SESSION['login'];

    $sql ="SELECT Password FROM tblusers WHERE EmailId=:email AND Password=:password";
    $query= $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();

    if($query->rowCount() > 0){
        $con="UPDATE tblusers SET Password=:newpassword WHERE EmailId=:email";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();

        $swalType = "success";
        $swalTitle = "Password Updated!";
        $swalText = "Your password has been changed successfully.";
        $swalRedirect = "profile.php"; 
    } else {
        $swalType = "error";
        $swalTitle = "Wrong Password";
        $swalText = "Your current password is incorrect. Please try again.";
        $swalRedirect = "";
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<title>ProModel Car Rental - Update Password</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/font-awesome.min.css">

<!-- SWITCHER -->
<link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />

<!-- FONT -->
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">

<script>
function valid(){
    if(document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value){
        Swal.fire({
            icon: 'warning',
            title: 'Passwords do not match',
            text: 'New Password and Confirm Password must be the same.',
            confirmButtonColor: '#f0ad4e'
        });
        document.chngpwd.confirmpassword.focus();
        return false;
    }
    return true;
}
</script>

</head>

<body>
<!-- Start Switcher -->
<?php include('includes/colorswitcher.php'); ?>
<!-- /Switcher -->

<?php include('includes/header.php'); ?>

<section class="page-header profile_page">
  <div class="container">
    <div class="page-header_wrap">
      <div class="page-heading">
        <h1>Update Password</h1>
      </div>
      <ul class="coustom-breadcrumb">
        <li><a href="index.php">Home</a></li>
        <li>Update Password</li>
      </ul>
    </div>
  </div>
  <div class="dark-overlay"></div>
</section>

<?php 
$useremail=$_SESSION['login'];
$sql = "SELECT * from tblusers where EmailId=:useremail";
$query = $dbh->prepare($sql);
$query->bindParam(':useremail',$useremail, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
{ ?>

<section class="user_profile inner_pages">
  <div class="container">

    <div class="user_profile_info gray-bg padding_4x4_40">
      <div class="upload_user_logo">
        <img src="assets/images/dealer-logo.jpg" alt="image">
      </div>

      <div class="dealer_info">
        <h5><?php echo htmlentities($result->FullName);?></h5>
        <p>
          <?php echo htmlentities($result->Address);?><br>
          <?php echo htmlentities($result->City);?>&nbsp;<?php echo htmlentities($result->Country);?>
        </p>
      </div>
    </div>

    <div class="row">

      <div class="col-md-3 col-sm-3">
        <?php include('includes/sidebar.php'); ?>
      

      <div class="col-md-6 col-sm-8">
        <div class="profile_wrap">
          <h5 class="uppercase underline">Update Password</h5>

          <form name="chngpwd" method="post" onsubmit="return valid();">

            <div class="form-group">
              <label>Current Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
              <label>New Password</label>
              <input type="password" name="newpassword" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Confirm Password</label>
              <input type="password" name="confirmpassword" class="form-control" required>
            </div>

            <div class="form-group">
              <input type="submit" name="updatepass" value="Update" class="btn btn-block">
            </div>

          </form>

        </div>
      </div>
</div>
    </div>
  </div>
</section>

<?php } } ?>

<?php include('includes/footer.php');?>

<div id="back-top" class="back-top"> 
  <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i></a> 
</div>

<?php include('includes/login.php');?>
<?php include('includes/registration.php');?>
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
