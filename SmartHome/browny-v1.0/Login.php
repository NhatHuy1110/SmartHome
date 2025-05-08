<?php
include('login_customer.php'); // Includes login logic

if (isset($_SESSION['login_customer'])) {
    header("Location: index.php"); // Already logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Smart Home Login</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="assets/img/P.png.png">
    <link rel="stylesheet" href="assets/css/customerlogin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato|Open+Sans:300,400,700,400italic,700italic|Montserrat:400,700">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/w3css/w3.css">
</head>

<body background="assets/img/blank.png">
    <nav class="navbar navbar-default bootsnav navbar-fixed dark no-background">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="index.php">Smart Home</a>
                <a class="navbar-brand" href="Login.php">
                    <span class="glyphicon glyphicon-user"></span> Welcome! Please Login
                </a>
            </div>
            <div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
                <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                    <li class="smooth-menu active"></li>
                    <li><a href="Login.php">Login</a></li>
                    <li><a href="Contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="jumbotron text-center">
            <h1>Smart Home</h1>
            <p>Please LOGIN to continue.</p>
        </div>

        <div class="col-md-5 col-md-offset-4">
            <?php if (!empty($error)): ?>
                <label class="text-danger" style="margin-left: 5px;"><?php echo $error; ?></label>
            <?php endif; ?>

            <div class="panel panel-primary">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="customer_username">
                                <span class="text-danger">*</span> Username:
                            </label>
                            <div class="input-group">
                                <input class="form-control" id="customer_username" name="customer_username" type="text" placeholder="Username" required autofocus>
                                <span class="input-group-btn">
                                    <label class="btn btn-primary"><span class="glyphicon glyphicon-user"></span></label>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="customer_password">
                                <span class="text-danger">*</span> Password:
                            </label>
                            <div class="input-group">
                                <input class="form-control" id="customer_password" name="customer_password" type="password" placeholder="Password" required>
                                <span class="input-group-btn">
                                    <label class="btn btn-primary"><span class="glyphicon glyphicon-lock"></span></label>
                                </span>
                            </div>
                        </div>

                        <button class="btn btn-primary" name="submit" type="submit">Submit</button>
                        <p class="help-block" style="margin-top:10px;">or <br> Create a new account.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="site-footer">
        <div class="container">
            <hr>
            <div class="row">
                <div class="col-sm-6">
                    <h5>Any problem?</h5>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>