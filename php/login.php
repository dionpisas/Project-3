<?php

$content = new TemplatePower("template/files/login.tpl");
$content->prepare();


if(isset($_SESSION['accountid'])){
    // is al ingelogd, dus niks doen
    $content->newBlock("MELDING");
    $content->assign("MELDING", "Je bent al ingelogd");
}else{
    if(!empty($_POST['gnaam']) AND !empty($_POST['password'])){
        // formulier is verstuurd

        $check_user = $db->prepare("SELECT count(*) FROM accounts a, users u
                                    WHERE a.Users_idUsers = u.idUsers
                                    AND a.Username = :username
                                    AND a.Password = :password");
        $check_user->bindParam(":username", $_POST['gnaam']);
        $password = sha1($_POST['password']);
        $check_user->bindParam(":password", $password);
        $check_user->execute();

        if($check_user->fetchColumn() == 1){
            // gebruiker gevonden

            $get_user = $db->prepare("SELECT a.*, u.* FROM accounts a, users u
                                    WHERE a.Users_idUsers = u.idUsers
                                    AND a.Username = :username
                                    AND a.Password = :password");
            $get_user->bindParam(":username", $_POST['gnaam']);
            $get_user->bindParam(":password", $password);
            $get_user->execute();

            $user = $get_user->fetch(PDO::FETCH_ASSOC);

            $_SESSION['accountid'] = $user['idAccounts'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['roleid'] = $user['Role_idRole'];

            $content->newBlock("MELDING");
            $content->assign("MELDING", "Je bent ingelogd");

        }else{
            // gebruiker niet gevonden: combinatie username + password klopt niet
            $errors->newBlock("ERRORS");
            $errors->assign("ERROR", "Combinatie username + password klopt niet");

            $content->newBlock("LOGINFORM");
            $content->assign("USERNAME", $_POST['gnaam']);
        }


    }else{
        // formulier niet verstuurd. Form laten zien
        $content->newBlock("LOGINFORM");

    }
}