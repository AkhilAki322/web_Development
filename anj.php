<?php
    if(!empty($_POST["name"])){
        $rr = $_POST["name"] % 2;
        
        if($rr == 1){
            echo "odd";
        }
        else{
            echo "even";
        }
    }
?>

<html>
<body>

<form action="anj.php" method="post">
Value: <input type="text" name="name"><br><br>
<input type="submit">
    <!--Submit-->
</form>

</body>
</html
