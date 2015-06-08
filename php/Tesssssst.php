<?php
$content = new TemplatePower("template/files/admin_blog.tpl");



$content->prepare();


if(isset($_GET['action']))
{
    $action = $_GET['action'];
}else{
    $action = NULL;
}
if (isset($_SESSION['accountid'])) {
    if ($_SESSION ['roleid']==2) {


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
                    $insert_comment->bindParam(":Blog_idBlog", $_POST['idBlog']);
                    $insert_comment->bindParam(":Accounts_idAccounts", $_SESSION ['accountid']);
                    $insert_comment->execute();

                } else {
                    $content->newBlock("COMMENTPOST");
                    $content->assign("ACTION", "index.php?pageid=7&action=toevoegen");
                    $content->assign("BUTTON", "Toevoegen Comment");

                }
                break;

            case "wijzigen":

                if (isset($_POST['Comment'])) {
                    $update_comment = $db->prepare("UPDATE Comments
                                          SET Text = :Text,
                                              Blog_idBlog = :Blog_idBlog,
                                          WHERE idblog = :blogid");
                    $update_comment->bindParam(":Text", $_POST['Comment']);
                    $update_comment->bindParam(":Blog_idBlog", $_POST['idBlog']);
                    $update_comment->bindParam(":Accounts_idAccounts", $_SESSION['accountid']);
                    $update_comment->execute();


                } else {

                    $get_comment = $db->prepare("SELECT Comments.*, accounts.username FROM Comments, accounts
                                                  WHERE blog_idblog = :blogid
                                                  AND Comments.accounts_idAccounts = accounts.idAccounts");
                    $get_comment->bindParam(":blogid", $_GET['blogid']);
                    $get_comment->execute();

                    $comment = $get_comment->fetch(PDO::FETCH_ASSOC);

                    $content->newBlock("COMMENTPOST");
                    $content->assign("ACTION", "index.php?pageid=7&action=wijzigen");
                    $content->assign("BUTTON", "Wijzigen Comment");

                    $content->assign(array(
                        "TEXT" => $comment ['Text'],
                        "GEBRUIKERSNAAM" => $comment ['username'],
                        "IDCOMMENTS" => $comment['idComments']
                    ));
                }


                break;
            case "verwijderen":


                if (isset($_POST['Comment'])) {
                    // formulier verstuurd

                    $delete = $db->prepare("DELETE FROM Comments
                                            WHERE idblog = :blogid");
                    $delete->bindParam(":blogid", $_POST['idblog']);
                    $delete->execute();


                } else {
                    $get_comment = $db->prepare("SELECT blog.*, accounts.* FROM blog, accounts
                                      WHERE accounts.idAccounts = blog.Accounts_idAccounts
                                      AND blog.idblog = :blogid");
                    $get_comment->bindParam(":blogid", $_GET['idblog']);
                    $get_comment->execute();

                    $comment = $get_comment->fetch(PDO::FETCH_ASSOC);

                    $content->newBlock("COMMENTPOST");
                    $content->assign("ACTION", "index.php?pageid=7&action=verwijderen");
                    $content->assign("BUTTON", "verwijderen Comment");


                    $content->assign(array(
                        "TEXT" => $comment ['Text'],
                        "GEBRUIKERSNAAM" => $comment ['username'],
                        "IDCOMMENTS" => $comment['idComments']

                    ));
                }
                break;

            default:
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
                    }
                    else{
                            if ($check_blog->fetchColumn() > 0) {
                                $get_blog = $db->query("SELECT * FROM blog");
                                $teller = 0;
                                while ($blog = $get_blog->fetch(PDO::FETCH_ASSOC)) {
                                    $teller++;
                                    if ($teller % 3 == 1) {
                                        // div openen
                                        $content->newBlock("BEGIN");
                                    }

                                    // block van een element oproepen
                                    $blogcontent = substr($blog['Content'], 0, 150) . " ...";

                                    $content->newBlock("PROJECT");
                                    $content->assign(array("TITLE" => $blog['Title'],
                                        "CONTENT" => $blogcontent,
                                        "BLOGID" => $blog['idBlog']));

                                    if ($teller % 3 == 0) {
                                        // div sluiten
                                        $content->newBlock("END");
                                    }
                                }
                            } else {
                                // geen projecten gevonden
                            }}
                        }
                    }
        }
    }
    else {
        $content->newBlock('MELDING');
        $content->assign("MELDING", "U bent niet bevoegd voor de area:P");

else {
    $content->newBlock('MELDING');
    $content->assign("MELDING", "U bent niet ingelogd");
}}