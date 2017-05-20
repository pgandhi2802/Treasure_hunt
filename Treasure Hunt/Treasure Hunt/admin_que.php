<?php
include 'connect.php';
if($_SERVER['REQUEST_METHOD']=="POST")
{
    $que_id=$_POST['que_id'];
    $img=$_POST['image_name'];
    $answer1=$_POST['answer1'];
    $answer2=$_POST['answer2'];
    $answer3=$_POST['answer3'];
    $answer4=$_POST['answer4'];
    $answer5=$_POST['answer5'];
    move_uploaded_file($_FILES["file"]["tmp_name"],
    "ques/" . $_FILES["file"]["name"]);
    $old="ques/" . $_FILES["file"]["name"];
    $info = new SplFileInfo($old);
    $ext=$info->getExtension();
    $img_name=$img.'.'.$ext;
    $new="ques/".$img_name;
    rename($old,$new); 
    echo $que_id;
    echo $img_name;
    echo $answer1;
    echo $answer2;
    echo $answer3;
    echo $answer4;
    echo $answer5;
    $query="select que_id from question where que_id='$que_id'";
    $result=  mysqli_query($con,$query);
    $count=  mysqli_num_rows($result);
    if($count==0)
    {
        $query1="insert into question (que_id,name,answer1,answer2,answer3,answer4,answer5)values('$que_id','$img_name','$answer1','$answer2','$answer3','$answer4','$answer5')";
        mysqli_query($con,$query1);
    }
    else
    {
        $query1="update question set name='$img_name',answer1='$answer1',answer2='$answer2',answer3='$answer3',answer4='$answer4',answer5='$answer5' where que_id='$que_id'";
        mysqli_query($con,$query1);
    }
}
?>
<html>
<body>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
    <center>
        <table>
            <tr><td>Choose a Picture</td><td>:</td><td><input type="file" name="file"></td></tr>
            <tr><td>Question No</td><td>:</td><td><input type="text" name="que_id" /></td></tr>
            <tr><td>Image Name</td><td>:</td><td><input type="text" name="image_name" /></td></tr>
            <tr><td>answer1</td><td>:</td><td><input type="text" name="answer1" /></td></tr>
            <tr><td>answer2</td><td>:</td><td><input type="text" name="answer2" /></td></tr>
            <tr><td>answer3</td><td>:</td><td><input type="text" name="answer3" /></td></tr>
            <tr><td>answer4</td><td>:</td><td><input type="text" name="answer4" /></td></tr>
            <tr><td>answer5</td><td>:</td><td><input type="text" name="answer5" /></td></tr>
            <tr><td></td><td></td><td><input type="submit" name="submit" value="Submit"></td></tr>
    </table>
</center>
</form>

</body>
</html>