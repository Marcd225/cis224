<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Subscribe to our Newsletter</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script>
$(function() 
 { 
      $( "#datepicker" ).datepicker({
      changeMonth:true,
      changeYear:true,
      yearRange:"-100:+0",
      dateFormat:"mm/dd/yy"
     });
 });
 </script>
<body>
<h1>Update your Subscriber information</h1>
<?php
if (isset($_GET['id'])) {
     $SubscriberID = stripslashes($_GET['id']);
	 $SubscriberID = htmlspecialchars( $SubscriberID );
     include("inc_db_newsletter.php");
     if ($DBConnect !== FALSE) {
        $SQLstring = "SELECT subscriberID, name, email, DATE_FORMAT(subscribe_date,'%m/%d/%Y') AS subscribe_date, "
      		. " DATE_FORMAT(confirmed_date,'%m/%d/%Y') AS confirmed_date FROM subscribers WHERE subscriberID=$SubscriberID";
        
		$QueryResult = @mysql_query($SQLstring, $DBConnect);
        while (($Row = mysql_fetch_assoc($QueryResult)) !== FALSE) {
           $SubscriberName = $Row['name'];
           $SubscriberEmail = $Row['email'];
           $SubscriberDate = $Row['subscribe_date'];
           $Confirmed_date =  $Row['confirmed_date'];
        }    
        mysql_close($DBConnect);
        $ShowForm = TRUE;		
     }
	 else {
		 $ShowForm = FALSE;
		 
	 }
}

if (isset($_POST['Submit'])) {
     $FormErrorCount = 0;
     if (isset($_POST['SubName'])) {
          $SubscriberName = stripslashes($_POST['SubName']);
          $SubscriberName = trim($SubscriberName);
          if (strlen($SubscriberName) == 0) {
               echo "<p>You must include your name!</p>\n";
               ++$FormErrorCount;
          }
     }
     else {
          echo "<p>Form submittal error (No 'SubName' field)!</p>\n";
          ++$FormErrorCount;
     }
     if (isset($_POST['SubEmail'])) {
          $SubscriberEmail = stripslashes($_POST['SubEmail']);
          $SubscriberEmail = trim($SubscriberEmail);
          if (strlen($SubscriberEmail) == 0) {
               echo "<p>You must include your email address!</p>\n";
               ++$FormErrorCount;
          }
     }
     else {
          echo "<p>Form submittal error (No 'SubEmail' field)!</p>\n";
          ++$FormErrorCount;
     }
	 
	 if (isset($_POST['SubDate'])) {
          $SubscriberDate = stripslashes($_POST['SubDate']);
          $SubscriberDate = trim($SubscriberDate);
          if (strlen($SubscriberDate) == 0) {
               echo "<p>You must enter your subsriber date</p>\n";
               ++$FormErrorCount;
          }
     }
     else {
          echo "<p>Form submittal error (No 'SubDate' field)!</p>\n";
          ++$FormErrorCount;
     }
	 
	 $SubscriberID = $_POST['id'];
	 
     if ($FormErrorCount == 0) {
          $ShowForm = FALSE;
          include("inc_db_newsletter.php");
          if ($DBConnect !== FALSE) {
               $TableName = "subscribers";
			   
               date_default_timezone_set('America/New_York'); // insert here default timezone
              
			   $SQLstring = "UPDATE subscribers " .
			        "SET name = '$SubscriberName', " .
					 "email = '$SubscriberEmail', " .
					  "subscribe_date = STR_TO_DATE('$SubscriberDate', '%m/%d/%Y') " .
					  "WHERE subscriberID = $SubscriberID";
					
					
  
               $QueryResult = @mysql_query($SQLstring, $DBConnect);
               if ($QueryResult === FALSE)
                    echo "<p>Unable to update the values into the subscriber table.</p>"
                       . "<p>Error code " . mysql_errno($DBConnect)
                       . ": " . mysql_error($DBConnect) . "</p>";
               else {
                 
                    echo "<p>" . htmlentities($SubscriberName) .
                         ", you have now updated your record successfully.<br />";
                    echo "Your subscriber ID is $SubscriberID.<br />";
                    echo "Your email address is " .
                         htmlentities($SubscriberEmail) . ".</p>";
				    echo "<a href=\"ShowNewsletterSubscribers.php\">Subscriber List</a>";
               }
               mysql_close($DBConnect);
          }
     }
     else
        $ShowForm = TRUE;
}

if ($ShowForm) {
   ?>
<form action="UpdateSubscribers.php" method="POST">
<p><strong>Your ID: </strong>
<input type="text" name="id" value="<?php echo $SubscriberID; ?>" readonly="readonly" /></p>
<p><strong>Your Name: </strong>
<input type="text" name="SubName" value="<?php echo $SubscriberName; ?>" /></p>
<p><strong>Your Email Address: </strong>
<input type="text" name="SubEmail" value="<?php echo $SubscriberEmail; ?>" /></p>
<p><strong>Your Subscribed Date: </strong>
<input type="text" name="SubDate"  id="datepicker" value="<?php echo $SubscriberDate; ?>" /></p>
<p><input type="Submit" name="Submit" value="Submit" /></p>
</form>
   <?php
}
?>
</body>
</html>

