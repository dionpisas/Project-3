<head>
    <link rel="stylesheet" type="text/css" href="template/css/adminblog.css">
</Head>
<div class="jumbotron">
    <h1>Admin Blog</h1>
</div>

<div class="col-sm-8 blog-main">

    <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="#">Library</a></li>
        <li class="active">Data</li>
    </ol>
    <div class="blog-post">
        <!-- START BLOCK : MELDING -->

        <div class="alert alert-info" role="alert">
            <p>{MELDING}</p>
        </div>
        <!-- END BLOCK : MELDING -->
        <p>
            <a href="index.php?pageid=3">Overzicht</a> -
            <a href="index.php?pageid=3&action=toevoegen">Blog toevoegen</a>
        </p>


        <!-- START BLOCK : BLOGPOST -->
        <form method="post" action="{ACTION}">
            <label for="title">Titel:</label><input type="text" id="title" name="title" value="{TITLE}">
            <label for="username">Gebruikersnaam</label></label><input type="text" id="username" name="username" value="{USERNAME}">
            <textarea id="content" name="Content">
                {CONTENT}
            </textarea>
            <input type="hidden" name="idblog" value="{BLOGID}">
            <input type="submit" value="{BUTTON}">

        </form>

        <!-- END BLOCK : BLOGPOST -->

        <!-- START BLOCK : SHOW_BLOG -->


        <form class="form-inline" action="index.php?pageid=3" method="post">
            <div class="form-group">
                <input type="text" class="form-control" id="Search" placeholder="Zoek blog" name="search" value="{SEARCH}">
            </div>
            <button type="submit" class="btn btn-default">Zoek</button>
        </form>

        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Title</th>
                <th>Gebruikersnaam</th>
                <th>Content</th>
            </tr>
            </thead>
            <tbody>

            <!-- START BLOCK : BLOG_ROW -->

            <tr>
           <td>{TITLE}</td>
           <td>{GEBRUIKERSNAAM}</td>
           <div><td>{CONTENT}</td></div>
            <td><a href="index.php?pageid=3&action=wijzigen&idblog={BLOGID}">wijzigen</a></td>
             <td><a href="index.php?pageid=3&action=verwijderen&idblog={BLOGID}">verwijderen</a></td>
                </tr>
            <!-- END BLOCK : BLOG_ROW -->
            </tbody>
        </table>



        <!-- END BLOCK : SHOW_BLOG -->



    </div><!-- /.blog-post -->
</div>

<div class="col-sm-3 col-sm-offset-1 blog-sidebar">

    <div class="sidebar-module sidebar-module-inset">
        <h4>About</h4>
        <p>Etiam porta <em>sem malesuada magna</em> mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
    </div>
    <div class="sidebar-module">
        <h4>Archives</h4>
        <ol class="list-unstyled">
            <li><a href="#">March 2014</a></li>
            <li><a href="#">February 2014</a></li>
            <li><a href="#">January 2014</a></li>
            <li><a href="#">December 2013</a></li>
            <li><a href="#">November 2013</a></li>
            <li><a href="#">October 2013</a></li>
            <li><a href="#">September 2013</a></li>
            <li><a href="#">August 2013</a></li>
            <li><a href="#">July 2013</a></li>
            <li><a href="#">June 2013</a></li>
            <li><a href="#">May 2013</a></li>
            <li><a href="#">April 2013</a></li>
        </ol>
    </div>
    <div class="sidebar-module">
        <h4>Elsewhere</h4>
        <ol class="list-unstyled">
            <li><a href="#">GitHub</a></li>
            <li><a href="#">Twitter</a></li>
            <li><a href="#">Facebook</a></li>
        </ol>
    </div>
</div>

