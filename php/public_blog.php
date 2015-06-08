<?php
$content = new TemplatePower("template/files/public_blog.tpl");
$content->prepare();



if(isset($_GET['action']))
{
    $action = $_GET['action'];
}else{
    $action = NULL;
}
if (isset($_SESSION['accountid'])) {



        switch ($action) {
            case "toevoegen":

                if (!empty($_POST['Comment'])
                ) {


                    // insert
                    $insert_comment = $db->prepare("INSERT INTO Comments SET
                                                    Text = :Text,
                                                    Blog_idBlog = :Blog_idBlog,
                                                    Accounts_idAccounts = :Accounts_idAccounts");
                    $insert_comment->bindParam(":Text", $_POST['Comment']);
                    $insert_comment->bindParam(":Blog_idBlog", $_POST['idblog']);
                    $insert_comment->bindParam(":Accounts_idAccounts", $_SESSION['accountid']);
                    $insert_comment->execute();

                    header('Location: index.php?pageid=7&blogid='.$_POST['idblog'].'');



                } else {


                }
                break;
            case "wijzigen":
                if ($_SESSION ['roleid']==2) {
                if (isset($_POST['Comment'])) {
                    $update_comment = $db->prepare("UPDATE Comments
                                          SET Text = :Text
                                          WHERE idComments = :idComments");
                    $update_comment->bindParam(":Text", $_POST['Comment']);
                    $update_comment->bindParam(":idComments", $_POST['idcomment']);
                    $update_comment->execute();
                    $content->gotoBlock("DETAILS");



                } else {

                    $get_comment = $db->prepare("SELECT Comments.*, accounts.username FROM Comments, accounts
                                                 WHERE Comments.Accounts_idAccounts = Accounts.idAccounts
                                                 AND idComments = :idComments");
                    $get_comment->bindParam(":idComments", $_GET['idComments']);
                    $get_comment->execute();

                    $comment = $get_comment->fetch(PDO::FETCH_ASSOC);

                    $content->newBlock("DETAILS");
                    $content->newBlock("COMMENT");
                    $content->assign("ACTION", "index.php?pageid=7&action=wijzigen&idComments=". $_GET['idComments']);
                    $content->assign("BUTTON", "Wijzigen Comment");

                    $content->assign(array(
                        "TEXT" => $comment ['Text'],
                        "COMMENTID" => $comment['idComments']
                    ));
                }}else{
                    $content->newBlock('MELDING');
                    $content->assign("MELDING", "U bent niet bevoegd voor deze actie");
                }
                break;
            case "verwijderen":
                if ($_SESSION ['roleid']==2) {
                    if (isset($_POST['Comment'])) {

                        $delete = $db->prepare("DELETE FROM Comments WHERE idComments = :idComments");
                        $delete->bindParam(":idComments", $_POST['idComments']);
                        $delete->execute();
                        header('Location: index.php?pageid=7&blogid=' . $_POST['idblog'] . '');
                    } else {

                        $get_comment = $db->prepare("SELECT Comments.*, accounts.username FROM Comments, accounts
                                                 WHERE Comments.Accounts_idAccounts = Accounts.idAccounts
                                                 AND idComments = :idComments");
                        $get_comment->bindParam(":idComments", $_GET['idComments']);
                        $get_comment->execute();

                        $comment = $get_comment->fetch(PDO::FETCH_ASSOC);
                        $content->newBlock("DETAILS");
                        $content->newBlock("COMMENT");
                        $content->assign("ACTION", "index.php?pageid=7&action=verwijderen&idComments=" . $_GET['idComments']);
                        $content->assign("BUTTON", "Verwijder Comment");

                        $content->assign(array(
                            "TEXT" => $comment ['Text'],
                            "COMMENTID" => $comment['idComments']));
                    }
                } else{
                    $content->newBlock('MELDING');
                    $content->assign("MELDING", "U bent niet bevoegd voor deze actie");
                }
                break;

            default:
                //alle blogs



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

                        $content->newBlock("COMMENT");
                        $content->assign("ACTION", "index.php?pageid=7&action=toevoegen");
                        $content->assign("BUTTON", "Toevoegen Blog");





                        $content->assign("BLOGID", $blog['idBlog']);

                    }

    }   else{
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

                    While($comment = $get_comments ->fetch(PDO::FETCH_ASSOC)) {
                        $content->newBlock("TOONCOMMENT");
                        $content->assign(array(
                            "TEXT" => $comment ['Text'],
                            "GEBRUIKERSNAAM" => $comment ['username'],
                            "IDCOMMENTS" => $comment['idComments']));
                    }

                }

        }
    }