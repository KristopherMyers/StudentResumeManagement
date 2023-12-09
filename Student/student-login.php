<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="/cis420/202380/resumecollectionstyles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>KMyers21 CIS420 Project Student Login Page</title>

    <script>
        $(document).ready(function () {
            $("form").submit(function (event) {
                event.preventDefault();
                var mnum = document.getElementById('mnum').value;
                var mnumNumsString = mnum.substring(1, 9);
                var mnumNums = Number(mnumNumsString, 10);
                if (mnum.search('M') === 0) {
                    if (mnum.length == 9) {
                        if (!isNaN(mnumNums)) {
                            document.getElementById('login_form').submit();
                        }
                        else {
                            alert(mnum + " does not have 8 digits as its ending, Try Again.")
                        }
                    }
                    else {
                        alert(mnum + " is not the correct length, Try Again.")
                    }
                }
                else {
                    alert(mnum + " does not start with M, Try Again.")
                }

            })
        })
    </script>

</head>

<form class='centered' id='login_form' method='post' action='/cis420/202380/Student/student.php'>

    <label class='text-huge' for='mnum'>M#</label>
    <input class='text-huge' type='text' id='mnum' name='mnum' placeholder='M# Here' />
    <input class='text-huge' type='submit' id='login' name='save' class='button' value='Login' />
</form>

</html>