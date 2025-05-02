<div class="sidebar-wrapper">
    <div>
        <div class="logo-wrapper"><a href="/dashboard"><img class="img-fluid for-light"
                    src="{{ asset('assets/images/header-card.png') }}"></a>
        </div>
        <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid"
                    src="{{ asset('assets/images/logo/logo-icon1.png') }}"></a></div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn"><a href="index.html"><img class="img-fluid"
                                src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a>
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
                    </li>
                    <!-- <li class="sidebar-main-title">
              <h6 class="lan-1">General </h6>
            </li>
            <li class="menu-box">
              <ul>
                <li class="sidebar-list">           <a class="sidebar-link sidebar-title" href="dashboard-02.html"><i data-feather="home"></i><span class="lan-3">Dashboard              </span></a>
                  <ul class="sidebar-submenu">
                  </ul>
                </li>
              </ul>
            </li> -->
                    <li class="sidebar-main-title">
                        <!-- <h6>Master Data</h6> -->
                    </li>
                    @can('view dashboard')
                        <li class="menu-box">
                            <ul>
                                <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="/dashboard"><i
                                            data-feather="pie-chart"></i><span>Dashboard</span></a></li>
                            </ul>
                        </li>
                    @endcan

                    <li class="sidebar-main-title">
                        <h6>Asset</h6>
                    </li>
                    <li class="menu-box">
                        <ul>
                            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                        data-feather="clipboard"></i><span>Registration</span></a>
                                @can('view registration')
                                    <ul class="sidebar-submenu">
                                        <li><a class="sidebar-link sidebar-title link-nav"
                                                href="/registration/assets-registration"><span>Assets Regist</span></a></li>
                                </li>
                                {{-- <li><a class="sidebar-link sidebar-title link-nav"
                                        href="/registration/approval-ops-sm"><span>Approval OPS SM</span></a></li> --}}
                            </ul>
                        @endcan

                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="server"></i><span>Asset Transfer</span></a>
                        <ul class="sidebar-submenu">

                            @can('view at request moveout')
                                <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                        href="/asset-transfer/request-moveout"><span>Request Movement Out</span></a></li>
                            @endcan

                            @can('view at approval ops am')
                              <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                        href="/asset-transfer/approval-ops-am"><span>Approval OPS AM</span></a></li>
                            @endcan

                            @can('view at approval ops rm')
                              <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                        href="/asset-transfer/approval-ops-rm"><span>Approval OPS RM</span></a></li>
                            @endcan

                            @can('view at approval ops sdg')
                              <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                        href="/asset-transfer/approval-sdg-asset"><span>Approval SDG Assets</span></a></li>
                            @endcan

                            {{-- @can('view at request movement in')
                              <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                              href="/asset-transfer/request-movement-in"><span>Request Movement In</span></a></li>
                            @endcan
                            
                            @can('view at delivery order')
                              <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                              href="/asset-transfer/delivery-order"><span>Delivery Order</span></a></li>
                            @endcan --}}
                            
                            @can('view at confirm asset')
                              <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                              href="/asset-transfer/confirm-asset"><span>Confirm Asset</span></a></li>
                            @endcan
                            
                            @can('view at head')
                              <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                              href="/asset-transfer/review"><span>Review Movement Out</span></a></li>
                            @endcan
                            
                            
                            
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="book"></i><span>Disposal</span></a>
                        <ul class="sidebar-submenu">
                                @can('view dis request disposal')
                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                        href="/disposal/request-disposal"><span>Request Disposal</span></a></li>
                                @endcan

                                @can('view dis approval ops am')
                                        <li class="sidebar-list">
                                        <a class="sidebar-link sidebar-title link-nav" href="/disposal/approval-ops-am"><span>Approval OPS AM</span></a></li>
                                @endcan

                                @can('view dis approval ops rm')
                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                        href="/disposal/approval-ops-rm"><span>Approval OPS RM</span></a></li>
                                @endcan

                                @can('view dis approval ops sdg')
                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                        href="/disposal/approval-sdg-asset"><span>Approval SDG Assets</span></a></li>
                                @endcan

                                @can('view dis review')
                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                        href="/disposal/review"><span>Review</span></a></li>
                                @endcan
                        </ul>
                    </li>

                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="archive"></i><span>Stock Opname</span></a>
                        <ul class="sidebar-submenu">
                            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                    href="/stockopname"><span>Data Stock Opname</span></a></li>
                            <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="/admin/adjustmentstock"><span>Adjustment Stock</span></a></li> -->
                        </ul>
                    </li>
                </ul>
                </li>



                {{-- <li class="sidebar-main-title">
                    <h6>Maintenance</h6>
                </li>
                <li class="menu-box">
                    <ul>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                    data-feather="clock"></i><span>Schedule</span></a>
                            <ul class="sidebar-submenu">
                                <li><a class="sidebar-link sidebar-title link-nav"
                                        href="/schedule/lihat_data_schedule"><span> Data Schedule</span></a></li>
                        </li>
                    </ul>

                <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                            data-feather="trello"></i><span>Preventive Maintenance</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="sidebar-link sidebar-title link-nav"
                                href="/preventive_maintenance/lihat_data_preventive_maintenance"><span> Data Preventive
                                    Schedule</span></a></li>
                </li>
                </ul>

                <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                            data-feather="edit"></i><span>Corrective Maintenance</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="sidebar-link sidebar-title link-nav"
                                href="/corrective_maintenance/lihat_data_corrective_maintenance"><span> Data Corrective
                                    Schedule</span></a></li>
                </li>
                </ul>

                <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                            data-feather="check-circle"></i><span>Checklist</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="sidebar-link sidebar-title link-nav"
                                href="/checklist/lihat_data_checklist"><span> Data Checklist Schedule</span></a></li>
                </li>
                </ul>
                </ul>
                </li> --}}


                @can('view master data')
                        <li class="sidebar-main-title">
                                <h6>Master Data</h6>
                                </li>

                                <li class="menu-box">
                                <ul>
                                        <li class="sidebar-list">
                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                                data-feather="database"></i><span>Master Data</span></a>
                                        <ul class="sidebar-submenu">
                                                @can('view md asset')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/asset"><span>Asset</span></a></li>
                                                @endcan

                                                @can('view md asset equipment')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/asset-equipment"><span>Asset Equipment</span></a></li>
                                                @endcan
                                                <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="#"><span>Merk</span></a></li> -->
                                                @can('view md brand')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/brand"><span>Brand</span></a></li>
                                                @endcan
                                                @can('view md kategori')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/category"><span>Kategori</span></a></li>
                                                @endcan
                                                @can('view md sub kategori')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/sub-category"><span>Sub Kategori</span></a></li>
                                                @endcan
                                                @can('view md checklist')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/checklist"><span>Checklist</span></a></li>
                                                @endcan
                                                @can('view md kondisi')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/condition"><span>Kondisi</span></a></li>
                                                @endcan
                                                @can('view md kontrol checklist')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/control-checklist"><span>Kontrol Checklist</span></a></li>
                                                @endcan
                                                @can('view md departement')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/departement"><span>Departement</span></a></li>
                                                @endcan
                                                @can('view md division')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/division"><span>Division</span></a></li>
                                                @endcan
                                                @can('view md group user')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/group-user"><span>Group User</span></a></li>
                                                @endcan
                                                @can('view md job level')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/job-level"><span>Job Level</span></a></li>
                                                @endcan
                                                @can('view md tata letak')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/layout"><span>Tata Letak</span></a></li>
                                                @endcan
                                                {{-- @can('view md maintenance')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/admin/mtc"><span>Maintenance</span></a></li>
                                                @endcan --}}
                                                @can('view md people')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/people"><span>People</span></a></li>
                                                @endcan
                                                @can('view md tipe asset')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/type-asset"><span>Tipe Asset</span></a></li>
                                                @endcan
                                                @can('view md tipe maintenance asset')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/type-maintenance-asset"><span>Tipe Maintenance Asset</span></a></li>
                                                @endcan
                                                @can('view md periodic maintenance')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/admin/periodic"><span>Periodic Maintenance</span></a></li>
                                                @endcan
                                                @can('view md prioritas')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/priority"><span>Prioritas</span></a></li>
                                                @endcan
                                                @can('view md alasan mutasi')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/alasan-mutasi"><span>Alasan Mutasi</span></a></li>
                                                @endcan
                                                @can('view md alasan stock opname')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/alasan-stock-opname"><span>Alasan Stock Opname</span></a></li>
                                                @endcan
                                                @can('view md regional')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/regional"><span>Regional</span></a></li>
                                                @endcan
                                                @can('view md perbaikan')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/repair"><span>Perbaikan</span></a></li>
                                                @endcan
                                                @can('view md pemasok')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/supplier"><span>Pemasok</span></a></li>
                                                @endcan
                                                @can('view md satuan')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/uom"><span>Satuan</span></a></li>
                                                @endcan
                                                @can('view md garansi')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/warranty"><span>Garansi</span></a></li>
                                                @endcan
                                                @can('view md kota')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/city"><span>Kota</span></a></li>
                                                @endcan
                                                @can('view md resto')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/resto"><span>Resto</span></a></li>
                                                @endcan
                                                {{-- @can('view md approval maintenance')
                                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                                                        href="/master-data/approval-maintenance"><span>Approval Maintenance</span></a></li>
                                                @endcan --}}



                                        </ul>   
                                        </li>
                                </ul>
                        </li>
                @endcan



                @can('menu user')
                        <li class="sidebar-main-title">
                        <h6>User Management</h6>
                        </li>
                        <li class="menu-box">
                        <ul>
                                <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                        data-feather="clipboard"></i><span>User</span></a>
                                <ul class="sidebar-submenu">
                                        <li><a class="sidebar-link sidebar-title link-nav"
                                                href="/user"><span>User</span></a></li>
                                </li>
                                @can('view role')
                                        {{-- <li><a class="sidebar-link sidebar-title link-nav" href="/role"><span>Role</span></a></li> --}}
                                @endcan
                        </li>
                                @can('view permission')
                                        <li><a class="sidebar-link sidebar-title link-nav" href="/permission"><span>Permission</span></a></li>
                                @endcan
                        </li>
                        </ul>
                        </ul>
                        </li>
                @endcan
                <li class="sidebar-main-title">
                    <h6>Report</h6>
                </li>
                <li class="menu-box">
                    <ul>
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                    data-feather="clipboard"></i><span>Report</span></a>
                            <ul class="sidebar-submenu">
                                <li><a class="sidebar-link sidebar-title link-nav"
                                        href="/reports/registrasi_asset_report"><span>Registrasi Asset</span></a></li>
                        </li>
                        <li><a class="sidebar-link sidebar-title link-nav"
                                href="/reports/mutasi_stock_asset"><span>Mutasi Stock Asset</span></a></li>
                </li>
                <li><a class="sidebar-link sidebar-title link-nav" href="/reports/kartu_stock_asset"><span>Kartu Stock
                            Asset</span></a></li>
                </li>
                <li><a class="sidebar-link sidebar-title link-nav" href="/reports/stock_opname"><span>Stock
                            Opname</span></a></li>
                </li>
                <li><a class="sidebar-link sidebar-title link-nav"
                        href="/reports/stock_asset_per_location"><span>Stock Asset Per Location</span></a></li>
                </li>
                <li><a class="sidebar-link sidebar-title link-nav" href="/reports/disposal_asset"><span>Disposal
                            Asset</span></a></li>
                </li>
                </ul>
                </ul>
                </li>
                </ul>
                </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>
