<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="../css/student.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="icon" href="../uploads/logo.jpg">

    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: "Montserrat", sans-serif;
            font-size:14px;
            font-weight: 300;
            font-style: normal;
        }
        .sidebar {
            height: 100vh;
            background-color: #00000A;
            padding-top: 20px; 
            transition: width 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
        }
        .sidebar.collapsed {
            width: 0;
            overflow: hidden;
        }
        .sidebar img {
            max-width: 100%; 
            height: auto;
            position: relative;
            top: -10px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            text-decoration: none;
            white-space: nowrap;
        }
        .sidebar a:hover {
            background-color: #494059;
            border-radius: 0px 45px 45px 0px;
        }
        .main-content {
            margin-left: 250px; 
            padding: 10px;
            transition: margin-left 0.3s ease, width 0.3s ease;
        }
        .main-content.full-width {
            margin-left: 20px;
            width: 98%;
        }
        @media (max-width: 1000px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                display: none;
            }
            .sidebar.collapsed {
                display: block;
            }
            .main-content {
                margin-left: 10px;
                width: 100%;
            }
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button,
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate,
        .dataTables_wrapper .dataTables_scrollBody table,
        .dataTables_wrapper .dataTables_scrollBody {
            font-size: 12px; 
            line-height: 1;
        }

        .dataTables_wrapper .dataTable th {
            padding: 4px 8px; 
        }
        .dataTables_wrapper .dataTable td {
            padding: 4px 8px; 
        }
        .navbar {
            background-color: #00000A;
            height: 50px;
        }
        .navbar-toggler {
            margin-right: 10px;
        }
        .username {
            color: white;
            margin-left: 250px; 
            display: flex;
            align-items: center;
            transition: margin-left 0.3s ease;
        }
        .collapsed .username {
            margin-left: 18px; 
        }
        .navbar-nav {
            margin-left: auto;
        }
        .sidebar {
            width: 250px;
            transition: width 0.3s ease;
        }
        .sidebar.collapsed {
            width: 0;
            overflow: hidden;
        }
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }
        .main-content.expanded {
            margin-left: 0;
        }
    </style>
</head>
<body>
    
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <span class="username"><img src="../uploads/user.png" style="width:30px; height:30px;border-radius:50%"></img>&nbsp;
                    Username &nbsp;<b style="background:blue; height:20px;font-size:12px; border-radius:5px;padding:2px">Admin</b></span>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#"><i class="fa fa-bell" aria-hidden="true"></i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../auth/logout.php">Logout <i class="fas fa-sign-out-alt"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 sidebar" id="sidebar">
            <button class="btn btn-sm btn-outline-light float-end" style="position:relative;top:-10px;left:10px" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <img src="../uploads/logo.png" alt="Logo" style="height:180px; width:200px; margin:10px;">
            <hr style="color:white;">
            <a href="../layout/home.php"><i class="fas fa-home"></i> Home</a>
            <a href="../pages/students.php"><i class="fas fa-user"></i> Manage Students</a>
            <a href="../pages/courses.php"><i class="fas fa-graduation-cap"></i> Manage Courses</a>
            <a href="../pages/fees.php"><i class="fas fa-receipt"></i> Manage Fees</a>
            <a href="../pages/faculties.php"><i class="fas fa-user"></i> Manage Faculties</a>

        </div>
        <div class="col-lg-10 col-md-10 col-sm-10 main-content" id="mainContent">
      
<script>
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        var sidebar = document.getElementById('sidebar');
        var mainContent = document.getElementById('mainContent');
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('full-width');
        
        // Adjust Bootstrap column classes
        if (mainContent.classList.contains('full-width')) {
            mainContent.classList.remove('col-lg-10', 'col-md-10', 'col-sm-10');
            mainContent.classList.add('col-lg-12', 'col-md-12', 'col-sm-12');
        } else {
            mainContent.classList.remove('col-lg-12', 'col-md-12', 'col-sm-12');
            mainContent.classList.add('col-lg-10', 'col-md-10', 'col-sm-10');
        }
    });
</script>

