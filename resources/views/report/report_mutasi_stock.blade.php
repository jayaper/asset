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
    <link rel="icon" href="{{ asset('assets/images/favicon/favicon.png') }}" type="image/x-icon">
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
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
    <!-- Add these in your <head> section -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .btn-link {
            color: #007bff;
            text-decoration: none;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .disabled {
            color: #6c757d;
            /* Grey color for disabled links */
            cursor: not-allowed;
            /* Change cursor for disabled links */
        }

        .mt-4 {
            margin-top: 1.5rem;
            /* Margin adjustment for spacing */
        }

        .mt-2 {
            margin-top: 0.5rem;
            /* Margin adjustment for spacing */
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination-info {
            text-align: center;
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
                <div class="nav-right col-8 pull-right right-header p-0">
                    <ul class="nav-menus">
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
                                <h3>Report Mutasi Stock</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a>
                                    </li>
                                    <li class="breadcrumb-item">ASMI</li>
                                    <li class="breadcrumb-item active">Report Mutasi Stock</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-sm-6">
                            </div>
                            <div class="container">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="/reports/export_excel_mutasi_stock" method="GET">
                                            <div class="col-md-3">
                                                <input type="hidden" id="start_date" name="start_date"
                                                    class="form-control" value="{{ request('start_date') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="hidden" id="end_date" name="end_date"
                                                    class="form-control" value="{{ request('end_date') }}">
                                            </div>
                                            <div class="col-md-6 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Download Excel Data
                                                </button>
                                            </div>
                                        </form>
                                        <form class="my-4" action="/reports/mutasi_stock_asset" method="GET">
                                        @csrf
                                            <div class="row" style="align-items: flex-end!important;">
                                                <div class="col-md-3">
                                                    <label for="start_date">Start Date</label>
                                                    <input type="date" id="start_date" name="start_date"
                                                        class="form-control" value="{{ request('start_date') }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="end_date">End Date</label>
                                                    <input type="date" id="end_date" name="end_date"
                                                        class="form-control" value="{{ request('end_date') }}">
                                                </div>
                                                <div style="align-items: flex-end!important;" class="col-md-6 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                    <a href="/reports/mutasi_stock_asset"
                                                        class="btn btn-secondary ml-2">Reset</a>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="table-responsive product-table">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Asset Tag</th>
                                                        <th>Transaction Number</th>
                                                        <th>Approval 1</th>
                                                        <th>Approval 1 Date</th>
                                                        <th>Approval 2</th>
                                                        <th>Approval 2 Date</th>
                                                        <th>Approval 3</th>
                                                        <th>Approval 3 Date</th>
                                                        <th>Asset Name</th>
                                                        <th>QTY</th>
                                                        <th>Satuan</th>
                                                        <th>From Location</th>
                                                        <th>Destination Location</th>
                                                        <th>Condition</th>
                                                        <th>Serial No.</th>
                                                        {{-- <th>Status</th> --}}
                                                        <th>Reason</th>
                                                        <th>Transfer Date</th>
                                                        <th>Confirmation</th>
                                                        <th>Confirm Date</th>
                                                        <th>Created Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($tDetail as $item)
                                                        <tr>
                                                            <td>{{ $item->asset_tag }}</td>
                                                            <td>{{ $item->out_id }}</td>
                                                            <td>{{ $item->appr_1_user }}</td>
                                                            <td>{{ $item->appr_1_date }}</td>
                                                            <td>{{ $item->appr_2_user }}</td>
                                                            <td>{{ $item->appr_2_date }}</td>
                                                            <td>{{ $item->appr_3_user }}</td>
                                                            <td>{{ $item->appr_3_date }}</td>
                                                            <td>{{ $item->asset_model }}</td>
                                                            <td>{{ $item->qty }}</td>
                                                            <td>{{ $item->uom_name }}</td>
                                                            <td>{{ $item->lokasi_asal }}</td>
                                                            <td>{{ $item->lokasi_akhir }}</td>
                                                            <td>{{ $item->condition_name }}</td>
                                                            <td>{{ $item->serial_number }}</td>
                                                            <td>{{ $item->reason_name }}</td>
                                                            <td>{{ $item->create_date }}</td>
                                                            <td>{{ $item->approval_name }}</td>
                                                            <td>{{ $item->confirm_date }}</td>
                                                            <td>{{ $item->out_date }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Correct order: jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Popper.js next (use the CDN) -->
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

    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/data-registrasi-asset.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</html>
