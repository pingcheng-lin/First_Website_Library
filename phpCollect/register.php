<?php
    include("connect.php");

    $ISBN = $_POST["value2"];
    //$email = $_POST["value2"];
    
    //send email if someone borrow
    $query = "SELECT * FROM ADMIN.BOOKS WHERE B_ISBN='".$ISBN."'" ;
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
    // Print data
    while (($row = oci_fetch_array($s, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            array_push($returnValue, $row);
    }
    if(empty($returnValue))
        echo json_encode("nothing");
    else    
        echo json_encode($returnValue);
    //judge whether isbn = isbn,email != mine if true send email

/*
    //borrow
    $query = "UPDATE books SET U_EMAIL = '".$email."' WHERE ISBN = '".$ISBN."'AND U_EMAIL = NULL";
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

    //return
    $query = "UPDATE books SET U_EMAIL = NULL WHERE ISBN = '".$ISBN."'AND U_EMAIL = '".$email."'";
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
    oci_commit($connect);*/
    oci_close($connect);
?>