@extends('employee.layouts.master')
@section('title')
Employee Information System-Employees
@endsection
@section('sidebar')
@include('employee.partials.sidebar')
@endsection
@section('header')
@include('employee.partials.header')
@endsection
@section('content')
<style>
   ul#stepForm, ul#stepForm li {
   margin: 0;
   padding: 0;
   }
   ul#stepForm li {
   list-style: none outside none;
   }
   label{margin-top: 10px;}
   .help-inline-error{color:red;}
   .container1 {
   display: block;
   position: relative;
   padding-left: 27px;
   margin-bottom: 12px;
   cursor: pointer;
   font-size: 22px;
   -webkit-user-select: none;
   -moz-user-select: none;
   -ms-user-select: none;
   user-select: none;
   }
   .pay-strct .form-group{margin-bottom:0;}h3.ad {background: #1c9ac5;color: #fff;padding: 5px 10px;font-size: 23px;}
   .addi {padding: 15px;margin-bottom: 9px;}
   /* Hide the browser's default checkbox */
   .container1 input {
   position: absolute;
   opacity: 0;
   cursor: pointer;
   height: 0;
   width: 0;
   }
   /* Create a custom checkbox */
   .checkmark {
   position: absolute;
   top: 5px;
   left: 0;
   height: 15px;
   width: 15px;
   background-color: #d6d2d2;
   }
   /* On mouse-over, add a grey background color */
   .container1:hover input ~ .checkmark {
   background-color: #ccc;
   }
   /* When the checkbox is checked, add a blue background */
   .container1 input:checked ~ .checkmark {
   background-color: #1c9ac5;
   }
   /* Create the checkmark/indicator (hidden when not checked) */
   .checkmark:after {
   content: "";
   position: absolute;
   display: none;
   }
   /* Show the checkmark when checked */
   .container1 input:checked ~ .checkmark:after {
   display: block;
   }
   /* Style the checkmark/indicator */
   .container1 .checkmark:after {
   left: 5px;
   top: 2px;
   width: 5px;
   height: 10px;
   border: solid white;
   border-width: 0 3px 3px 0;
   -webkit-transform: rotate(45deg);
   -ms-transform: rotate(45deg);
   transform: rotate(45deg);
   }
</style>
<!-- Content -->
<div class="content">
   <!-- Animated -->
   <div class="animated fadeIn">
      <div class="row" style="border:none;">
         <div class="col-md-6">
            <h5 class="card-title">Add Employee Master</h5>
         </div>
         <div class="col-md-6">
            <span class="right-brd" style="padding-right:15x;">
               <ul class="">
                  <li><a href="#">Employee</a></li>
                  <li>/</li>
                  <li><a href="#">Employee Master</a></li>
                  <li>/</li>
                  <li class="active">Add Employee Master</li>
               </ul>
            </span>
         </div>
      </div>
      <!-- Widgets  -->
      <div class="row">
         <div class="main-card">
            <div class="card">
               <!-- <div class="card-header"> <strong>Employee Master</strong> </div> -->
               <!-- @if(Session::has('message'))
                  <div class="alert alert-success" style="text-align:center;"><span class="glyphicon glyphicon-ok" ></span><em ><i class="fa fa-check-square-o"></i> {{ Session::get('message') }}</em></div>
                  @endif -->
               @include('include.messages')
               <div class="card-body card-block">
                  <div class="panel panel-primary">
                     <div class="panel-body">
                       
                     </div>
                  </div>
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
<div class="clearfix"></div>
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/lib/data-table/datatables.min.js') }}"></script>
<script src="{{ asset('js/lib/data-table/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/lib/data-table/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/lib/data-table/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/lib/data-table/jszip.min.js') }}"></script>
<script src="{{ asset('js/lib/data-table/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/lib/data-table/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/lib/data-table/buttons.print.min.js') }}"></script>
<script src="{{ asset('js/lib/data-table/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('js/init/datatables-init.js') }}"></script>
<script type="text/javascript">
   $(document).ready(function() {
   $('#bootstrap-data-table-export').DataTable();
   });
</script>
<script type="text/javascript" src="{{ asset('js/jquery.validate.js') }}"></script>
<script type="text/javascript">
   jQuery().ready(function() {
   
   
   
   $('#parmenent_country').val("INDIA");
   $('#emp_ps_country').val("INDIA");
   
   //var myLength = $("#parmenent_pincode").val().length;
   jQuery('#parmenent_pincode').change(function () {
   		var parmenent_pincode =  $("#parmenent_pincode").val();
   	var parmenent_pincode_length =  $("#parmenent_pincode").val().length;
     	if($.isNumeric(parmenent_pincode)!=true)
    {
    	$("#parmenent_pincode").val("");
   	alert("Value is not Numeric");
    }
    if(parmenent_pincode_length!=6)
    {
    	$("#parmenent_pincode").val("");
   	alert("Pincode should be six digit");
    }
     	//alert(parmenent_pincode);
   });
   
   jQuery('#present_pincode').change(function () {
   		var present_pincode =  $("#present_pincode").val();
   	    var present_pincode_length =  $("#present_pincode").val().length;
     	if($.isNumeric(present_pincode)!=true)
    {
    	$("#present_pincode").val("");
   	alert("Value is not Numeric");
    }
    if(present_pincode_length!=6)
    {
    	$("#present_pincode").val("");
   	alert("Pincode should be six digit");
    }
     	//alert(parmenent_pincode);
   });
   
   
   
   
     showHideDiv();
   
   setbasicpay();
   populateBranch();
   
   var select_basic_id = "<?php if (request()->get('q') != '') {echo $employee_rs[0]->basic_pay;}?>";
   var select_branch_id = "<?php if (request()->get('q') != '') {echo $employee_rs[0]->bank_branch_id;}?>";
   
   setTimeout(function(){
       if(select_basic_id!=""){
   $("#emp_basic_pay option[value='"+select_basic_id+"']").prop('selected', true);
    }
   
    if(select_branch_id!=""){
   $("#bank_branch_id option[value='"+select_branch_id+"']").prop('selected', true);
    }
     },1000);
   
   
   
   jQuery('#fname').keyup(function () {
     	this.value = this.value.replace(/[^a-zA-Z]/g,'');
   });
   
   jQuery('#emp_father_name').change(function () {
     	this.value = this.value.replace(/[^a-zA-Z\s]/g,'');
   });
   
   jQuery('#parmenent_mobile').change(function () {
     	this.value = this.value.replace(/[^0-9\.]/g,'');
     	var parmenent_mobile_length =  $("#parmenent_mobile").val().length;
     	if(parmenent_mobile_length!=10)
    {
    	$("#parmenent_mobile").val("");
   	alert("Phone No. should be ten digit");
    }
   });
   
   
   jQuery('#emp_ps_mobile').change(function () {
     	this.value = this.value.replace(/[^0-9\.]/g,'');
     	var emp_ps_mobile_length =  $("#emp_ps_mobile").val().length;
     	if(emp_ps_mobile_length!=10)
    {
    	$("#emp_ps_mobile").val("");
   	alert("phone should be ten digit");
    }
   });
   
   
     // validate form on keyup and submit
     var v = jQuery("#basicform").validate({
       rules: {
         uname: {
           required: false,
           minlength: 2,
           maxlength: 16
         },
         uemail: {
           required: false,
           minlength: 2,
           email: true,
           maxlength: 100,
         },
         upass1: {
           required: false,
           minlength: 6,
           maxlength: 15,
         },
         upass2: {
           required: false,
           minlength: 6,
           equalTo: "#upass1",
         }
   
       },
       errorElement: "span",
       errorClass: "help-inline-error",
     });
   
     $(".open1").click(function() {
       if (v.form()) {
         $(".frm").hide("fast");
         $("#sf2").show("slow");
       }
     });
   
     $(".open2").click(function() {
       if (v.form()) {
         $(".frm").hide("fast");
         $("#sf3").show("slow");
       }
     });
   $(".open3").click(function() {
       if (v.form()) {
         $(".frm").hide("fast");
         $("#sf4").show("slow");
       }
     });
   $(".open4").click(function() {
       if (v.form()) {
         $(".frm").hide("fast");
         $("#sf5").show("slow");
       }
     });
   $(".open5").click(function() {
       if (v.form()) {
         $(".frm").hide("fast");
         $("#sf6").show("slow");
       }
     });
   
     $(".open6").click(function() {
       if (v.form()) {
         $("#loader").show();
          setTimeout(function(){
            $("#basicform").html('<h2>Employee Added Successfully</h2>');
          }, 1000);
         return false;
       }
     });
   
     $(".back2").click(function() {
       $(".frm").hide("fast");
       $("#sf1").show("slow");
     });
   
     $(".back3").click(function() {
       $(".frm").hide("fast");
       $("#sf2").show("slow");
     });
   $(".back4").click(function() {
       $(".frm").hide("fast");
       $("#sf3").show("slow");
     });
   $(".back5").click(function() {
       $(".frm").hide("fast");
       $("#sf4").show("slow");
     });
   $(".back6").click(function() {
       $(".frm").hide("fast");
       $("#sf5").show("slow");
     });
   
   });
</script>
<script>
   function getGrades(company_id)
   {
   	$.ajax({
   		type:'GET',
   		url:'{{url('attendance/get-grades')}}/'+company_id,
   		success: function(response){
   		console.log(response);
   
   		$("#grade_id").html(response);
   
   		}
   
   	});
   }
   
   function calculateDor(){
   	var emp_dob = $("#emp_dob").val();
      	var dateOfBirth = new Date(emp_dob);
   
      	var sixty_years_ago = new Date(dateOfBirth.getFullYear()+60,dateOfBirth.getMonth(),dateOfBirth.getDate());
   
      	if(dateOfBirth.getDate()==1 && sixty_years_ago.getMonth()==0){
      		var lastdate = new Date(sixty_years_ago.getFullYear(), (sixty_years_ago.getMonth()+1), 0).getDate();
      	   var lastDayWithSlashes = lastdate + '/' + '12' + '/' + (sixty_years_ago.getFullYear()-1);
   
      	}else if(dateOfBirth.getDate()==1 && sixty_years_ago.getMonth()>0){
      		var lastdate = new Date(sixty_years_ago.getFullYear(), (sixty_years_ago.getMonth()), 0).getDate();
      		var lastDayWithSlashes = lastdate + '/' + (sixty_years_ago.getMonth()) + '/' + sixty_years_ago.getFullYear();
   
      	}else{
      		var lastdate = new Date(sixty_years_ago.getFullYear(), (sixty_years_ago.getMonth()+1), 0).getDate();
      		var lastDayWithSlashes = lastdate +'/' + (sixty_years_ago.getMonth()+1) + '/' + sixty_years_ago.getFullYear();
      	}
   
       $("#emp_retirement_date").val(lastDayWithSlashes);
   }
   
   
   
   function calculateDateOfIncrement(){
   	var emp_doj = $("#emp_doj").val();
   	var dateOfJoining= new Date(emp_doj);
   	var joingMonth=dateOfJoining.getMonth()+ 1;
   
   	if(dateOfJoining.getDate()==1 && joingMonth==1){
   		var nextIncrementDate = '01' + '/' + '07' + '/' + dateOfJoining.getFullYear();
   
   	}else if(dateOfJoining.getDate()>=1 && (joingMonth>=1 && joingMonth<=6)){
   		var nextIncrementDate = '01' + '/' + '01' + '/' + (dateOfJoining.getFullYear() +1);
   
   	}else if(dateOfJoining.getDate()==1 && joingMonth==7){
   
   		var nextIncrementDate = '01' + '/' + '01' + '/' + (dateOfJoining.getFullYear() +1);
   	}else {
   
   		var nextIncrementDate = '01' + '/' + '07' + '/' + (dateOfJoining.getFullYear() +1);
   	}
   
       $("#emp_next_increment_date").val(nextIncrementDate);
   
   }
   
   
   
   function setbasicpay(){
   	var emp_payscale_id = $("#emp_payscale option:selected" ).val();
   
   	$.ajax({
   		type:'GET',
   		url:'{{url('attendance/get-employee-scale')}}/'+emp_payscale_id,
   		success: function(response){
   			if(response.length>0){
   				var option = '';
   			for (var i=0;i<response.length;i++){
   			   option += '<option value="'+ response[i].pay_scale_basic + '">' + response[i].pay_scale_basic + '</option>';
   			}
   			$('#emp_basic_pay').html(option);
   
   			}
   
   		}
   	});
   }
   
   
   function populateBranch(){
   
   	var emp_bank_id = $("#emp_bank_name option:selected" ).val();
   
   	$.ajax({
   		type:'GET',
   		url:'{{url('attendance/get-employee-bank')}}/'+emp_bank_id,
   		success: function(response){
   			console.log(response);
   			var option = '';
   			for (var i=0;i<response.length;i++){
   			   option += '<option value="'+ response[i].id + '">' + response[i].branch_name + '</option>';
   			}
   			$('#bank_branch_id').html(option);
   		}
   	});
   }
   
   
   
   
   
   function getIfcs(){
   
   	var emp_branch_id= $("#bank_branch_id option:selected" ).val();
   
   	$.ajax({
   		type:'GET',
   		url:'{{url('attendance/get-employee-bank-ifsc-code')}}/'+emp_branch_id,
   		success: function(response){
   			console.log(response);
   				var obj = jQuery.parseJSON(response);
   				$("#emp_ifsc_code" ).val(obj.ifsc_code);
   		}
   	});
   }
   
   
</script>
<script>
   function getEmployeeType(company_id)
   {
   	//alert(company_id);
   	$.ajax({
   		type:'GET',
   		url:'{{url('attendance/get-employee-type')}}/'+company_id,
   		success: function(response){
   		console.log(response);
   
   		$("#employee_type_id").html(response);
   
   		}
   
   	});
   }
</script>
<script>
   function getDesignation(company_id)
   {
   	//alert(company_id);
   	$.ajax({
   		type:'GET',
   		url:'{{url('attendance/get-designation')}}/'+company_id,
   		success: function(response){
   		console.log(response);
   
   		$("#designation_id").html(response);
   
   		}
   
   	});
   }
</script>
<script>
   function getHeadNames(grade_id)
   {
   	var company_id=$("#company_id option:selected").val();
   	//alert("Company"+company_id);
   	//alert("Grade"+grade_id);
   	$.ajax({
   		type:'GET',
   		url:'{{url('attendance/get-head-names')}}/'+company_id+'/'+grade_id,
   		success: function(response){
   		console.log(response);
   
   		$("#head").html(response);
   
   		}
   
   	});
   }
</script>
<script type="text/javascript">
   function chckaddress() {
   	var ischecked=$('#diffaddrress').is(":checked");
   	//alert(ischecked);
   	var permanent_street_no=$("#permanent_street_no").val();
   	var permanent_city=$("#permanent_city").val();
   	var permanent_state=$("#permanent_state").val();
   	var permanent_country=$("#parmenent_country").val();
   	var permanent_pin=$("#permanent_pin").val();
   	var parmenent_mobile=$("#parmenent_mobile").val();
   
   	if(ischecked)
   	{
   		$("#present_street_no").val(permanent_street_no);
   		$("#present_city").val(permanent_city);
   		$("#present_state").val(permanent_state);
   		$("#emp_ps_country").val(permanent_country);
   		$("#present_pin").val(permanent_pin);
   		$("#present_mobile").val(parmenent_mobile);
   
   
   	}
   	else
   	{
   		$("#present_street_no").val('');
   		$("#present_city").val('');
   		$("#present_state").val('');
   		$("#present_country").val('');
   		$("#present_pin").val('');
   		$("#present_mobile").val('');
   	}
   
   
   
   }
   
</script>
<script src="{{ asset('js/jquery.autosuggest.js') }}"></script>
<script>
   var reporting_person='';
   var persons= reporting_person;
   //alert("Suggest"+persons);
   $("#reporting_person").autosuggest({
   			sugggestionsArray: persons
   		});
</script>
<script>
   function getReportingPerson(val)
   {
   	alert(val);
   	//var reporting_person= encodeURIComponent(val);
   	//window.location = 'payment_receive.php?job_work_no='+reporting_person;\
   	$.ajax({
   		type:'GET',
   		url:'{{url('attendance/get-reporting-names')}}',
   		success: function(response){
   		alert(response);
   		//var jqObj = jQuery.parseJSON(response);
   		//var jqObj =JSON.parse(response);
   		//var jqObj = $.parseJSON(response);
   		//console.log(jqObj.reporting_person);
   		//alert(jqObj);
   		$("#browsers").html(response);
   		//reporting_person= response;
   		//$("#reporting_person").val(jqObj.reporting_person);
   		}
   
   	});
   }
   
</script>
<script>
   function getBranches(company_id)
   {
   	//alert(company_id);
   	$.ajax({
   		type:'GET',
   		url:'{{url('pis/get-branches')}}/'+company_id,
   		success: function(response){
   		console.log(response);
   
   		$("#branch_id").html(response);
   
   		}
   
   	});
   }
</script>
<script>
   $(document).ready(function() {
    var i = 1;
     //alert('hii');
    $('#add').click(function() {
      // alert('hii');
      i++;
      $('#marksheet').append('<tr id="row' + i + '"><td>'+ i +'</td><td><input type="text" name="qualification[]" class="form-control"></td><td><input type="text" name="dicipline[]" class="form-control"></td> <td><input type="text" name="inst_name[]" class="form-control"></td><td><input type="text" name="board_name[]" class="form-control"></td><td><input type="text" name="pass_year[]" class="form-control"></td><td><input type="text" name="percentage[]" class="form-control"></td><td><input type="text" name="rank[]" class="form-control"></td><td style="width:150px;"><button type="button" id="' + i + '" class="btn btn-default pls btn_remove" ><i class="fa fa-minus"></i></button></td></tr>');
   
    });
   
   
    $(document).on('click', '.btn_remove', function() {
      var button_id = $(this).attr("id");
      $('#row' + button_id + '').remove();
    });
   
   
   
   
   
   });
   
                  
</script>
<!--<td><input type="text" name="nomination_name[]" class="form-control"></td>
   <td><input type="text" name="nomination_relation[]" class="form-control"></td>
   <td><input type="text" name="nomination_age[]" class="form-control"></td>
   <td style="width:150px;"><button id="addnomination" class="btn btn-default pls"><i class="fa fa-plus"></i></button><button class="btn btn-default pls"><i class="fa fa-minus"></i></button></td>
   -->
<script>
   $(document).ready(function() {
    var i = 1;
   
    $('#addnomination').click(function() {
   
      i++;
      $('#nomination').append('<tr id="rownominee' + i + '"><td>'+ i +'</td><td><input type="text" name="nomination_name[]" class="form-control"></td><td><input type="text" name="nomination_relation[]" class="form-control"></td> <td><input type="text" name="nomination_age[]" class="form-control"></td><td><button type="button" id="' + i + '" class="btn btn-default pls btn_remove_nominee"><i class="fa fa-minus"></i></button></td></tr>');
   
    });
   
   
    $(document).on('click', '.btn_remove_nominee', function() {
      var button_id = $(this).attr("id");
      $('#rownominee' + button_id + '').remove();
    });
   
   
   
   
   
   });
   
                  
</script>
<script>
   $(document).ready(function(){
         $("#filladdress").on("click", function(){
            if (this.checked)
            {
               $("#present_street_name").val($("#parmenent_street_name").val());
               $("#present_city").val($("#parmenent_city").val());
               $("#present_state").val($("#parmenent_state").val());
               $("#emp_ps_country").val($("#parmenent_country").val());
               $("#present_pincode").val($("#parmenent_pincode").val());
               $("#emp_ps_village").val($("#parmenent_village").val());
               $("#emp_ps_post_office").val($("#emp_per_post_office").val());
              	$("#emp_ps_dist").val($("#emp_per_dist").val());
              	$("#emp_ps_policestation").val($("#emp_per_policestation").val());
               $("#emp_ps_mobile").val($("#parmenent_mobile").val());
               $("#present_street_name").prop("readonly", true);
               $("#present_city").prop("readonly", true);
               $("#emp_ps_country").prop("readonly", true);
               $("#present_state").prop("readonly", true);
               $("#present_pincode").prop("readonly", true);
               $("#present_mobile").prop("readonly", true);
           }
           else
           {
               $("#present_street_name").val('');
               $("#present_city").val('');
               $("#present_country").val('');
               $("#present_state").val('');
               $("#present_pincode").val('');
               $("#present_mobile").val('');
               $("#present_street_name").prop("readonly", false);
               $("#present_city").prop("readonly", false);
               $("#present_country").prop("readonly", false);
               $("#present_state").prop("readonly", false);
               $("#present_pincode").prop("readonly", false);
               $("#present_mobile").prop("readonly", false);
       }
       });
   
       /*$(document).on('change','#emp_bank_name', function(e){
       	var ifsccode = $('#emp_bank_name option:selected').data('ifsccode');
       	$('#emp_ifsc_code').val(ifsccode);
   
       });*/
   
   
   
   
   });
           
</script>
<script>
   // function showHideDiv() {
   
   // 	var radioValue = $("input[name='emp_spouse_working']:checked").val();
      // 	if(radioValue=='Employee'){
      // 		$('#govt_emp').show();
   // 		$('#spouse_quarter').show();
      // 	}else{
      // 		$('#govt_emp').hide();
   // 		$('#spouse_quarter').hide();
   
      // 	}
   
   // }
</script>
<script>
   function showHideDiv() {
   
   	var radioValue = $("input[name='marital_status']:checked").val();
   	if(radioValue=='Yes'){
   		$('#marriage_date').show();
   	}else{
   		$('#marriage_date').hide();
   
   	}
   
   }
   
   
   function addnewrow(rowid)
   	{
   
   
   
   		if (rowid != ''){
   				$('#add'+rowid).attr('disabled',true);
   
   		}
   
   
   
   		$.ajax({
   
   				url:'{{url('settings/get-add-row-item')}}/'+rowid,
   				type: "GET",
   
   				success: function(response) {
   
   					$("#marksheet").append(response);
   
   				}
   			});
   	}
   
   
   	function delRow(rowid)
   	{
   		var lastrow = $(".itemslot:last").attr("id");
           //alert(lastrow);
           var active_div = (lastrow-1);
           $('#add'+active_div).attr('disabled',false);
           $(document).on('click','.deleteButton',function() {
               $(this).closest("tr.itemslot").remove();
           });
   
   	}
   
    function checkdepart(emp_department){
   
   	   	$.ajax({
   		type:'GET',
   		url:'{{url('employee/department-name')}}/'+emp_department,
           cache: false,
   		success: function(response){
   
   
   			document.getElementById("emp_designation").innerHTML = response;
   		}
   		});
      }
       function checktext(val){
   
   		if ($("#check_"+val).is(":checked")==true) {
                         $('#name_' +val).show();
   					   $("#check_name_"+val).prop("required", true);
   
                       } else {
                          $('#name_'+val).hide();
   					     $("#check_name_"+val).prop("required", false);
   						  $("#check_name_"+val).val('');
   
                       }
   	}
   
   
      function addnewrowearn(rowid)
   	{
   
   
   
   		if (rowid != ''){
   				$('#addearn'+rowid).attr('disabled',true);
   
   		}
   
   
   
   		$.ajax({
   
   				url:'{{url('settings/get-add-row-earn')}}/'+rowid,
   				type: "GET",
   
   				success: function(response) {
   
   					$("#marksheetearn").append(response);
   
   				}
   			});
   	}
   
   
   	function delRowearn(rowid)
   	{
   		var lastrow = $(".itemslotpayearn:last").attr("id");
           //alert(lastrow);
           var active_div = (lastrow);
           $('#addearn'+active_div).attr('disabled',false);
           $(document).on('click','.deleteButtonearn',function() {
               $(this).closest("tr.itemslotpayearn").remove();
           });
   
   	}
   
   
   	function addnewrowdeduct(rowid)
   	{
   
   
   
   		if (rowid != ''){
   				$('#adddeduct'+rowid).attr('disabled',true);
   
   		}
   
   
   
   		$.ajax({
   
   				url:'{{url('settings/get-add-row-deduct')}}/'+rowid,
   				type: "GET",
   
   				success: function(response) {
   
   					$("#marksheetdeduct").append(response);
   
   				}
   			});
   	}
   
   
   	function delRowdeduct(rowid)
   	{
   		var lastrow = $(".itemslotpaydeduct:last").attr("id");
           //alert(lastrow);
           var active_div = (lastrow);
           $('#adddeduct'+active_div).attr('disabled',false);
           $(document).on('click','.deleteButtondeduct',function() {
               $(this).closest("tr.itemslotpaydeduct").remove();
           });
   
   	}
   
   	function checkearnvalue(val,row)
   
   	{
   		var emp_basic_pay=$('#emp_basic_pay').val();
   	var headname=$('#name_earn'+row).val();
   
   	$.ajax({
   
   				url:'{{url('settings/get-earn')}}/'+headname+'/'+val+'/'+emp_basic_pay,
   				type: "GET",
   
   				success: function(response) {
                         if(val=='F'){
   					$("#value"+row).val(Math.round(response));
   					  $("#value"+row).prop("readonly", true);
   					  }else{
   						 $("#value"+row).val('0');
   						   $("#value"+row).prop("readonly", false);
   					  }
   
   				}
   			});
   
   
   	}
   
   	function checkdeductvalue(val,row)
   
   	{
   		var emp_basic_pay=$('#emp_basic_pay').val();
   	var headname=$('#name_deduct'+row).val();
   
   	$.ajax({
   
   				url:'{{url('settings/get-earn')}}/'+headname+'/'+val+'/'+emp_basic_pay,
   				type: "GET",
   
   				success: function(response) {
                         if(val=='F'){
   					$("#valuededuct"+row).val(Math.round(response));
   					  $("#valuededuct"+row).prop("readonly", true);
   					  }else{
   						 $("#valuededuct"+row).val('0');
   						   $("#valuededuct"+row).prop("readonly", false);
   					  }
   
   				}
   			});
   
   
   	}
   		function onlyUnique(value, index, self) {
     return self.indexOf(value) === index;
   }
   
   	function checkearninghead()
   
   	{
   		var ernclsarr= document.getElementsByClassName("earninigcls");
   		var earningarray = new Array();
   		 for(i=0;i<ernclsarr.length;i++) {
   			 var headname=$('#name_earn'+i).val();
   			 earningarray[i]=headname;
   
   		 }
   		 var unique = earningarray.filter(onlyUnique);
   
   		 var deductclsarr= document.getElementsByClassName("deductcls");
   		var deductarray = new Array();
   		 for(i=0;i<deductclsarr.length;i++) {
   			 var headname=$('#name_deduct'+i).val();
   			 deductarray[i]=headname;
   
   		 }
   		 var uniquededuct = deductarray.filter(onlyUnique);
   
   
   
   		 if(ernclsarr.length!=unique.length){
   			 alert("Same Earning Selected Multiple Times");
   		 }
   		  if(deductclsarr.length!=uniquededuct.length){
   			 alert("Same Deduct Selected Multiple Times");
   		 }
   
   		 if(ernclsarr.length==unique.length && deductclsarr.length==uniquededuct.length ) {
   			  document.getElementById("basicform").submit();
   		 }
   
   
   
   		}
   
</script>
@endsection