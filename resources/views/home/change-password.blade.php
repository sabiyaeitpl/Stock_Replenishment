@include('layouts.default')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">


        @include('include.header')

        @include('include.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">

                        <div class="col-sm-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.html">Home</a></li>

                                <li class="breadcrumb-item active">Change Password </li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->


            <!-- Main content -->
            <div class="container-fluid mt--6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card custom-card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-key"></i> Change Password</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                @include('include.messages')
                                <form action="{{url('save-change-password')}}" method="post">
                                {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Old Password</label>
                                                <input type="text" class="form-control" value="" id="exampleFormControlInput1" name="old_pass" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>New Password</label>
                                                <input type="text" class="form-control" value="" id="exampleFormControlInput1" name="new_pass" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Confirm Password</label>
                                                <input type="text" class="form-control" value="" id="exampleFormControlInput1" name="confirm_pass" required>
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <button class="btn btn-default" type="submit">Submit</button>
                                        </div>
                                    </div>


                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>



                </div>



            </div>
            <!-- /.content -->

        </div>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

 @include('include.script')


    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
                removeItemButton: true,
                maxItemCount: 5,
                searchResultLimit: 5,
                renderChoiceLimit: 5
            });


        });
    </script>

    <script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor');
    </script>
    <script>
        CKEDITOR.replace('editor2');
    </script>
    <script>
        CKEDITOR.replace('editor3');
    </script>
</body>

<!-- Mirrored from adminlte.io/themes/v3/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 24 Apr 2021 11:40:28 GMT -->

</html>