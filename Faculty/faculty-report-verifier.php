<?php

date_default_timezone_set('America/Chicago');

if (isset($_POST['generate'])) {

    if (empty($_POST['mnums'])) {
        $mnums = null;
    } else {
        $mnums = $_POST['mnums'];
    }

    $errorMNums = false;

    if ($mnums === null) {
        echo "<div class='error'>No M#s have been selected for report generation.</div>";
        $errorMNums = true;
    }

    if ($errorMNums === false) {

        echo "<form id='forward' action='/cis420/202380/Faculty/faculty-report-generate.php' method='post'>";
        foreach ($mnums as $a => $mnum) {
            echo "<input type='hidden' name='$a' value='$mnum'>";
        }
        echo "</form>";

        $time = date("g:i:s A e");
        echo "<div class='success'>Report Generated at $time</div>";
    }

} else {
    echo "<div class='error'>Invalid M# submission for report generation.</div>";
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    document.getElementById('forward').submit();
</script>