<?php

require_once 'init.php'; 
require_once(sprintf('%s/%s', MODELS_DIR,'User.php'));



function login()
{ 
    if (empty($_GET) )
         return false;
    $errors=[];

    $formData= $_GET;

    if (empty($formData['email'])) {
        $errors['email']="Az email cím megadása kötelező!";
    }
    if (empty($formData['password'])) {
        $errors['password']="A jelszó megadása kötelező!";
    }
    if (!empty ($errors))
        return $errors;

    $userData= getUsersData();

    foreach ($userData as $key =>$user ) {
        if ($formData['email'] === $user['email'] && password_verify($formData['password'], $user['password'])) {
           $_SESSION['logged_in'] =$user;//['id'];
            redirect('/');
        }
    }    
    if (!isset($_SESSION['logged_in']) ) {
        $errors['email']="Hibás email cím vagy jelszó!";
        return $errors;
    }
}


function login_admin()
{ 
    if (empty($_GET) )
         return false;
    $errors=[];

    $formData= $_GET;

    if (empty($formData['email'])) {
        $errors['email']="Az email cím megadása kötelező!";
    }
    if (empty($formData['password'])) {
        $errors['password']="A jelszó megadása kötelező!";
    }
    if (!empty ($errors))
        return $errors;

    $userData= getUsersData();

    foreach ($userData as $user ) {
        if ($formData['email'] == $user['email'] && $user['role']=="admin" && password_verify($formData['password'], $user['password'])) {
           $_SESSION['logged_in'] =$user;
           $_SESSION['logged_admin'] =true;
            redirect('/');
        }
        elseif ($formData['email'] == $user['email'] && $user['role']!="admin" && password_verify($formData['password'], $user['password'])) {
            $errors['email']="Csak adminisztrátori e-mail címmel lehet belépni!";
            return $errors;
        }
    }

    if (!isset($_SESSION['logged_in']) ) {       
        $errors['email']="Hibás email cím vagy jelszó!";
        return $errors;
    }

}

function getUsersData() {

    $user= new User();
    return $user->read();

}


// új tömböt ad vissza, amiben csak a megadott kulcsokat tartja meg
function requestFilter(array $keys, array $data) {
return array_filter($data, function($key) use ($keys) {
    return in_array($key, $keys);
}, ARRAY_FILTER_USE_KEY);

}

function redirect(string $path='/') {
    header('Location: '.$path);
    exit;
}


function dd(mixed $data) {

    echo "kiír <pre>";
    var_dump($data);
    echo "</pre>";
    die();
 }

 function pd(mixed $data) {

    echo "kiír <pre>";
    var_dump($data);
    echo "</pre>";
 }
 
?>