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
<!-- Content -->
<style>
   .right-panel {
   margin-top: 93px;
   }
   .card form {
   padding: 19px 0 0 0;
   background:none;
   }
   .aply-lv a {
    padding: 8px 14px;
}
</style>
<div class="content">
   <!-- Animated -->
   <div class="animated fadeIn">
      <div class="row" style="border:none;">
         <div class="col-md-6">
            <h5 class="card-title">ROL Master</h5>
         </div>
         <div class="col-md-6">
            <span class="right-brd" style="padding-right:15x;">
               <ul class="">
                  <li><a href="#">Stock</a></li>
                  <li>/</li>
                  <li class="active">ROL Master</li>
               </ul>
            </span>
         </div>
      </div>
      <!-- Widgets  -->
      <div class="row">
         <div class="main-card">
            <div class="card">
               <div class="card-header pb-3">
                  <div class="aply-lv">
                  <button type="button" class="btn btn-primary mx-1" title="Import Rol Details" style="float:right" data-toggle="modal" data-target="#exampleModal1">
                            Import Rol
                     </button>
                  <!-- <a href="{{ url('stock/add-rol') }}" class="btn btn-default" style="float:right;">Import Rol <i class="fa fa-plus"></i></a> -->
                     <a href="{{ url('stock/add-rol') }}" class="btn btn-primary" style="float:right;">Add ROL <i class="fa fa-plus"></i></a>
                     @if(count($sales_rs)>0)
                     <form  method="post" action="{{ url('xls-export-employees') }}" enctype="multipart/form-data" >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!-- <button data-toggle="tooltip" data-placement="bottom" title="Download Excel" class="btn btn-default" style="background:none !important;padding: 10px 15px;margin-top: -30px;float:right;margin-right: 15px;" type="submit"><img  style="width: 35px;" src="{{ asset('img/excel-dnld.png')}}"></button> -->
                     </form>
                     @endif
                  </div>
               </div>
               @include('include.messages')
              
               <br />
               <div class="clear-fix">
               <div class="col-md-3 text-right">
                        <div class="form-group">
                            <label for="search" class="sr-only">Search</label>
                            <input type="text" class="form-control" id="search" placeholder="Search" oninput="searchFunction()">
                        </div>
                        </div>
                  <table id="bootstrap-data-table2" class="table table-striped table-bordered">
                     <thead>
                        <tr>
                           <th>Sl No.</th>
                           <th>Sku</th>
                           <th>Effective From</th>
                           <th>Effective To  </th>
                           <th>Quantity </th>
                        </tr>
                     </thead>
                     <tbody id="stocktable">
                        @foreach($sales_rs as $item)
                        <tr>
                           <td>{{ $loop->iteration}}</td>
                           <td>{{ $item['sku']}}</td>
                           <td>{{ $item['effective_from']}}</td>
                           <td>{{ $item['effective_to'] }}</td>
                           <td>{{ $item['quantity'] }}</td>
                        </tr>
                        @endforeach
                     </tbody>
                     <tbody id="stockbed"></tbody>
                  </table>
                  {!! $sales_rs->withPath('rol')->links() !!}
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>

       <!-- Modal -->
       <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
						<div class="modal-dialog" role="document">
						  <form style='padding: 0px;' action="{{url('add-rol')}}" method="post" enctype="multipart/form-data">
							  @csrf
							  <div class="modal-content">
								<div class="modal-body">
								  
									<div class="form-group">
									  <label for="excel_file">Upload Rol Details CSV</label>
									  <input type="file" id="upload_csv" name="rol_csv" required class="form-control-file">
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
                 <script>
  function searchFunction() {
    var value = $("#search").val();
    
    $.ajax({
        url: "{{url('rol/get-rol-value')}}" + '/' + value,
        type: "GET",
        success: function (response) {
            // Clear existing rows in the table
            $("#stocktable").hide();
            $("#stockbed").empty();

            // Loop through the response and append rows to the table
            response.forEach(function (item) {
                $("#stockbed").append(`
                    <tr>
                           <td>${item.id}</td>
                           <td>${item.sku}</td>
                           <td>${item.effective_from}</td>
                           <td>${item.effective_to}</td>
                           <td>${item.quantity}</td>
                    </tr>
                `);
            });
        },
        error: function (error) {
            console.error("Error in AJAX request:", error);
        }
    });
}
</script>
@endsection
