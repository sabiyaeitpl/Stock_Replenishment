@extends('stock.layouts.master')

@section('title')
Stock
@endsection

 @section('sidebar')
	@include('stock.partials.sidebar')
@endsection

 @section('header')
	@include('stock.partials.header')
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
                                <form action="{{ url('stock/add-rol') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="shop_id" class="form-control-label">Stock <span>(*)</span></label>
                                            <select name="shop_id" id="shop_id" class="form-control employee select2_el" onchange="getSkuCodes()" required>
                                                <option value="">Select Store</option>
                                                @if(isset($shops) && count($shops) > 0)
                                                    @foreach($shops as $shop)
                                                        <option value="{{ $shop->storeid }}">{{ $shop->name }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">No Store Available</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col col-md-3">
                                            <label for="sku_code" class="form-control-label">Bar Code <span>(*)</span></label>
                                            <select class="form-control employee select2_el" name="sku_code" id="sku_code" disabled onchange="getData()" required>
                                                <!-- Options will be filled dynamically using JavaScript -->
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label class="form-control-label">Effective From <span>(*)</span></label>
                                            <input type="date" name="effective_form" class="form-control" required>
                                        </div>
                                        <div class="col col-md-2">
                                            <label class="form-control-label">Effective To <span>(*)</span></label>
                                            <input type="date" name="effective_to" class="form-control" required>
                                        </div>
                                        <div class="col col-md-2">
                                            <label class="form-control-label">Set Quantity <span>(*)</span></label>
                                            <input type="number" name="quantity" class="form-control" required>
                                        </div>
                                    </div>

                                    <div id="data-container" class="form-group">
                                        <!-- This is where your dynamically added data will go -->
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-12">
                                            <button type="submit" class="btn btn-danger btn-sm">Submit</button>
                                            <button type="reset" class="btn btn-danger btn-sm">
                                                <i class="fa fa-ban"></i> Reset
                                            </button>
                                            <p>(*) marked fields are mandatory</p>
                                        </div>
                                    </div>
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
@section('scripts')
@include('stock.partials.scripts')
<script src="{{ asset('js/select2.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
      $(document).ready(function() {
        initailizeSelect2();
    });
    // Initialize select2
    function initailizeSelect2() {

        $(".select2_el").select2();
    }

    function select2_reset(){
        $(".select2_el").val(null).trigger('change');
    }
    function getSkuCodes() {
        var shopId = document.getElementById('shop_id').value;

        axios.post('{{ url("/get-sku-codes") }}', { shop_id: shopId })
            .then(function (response) {
                var selectElement = document.getElementById('sku_code');
                selectElement.innerHTML = '<option value="" selected disabled>Select SKU Code</option>';

                response.data.forEach(function (skuCode) {
                    var option = document.createElement('option');
                    option.value = skuCode;
                    option.text = skuCode;
                    selectElement.add(option);
                });

                selectElement.disabled = false;
                document.querySelector('[onclick="getData()"]').disabled = true;
            })
            .catch(function (error) {
                console.error(error);
            });
    }
    function getData() {
    var skuCode = document.getElementById('sku_code').value;

    axios.post('{{ url("/get-data") }}', { sku_code: skuCode })
        .then(function (response) {
            var dataContainer = document.getElementById('data-container');
            dataContainer.innerHTML = ''; // Clear previous data

            if (response.data.length > 0) {
                response.data.forEach(function (item) {
                    // Create a new row container
                    var row = document.createElement('div');
                    row.className = 'row';

                    // Define the properties to display
                    var properties = ['division', 'section', 'department', 'category_1', 'category_2', 'category_3', 'category_4', 'stock_amount'];

                    properties.forEach(function (property) {
                        // Create a column container
                        var col = document.createElement('div');
                        col.className = 'col col-md-3';

                        // Create label and input elements
                        var label = document.createElement('label');
                        label.className = 'form-control-label';
                        label.textContent = property.replace('_', '-').toUpperCase() + ':';

                        var input = document.createElement('input');
                        input.type = 'text';
                        input.className = 'form-control';
                        input.value = item[property] || '';
                        input.readOnly = true;
                        input.name = property.toLowerCase();

                        // Append label and input to the column container
                        col.appendChild(label);
                        col.appendChild(input);

                        // Append the column to the row
                        row.appendChild(col);
                    });

                    // Create a horizontal line
                    var hr = document.createElement('hr');

                    // Append the row and horizontal line to the data container
                    dataContainer.appendChild(row);
                    dataContainer.appendChild(hr);
                });
            } else {
                dataContainer.innerHTML = '<p>No data available</p>';
            }

            document.querySelector('[onclick="getData()"]').disabled = false;

            document.getElementById('selected_shop_id').value = document.getElementById('shop_id').value;
            document.getElementById('selected_sku_code').value = skuCode;
        })
        .catch(function (error) {
            console.error(error);
        });
}



</script>

@endsection
