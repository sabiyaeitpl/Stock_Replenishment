<!-- Header-->
<header id="header" class="header">
    <div class="top-left">
        <div class="navbar-header">
            <!-- <a class="navbar-brand" href="./"><img src="{{ asset('theme/images/bellevue-logo1.png') }}" alt="Logo"></a> -->
            <!-- <a class="navbar-brand" href="./">Bellevue</a> -->
            <a class="navbar-brand hidden" href="./"><img src="{{ asset('images/logo2.png') }}" alt="Logo"></a>
         
        </div>
    </div>
    <div class="top-right">
   
        <div class="hd-name">
        <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
        </div>
        <div class="header-menu">
           

            <div class="user-area dropdown float-right">
                <a style="display: block;overflow: hidden;float:left;padding: 20px 15px 0 0;" class="home" href="{{url('dashboard')}}"><img style="width:23px;" src="{{ asset('public/images/home.png') }}" alt="Logo"></a>

                <a title="Logout" style="display: block;overflow: hidden;float:left;padding: 25px 15px 0 0;" class="home" href="{{url('logout')}}"><img style="width:25px;" src="{{ asset('theme/images/logout.png') }}" alt="Logo"></a>

               
            </div>

        </div>
    </div>
</header>
<!-- /#header -->