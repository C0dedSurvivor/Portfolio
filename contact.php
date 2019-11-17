<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Jacob Brosler</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <header>
        <h1 id="title">Jacob Brosler's 235 Page</h1>
    </header>

    <main>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <label>Name</label>
            <input name="name" placeholder="Type Here">
               
            <label>Email</label>
            <input name="email" type="email" placeholder="person@sample.com">

            <label>Subject</label>
            <input name="subject" placeholder="Type Here">
               
            <label>Message</label>
            <textarea name="message" placeholder="Type Here"></textarea>
               
            <button class="g-recaptcha" data-sitekey="6LcbFsMUAAAAANEYdD345MwBR5Vxdh9oMPaFPR6N" data-callback='onSubmit' type="submit" value="Submit">Submit</button>
        </form>

        <?php   

        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
        {
            $secret = 'your_actual_secret_key';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);
            if($responseData->success)
            {
                $to = "jbb7824@rit.edu";
               
                $from = sanitize_string($_POST["email"]);
                if(empty($from) || !filter_var($from, FILTER_VALIDATE_EMAIL)){
                    echo "<p id=\"result\" style=\"color:red\"><b>Email failed. Please use a valid email address.</b></p>";
                    return;
                }
    
                $subject = "Portfolio: ".(empty(sanitize_string($_POST["subject"])) ? "No Subject" : sanitize_string($_POST["subject"]));
                   
                $message = sanitize_string($_POST["message"]);
                if(empty($message)){
                    echo "<p id=\"result\" style=\"color:red\"><b>Email failed. Please include a message.</b></p>";
                    return;
                }
                
                $name = empty(trim($_POST["name"])) ? "No name" : sanitize_string($_POST["name"]);
               
                $headers = "From: $from" . "\r\n";
                   
                // Add the user's name to the end of the message
                $message .= "\n\n - Sent by: $name";
    
                // Mail returns false if the mail can't be sent
                $sent = mail($to,$subject,$message,$headers);
                if ($sent){
                    echo "<p id=\"result\" style=\"color:green\"><b>Message Sent!</b></p>";
                }else{
                    echo "<p id=\"result\" style=\"color:red\"><b>Email failed. Unknown error.</b></p>";
                }
            }
            else
            {
                echo "<p id=\"result\" style=\"color:red\"><b>Email failed. Robot verification failed, please try again.</b></p>";
            }
        }
       
        // #9 - this handy helper function is very necessary whenever
        // we are going to put user input onto a web page or a database
        // For example, if the user entered a <script> tag, and we added that <script> tag to our HTML page
        // they could perform an XSS attack (Cross-site scripting)
        function sanitize_string($string){
            $string = trim($string);
            $string = strip_tags($string);
            return $string;
        }
    ?>

    </main>

    <footer>
    
    </footer>
</body>

</html>