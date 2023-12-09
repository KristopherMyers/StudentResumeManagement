<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="/cis420/202380/resumecollectionstyles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>KMyers21 CIS420 Project Student Page</title>

    <?php
    $serverName = "sql7.hostek.com"; //serverName\instanceName
    $connectionInfo = array("Database" => "WEB3_CIS420", "UID" => "CIS420", "PWD" => "C!S420_fall2023");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    if (!empty($_POST['mnum'])) {
        $loadedStudent = $_POST['mnum'];
    } else {
        $loadedStudent = "ERROR: BAD M#";
    }
    //In the final version of this app, this should be set equal to the M# of the student who has logged in through OAuth 
    
    $skillMaxSql = "SELECT MAX(SkillID) as Max FROM SRM.Skills";
    $skillMaxStmt = sqlsrv_query($conn, $skillMaxSql);
    while ($skillMaxRow = sqlsrv_fetch_array($skillMaxStmt, SQLSRV_FETCH_ASSOC)) {
        $largestSkill = $skillMaxRow['Max'];
    }
    ?>

    <script>
        $(document).ready(function () {
            $("form").submit(function (event) {
                event.preventDefault();
                //General Info
                var mnum = "<?php echo $loadedStudent; ?>";
                var first = $("#first-name").val();
                var last = $("#last-name").val();
                var major = $("#major").val();
                var ele = document.getElementsByName('#seeking_employment');
                if (document.getElementById("employ-no").checked) {
                    var employ = 'F';
                }
                else if (document.getElementById("employ-yes").checked) {
                    var employ = 'T';
                }
                else {
                    var employ = '?';
                }
                var graduation = $("#grad-date").val();
                //Email
                const emails = [$("#email-1").val(), $("#email-2").val()];
                //Phone
                const phones = [$("#phone-1").val(), $("#phone-2").val()];
                //Skills
                let i = 1;
                const skills = [];
                while (i <= <?php echo $largestSkill; ?>) {
                    if (document.getElementById("skill-" + i)) {
                        if (document.getElementById("skill-" + i).checked) {
                            skills.push(i);
                        }
                    }
                    i++;
                }
                //Certifications
                i = 1;
                const certs = [];
                while (i <= 10) {
                    certs.push([$("#cert-name-" + i).val(), $("#cert-date-" + i).val(), $("#cert-doc-" + i).val()]);
                    i++;
                }
                //Job History
                i = 1;
                const jobs = [];
                while (i <= 5) {
                    jobs.push([$("#job-org-" + i).val(), $("#job-title-" + i).val(), $("#job-start-" + i).val(), $("#job-end-" + i).val(), $("#job-desc-" + i).val()]);
                    i++;
                }
                var save = $("#save").val();
                $(".messages").load("/cis420/202380/Student/student-submit.php", {
                    mnum: mnum,
                    first: first,
                    last: last,
                    major: major,
                    employ: employ,
                    graduation: graduation,
                    emails: emails,
                    phones: phones,
                    skills: skills,
                    certs: certs,
                    jobs: jobs,
                    save: save
                });
            });
        });
    </script>
</head>

<body>

    <header>
        <img class="logo" src="/cis420/202380/images/primary_white.png" alt="Murray State University logo">
        <img class="profile-picture" title="Currently logged in as Name" src="/cis420/202380/images/blank-profile.png"
            alt="Profile Picture">
        <div class="head-text">Update Digital Resume</div>
    </header>

    <div>Any items marked with a "<span class='required'>*</span>" are required unless otherwise stated.</div>
    <form action='/cis420/202380/Student/student-submit.php' method='post'>
        <div>
            <h1>General Information</h1>

            <?php

            $existingGeneralInfoSql = "SELECT * FROM SRM.Students WHERE MNumber = '$loadedStudent'";

            $existingGeneralInfoStmt = sqlsrv_query($conn, $existingGeneralInfoSql);

            if ($existingGeneralInfoStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            if (sqlsrv_has_rows($existingGeneralInfoStmt)) {
                while ($existingGeneralInfoRow = sqlsrv_fetch_array($existingGeneralInfoStmt)) {

                    $fixDate = $existingGeneralInfoRow['ExpectedGraduationDate']->format("Y-m-d");

                    $seekingEmploy = $existingGeneralInfoRow['SeekingEmployment'];

                    echo "  <div>M#: </div>
                            <div>" . $loadedStudent . "</div>
                            <br>
                            <label for='first-name'><span  class='required'>*</span>First Name:</label><br>
                            <input type='text' id='first-name' name='firstname' value='" . $existingGeneralInfoRow['FirstName'] . "' />
                            <br><br>
                            <label for='last-name'><span class='required'>*</span>Last Name:</label><br>
                            <input type='text' id='last-name' name='lastname' value='" . $existingGeneralInfoRow['LastName'] . "' />
                            <br><br>
                            <label for='major'><span class='required'>*</span>Major:</label><br>
                            <input type='text' id='major' name='major' value='" . $existingGeneralInfoRow['Major'] . "'/>
                            <br><br>
                            <div><span class='required'>*</span>Are you currently seeking employment?</div>
                            <div>Note: Selecting no should exclude your contact information from being given to employers.</div>";

                    if ($seekingEmploy === "T") {
                        echo "  <input type='radio' id='employ-yes' name='seeking_employment' value='T' checked/>
                                <label for='employ-yes'>Yes</label>
                                <input type='radio' id='employ-no' name='seeking_employment' value='F' />
                                <label for='employ-no'>No</label>";
                    } elseif ($seekingEmploy === "F") {
                        echo "  <input type='radio' id='employ-yes' name='seeking_employment' value='T' />
                                <label for='employ-yes'>Yes</label>
                                <input type='radio' id='employ-no' name='seeking_employment' value='F' checked/>
                                <label for='employ-no'>No</label>";
                    } else {
                        echo "  <input type='radio' id='employ-yes' name='seeking_employment' value='T' />
                                <label for='employ-yes'>Yes</label>
                                <input type='radio' id='employ-no' name='seeking_employment' value='F' />
                                <label for='employ-no'>No</label>";
                    }

                    echo "  <br><br>
                            <label for='grad-date'><span class='required'>*</span>Expected/Actual Graduation Date</label>
                            <input type='date' id='grad-date' name='graduation_date' value='" . $fixDate . "' /><br>";
                }
            } else {
                echo "  <div>M#: </div>
                            <div>" . $loadedStudent . "</div>
                            <br>
                            <label for='first-name'><span  class='required'>*</span>First Name:</label><br>
                            <input type='text' id='first-name' name='firstname' />
                            <br><br>
                            <label for='last-name'><span class='required'>*</span>Last Name:</label><br>
                            <input type='text' id='last-name' name='lastname'  />
                            <br><br>
                            <label for='major'><span class='required'>*</span>Major:</label><br>
                            <input type='text' id='major' name='major' />
                            <br><br>
                            <div><span class='required'>*</span>Are you currently seeking employment?</div>
                            <div>Note: Selecting no should exclude your contact information from being given to employers.</div>";

                echo "  <input type='radio' id='employ-yes' name='seeking_employment' value='T' />
                                <label for='employ-yes'>Yes</label>
                                <input type='radio' id='employ-no' name='seeking_employment' value='F' />
                                <label for='employ-no'>No</label>";

                echo "  <br><br>
                            <label for='grad-date'><span class='required'>*</span>Expected Graduation Date</label>
                            <input type='date' id='grad-date' name='graduation_date' /><br>";
            }


            ?>
        </div>
        <div>
            <h1>Contact Information</h1>
            <div>This section allows you to provide up to 2 email addresses and 2 phone numbers that employers may
                use to contact you.</div>
            <div class='flexbox'>
                <?php
                $existingEmailSql = "SELECT * FROM SRM.StudentEmailAddress WHERE StudentMNumber = '$loadedStudent'";
                $existingEmailStmt = sqlsrv_query($conn, $existingEmailSql);

                if ($existingEmailStmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                $i = 1;

                while ($existingEmailRow = sqlsrv_fetch_array($existingEmailStmt, SQLSRV_FETCH_ASSOC) and $i <= 2) {
                    echo "<div class = 'item'> 
                                <b>$i</b><br>
                                <label for='email-$i'>Email: </label><input type='text' id='email-$i' name='email_$i' value='" . $existingEmailRow['EmailAddress'] . "'/><br>
                            </div>
                            <br>";
                    $i = $i + 1;
                }

                while ($i <= 2) {
                    echo "<div class = 'item'> 
                                <b>$i</b><br>
                                <label for='email-$i'>Email: </label><input type='text' id='email-$i' name='email_$i' /><br>
                            </div>
                            <br>";
                    $i = $i + 1;
                }

                ?>
            </div>
            <div class='flexbox'>
                <?php
                $existingPhoneSql = "SELECT * FROM SRM.StudentPhoneNumber WHERE StudentMNumber = '$loadedStudent'";
                $existingPhoneStmt = sqlsrv_query($conn, $existingPhoneSql);

                if ($existingPhoneStmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                $i = 1;

                while ($existingPhoneRow = sqlsrv_fetch_array($existingPhoneStmt, SQLSRV_FETCH_ASSOC) and $i <= 2) {
                    echo "<div class = 'item'> 
                                <b>$i</b><br>
                                <label for='phone-$i'>Phone: </label><input type='text' id='phone-$i' name='phone_$i' value='" . $existingPhoneRow['PhoneNumber'] . "'/><br>
                            </div>
                            <br>";
                    $i = $i + 1;
                }

                while ($i <= 2) {
                    echo "<div class = 'item'> 
                                <b>$i</b><br>
                                <label for='phone-$i'>Phone: </label><input type='text' id='phone-$i' name='phone_$i' /><br>
                            </div>
                            <br>";
                    $i = $i + 1;
                }

                ?>
            </div>
        </div>
        <div>
            <h1>Skill Selection</h1>
            <div>If you possess any of the skills or are knowledgeable in any of the applications presented below, check
                them off.</div>
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
                $checkSkillSql = "SELECT *
                                        FROM SRM.StudentSkill as stuski
                                        JOIN SRM.Skills as ski on ski.SkillID = stuski.SkillID
                                        WHERE stuski.SkillID = " . $allSkillRow['SkillID'] . " AND stuski.StudentMNumber = '" . $loadedStudent . "'
                                        ORDER BY ski.SkillCategory, ski.SkillName";

                $checkSkillStmt = sqlsrv_query($conn, $checkSkillSql);

                if ($checkSkillStmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                if ($prevCat != $allSkillRow['SkillCategory']) {
                    echo "<br><b>" . $allSkillRow['SkillCategory'] . "</b><br>";
                }
                $skillCheckResult = sqlsrv_has_rows($checkSkillStmt);
                echo "<input type='checkbox' id='skill-" . $allSkillRow['SkillID'] . "' name='student_skills[]' value='" . $allSkillRow['SkillID'] . "'";

                if ($skillCheckResult == 1) {
                    echo " checked ";
                }
                echo "/> <label for='skill-" . $allSkillRow['SkillID'] . "'>" . $allSkillRow['SkillName'] . "</label>";
                $prevCat = $allSkillRow['SkillCategory'];
            }
            ?>


        </div>
        <div>
            <h1>Certifications</h1>
            <div>
                <div>This section allows you to identify up to 10 certifications you have earned that you believe an
                    employer would be interested in.</div>
                <div>Documentation is optional, but if you provide it, it should be a url that provides evidence
                    of your possession of the certification.</div>
                <div>In this section, fields marked with a "<span class='required'>*</span>" are required only if you
                    intend to use certification slot.</div>
                <div class='flexbox'>
                    <?php
                    $existingCertSql = "SELECT * FROM SRM.StudentCertification WHERE StudentMNumber = '$loadedStudent'";

                    $existingCertStmt = sqlsrv_query($conn, $existingCertSql);

                    $i = 1;

                    while ($existingCertRow = sqlsrv_fetch_array($existingCertStmt, SQLSRV_FETCH_ASSOC) and $i <= 10) {

                        echo "<div class = 'item'> 
                                    <b>$i</b><br>
                                    <label for='cert-name-$i'><span class='required'>*</span>Certification Name: </label><input type='text' id='cert-name-$i' name='certification_$i' value='" . $existingCertRow['CertificationName'] . "'/><br>";


                        if (empty($existingCertRow['AcquisitionDate'])) {
                            $fixDate = 'null';
                            echo "<label for='cert-date-$i'>Date Acquired: </label><input type='date' id='cert-date-$i' name='acquisition_date_$i'value=$fixDate/><br>";
                        } else {
                            $fixDate = $existingCertRow['AcquisitionDate']->format("Y-m-d");
                            echo "<label for='cert-date-$i'>Date Acquired: </label><input type='date' id='cert-date-$i' name='acquisition_date_$i'value='$fixDate'/><br>";
                        }

                        echo "<label for='cert-doc-$i'>Documentation: </label><input type='url' id='cert-doc-$i' name='documentation_$i' value='" . $existingCertRow['Documentation'] . "'/><br>
                        </div>
                        <br>";
                        $i++;
                    }

                    while ($i <= 10) {
                        echo "<div class = 'item'> 
                                    <b>$i</b><br>
                                    <label for='cert-name-$i'><span class='required'>*</span>Certification Name: </label><input type='text' id='cert-name-$i' name='certification_$i' /><br>
                                    <label for='cert-date-$i'>Date Acquired: </label><input type='date' id='cert-date-$i' name='acquisition_date_$i' /><br>
                                    <label for='cert-doc-$i'>Documentation: </label><input type='url' id='cert-doc-$i' name='documentation_$i' /><br>
                                </div>
                                <br>";
                        $i++;
                    }
                    ?>
                </div>
            </div>
        </div>
        <div>
            <h1>Job History</h1>
            <div>
                <div>This section allows you to indicate up to five job positions you have previously held.</div>
                <div>If you currently hold the position, leave the end date blank.</div>
                <div>In this section, fields marked with a "<span class='required'>*</span>" are required only if you
                    intend to use that job history slot.</div>
                <div class='flexbox'>
                    <?php

                    $existingJobSql = "SELECT * FROM SRM.StudentJobHistory WHERE StudentMNumber = '$loadedStudent'";

                    $existingJobStmt = sqlsrv_query($conn, $existingJobSql);

                    $i = 1;

                    while ($existingJobRow = sqlsrv_fetch_array($existingJobStmt, SQLSRV_FETCH_ASSOC) and $i <= 5) {
                        if ($existingJobRow['StartDate'] === null) {
                            $fixStartDate = null;
                        } else {
                            $fixStartDate = $existingJobRow['StartDate']->format("Y-m-d");
                        }
                        if ($existingJobRow['EndDate'] === null) {
                            $fixEndDate = null;
                        } else {
                            $fixEndDate = $existingJobRow['EndDate']->format("Y-m-d");
                        }


                        echo "<div class = 'item'> 
                                    <b>$i</b><br>
                                    <label for='org-$i'><span class='required'>*</span>Organization/Company Name: </label><input type='text' id='job-org-$i' name='organization_$i' value='" . $existingJobRow['OrganizationName'] . "' /><br>
                                    <label for='job-title-$i'><span class='required'>*</span>Job Title: </label><input type='text' id='job-title-$i' name='title_$i' value='" . $existingJobRow['JobTitle'] . "' /><br>
                                    <label for='job-start-$i'><span class='required'>*</span>Date Started: </label><input type='date' id='job-start-$i' name='start_date_$i' value='" . $fixStartDate . "' /><br>
                                    <label for='job-end-$i'>Date Ended: </label><input type='date' id='job-end-$i' name='end_date_$i' value='" . $fixEndDate . "' /><br>
                                    <label for='job-desc-$i'>Briefly Describe your duties and activities you performed while in this position:</label><br>                                
                                    <textarea id='job-desc-$i' name='description_$i'>" . $existingJobRow['AdditionalInfo'] . "</textarea><br>
                                </div>
                                <br>";
                        $i++;

                    }

                    while ($i <= 5) {
                        echo "<div class = 'item'> 
                                    <b>$i</b><br>
                                    <label for='job-org-$i'><span class='required'>*</span>Organization/Company Name: </label><input type='text' id='job-org-$i' name='organization_$i' /><br>
                                    <label for='job-title-$i'><span class='required'>*</span>Job Title: </label><input type='text' id='job-title-$i' name='title_$i' /><br>
                                    <label for='job-start-$i'><span class='required'>*</span>Date Started: </label><input type='date' id='job-start-$i' name='start_date_$i' /><br>
                                    <labe for='job-end-$i'l>Date Ended: </label><input type='date' id='job-end-$i' name='end_date_$i' /><br>
                                    <label for='job-desc-$i'>Briefly Describe your duties and activities you performed while in this position:</label><br>                                
                                    <textarea id='job-desc-$i' name='description_$i'></textarea><br>
                                </div>
                                <br>";
                        $i++;

                    }

                    ?>

                </div>
            </div>
        </div>
        <div class="messages"> </div>
        <br>
        <input type='submit' id='save' name='save' class='button' value='Save Changes' />
    </form>

</body>


</html>