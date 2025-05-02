<?php
session_start();
require_once 'Connection2.php';
$db = new DBConn();
$conn = $db->getConnection();
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Profile";
include 'head.php';
?>

<body>

    <?php include 'navbar.php'; ?>

    <div class="clearfix"></div>

    <!-- About section -->
    <section id="about" class="about">
        <div class="section-heading text-center">
            <h2>about me</h2>
        </div>
        <div class="container">
            <div class="about-content">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="single-about-txt">
                            <p>
                                Xin chào, tôi là Nguyễn Nhất Huy, nhóm trưởng của nhóm 1 trong môn đồ án đa ngành. Trong dự án này, tôi và nhóm của mình đã phát triển một ứng dụng SmartHome, với mục tiêu giúp người dùng điều khiển và giám sát các thiết bị trong nhà thông qua một nền tảng trực tuyến. Ứng dụng của chúng tôi không chỉ đơn giản hóa việc quản lý các thiết bị thông minh mà còn mang lại trải nghiệm người dùng tiện lợi, an toàn và dễ dàng sử dụng.
                            </p>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="single-about-add-info">
                                        <h3>phone</h3>
                                        <p>0782592***</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="single-about-add-info">
                                        <h3>email</h3>
                                        <p>huy@gmail.com</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="single-about-add-info">
                                        <h3>website</h3>
                                        <p>www.nnhuy.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-offset-1 col-sm-5">
                        <div class="single-about-img">
                            <img src="assets/images/about/profile_image.jpg" alt="profile_image">
                            <div class="about-list-icon">
                                <ul>
                                    <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-dribbble" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                </ul>
                            </div><!-- /.about-list-icon -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>

</html>