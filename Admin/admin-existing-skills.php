<?php 

$serverName = "sql7.hostek.com"; //serverName\instanceName
$connectionInfo = array("Database" => "WEB3_CIS420", "UID" => "CIS420", "PWD" => "C!S420_fall2023");
$conn = sqlsrv_connect($serverName, $connectionInfo);



echo "<table> 
        <tr>
            <th class='contains-info' title='Allows deletion of a skill.'>Del.</th>
            <th>Category Name</th>
            <th>Skill Name</th>
        </tr>";

        $existingSkillSql = "SELECT * FROM SRM.Skills ORDER BY SkillCategory";
        $existingSkillStmt = sqlsrv_query($conn, $existingSkillSql);
        while ($existingSkillRow = sqlsrv_fetch_array($existingSkillStmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                        
                        <td>
                        <button class='remove' id='".$existingSkillRow["SkillID"]."' name='".$existingSkillRow["SkillID"]."' value='".$existingSkillRow["SkillID"]."'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash error' viewBox='0 0 16 16'>
                        <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'/>
                        <path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'/>
                        </svg>
                        </button>
                        </td>
                        <td>" . $existingSkillRow["SkillCategory"] . "</td>
                        <td>" . $existingSkillRow["SkillName"] . "</td></tr>";
        }
            
        echo "</table>";
?>

<script> 

$(".remove").click(function (event) {
                var deleted_skill = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/cis420/202380/Admin/admin-delete-skill.php',
                    data: {
                        deleted_skill: deleted_skill
                    },
                    success: function (deleted_skill) {
                        $(".skill-deletion-response").html(deleted_skill);
                    }
                })
            })

</script>