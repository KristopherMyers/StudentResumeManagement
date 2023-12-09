<?php
$serverName = "sql7.hostek.com"; //serverName\instanceName
$connectionInfo = array("Database" => "WEB3_CIS420", "UID" => "CIS420", "PWD" => "C!S420_fall2023");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn) {
    if (isset($_POST["retrieve"])) {
        $mnum = $_POST["mnum"];

        $studentTableSql = "SELECT * FROM SRM.Students WHERE MNumber = '$mnum'";
        $studentTableStmt = sqlsrv_query($conn, $studentTableSql);
        if ($studentTableStmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $studentExists = sqlsrv_has_rows($studentTableStmt);
        if ($studentExists) {
            echo "<div class='success'>Student $mnum found. Displaying record below.</div>";
            echo "<table><tr><th>M#</th><th>Name</th><th>Major</th><th>Contact Info</th><th>Skills</th><th>Name</th><th>Employment History</th><th>Last Updated</th></tr><tr>";
            while ($studentrow = sqlsrv_fetch_array($studentTableStmt, SQLSRV_FETCH_ASSOC)) {
                echo "<td>" . $studentrow["MNumber"] . "</td>";
                echo "<td>" . $studentrow["LastName"] . ", " . $studentrow["FirstName"] . "</td>";
                echo "<td>" . $studentrow["Major"] . "</td>";

                echo "<td>";
                $emailTableSql = "SELECT * FROM SRM.StudentEmailAddress WHERE StudentMNumber = '$mnum' ORDER BY EmailID";
                $emailTableStmt = sqlsrv_query($conn, $emailTableSql);
                if ($emailTableStmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                if (sqlsrv_has_rows($emailTableStmt)) {
                    echo "<b>Email Addresses</b><ul>";
                    while ($emailTableRow = sqlsrv_fetch_array($emailTableStmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<li>".$emailTableRow["EmailAddress"]."</li>";
                    }
                    echo "</ul>";
                }

                $phoneTableSql = "SELECT * FROM SRM.StudentPhoneNumber WHERE StudentMNumber = '$mnum' ORDER BY PhoneNumberID";
                $phoneTableStmt = sqlsrv_query($conn, $phoneTableSql);
                if ($phoneTableStmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                if (sqlsrv_has_rows($phoneTableStmt)) {
                    echo "<b>Phone Numbers</b><ul>";
                    while ($phoneTableRow = sqlsrv_fetch_array($phoneTableStmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<li>".$phoneTableRow["PhoneNumber"]."</li>";
                    }
                    echo "</ul>";
                }
                echo "</td>";

                echo "<td>";
                $skillTableSql = "SELECT * FROM SRM.StudentSkill AS stuski JOIN SRM.Skills AS ski ON stuski.SkillID=ski.SkillID WHERE StudentMNumber = '$mnum' ORDER BY ski.SkillCategory, ski.SkillName";
                $skillTableStmt = sqlsrv_query($conn, $skillTableSql);
                if ($skillTableStmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                if (sqlsrv_has_rows($skillTableStmt)) { 
                    $prevCat = '';
                    while ($skillTableRow = sqlsrv_fetch_array($skillTableStmt, SQLSRV_FETCH_ASSOC)) { 
                        if ($prevCat === '') { 
                            echo "<br><b>" . $skillTableRow['SkillCategory'] . "</b><br><ul>";
                        }
                        else if ($prevCat != $skillTableRow['SkillCategory'] ) {
                            echo "</ul><br><b>" . $skillTableRow['SkillCategory'] . "</b><br><ul>";
                        }
                        echo "<li>".$skillTableRow['SkillName']."</li>";
                        $prevCat = $skillTableRow['SkillCategory'];
                    }
                }
                echo "</td>";

                echo "<td>";
                $certTableSql = "SELECT * FROM SRM.StudentCertification WHERE StudentMNumber = '$mnum' ORDER BY CertificationName";
                $certTableStmt = sqlsrv_query($conn, $certTableSql);
                if ($certTableStmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                if (sqlsrv_has_rows($certTableStmt)) { 
                    echo "<ul>";
                    while ($certTableRow = sqlsrv_fetch_array($certTableStmt, SQLSRV_FETCH_ASSOC)) { 
                        echo "<li>".$certTableRow['CertificationName']." - <a href='".$certTableRow['Documentation']."'>".$certTableRow['Documentation']."</a></li>";
                    }
                    echo "</ul>";
                }
                echo "</td>";

                echo "<td>";
                $jobTableSql = "SELECT * FROM SRM.StudentJobHistory WHERE StudentMNumber = '$mnum'";
                $jobTableStmt = sqlsrv_query($conn, $jobTableSql);
                if ($jobTableStmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                if (sqlsrv_has_rows($jobTableStmt)) {
                    echo "<ul>";
                    while ($jobTableRow = sqlsrv_fetch_array($jobTableStmt, SQLSRV_FETCH_ASSOC)) { 
                        echo "<li>".$jobTableRow['JobTitle']." at ".$jobTableRow['OrganizationName']."<ul><li>".$jobTableRow['AdditionalInfo']."</li></ul></li>";
                    }
                    echo "</ul>";
                }  
                echo "</td>";

                echo "<td>" . $studentrow["LastModified"]->format("M. jS, Y") ." at ". $studentrow["LastModified"]->format("h:i:s A"). "</td>";

            }
            echo "</tr>";
            echo "</table>";

        } else {
            echo "<div class='error'>A student with this M# is not contained in the database.</div>";
        }
    }
} else {
    echo "<div class='error'>Database connection could not be established.</div>";
    die(print_r(sqlsrv_errors(), true));
}

?>