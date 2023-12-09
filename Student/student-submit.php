<?php
$serverName = "sql7.hostek.com"; //serverName\instanceName
$connectionInfo = array("Database" => "WEB3_CIS420", "UID" => "CIS420", "PWD" => "C!S420_fall2023");
$conn = sqlsrv_connect($serverName, $connectionInfo);

date_default_timezone_set('America/Chicago');

function checkValidPhone($number)
{
    return true; //No validation method created yet, so just returns true for any input
}

echo "<br>";
if ($conn) {
    if (isset($_POST['save'])) {
        $mnum = $_POST['mnum'];
        $first = $_POST['first'];
        $last = $_POST['last'];
        $major = $_POST['major'];
        $employ = $_POST['employ'];
        $graduation = $_POST['graduation'];
        $emails = $_POST['emails'];
        $phones = $_POST['phones'];
        if (empty($_POST['skills'])) {
            $skills = null;
        } else {
            $skills = $_POST['skills'];
        }
        $certs = $_POST['certs'];
        $jobs = $_POST['jobs'];

        $errorMNum = false;
        $errorFirst = false;
        $errorLast = false;
        $errorMajor = false;
        $errorEmploy = false;
        $errorGraduation = false;

        $errorSkills = false;

        $errorEmail = false;
        $errorPhone = false;

        $errorCert = false;

        $errorJob = false;

        //General Info
        if (empty($mnum)) {
            echo "<div class='error'>M# is empty. Try logging in again.</div>";
            $errorMNum = true;
        }
        else if (strlen($mnum) > 10 or substr($mnum, 0, 1) != 'M') {
            echo "<div class='error'>M# is invalid. Try logging in again.</div>";
            $errorMNum = true;
        }
        if (empty($first)) {
            echo "<div class='error'>Please enter a first name.</div>";
            $errorFirst = true;
        }
        else if (strlen($first) > 50) {
            echo "<div class='error'>First name is too long.</div>";
            $errorFirst = true;
        }
        if (empty($last)) {
            echo "<div class='error'>Please enter a last name.</div>";
            $errorLast = true;
        }
        else if (strlen($last) > 50) {
            echo "<div class='error'>Last name is too long.</div>";
            $errorLast = true;
        }
        if (empty($major)) {
            echo "<div class='error'>Please enter a major.</div>";
            $errorMajor = true;
        }
        else if (strlen($major) > 50) {
            echo "<div class='error'>Major name is too long.</div>";
            $errorMajor = true;
        }
        if ($employ == "?") {
            echo "<div class='error'>Please choose a seeking employment option.</div>";
            $errorEmploy = true;
        }
        else if (strlen($employ) > 1 or $employ != ('T' or 'F')) { 
            echo "<div class='error'>The chosen seeking employment option is not valid.</div>";
            $errorEmploy = true;
        }
        if (empty($graduation)) {
            echo "<div class='error'>Please enter an expected graduation date. Estimate to the best of your ability if the exact date is unknown.</div>";
            $errorGraduation = true;
        }
        //Contact Info
        $i = 0;
        while ($i <= 1) {
            if (!empty($emails[$i])) {
                if (!filter_var($emails[$i], FILTER_VALIDATE_EMAIL)) {
                    echo "<div class='error'>E-mail address " . ($i + 1) . " is invalid.</div>";
                    $errorEmail = true;
                }
                else if (strlen($emails[$i]) > 50) { 
                    echo "<div class='error'>E-mail address " . ($i + 1) . " is too long.</div>";
                    $errorEmail = true;
                }
            }
            $i++;
        }
        $i = 0;
        while ($i <= 1) {
            if (!empty($phones[$i])) { 
                if (!checkValidPhone($phones[$i])) {
                    echo "<div class='error'>Phone number " . ($i + 1) . " has invalid formatting.</div>";
                    $errorPhone = true;
                }
                else if (strlen($phones[$i]) > 20) { 
                    echo "<div class='error'>Phone number " . ($i + 1) . " is too long.</div>";
                    $errorPhone = true;
                }
            }
            $i++;
        }
        //Certifications
        $i = 0;
        while ($i <= 9) {
            if (!empty($certs[$i][0]) or !empty($certs[$i][1]) or !empty($certs[$i][2])) {
                if (empty($certs[$i][0])) {
                    echo "<div class='error'>Certification " . ($i + 1) . " is missing a name. Add one, or clear all other data for certification " . ($i + 1) . ".</div>";
                    $errorCert = true;
                }
                else if (strlen($certs[$i][1]) > 100) {
                    echo "<div class='error'>Certification " . ($i + 1) . "'s name is too long." . ($i + 1) . ".</div>";
                    $errorCert = true;
                }
            }
            $i++;
        }

        //Jobs
        $i = 0;
        while ($i <= 4) {
            if (!empty($jobs[$i][0]) or !empty($jobs[$i][1]) or !empty($jobs[$i][2])) {
                if (empty($jobs[$i][0])) {
                    echo "<div class='error'>Job " . ($i + 1) . " is missing a company/organization name.</div>";
                    $errorJob = true;
                }
                else if (strlen($jobs[$i][0]) > 50) {
                    echo "<div class='error'>Job " . ($i + 1) . "'s company/organization name is too long.</div>";
                    $errorJob = true;
                }
                if (empty($jobs[$i][1])) {
                    echo "<div class='error'>Job " . ($i + 1) . " is missing the job title.</div>";
                    $errorJob = true;                    
                }
                else if (strlen($jobs[$i][1]) > 50) {
                    echo "<div class='error'>Job " . ($i + 1) . "'s job title is too long.</div>";
                    $errorJob = true;
                }
                if (empty($jobs[$i][2])) {
                    echo "<div class='error'>Job " . ($i + 1) . " is missing a start date. Estimate to the best of your ability if the exact date is unknown.</div>";
                    $errorJob = true;
                }
                if (strlen($jobs[$i][4]) > 255) {
                    echo "<div class='error'>Job " . ($i + 1) . "'s description is too long. Please be more brief.</div>";
                    $errorJob = true;
                }
            }
            $i++;
        }
        if ($errorMNum === false and $errorFirst === false and $errorLast === false and $errorMajor === false and $errorEmploy === false and $errorGraduation === false and $errorEmail === false and $errorPhone === false and $errorCert === false and $errorJob === false) {

            $testSql = "SELECT *
                    FROM SRM.Students
                    WHERE MNumber = '$mnum'";

            $testStmt = sqlsrv_query($conn, $testSql);
            $testResult = sqlsrv_has_rows($testStmt);

            $modificationDate = date("Y-m-d H:i:s");

            //Determines if there is 1 result for a student with the provided M#
            if ($testResult === true) {
                $studentInsertSql = "UPDATE SRM.Students 
                        SET FirstName = '$first', 
                            LastName = '$last', 
                            SeekingEmployment = '$employ', 
                            ExpectedGraduationDate = '$graduation',
                            Major = '$major',
                            LastModified = '$modificationDate'
                        WHERE MNumber = '$mnum'";
                sqlsrv_query($conn, $studentInsertSql);
            }

            //Runs if there is 0 or less results for a student with the provided M#
            elseif ($testResult === false) {

                $studentInsertSql = "INSERT INTO SRM.Students 
                                    VALUES ('$mnum', 
                                            '$first', 
                                            '$last', 
                                            '$employ', 
                                            '$graduation', 
                                            '$major',
                                            '$modificationDate')";
                sqlsrv_query($conn, $studentInsertSql);
            }
            else {
                echo "<div class='error'>An error occured while determining if your M# is already in the database.</div>";
            }
            //Runs as long as there are 1 or less results for a student with the provided M#
            if ($testResult <= 1) {

                //First, clear all contact information that has been previously applied to the student
                $clearEmailSql = "DELETE FROM SRM.StudentEmailAddress WHERE StudentMNumber = '$mnum'";
                sqlsrv_query($conn, $clearEmailSql);

                $clearPhoneSql = "DELETE FROM SRM.StudentPhoneNumber WHERE StudentMNumber = '$mnum'";
                sqlsrv_query($conn, $clearPhoneSql);

                //Then go insert all contact information that has been input
                $i = 0;
                while ($i <= 1) {
                    if (empty($emails[$i]) === false) {
                        $email = $emails[$i];
                        $emailInsertSql = "INSERT INTO SRM.StudentEmailAddress VALUES ('$mnum','$email')";
                        sqlsrv_query($conn, $emailInsertSql);
                    }
                    $i++;
                }

                $i = 0;
                while ($i <= 1) {
                    if (empty($phones[$i]) === false) {
                        $phone = $phones[$i];
                        $phoneInsertSql = "INSERT INTO SRM.StudentPhoneNumber VALUES ('$mnum','$phone')";
                        sqlsrv_query($conn, $phoneInsertSql);
                    }
                    $i++;
                }

                //Then, clear all skills that have been previously applied to the student
                $clearSkillSql = "DELETE FROM SRM.StudentSkill WHERE StudentMNumber = '$mnum'";
                sqlsrv_query($conn, $clearSkillSql);

                //Then go through and insert all skills that have been selected
                $i = 0;
                if (!empty($skills)) {
                    while ($i <= count($skills) - 1) {
                        $insertedSkill = $skills[$i];
                        $skillInsertSql = "INSERT INTO SRM.StudentSkill VALUES ('$mnum', $insertedSkill)";
                        sqlsrv_query($conn, $skillInsertSql);
                        $i++;
                    }
                }


                //Then, clear all certifications previously applied to the student
                $clearCertSql = "DELETE FROM SRM.StudentCertification WHERE StudentMNumber = '$mnum'";
                sqlsrv_query($conn, $clearCertSql);

                //Then go through the and insert all certifications that have been input
                $i = 0;
                while ($i <= 9) {

                    if (empty($certs[$i][0]) === false) {
                        $certName = $certs[$i][0];
                        $certDoc = $certs[$i][2];

                        if (empty($certs[$i][1]) === false) {
                            $certDate = $certs[$i][1];
                            if (empty($certs[$i][2])) {
                                $certDoc = 'null';
                                $certInsertSql = "INSERT INTO SRM.StudentCertification VALUES ('$certName', '$mnum', '$certDate', $certDoc)";
                                sqlsrv_query($conn, $certInsertSql);
                            } else {
                                $certDoc = $certs[$i][2];
                                $certInsertSql = "INSERT INTO SRM.StudentCertification VALUES ('$certName', '$mnum', '$certDate', '$certDoc')";
                                sqlsrv_query($conn, $certInsertSql);
                            }

                        } else {
                            $certDate = 'null';
                            if (empty($certs[$i][2])) {
                                $certDoc = 'null';
                                $certInsertSql = "INSERT INTO SRM.StudentCertification VALUES ('$certName', '$mnum', $certDate, $certDoc)";
                                sqlsrv_query($conn, $certInsertSql);
                            } else {
                                $certDoc = $certs[$i][2];
                                $certInsertSql = "INSERT INTO SRM.StudentCertification VALUES ('$certName', '$mnum', $certDate, '$certDoc')";
                                sqlsrv_query($conn, $certInsertSql);
                            }
                        }
                    }
                    $i++;
                }

                //Then, clear all jobs previously applied to the student
                $clearJobSql = "DELETE FROM SRM.StudentJobHistory WHERE StudentMNumber = '$mnum'";
                sqlsrv_query($conn, $clearJobSql);

                //Then go through and insert all job history that has been input
                $i = 0;
                while ($i <= 4) {
                    if (empty($jobs[$i][0]) === false and empty($jobs[$i][1]) === false and empty($jobs[$i][2]) === false) {
                        $orgName = $jobs[$i][0];
                        $jobTitle = $jobs[$i][1];
                        $startDate = $jobs[$i][2];
                        $jobDesc = $jobs[$i][4];

                        if (empty($jobs[$i][3]) === false) {
                            $endDate = $jobs[$i][3];
                            $jobInsertSql = "INSERT INTO SRM.StudentJobHistory
                                    VALUES ('$mnum', '$jobTitle', '$orgName', '$startDate', '$endDate', '$jobDesc')";
                            sqlsrv_query($conn, $jobInsertSql);
                        } else {
                            $endDate = 'null';
                            $jobInsertSql = "INSERT INTO SRM.StudentJobHistory
                                    VALUES ('$mnum', '$jobTitle', '$orgName', '$startDate', $endDate, '$jobDesc')";
                            sqlsrv_query($conn, $jobInsertSql);
                        }
                    }
                    $i++;
                }

            }
            $time = date("g:i:s A e");

            echo "<div class='success'>Data Saved at $time<div>";
        }

    } else {
        echo "<div class='error'>Error saving data.</div>";
    }
} else {
    echo "<div class='error'>Database connection could not be established.</div>";
    die(print_r(sqlsrv_errors(), true));
}
?>