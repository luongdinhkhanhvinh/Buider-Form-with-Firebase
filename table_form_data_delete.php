<?php
include_once('conn.php');
if(isset($_POST['id']))
{
    $id        = $_POST['id'];
    $deleted=$database->getReference('tables/'.$id)->remove();
    if(!empty($deleted))
    {
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
