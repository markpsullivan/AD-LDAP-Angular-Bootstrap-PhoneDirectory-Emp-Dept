<?php

// **** SETTINGS ****
//-------------------------------------------------------

$ldap_server = "192.168.0.1";
$auth_user = "ldapuser@domain.com";
$auth_pass = "password";
$company = "Company";
$baseDn = "OU=Users,OU=Office,DC=domain,DC=com";

//-------------------------------------------------------
// **** END SETTINGS ****

// Start at base dn defined in settings
$base_dn = $baseDn;

// Filter only users with company name defined in settings
$filter = "(&(objectCategory=user)(company=$company))";

// connect to server defined in settings
if (!($connect=ldap_connect($ldap_server))) {
     die("Could not connect to ldap server " . $ldap_server);
}

// bind to server with credentials defined in settings
if (!($bind = ldap_bind($connect, $auth_user, $auth_pass))) {
     die("Unable to bind to server");
}

// search active directory basedn with filters
if (!($search = ldap_search($connect, $base_dn, $filter))) {
     die("Unable to search ldap server");
}

// Sort Entries by Last Names
ldap_sort($connect, $search, 'sn');

// Count of objects returned
$number_returned = ldap_count_entries($connect,$search);

// fill $info array with all entries and attributes
$info = ldap_get_entries($connect, $search);

// Tell header to report type as JSON
header('Content-type: application/json');

// Create JSON formatted contents of the entries with:
// name, title, dept, email, and phone
echo '[';

for ($i=0; $i<$info["count"]; $i++) {
    
    // checking for unset attributes to avoid warnings, otherwise populating variables
    if (isset($info[$i]['displayname'][0])) {
        $name = $info[$i]['displayname'][0];
    } else {$phone = "";}
    
    if (isset($info[$i]['title'][0])) {
        $title = $info[$i]['title'][0];
    } else {$phone = "";}
    
    if (isset($info[$i]['department'][0])) {
        $dept = $info[$i]['department'][0];
    } else {$phone = "";}
    
    if (isset($info[$i]['mail'][0])) {
        $email = $info[$i]['mail'][0];
    } else {$phone = "";}
    
    if (isset($info[$i]['telephonenumber'][0])) {
        $phone = $info[$i]['telephonenumber'][0];
    } else {$phone = "";}
    
    // echo out variables in JSON format
    echo '{';
    echo  '"name":"' . $name . '",';
    echo  '"title":"' . $title . '",';
    echo  '"department":"' . $dept . '",';
    echo  '"email":"' . $email . '",';
    echo  '"phone":"' . $phone . '"';
    echo '}';
    
    // add trailing , to all but last entry
    if ($i < $info["count"]-1){
        echo ',';
    }
}
 echo ']';
?>