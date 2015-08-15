<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Newsletter Subscribers</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" /> 
</head>
<body>
<h1>Newsletter Subscribers</h1>
<?php
include("inc_db_newsletter.php");
if ($DBConnect !== FALSE) {
     $TableName = "subscribers";
     $SQLstring = "SELECT subscriberID, name, email, DATE_FORMAT(subscribe_date,'%m/%d/%Y') AS subscribe_date, "
      		. " DATE_FORMAT(confirmed_date,'%m/%d/%Y') AS confirmed_date FROM $TableName";
			
			
     $QueryResult = @mysql_query($SQLstring, $DBConnect);
     echo "<table width='100%' border='1'>\n";
     echo "<tr><th>Subscriber ID</th>" .
          "<th>Name</th><th>Email</th>" .
          "<th>Subscribe Date</th>" .
          "<th>Confirm Date</th></tr>\n";
     while (($Row = mysql_fetch_assoc($QueryResult)) !== FALSE) {
          echo "<tr><td><a href=\"updateSubscribers.php?id={$Row['subscriberID']}\">{$Row['subscriberID']}</a></td>";
          echo "<td>{$Row['name']}</td>";
          echo "<td>{$Row['email']}</td>";
          echo "<td>{$Row['subscribe_date']}</td>";
          echo "<td>{$Row['confirmed_date']}</td></tr>\n";
     };
     echo "</table>\n";
     $NumRows = mysql_num_rows($QueryResult);
     $NumFields = mysql_num_fields($QueryResult);
     echo "<p>Your query returned the above "
          . mysql_num_rows($QueryResult)
          . " rows and ". mysql_num_fields($QueryResult)
          . " fields:</p>";
     mysql_free_result($QueryResult);
     mysql_close($DBConnect);
}
?>
</body>
</html>

