<?php

$content = new TemplatePower("template/files/admin_users.tpl");
$content->prepare();

if(isset($_GET['action']))
{
    $action = $_GET['action'];
}else{
    $action = NULL;
}
if (isset ($_SESSION['accountid'])) {
    if ($_SESSION ['roleid'] == 2) {


        switch ($action) {
            case "toevoegen":
                if (!empty($_POST['vnaam'])
                    && !empty($_POST['anaam'])
                    && !empty($_POST['gnaam'])
                    && !empty($_POST['email'])
                    && !empty($_POST['password1'])
                    && !empty($_POST['password2'])
                ) {
                    // insert
                    if ($_POST['password1'] == $_POST['password2']) {
                        // insert
                        $insert_user = $db->prepare("INSERT INTO users SET
                  Surename = :anaam,
                  Name = :vnaam,
                  Email = :email");
                        $insert_user->bindParam(":anaam", $_POST['anaam']);
                        $insert_user->bindParam(":vnaam", $_POST['vnaam']);
                        $insert_user->bindParam(":email", $_POST['email']);
                        $insert_user->execute();

                        $userid = $db->lastInsertId();

                        $insert_account = $db->prepare("INSERT INTO accounts SET
                  Username = :username,
                  Password = :password,
                  salt = :salt,
                  Users_idUsers = :userid,
                  Role_idRole = :roleid");
                        $insert_account->bindParam(":username", $_POST['gnaam']);
                        $password = sha1($_POST['password1']);
                        $insert_account->bindParam(":password", $password);
                        $insert_account->bindParam(":salt", $userid);
                        $insert_account->bindParam(":userid", $userid);
                        $insert_account->bindValue(":roleid", 1);
                        $insert_account->execute();

                        print "Yeahhhhhhh";


                    } else {
                        $errors->newBlock("ERRORS");
                        $errors->assign("ERROR", "Wachtwoord komt niet overeen SUKKEL!");
                        $content->newBlock("USERFORM");
                        $content->assign("ACTION", "index.php?pageid=2&action=toevoegen");
                        $content->assign("BUTTON", "Toevoegen Gebruiker");
                    }

                } else {
                    // formulier
                    $content->newBlock("USERFORM");
                    $content->assign("ACTION", "index.php?pageid=2&action=toevoegen");
                    $content->assign("BUTTON", "Toevoegen Gebruiker");
                }
                break;
            case "wijzigen":

                if (isset($_POST['accountid'])) {
                    $update_account = $db->prepare("UPDATE accounts
                                          SET Username = :username
                                          WHERE idAccounts = :accountid");
                    $update_account->bindParam(":username", $_POST['gnaam']);
                    $update_account->bindParam(":accountid", $_POST['accountid']);
                    $update_account->execute();

                    $update_user = $db->prepare("UPDATE users
                                            SET Surename = :achternaam,
                                            Name = :voornaam,
                                            Email = :email
                                            WHERE idUsers = :userid");
                    $update_user->bindParam(":achternaam", $_POST['anaam']);
                    $update_user->bindParam(":voornaam", $_POST['vnaam']);
                    $update_user->bindParam(":email", $_POST['email']);
                    $update_user->bindParam(":userid", $_POST['userid']);
                    $update_user->execute();

                    print "ohhhhhhhhhh";

                } else {

                    $get_user = $db->prepare("SELECT users.*, accounts.* FROM users, accounts
                                      WHERE users.idUsers=accounts.Users_idUsers
                                      AND accounts.idAccounts = :accountid");
                    $get_user->bindParam(":accountid", $_GET['accountid']);
                    $get_user->execute();

                    $user = $get_user->fetch(PDO::FETCH_ASSOC);

                    $content->newBlock("USERFORM");
                    $content->assign("ACTION", "index.php?pageid=2&action=wijzigen");
                    $content->assign("BUTTON", "Wijzigen Gebruiker");

                    $content->assign(array(
                        "VOORNAAM" => $user['Name'],
                        "ACHTERNAAM" => $user['Surename'],
                        "EMAIL" => $user['Email'],
                        "USERNAME" => $user['Username'],
                        "ACCOUNTID" => $user['idAccounts'],
                        "USERID" => $user['idUsers']
                    ));

                }


                break;
            case "verwijderen":
                if (isset($_GET['accountid'])) {
                    $check_user = $db->prepare("SELECT count(*) FROM accounts WHERE idAccounts = :accountid");
                    $check_user->bindParam(":accountid", $_GET['accountid']);
                    $check_user->execute();

                    if ($check_user->fetchColumn() == 1) {
                        $get_user = $db->prepare("SELECT users.*, accounts.* FROM users, accounts
                                      WHERE users.idUsers=accounts.Users_idUsers
                                      AND accounts.idAccounts = :accountid");
                        $get_user->bindParam(":accountid", $_GET['accountid']);
                        $get_user->execute();

                        $user = $get_user->fetch(PDO::FETCH_ASSOC);

                        $content->newBlock("USERFORM");
                        $content->assign(array(
                            "ACTION" => "index.php?pageid=2&action=verwijderen",
                            "BUTTON" => "Verwijderen Gebruiker",
                            "ACCOUNTID" => $user['idAccounts'],
                            "USERID" => $user['idUsers'],
                            "VOORNAAM" => $user['Name'],
                            "ACHTERNAAM" => $user['Surename'],
                            "EMAIL" => $user['Email'],
                            "USERNAME" => $user['Username']
                        ));
                    } else {
                        $errors->newBlock("ERRORS");
                        $errors->assign("ERROR", "Deze gebruiker bestaat niet. Hoe ben je hier gekomen???");
                    }


                } elseif (isset($_POST['accountid'])) {
                    // formulier verstuurd

                    $delete = $db->prepare("DELETE FROM accounts WHERE idAccounts = :accountid");
                    $delete->bindParam(":accountid", $_POST['accountid']);
                    $delete->execute();

                    $delete = $db->prepare("DELETE FROM users WHERE idUsers = :userid");
                    $delete->bindParam(":userid", $_POST['userid']);
                    $delete->execute();

                    $content->newBlock("MELDING");
                    $content->assign("MELDING", "Gebruiker is verwijderd");


                } else {
                    $errors->newBlock("ERRORS");
                    $errors->assign("ERROR", "Deze gebruiker bestaat helemaal niet. Hoe ben je hier gekomen???");
                }
                break;
            default:
                $content->newBlock("USERLIST");
                if (!empty($_POST['search'])) {
                    $get_users = $db->prepare("SELECT users.Surename,
                                    users.Name,
                                    users.Email,
                                    accounts.Username,
                                    accounts.idAccounts
                                    FROM users, accounts
                                  WHERE users.idUsers = accounts.Users_idUsers
                                  AND (accounts.Username LIKE :search
                                  OR users.Email LIKE :search2
                                  OR users.Surename LIKE :search3
                                  OR users.Name LIKE :search4)
                                     ");
                    $search = "%" . $_POST['search'] . "%";
                    $get_users->bindParam(":search", $search);
                    $get_users->bindParam(":search2", $search);
                    $get_users->bindParam(":search3", $search);
                    $get_users->bindParam(":search4", $search);
                    $get_users->execute();

                    $content->assign("SEARCH", $_POST['search']);
                } else {
                    $get_users = $db->query("SELECT users.Surename,
                                    users.Name,
                                    users.Email,
                                    accounts.Username,
                                    accounts.idAccounts
                                  FROM users, accounts
                                  WHERE users.idUsers = accounts.Users_idUsers");
                }


                while ($users = $get_users->fetch(PDO::FETCH_ASSOC)) {
                    $content->newBlock("USERROW");
                    $content->assign(array(
                        "VOORNAAM" => $users['Name'],
                        "ACHTERNAAM" => $users['Surename'],
                        "EMAIL" => $users['Email'],
                        "USERNAME" => $users['Username'],
                        "ACCOUNTID" => $users['idAccounts']
                    ));


                }


        }
    }
    else {
        $content->newBlock('MELDING');
        $content->assign("MELDING", "U bent niet bevoegd voor de area:P");
    }
}

else {
        $content->newBlock('MELDING');
        $content->assign("MELDING", "U bent niet ingelogd");
    }
