@extends('stock.layouts.master')

@section('title')
Stock
@endsection

 @section('sidebar')
	@include('stock.partials.sidebar')
@endsection -->

 @section('header')
	@include('stock.partials.header')
@endsection


@section('scripts')
	@include('stock.partials.scripts')
@endsection

@section('content')


        <!-- Content -->
        <div class="content">
            <!-- Animated -->
            <div class="animated fadeIn">
			<div class="content">
            <!-- Animated -->
            <div class="animated fadeIn">
            <div class="row" style="border:none;">
            <div class="col-md-6">
            <h5 class="card-title">Add/Edit ROL</h5>
</div>
<div class="col-md-6">

                           <span class="right-brd" style="padding-right:15x;">
                            <ul class="">
							<li><a href="#">Stock</a></li>
                                <li>/</li>
                                <li><a href="#">ROL List</a></li>
                                <li>/</li>
                                <li class="active">Add/Edit ROL</li>

                            </ul>
                        </span>
</div>
</div>

                <!-- Widgets  -->
                <div class="row">
                  <?php //print_r($holidaydtl); exit; ?>
                    <div class="main-card">
                        <div class="card">
                            <!-- <div class="card-header"><strong class="card-title">Add Holiday</strong></div> -->
                            <div class="card-body card-block">
                                <form action="{{ url('holiday/add-holiday') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                     <input type="hidden" name="id" value="">


                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="email-input" class=" form-control-label">From Date <span>(*)</span></label>
										<input type="date" id="from_date" required name="from_date" class="form-control" value="" required>
										@if ($errors->has('from_date'))
											<div class="error" style="color:red;">{{ $errors->first('from_date') }}</div>
										@endif
										</div>
										<div class="col col-md-3"><label for="email-input" class=" form-control-label">To Date <span>(*)</span></label>
										<input type="date" required id="to_date" name="to_date" class="form-control" value="" required>
										@if ($errors->has('to_date'))
											<div class="error" style="color:red;">{{ $errors->first('to_date') }}</div>
										@endif
										</div>
										<div class="col col-md-3"><label for="email-input" class=" form-control-label">Day </label>
											<select class="form-control" name="" required>
                                                <option value=""></option>
											</select>
											@if ($errors->has('weekname'))
												<div class="error" style="color:red;">{{ $errors->first('weekname') }}</div>
											@endif
										</div>
										<div class="col col-md-3"><label for="email-input" class=" form-control-label">Holiday Type </label>
											<select class="form-control" name="holiday_type">
												<option value="closed" <?php if(!empty($holidaydtl->holiday_type)){ if("closed"== $holidaydtl->holiday_type) { echo "selected"; } } ?>>Closed</option>
												<option value="restricted" <?php if(!empty($holidaydtl->holiday_type)){ if($holidaydtl->holiday_type == 'Restricted'){ echo "selected"; } } ?>>Restricted</option>


											</select>
											@if ($errors->has('holiday_type'))
												<div class="error" style="color:red;">{{ $errors->first('holiday_type') }}</div>
											@endif
										</div>
                                    <div class="col col-md-3"><label for="email-input" class=" form-control-label">No. of Days </label>
										<input type="text" id="day" required name="day" class="form-control" value="<?php  if(!empty($holidaydtl->day)){echo $holidaydtl->day;} ?>" required readonly>
										@if ($errors->has('day'))
											<div class="error" style="color:red;">{{ $errors->first('day') }}</div>
										@endif
										</div>
									</div>
									<div class="row form-group">
										<div class="col col-md-3">
											<label for="email-input" class="form-control-label">Holiday Description <span>(*)</span></label>
										<textarea rows="3"  class="form-control" id="holiday_descripion" name="holiday_descripion"><?php  if(!empty($holidaydtl->holiday_descripion)){echo $holidaydtl->holiday_descripion;} ?></textarea>
										@if ($errors->has('holiday_descripion'))
											<div class="error" style="color:red;">{{ $errors->first('holiday_descripion') }}</div>
										@endif
										</div>

                                    </div>




                                <button type="submit" class="btn btn-danger btn-sm">Submit</button>
                                <button type="reset" class="btn btn-danger btn-sm">
                                    <i class="fa fa-ban"></i> Reset
                                </button>
								<p>(*) marked fields are mandatory</p>

							</form>
							</div>
                        </div>




                    </div>

                    </div>



                </div>
                <!-- /Widgets -->



            </div>
            <!-- .animated -->
        </div>
        <!-- /.content -->
        @endsection
<script>function calculateDays(){
			var from_date= $("#from_date").val();
			const myArr = from_date.split("-");
			from_date=myArr[1]+'/'+myArr[2]+'/'+myArr[0];

			var to_date= $("#to_date").val();
			const myArrnew = to_date.split("-");
						to_date=myArrnew[1]+'/'+myArrnew[2]+'/'+myArrnew[0];

				var fromdate = new Date(from_date);
			var todate = new Date(to_date);
			var diffDays = (todate.getDate() - fromdate.getDate()) + 1 ;
			$("#day").val(diffDays);
		}</script>
@section('scripts')
@include('stock.partials.scripts')
<script>
		function getGrades(company_id)
		{
			//alert(company_id);
			$.ajax({
				type:'GET',
				url:'{{url('attendance/get-grades')}}/'+company_id,
				success: function(response){
				console.log(response);

				$("#grade_id").html(response);
				//var jqObj = jQuery.parseJSON(response);
					//alert(response);
					//var jqObj =JSON.parse(response);
					//var jqObj = response.map(JSON.parse)
				//var jqObj = jQuery(response);
				//alert(jqObj.emp_present_address);
				//$("#grade_id").val(jqObj.emp_name);
				//$("#address").val(jqObj.emp_present_address);
				}

			});
		}



	</script>
@endsection
