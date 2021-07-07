<?php
session_start();
$_SESSION['page'] = 'API DOC';
if(empty($_SESSION))
{
    header('Location: login.php');
}
include_once('conn.php');
$reference      = $database->getReference('tables');
$snapshot       = $reference->getSnapshot();
$result         = $snapshot->getValue();
$key_records = [];
$values_records = [];
if(!empty($result))
{
    $values_records = $result;
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
                        <h6 class="h2 text-white d-inline-block mb-0">List of API</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">List of API</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--12">
        <div class="row">
            <div class="col">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <?php
                        $class = 'active';
                        foreach ($values_records as $tableKey => $formName)
                        {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 <?php echo $class;?>" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-<?php echo $tableKey;?>" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><?php echo $formName['name'];?></a>
                            </li>
                            <?php
                            $class = '';
                        }
                        ?>
                    </ul>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <?php
                            $class = 'active';
                            foreach ($values_records as $tableKey => $formName)
                            {
                                ?>
                                <div class="tab-pane fade show <?php echo $class;?>" id="tabs-<?php echo $tableKey;?>" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                    <table class="table-responsive table">
                                        <thead class="thead-light">
                                        <tr>
                                            <th class="w-25">Action</th>
                                            <th class="w-50">Sample Request</th>
                                            <th class="w-25"></th>
                                        </tr>
                                        </thead>
                                        <?php
                                        $apiField = [];
                                        foreach ((array) $formName['definition'] as $key1=>$field )
                                        {

                                            $field = json_decode($field);
                                            $field = (array) $field;
                                            foreach ($field as $head)
                                            {
                                                $head = (array) $head;

                                                $apiField[$head['name']] =  $head['type'];
                                            }
                                        }
                                        ?>

                                        <?php
                                        $fieldString = '';
                                        foreach ($apiField as $key => $type)
                                        {
                                            $fieldString .= '&'.$key.'='.$type;
                                        }
                                        ?>
                                        <tbody>
                                        <tr>
                                            <td>List</td>
                                            <td><a style="word-break: break-word" target="_blank" href="api.php?formKey=<?php echo$tableKey;?>&op=list">api.php?formKey=<?php echo $tableKey;?>&op=list</a></td>
                                            <td>
                                                <button  class="btn btn-copy btn-sm btn-info btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="Copy">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>View</td>
                                            <td><a style="word-break: break-word" target="_blank" href="api.php?formKey=<?php echo $tableKey;?>&op=show&id=xxx">api.php?formKey=<?php echo $tableKey;?>&op=show&id=xxx</a></td>
                                            <td><button  class="btn btn-copy btn-sm btn-info btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="Copy">
                                                    <i class="fa fa-copy"></i>
                                                </button> </td>
                                        </tr>
                                        <tr>
                                            <td>Store</td>
                                            <td><a  style="word-break: break-word" target="_blank" href="api.php?formKey=<?php echo$tableKey.'&op=store'.$fieldString;?>">api.php?formKey=<?php echo $tableKey.'&op=store'.$fieldString;?></a></td>
                                            <td><button  class="btn btn-copy btn-sm btn-info btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="Copy">
                                                    <i class="fa fa-copy"></i>
                                                </button> </td>
                                        </tr>
                                        <tr>
                                            <td>Update</td>
                                            <td><a  style="word-break: break-word" target="_blank" href="api.php?formKey=<?php echo $tableKey.'&op=update&id=xxx'.$fieldString;?>">api.php?formKey=<?php echo $tableKey.'&op=update&id=xxx'.$fieldString;?></a></td>
                                            <td><button  class="btn btn-copy btn-sm btn-info btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="Copy">
                                                    <i class="fa fa-copy"></i>
                                                </button> </td>
                                        </tr>
                                        <tr>
                                            <td>Delete</td>
                                            <td><a  style="word-break: break-word" target="_blank" href="api.php?formKey=<?php echo $tableKey;?>&op=destroy&id=xxx">api.php?formKey=<?php echo $tableKey;?>&op=destroy&id=xxx</a></td>
                                            <td><button  class="btn btn-copy btn-sm btn-info btn-icon-only rounded-circle mt-1 text-white" data-toggle="tooltip"  title="Copy">
                                                    <i class="fa fa-copy"></i>
                                                </button> </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                                $class= '';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php include './layout/footer.php';?>

        <!-- Optional JS -->

        <script type="text/javascript">

            //copy url event
            $('.btn-copy').click(function () {

                var path = window.location.href;
                path = path.substring(0, path.lastIndexOf("/"));

                var urlText = $(this).parents('tr').find('a').attr('href');

                var $temp = $("<input>");
                var $mailUrl = path+'/'+urlText;

                $("body").append($temp);
                $temp.val($mailUrl).select();

                document.execCommand("copy");

                $temp.remove();
            });
        </script>
</body>

</html>



