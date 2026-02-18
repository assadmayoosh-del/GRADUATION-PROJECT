<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$fpStatus = ""; // success | invalid

if(isset($_POST['update']))
{
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];
  $newpassword = md5($_POST['newpassword']);

  $sql ="SELECT EmailId FROM tblusers WHERE EmailId=:email and ContactNo=:mobile";
  $query= $dbh->prepare($sql);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
  $query->execute();

  if($query->rowCount() > 0)
  {
    $con="UPDATE tblusers SET Password=:newpassword WHERE EmailId=:email and ContactNo=:mobile";
    $chngpwd1 = $dbh->prepare($con);
    $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
    $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
    $chngpwd1->execute();

    $fpStatus = "success";
  }
  else {
    $fpStatus = "invalid";
  }
}
?>

<script>
function valid(){
  if(document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value){
    // بدل alert
    Swal.fire({
      icon: 'warning',
      title: 'Password Mismatch',
      text: 'New Password and Confirm Password do not match!',
      confirmButtonText: 'OK'
    });
    document.chngpwd.confirmpassword.focus();
    return false;
  }
  return true;
}
</script>

<div class="modal fade" id="forgotpassword">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Password Recovery</h3>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="forgotpassword_wrap">
            <div class="col-md-12">
              <form name="chngpwd" method="post" onsubmit="return valid();">
                <div class="form-group">
                  <input type="email" name="email" class="form-control" placeholder="Your Email address*" required>
                </div>

                <div class="form-group">
                  <input type="text" name="mobile" class="form-control" placeholder="Your Reg. Mobile*" required>
                </div>

                <div class="form-group">
                  <input type="password" name="newpassword" class="form-control" placeholder="New Password*" required>
                </div>

                <div class="form-group">
                  <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password*" required>
                </div>

                <div class="form-group">
                  <input type="submit" value="Reset My Password" name="update" class="btn btn-block">
                </div>
              </form>

              <div class="text-center">
                <p class="gray_text">For security reasons we don't store your password. Your password will be reset and a new one will be send.</p>
                <p><a href="#loginform" data-toggle="modal" data-dismiss="modal"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back to Login</a></p>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- SweetAlert (إذا موجود عندك مسبقًا في footer احذف هذا السطر لتجنب التكرار) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if($fpStatus=="success"){ ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
  Swal.fire({
    icon: 'success',
    title: 'Password Updated ✅',
    text: 'Your password successfully changed',
    confirmButtonText: 'Go to Login'
  }).then(() => {
    $('#forgotpassword').modal('hide');
    $('#loginform').modal('show');
  });
});
</script>
<?php } ?>

<?php if($fpStatus=="invalid"){ ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
  Swal.fire({
    icon: 'error',
    title: 'Invalid Details',
    text: 'Email id or Mobile no is invalid',
    confirmButtonText: 'Try Again'
  }).then(() => {
    $('#forgotpassword').modal('show');
  });
});
</script>
<?php } ?>
