<?php
include_once('include/function.php');
$content = new TemplatePower("template/files/vergeten.tpl");
$content->prepare();


if(isset($_GET['action'])){
    $action = $_GET['action'];
}else{
    $action = NULL;
}
switch($action){
    case "2":
        $content->newBlock("VERGETENFORM");
        $content->assign("OPTION", $_GET['option']);
        break;
    case "3":
        if(isset($_POST['option'])){
            // welke option hebben we.
            if($_POST['option'] == 1){
                // option 1: sturen we username
            }
            elseif($_POST['option'] == 2){
                // option2 : zetten we een hash in de db
                // account verkrijgen
                $check_account = $db->prepare("SELECT count(u.idUsers)
                        FROM users u, accounts a WHERE u.Email = :email
                        AND u.idUsers = a.Users_idUsers");
                $check_account->bindParam(":email", $_POST['email']);
                $check_account->execute();
                if($check_account->fetchColumn() == 1){
                    // gebruiker gevonden
                    $get_account = $db->prepare("SELECT a.*, u.*
                        FROM users u, accounts a WHERE u.Email = :email
                        AND u.idUsers = a.Users_idUsers");
                    $get_account->bindParam(":email", $_POST['email']);
                    $get_account->execute();
                    $account = $get_account->fetch(PDO::FETCH_ASSOC);
                    $hash = hashgenerator();
                    $insert_hash = $db->prepare("UPDATE accounts
                                SET Reset = :hash
                                WHERE idAccounts = :accountid ");
                    $insert_hash->bindParam(":hash", $hash);
                    $insert_hash->bindParam(":accountid", $account['idAccounts']);
                    $insert_hash->execute();
                }else{
                    // er is geen gebruiker met dat mail adres
                }
                /*
                  ");
                                */
                // mail sturen met link
            }elseif($_POST['option'] == 3)
            {
                // option 3: sturen we username
                //             zetten we een hash in de db
                // mail sturen met link
            }else{
                // error
            }
            // mail sturen
        }else{
        }
        break;
    case "4":
        break;
    default:
}