<style>
    .top-left .menutoggle {
    color: #fff !important;
    cursor: pointer;
    font-size: 1em;
    height: 33px;
    line-height: 60px;
    width: 83px;
    /* display: block; */
    position:absolute;
    text-align: right;
    }
</style>
<header id="header" class="header">
    <div class="top-left">
        <div class="navbar-header">
            <!-- <a class="navbar-brand" href="./"><img src="{{ asset('theme/images/bopt-logo.png') }}" alt="Logo"></a> -->
            <a class="navbar-brand" href="./"><img src="{{ asset('theme/images/bellevue-logo1.png') }}" alt=""></a>
            <a class="navbar-brand hidden" href="./"><img src="{{ asset('images/logo2.png') }}" alt="Logo"></a>
            <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
        </div>
    </div>
    <div class="top-right">
        <div class="hd-name">
            <!-- <h2>BOARD OF PRACTICAL TRAINING (EASTERN REGION)</h2>
                            <h4><span style="font-size: 20px;">Under Ministry of HRD, Government of India</span></h4> -->
        </div>
        <div class="header-menu">
            <!--<div class="header-left">
                        <button class="search-trigger"><i class="fa fa-search"></i></button>
                        <div class="form-inline">
                            <form class="search-form">
                                <input class="form-control mr-sm-2" type="text" placeholder="Search ..." aria-label="Search">
                                <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                            </form>
                        </div>
                        

                        <!--<div class="dropdown for-notification">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bell"></i>
                                <span class="count bg-danger">3</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="notification">
                                <p class="red">You have 3 Notification</p>
                                <a class="dropdown-item media" href="#">
                                    <i class="fa fa-check"></i>
                                    <p>Server #1 overloaded.</p>
                                </a>
                                <a class="dropdown-item media" href="#">
                                    <i class="fa fa-info"></i>
                                    <p>Server #2 overloaded.</p>
                                </a>
                                <a class="dropdown-item media" href="#">
                                    <i class="fa fa-warning"></i>
                                    <p>Server #3 overloaded.</p>
                                </a>
                            </div>
                        </div>

                        <div class="dropdown for-message">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="message" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-envelope"></i>
                                <span class="count bg-primary">4</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="message">
                                <p class="red">You have 4 Mails</p>
                                <a class="dropdown-item media" href="#">
                                    <span class="photo media-left"><img alt="avatar" src="images/avatar/1.jpg"></span>
                                    <div class="message media-body">
                                        <span class="name float-left">Jonathan Smith</span>
                                        <span class="time float-right">Just now</span>
                                        <p>Hello, this is an example msg</p>
                                    </div>
                                </a>
                                <a class="dropdown-item media" href="#">
                                    <span class="photo media-left"><img alt="avatar" src="images/avatar/2.jpg"></span>
                                    <div class="message media-body">
                                        <span class="name float-left">Jack Sanders</span>
                                        <span class="time float-right">5 minutes ago</span>
                                        <p>Lorem ipsum dolor sit amet, consectetur</p>
                                    </div>
                                </a>
                                <a class="dropdown-item media" href="#">
                                    <span class="photo media-left"><img alt="avatar" src="images/avatar/3.jpg"></span>
                                    <div class="message media-body">
                                        <span class="name float-left">Cheryl Wheeler</span>
                                        <span class="time float-right">10 minutes ago</span>
                                        <p>Hello, this is an example msg</p>
                                    </div>
                                </a>
                                <a class="dropdown-item media" href="#">
                                    <span class="photo media-left"><img alt="avatar" src="images/avatar/4.jpg"></span>
                                    <div class="message media-body">
                                        <span class="name float-left">Rachel Santos</span>
                                        <span class="time float-right">15 minutes ago</span>
                                        <p>Lorem ipsum dolor sit amet, consectetur</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>-->

            <div class="user-area dropdown float-right">
            <a style="display: block;overflow: hidden;float:left;padding: 20px 15px 0 0;" class="home" href="{{url('dashboard')}}"><img style="width: 26px;
    margin-top: 76px;" src="{{ asset('images/home.png') }}" alt="Logo"></a>
     <a title="HCM Dashboard" style="display: block;overflow: hidden;float:left;padding: 59px 15px 0 0;" class="home" href="{{url('hcm-dashboard')}}" alt=""><img style="width:25px;" src="{{ asset('images/inner-dashboard.png') }}" alt="Logo"></a>                    
      <a title="Logout" style="display: block;overflow: hidden;float:left;padding: 25px 15px 0 0;" class="home" href="{{url('logout')}}"><img src="{{ asset('img/login.png') }}" alt=
                        " " style="margin-top: 66px;
    width: 30px;
}"></a>

                <!-- <div class="user-menu dropdown-menu">
                            
                            <a class="nav-link" href="change-login-password.php"><i class="fa fa-bars"></i> Change Password</a></li>

                            <a class="nav-link" href="#"><i class="fa fa- user"></i>Notifications <span class="count">13</span></a>

                            <a class="nav-link" href="#"><i class="fa fa -cog"></i>Settings</a>

                            <a class="nav-link" href="{{url('mainLogout')}}"><i class="fa fa-power-off"></i>Logout</a>
                        </div> -->
            </div>

        </div>
    </div>
</header>
