<?php

function authenticate ($user_id, $password) {
	// basic sequence with LDAP is connect, bind, search, interpret search
	// result, close connection
	
	$ldaphost = "ldaps://netid.arizona.edu"; // ldap server address
	$ldaprdn = "uid=".$user_id.",ou=Accounts,ou=NetID,ou=CCIT,o=University of Arizona,c=US";			// users full dn
	
	$ds = ldap_connect($ldaphost);  // must be a valid LDAP server
	
	if ($ds) { 
		// binding to ldap server
		$r = ldap_bind($ds, $ldaprdn, $password); 
		
		if ($r) {
			// search for user information
			$base_dn = "ou=Accounts,ou=NetID,ou=CCIT,o=University of Arizona,c=US";		// the ldap base location
			$filter = "(uid=".$user_id.")";		// the ldap filter criteria
			$justthese = array("uid", "emplId", "dbkey", "activeStudent", "activeemployee");		// which attributes should it return
			$sr = ldap_search($ds, $base_dn, $filter, $justthese);	// perform the search
		
			if (ldap_count_entries($ds, $sr) == 1) {			
				// Get the user's information stored in session variables for later use.
				$info = ldap_get_entries($ds, $sr);
				
				// loop through the ldap query results and write the returned values into local variables
				for ($i=0; $i<$info["count"]; $i++) {
					$uid = $info[$i]["uid"][0];
					$dbkey = $info[$i]["dbkey"][0];
					$emplId = $info[$i]["emplId"][0];
					$activestudent = $info[$i]["activestudent"][0];
					$activeemployee = $info[$i]["activeemployee"][0];
				}
				
				if ($activestudent || $activeemployee || $emplId) {		// check to see if the user is a currently active student or employee
					return array ("valid" => 1, "uid" => $uid, "dbkey" => $dbkey,"emplId" => $emplId, "activestudent" => $activestudent, "activeemployee" => $activeemployee);		// set return value to 1 to indicate success in authentication	
				} else {
					return array ("valid" => 0, "uid" => 0, "dbkey" => 0, "emplId" => 0, "activestudent" => 0, "activeemployee" => 0);		// set return value to 0 to indicate a failure in authentication
				}
				
			} else {
				// for some reason the search returned nothing
				return array ("valid" => 0, "uid" => 0, "dbkey" => 0, "emplId" => 0, "activestudent" => 0, "activeemployee" => 0);		// set return value to 0 to indicate a failure in authentication
			}
			
		} else {
			// what to do if the bind fails
			return array ("valid" => 0, "uid" => 0, "dbkey" => 0,  "emplId" => 0, "activestudent" => 0, "activeemployee" => 0);	// set return value to 0 to indicate a failure in authentication
		}

		ldap_close($ds);	// close the ldap connection
	
	} else {
		// what to do when the ldap server can't be connected to
		return array ("valid" => -1, "uid" => 0, "dbkey" => 0, "emplId" => 0, "activestudent" => 0, "activeemployee" => 0);	// set return value to -1 to indicate error in authentication
	}
}  // end authenticate()




// The get_user function is used to retrieve and return a user's data from tbl_user
// given a user_key, net_id or ua_id
function get_user ($net_id, $user_key, $ua_id) {
	
	// create some default variables
	$ary_user = array();
	
	// First, find out if the user already exists in tbl_user
		// Create the query string
		$sql_get_user = "select u.user_key, u.fname, u.lname, u.email, u.phone, u.student_id, u.uid, u.staff, u.ua_netid, ";
		$sql_get_user .= "u.cancel_date, u.cancel_auth, u.modify_date, u.modify_auth, u.create_date ";
		$sql_get_user .= "from tbl_user u ";
		
		if ($net_id != '0') {
			$sql_get_user .= "where u.ua_netid = '".$net_id."' ";
		} elseif ($user_key != '0') {
			$sql_get_user .= "where u.user_key = '".$user_key."' ";
		} elseif ($ua_id != '0') {
			$sql_get_user .= "where u.uid = '".$ua_id."' ";
		} else {
			$sql_get_user .= "where 1 = 0 ";
		}
		
		// Call the mysqlquery function in inc_dbfunctions.php to execute $query and
		// store the value in $result.
		$rslt_get_user = mysqlquery($sql_get_user);
		
		if ($rslt_get_user != 0) { // $result will be 0 if the mysqlquery function encountered a db error
			
			if (mysql_num_rows($rslt_get_user) > 0) { // if a record was returned, the user exists
				return mysql_fetch_array($rslt_get_user, MYSQL_BOTH);
			} else {
				return -1; // return an error indicator
			} // end if

		} else {

			return -1; // return an error indicator

		} // end if
	
} // end get_user

?>
