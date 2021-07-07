<?php
session_start();
include_once('conn.php');
if(!empty($_POST['email']) && isset($_POST['email']) && !empty($_POST['password']) && isset($_POST['password']))
{

    if(AUTH_USERNAME == $_POST['email'] && AUTH_PASSWORD == $_POST['password'])
    {
        $_SESSION['email']    = $_POST['email'];
        $_SESSION['password'] = $_POST['password'];

        echo json_encode(
            array(
                'code' => 200,
                'message' => 'successfully login redirect...',
            )
        );
    }
    else
    {
        echo json_encode(
            array(
                'code' => 201,
                'message' => 'Invalide username or password.',
            )
        );
    }
}
else
{
    echo json_encode(
        array(
            'code' => 402,
            'message' => 'Something went wrong.',
        )
    );
}
?>