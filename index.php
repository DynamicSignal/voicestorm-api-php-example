<!DOCTYPE html>
<?php include_once 'config.php' ?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>VoiceStorm User Sample</title>
        <style>
            .container{
                width: 400px;
                margin: 80px auto;
            }
            .danger{
                color: red;
                font-size: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>VoiceStorm User Sample</h2>
            <?php
            if (isset($GLOBALS['voicestormAccessToken']) && !empty($GLOBALS['voicestormAccessToken']) && isset($GLOBALS['voicestormTokenSecret']) && !empty($GLOBALS['voicestormTokenSecret']) && isset($GLOBALS['voicestormBaseUrl']) && !empty($GLOBALS['voicestormBaseUrl']))
            {
                ?>
                <p>
                <form role = "form" method="post" action="formhandler.php" >
                    <p>Enter an email address to update an existing user or create a new one...</p>
                    <p>
                        <label for = "email">Email:</label>
                        <input type = "email" name="email" class = "form-control" id = "email" placeholder = "Enter email" >
                    </p>
                    <button type = "submit" class = "btn btn-primary">Submit</button>
                </form>
            </p>
            <?php
        }
        else
        {
            ?>
            <p class="danger">Please open config.php and set the required variables</p>
        <?php } ?>


    </div>
</body>
</html>
