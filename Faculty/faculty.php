<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="/cis420/202380/resumecollectionstyles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>KMyers21 CIS420 Project Faculty Page</title>

    <?php
    $serverName = "sql7.hostek.com"; //serverName\instanceName
    $connectionInfo = array("Database" => "WEB3_CIS420", "UID" => "CIS420", "PWD" => "C!S420_fall2023");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    $skillMaxSql = "SELECT MAX(SkillID) as Max FROM SRM.Skills";
    $skillMaxStmt = sqlsrv_query($conn, $skillMaxSql);
    while ($skillMaxRow = sqlsrv_fetch_array($skillMaxStmt, SQLSRV_FETCH_ASSOC)) {
        $largestSkill = $skillMaxRow['Max'];
    }
    ?>

    <script>
        $(document).ready(function () {
            var load = 'load';
            let i = 0;
            $('.student-table').load("/cis420/202380/Faculty/faculty-get-records.php", {
                load: load
            });
            $("#generateForm").submit(function (event) {
                event.preventDefault();
                var generateForm = document.getElementById("generateForm");
                i = 0;
                const mnums = [];
                if (generateForm.mnum_boxes.length === undefined) {
                    if (generateForm.mnum_boxes.checked === true) {
                            mnums.push(generateForm.mnum_boxes.value);
                        }
                } else {
                    for (i = 0; i < generateForm.mnum_boxes.length; i++) {
                        if (generateForm.mnum_boxes[i].checked === true) {
                            mnums.push(generateForm.mnum_boxes[i].value);
                        }
                    }
                }
                var generate = $("#generate").val();
                $(".messages").load("/cis420/202380/Faculty/faculty-report-verifier.php", {
                    mnums: mnums,
                    generate: generate
                });
            });

            $("#searchForm").submit(function (event) {
                event.preventDefault();
                var searchForm = document.getElementById("searchForm");
                var certifications = document.getElementById('certifications').value;
                var majors = document.getElementById('majors').value;
                var graddate = document.getElementById('grad_date').value;
                i = 1;
                const skills = [];
                while (i <= <?php echo $largestSkill; ?>) {
                    if (document.getElementById("skill-" + i).checked) {
                        skills.push(i);
                    }
                    i++;
                }
                var employ;
                if (document.getElementById('employment').checked) {
                    employ = 'T';
                }
                else {
                    employ = 'F';
                }
                var rule;
                if (document.getElementById('inclusion_any').checked) {
                    rule = document.getElementById('inclusion_any').value;
                }
                else if (document.getElementById('inclusion_all').checked) {
                    rule = document.getElementById('inclusion_all').value;
                }
                else {
                    rule = 'null';
                }
                var search = $("#search").val();
                $(".student-table").load("/cis420/202380/Faculty/faculty-search-options.php", {
                    certifications: certifications,
                    majors: majors,
                    skills: skills,
                    rule: rule,
                    employ: employ,
                    graddate: graddate,
                    search: search
                });
            })

        })

    </script>
</head>

<body>
    <header>
        <img class="logo" src="/cis420/202380/images/primary_white.png" alt="Murray State University logo">
        <img class="profile-picture" title="Currently logged in as Name"
            src="/cis420/202380/images/blank-profile.png" alt="Profile Picture">
        <div class="head-text">View Digital Resumes

        </div>
    </header>



    <div class="sidebar">
        <form id="searchForm" method="post">
            <h1>Search Options</h1>
            <div>If you want to search for multiple values of a single type, separate them using commas. Ex: "Computer
                Science, Biology, Engineering"</div>
            <div>
                <h3><label for='certifications'>Certification(s): </label></h3>
                <input type='text' id='certifications' name='certifications' placeholder='Certification name(s) here' />
            </div>
            <div>
                <h3><label for='majors'>Major(s): </label></h3>
                <input type='text' id='majors' name='majors' placeholder='Major name(s) here' />
            </div>
            <div>
                <h3>Skills:</h3>
                <div class='skillbox'>
                    <?php

                    $allSkillSql = "SELECT *
                                FROM SRM.Skills
                                ORDER BY SkillCategory, SkillName";

                    $allSkillStmt = sqlsrv_query($conn, $allSkillSql);

                    if ($allSkillStmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }

                    $prevCat = '';
                    while ($allSkillRow = sqlsrv_fetch_array($allSkillStmt, SQLSRV_FETCH_ASSOC)) {
                        if ($prevCat == '') {
                            echo "<b>" . $allSkillRow['SkillCategory'] . "</b><br>";
                        } else if ($prevCat != $allSkillRow['SkillCategory']) {
                            echo "<br><b>" . $allSkillRow['SkillCategory'] . "</b><br>";
                        }
                        echo "<input type='checkbox' id='skill-" . $allSkillRow['SkillID'] . "' name='student_skills[]' value='" . $allSkillRow['SkillID'] . "'/>
                            <label for='skill-" . $allSkillRow['SkillID'] . "'>" . $allSkillRow['SkillName'] . "</label>";
                        $prevCat = $allSkillRow['SkillCategory'];
                    }

                    ?>
                </div>
            </div>
            <div>
                <h3 class='contains-info'
                    title='For these rules to apply, at least one of the above search options must be selected.'>
                    Inclusion Rules: </h3>
                <input type='radio' id='inclusion_any' name='inclusion_rule' value='any' checked /><label
                    for='inclusion_any'>Include Results Matching Any of the Above Search Values</label>
                <br>
                <input type='radio' id='inclusion_all' name='inclusion_rule' value='all' /><label
                    for='inclusion_all'>Only Include Results Matching All of the Above Search Values</label>
                <br>
                <input type='checkbox' id='employment' name='seeking_employment' value='T' /><label
                    for='employment'>Only Include Students Seeking Employment</label>
                <br>

                <label for='grad_date'>Only Students who Graduate By: </label>
                <input type='date' id='grad_date' name='graduation_date' />
            </div>

        </form>
        <br>
        <div class="messages"> </div>

        <input type="submit" id="search" name="search" class="first-big-button" value="Apply Search Filters"
            form="searchForm" />

        <form action="./faculty.php">
            <button type="submit" class="big-button">Clear Search Filters</button>
        </form>
        <div class='error'>Note: Both of the above buttons clear selected M#s</div>
        <input type="submit" id="generate" name="generate" class="big-button"
            value="Generate PDF Report Using Selected M#s" form="generateForm" />
    </div>

    <div class="table-container">
        <form id="generateForm" action="/cis420/202380/Faculty/faculty-report-verifier.php" method="post">
            <table class="student-table">

            </table>
    </div>

    </form>

</body>


</html>