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
                if (!empty($_POST['title'])
                    && !empty($_POST['username'])
                    && !empty($_POST['Content'])
                ) {
                    // insert
                    $insert_blog = $db->prepare("INSERT INTO blog SET
                  Title = :Title,
                  Content = :Content,
                  Accounts_idAccounts = :IDaccount");
                    $insert_blog->bindParam(":Title", $_POST['title']);
                    $insert_blog->bindParam(":Content", $_POST['Content']);
                    $insert_blog->bindValue(":IDaccount", 1);
                    $insert_blog->execute();

                } else {
                    $content->newBlock("BLOGPOST");
                    $content->assign("ACTION", "index.php?pageid=3&action=toevoegen");
                    $content->assign("BUTTON", "Toevoegen Blog");

                }
                break;

            case "wijzigen":

                if (isset($_POST['idblog'])) {
                    $update_blog = $db->prepare("UPDATE blog
                                          SET Title = :Title,
                                              Content = :Content
                                          WHERE idblog = :blogid");
                    $update_blog->bindParam(":Title", $_POST['title']);
                    $update_blog->bindParam(":Content", $_POST['Content']);
                    $update_blog->bindParam(":blogid", $_POST['idblog']);
                    $update_blog->execute();


                } else {

                    $get_blog = $db->prepare("SELECT blog.*, accounts.* FROM blog, accounts
                                      WHERE accounts.idAccounts = blog.Accounts_idAccounts
                                      AND blog.idblog = :blogid");
                    $get_blog->bindParam(":blogid", $_GET['idblog']);
                    $get_blog->execute();

                    $blog = $get_blog->fetch(PDO::FETCH_ASSOC);

                    $content->newBlock("BLOGPOST");
                    $content->assign("ACTION", "index.php?pageid=3&action=wijzigen");
                    $content->assign("BUTTON", "Wijzigen blog");

                    $content->assign(array(
                        "USERNAME" => $blog['Username'],
                        "CONTENT" => $blog ['Content'],
                        "TITLE" => $blog   ['Title'],
                        "BLOGID" => $blog  ['idBlog']
                    ));
                }


                break;
            case "verwijderen":


                if (isset($_POST['idblog'])) {
                    // formulier verstuurd

                    $delete = $db->prepare("DELETE FROM blog WHERE idblog = :blogid");
                    $delete->bindParam(":blogid", $_POST['idblog']);
                    $delete->execute();


                } else {
                    $get_blog = $db->prepare("SELECT blog.*, accounts.* FROM blog, accounts
                                      WHERE accounts.idAccounts = blog.Accounts_idAccounts
                                      AND blog.idblog = :blogid");
                    $get_blog->bindParam(":blogid", $_GET['idblog']);
                    $get_blog->execute();

                    $blog = $get_blog->fetch(PDO::FETCH_ASSOC);

                    $content->newBlock("BLOGPOST");
                    $content->assign("ACTION", "index.php?pageid=3&action=verwijderen");
                    $content->assign("BUTTON", "verwijderen blog");


                    $content->assign(array(
                        "USERNAME" => $blog['Username'],
                        "CONTENT" => $blog ['Content'],
                        "TITLE" => $blog   ['Title'],
                        "BLOGID" => $blog  ['idBlog']

                    ));
                }
                break;
            case "zoeken":

                break;

            default:
                $content->newBlock("SHOW_BLOG");
                if (!empty($_POST['search'])) {
                    $get_blog = $db->prepare("SELECT blog.title,
                                            blog.content,
                                            blog.idblog,
                                    accounts.Username
                                    FROM blog, accounts
                                  WHERE accounts.idAccounts = blog.Accounts_idAccounts
                                  AND (accounts.Username LIKE :search
                                  OR blog.title LIKE :search2)
                                     ");
                    $search = "%" . $_POST['search'] . "%";
                    $get_blog->bindParam(":search", $search);
                    $get_blog->bindParam(":search2", $search);
                    $get_blog->execute();

                    $content->assign("SEARCH", $_POST['search']);
                } else {
                    $get_blog = $db->query("SELECT blog.content, blog.title, accounts.Username, blog.idblog
                                FROM blog, accounts
                                WHERE blog.Accounts_idAccounts = accounts.idAccounts");

                }

                while ($blogs = $get_blog->fetch(PDO::FETCH_ASSOC)) {
                    $content->newBlock("BLOG_ROW");
                    $content->assign(array(
                        "TITLE" => $blogs['title'] = substr($blogs['title'],0,25),
                        "CONTENT" => $blogs['content'] = substr($blogs['content'],0,25),
                        "GEBRUIKERSNAAM" => $blogs['Username'],
                        "BLOGID" => $blogs['idblog']
                    ));

                }
        }
    }
else {
    $content->newBlock('MELDING');
    $content->assign("MELDING", "U bent niet bevoegd voor de area:P");
}}
    else {
        $content->newBlock('MELDING');
        $content->assign("MELDING", "U bent niet ingelogd");
}