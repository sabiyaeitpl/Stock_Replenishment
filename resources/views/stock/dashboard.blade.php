@extends('stock.layouts.master')
@section('title')
Stock Information
@endsection

@section('sidebar')
@include('stock.partials.sidebar')
@endsection

@section('header')
@include('stock.partials.header')
@endsection



@section('content')
<style>
    .right-panel {

    margin-top: 93px;
}
.card form {
    	padding: 19px 0 0 0;
        background:none;
	}
</style>
<div class="content">
    <!-- Animated -->
    <div class="animated fadeIn">
        <div class="row" style="border:none;">
            <div class="col-md-6">
                <h5 class="card-title">Dashboard</h5>
            </div>
            <div class="col-md-6">
                <span class="right-brd" style="padding-right:15x;">
                    <ul class="">
                        <li><a href="#">Dashboard</a></li>

                    </ul>
                </span>
            </div>
        </div>
        <!-- Widgets  -->
        <div class="row">

            <div class="main-card">
                <div class="card">
                    <br />
                    <div class="clear-fix">
                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Store Name.</th>
                                    <th>Barcode</th>
                                    <th>Stock Quantity</th>
                                    <th>ROL Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rolValue as $item)
                                @foreach($item as $itex)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>{{ $itex->name }}</td>
                                    <td>{{ $itex->barcode }}</td>
                                    <td>{{ $itex->stock_quantity }}</td>
                                    <td>{{ $itex->rol_quantity }}</td>
                                    <?php
                                        $stock_quantity = $itex->stock_quantity;
                                        $rol_quantity = $itex->rol_quantity;
                                        $diff_quantiy = ($rol_quantity - $stock_quantity);
                                    ?>
                                    @if ($diff_quantiy > 2)
                                    <td style="text-align: center;"><img src="{{asset('theme/Z16w.gif')}}" alt="" style="width: 40px;height:40px"></td>
                                    @else
                                    <td style="text-align: center;"><img src="{{asset('theme/alert1.png')}}" alt="" style="width: 40px;height:40px"></td>
                                    @endif


                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>


                    </div>

                </div>

            </div>



        </div>
        <!-- /Widgets -->



    </div>
    <!-- .animated -->
</div>
<!-- /.content -->
<?php //include("footer.php");
?>
</div>
<!-- /#right-panel -->

@endsection
@section('scripts')
@include('stock.partials.scripts')
@endsection
