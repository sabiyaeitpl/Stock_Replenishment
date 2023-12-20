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
                <h5 class="card-title">Stock Master</h5>
            </div>
            <div class="col-md-6">
                <span class="right-brd" style="padding-right:15x;">
                    <ul class="">
                        <li><a href="#">Stock</a></li>
                        <li>/</li>
                        <li class="active">Stock Master</li>

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
                    <button type="button" class="btn btn-primary mx-1" title="Import Pay Details" style="float:right" data-toggle="modal" data-target="#exampleModal1">
                            Import Stock Details
                     </button>
                        <!-- <a href="{{ url('add-stock') }}" class="btn btn-default" style="float:right;">Add Import Master <i class="fa fa-plus"></i></a> -->
                        @if(count($employee_rs)>0)
                        <form  method="post" action="{{ url('xls-export-employees') }}" enctype="multipart/form-data" >
											<input type="hidden" name="_token" value="{{ csrf_token() }}">

											<!-- <button data-toggle="tooltip" data-placement="bottom" title="Download Excel" class="btn btn-default" style="background:none !important;padding: 10px 15px;margin-top: -30px;float:right;margin-right: 15px;" type="submit"><img  style="width: 35px;" src="{{ asset('img/excel-dnld.png')}}"></button> -->
												</form>
                                <form  method="post" action="{{ url('xls-export-employee-only') }}" enctype="multipart/form-data" >
											<input type="hidden" name="_token" value="{{ csrf_token() }}">

											<!-- <button data-toggle="tooltip" data-placement="bottom" title="Download Excel" class="btn btn-default" style="background:none !important;padding: 10px 15px;margin-top: -30px;float:right;margin-right: 15px;" type="submit">Stock Data Only</button> -->
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
                                    <th>Name</th>
                                    <th>Division</th>
                                    <th>Section</th>
                                    <th>Department</th>
                                    <th>Barcode</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee_rs as $employee)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>{{ $employee->name}}</td>
                                    <td>{{ $employee->division}}</td>
                                    <td>{{ $employee->section}}</td>
                                    <td>{{ $employee->department }}</td>
                                    <td>{{ $employee->barcode }}</td>
                                    <td>{{ $employee->stock_quantity }}</td>
                                   
                                </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>

                </div>

            </div>



        </div>
        <!-- /Widgets -->


        <!-- Modal -->
        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
						<div class="modal-dialog" role="document">
						  <form style='padding: 0px;' action="#" method="post" enctype="multipart/form-data">
							  @csrf
							  <div class="modal-content">
								<!--<div class="modal-header">-->
								<!--  <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>-->
								<!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
								<!--    <span aria-hidden="true">&times;</span>-->
								<!--  </button>-->
								<!--</div>-->
								<div class="modal-body">
								  
									<div class="form-group">
									  <label for="excel_file">Upload Stock Details CSV</label>
									  <input type="file" name="excel_file" class="form-control" style='height: 40px;' id="excel_file">
									</div>
								  
								</div>
								<div class="modal-footer">
								  <button type="button" class="btn btn-secondary" style="padding: 0px 8px;height: 32px;" data-dismiss="modal">Close</button>
								  <button type="submit" class="btn btn-primary" style="color: #fff;background-color: #0884af;border-color: #0884af;padding: 0px 8px;height: 32px;">Import</button>
								</div>
							  </div>
						  </form>
						</div>
					  </div>
					  <!-- END -->



    </div>
    <!-- .animated -->
</div>
<!-- /.content -->
<?php //include("footer.php");
?>
</div>
<!-- /#right-panel -->

@endsection
