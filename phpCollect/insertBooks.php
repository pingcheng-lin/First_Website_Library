<?php
    include("connect.php");

    $ISBN = $_POST["ISBN"];
    $title = $_POST['title'];
    $subject = $_POST['subject'];
    $email = NULL;

    // Insert some data
    $query = "INSERT INTO ADMIN.BOOKS(B_ISBN, B_TITLE, B_SUBJECT, U_EMAIL) VALUES (:ISBN,:title,:sub,:email)";
    $s = oci_parse($connect, $query);
    if (!$s) {
        $message = oci_error($connect);
        trigger_error("Could not parse statement: ". $message["message"], E_USER_ERROR);
    }
    oci_bind_by_name($s, ':ISBN', $ISBN);
    oci_bind_by_name($s, ':title', $title);
    oci_bind_by_name($s, ':sub', $subject);
    oci_bind_by_name($s, ':email', $email);

    $result = oci_execute($s, OCI_NO_AUTO_COMMIT); // for PHP <= 5.3.1 use OCI_DEFAULT instead
    if (!$result) {
        $message = oci_error($s);
    trigger_error("Could not execute statement: ". $message["message"], E_USER_ERROR);
    }
    oci_commit($connect);
        
    //notify subscriber
    $query = "SELECT * FROM ADMIN.USERS";
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