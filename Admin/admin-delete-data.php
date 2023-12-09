<?php

$serverName = "sql7.hostek.com"; //serverName\instanceName
$connectionInfo = array("Database" => "WEB3_CIS420", "UID" => "CIS420", "PWD" => "C!S420_fall2023");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn) {
    if (isset($_POST["delete"])) {
        $mnum = $_POST["mnum"];

        $studentTableSql = "SELECT * FROM SRM.Students WHERE MNumber = '$mnum'";
        $studentTableStmt = sqlsrv_query($conn, $studentTableSql);
        if ($studentTableStmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $studentExists = sqlsrv_has_rows($studentTableStmt);
        if ($studentExists) {
            $emailDeleteSql = "DELETE FROM SRM.StudentEmailAddress WHERE StudentMNumber = '$mnum'";
            $emailDeleteStmt = sqlsrv_query($conn, $emailDeleteSql);
            if ($emailDeleteStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $phoneDeleteSql = "DELETE FROM SRM.StudentPhoneNumber WHERE StudentMNumber = '$mnum'";
            $phoneDeleteStmt = sqlsrv_query($conn, $phoneDeleteSql);
            if ($phoneDeleteStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $skillDeleteSql = "DELETE FROM SRM.StudentSkill WHERE StudentMNumber = '$mnum'";
            $skillDeleteStmt = sqlsrv_query($conn, $skillDeleteSql);
            if ($skillDeleteStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $certDeleteSql = "DELETE FROM SRM.StudentCertification WHERE StudentMNumber = '$mnum'";
            $certDeleteStmt = sqlsrv_query($conn, $certDeleteSql);
            if ($certDeleteStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $jobDeleteSql = "DELETE FROM SRM.StudentJobHistory WHERE StudentMNumber = '$mnum'";
            $jobDeleteStmt = sqlsrv_query($conn, $jobDeleteSql);
            if ($jobDeleteStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $mainDeleteSql = "DELETE FROM SRM.Students WHERE MNumber = '$mnum'";
            $mainDeleteStmt = sqlsrv_query($conn, $mainDeleteSql);
            if ($mainDeleteStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            echo "<div class='success'>Student $mnum has been deleted.</div>";
        } else {
            echo "<div class='error'>A student with this M# is not contained in the database.</div>";
        }
    }
} else {
    echo "<div class='error'>Database connection could not be established.</div>";
    die(print_r(sqlsrv_errors(), true));
}

?>