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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        <i data-feather="log-out"></i><span>Log Out</span>
                                    </a>
                                </li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </li>
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
                    <div class="page-title mt-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>Assets Registration</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a>
                                    </li>
                                    <li class="breadcrumb-item">ASMI</li>
                                    <li class="breadcrumb-item active">Assets Registration</li>
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
                                    <h5>Assets Registration</h5><span>adalah daftar atau kumpulan aset yang dimiliki
                                        oleh
                                        seseorang, organisasi, atau perusahaan. Daftar ini biasanya mencakup rincian
                                        tentang setiap aset, seperti jenis aset, nilai, lokasi, dan informasi relevan
                                        lainnya.</span>
                                </div>
                                <div class="card-body">
                                    <div class="btn-showcase">
                                        <div class="button_between">
                                            @can('btn add registration')
                                                <a href="/registration/add-assets-registration"
                                                    class="btn btn-square btn-primary">Add Data Asset</a>
                                            @endcan
                                            @can('btn import registration')
                                                <button class="btn btn-square btn-primary" type="button"
                                                    data-toggle="modal" data-target="#importDataExcel"> <i
                                                        class="fa fa-file-excel-o"></i> Import Data Excel </button>
                                            @endcan
                                            @can('btn export registration')
                                                <a href="{{ url('/registration/export-data-assets') }}"
                                                    class="btn btn-square btn-primary" role="button">
                                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Download Excel
                                                    Data
                                                </a>
                                            @endcan

                                        </div>
                                    </div>
                                </div>


                                <div class="modal fade" id="detailDataAsset" tabindex="-1" role="dialog"
                                    aria-labelledby="detailDataAssetLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailDataAssetLabel"><b>Detail Barang
                                                        Asset</b>
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <img id="qrCodeImage" src="" alt="QR Code"
                                                    style="width: 150px; height: 150px;">
                                                <p id="assetDetails"></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary"
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
                                                <form action="{{ url('/registration/import-data-assets') }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="data_excel">Import Data Excel:</label>
                                                        <input type="file" name="file" class="form-control"
                                                            placeholder="Upload File Excel" required
                                                            accept=".xlsx,.xls,.csv">
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <button type="button" class="btn btn-danger"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                            </form>
                                            @if (session('success'))
                                                <div class="p-4">
                                                    <div class="alert alert-success">{{ session('success') }}</div>
                                                </div>
                                            @endif

                                            @if (session('error'))
                                                <div class="p-4">
                                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif


                                <div class="card-body">
                                    <div class="table-responsive product-table"
                                        style="border-radius: 8px; overflow-x: auto; scrollbar-width: thin;">
                                        <table id="coba" class="table table-responsive table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Asset Tag</th>
                                                    <th>Asset Name</th>
                                                    <th>Register Location</th>
                                                    <th>Location Now</th>
                                                    <th>Qty</th>
                                                    <th>Status Asset</th>
                                                    <th>Serial Number</th>
                                                    <th>Type Asset</th>
                                                    <th>Category Asset</th>
                                                    <th>Priority</th>
                                                    <th>Merk</th>
                                                    <th>Satuan</th>
                                                    <th>Layout</th>
                                                    <th>Register Date</th>
                                                    <th>Supplier</th>
                                                    <th>Condition</th>
                                                    <th>Purchase Date</th>
                                                    <th>Warranty</th>
                                                    <th>Periodic Maintenance</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($assets as $asset)
                                                    <tr>
                                                        <td>{{ $asset->register_code }}</td>
                                                        <td>{{ $asset->asset_model }}</td>
                                                        <td>{{ $asset->name_store_street }}</td>
                                                        <td>{{ $asset->location_now }}</td>
                                                        <td>{{ $asset->qty }}</td>
                                                        <td>
                                                            @if ($asset->status_asset_id == 1)
                                                                <b class="text-success">{{ $asset->status_asset_name }}</b>
                                                            @elseif($asset->status_asset_id == 2)
                                                                <b class="text-warning">{{ $asset->status_asset_name }}</b>
                                                            @else
                                                                <b class="text-danger">{{ $asset->status_asset_name }}</b>
                                                            @endif
                                                        </td>
                                                        <td>{{ $asset->serial_number }}</td>
                                                        <td>{{ $asset->type_name }}</td>
                                                        <td>{{ $asset->cat_name }}</td>
                                                        <td>{{ $asset->priority_name }}</td>
                                                        <td>{{ $asset->brand_name }}</td>
                                                        <td>{{ $asset->uom_name }}</td>
                                                        <td>{{ $asset->layout_name }}</td>
                                                        <td>{{ $asset->register_date }}</td>
                                                        <td>{{ $asset->supplier_name }}</td>
                                                        <td>{{ $asset->condition_name }}</td>
                                                        <td>{{ $asset->purchase_date }}</td>
                                                        <td>{{ $asset->warranty_name }}</td>
                                                        <td>{{ $asset->periodic_mtc_name }}</td>
                                                        <td>
                                                            @if (is_null($asset->deleted_at))
                                                                <b class="text-success">Active</b>
                                                            @else
                                                                <b class="text-danger">Deactive</b>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn btn-large btn-primary btn-sm dropdown-toggle"
                                                                    type="button"
                                                                    id="dropdownMenuButton{{ $asset->id }}"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="dropdownMenuButton{{ $asset->id }}">
                                                                    <a class="dropdown-item"
                                                                        href="/registration/generate-pdf/{{ $asset->register_code }}">Cetak QR
                                                                        Code</a>
                                                                    <a class="dropdown-item"
                                                                        href="/registration/tracking-asset-registration/{{ $asset->register_code }}">Tracking
                                                                        History</a>
                                                                    <a class="dropdown-item"
                                                                        href="/registration/detail_data_registrasi_asset/{{ $asset->register_code }}"
                                                                        target="_blank">Detail Barang Asset</a>

                                                                    @can('btn action edit registration')
                                                                        <a class="dropdown-item"
                                                                            href="/registration/edit-assets-registration/{{ $asset->register_code }}">Update</a>
                                                                    @endcan

                                                                    @can('btn action delete registration')
                                                                        @if (is_null($asset->deleted_at))
                                                                            <button class="dropdown-item delete-btn"
                                                                                data-id="{{ $asset->id }}">Delete</button>
                                                                        @else
                                                                            <button class="dropdown-item delete-btn"
                                                                                data-id="{{ $asset->id }}">Activate</button>
                                                                        @endif
                                                                    @endcan
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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

    <!-- Correct order: jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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

    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/data-registrasi-asset.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- <script>
        // Check if DataTable is already initialized and destroy it if necessary
        if ($.fn.DataTable.isDataTable('#coba')) {
            $('#coba').DataTable().destroy();
            $('#coba').empty(); // Clear the table to prevent duplicate data
        }

        // Initialize DataTable
        var table = $('#coba').DataTable({
            scrollX: true,
            "ajax": {
                "url": "/registration/get_data_registrasi_asset",
                "type": "GET",
                "dataSrc": ""
            },
            "columns": [
                {
                    "data": "register_code",
                    "createdCell": function(td, cellData, rowData, row, col) {
                        $(td).addClass('d-flex');
                    }
                },
                {
                    "data": "asset_model"
                },
                {
                    "data": "name_store_street"
                },
                {
                    "data": "location_now"
                },
                {
                    "data": "qty"
                },
                {
                    "data": "status_asset_name"
                },
                {
                    "data": "serial_number"
                },
                {
                    "data": "type_name"
                },
                {
                    "data": "cat_name"
                },
                {
                    "data": "priority_name",
                    "render": function(data, type, row) {
                        let color, bgColor;
                        if (data === "PRIORITY") {
                            bgColor = 'red';
                            color = 'white';
                        } else if (data === "NOT PRIORITY") {
                            bgColor = 'yellow';
                            color = 'black';
                        } else if (data === "BASIC") {
                            bgColor = 'blue';
                            color = 'white';
                        }
                        return `<span style="display: inline-block; padding: 5px 10px; background-color: ${bgColor}; border-radius: 4px; color: ${color};">${data}</span>`;
                    }
                },
                {
                    "data": "brand_name"
                },
                {
                    "data": "uom_name"
                },
                {
                    "data": "layout_name"
                },
                {
                    "data": "register_date"
                },
                {
                    "data": "supplier_name"
                },
                {
                    "data": "condition_name"
                },
                {
                    "data": "purchase_date"
                },
                {
                    "data": "warranty_name"
                },
                {
                    "data": "periodic_mtc_name"
                },
                {
                    "data": "data_registrasi_asset_status",
                    "render": function(data, type, row) {
                        if (data === "active") {
                            return `<span style="color: green;">${data}</span>`;
                        } else {
                            return `<span style="color: red;">${data}</span>`;
                        }
                    }
                },
                {
                    "data": "null",
                    "render": function(data, type, row) {
                        let approveButton = '';
                        if (row.approve_status !== 'sudah approve') {
                            approveButton =
                                `<button class="dropdown-item approve-btn" data-id="${row.id}">Submit Approve</button>`;
                        }

                        return `
                        <div class="dropdown">
                            <button class="btn btn-large btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton${row.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton${row.id}">
                                <a class="dropdown-item" href="/registration/generate-pdf/${row.register_code}" target="_blank">Cetak QR Code</a>
                                <a class="dropdown-item" href="/registration/tracking-asset-registration/${row.register_code}">Tracking History</a>
                                <a class="dropdown-item" href="/registration/detail_data_registrasi_asset/${row.register_code}" target="_blank">Detail Barang Asset</a>

                                @can('btn action edit registration')
                                    <a class="dropdown-item" href="/registration/edit-assets-registration/${row.register_code}">Update</a>
                                @endcan

                                @can('btn action delete registration')
                                    <button class="dropdown-item delete-btn" data-id="${row.id}">Delete</button>
                                @endcan

                                ${approveButton}
                            </div>
                        </div>
                        <br>
                        `;
                    }
                }
            ]
        });
    </script> --}}

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // delete data
        $('#coba').on('click', '.delete-btn', function() {
            var assetId = $(this).data('id');
            console.log("Asset ID:", assetId);

            if (!assetId) {
                Swal.fire(
                    'Error!',
                    'Asset ID is not defined.',
                    'error'
                );
                return;
            }

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Tekan Batal untuk membatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'YA, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/registration/delete-assets-registration/${assetId}`,
                        type: 'DELETE',
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                'Status Asset kamu berhasil ter-update.',
                                'success'
                            ).then(() => {
                                window.location.href =
                                    '/registration/assets-registration';
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Gagal!!',
                                'Gagal meng-update Asset.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>

    <!-- login js-->
    <!-- Plugin used-->
</body>

</html>
