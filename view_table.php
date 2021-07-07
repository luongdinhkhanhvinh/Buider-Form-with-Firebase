<?php
session_start();
$_SESSION['page'] = 'View table';
if(empty($_SESSION))
{
    header('Location: login.php');
}
?>
<?php
include_once('conn.php');

$id        = $_GET['id'];
$reference = $database->getReference('records/' . $id);
$snapshot  = $reference->getSnapshot();

$reference = $database->getReference('tables/' . $id.'/name');
$snapshot1  = $reference->getSnapshot();

$tableName = $snapshot1->getValue();
$table_result_val= [] ;
$table_result_key= [] ;
$result           = $snapshot->getValue();
if(!empty($result))
{
    $table_result_val = array_values($result);
    $table_result_key = array_keys($result);
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
                        <h6 class="h2 text-white d-inline-block mb-0"><?php echo $tableName;?></h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page"> <a href="list_table.php">List of Table</a> </li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $tableName;?></li>
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
                                <h4 class=" text-uppercase text-muted  font-weight-bold mt-3"><?php echo $tableName;?> </h4>
                            </div>
                            <div class="col-6 align-middle d-flex justify-content-end">
                                <a href="list_table.php" class=" btn btn-secondary btn-icon-only rounded-circle mt-1">
                                    <i class="fa fa-arrow-left"></i>
                                </a>
                                <a href="form_view_table.php?id=<?= $id ?>" class=" btn btn-primary text-white btn-icon-only rounded-circle mt-1">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                    $records = array();
                    foreach($table_result_val as $key => $record)
                    {
                        $record_deco = json_decode($record);
                        ?>
                        <?php
                        $headers = array();
                        $record  = array();

                        for($i = 0; $i < count($record_deco); $i++)
                        {
                            $headers[] = $record_deco[$i]->name;
                            $record[]  = $record_deco[$i]->value;
                        }
                        $records[] = $record;
                        ?>
                    <?php }
                    ?>
                    <!-- Card Body -->
                    <div class="table-responsive py-4">
                        <table class="table table-flush " id="datatable-custom" >
                            <thead class="thead-light">
                            <tr>
                                <?php if(!empty($headers))
                                {
                                    for($i = 0; $i < (count($headers) < 4 ? count($headers) : 4); $i++) { ?>
                                        <th><?= str_replace('_',' ',$headers[$i]) ?></th>
                                    <?php }
                                }
                                ?>
                                <th class="disabled-sorting text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for($j = 0; $j < count($records); $j++) { ?>
                                <tr>
                                    <?php for($k = 0; $k < (count($records[$j]) < 4 ? count($records[$j]) : 4); $k++) { ?>
                                        <td><?= $records[$j][$k] ?></td>
                                    <?php }

                                    ?>
                                    <td class="text-right">
                                        <button class="btn btn-sm btn-success btn-icon-only rounded-circle mt-1 text-white" data-json='<?= $table_result_val[$j]; ?>' onclick="get_record(this);" data-toggle="tooltip"  title="View">
                                            <i class="fa fa-eye" ></i>
                                        </button>
                                        <a href="form_view_edit.php?tid=<?= $id ?>&&id=<?= $table_result_key[$j] ?>" class="btn btn-sm btn-primary btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="Edit">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <button  data-id="<?= $table_result_key[$j] ?>" class="btn btn-sm btn-danger btn-icon-only rounded-circle mt-1 text-white remove" data-toggle="tooltip"  title="Remove">
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

        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default">Record Detail</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card shadow border-0">
                            <div class="table-responsive">
                                <table class="table table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Field Name</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody id="view_body"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="card-header card-header-text" data-background-color="orange">
                            <h4 class="card-title">Record Detail</h4>
                            <p class="category"></p>
                        </div>
                        <div class="card-content table-responsive">
                            <table class="table">
                                <tbody id="body">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                var table_ids = '<?=$id?>';
                var DatatableBasic = (function() {
                    var $dtBasic = $('#datatable-custom');
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
                                url: "table_record_delete.php?id=" + table_ids,
                                dataType: 'json',
                                data: {id: id},
                                success: function (data) {
                                    if (data.code == 200) {
                                        swal({
                                            title: "Success",
                                            text: "Your table record is successfully deleted!",
                                            type: "success",
                                            buttonsStyling: !1,
                                            confirmButtonClass: "btn btn-success"
                                        });
                                        setTimeout(function () {
                                            window.location = "view_table.php?id=" + table_ids;
                                        }, 2000);
                                    } else {
                                        swal({
                                            title: "Oops",
                                            text: "Your table record is deleted fail!",
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

            function get_record(obj) {
                showPageLoader();
                var json = obj.getAttribute('data-json');
                var data = JSON.parse(json);
                var body = '';
                for (var i = 0; i < data.length; i++) {
                    var name = str =  data[i].name.replace('_',' ').toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
                    body += '<tr><td>' + name+ '</td><td>' + data[i].value + '</td></tr>';
                }
                $('#view_body').html(body);
                closePageLoader();
                $('#modal-form').modal('show');
            }

        </script>
</body>

</html>





