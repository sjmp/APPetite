<?php require_once 'dbconnection.php'; ?>
<?php include 'functions/shared_functions.php'; ?>

<?php
	header("Access-Control-Allow-Origin: *");

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

	$admin = $request->group;
//    $password = sqlsrv_escape_string($request->password);
//    $nhsnumber = sqlsrv_escape_string($request->nhsnumber);
	$password = $request->password;
	$nhsnumber = $request->nhsnumber;
	// Expects the date in the format 'YYYY-MM-DD'
	$dateofbirth = $request->dateofbirth;
	$dateofbirth .= "T00:00:00";
	$gender = $request->gender;
    $activitylevel = $request->activitylevel;


    $sql = "SELECT * FROM users WHERE nhsnumber = '" . $nhsnumber . "'";
    $nhsnumbercheck = sqlsrv_query($conn, $sql) or die(errorparse("Error: Query to check if nhsnumber exists failed"));

    if(!null == (sqlsrv_fetch_array($nhsnumbercheck))){
        echo errorparse("failure");
    }else{            
        /// Hash and salt the password
        $password = sha1('vq3%jt'.$password.'s1*v'); 
        
        ///Process the query then redirect if successful
        $query = "INSERT INTO users (admin, password, nhsnumber, dateofbirth, gender, activitylevel) ";
        $query .= "VALUES ('{$admin}', '{$password}', '{$nhsnumber}', CONVERT(datetime2,'{$dateofbirth}',120), '{$gender}', '{$activitylevel}')";
        $result = sqlsrv_query($conn, $query) or die (errorparse('Error: Query to insert new user failed. Query: '));
        
        echo errorparse("success");
    }
?>
