<?php

$content = new TemplatePower("template/files/admin_project.tpl");
$content->prepare();

if(isset($_GET['action']))
{
    $action = $_GET['action'];
}else{
    $action = NULL;
}

if(isset($_SESSION['roleid'])){
    if($_SESSION['roleid'] == 2){
        switch($action)
        {
            case "toevoegen":
                if(!empty($_POST['title']) AND !empty($_POST['content'])){ // voorwaarde => insert
                    $insert = $db->prepare("INSERT INTO products
                                    SET Title = :title,
                                    Content = :content,
                                    Accounts_idAccounts = :account");
                    $insert->bindParam(":title", $_POST['title']);
                    $insert->bindParam(":content", $_POST['content']);
                    $insert->bindParam(":account", $_SESSION['accountid']);
                    $insert->execute();
                }else{
                    // formulier
                    $content->newBlock("PROJECTFORM");
                    $content->assign(array("ACTION" => "index.php?pageid=5&action=toevoegen",
                        "BUTTON" => "Toevoegen Project"));
                }
                break;
            case "wijzigen":
                if(isset($_GET['projectid'])){
                    // ophalen project
                    $check_project = $db->prepare("SELECT count(*) FROM
                                                    accounts a, products p
                                                    WHERE a.idAccounts = p.Accounts_idAccounts
                                                    AND p.idProducts = :projectid");
                    $check_project->bindParam(":projectid", $_GET['projectid']);
                    $check_project->execute();
                    if($check_project->fetchColumn() == 1){
                        // hij bestaat in db
                        $get_project = $db->prepare("SELECT * FROM
                                                    accounts a, products p
                                                    WHERE a.idAccounts = p.Accounts_idAccounts
                                                    AND p.idProducts = :projectid");
                        $get_project->bindParam(":projectid", $_GET['projectid']);
                        $get_project->execute();

                        $project = $get_project->fetch(PDO::FETCH_ASSOC);
                        $content->newBlock("PROJECTFORM");
                        $content->assign(array(
                            "TITLE" => $project['Title'],
                            "CONTENT" => $project['Content'],
                            "PROJECTID" => $project['idProducts'],
                            "ACTION" => "index.php?pageid=6&action=wijzigen",
                            "BUTTON" => "Wijzigen Project"
                        ));

                    }else{
                        $errors->newBlock("ERRORS");
                        $errors->assign("ERROR", "Waarom heb je het projectid in de url veranderd???");
                    }

                }elseif(!empty($_POST['title'])
                    AND !empty($_POST['content'])
                    AND !empty($_POST['projectid'])){

                    $update = $db->prepare("UPDATE products SET Title = :title,
                                                  Content = :content
                                                  WHERE idProducts = :projectid");
                    $update->bindParam(":title", $_POST['title']);
                    $update->bindParam(":content", $_POST['content']);
                    $update->bindParam(":projectid", $_POST['projectid']);
                    $update->execute();

                    $content->newBlock("MELDING");
                    $content->assign("MELDING", "Project gewijzigd");

                }else{
                    $errors->newBlock("ERRORS");
                    $errors->assign("ERROR", "WTF doe je hier");
                }
                break;
            case "verwijderen":
                if(isset($_GET['projectid'])){
                    // formulier laten zien
                    $check_project = $db->prepare("SELECT count(*) FROM products
                                WHERE idProducts = :productid");
                    $check_project->bindParam(":productid", $_GET['projectid']);
                    $check_project->execute();

                    // check of er 1 rij is
                    if($check_project->fetchColumn() == 1){

                        // hij bestaat
                        // nu eerst gegevens ophalen
                        $get_project = $db->prepare("SELECT * FROM products
                                WHERE idProducts = :productid");
                        $get_project->bindParam(":productid", $_GET['projectid']);
                        $get_project->execute();

                        $project = $get_project->fetch(PDO::FETCH_ASSOC);

                        $content->newBlock("PROJECTFORM");
                        $content->assign(array(
                            "TITLE" => $project['Title'],
                            "CONTENT" => $project['Content'],
                            "PROJECTID" => $project['idProducts'],
                            "ACTION" => "index.php?pageid=5&action=verwijderen",
                            "BUTTON" => "Verwijder project"
                        ));
                    }else
                    {
                        $errors->newBlock("ERRORS");
                        $errors->assign("ERROR", "Item bestaat niet");
                    }



                }elseif(isset($_POST['projectid'])){
                    // item verwijderen
                    $check_project = $db->prepare("SELECT count(*) FROM products
                                WHERE idProducts = :productid");
                    $check_project->bindParam(":productid", $_POST['projectid']);
                    $check_project->execute();

                    // check of er 1 rij is
                    if($check_project->fetchColumn() == 1) {
                        // item verwijderen
                        $delete_project = $db->prepare("DELETE FROM products WHERE idProducts = :projectid");
                        $delete_project->bindParam(":projectid", $_POST['projectid']);
                        $delete_project->execute();

                        $content->newBlock("MELDING");
                        $content->assign("MELDING", "Project verwijderd");
                    }else{
                        // error, is niet in de db
                        $errors->newBlock("ERRORS");
                        $errors->assign("ERROR", "Dit item kan niet meer worden verwijderd, want het staat niet in de DB");
                    }

                }else{
                    // ERROR !!
                    $errors->newBlock("ERRORS");
                    $errors->assign("ERROR", "WTF doe je hier");
                }
                break;
            default:
                if(!empty($_POST['search'])){
                    // checken of er projecten zijn
                    $check_projects = $db->prepare("SELECT count(p.idProducts)
                                                  FROM accounts a, products p
                                                  WHERE a.idAccounts = p.Accounts_idAccounts
                                                  AND ( p.Title LIKE :zoek
                                                  OR p.Content LIKE :zoek2 )");
                    $zoek = "%".$_POST['search']."%";
                    $check_projects->bindParam(":zoek", $zoek);
                    $check_projects->bindParam(":zoek2", $zoek);
                    $check_projects->execute();

                    if($check_projects->fetchColumn() > 0 ) {
                        // jaaaa, we hebben projecten
                        $content->newBlock("PROJECTLIST");

                        $get_projects = $db->prepare("SELECT a.Username,
                                                          p.Title,
                                                          p.Content,
                                                          p.idProducts
                                                  FROM accounts a, products p
                                                  WHERE a.idAccounts = p.Accounts_idAccounts
                                                   AND ( p.Title LIKE :zoek
                                                  OR p.Content LIKE :zoek2 )");
                        $get_projects->bindParam(":zoek", $zoek);
                        $get_projects->bindParam(":zoek2", $zoek);
                        $get_projects->execute();
                    }else{
                        $content->newBlock("MELDING");
                        $content->assign("MELDING", "Geen resultaten gevonden");
                        break;
                    }

                }else{

                    // checken of er projecten zijn
                    $check_projects = $db->query("SELECT count(p.idProducts)
                                                  FROM accounts a, products p
                                                  WHERE a.idAccounts = p.Accounts_idAccounts");
                    if($check_projects->fetchColumn() > 0 ) {
                        // jaaaa, we hebben projecten
                        $content->newBlock("PROJECTLIST");

                        $get_projects = $db->query("SELECT a.Username,
                                                          p.Title,
                                                          p.Content,
                                                          p.idProducts
                                                  FROM accounts a, products p
                                                  WHERE a.idAccounts = p.Accounts_idAccounts");
                    }else {
                        $content->newBlock("MELDING");
                        $content->assign("MELDING", "Geen resultaten gevonden");
                        break;
                    }

                }

                while($projects = $get_projects->fetch(PDO::FETCH_ASSOC)){
                    $content->newBlock("PROJECTROW");
                    $inhoud = $projects['Content'];
                    if(strlen($inhoud) > 30){
                        $inhoud = substr($projects['Content'],0,30)."...";
                    }

                    $content->assign(array(
                        "USERNAME" => $projects['Username'],
                        "TITLE" => $projects['Title'],
                        "CONTENT" => $inhoud,
                        "PROJECTID" => $projects['idProducts']
                    ));
                }


        }
    }else{
        // je hebt niet de goede rechten
        $errors->newBlock("ERRORS");
        $errors->assign("ERROR", "Je hebt niet de goede rechten");
    }

}else{
    // je bent niet ingelogd
    $errors->newBlock("ERRORS");
    $errors->assign("ERROR", "Je bent niet ingelogd");
}
