<?php
if (isset($_POST["action"]) && ($_POST["action"] == "add")) {

    include("connect.php");

    $ISBN = $_POST["ISBN"];
    $title = $_POST['title'];
    $subject = $_POST['subject'];
    $status = 1;
    $uID = NULL;

    // Insert some data
    $query = "INSERT INTO ADMIN.BOOKS(B_ISBN, U_ID, B_TITLE, B_SUBJECT, B_STATUS) VALUES (:ISBN,:u_ID,:title,:sub,:stat)";
    $s = oci_parse($connect, $query);
    if (!$s) {
        $m = oci_error($connect);
        trigger_error("Could not parse statement: ". $m["message"], E_USER_ERROR);
    }
    oci_bind_by_name($s, ':ISBN', $ISBN);
    oci_bind_by_name($s, ':u_ID', $uID);
    oci_bind_by_name($s, ':title', $title);
    oci_bind_by_name($s, ':sub', $subject);
    oci_bind_by_name($s, ':stat', $status);

    $r = oci_execute($s); // for PHP <= 5.3.1 use OCI_DEFAULT instead
    if (!$r) {
        $m = oci_error($s);
        trigger_error("Could not execute statement: ". $m["message"], E_USER_ERROR);
    }
    oci_commit($connect);

    //return
    $url = "../htmlCollect/add.html";
    echo "<script type='text/javascript'>";
    echo "window.location.href='$url'";
    echo "</script>";
}
?>