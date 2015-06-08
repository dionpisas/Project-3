<?php
$content = new TemplatePower("template/files/public_blog.tpl");
$content->prepare();

if(isset($_GET['blogid'])){
    // controleren of alles er is
    $content->newBlock("DETAILS");

    $check_blog = $db->prepare("SELECT COUNT(*) FROM blog, accounts
                                      WHERE accounts.idAccounts = blog.Accounts_idAccounts
                                      AND blog.idblog = :blogid");
    $check_blog->bindParam(":blogid", $_GET['blogid']);
    $check_blog->execute();
    if($check_blog->fetchColumn() == 1) {
        $get_blog = $db->prepare("SELECT blog.*, accounts.* FROM blog, accounts
                                      WHERE accounts.idAccounts = blog.Accounts_idAccounts
                                      AND blog.idblog = :blogid");
        $get_blog->bindParam(":blogid", $_GET['blogid']);
        $get_blog->execute();

        $blog = $get_blog->fetch(PDO::FETCH_ASSOC);

        $content->assign(array("TITLE" => $blog['Title'],
            "CONTENT" => $blog['Content'],
            "USERNAME" => $blog['Username']));

         $content->newBlock("Comment");

         $content->assign("BLOGID", $blog['idBlog']);



     if(!empty($_POST['Comment'])){

         if(isset($_SESSION['accountid'])){

             $insert_comment = $db->prepare("INSERT INTO Comments SET
                            Text = :Text,
                            Blog_idBlog= :Blog_idBlog,
                            Accounts_idAccounts= :Accounts_idAccounts");
             $insert_comment->bindParam(":Text", $_POST['Comment']);
             $insert_comment->bindParam(":Blog_idBlog", $_POST['idblog']);
             $insert_comment->bindParam(":Accounts_idAccounts", $_SESSION['accountid']);
             $insert_comment->execute();


           }else{

           }

        }
        $check_comments =$db->prepare ("SELECT COUNT(*) FROM Comments
                                            WHERE blog_idblog = :blogid");
        $check_comments ->bindParam(":blogid", $_GET['blogid']);
        $check_comments ->execute();

        if( $check_comments ->fetchColumn()> 0){
            $get_comments= $db->prepare("SELECT Comments.*, accounts.username FROM Comments, accounts
                                            WHERE blog_idblog = :blogid
                                            AND Comments.accounts_idAccounts = accounts.idAccounts");
            $get_comments ->bindParam(":blogid", $_GET['blogid']);
            $get_comments ->execute();
        }

        While($comment = $get_comments ->fetch(PDO::FETCH_ASSOC)){
            $content->newBlock("Commenttonen");
            $content ->assign(array(
                "TEXT" => $comment ['Text'],
                "GEBRUIKERSNAAM" => $comment ['username'],
                "IDCOMMENTS" => $comment['idComments']));

            if($check_comments ->fetchColumn() == 1){
                $get_comment= $db->prepare("SELECT Comments.*, accounts.username FROM Comments, accounts
                                            WHERE blog_idblog = :blogid
                                            AND Comments.accounts_idAccounts = accounts.idAccounts");

            }
        }
    }

}/*elseif(isset($_GET['idComments'])){
    if($_GET['option'] == 'wijzigen'){
        $get_comment= $db->prepare("SELECT Comments.*, accounts.username FROM Comments, accounts
                                            WHERE Comments.idComments = :idComments
                                            AND Comments.accounts_idAccounts = accounts.idAccounts");
    }
}*/
else{
    $check_blog = $db->query("SELECT count(*) FROM blog");

    if($check_blog->fetchColumn() > 0 ){
        $get_blog = $db->query("SELECT * FROM blog");
        $teller = 0;
        while($blog = $get_blog->fetch(PDO::FETCH_ASSOC)){
            $teller++;
            if($teller % 3 == 1){
                // div openen
                $content->newBlock("BEGIN");
            }

            // block van een element oproepen
            $blogcontent = substr($blog['Content'], 0, 150)." ...";

            $content->newBlock("PROJECT");
            $content->assign(array("TITLE" => $blog['Title'],
                "CONTENT" => $blogcontent,
                "BLOGID" => $blog['idBlog']));

            if($teller % 3 == 0){
                // div sluiten
                $content->newBlock("END");
            }
        }
    }else{
        // geen projecten gevonden
    }
}
