<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="/cis420/202380/resumecollectionstyles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>KMyers21 CIS420 Project Admin Page</title>

    <?php
    $serverName = "sql7.hostek.com"; //serverName\instanceName
    $connectionInfo = array("Database" => "WEB3_CIS420", "UID" => "CIS420", "PWD" => "C!S420_fall2023");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    ?>

    <script>
        $(document).ready(function () {
            var globalMNum = '';
            $("#skillAddForm").submit(function (event) {
                event.preventDefault();
                var skilltoadd = document.getElementById('skill_name').value;
                var categoryofnewskill = document.getElementById('category_name').value;
                var add = $("#add").val();
                $(".skill-add-response").load("/cis420/202380/Admin/admin-skill-add.php", {
                    skill: skilltoadd,
                    category: categoryofnewskill,
                    add: add
                });

            })
            $("#refreshTable").submit(function (event) {
                event.preventDefault();
                $(".small-table").load("/cis420/202380/Admin/admin-existing-skills.php");
            })
            $("#recordRetrieveForm").submit(function (event) {
                event.preventDefault();
                var retrievedMNum = document.getElementById('m_number').value;
                var mnumNumsString = retrievedMNum.substring(1, 9);
                var mnumNums = Number(mnumNumsString, 10);
                var retrieve = $("#retrieve").val();
                if (retrievedMNum.search('M') === 0) {
                    if (retrievedMNum.length == 9) {
                        if (!isNaN(mnumNums)) {
                            globalMNum = retrievedMNum;
                            $(".record-retrieval-response").load("/cis420/202380/Admin/admin-get-single-student-record.php", {
                                mnum: retrievedMNum,
                                retrieve: retrieve
                            });
                        }
                        else {
                            alert(retrievedMNum + " does not have 8 digits as its ending, Try Again.")
                        }
                    }
                    else {
                        alert(retrievedMNum + " is not the correct length, Try Again.")
                    }
                }
                else {
                    alert(retrievedMNum + " does not start with M, Try Again.")
                }
            })
            $("#recordDeleteForm").submit(function (event) {
                event.preventDefault();
                var deletereq = $("#delete").val();
                if (globalMNum != '') {
                    $(".record-deletion-response").load("/cis420/202380/Admin/admin-delete-data.php", {
                        delete: deletereq,
                        mnum: globalMNum
                    });
                }
                else {
                    alert("No record has been retrieved for deletion yet.")
                }

            })
            $(".remove").click(function (event) {
                alert("clicked");
                /*$.ajax({
                    type: 'POST',
                    url: '/cis420/202380/Admin/admin-delete-skills.php',
                    success: function (data) {
                        alert(data);
                        $(".skill-deletion-response").text(data);
                    }
                })*/
            })
            $(".small-table").load("/cis420/202380/Admin/admin-existing-skills.php");
        });
    </script>
</head>

<body>
    <header>
        <img class="logo" src="/cis420/202380/images/primary_white.png" alt="Murray State University logo">
        <img class="profile-picture" title="Currently logged in as Name" src="/cis420/202380/images/blank-profile.png"
            alt="Profile Picture">
        <div class="head-text">Administrator Page</div>
    </header>
    <div class='window-left'>
        <h1>Add Skill</h1>
        <form id='skillAddForm'>
            <label for="category_name">Category Name:</label><input type='text' id='category_name' placeholder="Category here" />
            <br>
            <label for="skill_name">Skill Name:</label><input type='text' id='skill_name'
                placeholder="Skill here" />
            <br>
            <input type="submit" id="add" name="add" value="Add Entered Skill" />
        </form>
        <div class='skill-add-response'></div>
        <h2>Existing Skills</h2>
        <div class="small-table">
        </div>
        <div class='skill-deletion-response'></div>
        <form id='refreshTable'>
            <input type="submit" id="refresh" name="refresh" value='Refresh Table' />
        </form>
    </div>
    <div class='window-right'>
        <h1>Record Deletion</h1>
        <form id='recordRetrieveForm'>
            <label for="m_number">M# to View:</label><input type='text' id='m_number' placeholder="M#" />
            <br>
            <input type="submit" id="retrieve" name="retrieve" value="Retrieve Record for Review" />
        </form>
        <br>
        <div class='record-retrieval-response student-table'>
        </div>
        <form id='recordDeleteForm'>
            <input type='submit' id='delete' name='delete' value='Delete This Record' />
            <div class='error'>Warning: Deletion cannot be undone.</div>
        </form>
        <div class='record-deletion-response'></div>
    </div>
</body>