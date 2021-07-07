<?php
include_once('conn.php');
if(isset($_POST['id']))
{
    $table_id = $_GET['id'];
    $id       = $_POST['id'];

    $reference      = $database->getReference('tables/' . $table_id);
    $snapshot       = $reference->getSnapshot();
    $result         = $snapshot->getValue();
    $values_records = array_values($result);
    $table_records  = ($values_records[0] - 1);
    $deleted        = $database->getReference('records/' . $table_id . '/' . $id)->remove();
    if(!empty($deleted))
    {
        $result=$database->getReference('tables/' . $table_id.'/count')->set(
            $table_records
        );
        echo json_encode(
            array(
                'code' => 200,
                'message' => 'success',
            )
        );
    }
    else
    {
        echo json_encode(
            array(
                'code' => 400,
                'message' => 'fail',
            )
        );
    }
}
?>
