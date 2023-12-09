<?php
$serverName = "sql7.hostek.com"; //serverName\instanceName
$connectionInfo = array("Database" => "WEB3_CIS420", "UID" => "CIS420", "PWD" => "C!S420_fall2023");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn) {
    if (isset($_POST['load'])) {
    class StudentClass
    {
        public $include;
        public $mNumber;
        public $name;
        public $major;
        public $contact = [[]];
        public $skills = [[]];
        public $certifications = [];
        public $seekEmpl;
        public $gradDate;
        public $jobHistory = [[]];

        public function outputRow()
        {
            echo "<tr>
            <td class='centered-column'><input type='checkbox' id='box-$this->mNumber' name='mnum_boxes' value='$this->mNumber'";
            if ($this->include === "T") { 
                echo " checked";
            }
            echo "/></td>
                <td>$this->mNumber</td>
                <td>$this->name</td>
                <td>$this->major</td>
                <td>";

            $contactkeys = array_keys($this->contact);

            for ($i = 1; $i < count($this->contact); $i++) {
                echo "<b>" . $contactkeys[$i] . "</b><ul>";
                foreach ($this->contact[$contactkeys[$i]] as $value) {
                    echo "<li>" . $value . "</li>";
                }
                echo "</ul>";
            }
            echo
                '</td>
                    <td>';
            $skillkeys = array_keys($this->skills);
            for ($i = 1; $i < count($this->skills); $i++) {
                echo "<b>" . $skillkeys[$i] . "</b><ul>";
                foreach ($this->skills[$skillkeys[$i]] as $value) {
                    echo "<li>" . $value . "</li>";
                }
                echo "</ul>";
            }
            echo
                '</td>
                    <td>';
            if (count($this->certifications) > 0) {
                echo "<ul>";
                foreach ($this->certifications as $certification) {
                    echo "<li>" . $certification . '</li>';
                }
                ;
                echo "</ul>";
            }
            echo
                '</td>';
            if ($this->seekEmpl == 'T') {
                echo '<td class="centered-column" title="This student is seeking employment."><svg xmlns="http://www.w3.org/2000/svg" width="auto" height="auto" fill="green" class="bi bi-check-lg" viewBox="0 0 16 16">
                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                      </svg></td>';
            } elseif ($this->seekEmpl == 'F') {
                echo '<td class="centered-column" title="This student is not seeking employment."><svg xmlns="http://www.w3.org/2000/svg" width="auto" height="auto" fill="red" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                      </svg></td>';
            } else {
                echo '<td class="centered-column" title="Something went wrong when determining if this student is seeking employment."><svg xmlns="http://www.w3.org/2000/svg" width="auto" height="auto" fill="current" class="bi bi-question-lg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M4.475 5.458c-.284 0-.514-.237-.47-.517C4.28 3.24 5.576 2 7.825 2c2.25 0 3.767 1.36 3.767 3.215 0 1.344-.665 2.288-1.79 2.973-1.1.659-1.414 1.118-1.414 2.01v.03a.5.5 0 0 1-.5.5h-.77a.5.5 0 0 1-.5-.495l-.003-.2c-.043-1.221.477-2.001 1.645-2.712 1.03-.632 1.397-1.135 1.397-2.028 0-.979-.758-1.698-1.926-1.698-1.009 0-1.71.529-1.938 1.402-.066.254-.278.461-.54.461h-.777ZM7.496 14c.622 0 1.095-.474 1.095-1.09 0-.618-.473-1.092-1.095-1.092-.606 0-1.087.474-1.087 1.091S6.89 14 7.496 14Z"/>
                          </svg></td>';
            }
            echo
                '<td>' . $this->gradDate . '</td>
                            <td> <ul>';
            $jobkeys = array_keys($this->jobHistory);
            for ($i = 1; $i < count($this->jobHistory); $i++) {
                echo '<li>' . $this->jobHistory[$jobkeys[$i]]["Title"] . ' at ' . $this->jobHistory[$jobkeys[$i]]["Organization"] . ', ' . $this->jobHistory[$jobkeys[$i]]["Start"] . ' to ' . $this->jobHistory[$jobkeys[$i]]["End"] . '<br>';
                echo '<ul><li class="allow-wrap">' . $this->jobHistory[$jobkeys[$i]]["Info"] . '</li></ul></li>';
            }
            echo
                '</ul>
               </td>
              <tr>';
        }

        //Constructor to set the M# of this student object
        public function setMNum(string $toset)
        {
            $this->mNumber = $toset;
        }

        //Constructor to set the name of this student object
        public function setName(string $toset)
        {
            $this->name = $toset;
        }

        //Constructor to set the major of this student object
        public function setMajor(string $toset)
        {
            $this->major = $toset;
        }

        //Constructor to set the seeking employment status of this student object
        public function setSeekEmpl(string $toset)
        {
            $this->seekEmpl = $toset;
        }

        //Constructor to set the graduation date of this student object
        public function setGradDate(string $toset)
        {
            $this->gradDate = $toset;
        }

        //Constructor to set phone numbers for this student object
        public function setContactPhone(string $toset)
        {
            if (array_key_exists('Phone Number(s)', $this->contact)) {
                $this->contact['Phone Number(s)'][count($this->contact['Phone Number(s)'])] = $toset;
            } else {
                $this->contact['Phone Number(s)'][0] = $toset;
            }
        }

        //Constructor to set email addresses for this student object
        public function setContactEmail(string $toset)
        {
            if (array_key_exists('Email Address(es)', $this->contact)) {
                $this->contact['Email Address(es)'][count($this->contact['Email Address(es)'])] = $toset;
            } else {
                $this->contact['Email Address(es)'][0] = $toset;
            }
        }

        //Constructor to set skills for this student object
        public function setSkill(string $category, string $name)
        {
            if (array_key_exists($category, $this->skills)) {
                $this->skills[$category][count($this->skills[$category])] = $name;
            } else {
                $this->skills[$category][0] = $name;
            }
        }

        //Constructor to set certfications for this student object
        public function setCert(string $toset)
        {
            $this->certifications[] = $toset;
        }

        //Constructor to create job history for this student object
        public function setJobHistory(string $id, string $title, string $organization, string $start, string $end, string $info)
        {
            $this->jobHistory[$id]['Title'] = $title;
            $this->jobHistory[$id]['Organization'] = $organization;
            $this->jobHistory[$id]['Start'] = $start;
            $this->jobHistory[$id]['End'] = $end;
            $this->jobHistory[$id]['Info'] = $info;
        }


    }

    echo "<tr>
            <th class='contains-info' title='Students that are checked off will be included in the PDF report.'>Incl.</th>
            <th>M#</th>
            <th>Name</th>
            <th>Major</th>
            <th>Contact Information</th>
            <th>Skills</th>
            <th>Certifications</th>
            <th>Seeking Employment</th>
            <th>Expected Graduation Date</th>
            <th>Employment History</th>
        </tr>";

    //The sql statement used to get all general information from the student table, the M# of which is a foreign key in other tables
    $mainsql = "SELECT MNumber, FirstName, LastName, SeekingEmployment, ExpectedGraduationDate, Major
    FROM SRM.Students
    ORDER BY MNumber";


    //Function to return only rows in the skill table that match the M# given
    function skillsql($studentMNum)
    {
        return
            "SELECT stud.MNumber, ski.SkillCategory, ski.SkillName
            FROM SRM.Students as stud                     
            JOIN SRM.StudentSkill as stuski ON stud.MNumber=stuski.StudentMNumber
            JOIN SRM.Skills as ski ON stuski.SkillID=ski.SkillID
            WHERE stud.MNumber = '" . $studentMNum . "'                         
            ORDER BY stud.MNumber, ski.SkillCategory, ski.SkillName";
    }

    //Function to return only rows in the certification table that match the M# given
    function certsql($studentMNum)
    {
        return
            "SELECT stud.MNumber, stucer.CertificationName
            FROM SRM.Students as stud
            JOIN SRM.StudentCertification as stucer ON stud.MNumber=stucer.StudentMNumber
            WHERE stud.MNumber = '" . $studentMNum . "'
            ORDER BY stucer.CertificationName";
    }

    //Function to return only rows in the email address table that match the M# given
    function emailsql($studentMNum)
    {
        return
            "SELECT stud.MNumber, stuema.EmailAddress
            FROM SRM.Students as stud
            JOIN SRM.StudentEmailAddress as stuema ON stud.MNumber=stuema.StudentMNumber
            WHERE stud.MNumber = '" . $studentMNum . "'";
    }

    //Function to return only rows in the phone number table that match the M# given
    function phonesql($studentMNum)
    {
        return
            "SELECT stud.MNumber, stupho.PhoneNumber
            FROM SRM.Students as stud
            JOIN SRM.StudentPhoneNumber as stupho ON stud.MNumber=stupho.StudentMNumber
            WHERE stud.MNumber = '" . $studentMNum . "'";
    }

    function jobsql($studentMNum)
    {
        return
            "SELECT stud.MNumber, stujob.JobID, stujob.JobTitle, stujob.OrganizationName, stujob.StartDate, stujob.EndDate, stujob.AdditionalInfo
            FROM SRM.Students as stud
            JOIN SRM.StudentJobHistory as stujob ON stud.MNumber=stujob.StudentMNumber
            WHERE stud.MNumber = '$studentMNum'";
    }



        //Variable that contains the results of the mainsql query run on the connected database
        $mainstmt = sqlsrv_query($conn, $mainsql);

        //Ensures query given by mainsql is correct syntax wise
        if ($mainstmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        //Runs for every row returned by mainsql
        while ($mainrow = sqlsrv_fetch_array($mainstmt, SQLSRV_FETCH_ASSOC)) {

            //Convert DateTime to string
            $fixDate = $mainrow['ExpectedGraduationDate']->format("M. jS, Y");

            //Create a new student object named using the student's M# followed by Obj. Ex. M000000000Obj
            ${$mainrow['MNumber'] . 'Obj'} = new StudentClass();

            //Set general properties in the new student object
            ${$mainrow['MNumber'] . 'Obj'}->setMNum($mainrow['MNumber']);
            ${$mainrow['MNumber'] . 'Obj'}->setName($mainrow['LastName'] . ", " . $mainrow['FirstName']);
            ${$mainrow['MNumber'] . 'Obj'}->setMajor($mainrow['Major']);
            ${$mainrow['MNumber'] . 'Obj'}->setSeekEmpl($mainrow['SeekingEmployment']);
            ${$mainrow['MNumber'] . 'Obj'}->setGradDate($fixDate);

            //Variable that contains the results of the skillsql query run on the connected database
            $skillstmt = sqlsrv_query($conn, skillsql($mainrow['MNumber']));

            //Ensures query given by skillsql function is correct syntax wise
            if ($skillstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            //Set skills in the student object
            while ($skillrow = sqlsrv_fetch_array($skillstmt, SQLSRV_FETCH_ASSOC)) {
                ${$mainrow['MNumber'] . 'Obj'}->setSkill($skillrow['SkillCategory'], $skillrow['SkillName']);
            }

            //Variable that contains the results of the certsql query run on the connected database
            $certstmt = sqlsrv_query($conn, certsql($mainrow['MNumber']));

            //Ensures query given by certsql function is correct syntax wise
            if ($certstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            //Set certifications in the student object
            while ($certrow = sqlsrv_fetch_array($certstmt, SQLSRV_FETCH_ASSOC)) {
                ${$mainrow['MNumber'] . 'Obj'}->setCert($certrow['CertificationName']);
            }

            //Variable that contains the results of the emailsql query run on the connected database
            $emailstmt = sqlsrv_query($conn, emailsql($mainrow['MNumber']));

            //Ensures query given by emailsql function is correct syntax wise
            if ($emailstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            //Set emails in the student object
            while ($emailrow = sqlsrv_fetch_array($emailstmt, SQLSRV_FETCH_ASSOC)) {
                ${$mainrow['MNumber'] . 'Obj'}->setContactEmail($emailrow['EmailAddress']);
            }

            //Variable that contains the results of the phonesql query run on the connected database
            $phonestmt = sqlsrv_query($conn, phonesql($mainrow['MNumber']));

            //Ensures query given by phonesql function is correct syntax wise
            if ($phonestmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            //Set phone numbers in the student object
            while ($phonerow = sqlsrv_fetch_array($phonestmt, SQLSRV_FETCH_ASSOC)) {
                ${$mainrow['MNumber'] . 'Obj'}->setContactPhone($phonerow['PhoneNumber']);
            }

            //Variable that contains the results of the jobsql query run on the connected database
            $jobstmt = sqlsrv_query($conn, jobsql($mainrow['MNumber']));

            //Ensures query given by jobsql function is correct snytax wise
            if ($jobstmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            //Set job history in the student object
            while ($jobrow = sqlsrv_fetch_array($jobstmt, SQLSRV_FETCH_ASSOC)) {

                if (is_null($jobrow['StartDate'])) {
                    $jobstartfix = "Start date not provided";
                } else {
                    $jobstartfix = $jobrow['StartDate']->format("M. jS, Y");
                }


                if (is_null($jobrow['EndDate'])) {
                    $jobendfix = "Present";
                } else {
                    $jobendfix = $jobrow['EndDate']->format("M. jS, Y");
                }

                ${$mainrow['MNumber'] . 'Obj'}->setJobHistory($jobrow['JobID'], $jobrow['JobTitle'], $jobrow['OrganizationName'], $jobstartfix, $jobendfix, $jobrow['AdditionalInfo']);
            }

            //Output the row
            echo ${$mainrow['MNumber'] . 'Obj'}->outputRow();

        }
    } else {
        echo "<div class='error'>Error creating full table.</div>";
    }
} else {
    echo "<div class='error'>Database connection could not be established.<div><br />";
    die(print_r(sqlsrv_errors(), true));
}
?>