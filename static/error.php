<?php require_once '../bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>CNAMER &mdash; Error</title>
    <meta name="description" content="CNAMER, Domain redirects using CNAMEs, no configuration needed!"/>
    <meta name="author" content="Samuel Ryan / sam@samryan.co.uk">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400|Inconsolata:400' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-29439846-22', 'cnamer.com');
        ga('send', 'pageview');
    </script>
</head>
<body>
    <div id="header">
        <div class="container">
            <h1><a href="http://<?php echo CNAMER_DOMAIN; ?>">CNAMER</a></h1>
        </div>
    </div>
    <div id="intro">
        <div class="container">
            <h3>error</h3>
            <p>
                The domain that you have visited is configured to redirect using
                CNAMER, however the DNS lookup has failed which means CNAMER
                does not know where to send this request.
            </p>
            <p>  
                If you are the domain administrator and have recently configured 
                your domain to point to CNAMER it is possible your DNS hasn't 
                propagated yet, if that is the case wait a few minutes and try 
                again -- <a href="http://<?php echo $_GET['request']; ?>/<?php echo $_GET['uri']; ?>">click here to try again</a>.
            </p>
            <p>
                If you are a user of this website this error means that we don't
                know where to send you :( If you know the contact details of the
                site administrator you should inform them that you're experiencing
                this error!
        </div>
    </div>
    <div id="content" class="container">
        <h1 id="usage">bug</h1>
        <p>
            There is the possibility that <span class="cnamer">cnamer</span> itself is at fault, if you have
            located an issue with <span class="cnamer">cnamer</span> (or wish to investigate) the source code
            for <span class="cnamer">cnamer</span> is available on GitHub 
            (<a href="https://github.com/citricsquid/cnamer">citricsquid/cnamer</a>)
            and any issues can be submitted via the
            <a href="https://github.com/citricsquid/cnamer/issues">GitHub issues</a>
            page. Alternatively you can contact me via <a href="mailto:sam@samryan.co.uk">email</a> 
            (sam@samryan.co.uk).
        </p>
        <h1 id="usage">availability</h1>
        <p>
            <span class="cnamer">cnamer</span> status is available at <a href="http://status.cnamer.com">status.cnamer.com</a>,
            it's possible (albeit unlikely) that the <span class="cnamer">cnamer</span> DNS lookups may be
            suffering an outage, this can be verified at the above status website.
        </p>
    </div>
    <div class="container">
        <p>
            <a href="http://<?php echo CNAMER_DOMAIN; ?>">&larr; go to cnamer.com</a>
        </p>
    </div>
</body>
</html>