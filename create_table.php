<?php
session_start();
$_SESSION['page'] = 'Create table';
if(empty($_SESSION))
{
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<head>
    <?php include './layout/head.php';?>
</head>

<body>
<?php include './layout/sidebar.php';?>
<!-- Main content -->
<div class="main-content" id="panel">
    <!-- Topnav -->
    <?php include './layout/topnav.php';?>
    <!-- Header -->
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Create Table</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create Table</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        <h3 class="mb-0">New Table</h3>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <form class="form-horizontal" method="post">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols1Input">Table Name</label>
                                <input type="text" id="tabel_name" name="tabel_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <div id="fb-editor"></div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">

                        <div class="form-group text-center">
                            <button type="button" id="submit" class="btn btn-success">Save Table</button>
                            <button type="button" id="clear" class="btn btn-info">Clear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php include './layout/footer.php';?>

        <script>
            jQuery(function ($) {
                var fbEditor = document.getElementById('fb-editor');
                var options = {
                    disableFields: [
                        'file',
                        'header',
                        'hidden',
                        'button',
                        'paragraph'
                    ],
                    disabledActionButtons: ['data','save','clear']
                };

                var formBuilder = $(fbEditor).formBuilder(options);

                $(document).on('click','#clear',function () {
                    formBuilder.actions.removeField();
                })

                $(document).on('click', '#submit', function () {
                    var tableData = [];
                    var tabelName = $('#tabel_name').val();
                    if (tabelName == '') {
                        $('#tabel_name').focus();
                        demo.showSwal('error-message', 'Table name field is required!');
                    } else {
                        var formData = formBuilder.actions.getData('json');
                        tableData.push(tabelName);
                        tableData.push(formData);
                        $.ajax({
                            type: "POST",
                            url: "table_data.php",
                            dataType: 'json',
                            data: {json: JSON.stringify(tableData)},
                            success: function (data) {
                                if (data.code == 200) {
                                    swal({
                                        title: "Success",
                                        text: "Your table is successfully created!",
                                        type: "success",
                                        buttonsStyling: !1,
                                        confirmButtonClass: "btn btn-success"
                                    });
                                    setTimeout(function () {
                                        window.location = "list_table.php";
                                    }, 2000);

                                } else if (data.code == 401) {
                                    swal({
                                        title: "Oops",
                                        text: "Something went wrong!",
                                        type: "error",
                                        buttonsStyling: !1,
                                        confirmButtonClass: "btn btn-success"
                                    });
                                } else {
                                    swal({
                                        title: "Oops",
                                        text: "Your table is created fail!",
                                        type: "error",
                                        buttonsStyling: !1,
                                        confirmButtonClass: "btn btn-success"
                                    });
                                }
                            }
                        });
                    }

                });
            });
        </script>
</body>

</html>


