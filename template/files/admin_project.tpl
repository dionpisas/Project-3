
<div class="jumbotron">
    <h1>Admin Project</h1>
</div>

<div class="col-sm-12 blog-main">

    <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="#">Library</a></li>
        <li class="active">Data</li>
    </ol>
    <div class="blog-post">

        <p>
            <a href="index.php?pageid=6">Overzicht</a> -
            <a href="index.php?pageid=6&action=toevoegen">Project toevoegen</a>
        </p>

        <!-- START BLOCK : MELDING -->

        <div class="alert alert-info" role="alert">
            <p>{MELDING}</p>
        </div>
        <!-- END BLOCK : MELDING -->


        <!-- START BLOCK : PROJECTFORM -->
        <form class="form-horizontal" action="{ACTION}" method="post">
            <div class="form-group">
                <label for="inputtitle" class="col-sm-2 control-label">Title</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputtitle" placeholder="Title" name="title" value="{TITLE}">
                </div>
            </div>
            <div class="form-group">
                <label for="editor1" class="col-sm-2 control-label">Content</label>
                <div class="col-sm-10">
                    <textarea class="ckeditor"  rows="3" name="content" id="editor1" placeholder="Content">{CONTENT}</textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-12">
                    <input type="hidden" name="projectid" value="{PROJECTID}">
                    <button type="submit" class="btn btn-default">{BUTTON}</button>
                </div>
            </div>
        </form>

        <!-- END BLOCK : PROJECTFORM -->

        <!-- START BLOCK : PROJECTLIST -->
        <form class="form-inline" action="index.php?pageid=5" method="post">
            <div class="form-group">
                <input type="text" class="form-control" id="Search" placeholder="Zoek project" name="search" value="{SEARCH}">
            </div>
            <button type="submit" class="btn btn-default">Zoek</button>
        </form>




        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Username</th>
                <th>Title</th>
                <th>Content</th>
                <th>Wijzigen</th>
                <th>Verwijderen</th>
            </tr>
            </thead>
            <tbody>
            <!-- START BLOCK : PROJECTROW -->
            <tr>
                <td>{USERNAME}</td>
                <td>{TITLE}</td>
                <td>{CONTENT}</td>
                <td><a href="index.php?pageid=5&action=wijzigen&projectid={PROJECTID}">Wijzigen</a> </td>
                <td><a href="index.php?pageid=5&action=verwijderen&projectid={PROJECTID}">Verwijderen</a></td>
            </tr>
            <!-- END BLOCK : PROJECTROW -->

            </tbody>
        </table>

        <!-- END BLOCK : PROJECTLIST -->



    </div><!-- /.blog-post -->
</div>

