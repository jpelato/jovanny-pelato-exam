<?php
function connect(){
    $servername = "localhost";
    $username = "root";
    $password = "jpelato";
    $dbname = "youtube_db";
    $conn = new mysqli($servername, $username, $password,$dbname);
    if ($conn->connect_error) {
        echo $conn->connect_error;
    }
    return $conn;
}

function getAll($tableName){
    $sql = 'SELECT * FROM '.$tableName;
    $con = connect();
    $exe = $con->query($sql);
    $result = $exe->num_rows > 0 ? $exe->fetch_all(MYSQLI_ASSOC) : '0 results';
    $con->close();
    echo json_encode($result);
}

function getBy($table,$condition){
    $sql = 'SELECT * FROM '.$table.' WHERE '.$condition;
    $con = connect();
    $exe = $con->query($sql);
    $result = $exe->num_rows > 0 ? $exe->fetch_all(MYSQLI_ASSOC) : '0 results';
    $con->close();
    echo json_encode($result);
}

function saveMultiple($table,$multipleInsert){
    foreach ($multipleInsert as &$insert) {
        save($table,$insert);
    }
    unset($insert);
}
function save($table, $insert){
    
    $columns = array_keys($insert);
    $value = '';
    foreach ($columns as &$column) {
        if(is_array($insert[$column])){
            $json = json_encode($insert[$column]);  
            $json_string = str_replace(['\'','"'],['\\\'','\"'],$json);
            $value .= "'".$json_string."',";
            continue;
        }
        if(strtotime($insert[$column])){
            $date = date("Y-m-d H:i:s", strtotime($insert[$column]));
            $value .= "'".$date."',";
            continue;
        }
        $value .= "'".str_replace(['\'','"'],['\\\'','\"'],$insert[$column])."',";
    }
    unset($column);
    $trim_value = rtrim($value,",");
    $sql = "INSERT INTO $table (".implode(",",$columns).") VALUES ($trim_value)";
    $con = connect();
    $exe = $con->query($sql);
    $con->close();
}

function delete($table,$condition){
    $sql = 'DELETE FROM '.$table.' WHERE '.$condition;
    $con = connect();
    $exe = $con->query($sql);
    $con->close();
}