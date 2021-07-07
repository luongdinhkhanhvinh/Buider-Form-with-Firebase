<?php
include_once('conn.php');

if(isset($_POST['json']))
{
    $json        = $_POST['json'];
    $data        = json_decode($json);
    $id      = $data[0];
    $table_id      = $data[1];
    $records = $data[2];

    $newPost     = $database->getReference('records/'.$table_id.'/'.$id)->set(
        $records
    );
    if(!empty($newPost->getKey()))
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
