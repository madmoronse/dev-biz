<? $conn = new mysqli('localhost', 'u0040607_admin', 'dE40oJBhdE40oJBh');
if ($_REQUEST['p']!=11) die();
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

mysqli_select_db($conn,'u0040607_outmax');
$query="SELECT * FROM  oc_customer LIMIT 11000 , 1000";
$query2="SELECT * FROM  oc_zcustomer LIMIT 11000 , 1000";

$users1 = mysqli_query($conn,$query);
$users2 = mysqli_query($conn,$query2);

$temp_users2=$users2;
$i=0;
$ii=0;
while($row = mysqli_fetch_array($users1))
{

     //$row['customer_id']."<br>";
      while($row2 = mysqli_fetch_array($users2))
      {
        if ($row['customer_id']==$row2['customer_id'])
        {
              if ($row2['password']!=$row['password']){ echo $row2['email'].' - '.$row2['password'];
              echo '<br>';}
            }
      }
      mysqli_data_seek($users2,0);
}


mysqli_close($conn);

?>
