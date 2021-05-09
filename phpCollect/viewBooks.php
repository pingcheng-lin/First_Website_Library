<?php
    include("connect.php");
    $type = $_POST["db"];
    $value = $_POST["value"];
    if($type == "ISBN") {
        $query = "SELECT * FROM ADMIN.BOOKS WHERE B_ISBN LIKE '%".$value."%'";
    } elseif($type == "title") {
        $query = "SELECT * FROM ADMIN.BOOKS WHERE B_TITLE LIKE '%".$value."%'";
    } elseif($type == "subject") {
        $query = "SELECT * FROM ADMIN.BOOKS WHERE B_SUBJECT LIKE '%".$value."%'";
    } elseif($type == "category") {
        $query = "SELECT * FROM ADMIN.BOOKS WHERE B_SUBJECT='".$value."'";
    }
    // find data
    $s = oci_parse($connect, $query);
    if (!$s) {
        $message = oci_error($connect);
        trigger_error("Could not parse statement: ". $message["message"], E_USER_ERROR);
    }

    $result = oci_execute($s, OCI_NO_AUTO_COMMIT); // for PHP <= 5.3.1 use OCI_DEFAULT instead
    if (!$result) {
        $message = oci_error($s);
        trigger_error("Could not execute statement: ". $message["message"], E_USER_ERROR);
    }
    oci_commit($connect);

    $returnValue = array();
    while (($row = oci_fetch_array($s, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            array_push($returnValue, $row);
    }
    oci_close($connect);
    echo json_encode($returnValue);
?>