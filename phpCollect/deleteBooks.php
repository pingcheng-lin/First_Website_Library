<?php
    include("connect.php");

    $ISBN = $_POST["ISBN"];

    $query = "SELECT * FROM ADMIN.BOOKS WHERE B_ISBN = '".$ISBN."'";
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

    if ($judge[0]["B_ISBN"] != NULL) {
        // delete a data
        $query = "DELETE FROM ADMIN.BOOKS WHERE B_ISBN='".$ISBN."'";
        $s = oci_parse($connect, $query);
        if (!$s) {
            $message = oci_error($connect);
            trigger_error("Could not parse statement: ". $message["message"], E_USER_ERROR);
        }

        $resuly = oci_execute($s, OCI_NO_AUTO_COMMIT); // for PHP <= 5.3.1 use OCI_DEFAULT instead
        if (!$resuly) {
            $message = oci_error($s);
            trigger_error("Could not execute statement: ". $message["message"], E_USER_ERROR);
        }
        oci_commit($connect);
        oci_close($connect);
        echo json_encode("Success");
    }
    
?>