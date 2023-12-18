@include('layouts.default-login')


<body>
    <div class="page-wrapper">

        <header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                         <img src="{{ asset('theme/images/Stockx_logo.png') }}" style="width:115px;">
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-6 col-6">
                        <ul class="head-right">
                            @if (!empty(Session::get('admin')))
                            <li>
                                <div class="element">

                                    <div class="inner-icon">
                                        <a data-toggle="tooltip" data-placement="bottom" title="Logout"
                                            href="{{url('logout')}}" style="color:#fff;"><img
                                                src="{{URL::to('')}}/theme/main/logout.png" style="width:30px;"></a>
                                    </div>


                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

        </header>

        <div class="main-dash">
            <div class="wrapper">
                <div class="row">
                    <?php
                        $rolemenu = '';
                        $hcm = '';
                        $config = '';
                    ?>

                    @if (!empty(Session::get('admin')))
                    <?php $admin = Session::get('admin');?>
                    @if($admin->user_type=='user')
                    @foreach($Roledata as $roles)
                    @if( Session::get('adminusernmae')==$roles->member_id)
                        <?php
                            if ($roles->module_name == 'Role Management') {
                                $rolemenu = 'Role_Management';
                            }
                            if ($roles->module_name == 'Stock Mnagement') {
                                $hcm = 'stock_management';
                            }

                        ?>
                    @endif
                    @endforeach



                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        @if($rolemenu=='Role_Management')


                        <a href="{{ url('role/dashboard') }}">

                            @else
                            <a href="#">
                                @endif

                                <div class="box">
                                    <div class="dash-icon">
                                        <img src="{{ asset('theme/main/settings.png') }}" alt="">
                                    </div>
                                    <div class="dash-name">
                                        <h3>Role</h3>
                                    </div>
                                </div>
                            </a>
                    </div>


                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        @if($hcm=='stock_management')
                        <a href="{{ url('stock/dashboard') }}">
                            @else
                            <a href="#">
                                @endif
                                <div class="box dc">
                                    <div class="dash-icon">
                                        <img src="{{ asset('theme/main/hcm.png') }}" alt="">
                                    </div>
                                    <div class="dash-name">
                                        <h3>Stock Replanishment</h3>
                                    </div>
                                </div>
                            </a>
                    </div>

                    @elseif($admin->user_type=='admin')

                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <a href="{{ url('role/dashboard') }}">
                            <div class="box">
                                <div class="dash-icon">
                                    <img src="{{ asset('theme/main/settings.png') }}" alt="">
                                </div>
                                <div class="dash-name">
                                    <h3>Role</h3>
                                </div>
                            </div>
                        </a>
                    </div>


                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <a href="{{ url('stock/dashboard') }}">
                            <div class="box dc">
                                <div class="dash-icon">
                                    <img src="{{ asset('theme/main/hcm.png') }}" alt="">
                                </div>
                                <div class="dash-name">
                                    <h3>Stock Replanishment</h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    @endif
                    @endif



                </div>
            </div>
        </div>

        <footer>
            <p>&copy; Copyright {{date('Y')}} EIT | All Right Reserved</p>
        </footer>
        <!-- circle-progress/circle-progress.min.js -->

        <!-- Jquery JS-->
        <script src="{{ asset('vendor-main-dash/jquery-3.2.1.min.js')}}"></script>
        <!-- Bootstrap JS-->
        <script src="{{ asset('vendor-main-dash/bootstrap-4.1/popper.min.js')}}"></script>
        <script src="{{ asset('vendor-main-dash/bootstrap-4.1/bootstrap.min.js')}}"></script>
        <!-- Vendor JS       -->
        <script src="{{ asset('vendor-main-dash/slick/slick.min.js')}}">
        </script>
        <script src="{{ asset('vendor-main-dash/wow/wow.min.js')}}"></script>
        <script src="{{ asset('vendor-main-dash/animsition/animsition.min.js')}}"></script>
        <script src="{{ asset('vendor-main-dash/bootstrap-progressbar/bootstrap-progressbar.min.js')}}">
        </script>
        <script src="{{ asset('vendor-main-dash/counter-up/jquery.waypoints.min.js')}}"></script>
        <script src="{{ asset('vendor-main-dash/counter-up/jquery.counterup.min.js')}}">
        </script>
        <script src="{{ asset('vendor-main-dash/circle-progress/circle-progress.min.js')}}"></script>
        <script src="{{ asset('vendor-main-dash/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
        <script src="{{ asset('vendor-main-dash/chartjs/Chart.bundle.min.js')}}"></script>
        <script src="{{ asset('vendor-main-dash/select2/select2.min.js')}}">
        </script>

        <!-- Main JS-->
        <script src="{{ asset('js-main-dash/main.js')}}"></script>
        <script>

</html>
