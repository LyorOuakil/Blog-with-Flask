<?php

if (isset($_GET['delete']))
{
    deleteUserAPI($_GET['delete']);
}

if(isset($_GET['update']))
{
  updateUserAPI($_GET['update']);
}

if (isset($_GET['hello']))
{
    createUserAPI();
}

function callAPI($method, $url, $data){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_PORT => "5000",
      CURLOPT_URL => "http://127.0.0.1:5000/api/user",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "{\"username\" : \"Test\", \"email\" : \"test@test.com\", \"password\" : \"toto\"}",
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Postman-Token: 6ba91535-255d-4b71-9e3e-b109c5e825d9",
        "cache-control: no-cache"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      return json_decode($response);
    }
 }

 function deleteUserAPI($id)
 {
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_PORT => "5000",
      CURLOPT_URL => "http://127.0.0.1:5000/api/user/".$id,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "DELETE",
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Postman-Token: 7017ba50-6e8e-48e8-95f8-15fb611365d4",
        "cache-control: no-cache"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
 }

function updateUserAPI($id)
{
  $curl = curl_init();
  $username = "Random" . rand(0, 500);
  $email = $username . "@exemple.fr";
  curl_setopt_array($curl, array(
    CURLOPT_PORT => "5000",
    CURLOPT_URL => "http://127.0.0.1:5000/api/user/".$id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_POSTFIELDS => "{\"username\" : \"$username\", \"email\" : \"$email\", \"password\" : \"toto\"}",
    CURLOPT_HTTPHEADER => array(
      "Content-Type: application/json",
      "Postman-Token: 0525d96b-8251-4fec-b44c-64bedb3c8795",
      "cache-control: no-cache"
    ),
  ));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }
}

function createUserAPI()
{
    $curl = curl_init();
    $username = "Random" . rand(0, 500);
    $email = $username . "@exemple.fr";
    curl_setopt_array($curl, array(
      CURLOPT_PORT => "5000",
      CURLOPT_URL => "http://127.0.0.1:5000/api/register",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\"username\" : \"$username\", \"email\" : \"$email\", \"password\" : \"toto\"}",
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Postman-Token: 7866ddd2-f059-45c8-b583-681f3d8d070c",
        "cache-control: no-cache"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }   
}

function loginUserAPI()
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_PORT => "5000",
      CURLOPT_URL => "http://127.0.0.1:5000/login",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => ["email" => "lyor@lyor.fr", "password" => "mabite"],
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
}

$response = callAPI('GET', 'http://127.0.0.1:5000/user', false);
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h1>Users :</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Username </th>
            <th>Email</th>
            <th></th>
        </tr>
        <?php
            foreach ($response as $value) {
                foreach ($value as $obj) {
                    echo ('<tr><th><a href="client.php?update='.$obj->id.'">'.$obj->id . '</a></th>');
                    echo ("<th>".$obj->username . "</th>");
                    echo ('<th>'.$obj->email.'"</th>');
                    echo ('<th><a href="client.php?delete='.$obj->id.'"> Delete </a></th></tr>');
                }
            }
        ?>
    </table>
    <a type="button" href="client.php?hello=true" >Create User</a>
</body>
</html>