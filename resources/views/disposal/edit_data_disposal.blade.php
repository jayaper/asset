<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    <style>
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #f0f7ff;
            padding: 20px;
            border-radius: 10px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-group>div {
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .qr-code {
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ccc;
            width: 150px;
            height: 150px;
            margin-top: 10px;
            background-color: #f0f0f0;
        }

        .button-form {
            text-align: right;
        }

        .asset-fields {
            margin-bottom: 30px;
            /* Adjust value as needed for spacing */
        }

        .scan-container {
            border: 2px solid #007bff;
            /* Blue border */
            border-radius: 8px;
            /* Rounded corners */
            padding: 20px;
            /* Spacing inside the container */
            background-color: #f7fbff;
            /* Light blue background */
            width: 100%;
            /* Full width of the container */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
        }

        .scan-header h3 {
            margin: 0 0 10px 0;
            /* Remove margin at the top and add space below */
            font-family: Arial, sans-serif;
            font-size: 18px;
            color: #333;
            /* Dark text color */
        }

        .scan-input {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            /* Space between input and action buttons */
        }

        .scan-input input {
            flex: 1;
            /* Takes the remaining space */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .scan-input .search-button {
            background-color: #007bff;
            /* Blue button */
            color: white;
            border: none;
            padding: 10px 15px;
            margin-left: 5px;
            /* Space between input and button */
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .scan-input .search-button i {
            font-size: 16px;
        }

        .scan-actions {
            text-align: right;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #555;
            /* Slightly darker text */
        }

        .scan-actions span {
            margin-left: 20px;
            /* Space between each action */
        }


        .btn-showcase {
            position: relative;
            top: 1.5rem;
        }

        #assetFieldsContainer {
            position: relative;
            top: 2.2rem;
        }

        .btn-submit-form {
            position: relative;
            top: 1rem;
        }
    </style>

</head>

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
                <div class="container-fluid">
                    <div class="page-title mt-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>Edit Data Asset Disposal</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a>
                                    </li>
                                    <li class="breadcrumb-item">ASMI</li>
                                    <li class="breadcrumb-item active">Request Disposal Out Name List</li>
                                    <li class="breadcrumb-item active">Edit Data Asset Disposal</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="section">
                        <div class="card pb-5">
                            <div class="card-body">
                                <div class="form-group">
                                    <form id="moveout-form" action="{{ url('disposal/request-disposal/update_data_disposal', $moveOutAssets->out_id) }}"
                                        method="POST">
                                        @csrf
                                        {{-- @method('PUT') --}}
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="out_date">Tanggal Movement Out:</label>
                                                    <input type="date" class="form-control" value="{{ $moveOutAssets->out_date }}" required readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="from_loc">Lokasi Asal:</label>
                                                    <!-- Visible input for name_store_street -->
                                                    <input type="text" id="from_loc" class="form-control" value="{{ $moveOutAssets->name_store_street }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="out_desc">Deskripsi Disposal Out:</label>
                                                    <input type="text" name="out_desc" id="out_desc" class="form-control"
                                                    value="{{ $moveOutAssets->out_desc }}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="reason_id">Alasan Disposal Out:</label>
                                                    <select name="reason_id" id="reason_id" class="form-control" required>
                                                        @foreach ($reasons as $reason)
                                                        <option value="{{ $reason->reason_id }}"
                                                            @if ($reason->reason_id == $moveOutAssets->reason_id) selected @endif>
                                                            {{ $reason->reason_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            @foreach ($assets as $asset)
                                                <div id="assetFieldsContainer">
                                                    <div class="asset-fields">
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="asset_id">Data Asset:</label>
                                                                        <input type="text" class="form-control" value="{{ $asset->asset_model }}" readonly>
                                                                        <input type="hidden" name="det_id[]" value="{{ $asset->id }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="merk">Merk:</label>
                                                                    <input type="text" class="form-control" value="{{ $asset->brand_name }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="qty">Quantity:</label>
                                                                    <input type="text" class="form-control" value="{{ $asset->qty }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="qty">Satuan:</label>
                                                                    <input type="text" class="form-control" value="{{ $asset->uom_name }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="serial_number">Serial Number:</label>
                                                                    <input type="text" class="form-control" value="{{ $asset->serial_number }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="register_code">Register Code:</label>
                                                                    <input type="text" class="form-control" value="{{ $asset->asset_tag }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="condition_id_0">Kondisi Asset:</label>
                                                                    <select name="condition_id[]" class="form-control" required>
                                                                    <option value="">Pilih Kondisi</option>
                                                                    @foreach ($conditions as $condition)
                                                                        @if ($condition->condition_id == $asset->condition)
                                                                            <option value="{{ $condition->condition_id }}" selected>{{ $condition->condition_name }}</option>
                                                                        @else
                                                                            <option value="{{ $condition->condition_id }}">{{ $condition->condition_name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Image:</label>
                                                                    <input type="file" name="image[]"
                                                                    class="form-control image-input" accept="image/*">
                                                                    <div class="selected-file-name"></div>
                                                                </div>
                                                            </div>
                                                            <br><br><br><br>
                                                            <div class="row mb-3">
                                                                <div class="col-sm-3">
                                                                    <label for="image">Image/Gambar Barang Data di dalam
                                                                        database:</label>
                                                                    <a target="_blank" href="/storage/{{ $asset->image }}">
                                                                        <img src="/storage/{{ $asset->image }}" width="300">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" id="submit-btn" class="btn btn-primary ms-2">Submit</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Container-fluid Ends-->

        </div>
        <!-- footer start-->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 p-0 footer-left">
                        <!-- <p class="mb-0">Copyright © 2023 Enzo.  All rights reserved.</p> -->
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

    <!-- Correct order: jQuery first, then Bootstrap JS -->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    =<!-- Popper.js next (use the CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>

    <!-- Bootstrap JS (use the CDN or your local version) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
        integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous">
    </script>



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

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/data-registrasi-asset.js') }}"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>

    <script>
        const formElement = document.getElementById('moveout-form');
        const submitBtn = document.getElementById('submit-btn');

        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Pastikan data yang diisi sudah benar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(formElement);

                    // Kirim via AJAX
                    $.ajax({
                        url: formElement.action,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        success: function(response) {
                            Swal.fire('Sukses!', 'Data berhasil dikirim.', 'success')
                                .then(() => {
                                    window.location.href = '/disposal/request-disposal';
                                });
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal', 'Terjadi kesalahan: ' + xhr.responseText, 'error');
                        }
                    });
                }
            });
        });
    </script>

</body>
</html>
