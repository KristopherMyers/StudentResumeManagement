<?php
require('./fpdf/fpdf.php');

$serverName = "sql7.hostek.com"; //serverName\instanceName
$connectionInfo = array("Database" => "WEB3_CIS420", "UID" => "CIS420", "PWD" => "C!S420_fall2023");
$conn = sqlsrv_connect($serverName, $connectionInfo);

date_default_timezone_set('America/Chicago');

if ($conn) {
    $mnums = $_POST;

    //Start making the report
    ob_start();

    $report = new FPDF();
    $report->SetFont('Times', '', 12);

    $reportIndex = 1;

    foreach ($mnums as $value) {
        $genInfoSql = "SELECT * FROM SRM.Students WHERE MNumber = '$value'";
        $genInfoStmt = sqlsrv_query($conn, $genInfoSql);

        $emailSql = "SELECT * FROM SRM.StudentEmailAddress WHERE StudentMNumber = '$value'";
        $emailStmt = sqlsrv_query($conn, $emailSql);

        $phoneSql = "SELECT * FROM SRM.StudentPhoneNumber WHERE StudentMNumber = '$value'";
        $phoneStmt = sqlsrv_query($conn, $phoneSql);

        $skillSql = "SELECT * FROM SRM.StudentSkill as stuski JOIN SRM.Skills as ski ON stuski.SkillID=ski.SkillID WHERE StudentMNumber = '$value' ORDER BY ski.SkillCategory, ski.SkillName";
        $skillStmt = sqlsrv_query($conn, $skillSql);

        $certificationSql = "SELECT * FROM SRM.StudentCertification WHERE StudentMNumber = '$value'";
        $certificationStmt = sqlsrv_query($conn, $certificationSql);

        $jobSql = "SELECT * FROM SRM.StudentJobHistory WHERE StudentMNumber = '$value' ORDER BY StartDate";
        $jobStmt = sqlsrv_query($conn, $jobSql);

        //Check syntax of all sql queries
        if ($genInfoStmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if ($emailStmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if ($phoneStmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if ($certificationStmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if ($jobStmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        //Add a students information to the report
        while ($genInfoRow = sqlsrv_fetch_array($genInfoStmt, SQLSRV_FETCH_ASSOC)) {
            if (is_null($genInfoRow['ExpectedGraduationDate'])) {
                $gradDateFix = "(Expected graduation date not provided)";
            } else {
                $gradDateFix = $genInfoRow['ExpectedGraduationDate']->format("M. jS, Y");
            }
            $report->AddPage('P', 'Letter');
            $report->SetAutoPageBreak(true, 0);
            $report->SetFont('Times', 'BU', 24);
            $report->Write(5, 'Result ', '');
            $report->Write(5, $reportIndex, '');
            $report->Write(5, ' - ', '');
            $report->Write(5, $genInfoRow['LastName'], '');
            $report->Write(5, ', ', '');
            $report->Write(5, $genInfoRow['FirstName'], '');
            $report->Ln(5);
            $report->Ln(5);
            $report->SetFont('Times', 'BU', 16);
            $report->Write(5, 'General Info', '');
            $report->SetFont('Times', '', 12);
            $report->Ln(5);
            $report->Write(5, 'Majoring in ', '');
            $report->Write(5, $genInfoRow['Major'], '');
            $report->Ln(5);
            $report->Write(5, 'Expects to graduate from MSU by ', '');
            $report->Write(5, $gradDateFix, '');
            $report->Ln(5);
            $report->Ln(5);
            $report->SetFont('Times', 'BU', 16);
            $report->Write(5, 'Contact Information', '');
            $report->Ln(5);
            $report->Ln(5);
            $report->SetFont('Times', 'B', 13);
            $report->Write(5, 'Email Address(es)', '');
            $report->SetFont('Times', '', 12);
            $report->Ln(5);
            while ($emailRow = sqlsrv_fetch_array($emailStmt, SQLSRV_FETCH_ASSOC)) {
                $report->SetFont('Times', 'U', 12);
                $report->SetTextColor(0, 0, 255);
                $report->Write(5, $emailRow['EmailAddress'], '');
                $report->SetTextColor(0, 0, 0);
                $report->SetFont('Times', '', 12);
                $report->Ln(5);
            }
            $report->Ln(5);
            $report->SetFont('Times', 'B', 13);
            $report->Write(5, 'Phone Number(s)', '');
            $report->SetFont('Times', '', 12);
            $report->Ln(5);
            while ($phoneRow = sqlsrv_fetch_array($phoneStmt, SQLSRV_FETCH_ASSOC)) {
                $report->Write(5, $phoneRow['PhoneNumber'], '');
                $report->Ln(5);
            }
            $report->Ln(5);
            $report->SetFont('Times', 'BU', 16);
            $report->Write(5, 'Skills', '');
            $report->SetFont('Times', '', 12);
            $report->Ln(5);
            $skillArray = [[]];
            while ($skillRow = sqlsrv_fetch_array($skillStmt, SQLSRV_FETCH_ASSOC)) {
                if (array_key_exists($skillRow['SkillCategory'], $skillArray)) {
                    $skillArray[$skillRow['SkillCategory']][count($skillArray[$skillRow['SkillCategory']])] = $skillRow['SkillName'];
                } else {
                    $skillArray[$skillRow['SkillCategory']][0] = $skillRow['SkillName'];
                }
            }
            $skillkeys = array_keys($skillArray);
            for ($i = 1; $i < count($skillArray); $i++) {
                $report->SetFont('Times', 'B', 13);
                $report->Write(5, $skillkeys[$i], '');
                $report->SetFont('Times', '', 12);
                $report->Ln(5);
                foreach ($skillArray[$skillkeys[$i]] as $value) {
                    $report->Write(5, $value, '');
                    $report->Ln(5);
                }
                $report->Ln(5);
            }
            $report->SetFont('Times', 'BU', 16);
            $report->Write(5, 'Certifications', '');
            $report->SetFont('Times', '', 12);
            $report->Ln(5);
            while ($certificationRow = sqlsrv_fetch_array($certificationStmt, SQLSRV_FETCH_ASSOC)) {
                $report->Write(5, $certificationRow['CertificationName'], '');
                $report->Ln(5);
                if (is_null($certificationRow['AcquisitionDate'])) {
                    $report->Write(5, 'Unknown acquisition date', '');
                } else {
                    $certificationDateFix = $certificationRow['AcquisitionDate']->format("M. jS, Y");
                    $report->Write(5, 'Acquired on ' . $certificationDateFix, '');
                }

                if (is_null($certificationRow['Documentation'])) {
                    $report->Write(5, ' - documentation not provided', '');
                } else {
                    $report->Write(5, ' - ', '');
                    $report->SetFont('Times', 'U', 12);
                    $report->SetTextColor(0, 0, 255);
                    $report->Write(5, 'link', $certificationRow['Documentation']);
                    $report->SetTextColor(0, 0, 0);
                    $report->SetFont('Times', '', 12);
                }
                $report->Ln(5);
                $report->Ln(5);
            }
            $report->SetFont('Times', 'B', 16);
            $report->Write(5, 'Employment History', '');
            $report->SetFont('Times', '', 12);
            $report->Ln(5);
            $jobArray = [[]];
            while ($jobRow = sqlsrv_fetch_array($jobStmt, SQLSRV_FETCH_ASSOC)) {
                $report->Write(5, $jobRow['JobTitle'], '');
                $report->Write(5, ' at ', '');
                $report->Write(5, $jobRow['OrganizationName'], '');
                $report->Ln(5);
                if (is_null($jobRow['StartDate'])) {
                    $report->Write(5, "Start date not provided", '');
                } else {
                    $fixStart = $jobRow['StartDate']->format("M. jS, Y");
                    $report->Write(5, "$fixStart", '');
                }
                $report->Write(5, ' to ', '');
                if (is_null($jobRow['EndDate'])) {
                    $report->Write(5, "Present", '');
                } else {
                    $fixEnd = $jobRow['EndDate']->format("M. jS, Y");
                    $report->Write(5, "$fixEnd", '');
                }
                $report->Ln(5);
                $report->Write(5, $jobRow['AdditionalInfo'], '');
                $report->Ln(5);
                $report->Ln(5);
            }

            $reportIndex++;
        }

    }

    $report->Output('D', 'MSU Student Report ' . date('M-d-Y H-i-s') . '.pdf', false);

    ob_end_flush();

} else {
    echo "<div class='error'>Connection could not be established.</div>";
    die(print_r(sqlsrv_errors(), true));
}

?>