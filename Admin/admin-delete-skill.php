<?php

$serverName = "sql7.hostek.com"; //serverName\instanceName
$connectionInfo = array("Database" => "WEB3_CIS420", "UID" => "CIS420", "PWD" => "C!S420_fall2023");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn) {
    if(isset($_POST['deleted_skill'])) {
        $deleted_skill = $_POST['deleted_skill'];
        $skillTableSql = "SELECT * FROM SRM.Skills WHERE SkillID = $deleted_skill";
        $skillTableStmt = sqlsrv_query($conn, $skillTableSql);
        if ($skillTableStmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $skillExists = sqlsrv_has_rows($skillTableStmt);
        if ($skillExists) { 
            $studentSkillSql = "SELECT * FROM SRM.StudentSkill WHERE SKillID = $deleted_skill";
            $studentSkillStmt = sqlsrv_query($conn, $studentSkillSql);
            if ($studentSkillStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $numStudentsAffected = 0;
            while ($studentSkillRow = sqlsrv_fetch_array($studentSkillStmt)) { 
                $numStudentsAffected++;
            }
            $studentSkillDeleteSql = "DELETE FROM SRM.StudentSkill WHERE SkillID = $deleted_skill";
            $studentSkillDeleteStmt = sqlsrv_query($conn, $studentSkillDeleteSql);
            if ($studentSkillDeleteStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $skillDeleteSql = "DELETE FROM SRM.Skills WHERE SkillID = $deleted_skill";
            $skillDeleteStmt = sqlsrv_query($conn, $skillDeleteSql);
            if ($skillDeleteStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            echo "<div class='success'>Deletion succeeded, $numStudentsAffected student(s) have lost the deleted skill.</div>";
        }
        else {
            echo "<div class='error'>Skill not found. It may have already been deleted.</div>";
        }
    }
    else {
        echo "<div class='error'>Deletion attempt detected, but no skill was chosen.</div>";
    }
} else {
    echo "<div class='error'>Database connection could not be established.</div>";
    die(print_r(sqlsrv_errors(), true));
}

?>