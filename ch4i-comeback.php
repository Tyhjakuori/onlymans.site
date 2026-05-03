<!DOCTYPE html>
<html lang=en>

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Ch4i timer | OnlyMans</title>
    <meta name="description" content="Ch4i comeback timer">
    <meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1" />
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/ch4i_styles.css" type="text/css">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
</head>

<body>
    <?php echo file_get_contents("html/navigation.html"); ?>
    <script nonce="NGINX_CSP_NONCE" src=ch4i-timer.js>
    </script>
    <section class="countdown">
        <div class="timer">
            <h3>Ch4i comes back in:</h3>
            <div class="counter">
                <div class="counter__box black-white">
                    <p class="counter__time" id="days"></p>
                    <p class="counter__duration">days</p>
                </div>
                <div class="counter__box sky-blue">
                    <p class="counter__time" id="hours"></p>
                    <p class="counter__duration">hours</p>
                </div>
                <p class="dots">:</p>
                <div class="counter__box sky-blue">
                    <p class="counter__time" id="minutes"></p>
                    <p class="counter__duration">minutes</p>
                </div>
                <p class="dots">:</p>
                <div class="counter__box sky-blue">
                    <p class="counter__time" id="seconds"></p>
                    <p class="counter__duration">seconds</p>
                </div>

            </div>
        </div>
    </section>
    <?php echo file_get_contents("html/footer.html"); ?>
