<?php

function registerAccount($username,$password,$email){
    global $db;

    if (!ctype_alnum($username)) {
        header("location: ?page=register&message=failedWeirdCharacters");
    }
    elseif (strlen($password) < 8) {
        header("location: ?page=register&message=passwordLeast8");
    }
    else {
        $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
        $sql = "INSERT INTO users (username,password,email) VALUES(?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$username,$hash,$email]);

        # tạo thành công
        if ($stmt->rowCount() > 0) {
            $id = $db->lastInsertId();
            $userWorld = $username . "\'s world";
            $sql = "INSERT INTO world (id,name) VALUES ('$id','$userWorld')";
            $db->query($sql);
            $sql = "INSERT INTO resources (id) VALUES ('$id')";
            $db->query($sql);
            $_SESSION['loggedIn'] = $id;
            header("location: ?page=loggedIn&message=Registered");
        } else {
            header("location: ?page=register&message=failed");
        }   
    }
}

function login($username, $password) {
    global $db;

    $sql = "SELECT id, username, password FROM users WHERE username=:username LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([':username' => $username]);
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetchAll();
        // var_dump($result);
        $hash = $result[0]['password'];
        if (password_verify($password, $hash)) {
            $_SESSION['loggedIn'] = $result[0]['id'];
            header("location: ?page=loggedIn&message=You%20logged%20in");
        } else {
            header("location: ?page=login&message=InvalidPassword");
        }
    }
    else {
        header("location: ?page=register&message=Userdoesnotexist");
    }
}

function logout() {
    session_destroy();
    header("location: ?");
}

if($_GET['action'] === "register") {
    registerAccount($_POST['username'], $_POST['password'], $_POST['email']);
}

elseif ($_GET['action'] === "login") {
    login($_POST['username'], $_POST['password']);
}

elseif ($_GET['action'] === "logout") {
    logout();
}

?>