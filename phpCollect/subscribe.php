<?php
    include("connect.php");

    $email = $_POST["email"];

    // Insert some data
    $query = "INSERT INTO ADMIN.USERS(U_EMAIL) VALUES (:email)";
    $s = oci_parse($connect, $query);
    if (!$s) {
        $message = oci_error($connect);
        trigger_error("Could not parse statement: ". $message["message"], E_USER_ERROR);
    }
    oci_bind_by_name($s, ':email', $email);

    $result = oci_execute($s, OCI_NO_AUTO_COMMIT); // for PHP <= 5.3.1 use OCI_DEFAULT instead
    if (!$result) {
        $message = oci_error($s);
        trigger_error("Could not execute statement: ". $message["message"], E_USER_ERROR);
    }
    oci_commit($connect);
    oci_close($connect);
    echo json_encode("Success");
?>