<?php
include 'connect.php';
session_start();
$count_query="select count(count) from submission";
$query_result=mysqli_query($con,$count_query);
while($row=mysqli_fetch_array($query_result))
{
	echo $row['count(count)'];
}
?>