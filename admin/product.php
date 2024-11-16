<?php

require_once "./../dbconnect.php";
$categories = null;
$admin = null;
$updateproduct = null;
$message = '';

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

if (!isset($_SESSION['admin_email'])) {
    header("Location:login.php");
}

if (isset($_SESSION['admin_email'])) {
    $email = $_SESSION['admin_email'];
    try {
        $conn = connect();
        $sql = "SELECT * FROM admin WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


function getcategory()
{
    try {
        $conn = connect();
        $sql = "SELECT * FROM category";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $category = $stmt->fetchAll();
        return $category;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getproduct()
{
    try {
        $conn = connect();
        $sql = "SELECT * FROM products";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll();
        return $products;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function productinsert($pname, $description, $type, $price, $categoryid, $image)
{
    $date = new DateTimeImmutable();
    $datetime = $date->format('l-jS-F-Y-');
    $rdm = rand();
    $filename = $image['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $uploadpath = "./../assets/images/img/products/" . $datetime . $rdm . "." . $ext;
    $dbfilepath = "assets/images/img/products/" . $datetime . $rdm . "." . $ext;
    if (move_uploaded_file($image['tmp_name'], $uploadpath)) {
        try {
            $conn = connect();
            $sql = "INSERT INTO products (name,description,type,price,url,category_id) VALUES (?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$pname, $description, $type, $price, $dbfilepath, $categoryid]);
            $conn = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['insert'])) {
    $pname = $_POST['name'];
    $description = $_POST['desciption'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $categoryid = $_POST['category'];
    $image = $_FILES['image'];
    productinsert($pname, $description, $type, $price, $categoryid, $image);
}

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['updateid'])) {
    $updateid = $_GET['updateid'];
    try {
        $conn = connect();
        $sql = "SELECT * FROM products where id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$updateid]);
        $updateproduct = $stmt->fetchAll();
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update'])) {
    $pid = $_POST['id'];
    $pname = $_POST['name'];
    $description = $_POST['desciption'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $categoryid = $_POST['category'];
    try {
        $conn = connect();

        // To update in product table
        $sql = "UPDATE products SET name=?, description=?, type=?, price=?, category_id=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$pname, $description, $type, $price, $categoryid,$pid]);
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['deleteid'])) {
    $pid = $_GET['deleteid'];
    try{
        $conn = connect();

        // Delet images from diskspace
        $sql = "SELECT url FROM products WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$pid]);
        $filepaths = $stmt->fetchAll();
        foreach($filepaths as $filepath){
            $path = "./../". $filepath['url'];
            // $path = $filepath['url'];
            unlink($path);
        }

        // Delete review from product review table
        $sql = "DELETE FROM productreview WHERE products_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$pid]);

        // Delete product from product table
        $sql = "DELETE FROM products WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$pid]);
        $conn = null;
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

$categories = getcategory();
$products = getproduct();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Sixty9 | Product Data</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="540x15" href="./../assets/images/img/logo1.png">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.html">
                <div class="sidebar-brand-icon">
                    <img src="./../assets/images/img/logo1.png" alt="logo" width="90">
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Product
            </div>

            <li class="nav-item active">
                <a class="nav-link" href="product.php">
                    <i class="fas fa-fw fa-warehouse"></i>
                    <span>Products</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="category.php">
                    <i class="fas fa-fw fa-window-restore"></i>
                    <span>Category</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="size.php">
                    <i class="fas fa-fw fa-superscript"></i>
                    <span>Size</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="stock.php">
                    <i class="fas fa-fw fa-cubes"></i>
                    <span>Stock</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Users
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="us">
                    <i class="fas fa-fw fa-users"></i>
                    <span>User Information</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg" alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg" alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg" alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small" style="text-transform: capitalize;"><?php echo $admin['name'] ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Products Data Manipulation</h1>

                    <!-- start forms -->
                    <div class="row">
                        <!-- start insert form -->
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Accordion -->
                                <a href="#insertform" class="d-block card-header py-3" data-toggle="collapse"
                                    role="button" aria-expanded="true" aria-controls="insertform">
                                    <h6 class="m-0 font-weight-bold text-primary">Product Insert Form</h6>
                                </a>
                                <!-- Card Content - Collapse -->
                                <div class="collapse show" id="insertform">
                                    <div class="card-body">
                                        <form action="./product.php" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="insertname">Product Name</label>
                                                <input type="text" name="name" class="form-control" id="insertname" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="insertdescrption">Description</label>
                                                <textarea class="form-control" name="desciption" id="insertdescrption" rows="3" required></textarea>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inerttype">Gender Type</label>
                                                    <select name="type" id="inerttype" class="form-control" required>
                                                        <option disabled selected>Choose types</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="insercat">Category</label>
                                                    <select name="category" id="insercat" class="form-control" required>
                                                        <option disabled selected>Choose product category..</option>
                                                        <?php foreach ($categories as $category) { ?>
                                                            <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="insertprice">Price</label>
                                                    <input type="number" name="price" min="0.5" max="5000" step="0.01" class="form-control" id="insertprice" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="insertphoto">Product Photo</label>
                                                    <input type="file" name="image" class="form-control-file py-1" accept="image/png, image/jpeg, image/jpg" id="insertphoto" required>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <button type="submit" name="insert" class="btn btn-primary btn-md px-5 py-2">Insert</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end insert form -->

                        <!-- start update form -->
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Accordion -->
                                <a href="#updateform" class="d-block card-header py-3" data-toggle="collapse"
                                    role="button" aria-expanded="true" aria-controls="updateform">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Product Update Form
                                        <?php if (isset($_GET['updateid'])) { ?>
                                            (ID - <?php echo $updateproduct[0]['id'] ?> )
                                        <?php } ?>
                                    </h6>
                                </a>
                                <!-- Card Content - Collapse -->
                                <div class="collapse show" id="updateform">
                                    <div class="card-body">
                                        <form action="./product.php" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $updateproduct[0]['id'] ?>" />
                                            <div class="form-group">
                                                <label for="updatename">Product Name</label>
                                                <?php if (isset($_GET['updateid'])) { ?>
                                                    <input type="text" name="name" class="form-control" id="updatename" value="<?php echo $updateproduct[0]['name'] ?>" required>
                                                <?php } else { ?>
                                                    <input type="text" name="name" class="form-control" id="updatename" required>
                                                <?php } ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="updatedescrption">Description</label>
                                                <?php if (isset($_GET['updateid'])) { ?>
                                                    <textarea class="form-control" name="desciption" id="updatedescrption" rows="3" required><?php echo $updateproduct[0]['description'] ?></textarea>
                                                <?php } else { ?>
                                                    <textarea class="form-control" name="desciption" id="updatedescrption" rows="3" required></textarea>
                                                <?php } ?>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label for="updatetype">Gender Type</label>
                                                    <select name="type" id="updatetype" class="form-control" required>
                                                        <?php if (isset($_GET['updateid'])) { ?>
                                                            <?php if ( $updateproduct[0]['type'] == "male" ) {  ?>
                                                                <option value="male" selected>Male</option>
                                                                <option value="female">Female</option>
                                                            <?php } else{ ?>
                                                                <option value="male">Male</option>
                                                                <option value="female" selected>Female</option>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <option disabled selected>Choose types</option>
                                                            <option value="male">Male</option>
                                                            <option value="female">Female</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="updatecat">Category</label>
                                                    <select name="category" id="updatecat" class="form-control" required>
                                                        <?php if (isset($_GET['updateid'])) { ?>
                                                            <?php foreach ($categories as $category) { ?>
                                                                <?php if ($category['id'] == $updateproduct[0]['category_id']) { ?>
                                                                    <option value="<?php echo $category['id'] ?>" selected><?php echo $category['name'] ?></option>
                                                                <?php } else { ?>
                                                                    <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <option disabled selected>Choose product category..</option>
                                                            <?php foreach ($categories as $category) { ?>
                                                                <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="updateprice">Price</label>
                                                    <?php if (isset($_GET['updateid'])) { ?>
                                                        <input type="number" name="price" min="0.5" max="5000" step="0.01" class="form-control" id="updateprice" value="<?php echo $updateproduct[0]['price'] ?>" required>
                                                    <?php } else { ?>
                                                        <input type="number" name="price" min="0.5" max="5000" step="0.01" class="form-control" id="updateprice" required>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-3">
                                                <?php if (isset($_GET['updateid'])) { ?>
                                                    <button type="submit" name="update" class="btn btn-primary btn-md px-5 py-2">Update</button>
                                                <?php } else { ?>
                                                    <button type="submit" name="update" class="btn btn-primary btn-md px-5 py-2" disabled>Update</button>
                                                <?php } ?>
                                                <button type="reset" class="btn btn-danger btn-md px-5 py-2">Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end update form  -->
                    </div>
                    <!-- end forms -->

                    <!-- start data table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">DataTables</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product) {?>
                                            <tr>
                                                <td><?php echo $product['id']?></td>
                                                <td><?php echo $product['name']?></td>
                                                <td>
                                                    <div style="width:250px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                                                        <?php echo $product['description']?>
                                                    </div>
                                                </td>
                                                <td><?php echo $product['type']?></td>
                                                <td>
                                                    <?php foreach ($categories as $category) {
                                                                if ($category['id'] == $product['category_id']) {
                                                                    echo $category['name'];
                                                                }
                                                            }
                                                    ?>
                                                </td>
                                                <td>$<?php echo $product['price']?></td>
                                                <td>
                                                    <a href="product.php?updateid=<?php echo $product['id'] ?>" class="btn btn-warning btn-sm"><span class="icon text-white-50"><i class="fas fa-edit"></i></span></a>
                                                    <a href="product.php?deleteid=<?php echo $product['id'] ?>" class="btn btn-danger btn-sm"><span class="icon text-white-50"><i class="fas fa-trash"></i></span></a>
                                                </td>
                                            </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- end data table -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Sxity9 Sportswear 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>