<?php 
    $error=0;
    include 'connect.php';
    session_start();
    //  to get client ip
    function getIp(){
        $ip = $_SERVER['REMOTE_ADDR'];     
        if($ip){
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            return $ip;
        }
        return false;
    }
    $user=$_SESSION['user'];
    $block="select user from blocked where user='$user'";
    $result=  mysqli_query($con,$block);
    $cnt=  mysqli_num_rows($result);
    if($cnt!=0)
        header("location:block.php");
    //to count maximum question
    $max_que_query=  mysqli_query($con,"select que_id from question");
    $max_que=  mysqli_num_rows($max_que_query);
    //check log in
    if($_SESSION['loggedin'])
    {
        $user=$_SESSION['user'];
        $query="select * from user where user='$user'";
        $result=mysqli_query($con,$query);
        $user_detail=  mysqli_fetch_assoc($result);$score=$user_detail['score'];
        if($_SERVER['REQUEST_METHOD']=="POST")
        {
            $que=$_POST['que_no'];
            $ans_post=strtolower ($_POST['ans']);
            $ip=getIp();
            $query1="insert into submission (user,que_id,ans,ip)values('$user','$que','$ans_post','$ip')";
            mysqli_query($con,$query1);
            $query2="insert into user_$user (que_id,ans,ip) values('$que','$ans_post','$ip')";
            mysqli_query($con,$query2);
            $query="select answer1,answer2,answer3,answer4,answer5 from question where que_id='$que'";
            $result=mysqli_query($con,$query);
            $ans=mysqli_fetch_assoc($result);
            if($ans_post==$ans['answer1']||$ans_post==$ans['answer2']||$ans_post==$ans['answer3']||$ans_post==$ans['answer4']||$ans_post==$ans['answer5'])
            {
                $score=$_POST['score']+1;
                $query="update user set score='$score',ip='$ip' where user='$user'";
                $done=mysqli_query($con,$query);
             }
            else 
            {
                $error=1;
            }?>
             <!--to show content at start-->
<script src="js/show_hide.js"></script>
            <script>
            $(document).ready(function(){
                $("#rule").hide();
                $("#about").hide();
                $("#leaderboard").hide();
                $("#show_about").click(function(){
                $("#about").show();
                $("#rule").hide();
                $("#signup").hide();
                $("#leaderboard").hide();
                });
                $("#show_rule").click(function(){
                $("#about").hide();
                $("#rule").show();
                $("#signup").hide();
                $("#leaderboard").hide();
                });
                $("#show_signup").click(function(){
                $("#about").hide();
                $("#rule").hide();
                $("#signup").show();
                $("#leaderboard").hide();
                });
                $("#show_leaderboard").click(function(){
                $("#about").hide();
                $("#rule").hide();
                $("#signup").hide();
                $("#leaderboard").show();
                });
            });
        </script><?php
        }
        else
        { ?>
        <!-- while solving -->
            <script src="js/show_hide.js"></script>
            <script>
            $(document).ready(function(){
                $("#rule").hide();
                $("#signup").hide();
                $("#leaderboard").hide();
                $("#show_about").click(function(){
                $("#about").show();
                $("#rule").hide();
                $("#signup").hide();
                $("#leaderboard").hide();
                });
                $("#show_rule").click(function(){
                $("#about").hide();
                $("#rule").show();
                $("#signup").hide();
                $("#leaderboard").hide();
                });
                $("#show_signup").click(function(){
                $("#about").hide();
                $("#rule").hide();
                $("#signup").show();
                $("#leaderboard").hide();
                });
                $("#show_leaderboard").click(function(){
                $("#about").hide();
                $("#rule").hide();
                $("#signup").hide();
                $("#leaderboard").show();
                });
                $("#show_que").click(function(){
                $("#about").hide();
                $("#rule").hide();
                $("#signup").show();
                $("#leaderboard").hide();
                });
            });
        </script>
<?php         }
        ?>
    

<html>
    <head>
        <meta charset="UTF-8">
        <title>Treasure Hunt</title>
        <script src="js/right_click_disable.js"></script>
        <link href="css/start.css" rel="stylesheet" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=The+Girl+Next+Door' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Metal+Mania' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div id="header">
            <div id="wrapper_header">
                <img src="image/csi_logo.jpg" />&nbsp;&nbsp;&nbsp;&nbsp;
                <br />
                <div id="event_name">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Online Treasure Hunt
            </div>
                <div style="width:270px;float:right;"id="login">
                    <div style="float:left;border-right:2px solid white;"id="user_detail"><?php echo $user_detail['fname'].' '.$user_detail['lname']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    <div id="show_score">Score : <?php echo $score; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a style="height:60px;border-left:2px solid white;"href="logout.php">&nbsp;&nbsp;&nbsp;&nbsp;Logout</a></div>
                </div>
            </div>
        </div>
        <div id="wrapper_content">
            
            <div id="show_about_sign-up_leaderboard">
                <div id="show_about" style="cursor:pointer;">About&nbsp;/&nbsp;</div>
                <div id="show_rule" style="cursor:pointer;">Rules&nbsp;/&nbsp;</div>
                <div id="show_signup" style="cursor:pointer;">Start Solving&nbsp;/&nbsp;</div>
                <div id="show_leaderboard" style="cursor:pointer;">Leaderboard</div>
            </div>
        </div><br /><br /><br />
    <center><div id="que_track">
            <?php 
                $query="select * from user where user='$user'";
                $result=mysqli_query($con,$query);
                $user=  mysqli_fetch_assoc($result);
                $que=$user['score']+1;
                $query_que=mysqli_query($con,"select name from question where  que_id='$que'");
                $que_id=  mysqli_fetch_assoc($query_que);
                $que_track=1;
                while($que_track<$que)
                    { ?><div class="que_trck_disable"><?php echo 'Q'.$que_track;?></div>
                    <div class="que_trck_disable" id="slash">/</div>
                    <?php
                    $que_track=$que_track+1;
                }
                if($que_track!=$max_que+1)
                {
            ?>
                <div class="que_trck_disable" id="show_que" style="cursor:pointer;"><?php echo 'Q'.$que_track;?></div> <?php }
                else
                { ?>
                    <div class="que_trck_disable" id="show_que" style="cursor:pointer;">END</div> <?php 
                }?>
        </div></center>
        <div id="about_signup_leaderboard">
            <!--About page -->
            <div id="about"><h2>About</h2><hr /><p>For the Second Time in History of JUET. The College presents an ONLINE TREASURE HUNT. </p><p>Participate and Discover dazzling world of Fun and Knowledge</p></div>
            <!--Rules Page -->
            <div id="rule"><h2>Rules</h2><hr />
                <ul>
                    <li>Registering for an event is compulsory.</li>
                    <li>1 member/Single Event.</li>
                    <li>Time Limit :- 2 days.</li>
                    <li>Without answering a question you can’t move on to the next question.</li>
                    <li>In case answer is a name, use a space between first name and last name. (eg :- Pranab Mukherjee).</li>
                    <li>Special characters are not allowed.</li>
                </ul>
            </div>
            <!-- Start solving-->
            <div id="signup" >
                <center>
                    <table>
                        <?php 
                        
                            if($que<=$max_que)
                            {
                               $img_nm='ques/'.$que_id['name'];
                               $name_img=  strtolower($img_nm);
                        ?>
                                <img src=<?php echo $name_img; ?> /><br /><br />
                                <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                                    <input type="text" name="que_no" value="<?php echo $que;?>" hidden />
                                    <input type="text" name="score" value="<?php echo $user['score']; ?>" hidden/>
                                    <input type="text" name="ans" /><?php
                            if($error==1)
                            { ?>
                                <div id="err">Wrong Answer</div>
                        <?php }
                        ?><br /><br />
                                    <input type="submit" name="submit" />
                                </form>
                       <?php }
                             else 
                             { ?>
                                <div id="alert" >You have got treasure at <?php echo $user['time'];?></div>
                       <?php }
                             ?>
                    </table>
                </center>
            </div>
            <!-- leaderboard -->
            <div id="leaderboard"><h2>Score Board</h2><hr />
                <center>                <table>
                    <th>Rank</th>&nbsp;&nbsp;<th>Enrollment</th>&nbsp;&nbsp;<th>Score</th>&nbsp;&nbsp;<th>Last Submisson Time</th>
               <?php    
                    $rank=0;
                    $query_leader="select * from user order by score desc,time asc";
    $result_leader=mysqli_query($con,$query_leader);
                while($user_leader=  mysqli_fetch_array($result_leader))
    { $rank=$rank+1;?>
                    
                    <tr><td><?php echo $rank;?></td>&nbsp;&nbsp;<td><?php echo $user_leader['user']; ?></td>&nbsp;&nbsp;<td><?php echo $user_leader['score'];?></td>&nbsp;&nbsp;<td><?php echo $user_leader['time'];?></td></tr>
        
    <?php } ?>
                    </table></center>
            </div>
        </div>
		<div style="margin-top:60px;" id="footer">
		<center>CSI JUET</center>
		</div>
    </body>
</html>
<?php }
else
{
    header('location:login.php');
}
?>