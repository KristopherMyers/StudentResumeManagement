<?php 
$serverName = "sql7.hostek.com"; //serverName\instanceName
$connectionInfo = array("Database" => "WEB3_CIS420", "UID" => "CIS420", "PWD" => "C!S420_fall2023");
$conn = sqlsrv_connect($serverName, $connectionInfo);

date_default_timezone_set('America/Chicago');

echo "<br>";
if ($conn) { 
    if (isset($_POST['add'])) {
        $skill = $_POST['skill'];
        $category = $_POST['category'];
        $testSql = "SELECT * FROM SRM.Skills WHERE SkillName = '$skill' AND SkillCategory = '$category'";
        $testStmt = sqlsrv_query($conn, $testSql);
        $testResult = sqlsrv_has_rows($testStmt);

        $categoryError = false;
        $skillError = false;

        if (empty($category)) {
            echo "<div class='error'>No skill input.</div>";
            $categoryError = true;
        }
        if (empty($skill)) {
            echo "<div class='error'>No category input.</div>";
            $skillError = true;
        }
        if (strlen($category) > 50) {
            echo "<div class='error'>Category name is too long.</div>";
            $categoryError = true;
        }
        if (strlen($skill) > 50) {
            echo "<div class='error'>New Skill name is too long.</div>";
            $skillError = true;
        }
        if ($testResult === true) {
            echo "<div class='error'>Skill of type $category named $skill already exists</div>";
        }

        if ($testResult === false and $categoryError === false and $skillError === false) { 
            $addSkillSql = "INSERT INTO SRM.Skills VALUES ('$skill', '$category')";
            $addSkillStmt = sqlsrv_query($conn, $addSkillSql);
            echo "<div class='success'>New skill of type $category named $skill added.</div>";
        }       
    }
    else {
        echo "<div class='error'>Error making skill.</div>";
    }
}
else {
    echo "<div class='error'>Database connection could not be established.</div>";
    die(print_r(sqlsrv_errors(), true));
}
?>