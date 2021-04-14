<?php
session_start();
$_SESSION['page'] = 'Add form view';
if(empty($_SESSION))
{
    header('Location: login.php');
}
include_once('conn.php');
if(isset($_GET['id']))
{
    $id            = $_GET['id'];
    $reference     = $database->getReference('tables/' . $id);
    $snapshot      = $reference->getSnapshot();
    $tabeldata     = $snapshot->getValue();
    $table_records = $tabeldata['count'];
    $table_records+=1;

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
                        <h6 class="h2 text-white d-inline-block mb-0">Create Record</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="view_table.php?id=<?= $id ?>"><?= $tabeldata['name']?></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create Record</li>
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
                        <h3 class="mb-0">New Record</h3>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="form-horizontal" >
                            <div class="form-group">
                                <form class="fb-render" id="fb-render"></form>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">

                        <div class="form-group text-center">
                            <button type="button" id="get-table-data" class="btn btn-success">Save</button>
                            <a href="view_table.php?id=<?= $id ?>" class="btn btn-info">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php include './layout/footer.php';?>
        <script src="./assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

        <script>
            $(document).on('click', '#get-table-data', function () {
                $("#get-table-data").attr("disabled", true);
                var tableData = [];
                var formData = JSON.stringify(jQuery('#fb-render').serializeArray());

                var id = '<?=$id?>';
                var table_records = '<?=$table_records?>';
                tableData.push(table_records);
                tableData.push(id);
                tableData.push(formData);
                $.ajax({
                    type: "POST",
                    url: "save_table_data.php",
                    dataType: 'json',
                    data: {json: JSON.stringify(tableData)},
                    success: function (data) {
                        $("#get-table-data").attr("disabled", false);
                        if (data.code == 200) {
                            swal({
                                title: "Success",
                                text: "Your table is successfully updated!",
                                type: "success",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            setTimeout(function () {
                                window.location = "view_table.php?id=" + id;
                            }, 2000);

                        } else {
                            swal({
                                title: "Oops",
                                text: "Your table is created fail!",
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            $("#get-table-data").attr("disabled", false);
                        }
                    }
                });
            });

            jQuery(function ($) {
                formRenderOpts = {
                    formData: <?= $tabeldata['definition']?>
                };
                var renderedForm = $('#fb-render');
                renderedForm.formRender(formRenderOpts);
                $('#view_editor').html(renderedForm.html());

                //apply theme style of checkbox
                $('input[type="checkbox"]').each(function() {
                    $(this).addClass('custom-control-input');
                    $(this).parent().addClass('custom-control custom-checkbox mb-3');
                    $(this).parent().find('label').addClass('custom-control-label')
                    if($(this). prop("checked") == true)
                    {
                        $(this). prop("checked",true);
                    }else{
                        $(this). prop("checked",false);
                    }
                });

                //apply theme style of checkbox
                $('input[type="radio"]').each(function() {
                    $(this).addClass('custom-control-input');
                    $(this).parent().addClass('custom-control custom-radio');
                    $(this).parent().find('label').addClass('custom-control-label')
                });

                //change inout type date to datepicker
                $('input[type="date"]').each(function() {
                    $(this).addClass('datepicker');
                    $(this).attr('type','text');
                    $(this).attr('readonly','true');
                });

                var Datepicker = (function() {
                    // Variables
                    var $datepicker = $('.datepicker');

                    // Methods
                    function init($this) {
                        var options = {
                            disableTouchKeyboard: true,
                            autoclose: false,
                            clearBtn:true,
                            todayBtn: true,
                        };
                        $this.datepicker(options);
                    }
                    // Events
                    if ($datepicker.length) {
                        $datepicker.each(function() {
                            init($(this));
                        });
                    }

                })();
            });
        </script>
</body>
</html>



