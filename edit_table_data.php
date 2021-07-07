<?php
include_once('conn.php');
if(isset($_POST['json']))
{
    $json = $_POST['json'];
    $data = json_decode($json);
    //    print_r($data);die;
    $id          = $data[0];
    $table       = $data[1];
    $defifnition = $data[2];

    $result=$database->getReference('tables/' . $id)->set(
        [
            'name' => $table,
            'definition' => $defifnition,
            'count' => 0,
        ]
    );

    if(!empty($result))
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
