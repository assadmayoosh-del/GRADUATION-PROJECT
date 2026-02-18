<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$loginStatus = ""; // success | error

if(isset($_POST['login']))
{
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  $sql ="SELECT EmailId,FullName FROM tblusers WHERE EmailId=:email and Password=:password";
  $query= $dbh->prepare($sql);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->bindParam(':password', $password, PDO::PARAM_STR);
  $query->execute();

  // بدل fetchAll (لأنه بيرجع Array) نجيب صف واحد
  $user = $query->fetch(PDO::FETCH_OBJ);

  if($user)
  {
    $_SESSION['login'] = $user->EmailId;
    $_SESSION['fname'] = $user->FullName;
    $loginStatus = "success";
  } else {
    $loginStatus = "error";
  }
}
?>

<div class="modal fade" id="loginform">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Login</h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="login_wrap">
            <div class="col-md-12 col-sm-6">
              <form method="post">
                <div class="form-group">
                  <input type="email" class="form-control" name="email" placeholder="Email address*" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" name="password" placeholder="Password*" required>
                </div>
                <div class="form-group checkbox">
                  <input type="checkbox" id="remember">
                </div>
                <div class="form-group">
                  <input type="submit" name="login" value="Login" class="btn btn-block">
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer text-center">
        <p>Don't have an account? <a href="#signupform" data-toggle="modal" data-dismiss="modal">Signup Here</a></p>
        <p><a href="#forgotpassword" data-toggle="modal" data-dismiss="modal">Forgot Password ?</a></p>
      </div>
    </div>
  </div>
</div>

<!-- SweetAlert2 (لازم تكون موجودة) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if($loginStatus=="error"){ ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
  Swal.fire({
    icon: 'error',
    title: 'Login Failed',
    text: 'Invalid email or password!',
    confirmButtonText: 'Try Again'
  }).then(() => {
    $('#loginform').modal('show');
  });
});
</script>
<?php } ?>

<?php if($loginStatus=="success"){ ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
  Swal.fire({
    icon: 'success',
    title: 'Welcome back 👋',
    text: 'Login successful',
    timer: 1400,
    showConfirmButton: false
  }).then(() => {
    window.location.href = window.location.href;
  });
});
</script>
<?php } ?>
