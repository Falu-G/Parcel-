<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
// Add a new User
    $app->post('/createUser', function ($request, $response) {
        $input = $request->getParsedBody(); 
		$checkUserExist = "select usr_eml 'email' from user where usr_eml= :eml";
		$cue = $this->db->prepare($checkUserExist);
		$cue->bindParam("eml",$input['email']);
		$cue->execute();
		$emlexist = $cue->fetchObject();
		if($cue->rowCount() <= 0){
			$sql = "INSERT INTO user (usr_nme,usr_eml,usr_passwd) VALUES (:name,:eml,:pass)";
			$sth = $this->db->prepare($sql);
			$this->logger->addInfo($input['email']);
			$sth->bindParam("name", $input['name']);
			$input['password'] = hash("sha256",$input['email'].$input['password']);
			$sth->bindParam("eml",$input['email']);
			$sth->bindParam("pass", $input['password']);
			$sth->execute();
			if($sth){
				$input['id'] = $this->db->lastInsertId();
				$input['response status'] = "User Created Successfully";
			}
			else{
				$input['response status'] = "failed";
			}
		}else{
			$error['response']="email already exist";
			$error['response status'] ="failed";
			return $this->response->withJson($error);
		}
        
        
        return $this->response->withJson($input);
    });
// User Login
 $app->post('/userLogin', function ($request, $response) {
        $input = $request->getParsedBody(); 
		$eml = trim($input['email']);
		$eml = strip_tags($input['email']);
		$eml = htmlspecialchars($input['email']);
		$pass = trim($input['Password']);
		$pass = strip_tags($input['Password']);
		$pass = htmlspecialchars($input['Password']);
		$error = false;
	  if(empty($eml)){
	   $error = true;
	   $errMSG = "Please enter your email address.";
	  } else if ( !filter_var($eml,FILTER_VALIDATE_EMAIL) ) {
	   $error = true;
	   $errMSG = "Please enter valid email address.";
	  } 
	  if(empty($pass)){
	   $error = true;
	   $errMSG = "Please enter your password.";
	  }
	    if (!$error) {   
		   $pass = hash('sha256', $eml.$pass); // password hashing using SHA256		  
		   $res="SELECT usr_id, usr_eml, usr_passwd FROM user WHERE usr_eml=:eml";
		   $cue = $this->db->prepare($res);
		   $cue->bindParam("eml",$eml);
			$cue->execute();
			$usrexist = $cue->fetchObject();
		if($cue->rowCount() == 1 && $pass == $usrexist->usr_passwd ){
		   
			  $input['current user'] = $usrexist->usr_id;
			  $input['response status'] = "login success";
			  return $this->response->withJson($input);
		   }
		   else {
			  $errMSG = "Incorrect Credentials, Try again...";
			  $input['response status'] = "login failed";
			  $input['response message'] = $errMSG;
			  return $this->response->withJson($input);
		   }
    
	}else{
		$input['response status'] = "login failed";
		$input['response message'] = $errMSG;
		return $this->response->withJson($input);
	}
 
 });
// Creating Parcel deliveryOrder
    $app->post('/createParcelDeliveryOrder', function ($request, $response) {
        $input = $request->getParsedBody();
        $sql = "INSERT INTO parcel (Placed_by,Weight,Sent_on,Status,delivered_on,From_address,To_address,Cancelled) VALUES (:Plc_by,:weight,:sent_on,:stat,:delv_on,:frm_add,:to_add,:cancl)";
         $sth = $this->db->prepare($sql);
        $sth->bindParam("Plc_by", $input['Placed_by']);
        $sth->bindParam("weight",$input['Weight']);
		$sth->bindParam("sent_on",$input['Sent_on']);
		$sth->bindParam("delv_on", $input['Delivered_on']);
        $sth->bindParam("stat",$input['Status']);
		$cancl = "false";
		$sth->bindParam("cancl",$cancl);
		$sth->bindParam("frm_add",$input['From_address']);
		$sth->bindParam("to_add",$input['To_address']);
		$sth->execute();
        $input['id'] = $this->db->lastInsertId();
		$input['response status'] = "Delivery Order Created Successfully";
        return $this->response->withJson($input);
    });
//change order destination 
$app->put('/changeOrderDestination/[{id}]', function ($request, $response, $args) {
        $input = $request->getParsedBody();
        $sql = "UPDATE parcel SET To_address=:addrs WHERE id=:id";
         $sth = $this->db->prepare($sql);
        $sth->bindParam("id", $args['id']);
        $sth->bindParam("addrs", $input['To_address']);
        $sth->execute();
        $input['id'] = $args['id'];
		$input['response status'] = "Updated Successfully";
        return $this->response->withJson($input);
    });
//cancel Parcel delivery order 
$app->put('/cancelParcelDeliveryOrder/[{id}]', function ($request, $response, $args) {
        $input = $request->getParsedBody();
        $sql = "UPDATE parcel SET Cancelled=:stat WHERE id=:id";
         $sth = $this->db->prepare($sql);
        $sth->bindParam("id", $args['id']);
        $sth->bindParam("stat",$input['cancel']);
        $sth->execute();
        $input['id'] = $args['id'];
		$input ['response status']= "Updated Successfully";
        return $this->response->withJson($input);
    });
// See Details of a delivery order
    $app->get('/deliveryDetails/[{id}]', function ($request, $response, $args) {
         $sth = $this->db->prepare("SELECT Placed_by,Weight,Sent_on 'Date delivered',Status,To_address 'Address' FROM parcel WHERE id=:id");
        $sth->bindParam("id", $args['id']);
        $sth->execute();
        $deliveryDetails = $sth->fetchObject();
        return $this->response->withJson($deliveryDetails);
    });
//set delivery status 
$app->put('/changeStatusParcelDeliveryOrder/[{id}]', function ($request, $response, $args) {
        $input = $request->getParsedBody();
        $sql = "UPDATE parcel SET Status=:stat WHERE id=:id";
         $sth = $this->db->prepare($sql);
        $sth->bindParam("id", $args['id']);
        $sth->bindParam("stat", $input['status']);
        $sth->execute();
        $input['id'] = $args['id'];
		$input ['response status']= "Updated Successfully";
        return $this->response->withJson($input);
    });