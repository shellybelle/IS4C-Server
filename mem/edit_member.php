<?php
/*******************************************************************************

    Copyright 2007 Alberta Cooperative Grocery, Portland, Oregon.

    This file is part of Fannie.

    IS4C is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    IS4C is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    in the file license.txt along with IS4C; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*********************************************************************************/

// A page to view and edit a member's details.

$page_title = 'Edit a Member';
include ('./includes/header.html');

// Check for a valid user ID, through GET or POST.
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) )
{
	// Accessed through view_users.php.
	$id = $_GET['id'];
}
elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) )
{
	// Accessed through form submission.
	$id = $_POST['id'];
}
else
{
	// No valid ID, kill the script.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p>
	<p class="error">You have to <b><a href="auto_mem_find.php">find a member</a></b> before you can edit them.</p><p><br /><br /></p>'; 
	include ('./includes/footer.html');
	exit();
}

require_once($_SERVER["DOCUMENT_ROOT"] . '/src/mysql_connect.php'); // Connect to the database.

if (isset($_POST['submitted']))
{
	// If the form has been submitted, check the new data and update the record.	
	// Initialize the errors array.
	$errors = array();
	
	// Validate the form data.
	if (empty($_POST['first_name']))
	{		
		$errors[] = 'You left their first name blank.';	
	}
	else
	{
		$fn = escape_data($_POST['first_name']); // Store the first name.
	}

	if (empty($_POST['last_name']))
	{
		$errors[] = 'You left their last name blank.';	
	}
	else
	{
		$ln = escape_data($_POST['last_name']); // Store the last name.
	}
	$email = escape_data($_POST['email']);
	$phoneNo = escape_data($_POST['phone_number']);
	if ((isset($_POST['charge_ok'])) && ($_POST['charge_ok'] = 'on'))
	{
		$ChargeOk = 1;
	}
	elseif ((isset($_POST['charge_ok'])) && ($_POST['charge_ok'] = 'off'))
	{
		$ChargeOk = 0;
	}
	else
	{
		$ChargeOk = 0;
	}

	if (!isset($_POST['checks_ok']))
	{
		$_POST['checks_ok'] = 'off';
	}

	if ($_POST['discount'] > 20)
	{	
		$errors[] = 'You entered a discount greater than the maximum.';
	}
	else
	{
		$d = escape_data($_POST['discount']); // Store the discount.
	}
	
	if (empty($errors))
	{
		if (isset($_POST['staff']) && $_POST['staff'] == 6)
		{
			$Type = 'reg';
		}
		else
		{
			$Type = 'pc';
		}	
		$staff = $_POST['staff'];
		$memtype = $_POST['memtype'];
		if ($_POST['checks_ok'] == 'on')
		{
			$WriteCheck=1;
		}
		else
		{
			$WriteCheck=0;
		}
		$query =
		"	UPDATE custdata
				SET FirstName = '$fn',
				LastName = '$ln',
				email = '$email',
				phoneNo = '$phoneNo',
				WriteChecks = $WriteCheck,
				discount = $d,
				memType = $memtype,
				Type = '$Type',
				staff = '$staff',
				ChargeOk = $ChargeOk
				WHERE id = $id;
		";
		$result = @mysql_query($query);
			
		if (mysql_affected_rows() == 1)
		{ // If the query was successful.				
			echo '<h1 id="mainhead">Edit a Member</h1>
			<p>The member has been edited.</p><p><br /><br /></p>';
		}
		else
		{ // The query was unsuccessful.				
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">There are two possibilities:<br />
			<b>1.)</b> The member could not be edited due to a system error.<br />
			<b>2.)</b> Nothing was changed.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
		}
	}
	else
	{ // Report the errors.		
		echo '<h1 id="mainhead">Error!!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg)
		{
			// Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';			
	} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Always show the form.

// Retrieve the user's information.
$query = "SELECT * FROM custdata WHERE id = " . $id;
$query2 = "SELECT staff_no, staff_desc FROM staff ORDER BY staff_no ASC";
$query3 = "SELECT memtype, memDesc FROM memtype ORDER BY memtype ASC";
$result = @mysql_query($query);
$result2 = @mysql_query($query2);
$result3 = @mysql_query($query3);

if (mysql_num_rows($result) == 1)
{
	// Valid id show the form.
	// Get the user's information.
	$row = mysql_fetch_array($result);
	
	// Create the form.
	if ($row["ChargeOk"] == 1)
	{
		$ChargeOk = ' CHECKED';
	}
	else
	{
		$ChargeOk = '';
	}

	if ($row["WriteChecks"] == 1)
	{
		$ChecksOk = ' CHECKED';
	}
	else
	{
		$ChecksOk = '';
	}
	?>
	<h2>Edit a Member.</h2>
	<form action="auto_mem_edit.php" method="post">
		<p>
                    Card Number:
                    <?php $CardNo = $row["CardNo"] ?>
                    <?=$row["CardNo"]?>
		</p>
                <div style="border: 1px; border-color: #000000; border-style: solid">
                    <h3>Contact Information:</h3>
                     <p>
                         First Name:
                         <input type="text" name="first_name" size="15" maxlength="15" value="<?=$row["FirstName"]?>" required />
		     </p>
		     <p>
                         Last Name:
                         <input type="text" name="last_name" size="15" maxlength="30" value="<?=$row["LastName"]?>" required />
                     </p>
                     <p>
                         E-Mail Address:
                         <input type="email" name="email" size = "20" maxlength="100" value="<?=$row["email"]?>" />
                     </p>
                     <p>
                         Phone Number:
                         <input type="text" name="phone_number" size = "15" maxlength="30" value="<?=$row["phoneNo"]?>" />
                     </p>
                     <p>
                         Address:
                         <div style="border: 1px; border-color: #000000; border-style: dashed;">
                             <input type="text" name="street1" size = "20" maxlength="50" placeholder="Street Line 1" value="<?=$row["addressStreet"]?>" /><br />
                             <input type="text" name="street2" size = "20" maxlength="50" placeholder="Street Line 2" value="<?=$row["addressStreet2"]?>" /><br />
                             <input type="text" name="city" size = "20" maxlength="20" placeholder="City" value="<?=$row["addressCity"]?>" />
                             <input type="text" name="state" size = "2" maxlength="2" placeholder="FL" value="<?=$row["addressState"]?>" />
                             <input type="text" name="zip" size = "10" maxlength="10" placeholder="Zipcode" value="<?=$row["addressZip"]?>" /><br />
                         </div>
                     </p>
                    </div>
                    <div style="border: 1px; border-color: #000000; border-style: solid">
                        <h3>Availability:</h3>
                        <input type="checkbox" name="available_sunday" <?=$row["availableSunday"]?'checked':''?>/> Sundays <input type="text" name"available_sunday_hours" size="20" maxlength="20" placeholder="Available Hours" value="<?=$row["availableSundayHours"]?>" /?><br />
                        <input type="checkbox" name="available_monday" <?=$row["availableMonday"]?'checked':''?>/> Mondays <input type="text" name"available_monday_hours" size="20" maxlength="20" placeholder="Available Hours" value="<?=$row["availableMondayHours"]?>" /?><br />
                        <input type="checkbox" name="available_tuesday" <?=$row["availableTuesday"]?'checked':''?>/> Tuesdays <input type="text" name"available_tuesday_hours" size="20" maxlength="20" placeholder="Available Hours" value="<?=$row["availableTuesdayHours"]?>" /?><br />
                        <input type="checkbox" name="available_wednesday" <?=$row["availableWednesday"]?'checked':''?>/> Wednesdays <input type="text" name"available_wednesday_hours" size="20" maxlength="20" placeholder="Available Hours" value="<?=$row["availableWednesdayHours"]?>" /?><br />
                        <input type="checkbox" name="available_thursday" <?=$row["availableThursday"]?'checked':''?>/> Thursdays <input type="text" name"available_thursday_hours" size="20" maxlength="20" placeholder="Available Hours" value="<?=$row["availableThursdayHours"]?>" /?><br />
                        <input type="checkbox" name="available_friday" <?=$row["availableFriday"]?'checked':''?>/> Fridays <input type="text" name"available_friday_hours" size="20" maxlength="20" placeholder="Available Hours" value="<?=$row["availableFridayHours"]?>" /?><br />
                        <input type="checkbox" name="available_saturday" <?=$row["availableSaturday"]?'checked':''?>/> Saturdays <input type="text" name"available_saturday_hours" size="20" maxlength="20" placeholder="Available Hours" value="<?=$row["availableSaturdayHours"]?>" /?><br />
                </div>
		
	<?php
	if ($row["staff"] == 1 || $row["staff"] == 2 || $row["staff"] == 5)
	{
		echo '<p>House Charge? <input type="checkbox" name="charge_ok" ' . $ChargeOk . ' /></p>';
	}
	?>
		<p>
			Write Checks?
			<input type="checkbox" name="checks_ok" <?=$ChecksOk?> />
		</p>
		<p>
			Discount:
			<input type="text" name="discount" size="3" maxlength="2" value="<?=$row["Discount"]?>" />%
		</p>
		<p>
			Member Type:
			<select name="staff">
				<option value='0'>None</option>
	<?php
	while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC))
	{
		echo '<option value=' . $row2['staff_no'];
		if ($row2['staff_no'] == $row['staff'])
		{
			echo ' selected="selected"';
		}
		echo '>' . $row2['staff_desc'] . '</option>';
	}
	?>
			</select>
		</p>
		<p>
			Member Status:
			<select name="memtype">
	<?php
	while ($row3 = mysql_fetch_array($result3, MYSQL_ASSOC))
	{
		echo '<option value='. $row3['memtype'];
		if ($row3['memtype'] == $row['memType'])
		{
			echo ' selected="selected"';
		}
		echo '>' . $row3['memDesc'] . '</option>';
	}
	?>
			</select>
		</p>
		<p>
			<input type="submit" name="submit" value="Submit" />
		</p>
		<input type="hidden" name="submitted" value="TRUE" />
		<input type="hidden" name="id" value="<?=$id?>" />
	</form>

    <!-- This section shows the last six shifts assigned to this member -->
    <table border='1'>
        <tr>
            <th>Start</th>
            <th>End</th>
            <th>Worked</th>
        </tr>
    <?php
        $query =
        '
            SELECT time.shifts.start,
	            time.shifts.end,
	            time.volunteer_hours.id vol_id
	            FROM is4c_op.custdata
		            JOIN time.employee_shifts
			            ON (CardNo = emp_no)
		            JOIN time.shifts
			            USING (shift_id)
		            LEFT JOIN time.volunteer_hours
			            USING (shift_id, emp_no)
	            WHERE emp_no = ' . $CardNo . '
	            ORDER BY time.shifts.start DESC
	            LIMIT 6;
        ';

        $result = @mysql_query($query);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
        {?>
        <tr>
            <td><?=$row['start']?></td>
            <td><?=$row['end']?></td>
            <td><?=!is_null($row['vol_id'])?'Worked':'Not Worked'?></td>
        </tr>
        <?php
        }
    ?>
    </table>
	<?php
}
else
{
	// Not a valid Member ID
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
}
mysql_close(); // Close the DB connection.
?>
