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

@section('scripts')
@include('stock.partials.scripts')
@endsection

@section('content')
<?php
function my_simple_crypt($string, $action = 'encrypt')
{
    // you may change these values to your own
    $secret_key = 'bopt_saltlake_kolkata_secret_key';
    $secret_iv = 'bopt_saltlake_kolkata_secret_iv';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}
?>
<!-- Content -->
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
                <h5 class="card-title">Sales Master</h5>
            </div>
            <div class="col-md-6">
                <span class="right-brd" style="padding-right:15x;">
                    <ul class="">
                        <li><a href="#">Sales</a></li>
                        <li>/</li>
                        <li class="active">Sales Master</li>

                    </ul>
                </span>
            </div>
        </div>
        <!-- Widgets  -->
        <div class="row">

            <div class="main-card">
                <div class="card">

                    <div class="card-header">
                    <div class="aply-lv">
                        <a href="{{ url('add-sales') }}" class="btn btn-default" style="float:right;">Add Import Master <i class="fa fa-plus"></i></a>
                        @if(count($rolValue)>0)
                        <form  method="post" action="{{ url('xls-export-employees') }}" enctype="multipart/form-data" >
											<input type="hidden" name="_token" value="{{ csrf_token() }}">

											<button data-toggle="tooltip" data-placement="bottom" title="Download Excel" class="btn btn-default" style="background:none !important;padding: 10px 15px;margin-top: -30px;float:right;margin-right: 15px;" type="submit"><img  style="width: 35px;" src="{{ asset('img/excel-dnld.png')}}"></button>
												</form>
                                <form  method="post" action="{{ url('xls-export-employee-only') }}" enctype="multipart/form-data" >
											<input type="hidden" name="_token" value="{{ csrf_token() }}">

											<button data-toggle="tooltip" data-placement="bottom" title="Download Excel" class="btn btn-default" style="background:none !important;padding: 10px 15px;margin-top: -30px;float:right;margin-right: 15px;" type="submit">Sales Data Only</button>
												</form>
                        @endif
                    </div>
                    </div>

                    <!-- @if(Session::has('message'))
										<div class="alert alert-success" style="text-align:center;"><span class="glyphicon glyphicon-ok" ></span><em > {{ Session::get('message') }}</em></div>
								@endif	 -->
                    @include('include.messages')

                    <!-- <div class="aply-lv">
                        <a href="{{ url('add-employee') }}" class="btn btn-default">Add Employee Master <i class="fa fa-plus"></i></a>
                    </div> -->
                    <br />
                    <div class="clear-fix">
                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Barcode</th>
                                    <th>Total Quantity</th>
                                    <th>Roll Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rolValue as $item)
                                @foreach($item as $itex)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>{{ $itex->barcode }}</td>
                                    <td>{{ $itex->stock_quantity }}</td>
                                    <td>{{ $itex->rol_quantity }}</td>

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
