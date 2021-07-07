<?php
//include APIHandler Class
include 'ApiHandler.php';

//check form key and operation code exist
if (isset($_GET['formKey']) AND isset($_GET['op']))
{
    //get form key and operation type into variable
    $formKey = $_GET['formKey'];
    $operation = $_GET['op'];

    //create class object
    $handler = New ApiHandler();

    //call checkTableExist function for form key validation
    $handler->checkTableExist($formKey);

    //if operation code list then call function for list data
    if($operation == 'list')
    {
        //call function
        $handler->listData($formKey);
    }

    //if operation code show then call function for show data
    else if($operation == 'show')
    {
        //get key of record id
        $key = $_GET['id'];

        //check data exist
        $tableKey = $handler->checkDataKeyExist($formKey,$key);

        //show data if key exist
        $handler->viewData($formKey,$key);
    }

    //if operation code store, so ite store data
    elseif($operation == 'store')
    {
        //get all field of form
        $field = $handler->getHeadingOfTable($formKey);

        //generate two array
        $passData = [];
        $notExistKey = [];

        //start for loop for form field
        foreach ($field as $item)
        {
            //if form field is pass on parameter then store parameter and it value otherwise save form field into into return array
            if(isset($_GET[$item]))
            {
                $data = [];
                $data['name'] =$item;
                $data['value'] = $_GET[$item];
                array_push($passData,$data);
            }else{
                array_push($notExistKey,$item);
            }
        }

        //check all form field pass in parameter then process next stage otherwise return error with required parameter
        if(!empty($notExistKey))
        {
            //return response
            echo json_encode(
                array(
                    'code' => 400,
                    'status' => 'Bad Request',
                    'message' => 'The requested parameter was invalid',
                    'description' => $notExistKey,
                )
            );
            exit();
        }

        //check main array not empty then store data otherwise return data
        if(empty($passData))
        {
            //return response
            echo json_encode(
                array(
                    'code' => 404,
                    'status' => 'Not Found',
                    'message' => 'The requested parameter was not found.',
                )
            );
            exit();
        }else{
            //json encode and call class function for store data
            $storeJson = json_encode($passData);
            $handler->storeData($formKey,$storeJson);
        }

    }

    //if operation is update
    elseif($operation == 'update')
    {

        //get key of record id
        $key = $_GET['id'];

        //get all field of form
        $field = $handler->getHeadingOfTable($formKey);

        //check data exist
        $tableKey = $handler->checkDataKeyExist($formKey,$key);

        //generate two array
        $passData = [];
        $notExistKey = [];

        //start for loop for form field
        foreach ($field as $item)
        {
            //if form field is pass on parameter then store parameter and it value otherwise save form field into into return array
            if(isset($_GET[$item]))
            {
                $data = [];
                $data['name'] =$item;
                $data['value'] = $_GET[$item];
                array_push($passData,$data);
            }else{
                array_push($notExistKey,$item);
            }
        }

        //check all form field pass in parameter then process next stage otherwise return error with required parameter
        if(!empty($notExistKey))
        {
            //return response
            echo json_encode(
                array(
                    'code' => 400,
                    'status' => 'Bad Request',
                    'message' => 'The requested parameter was invalid',
                    'description' => $notExistKey,
                )
            );
            exit();
        }

        //check main array not empty then store data otherwise return data
        if(empty($passData))
        {
            //return response
            echo json_encode(
                array(
                    'code' => 404,
                    'status' => 'Not Found',
                    'message' => 'The requested parameter was not found.',
                )
            );
            exit();
        }else{
            //json encode and call class function for store data
            $storeJson = json_encode($passData);
            $handler->updateData($tableKey,$key,$storeJson);
        }

    }

    // if operation is deleted data
    elseif($operation== 'destroy')
    {

        //get key of record id
        $key = $_GET['id'];

        //check data exist
        $handler->checkDataKeyExist($formKey,$key);

        //call class function for delete record
        $handler->destroyData($formKey,$key);

    }else{

        //return  response for invoice operation
        echo json_encode(
            array(
                'code' => 403,
                'status' => 'Forbidden',
                'message' => 'The client did not have permission to perform this request.',
            )
        );
    }
}else{

    //return response for invalid form name
    echo json_encode(
        array(
            'code' => 404,
            'status' => 'Not Found',
            'message' => 'The requested form key was not found.',
        )
    );
}
?>