<?php
include("connection.php");

$id=$_POST['id'];
$response = [];

if($conn->connect_error){
    $response["User updated"]=false;
    echo json_encode($response);
    die('Connection Failed : '.$conn->connect_error);
}else{
    if($_POST['username']){
        $username=$_POST['username'];
        $stmt= $conn->prepare("SELECT COUNT(*) from users WHERE username=?");
        $stmt->bind_param("s",$username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($result);  
        $stmt->fetch();

        if($result==0){
            $stmt = $conn->prepare("UPDATE users SET username=? WHERE id=?");
            $stmt->bind_param("si", $username, $id);
            $stmt->execute();
        }
    }
    if($_POST['full_name']){
        $full_name=$_POST['full_name'];
        $stmt = $conn->prepare("UPDATE users SET full_name=? WHERE id=?");
        $stmt->bind_param("si", $full_name, $id);
        $stmt->execute();
    }
    if($_POST['password']){
        $password=$_POST['password'];
        $password=hash('sha256',$password);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $password, $id);
        $stmt->execute();
    }
    if($_POST['bio']){
        $bio=$_POST['bio'];
        $stmt = $conn->prepare("UPDATE users SET bio=? WHERE id=?");
        $stmt->bind_param("si", $bio, $id);
        $stmt->execute();
    }
    if($_FILES['profile_picture']['name']){
        $target="profile-pic/".basename($_FILES['profile_picture']['name']);
        $profile_picture=$_FILES['profile_picture']['name'];
        $stmt = $conn->prepare("UPDATE users SET profile_picture=? WHERE id=?");
        $stmt->bind_param("si", $profile_picture, $id);
        $stmt->execute();
    }
    $response["User updated"]=true;
    echo json_encode($response);
}
$stmt->close();
$conn->close();
?>