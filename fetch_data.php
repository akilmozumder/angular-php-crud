<?php
include('db_connection.php');

$query="SELECT * from tbl_info ORDER BY id";
$stmt = $connect->prepare($query);
if($stmt->execute()){
    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    echo json_encode($data);
}


?>