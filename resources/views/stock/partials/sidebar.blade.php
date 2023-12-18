
<style>
.navbar .navbar-nav li.menu-item-has-children .sub-menu{padding-left:0;}
</style>

@if(Session('admin')->user_type=='user')
<aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">

        <?php $menus = array();
            foreach ($Roledata as $roleaccess) {
                $menus[] = $roleaccess->menu;

            }
            $menuslist = array_unique($menus);
        ?>

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                </ul>
            </div>
        </nav>
    </aside>
 @else
 <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="{{ url('stock/dashboard') }}"><img src="{{ asset('images/dashboard-icon.png') }}" alt="" />Dashboard </a>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset('images/financial-profit.png') }}" alt="" /> Stock</a>
                        <ul class="sub-menu children dropdown-menu">

                            <li><a href="{{ url('stock') }}"><img src="{{ asset('images/lv-rule.png') }}" alt="" /> Stock List</a></li>
                            <li><a href="{{ url('sales') }}"><img src="{{ asset('images/lv-rule.png') }}" alt="" /> Sales List</a></li>
                            <li><a href="{{ url('stock/rol') }}"><img src="{{ asset('images/lv-rule.png') }}" alt="" /> Rol List</a></li>
                            <li><a href="{{ url('compare') }}"><img src="{{ asset('images/lv-rule.png') }}" alt="" /> Product Compare</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
</aside>

@endif
