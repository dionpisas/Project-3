<?php


$content = new TemplatePower("template/files/products.tpl");
$content->prepare();

if(isset($_GET['projectid'])){
    // controleren of alles er is
    $content->newBlock("DETAILS");

    $check_project = $db->prepare("SELECT count(*) FROM products
                                    WHERE idProducts = :projectid");
    $check_project->bindParam(":projectid", $_GET['projectid']);
    $check_project->execute();

    if($check_project->fetchColumn() == 1){
        $get_project = $db->prepare("SELECT p.*, a.Username FROM products p, accounts a
                                    WHERE p.idProducts = :projectid
                                    AND p.Accounts_idAccounts = a.idAccounts");
        $get_project->bindParam(":projectid", $_GET['projectid']);
        $get_project->execute();

        $project = $get_project->fetch(PDO::FETCH_ASSOC);

        $content->assign(array("TITLE" => $project['Title'],
            "CONTENT" => $project['Content'],
            "USERNAME" => $project['Username']));


    }else{
        // error
    }


}else{
    $check_projects = $db->query("SELECT count(*) FROM products");

    if($check_projects->fetchColumn() > 0 ){
        $get_projects = $db->query("SELECT * FROM products");
        $teller = 0;
        while($projects = $get_projects->fetch(PDO::FETCH_ASSOC)){
            $teller++;
            if($teller % 3 == 1){
                // div openen
                $content->newBlock("BEGIN");
            }

            // block van een element oproepen
            $projectcontent = substr($projects['Content'], 0, 150)." ...";

            $content->newBlock("PROJECT");
            $content->assign(array("TITLE" => $projects['Title'],
                "CONTENT" => $projectcontent,
                "PROJECTID" => $projects['idProducts']));

            if($teller % 3 == 0){
                // div sluiten
                $content->newBlock("END");
            }
        }
    }else{
        // geen projecten gevonden
    }
}

