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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google font-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
    <style>
        #myChart {
            width: 100% !important;
            height: 314PX;
        }

        * {
            box-sizing: border-box;
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
                    <aiv class="logo-wrapper">
                        <a href="index.html">
                            <img class="img-fluid" src="{{ asset('assets/images/logo/login.png') }}" alt="">
                        </a>
                    </aiv>
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
                        <li class="onhover-dropdown">
                            <div class="notification-box"><i class="fa fa-bell-o"> </i><span
                                    class="badge rounded-pill badge-primary">4</span></div>
                            <ul class="notification-dropdown onhover-show-div">
                                <li><i data-feather="bell"> </i>
                                    <h6 class="f-18 mb-0">Notifications</h6>
                                </li>
                                <li><a href="email_read.html">
                                        <p><i class="fa fa-circle-o me-3 font-primary"> </i>Delivery processing <span
                                                class="pull-right">10 min.</span></p>
                                    </a></li>
                                <li><a href="email_read.html">
                                        <p><i class="fa fa-circle-o me-3 font-success"></i>Order Complete<span
                                                class="pull-right">1 hr</span></p>
                                    </a></li>
                                <li><a href="email_read.html">
                                        <p><i class="fa fa-circle-o me-3 font-info"></i>Tickets Generated<span
                                                class="pull-right">3 hr</span></p>
                                    </a></li>
                                <li><a href="email_read.html">
                                        <p><i class="fa fa-circle-o me-3 font-danger"></i>Delivery Complete<span
                                                class="pull-right">6 hr</span></p>
                                    </a></li>
                                <li><a class="btn btn-primary" href="email_read.html">Check all notification</a></li>
                            </ul>
                        </li>
                        <li class="onhover-dropdown"><i class="fa fa-comment-o"></i>
                            <ul class="chat-dropdown onhover-show-div">
                                <li><i data-feather="message-square"></i>
                                    <h6 class="f-18 mb-0">Message Box</h6>
                                </li>
                                <li>
                                    <div class="d-flex"><img class="img-fluid rounded-circle me-3"
                                            src="{{ asset('assets/images/user/1.jpg') }}">
                                        <div class="status-circle online"></div>
                                        <div class="flex-grow-1"><a href="chat.html"> <span>Ain Chavez</span>
                                                <p>Do you want to go see movie?</p>
                                            </a></div>
                                        <p class="f-12 font-success">1 mins ago</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex"><img class="img-fluid rounded-circle me-3"
                                            src="{{ asset('assets/images/user/2.png') }}">
                                        <div class="status-circle online"></div>
                                        <div class="flex-grow-1"><a href="chat.html"> <span>Kori Thomas</span>
                                                <p>What`s the project report update?</p>
                                            </a></div>
                                        <p class="f-12 font-success">3 mins ago</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex"><img class="img-fluid rounded-circle me-3"
                                            src="{{ asset('assets/images/dashboard/admins.png') }}">
                                        <div class="status-circle offline"></div>
                                        <div class="flex-grow-1"><a href="chat.html"> <span>Ain Chavez</span>
                                                <p>Thank you for rating us.</p>
                                            </a></div>
                                        <p class="f-12 font-danger">9 mins ago</p>
                                    </div>
                                </li>
                                <li class="text-center"> <a class="btn btn-primary" href="chat.html">View All</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <div class="mode"><i class="fa fa-moon-o"></i></div>
                        </li>
                        <li class="maximize"><a class="text-dark" href="#!"
                                onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
                        <li class="profile-nav onhover-dropdown p-0 me-0">
                            <div class="d-flex profile-media"><img class="b-r-50"
                                    src="{{ asset('assets/images/dashboard/profile.jpg') }}">
                                @if (Auth::check())
                                    <div class="flex-grow-1">
                                        <span>{{ Auth::user()->username }}</span>
                                        <p class="mb-0 font-roboto">{{ session('role') ?? Auth::user()->role }} <i
                                                class="middle fa fa-angle-down"></i></p>
                                    </div>
                                @else
                                    <div class="flex-grow-1">
                                        <span>Guest</span>
                                        <p class="mb-0 font-roboto">No role <i class="middle fa fa-angle-down"></i>
                                        </p>
                                    </div>
                                @endif


                            </div>
                            <ul class="profile-dropdown onhover-show-div">
                                <li><a href="user-profile.html"><i data-feather="user"></i><span>Account </span></a>
                                </li>
                                <!-- <li><a href="email_inbox.html"><i data-feather="mail"></i><span>Inbox</span></a></li>
                  <li><a href="kanban.html"><i data-feather="file-text"></i><span>Taskboard</span></a></li> -->
                                <li><a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
                                        <i data-feather="log-out"></i><span>Log Out</span></a></li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
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
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="card p-4 text-center">
                                        @if (!$user->hasRole('Admin') &&
                                         !$user->hasRole('SDG'))

                                            <label for=""><h4>Asset In</h4></label>
                                            <p class="count red fs-2 text-info">{{ $assetIn }}</p>
                                            <p class="description"><a href="/asset-transfer/confirm-asset">Show Total Asset In -></a></p>
                                                
                                        @else

                                            <label for=""><h4>Total Resto</h4></label>
                                            <p class="count red fs-2 text-info">{{ $totalResto }}</p>
                                            <p class="description">Total Resto Data</p>
                                            
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card p-4 text-center">
                                        <label for=""><h4>Total Asset</h4></label>
                                        <p class="count red fs-2 text-primary">{{ $totalAsset }}</p>
                                        <p class="description"><a href="/registration/assets-registration">Show Total Asset Data -></a></p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card p-4 text-center">
                                        <label for=""><h4>Total Asset Registered</h4></label>
                                        <p class="count red fs-2 text-success">{{ $totalRegistered }}</p>
                                        <p class="description"><a href="/registration/assets-registration">Show Total Asset Registered Data -></a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="text-center border-primary border-2 border-bottom">
                                    <label class="mt-3 text-primary"><h4>Asset Movement</h4></label>
                                </div>
                                <div class="row p-4">
                                    <div class="col-sm-4">
                                        <div class="text-center">
                                            <label for=""><h4>Movement</h4></label>
                                            <p class="count red fs-2 text-primary">{{ $assetMove }}</p>
                                            <p class="description">Total Movement Asset</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-center">
                                            <label for=""><h4>Good Asset</h4></label>
                                            <p class="count red fs-2 text-success">{{ $goodAsset }}</p>
                                            <p class="description">Good Condition Asset</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-center">
                                            <label for=""><h4>Bad Asset</h4></label>
                                            <p class="count red fs-2 text-danger">{{ $badAsset }}</p>
                                            <p class="description">Bad Condition Asset</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="text-center border-danger border-2 border-bottom">
                                    <label class="mt-3 text-danger"><h4>Asset Disposal</h4></label>
                                </div>
                                <div class="row p-4">
                                    <div class="col-sm-4">
                                        <div class="text-center">
                                            <label for=""><h4>Disposal</h4></label>
                                            <p class="count red fs-2 text-primary">{{ $assetDisposal }}</p>
                                            <p class="description">Total Movement Asset</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-center">
                                            <label for=""><h4>Good Asset</h4></label>
                                            <p class="count red fs-2 text-success">{{ $goodAssetDis }}</p>
                                            <p class="description">Good Condition Asset</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-center">
                                            <label for=""><h4>Bad Asset</h4></label>
                                            <p class="count red fs-2 text-danger">{{ $badAssetDis }}</p>
                                            <p class="description">Bad Condition Asset</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-5">
                    <div class="pb-5">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="start_date">Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date">End Date</label>
                                <input type="date" id="end_date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-primary me-2" onclick="filterNow()">Filter</button>
                                <a href="/dashboard" class="btn btn-secondary ml-2">Reset</a>
                            </div>
                        </div>
                    </div>
                    <!-- Tombol untuk navigasi halaman -->
                    <div class="d-flex justify-content-end">
                        <button id="prevPage" class="btn btn-info me-2">Previous</button>
                        <span id="current-page"> 1</span> / <span id="total-pages">10 </span>
                        <button id="nextPage" class="btn btn-info ms-2">Next</button>
                    </div>
                    <div class="d-flex justify-content-center mb-4">
                        <h2>Asset Per Resto Mie Gacoan</h2>
                    </div>
                    <div id="posts-container"></div>
                    <canvas id="myChart"></canvas>
                </div>
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
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <!-- login js-->
    <!-- Plugin used-->
    <script>
        let currentPage = 1; // Halaman saat ini
        const itemsPerPage = 10; // Jumlah restoran per halaman

        let labels = [];
        let data1 = []; // Data pertama
        let data2 = []; // Data kedua
        let data3 = []; // Data ketiga (data disposal)

        $(document).ready(function() {
            // Mengambil data melalui AJAX ketika halaman siap
            fetchRestoData();
        });

        // Fungsi filter berdasarkan tanggal
        function filterNow() {
            const startDate = $("#start_date").val();
            const endDate = $("#end_date").val();

            // Pastikan startDate dan endDate valid
            if (startDate && endDate) {
                // Panggil fetchRestoData dengan startDate dan endDate
                fetchRestoData(startDate, endDate);
            } else {
                alert('Silakan pilih tanggal dengan benar!');
            }
        }

        // Fungsi mengambil data restoran
        function fetchRestoData(startDate, endDate) {
            $.ajax({
                url: '{{ route('dashboard.get-data-resto-json') }}', // URL untuk mengambil data
                method: 'GET',
                data: {
                    start_date: startDate, // Ambil nilai start_date dari input
                    end_date: endDate // Ambil nilai end_date dari input
                },
                dataType: 'json', // Format data yang diharapkan
                success: function(response) {
                    if (response && Object.keys(response).length > 0) {
                        processRestoData(response);
                    } else {
                        $('#posts-container').html('<p>Tidak ada data.</p>');
                    }
                },
                error: function() {
                    $('#posts-container').html('<p>Terjadi kesalahan saat mengambil data.</p>');
                }
            });
        }

        // Fungsi untuk memproses data restoran dan membuat chart
        function processRestoData(response) {
            const restoMap = {}; // key = nama resto, value = { in: x, out: y }

            function addQtyToMap(name, type, qty) {
                if (!restoMap[name]) {
                    restoMap[name] = { in: 0, out: 0 };
                }
                restoMap[name][type] += Number(qty) || 0;
            }


            response.dataIn.forEach(function(item) {
                addQtyToMap(item.tujuan_lokasi, 'in', item.qty);
            });

            response.dataOut.forEach(function(item) {
                addQtyToMap(item.asal_lokasi, 'out', item.qty);
            });

            // Reset array global
            labels = [];
            data1 = [];
            data2 = [];

            // Ubah hasil map jadi array grafik
            Object.keys(restoMap).forEach(function(restoName) {
                labels.push(restoName); // ini dipakai di sumbu X
                data1.push(restoMap[restoName].in);
                data2.push(restoMap[restoName].out);
            });

            updateChart();
        }


        // Fungsi untuk memperbarui chart berdasarkan halaman yang aktif
        function updateChart() {
            // Mendapatkan konteks canvas
            var ctx = document.getElementById('myChart').getContext('2d');

            // Mengecek apakah chart sudah ada sebelumnya dan menghancurkannya
            if (window.myChart instanceof Chart) {
                window.myChart.destroy();
            }

            // Membatasi data yang akan ditampilkan pada halaman ini
            const startIdx = (currentPage - 1) * itemsPerPage;
            const endIdx = startIdx + itemsPerPage;
            const pageLabels = labels.slice(startIdx, endIdx);
            const datain = data1.slice(startIdx, endIdx);
            const dataout = data2.slice(startIdx, endIdx);

            // Membuat chart baru dan menyimpannya ke dalam window.myChart
            window.myChart = new Chart(ctx, {
                type: 'bar', // Jenis chart, bisa diganti dengan line, pie, dll
                data: {
                    labels: pageLabels, // Label sumbu X
                    datasets: [{
                            label: 'Asset In',
                            data: datain, // Data asli
                            backgroundColor: 'rgba(13, 202, 240, 0.2)', // Warna latar belakang
                            borderColor: 'rgba(13, 202, 240, 1)', // Warna border
                            borderWidth: 1
                        },
                        {
                            label: 'Asset Out',
                            data: dataout, // Data good
                            backgroundColor: 'rgba(25, 135, 84, 0.2)', // Warna latar belakang
                            borderColor: 'rgba(25, 135, 84, 1)', // Warna border
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Perbarui tombol navigasi (Next dan Previous)
            updatePagination();
        }

        // Fungsi untuk memperbarui tombol pagination
        function updatePagination() {
            const totalPages = Math.ceil(labels.length / itemsPerPage);
            $('#current-page').text(currentPage);
            $('#total-pages').text(totalPages);

            // Sembunyikan tombol "Previous" jika sudah di halaman pertama
            $('#prevPage').prop('disabled', currentPage === 1);

            // Sembunyikan tombol "Next" jika sudah di halaman terakhir
            $('#nextPage').prop('disabled', currentPage === totalPages);
        }

        // Event listeners untuk tombol navigasi paging
        $('#nextPage').on('click', function() {
            const totalPages = Math.ceil(labels.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updateChart(); // Perbarui chart untuk halaman berikutnya
            }
        });

        $('#prevPage').on('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updateChart(); // Perbarui chart untuk halaman sebelumnya
            }
        });
    </script>
</body>

</html>
