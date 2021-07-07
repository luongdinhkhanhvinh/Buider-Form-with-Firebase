<?php
session_start();
$_SESSION['page'] = 'List of table';
if(empty($_SESSION))
{
    header('Location: login.php');
}
include_once('conn.php');
$reference      = $database->getReference('tables');
$snapshot       = $reference->getSnapshot();
$result         = $snapshot->getValue();
$values_records = [];
$key_records = [];
if(!empty($result))
{
    $values_records = array_values($result);
    $key_records    = array_keys($result);
}
?>
<!DOCTYPE html>
<head>
    <?php include './layout/head.php';?>

    <link rel="stylesheet" href="./assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="./assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="./assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
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
                        <h6 class="h2 text-white d-inline-block mb-0">List of Table</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">List of Table</li>
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
                    <div class="card-header">
                        <div class="row"><div class="col-6">
                                <h4 class=" text-uppercase text-muted  font-weight-bold mt-3">Tables</h4>
                            </div>
                            <div class="col-6 align-middle d-flex justify-content-end">
                                <a href="./create_table.php" class=" btn btn-primary text-white btn-icon-only rounded-circle mt-1">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="table-responsive py-4">
                        <table class="table table-flush " id="custom-datatable" >
                            <thead class="thead-light">
                            <tr>
                                <th class="w-50">Name</th>
                                <th class="w-25">Submitted Records</th>
                                <th class="w-25 disabled-sorting text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($values_records as $key => $record)
                            {
                                ?>
                                <tr>

                                    <td><?= $record['name'] ?></td>
                                    <td><?= $record['count'] ?></td>
                                    <td class="text-right">
                                        <a href="view_table.php?id=<?= $key_records[$key] ?>" class="btn btn-sm btn-success btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="List">
                                            <i class="fa fa-list"></i>
                                        </a>

                                        <a href="form_view_table.php?id=<?= $key_records[$key] ?>" class="btn btn-sm btn-info btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="New">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                        <a href="edit_table.php?id=<?= $key_records[$key] ?>" class="btn btn-sm btn-primary btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="Edit">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <button  data-id="<?= $key_records[$key] ?>" class="btn btn-sm btn-danger btn-icon-only rounded-circle mt-1 text-white remove" data-toggle="tooltip"  title="Remove">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <?php include './layout/footer.php';?>

        <!-- Optional JS -->
        <script src="./assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="./assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="./assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="./assets/vendor/datatables.net-select/js/dataTables.select.min.js"></script>


        <script type="text/javascript">
            $(document).ready(function () {
                var DatatableBasic = (function() {
                    var $dtBasic = $('#custom-datatable');
                    function init($this) {
                        var options = {
                            keys: !0,
                            select: false,
                            language: {
                                paginate: {
                                    previous: "<i class='fas fa-angle-left'>",
                                    next: "<i class='fas fa-angle-right'>"
                                }
                            },
                        };
                        var table = $this.on('init.dt', function() {
                           // $('div.dataTables_length select').removeClass('custom-select custom-select-sm');

                        }).DataTable(options);
                    }
                    if ($dtBasic.length) {
                        init($dtBasic);
                    }
                })();


                $(document).on('click', '.remove', function (e) {
                    var id = $(this).data("id");
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        type: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#0CC27E",
                        cancelButtonColor: "#FF586B",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!",
                        confirmButtonClass: "btn btn-success mr-5",
                        cancelButtonClass: "btn btn-danger",
                        buttonsStyling: !1
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "table_form_data_delete.php",
                                dataType: 'json',
                                data: {id: id},
                                success: function (data) {
                                    if (data.code == 200) {
                                        swal({
                                            title: "Success",
                                            text: "Your table is successfully deleted",
                                            type: "success",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
                                        setTimeout(function () {
                                            window.location = "list_table.php";
                                        }, 2000);
                                    } else {
                                        swal({
                                            title: "Oops",
                                            text: "Your table is deleted fail!",
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
            });

        </script>
</body>

</html>


