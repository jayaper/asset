<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Enzo admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Enzo admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href=" {{ asset('assets/images/favicon/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon/favicon.png') }}" type="image/x-icon">
    <title>ASMI - Asset System Management Integration</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/font-awesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/feather-icon.css') }}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    /* Hide default checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* Slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }

    /* Before Toggle */
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    /* Checked state */
    input:checked+.slider {
        background-color: #4caf50;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }
</style>

<body>
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="loader"></div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        <div class="page-header">
            <div class="header-wrapper row m-0">
                <form class="form-inline search-full col" action="#" method="get">
                    <div class="form-group w-100">
                        <div class="Typeahead Typeahead--twitterUsers">
                            <div class="u-posRelative">
                                <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text"
                                    placeholder="Search In Enzo .." name="q" title="" autofocus>
                                <div class="spinner-border Typeahead-spinner" role="status"><span
                                        class="sr-only">Loading...</span></div><i class="close-search"
                                    data-feather="x"></i>
                            </div>
                            <div class="Typeahead-menu"></div>
                        </div>
                    </div>
                </form>
                <div class="header-logo-wrapper col-auto p-0">
                    <div class="logo-wrapper">
                        <a href="index.html">
                            <img class="img-fluid" src="{{ asset('assets/images/logo/login.png') }}" alt="">
                        </a>
                    </div>
                    <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle"
                            data-feather="align-center"></i></div>
                </div>
                <!-- <div class="left-header col horizontal-wrapper ps-0">
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text mobile-search"><i class="fa fa-search"></i></span></div>
              <input class="form-control" type="text" placeholder="Search Here........">
            </div>
          </div> -->
                <div class="nav-right col-8 pull-right right-header p-0">
                    <ul class="nav-menus">
                        <!-- <li class="onhover-dropdown">
                <div class="notification-box"><i class="fa fa-bell-o"> </i><span class="badge rounded-pill badge-primary">4</span></div>
                <ul class="notification-dropdown onhover-show-div">
                  <li><i data-feather="bell">            </i>
                    <h6 class="f-18 mb-0">Notifications</h6>
                  </li>
                  <li><a href="email_read.html">
                      <p><i class="fa fa-circle-o me-3 font-primary"> </i>Delivery processing <span class="pull-right">10 min.</span></p></a></li>
                  <li><a href="email_read.html">
                      <p><i class="fa fa-circle-o me-3 font-success"></i>Order Complete<span class="pull-right">1 hr</span></p></a></li>
                  <li><a href="email_read.html">
                      <p><i class="fa fa-circle-o me-3 font-info"></i>Tickets Generated<span class="pull-right">3 hr</span></p></a></li>
                  <li><a href="email_read.html">
                      <p><i class="fa fa-circle-o me-3 font-danger"></i>Delivery Complete<span class="pull-right">6 hr</span></p></a></li>
                  <li><a class="btn btn-primary" href="email_read.html">Check all notification</a></li>
                </ul>
              </li> -->
                        <!-- <li class="onhover-dropdown"><i class="fa fa-comment-o"></i>
                <ul class="chat-dropdown onhover-show-div">
                  <li><i data-feather="message-square"></i>
                    <h6 class="f-18 mb-0">Message Box</h6>
                  </li>
                  <li>
                    <div class="d-flex"><img class="img-fluid rounded-circle me-3" src="{{ asset('assets/images/user/1.jpg') }}">
                      <div class="status-circle online"></div>
                      <div class="flex-grow-1"><a href="chat.html"> <span>Ain Chavez</span>
                          <p>Do you want to go see movie?</p></a></div>
                      <p class="f-12 font-success">1 mins ago</p>
                    </div>
                  </li>
                  <li>
                    <div class="d-flex"><img class="img-fluid rounded-circle me-3" src="{{ asset('assets/images/user/2.png') }}">
                      <div class="status-circle online"></div>
                      <div class="flex-grow-1"><a href="chat.html"> <span>Kori Thomas</span>
                          <p>What`s the project report update?</p></a></div>
                      <p class="f-12 font-success">3 mins ago</p>
                    </div>
                  </li>
                  <li>
                    <div class="d-flex"><img class="img-fluid rounded-circle me-3" src="{{ asset('assets/images/dashboard/admins.png') }}">
                      <div class="status-circle offline"></div>
                      <div class="flex-grow-1"><a href="chat.html"> <span>Ain Chavez</span>
                          <p>Thank you for rating us.</p></a></div>
                      <p class="f-12 font-danger">9 mins ago</p>
                    </div>
                  </li>
                  <li class="text-center"> <a class="btn btn-primary" href="chat.html">View All</a></li>
                </ul>
              </li> -->
                        <li>
                            <div class="mode"><i class="fa fa-moon-o"></i></div>
                        </li>
                        <li class="maximize"><a class="text-dark" href="#!"
                                onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
                        <li class="profile-nav onhover-dropdown p-0 me-0">
                            <div class="d-flex profile-media"><img class="b-r-50"
                                    src="{{ asset('assets/images/dashboard/profile.jpg') }}">
                                <?php $session = session(); ?>
                                <div class="flex-grow-1"><span>{{ Auth::user()->username }}</span>
                                    <p class="mb-0 font-roboto">{{ Auth::user()->role }}<i
                                            class="middle fa fa-angle-down"></i></p>
                                </div>

                            </div>
                            <ul class="profile-dropdown onhover-show-div">
                                <li><a href="user-profile.html"><i data-feather="user"></i><span>Account </span></a>
                                </li>
                                <!-- <li><a href="email_inbox.html"><i data-feather="mail"></i><span>Inbox</span></a></li>
                  <li><a href="kanban.html"><i data-feather="file-text"></i><span>Taskboard</span></a></li> -->
                                <li><a href="/logout"><i data-feather="log-out"> </i><span>Log Out</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <script class="result-template" type="text/x-handlebars-template">
            <div class="ProfileCard u-cf">                        
            <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
            <div class="ProfileCard-details">
            <div class="ProfileCard-realName"></div>
            </div>
            </div>
          </script>
                <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
            </div>
        </div>
        <!-- Page Header Ends                              -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            <!-- Page Sidebar Start-->
            @include('layouts.sidebar')
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <!-- Container-fluid starts-->

                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>User Name List</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a>
                                    </li>
                                    <li class="breadcrumb-item">ASMI</li>
                                    <li class="breadcrumb-item active">User Name List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Container-fluid starts-->
                <div class="container-fluid list-products">
                    <div class="row">
                        <!-- Individual column searching (text inputs) Starts-->
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>User Name List</h5>
                                    <span>adalah daftar atau kumpulan aset yang dimiliki oleh seseorang, organisasi,
                                        atau perusahaan. Daftar ini biasanya mencakup rincian tentang setiap aset,
                                        seperti jenis aset, nilai, lokasi, dan informasi relevan lainnya.</span>
                                </div>
                                <div class="card-body">
                                    <div class="btn-showcase">
                                        <div class="button_between">
                                            <button class="btn btn-square btn-primary" type="button"
                                                data-toggle="modal" data-target="#addDataUser">+ Add Data
                                                User</button>
                                            {{-- <button class="btn btn-square btn-primary" type="button" data-toggle="modal" data-target="#importDataExcel"> <i class="fa fa-file-excel-o" ></i> Import Data Excel </button>
                                <button class="btn btn-square btn-primary" type="button"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                Download PDF Data</button> --}}
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal add -->
                                <!-- Modal Add Data Asset -->
                                <div class="modal fade" id="addDataUser" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add Data User</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/user/add-user" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-sm-12 pb-2">
                                                            <label for="username">Username:</label>
                                                            <input type="text" name="username" id="username"
                                                                class="form-control" placeholder="Enter Username"
                                                                required>
                                                        </div>
                                                        <div class="col-sm-12 pb-2">
                                                            <label for="password">Password:</label>
                                                            <div class="input-group">
                                                                <input type="password" name="password" id="password"
                                                                    class="form-control" placeholder="Enter Password"
                                                                    required>
                                                                <div class="input-group-append">
                                                                    <button type="button"
                                                                        class="btn btn-outline-secondary"
                                                                        id="togglePassword">
                                                                        <i class="fas fa-eye"></i>
                                                                        <!-- Icon for visibility toggle -->
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 pb-2">
                                                            <label for="email">Email:</label>
                                                            <input type="email" name="email" id="email"
                                                                class="form-control" placeholder="Enter email"
                                                                required>
                                                        </div>
                                                        
                                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                                            <label for="">Role:</label>
                                                            <div class="row">
                                                                <br />
                                                                @foreach ($rolesUser as $role)
                                                                    <div class="col-4 p-3">
                                                                        <div class="d-flex align-content-center">
                                                                            <label class="switch">
                                                                                <input type="radio" name="role"
                                                                                    value="{{ $role->id }}"
                                                                                    required>
                                                                                <span class="slider"></span>
                                                                            </label>
                                                                            <span
                                                                                class="ps-1">{{ $role->name }}</span>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div id="fetch_location" class="pb-4"></div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save
                                                            changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Update Modal -->
                                <div id="updateModal" class="modal fade" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update User</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="updateForm">
                                                @csrf
                                                @method('PUT') <!-- Method override untuk PUT -->
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-sm-12 pb-2">
                                                            <label for="username">Username:</label>
                                                            <input type="text" name="username" id="edit-username"
                                                                class="form-control" required>
                                                        </div>
                                                        <div class="col-sm-12 pb-2">
                                                            <label for="password">Password:</label>
                                                            <div class="input-group">
                                                                <input type="password" name="password"
                                                                    id="edit-password" class="form-control">
                                                                <div class="input-group-append">
                                                                    <button type="button"
                                                                        class="btn btn-outline-secondary"
                                                                        id="togglePassword1">
                                                                        <i class="fas fa-eye"></i>
                                                                        <!-- Icon for visibility toggle -->
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 pb-2">
                                                            <label for="email">Email:</label>
                                                            <input type="email" name="email" id="edit-email"
                                                                class="form-control" required>
                                                        </div>

                                                        <div class="col-sm-12 pb-2">
                                                            <div class="row p-2">
                                                                <label for="role">Role:</label>
                                                                @foreach ($rolesUser as $role)
                                                                    <div class="col-4 p-3">
                                                                        <div class="d-flex align-content-center">
                                                                            <label class="switch">
                                                                                <input type="radio" name="roleedit"
                                                                                    class="edit_role"
                                                                                    value="{{ $role->id }}">
                                                                                <span class="slider"></span>
                                                                            </label>
                                                                            <span
                                                                                class="ps-1">{{ $role->name }}</span>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12">
                                                            <div id="edit-additional-form-container"></div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                          <div id="edit-location_now"></div>
                                                        </div>
                                                        <input type="hidden" name="id" id="id">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save
                                                        changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="userDetailModal" tabindex="-1" role="dialog"
                                    aria-labelledby="brandModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="brandModalLabel">Detail User</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>ID:</strong> <span id="det-user-id"></span></p>
                                                <p><strong>Username:</strong> <span id="det-user-username"></span></p>
                                                <p><strong>Email:</strong> <span id="det-user-email"></span></p>
                                                <p><strong>Role:</strong> <span id="det-user-role"></span></p>
                                                <p><strong>Location Now:</strong> <span id="det-user-location"></span>
                                                </p>
                                                <!-- You can add more brand details here -->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="importDataExcel" tabindex="-1" role="dialog"
                                    aria-labelledby="importDataExcelLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="importDataExcelLabel">Import Data Excel
                                                    Asset</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <label for="import-data">Import Data Excel : </label>
                                                    <input type="file" name="data_excel" id="data_excel"
                                                        class="form-control" placeholder="Upload File Excel">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <button type="button" class="btn btn-danger"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @elseif (session('failed'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('failed') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <div class="table-responsive product-table"
                                        style="max-width: 100%; overflow-x: auto;">
                                        <div class="d-flex justify-content-between mb-3 mt-3">
                                            <h5>User Data</h5> <!-- Add a heading for the table if needed -->
                                            <!-- Search Input Field aligned to the right -->
                                            <div class="input-group" style="width: 250px;">
                                                <input type="text" id="searchInput" class="form-control"
                                                    placeholder="Search for assets..." />
                                            </div>
                                        </div>
                                        <table class="table table-striped display" id="coba"
                                            style="width: 100%;">
                                            <thead>
                                                <tr class="text-center">
                                                    <th class="text-start">#</th>
                                                    <th class="text-start">Username</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                    <tr class="text-center">
                                                        <td class="text-start">{{ $user->id }}</td>
                                                        <td class="text-start">{{ $user->username }}</td>
                                                        <td>{{ $user->role_name }}</td>
                                                        <td><form class="delete-form"
                                                                action="{{ url('user/delete', $user->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="delete-button"
                                                                    title="Delete"
                                                                    style="border: none; background: none; cursor: pointer;">
                                                                    @if (is_null($user->deleted_at))
                                                                        <b class="text-success">Active</b>
                                                                    @else
                                                                        <b class="text-danger">Deactive</b>
                                                                    @endif
                                                                </button>
                                                            </form>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0);" class="edit-button"
                                                                data-id="{{ $user->id }}"
                                                                data-username="{{ $user->username }}"
                                                                data-password="{{ $user->password }}"
                                                                data-email="{{ $user->email }}"
                                                                data-location_now="{{ $user->location_now }}"
                                                                data-role="{{ $user->role_id }}" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="javascript:void(0);" class="detail-button"
                                                                data-id="{{ $user->id }}"
                                                                data-username="{{ $user->username }}"
                                                                data-email="{{ $user->email }}"
                                                                data-location_now="{{ $user->location_now }}"
                                                                data-role_id="{{ $user->role_id }}"
                                                                data-role_name="{{ $user->role_name }}"
                                                                title="Detail">
                                                                <i class="fas fa-book"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center align-items-center mt-4">
                                            <div>
                                                <!-- Previous Button -->
                                                @if ($users->onFirstPage())
                                                    <span class="disabled">
                                                        << Previous</span>
                                                        @else
                                                            <a href="{{ $users->previousPageUrl() }}"
                                                                class="btn btn-link">
                                                                << Previous</a>
                                                @endif
                                            </div>
                                            <div>
                                                <!-- Next Button -->
                                                @if ($users->hasMorePages())
                                                    <a href="{{ $users->nextPageUrl() }}" class="btn btn-link">Next
                                                        >></a>
                                                @else
                                                    <span class="disabled">Next >></span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Display current page and total pages -->
                                        <div class="d-flex justify-content-center mt-2">
                                            <span>Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Individual column searching (text inputs) Ends-->
                    </div>
                </div>
                <!-- Container-fluid Ends-->

            </div>
            <!-- footer start-->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 p-0 footer-left">
                            <!-- <p class="mb-0">Copyright © 2023 Enzo. All rights reserved.</p> -->
                        </div>
                        <div class="col-md-6 p-0 footer-right">
                            <ul class="color-varient">
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                            <p class="mb-0 ms-3">Hand-crafted & made with <i class="fa fa-heart font-danger"></i></p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <!-- scrollbar js-->
    <script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
    <script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
    <script src="{{ asset('assets/js/chart/knob/knob.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart/knob/knob-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/prism/prism.min.js') }}"></script>
    <script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom-card/custom-card.js') }}"></script>
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
    <script src="{{ asset('assets/js/slick-slider/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick-slider/slick-theme.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/data-asset.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
        integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous">
    </script>

    {{-- Get Data user --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mengambil data user menggunakan Ajax
            $.ajax({
                url: "{{ route('get.user') }}", // Route untuk get_user
                method: "GET",
                success: function(data) {
                    let rows = '';
                    data.forEach(function(user) {
                        rows += `
                            <tr>
                                <td>${user.id}</td> <!-- Tampilkan ID user -->
                                <td>${user.username}</td> <!-- Tampilkan Nama user -->
                                <td>${user.password}</td> <!-- Tampilkan Nama user -->
                                 <td>${user.location_now}</td> <!-- Tampilkan Nama user -->
                                 <td>${user.role}</td> <!-- Tampilkan Nama user -->
                                 
                                <td>
                                <a href="javascript:void(0);" class="edit-button" data-id="${user.id}" data-name="${user.username}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form class="delete-form" action="{{ url('admin/users/delete') }}/${user.id}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-button" title="Delete" style="border: none; background: none; cursor: pointer;">
                                        <i class="fas fa-trash-alt" style="color: red;"></i>
                                    </button>
                                </form>
                            </td>
                            </tr>
                        `;
                    });
                    $('#userTableBody').html(rows); // Memasukkan baris ke dalam tbody
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching data:', textStatus, errorThrown);
                }
            });
        });
    </script>

    {{-- Add Data User --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Update Data User --}}
    <script>
      function selectAdmin1(){

        $('#edit-location_now').html('<input type="text" name="location_now" value="">');
        $('#edit-location_now').hide();

      }

      function selectAm1(){
      $.ajax({
        url: '/user/user-get-area',
        type: 'GET',
        success: function (response) {

          let options = '';
          response.forEach(function(data) {
              options += `<option value="${data.id}">${data.city}</option>`;
          });

          const hasil = `
              <div class="col-sm-12 p-0">
                  <label for="edit-location_now">Location:</label>
                  <select name="location_now" id="edit-location_now" class="form-control">
                      ${options}
                  </select>
              </div>
          `;

          $('#edit-location_now').html(hasil).show();
          
        },
        error: function () {
          $('#edit-location_now').html('<div class="col-sm-12"><span>Data tidak ada.</span></div>');
        }
      });
      }

      function selectRm1(){
      $.ajax({
        url: '/user/user-get-region',
        type: 'GET',
        success: function (response) {

          let options = '';
          response.forEach(function(data) {
              options += `<option value="${data.id}">${data.regional}</option>`;
          });

          const hasil = `
              <div class="col-sm-12 p-0">
                  <label for="edit-location_now">Location:</label>
                  <select name="location_now" id="edit-location_now" class="form-control">
                      ${options}
                  </select>
              </div>
          `;

          $('#edit-location_now').html(hasil).show();
          
        },
        error: function () {
          $('#edit-location_now').html('<div class="col-sm-12"><span>Data tidak ada.</span></div>');
        }
      });
      }

      function selectSm1(){
      $.ajax({
        url: '/user/user-get-location',
        type: 'GET',
        success: function (response) {

          let options = '';
          response.forEach(function(data) {
              options += `<option value="${data.id}">${data.name_store_street}</option>`;
          });

          const hasil = `
              <div class="col-sm-12 p-0">
                  <label for="edit-location_now">Location:</label>
                  <select name="location_now" id="edit-location_now" class="form-control">
                      ${options}
                  </select>
              </div>
          `;

          $('#edit-location_now').html(hasil).show();
          
        },
        error: function () {
          $('#edit-location_now').html('<div class="col-sm-12"><span>Data tidak ada.</span></div>');
        }
      });
      }

      function selectSdg1(){

        $('#edit-location_now').html('<input type="text" name="location_now" value="">');
        $('#edit-location_now').hide();

      }
    </script>
    <script>
      function selectAdmin(){

        $('#fetch_location').html('<input type="text" name="location_now" value="">');
        $('#fetch_location').hide();

      }

      function selectAm(){
      $.ajax({
        url: '/user/user-get-area',
        type: 'GET',
        success: function (response) {

          let options = '';
          response.forEach(function(data) {
              options += `<option value="${data.id}">${data.city}</option>`;
          });

          const hasil = `
              <div class="col-sm-12 p-0">
                  <label for="location_now">Location:</label>
                  <select name="location_now" id="location_now" class="form-control">
                      ${options}
                  </select>
              </div>
          `;

          $('#fetch_location').html(hasil).show();
          
        },
        error: function () {
          $('#fetch_location').html('<div class="col-sm-12"><span>Data tidak ada.</span></div>');
        }
      });
      }

      function selectRm(){
      $.ajax({
        url: '/user/user-get-region',
        type: 'GET',
        success: function (response) {

          let options = '';
          response.forEach(function(data) {
              options += `<option value="${data.id}">${data.regional}</option>`;
          });

          const hasil = `
              <div class="col-sm-12 p-0">
                  <label for="location_now">Location:</label>
                  <select name="location_now" id="location_now" class="form-control">
                      ${options}
                  </select>
              </div>
          `;

          $('#fetch_location').html(hasil).show();
          
        },
        error: function () {
          $('#fetch_location').html('<div class="col-sm-12"><span>Data tidak ada.</span></div>');
        }
      });
      }

      function selectSm(){
      $.ajax({
        url: '/user/user-get-location',
        type: 'GET',
        success: function (response) {

          let options = '';
          response.forEach(function(data) {
              options += `<option value="${data.id}">${data.name_store_street}</option>`;
          });

          const hasil = `
              <div class="col-sm-12 p-0">
                  <label for="location_now">Location:</label>
                  <select name="location_now" id="location_now" class="form-control">
                      ${options}
                  </select>
              </div>
          `;

          $('#fetch_location').html(hasil).show();
          
        },
        error: function () {
          $('#fetch_location').html('<div class="col-sm-12"><span>Data tidak ada.</span></div>');
        }
      });
      }

      function selectSdg(){

        $('#fetch_location').html('<input type="text" name="location_now" value="">');
        $('#fetch_location').hide();

      }
    </script>

    <script>
      function selectEditAdmin(locationNow){

        $('#edit-location_now').html('<input type="text" name="location_now" value="">');
        $('#edit-location_now').hide();

      }

      function selectEditAm(locationNow){
      $.ajax({
        url: '/user/user-get-area',
        type: 'GET',
        success: function (response) {

          let options = '';
          response.forEach(function(data) {
            const selected = (data.id == locationNow) ? 'selected' : '';
            options += `<option value="${data.id}" ${selected}>${data.city}</option>`;
          });

          const hasil = `
              <div class="col-sm-12 p-0">
                  <label for="location_now">Location:</label>
                  <select name="location_now" id="location_now" class="form-control">
                      ${options}
                  </select>
              </div>
          `;

          $('#edit-location_now').html(hasil).show();
          
        },
        error: function () {
          $('#edit-location_now').html('<div class="col-sm-12"><span>Data tidak ada.</span></div>');
        }
      });
      }

      function selectEditRm(locationNow){
      $.ajax({
        url: '/user/user-get-region',
        type: 'GET',
        success: function (response) {

          let options = '';
          response.forEach(function(data) {
            const selected = (data.id == locationNow) ? 'selected' : '';
            options += `<option value="${data.id}" ${selected}>${data.regional}</option>`;
          });

          const hasil = `
              <div class="col-sm-12 p-0">
                  <label for="location_now">Location:</label>
                  <select name="location_now" id="location_now" class="form-control">
                      ${options}
                  </select>
              </div>
          `;

          $('#edit-location_now').html(hasil).show();
          
        },
        error: function () {
          $('#edit-location_now').html('<div class="col-sm-12"><span>Data tidak ada.</span></div>');
        }
      });
      }

      function selectEditSm(locationNow){
      $.ajax({
        url: '/user/user-get-location',
        type: 'GET',
        success: function (response) {

          let options = '';
          response.forEach(function(data) {
              const selected = (data.id == locationNow) ? 'selected' : '';
              options += `<option value="${data.id}" ${selected}>${data.name_store_street}</option>`;
          });

          const hasil = `
              <div class="col-sm-12 p-0">
                  <label for="location_now">Location:</label>
                  <select name="location_now" id="location_now" class="form-control">
                      ${options}
                  </select>
              </div>
          `;

          $('#edit-location_now').html(hasil).show();
          
        },
        error: function () {
          $('#edit-location_now').html('<div class="col-sm-12"><span>Data tidak ada.</span></div>');
        }
      });
      }

      function selectEditSdg(locationNow){

        $('#edit-location_now').html('<input type="text" name="location_now" value="">');
        $('#edit-location_now').hide();

      }
    </script>
    <script>
      function selectDetailAdmin(locationNow){

        $('#det-user-location').text('Tidak Ada.');

      }
      function selectDetailAm(locationNow){
        $.ajax({
            url: '/user/user-get-area',
            type: 'GET',
            success: function(response) {
                let hasil = 'Tidak diketahui'; // default jika tidak ketemu
                response.forEach(function(data) {
                    if (data.id == locationNow) {
                        hasil = data.city;
                    }
                });

                $('#det-user-location').text(hasil);

            }
        });
      }

      function selectDetailRm(locationNow){
        $.ajax({
            url: '/user/user-get-region',
            type: 'GET',
            success: function(response) {
                let hasil = 'Tidak diketahui'; // default jika tidak ketemu
                response.forEach(function(data) {
                    if (data.id == locationNow) {
                        hasil = data.regional;
                    }
                });

                $('#det-user-location').text(hasil);

            }
        });
      }

      function selectDetailSm(locationNow){
        $.ajax({
            url: '/user/user-get-location',
            type: 'GET',
            success: function(response) {
                let hasil = 'Tidak diketahui'; // default jika tidak ketemu
                response.forEach(function(data) {
                    if (data.id == locationNow) {
                        hasil = data.name_store_street;
                    }
                });

                $('#det-user-location').text(hasil);

            }
        });
      }

      function selectDetailSdg(locationNow){

        $('#det-user-location').text('Tidak Ada.');

      }
    </script>

    <script>
      $('input[name="role"]').on('change', function() {
          const selectedRoleId = $(this).val(); // Ambil ID role yang dipilih
          switch (selectedRoleId){
            case '1' : //admin
              selectAdmin();
              break;
            case '2' : //am
              selectAm();
              break;
            case '3' : //rm
              selectRm();
              break;
            case '4' : //sm
              selectSm();
              break;
            case '5' : //sdg
              selectSdg();
              break;
          }
      });
      $('input[name="roleedit"]').on('change', function() {
          const selectedRoleId = $(this).val(); // Ambil ID role yang dipilih
          switch (selectedRoleId){
            case '1' : //admin
              selectAdmin1();
              break;
            case '2' : //am
              selectAm1();
              break;
            case '3' : //rm
              selectRm1();
              break;
            case '4' : //sm
              selectSm1();
              break;
            case '5' : //sdg
              selectSdg1();
              break;
          }
      });
      $(document).on('click', '.edit-button', function() {
          const userId = $(this).data('id');
          const username = $(this).data('username');
          const email = $(this).data('email');
          const password = $(this).data('password');
          const roleId = $(this).data('role'); // Role ID dari user
          const locationNow = $(this).data('location_now');



          switch (roleId){
            case 1 : //admin
              selectEditAdmin(locationNow);
              break;
            case 2 : //am
              selectEditAm(locationNow);
              break;
            case 3 : //rm
              selectEditRm(locationNow);
              break;
            case 4 : //sm
              selectEditSm(locationNow);
              break;
            case 5 : //sdg
              selectEditSdg(locationNow);
              break;
          }

          // Isi field form
          $('#id').val(userId);
          $('#edit-username').val(username);
          $('#edit-email').val(email);
          // $('#edit-location_now').val(locationNow).change();



          $('.edit_role').each(function() {
              if ($(this).val() == roleId) {
                  $(this).prop('checked', true);
              } else {
                  $(this).prop('checked', false);
              }
          });

          // Tampilkan modal edit
          $('#updateModal').modal('show');
      });




      $('#updateForm').on('submit', function(e) {
          e.preventDefault();

          $.ajax({
              url: '/user/update-user/' + $('#id').val(),
              method: 'PUT',
              data: $(this).serialize(),
              success: function(response) {
                  if (response.status === 'success') {
                      alert(response.message);
                      window.location.href = response.redirect_url;
                  } else {
                      alert('Failed to update user.');
                  }
              },
              error: function(jqXHR) {
                  const message = jqXHR.responseJSON?.message || 'Failed to update user.';
                  alert(message);
              }
          });
      });
    </script>

    {{-- Detail --}}
    <script>
        $(document).ready(function() {
            // Event listener for detail button
            $('.detail-button').on('click', function() {
                // Get brand data from the clicked button
                var userId = $(this).data('id');
                var userName = $(this).data('username');
                var eMail = $(this).data('email');
                var locationNow = $(this).data('location_now');
                var roleId = $(this).data('role_id');
                var role_name = $(this).data('role_name');

                // Set the data into the modal
                $('#det-user-id').text(userId);
                $('#det-user-username').text(userName);
                $('#det-user-email').text(eMail);
                $('#det-user-role').text(role_name);

                switch (roleId){
                    case 1 : //admin
                    selectDetailAdmin(locationNow);
                    break;
                    case 2 : //am
                    selectDetailAm(locationNow);
                    break;
                    case 3 : //rm
                    selectDetailRm(locationNow);
                    break;
                    case 4 : //sm
                    selectDetailSm(locationNow);
                    break;
                    case 5 : //sdg
                    selectDetailSdg(locationNow);
                    break;
                }

                // Show the modal
                $('#userDetailModal').modal('show');
            });
        });
    </script>

    {{-- Delete data User --}}
    <script>
        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault(); // Prevent default form submission
            const form = $(this).closest('form'); // Get the closest form to the button

            // Display confirmation dialog
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Ingin mengubah Status User?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjut!',
                cancelButtonText: 'Tidak, simpan'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get the action URL from the form
                    const actionUrl = form.attr('action');

                    // Perform AJAX DELETE request
                    $.ajax({
                        url: actionUrl, // Form action URL
                        method: 'DELETE', // HTTP method
                        data: form.serialize(), // Serialize form data
                        success: function(response) {
                            if (response.status === 'success') {
                                window.location.href = response
                                .redirect_url; // Redirect on success
                            } else {
                                Swal.fire('Error!', response.message,
                                'error'); // Show error message
                            }
                        },
                        error: function(jqXHR) {
                            Swal.fire('Gagal!', 'Gagal menghapus data. Coba lagi.',
                            'error'); // Error message
                        }
                    });
                }
            });
        });
    </script>

    <script>
        // JavaScript for searching/filtering the table rows
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toLowerCase();
            table = document.getElementById('coba');
            tr = table.getElementsByTagName('tr');

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 1; i < tr.length; i++) { // Start from 1 to skip table header
                tr[i].style.display = "none"; // Hide the row initially

                // Loop through all columns in the row
                for (j = 0; j < tr[i].getElementsByTagName('td').length; j++) {
                    td = tr[i].getElementsByTagName('td')[j];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = ""; // Show the row if match is found
                            break; // Exit loop once a match is found
                        }
                    }
                }
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#togglePassword').click(function() {
                const passwordField = $('#password');
                const passwordFieldType = passwordField.attr('type');
                const icon = $(this).find('i');

                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text'); // Show the password
                    icon.removeClass('fa-eye').addClass('fa-eye-slash'); // Change icon to eye-slash
                } else {
                    passwordField.attr('type', 'password'); // Hide the password
                    icon.removeClass('fa-eye-slash').addClass('fa-eye'); // Change icon to eye
                }
            });
            $('#togglePassword1').click(function() {
                const passwordField = $('#edit-password');
                const passwordFieldType = passwordField.attr('type');
                const icon = $(this).find('i');

                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text'); // Show the password
                    icon.removeClass('fa-eye').addClass('fa-eye-slash'); // Change icon to eye-slash
                } else {
                    passwordField.attr('type', 'password'); // Hide the password
                    icon.removeClass('fa-eye-slash').addClass('fa-eye'); // Change icon to eye
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // This will handle all modals that have a button with the data-dismiss attribute
            $('[data-dismiss="modal"]').on('click', function() {
                $('.modal').modal('hide'); // Hide any open modal
            });
        });
    </script>


    <script>
        $('#role').change(function() {
            const selectedRole = $(this).val();
            $('#additional-form-container').empty();

            if (selectedRole === 'am') {
                // Append the "Location" form for "am" role
                const locationForm = `
                <label for="location_now">Location Now:</label>
                <select name="location_now" id="location_now" class="form-control">
                    @foreach ($restos as $location)
                    <option value="{{ $location->id }}">{{ $location->name_store_street }}</option>
                    @endforeach
                </select>
            `;
                $('#additional-form-container').append(locationForm);
            } else if (selectedRole === 'rm') {
                // Append the "Location" form for "am" role
                const locationForm = `
                <label for="location_now">Location Now:</label>
                <select name="location_now" id="location_now" class="form-control">
                    @foreach ($restos as $location)
                    <option value="{{ $location->id }}">{{ $location->name_store_street }}</option>
                    @endforeach
                </select>
            `;
                $('#additional-form-container').append(locationForm);
            } else if (selectedRole === 'sm') {
                // Append the "Location" form for "am" role
                const locationForm = `
                <label for="location_now">Location Now:</label>
                <select name="location_now" id="location_now" class="form-control">
                    @foreach ($restos as $location)
                    <option value="{{ $location->id }}">{{ $location->name_store_street }}</option>
                    @endforeach
                </select>
            `;
                $('#additional-form-container').append(locationForm);
            } else if (selectedRole === 'sdg') {
                // Append the "Location" form for "am" role
                const locationForm = `
                <label for="location_now">Location Now:</label>
                <select name="location_now" id="location_now" class="form-control">
                    @foreach ($restos as $location)
                    <option value="{{ $location->id }}">{{ $location->name_store_street }}</option>
                    @endforeach
                </select>
            `;
                $('#additional-form-container').append(locationForm);
            }
        });

        $('#edit-role').change(function() {
            const selectedRole = $(this).val();
            $('#edit-additional-form-container').empty();

            if (selectedRole === 'am') {
                // Append the "Location" form for "am" role
                const locationForm = `
                <label for="location_now">Location Now:</label>
                <select name="location_now" id="location_now" class="form-control">
                    @foreach ($restos as $location)
                    <option value="{{ $location->id }}">{{ $location->name_store_street }}</option>
                    @endforeach
                </select>
            `;
                $('#edit-additional-form-container').append(locationForm);
            } else if (selectedRole === 'rm') {
                // Append the "Location" form for "rm" role
                const locationForm = `
                <label for="location_now">Location Now:</label>
                <select name="location_now" id="location_now" class="form-control">
                    @foreach ($restos as $location)
                    <option value="{{ $location->id }}">{{ $location->name_store_street }}</option>
                    @endforeach
                </select>
            `;
                $('#edit-additional-form-container').append(locationForm);
            } else if (selectedRole === 'user') {
                // Append the "Location" form for "user" role
                const locationForm = `
                <label for="location_now">Location Now:</label>
                <select name="location_now" id="location_now" class="form-control">
                    @foreach ($restos as $location)
                    <option value="{{ $location->id }}">{{ $location->name_store_street }}</option>
                    @endforeach
                </select>
            `;
                $('#edit-additional-form-container').append(locationForm);
            } else if (selectedRole === 'sm') {
                const locationForm = `
                <label for="location_now">Location Now:</label>
                <select name="location_now" id="location_now" class="form-control">
                    @foreach ($restos as $location)
                    <option value="{{ $location->id }}">{{ $location->name_store_street }}</option>
                    @endforeach
                </select>
            `;
                $('#edit-additional-form-container').append(locationForm);
            } else if (selectedRole === 'sm') {
                // Append the "Location" form for "am" role
                const locationForm = `
                <label for="location_now">Location Now:</label>
                <select name="location_now" id="location_now" class="form-control">
                    @foreach ($restos as $location)
                    <option value="{{ $location->id }}">{{ $location->name_store_street }}</option>
                    @endforeach
                </select>
            `;
                $('#additional-form-container').append(locationForm);
            }
        });
    </script>

    <!-- login js-->
    <!-- Plugin used-->
</body>

</html>
