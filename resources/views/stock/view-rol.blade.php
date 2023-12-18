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
               <div class="card-header">
                  <div class="aply-lv">
                     <a href="{{ url('stock/add-rol') }}" class="btn btn-default" style="float:right;">Add ROL <i class="fa fa-plus"></i></a>
                     @if(count($sales_rs)>0)
                     <form  method="post" action="{{ url('xls-export-employees') }}" enctype="multipart/form-data" >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button data-toggle="tooltip" data-placement="bottom" title="Download Excel" class="btn btn-default" style="background:none !important;padding: 10px 15px;margin-top: -30px;float:right;margin-right: 15px;" type="submit"><img  style="width: 35px;" src="{{ asset('img/excel-dnld.png')}}"></button>
                     </form>
                     @endif
                  </div>
               </div>
               @include('include.messages')
               {{--
               <div class="aply-lv">
                  <a href="{{ url('add-employee') }}" class="btn btn-default">Add Employee Master <i class="fa fa-plus"></i></a>
               </div>
               --}}
               <br />
               <div class="clear-fix">
                  <table id="bootstrap-data-table" class="table table-striped table-bordered">
                     <thead>
                        <tr>
                           <th>Sl No.</th>
                           <th>Sku</th>
                           <th>Effective To</th>
                           <th>Effective From  </th>
                           <th>styleCode</th>
                           <th>Brand </th>
                           <th>Quantity </th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($sales_rs as $item)
                        <tr>
                           <td>{{ $loop->iteration}}</td>
                           <td>{{ $item->sku}}</td>
                           <td>{{ $item->effective_to }}</td>
                           <td>{{ $item->effective_from }}</td>
                           <td>{{ $item->styleCode}}</td>
                           <td>{{ $item->brand }}</td>
                           <td>{{ $item->quantity }}</td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
@endsection
