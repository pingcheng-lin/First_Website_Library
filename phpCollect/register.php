<?php
    include("connect.php");

    $ISBN = $_POST["value1"];
    $email = $_POST["value2"];

    $query = "SELECT U_EMAIL FROM ADMIN.BOOKS WHERE B_ISBN = '".$ISBN."'";
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
    
    $judge = array();
    while (($row = oci_fetch_array($s, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            array_push($judge, $row);
    };
    
    if ($judge[0]["U_EMAIL"] == NULL) {
        //borrow
        $query = "UPDATE ADMIN.BOOKS SET U_EMAIL = '".$email."' WHERE B_ISBN = '".$ISBN."'AND U_EMAIL IS NULL";
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
        echo json_encode("success borrow");
    } else if ($judge[0]["U_EMAIL"] == $email) {
        //return
        $query = "UPDATE ADMIN.BOOKS SET U_EMAIL = NULL WHERE B_ISBN = '".$ISBN."'AND U_EMAIL = '".$email."'";
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
        echo json_encode("success return");
    } else if ($judge[0]["U_EMAIL"] != $email) {
        echo json_encode($judge[0]["U_EMAIL"]);
    }

    oci_close($connect);
?>