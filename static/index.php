<?php 
require '../bootstrap.php';
$stats = json_decode(file_get_contents("../data/stats/global.json"), true); 

$time = (time() - $stats['last_update']) * 1000;
foreach(array("redirect", "domain") as $type) {
    $length = round($time / $stats["{$type}_counter_length"]);
    $estimate[$type] = $stats["{$type}_count"] + ($length * $stats["{$type}_counter_increment"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>CNAMER &mdash; DNS redirects</title>
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
    <script type="text/javascript">
        function update_counter(element_id, increment_by){
            var counter = document.getElementById(element_id);
            var curnum = parseInt(counter.innerHTML, 10);
            curnum = curnum + increment_by;
            counter.innerHTML = curnum;
        }

        setInterval(function() {
            update_counter('redirect_count', <?php echo $stats['redirect_counter_increment']; ?>);
        }, <?php echo $stats['redirect_counter_length']; ?>);

        setInterval(function() {
            update_counter('domain_count', <?php echo $stats['domain_counter_increment']; ?>);
        }, <?php echo $stats['domain_counter_length']; ?>);
    </script>
    <div id="header">
        <div class="container">
            <h1><a href="http://<?php echo CNAMER_DOMAIN; ?>">CNAMER</a></h1>
            <h5><span id="domain_count"><?php echo $estimate['domain'] ?></span> Domains &rarr; <span id="redirect_count"><?php echo $estimate['redirect']; ?></span> redirects <span title="This is estimated, updated every 5 mins with accurate figures">*</span></h5>
        </div>
    </div>
    <div id="intro">
        <div class="container">
            <p>
                Redirect a domain using only a DNS record, no forms, no accounts,
                no database, no charge, just <span class="cnamer">cnamer</span>.
                A variety of options are available, switch protocols, specify 
                HTTP status code, include a custom uri string and more.
            </p>
            <p>
                Redirect search.website.com to google.com:
            </p>
            <code>
                search.website.com. CNAME google.com.<?php echo CNAMER_DOMAIN; ?>.
            </code>
        </div>
    </div>
    <div id="content" class="container">
        <h1 id="usage">1.<a href="#usage">Usage</a></h1>
        <p id="overview">
            Point a domain to the <span class="cnamer">cnamer</span> server
            and specify options as part of the CNAME or a TXT record,
            <span class="cnamer">cnamer</span> will perform a DNS lookup and
            redirect the user to your specified destination. Throughout this
            documentation "<?php echo CNAMER_DEMO; ?>" will be used as a placeholder for
            your domain, you can visit any <?php echo CNAMER_DEMO; ?> example to see the redirect 
            in action.
        </p>
        <h2 id="usage:cname"><a href="#usage:cname">CNAME</a></h2>
        <p>
            Specify the destination domain as a subdomain of <?php echo CNAMER_DOMAIN; ?>. A 
            simple subdomain redirect of <a href="http://google.<?php echo CNAMER_DEMO; ?>">google.<?php echo CNAMER_DEMO; ?></a>
            to google.com:
        </p>
        <code>
            google.<?php echo CNAMER_DEMO; ?>. CNAME google.com.<?php echo CNAMER_DOMAIN; ?>.
        </code>
        <p>
            Options can be specified as part of a cname as key.value pairs that 
            proceed an -opts- flag, after the target domain. For example to 
            redirect <a href="http://wikipedia.<?php echo CNAMER_DEMO; ?>">wikipedia.<?php echo CNAMER_DEMO; ?></a> to 
            wikipedia.org with a <a href="http://httpstatus.es/301" title="permanent">301</a> status code:
        </p>
        <code>
            wikipedia.<?php echo CNAMER_DEMO; ?>. CNAME wikipedia.org-opts-statuscode.301.<?php echo CNAMER_DOMAIN; ?>.
        </code>
        <p>
            Multiple options should be separated by dashes, for example 
            <a href="http://youtube.<?php echo CNAMER_DEMO; ?>">youtubessl.<?php echo CNAMER_DEMO; ?></a> 
            is a redirect to https://youtube.org with a 301 status code:
        </p>
        <code>
            youtubessl.<?php echo CNAMER_DEMO; ?>. CNAME youtube.com-opts-statuscode.301-protocol.https.<?php echo CNAMER_DOMAIN; ?>.
        </code>
        <h2 id="usage:txt"><a href="#usage:txt">TXT</a></h2>
        <p>
            TXT records are an alternative method of specifying redirect properties,
            the target and options should be encoded with JSON and set as the value
            of a TXT record matching your redirect domain prepended with "cnamer-".
            If your redirect is on "sub.domain.com" your TXT record name should be
            "cnamer-sub.domain.com". If you're using a root domain then the TXT
            record name should be "cnamer-root.domain.com". The only mandatory
            property is "destination", the rest are optional.
        </p>
        <p>
            For example to redirect
            <a href="http://github.<?php echo CNAMER_DEMO; ?>">github.<?php echo CNAMER_DEMO; ?></a> to 
            https://github.com with a status code of 301 create a TXT record:
        </p>
        <code>
            <span class="code-sub">Name</span>  cnamer-github.<?php echo CNAMER_DEMO; ?>
            <br/>
            <span class="code-sub">VALUE</span> {"destination":"https://github.com/", "statuscode": 301}
        </code>
        <p>
            Then create a CNAME pointing your chosen domain to txt.<?php echo CNAMER_DOMAIN; ?>,
            this tells <span class="cnamer">cnamer</span> to look for properties 
            in the TXT records:
        </p>
        <code>
            github.<?php echo CNAMER_DEMO; ?>. CNAME txt.<?php echo CNAMER_DOMAIN; ?>.
        </code>
        <h2 id="usage:a"><a href="#usage:a">A Record</a></h2>
        <p>
            A root domain (eg: <?php echo CNAMER_DEMO; ?>) cannot be a CNAME, a workaround for
            this is supported: point the A record for the root domain to the 
            <span class="cnamer">cnamer</span> server (<?php echo CNAMER_IP; ?>) 
            and then create a CNAME matching the root domain (using CNAME or TXT 
            options as described above). For example to redirect 
            <a href="http://<?php echo CNAMER_DEMO; ?>"><?php echo CNAMER_DEMO; ?></a> to 
            example.org:
        </p>
        <code>
            <span class="code-sub">A</span> <?php echo CNAMER_DEMO; ?>. IN A <?php echo CNAMER_IP; ?>
            <br/>
            <span class="code-sub">CNAME</span> <?php echo CNAMER_DEMO; ?>.<?php echo CNAMER_DEMO; ?>. CNAME example.org.<?php echo CNAMER_DOMAIN; ?>
        </code>
        <h1 id="options">2.<a href="#options">Options</a></h1>
        <p>
            There are a wide variety of use cases for domain redirects, each
            has unique requirements and <span class="cnamer">cnamer</span> 
            provides options to allow for the flexibility most situations will
            need.
        </p>
        <table id="options-table">
            <tr>
                <th>Option</th>
                <th>Values</th>
                <th>Description</th>
            </tr>
            <tr>
                <td>protocol</td>
                <td>https, <strong>http</strong>, ftp</td>
                <td>Protocol for the target URL</td>
            </tr>
            <tr>
                <td>statuscode</td>
                <td><strong>301</strong>, <a href="http://httpstatus.es">HTTP Status Code</a></td>
                <td>HTTP Status Code to be used in the redirect</td>
            </tr>
            <tr>
                <td>uri</td>
                <td>true or <strong>false</strong></td>
                <td>Append URI (if any) to the target URL</td>
            </tr>
            <tr>
                <td>uristring</td>
                <td><em>string</em></td>
                <td>Custom URI string (multi values joined in order)</td>
            </tr>
        </table>
        <h3 id="options:validation"><a href="#options:validation">validation</a></h3>
        <p>
            All values listed above are only suggestions and are not enforced,
            you are welcome to pass any values and the system will try and use
            the options, if they are invalid (eg: a non-existent protocol, 
            invalid status code) your redirect may fail.
        </p>
        <h1 id="notes">3.<a href="#notes">Notes</a></h1>
        <h3 id="notes:caching"><a href="#notes:caching">caching</a></h3>
        <p>
            If a DNS lookup is successful (responds with a CNAME record) then
            that response is cached for 10 minutes, this is to reduce server load
            and to reduce the time it takes for a redirect to take place.
            The lack of caching on failed requests means that if you are setting 
            up a CNAMER redirect for the first time you do not have to wait 10
            minutes to see if it's working; you can refresh as many times as you
            like until it redirects.
        </p>
        <h3 id="notes:response-times"><a href="#notes:response-times">Response Times</a></h3>
        <p>
            TODO
        </p>
        <h1 id="examples">4.<a href="#examples">Examples</a></h1>
        <p>
            Redirect <a href="http://support.mcf.li">support.mcf.li</a> to 
            support.curse.com/forums/22197033, using a 
            CNAME to configure the redirect:
        </p>
        <code>
            support.mcf.li. CNAME support.curse.com-opts-uristring.forums-uristring.22197033.cnamer.com.
        </code>
        <p>
            Example <a href="examples/support-desk-to-support-zendesk.png">DNS configuration screenshot</a> (Linode DNS Manager). 
        </p>
        <hr/>
        <p>
            Redirect <a href="http://citricsquid.com">citricsquid.com</a> to 
            samryan.co.uk, configured with a TXT record:
        </p>
        <code>
            <span class="code-sub">A</span> 176.58.124.239
            <br/>
            <span class="code-sub">CNAME</span> cnamer.citricsquid.com. CNAME txt.cnamer.com.
            <br/>
            <span class="code-sub">TXT</span> cnamer.citricsquid.com. TXT {"destination":"http://samryan.co.uk/"}
        </code>
        <p>
            Example <a href="examples/citricsquid.com-to-samryan.co.uk.png">DNS configuration screenshot</a> (Linode DNS Manager). 
        </p>
        <hr/>
        <p>
            Redirect <a href="http://search.mcf.li/search+terms">search.mcf.li/search+terms</a> 
            to google.com/search?q=site:minecraftforum.net+{search+terms} configured
            with a TXT record:
        </p>
        <code>
            <span class="code-sub">CNAME</span> search.mcf.li. CNAME txt.cnamer.com.
            <br/>
            <span class="code-sub">TXT</span> search.mcf.li. TXT {"destination":"https://google.com/search?q=site:minecraftforum.net+", "options":{"uri": true}}
        </code>
        <p>
            Example <a href="examples/search-mcf-li-to-google-com.png">DNS configuration screenshot</a> (Linode DNS Manager). 
        </p>
        <h1 id="use-cases">5.<a href="#use-cases">Use Cases</a></h1>
        <h2>Domain hack .coms</h2>
        <p>
            Redirecting a full length alternative to a domain hack to the proper
            domain, eg: httpstatuses.com -> httpstatus.es:
        </p>
        <code>
            <a href="http://httpstatuses.com">httpstatuses.com</a>. CNAME httpstatuses.com-opts-uri.true.cnamer.com.
        </code>
        <h2>Short URLS</h2>
        <h2>SaaS migration</h2>
        <p>
            Migrating from one platform to another can leave behind dead links 
            across the internet, moving from help.domain.com hosted by Desk to 
            support.domain.com hosted by Zendesk with a CNAMER redirect on the 
            previous domain is an easy way to ensure customers end up in the 
            right place.
        </p>
        <code>
            <a href="http://support.mcf.li">support.mcf.li</a>. CNAME support.curse.com-opts-uristring.forums-uristring.22197033.cnamer.com.
        </code>
        <h2>Holding Domains / Retired Domains</h2>
        <p>
            Domains being held for future projects or from retired projects
            don't need to be left pointing into the abyss, redirect to a personal 
            site or landing page.
        </p>
        <code>
            <a href="http://consumed.by">consumed.by</a>. CNAME samryan.co.uk.cnamer.com.
        </code>
    </div>
    <div id="footer">
        <div class="container">
            <h1 id="about"><a href="#about">About</a></h1>
            <p>
                <span class="cnamer">CNAMER</span> is 
                <a href="https://github.com/citricsquid/cnamer">open source</a>, code contributions, 
                general feedback and ideas are greatly appreciated via either 
                the <a href="https://github.com/citricsquid/cnamer/issues">GitHub issues</a>, 
                <a href="mailto:sam@samryan.co.uk">email</a> (sam@samryan.co.uk) 
                or <a href="http://twitter.com/citricsquid">@citricsquid</a> on
                Twitter.
            </p>
            <h3 id="about:pledge"><a href="#about:pledge">pledge</a></h3>
            <p>
                CNAMER.com is hosted on a cnamer specific Linode, I pledge to keep it
                online indefinitely and should any changes need to be made to
                the services availability I will provide 
                <strong>at minimum</strong> 3 month's notice. Notice will be
                provided through this website and the following mailing list, 
                <a href="http://samryan.us4.list-manage.com/subscribe?u=63f7de814651215b94b75905e&id=72c4386047">CNAMER Mailing List</a>.
            </p>
            <p>
                I understand that allowing a third party service control over the 
                behaviour of a domain is putting a great deal of trust in that
                service, with this in mind I promise that CNAMER(.com) will 
                <strong>never</strong> be sold, allowed to expire or used for any
                other purpose than what is outlined here. CNAMER will never direct
                a domain to anywhere other than the specified destination, it 
                will always be transparent and there will never be any 
                interstitial pages / advertisements.
                <!-- 
                    I can't imagine any scenario where someone would want to 
                    take ownership of this site however I want to be explicitly
                    clear so that there is no ambiguity. Even if I was offered
                    $1,000,000 I will not sell. No question.
                -->
            </p>
            <h3 id="about:status"><a href="#about:status">Status</a></h3>
            <p>
                CNAMER.com response time and incident history is available at 
                <a href="http://status.cnamer">status.cnamer.com</a>, cnamer.com
                and DNS redirects are both monitored by Pingdom.
            </p>
            <!--
            <p class="secret">
                I spent longer designing and redesigning this landing page + 
                writing documentation and rewriting documentation than I did 
                writing the actual code.
            </p>
            -->
        </div>
    </div>
</body>
</html>