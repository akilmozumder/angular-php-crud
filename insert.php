<?php

include('db_connection.php');

$form_data = json_decode(file_get_contents("php://input"));

 $error = array();
 $message = '';
 $validation_error = '';
 $first_name = '';
 $last_name = '';
 if($form_data->action == 'fetch_single_data'){
    $query = "SELECT * FROM tbl_info WHERE id='".$form_data->id."'";
    $stmt=$connect->prepare($query);
    $stmt->execute();
    $result=$stmt->fetchAll();
    foreach($result as $row){
        $output['first_name']=$row['first_name'];
        $output['last_name']=$row['last_name'];

    }
 }elseif($form_data->action == 'delete'){
    $query="DELETE FROM tbl_info WHERE id='".$form_data->id."'";
    $stmt = $connect->prepare($query);
    if($stmt->execute()){
       $output['message']="Data deleted";
    }
 }
 else
 {

    if(empty($form_data->first_name)){
        $error[]='First name required';
    }else{
        $first_name = $form_data->first_name;
    }

    if(empty($form_data->last_name)){
        $error[]='Last name required';
    }else{
        $last_name = $form_data->last_name;
    }

    if(empty($error)){
        if($form_data->action == 'Insert'){
            $data = array(
                ':first_name' => $first_name,
                ':last_name' => $last_name
            );
            $query="INSERT INTO tbl_info (first_name,last_name) VALUES (:first_name,:last_name)";
            $statement = $connect->prepare($query);
            if($statement->execute($data)){
                $message = 'Data Inserted';
            }
        }
        
        if($form_data->action=="Update"){
            $data = array(
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':id' => $form_data->id
            );
            
            $query="UPDATE tbl_info SET first_name = :first_name,last_name=:last_name WHERE id = :id";
            $stmt=$connect->prepare($query);
            if($stmt->execute($data)){
                $message = 'Data updated';
            }
           
        }
    }else{
        $validation_error = implode(", ",$error);
    }

    $output = array(
        'error' => $validation_error,
        'message' => $message
     );
 }
//  $output = array(
//     'error' => $validation_error,
//     'message' => $message
//  );

 echo json_encode($output);



?>