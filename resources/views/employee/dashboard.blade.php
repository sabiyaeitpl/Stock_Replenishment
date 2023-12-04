@extends('employee.layouts.master')

@section('title')
Employee Dashboard
@endsection

@section('sidebar')
	@include('employee.partials.sidebar')
@endsection

@section('header')
	@include('employee.partials.header')
@endsection



@section('scripts')
	@include('employee.partials.scripts')
@endsection

@section('content')
<style>
.dash-mar h2 {background: linear-gradient(to right, #28ca8e 30% , #0aa3de);color: #fff;padding: 5px 10px;margin-top: 15px;font-size: 23px;}
.single-chart {width: 40%;justify-content: space-around ;}
.circular-chart {display: block;margin: 10px auto 0;max-width: 80%;max-height: 250px;float:left;}
.circle-bg {fill: none;stroke: #eee;stroke-width: 3.8;}
.circle {fill: none;stroke-width: 4;stroke-linecap: round;animation: progress 4s ease-out forwards;}
@keyframes progress {0% {stroke-dasharray: 0 100;}}
.circular-chart.orange .circle {stroke: #ff9f00;}
.circular-chart.green .circle {stroke: #9e5df7;}
.circular-chart.blue .circle {stroke: #a203a7;}
.circular-chart.grn .circle {stroke: green;}
.circular-chart.red .circle {stroke: #ea3009;}
.circular-chart.sky .circle {stroke: #06bce2;}
.percentage {fill: #666;font-family: sans-serif;font-size: 0.5em;text-anchor: middle;}
.dib{margin-top:15px;}
#chart{width: 700px;height: 450px;margin: 0 auto;border: 1px solid #000;}

table tr th{
    color: #c75b0b;
	 font-weight: normal;
	 padding: 10px 20px;
}
table tr td{
padding: 10px 20px !important;
    font-size: 13px;
}
td{vertical-align:middle !important;}
td.wish{vertical-align:middle;}
.wish a {
    background: #ef0c34;
    color: #fff;
    padding: 7px 10px;
    border-radius: 4px;
    border: 2px solid #ef0c34;
    transition: 1s;
}
.wish:hover a{color:#ef0c34;background:transparent;}
.incre a {
    background: #099a29;
    color: #fff;
    padding: 7px 13px;
    font-size: 14px;
    border-radius: 4px;
	border:2px solid #099a29;transition:1s;
}
.incre:hover a{color:#099a29;background:transparent;}
table tr:nth-child(even) {background: #ecf6f9;}
table tr th{padding: 10px 20px !important;}
table tr:last-child{border-bottom:none;}
/***********************blink******************/
/* Firefox old*/
@-moz-keyframes blink {
    0% {
        opacity:1;
    }
    50% {
        opacity:0;
    }
    100% {
        opacity:1;
    }
} 

@-webkit-keyframes blink {
    0% {
        opacity:1;
    }
    50% {
        opacity:0;
    }
    100% {
        opacity:1;
    }
}
/* IE */
@-ms-keyframes blink {
    0% {
        opacity:1;
    }
    50% {
        opacity:0;
    }
    100% {
        opacity:1;
    }
} 
/* Opera and prob css3 final iteration */
@keyframes blink {
    0% {
        opacity:1;
    }
    50% {
        opacity:0;
    }
    100% {
        opacity:1;
    }
} 
.blink-image {
    -moz-animation: blink normal 1s infinite ease-in-out; /* Firefox */
    -webkit-animation: blink normal 1s infinite ease-in-out; /* Webkit */
    -ms-animation: blink normal 1s infinite ease-in-out; /* IE */
    animation: blink normal 1s infinite ease-in-out;
	    width: 37px;
}
.birthday tr:nth-child(even) {
    background: #f7f2f7 !important;
}
/**************************************/
</style>
 <!-- Content -->
        <div class="content">

        	<div class="animated fadeIn">
      <!-- privious month detail  -->
      <div class="dash-mar">
	  
	  <div class="row">
	 
       </div> 
      </div>
      <!-- /privious month detail -->
	  
	 <!----birthday-reminder--------------> 
	
           
		</div>
        <!-- /.content -->
        <div class="clearfix"></div>
       


@endsection
