<?php
  	session_start();
  	if(isset($_SESSION['admin'])){
    	header('location: admin/home.php');
  	}

    if(isset($_SESSION['voter'])){
      header('location: home.php');
    }
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page" style="background-color: #E6F2E9;"> <!-- Light green background -->
<div class="login-box" style="background-color:#8DBF8B; color:white; font-size: 22px; font-family: 'Arial';"> <!-- Modified box color to a medium green -->
    <div class="login-logo" style="background-color:#8DBF8B; color:white; font-size: 28px; font-family: 'Arial';">
        <b>Your Vote Your Future</b> <!-- Added text -->
    </div>

    <div class="login-box-body" style="background-color:#A0CCA0; color:white; font-size: 22px; font-family: 'Arial';"> <!-- Slightly lighter green for the box body -->
        <p class="login-box-msg" style="color:white; font-size: 18px; font-family: 'Arial';">Sign in to start your voting session</p>

        <form action="login.php" method="POST">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="voter" placeholder="National ID number" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8 col-xs-offset-2"> <!-- Centered the button -->
                    <button type="submit" class="btn btn-primary btn-block btn-curve" style="background-color: #66CDAA; color:white; font-size: 16px; font-family: 'Arial';" name="login"><i class="fa fa-sign-in"></i> Sign In</button>
                </div>
            </div>
        </form>
    </div>
    <?php
        if(isset($_SESSION['error'])){
            echo "
                <div class='callout callout-danger text-center mt20'>
                    <p>".$_SESSION['error']."</p> 
                </div>
            ";
            unset($_SESSION['error']);
        }
    ?>
</div>

<?php include 'includes/scripts.php' ?>
</body>
</html>
