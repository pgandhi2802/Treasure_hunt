<?php
    include"connect.php";
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
    $ip=getIp();
    $block="select ip from blocked where ip='$ip'";
    $result=  mysqli_query($con,$block);
    $cnt=  mysqli_num_rows($result);
    if($cnt!=0)
        header("location:block.php");
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Treasure Hunt</title>
        <script src="js/show_hide.js"></script>
            <?php
            if($_SERVER['REQUEST_METHOD']=="POST")
    { ?>
        <script>
            $(document).ready(function(){
                $("#signup").show();
                $("#about").hide();
                $("#leaderboard").hide();
                $("#show_about").click(function(){
                $("#about").show();
                $("#signup").hide();
                $("#leaderboard").hide();
                });
                $("#show_signup").click(function(){
                $("#about").hide();
                $("#signup").show();
                $("#leaderboard").hide();
                });
                $("#show_leaderboard").click(function(){
                $("#about").hide();
                $("#signup").hide();
                $("#leaderboard").show();
                });
            });
        </script>
<?php    }
    else
    { ?>  
        <script>
            $(document).ready(function(){
                $("#signup").hide();
                $("#leaderboard").hide();
                $("#show_about").click(function(){
                $("#about").show();
                $("#signup").hide();
                $("#leaderboard").hide();
                });
                $("#show_signup").click(function(){
                $("#about").hide();
                $("#signup").show();
                $("#leaderboard").hide();
                });
                $("#show_leaderboard").click(function(){
                $("#about").hide();
                $("#signup").hide();
                $("#leaderboard").show();
                });
            });
        </script>
<?php    }
    
include'connect.php';
if(!isset($_SESSION['loggedin']))
{
    $log_user=$log_pass=$errfname=$errlname=$erruser=$errpass=$errconpass=$erremail=$errcontact="";
    $check=0;
    if(isset($_POST['sign_up']))
    {
        if(!empty($_POST['fname']))
        {
            $fname =$_POST["fname"];
            if(!preg_match("/^[a-zA-Z ]*$/",$fname))
            {
                $errfname = "Only letters allowed"; 
                $check=1;
            }
        }
        else
        {
            $errfname = "Required Field"; 
            $check=1;
        }
        if(!empty($_POST['lname']))
        {
            $lname =$_POST["lname"];
            if(!preg_match("/^[a-zA-Z ]*$/",$lname))
            {
                $errlname = "Only letters allowed"; 
                $check=1;
            }
        }
        else
        {
            $errlname = "Required Field"; 
            $check=1;
        }
        
       if(!empty($_POST['email']))
       {
            $email =$_POST["email"];
            if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
            {
                $erremail = "Invalid email format";
                $check=1;
            }
            $sql="select * from user where email='$email'";
            $query=mysqli_query($con,$sql);
            $check2=mysqli_num_rows($query);
            if($check2!=0)
            {
                $erremail="email already exists";
                $check=1;
            }
        }
        else
        {
            $erremail = "Required Field"; 
            $check=1;
        }
        if(!empty($_POST['contact']))
        {
            $contact=$_POST['contact'];
            if(preg_match("/d{10}/",$contact))
            {
                $errcontact="invalid no.";
                $check=1;
            }
            if(strlen($contact)!=10)
            {
                $errcontact="invalid no.";
                $check=1;
            }
        }
        else
        {
            $errcontact = "Required Field"; 
            $check=1;
        }
        if(!empty($_POST['user']))
        {
            $user=$_POST['user'];
            if(preg_match("/d{10}/",$user))
            {
                $erruser="invalid user";
                $check=1;
            }
            if(strlen($user)!=6)
            {
                $erruser="invalid no.";
                $check=1;
            }
            else
            {
                $user=$_POST['user'];
                $sql="select * from user where user='$user'";
                $query=mysqli_query($con,$sql);
                $check2=mysqli_num_rows($query);
                if($check2!=0)
                {
                    $erruser="user already exists";
                    $check=1;
                }
            }
        }
        else
        {
            $erruser = "Required Field"; 
            $check=1;
        }
        if(!empty($_POST['pass']) && !empty($_POST['pass2']))
        {
            if(!($_POST['pass']==$_POST['pass2']))
            {
                $errconpass="password do not match";
                $check=1;
            }
        }
        else{
            if(empty($_POST['pass']))
            {
                $errpass="Required Field";
                $check=1;
            }
            if(empty($_POST['pass']))
            {
                $errconpass="Required Field";
                $check=1;
            }
        }
        if($check==0)
        {
            $pass=$_POST['pass'];
            $fname=$_POST['fname'];
            $lname=$_POST['lname'];
            $user=$_POST['user'];
            $pass=$_POST['pass'];
            $email=$_POST['email'];
            $contact=$_POST['contact'];
            $score=0;
            $ip=$_SERVER['REMOTE_ADDR'];
            $cre_query="create table user_$user (count int(11) primary key auto_increment,que_id int(2) not null,ans varchar(60) not null,ip varchar(20) not null)";
            mysqli_query($con,$cre_query);
            $query=mysqli_query($con,"insert into user
            (fname,lname,user,password,email,contact,score,ip)
            values
            ('$fname','$lname','$user','$pass','$email','$contact','$score','$ip')");
            if($query)
            {
                $query=mysqli_query($con,"select * from user where user= '$user'");
                $row =  mysqli_fetch_array($query);
                if($_POST['pass']==$row['password'])
                {
                    session_start();
                    $_SESSION['loggedin']=true;
//                    $_SESSION['name']=$fname.' '.$lname;
                    $_SESSION['user']=$user;
                    header("location:start_solving.php");
		}
            }
        }
    }
    if(isset($_POST['log_in']))
    {
        $check=0;
        if(!empty($_POST['user']))
        {
            include 'connect.php';
            $user=$_POST['user'];
            $sql="select * from user where user='$user'";
            $query=mysqli_query($con,$sql);
            if($query)
            {
                $check2=mysqli_num_rows($query);
                if($check2==0)
                {
                    $log_user="user does not exists";
                    $check=1;
                }
            }
        }
        else
        {
            $log_user = "Required Field"; 
            $check=1;
        }
        if(empty($_POST['pass']))
        {
                $log_pass="Required Field";
                $check=1;
        }
        if($check==0)
        {
            $user=$_POST['user'];
            $pass=$_POST['pass'];
            $query=mysqli_query($con,"select * from user where user='$user'");
            $row =  mysqli_fetch_array($query);
            if($_POST['pass']==$row['password'])
            {
                session_start();
                       $_SESSION['loggedin']=true;
//                    $_SESSION['name']=$fname.' '.$lname;
                    $_SESSION['user']=$user;
                    $ip=$_SERVER['REMOTE_ADDR'];
                    $query_log="update user set ip='$ip' where user='$user'";
                    $done_log=mysqli_query($con,$query_log);
                    if($done_log)
                    header("location:start_solving.php");
            }
            else
            {
                header('location:login.php');
            }
        }
    }
}
?>
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
                <div id="event_name">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Online Treasure Hunt
            </div>
                <div id="login">
                <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                    <input type="text" name="user" placeholder="Enrollment No." /><?php echo $log_user; ?>&nbsp;&nbsp;&nbsp;
                    <input type="password" name="pass" placeholder="Password" /><?php echo $log_pass; ?>
                    <input type="submit" name="log_in" value="login" />
                </form>
                </div>
            </div>
        </div>
        
		<div style="min-height: 400px;"id="wrapper">
                    
			<div id="wrapper_content">
				<div id="show_about_sign-up_leaderboard">
					<div id="show_about">About&nbsp;/&nbsp;</div>
					<div id="show_signup">Signup&nbsp;/&nbsp;</div>
					<div id="show_leaderboard">Leaderboard</div>
				</div><br />
			</div>
			<div id="about_signup_leaderboard">
				<div id="about"><h2>About</h2><hr /><p>For the Second Time in History of JUET. The College presents an ONLINE TREASURE HUNT. </p><p>Participate and Discover dazzling world of Fun and Knowledge</p></div>
				<div id="signup"><h2>Sign Up</h2><hr />
					<center>
						<form action="<?php echo $_SERVER["PHP_SELF"];?>"  method="post">
							<table>
								<tr><td>First Name</td><td>:</td><td><input type="text" name="fname"/><br /><div id="error2"><?php echo $errfname; ?></div></td></tr>
								<tr><td>Last Name</td><td>:</td><td><input type="text" name="lname"/><br /><div id="error2"><?php echo $errlname; ?></div></td></tr>
								<tr><td>Enrollment No.</td><td>:</td><td><input type="text" name="user"/><br /><div id="error2"><?php echo $erruser; ?></div></td></tr>
								<tr><td>Password</td><td>:</td><td><input type="password" name="pass"/><br /><div id="error2"><?php echo $errpass; ?></div></td></tr>
								<tr><td>Confirm Password</td><td>:</td><td><input type="password" name="pass2"/><br /><div id="error2"><?php echo $errconpass; ?></div></td></tr>
								<tr><td>Email</td><td>:</td><td><input type="text" name="email"/><br /><div id="error2"><?php echo $erremail; ?></div></td></tr>
								<tr><td>Contact no. </td><td>:</td><td><input type="text" name="contact"/><br /><div id="error2"><?php echo $errcontact; ?></div></td></tr>
								<tr><td></td><td></td><td><input type="submit" name="sign_up" value="Signup" /></td</tr>
							</table>
						</form>
					</center>
				</div>
				<div id="leaderboard"><h2>Score Board</h2><hr />
					<center>
						<table>
							<th>Rank</th>&nbsp;&nbsp;<th>Enrollment</th>&nbsp;&nbsp;<th>Score</th>&nbsp;&nbsp;<th>Last Submisson Time</th>
							<?php    
								$rank=0;
								$query_leader="select * from user order by score desc,time asc";
								$result_leader=mysqli_query($con,$query_leader);
								while($user_leader=  mysqli_fetch_array($result_leader))
								{ $rank=$rank+1;?>
							<tr><td><?php echo $rank;?></td>&nbsp;&nbsp;<td><?php echo $user_leader['user']; ?></td>&nbsp;&nbsp;<td><?php echo $user_leader['score'];?></td>&nbsp;&nbsp;<td><?php echo $user_leader['time'];?></td></tr>
							<?php } ?>
						</table>
					</center>
				</div>
			</div>
		</div>
		</div>
		<div id="footer">
		<center>CSI JUET</center>
		</div>
    </body>
</html>
