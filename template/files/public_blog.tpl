<div class="jumbotron">
    <h1>Public blog</h1>
</div>

<div class="col-sm-12 blog-main">

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

        <!-- Three columns of text below the carousel -->
        <!-- START BLOCK : BEGIN -->
        <div class="row">
            <!-- END BLOCK : BEGIN -->

            <!-- START BLOCK : PROJECT -->
            <div class="col-lg-4">
                <img class="img-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" width="140" height="140">
                <h2>{TITLE}</h2>
                <p>{CONTENT}</p>
                <p><a class="btn btn-default" href="index.php?pageid=7&blogid={BLOGID}" role="button">View details &raquo;</a></p>
            </div><!-- /.col-lg-4 -->
            <!-- END BLOCK : PROJECT -->

            <!-- START BLOCK : END -->
        </div><!-- /.row -->
        <!-- END BLOCK : END -->



        <!-- START BLOCK : DETAILS -->
        <div class="col-sm-12 blog-main">

            <div class="blog-post">
                <h2 class="blog-post-title">{TITLE}</h2>
                <p class="blog-post-meta">January 1, 2014 by <a href="#">{USERNAME}</a></p>

                <p>{CONTENT}</p>
                <hr>
                <!-- START BLOCK : TOONCOMMENT -->
                <div class="col-sm-12 blog-main">

                    <div class="blog-post">
                        <h5 class="blog-post-title">Gebruiker:{GEBRUIKERSNAAM}</h5>

                        <p>Comment:{TEXT}</p>
                        <td><a href="index.php?pageid=7&action=wijzigen&idComments={IDCOMMENTS}">wijzigen</a></td>
                        <td><a href="index.php?pageid=7&action=verwijderen&idComments={IDCOMMENTS}">verwijderen</a></td>
                        <hr>
                        </div>
                    </div>
                <!-- END BLOCK : TOONCOMMENT -->
                <!-- START BLOCK : COMMENT -->
                <form  action="{ACTION}" method="post">
                        <div class="form-group">
                            <label for="Comment" class="col-sm-6 control-label">Comment</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="Comment" value="{TEXT}" placeholder="comment" name="Comment">
                                <input type="hidden" name="idblog" value="{BLOGID}">
                                <input type="hidden" name="idcomment" value="{COMMENTID}"
                            </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset- col-sm-10">
                            <button type="submit" value="{BUTTON}" class="btn btn-default">{BUTTON}</button>
                        </div>
                    </div>
                </form>

            <!-- END BLOCK : COMMENT -->
            </div><!-- /.blog-post -->
        </div>

        <!-- END BLOCK : DETAILS -->

</div>

    </div><!-- /.blog-post -->
