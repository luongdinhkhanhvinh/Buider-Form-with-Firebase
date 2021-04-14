<?php


class ApiHandler
{

    //declare private object for database connection
    private $database;

    //class construct for firebase database connection initialization into object
    public function __construct()
    {
        //initialization firebase database connection
        $this->database = include_once('conn.php');
    }


    //function for list all data
    public function listData($tableKey)
    {
        //retrieve data from database
        $reference = $this->database->getReference('records/' . $tableKey . '/');
        $snapshot = $reference->getSnapshot();
        $data = $snapshot->getValue();

        //check if data found then show otherwise return error response
        if (empty($data)) {
            echo json_encode(
                array(
                    'code' => 404,
                    'status' => 'Not Found',
                    'message' => 'The requested record list was not found.',
                )
            );
            exit();
        }

        //declare main array for response
        $responseArray = [];
        foreach ($data as $key => $record) {
            //array declaration
            $simpleArray = [];

            //json data decode
            $record = json_decode($record);

            //std class convert into array
            $record = (array)$record;

            //push record key into simple array
            $simpleArray['record_id'] = $key;

            //start loop
            foreach ($record as $fieldKey => $mainData) {
                //push key and value
                $mainData = (array)$mainData;
                $simpleArray[$mainData['name']] = $mainData['value'];
            }

            //push main array
            array_push($responseArray, $simpleArray);
        }

        //return json response
        echo json_encode($responseArray);
        exit();
    }

    //function for show data with table name
    public function viewData($formKey, $key)
    {
        //retrieve data from database
        $reference = $this->database->getReference('records/' . $formKey . '/' . $key);
        $snapshot = $reference->getSnapshot();
        $record = $snapshot->getValue();

        //check if data found then show otherwise return error response
        if (empty($record)) {
            echo json_encode(
                array(
                    'code' => 404,
                    'status' => 'Not Found',
                    'message' => 'The requested record was not found.',
                )
            );
            exit();
        }

        //json data decode
        $record = json_decode($record);
        //std class convert into array
        $record = array($record);
        $record = $record[0];

        //array declaration
        $simpleArray = [];

        //push record key into simple array
        $simpleArray['record_id'] = $key;

        //start loop
        foreach ($record as $fieldKey => $mainData) {
            //push key and value
            $mainData = (array)$mainData;
            $simpleArray[$mainData['name']] = $mainData['value'];
        }

        //return json response
        echo json_encode($simpleArray);
        exit();
    }

    //function for store new data
    public function storeData($formKey, $data)
    {
        //store data into database
        $reference = $this->database->getReference('records/' . $formKey . '/')->push($data);

        //reset total count base on table record
        $this->resetTotalCount($formKey);

        //return json response success
        echo json_encode(
            array(
                'code' => 201,
                'status' => 'Created',
                'message' => 'A new record was successfully created.',
            )
        );
        exit();
    }

    //function for update data
    public function updateData($tableKey, $key, $storeJson)
    {

        //update data base on table key and data key
        $reference = $this->database->getReference('records/' . $tableKey . '/' . $key)->set($storeJson);


        //return json response success
        echo json_encode(
            array(
                'code' => 201,
                'status' => 'Updated',
                'message' => 'A record was successfully update.',
            )
        );
        exit();

    }


    //function for remove data
    public function destroyData($formKey, $key)
    {
        //remove data base on table key and data key
        $reference = $this->database->getReference('records/' . $formKey . '/' . $key)->remove();
        $this->resetTotalCount($formKey);

        //return json response success
        echo json_encode(
            array(
                'code' => 201,
                'status' => 'Deleted',
                'message' => 'A record was successfully deleted.',
            )
        );
        exit();
    }

    //function for check table key exist
    public function checkTableExist($formKey)
    {
        //fire query for get data inside table
        $reference = $this->database->getReference('tables/' . $formKey . '/');
        $snapshot = $reference->getSnapshot();
        $result = $snapshot->getValue();

        //check result found or not
        if (empty($result)) {
            //return json response error
            echo json_encode(
                array(
                    'code' => 404,
                    'status' => 'Not Found',
                    'message' => 'The table key was invalid.',
                )
            );
            exit();
        }

        //return json response success with table key
        return $formKey;
    }


    //function for get all field of form
    public function getHeadingOfTable($formKey)
    {
        //retrieve all field base on table key
        $reference = $this->database->getReference('tables/' . $formKey . '/');
        $snapshot = $reference->getSnapshot();
        $result = $snapshot->getValue();

        //declare variable for table field
        $definition = null;

        //check data found base on table key
        if (!empty($result)) {
            //store field of table into variable
            $definition = $result['definition'];
        }

        //check table definition
        if (empty($definition)) {

            //return json response error
            echo json_encode(
                array(
                    'code' => 404,
                    'status' => 'Not Found',
                    'message' => "The table field was invalid.",
                )
            );
            exit();
        } else {
            //declare field array
            $field = [];

            //encode json structure of form
            $definition = json_decode($definition);

            // start for loop
            foreach ($definition as $item) {

                //convert to array
                $item = (array)$item;
                array_push($field, $item['name']);
            }

            //return form field
            return $field;
        }
    }

    //function fot check record key exist base on table key and record key
    public function checkDataKeyExist($tableKey, $key)
    {
        //fire query for get data inside table
        $reference = $this->database->getReference('records/' . $tableKey . '/' . $key);
        $snapshot = $reference->getSnapshot();
        $result = $snapshot->getValue();

        //check result found or not
        if (empty($result)) {
            //return json response error
            echo json_encode(
                array(
                    'code' => 404,
                    'status' => 'Not Found',
                    'message' => 'The record id was invalid.',
                )
            );
            exit();
        }

        //return response form key
        return $tableKey;
    }


    //function for set total record
    protected function resetTotalCount($tableKey)
    {
        //fire query for get data inside table
        $reference = $this->database->getReference('records/' . $tableKey . '');
        $snapshot = $reference->getSnapshot();
        $data = $snapshot->getValue();

        //declare variable for total with 0;
        $totalRecord = 0;

        //check data found
        if (!empty($data)) {
            //count no of data found then store into variable
            $totalRecord = count($data);
        }

        //update total record into table count key
        $this->database->getReference('tables/' . $tableKey . '/count')->set(
            $totalRecord
        );
    }
}