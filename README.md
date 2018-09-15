This simple lightweight library was created in effort to protect applications from malicious programs, spammers, bruteforcers etc and to perform general security checks on users accessing websites. The library generates a random string of characters, rotates and repositions the characters to confuse AI.

## Quick installation
```php
<?php

require_once("cap.php");

$LewCaptcha = new LewCaptcha\LewCaptcha();

if(isset($_POST['code'])){
    if($LewCaptch->codeIsCorrect($_POST['code'])){
      echo "Correct code";
    }
    else{
      echo "Incorrect code";
    }
}

?>

<html>

<head>

</head>

<body>

  <form method="post">

    <input type="text" name="code" />
    <input type="submit" value="Go" style='margin-bottom: 20px;' /><br />

    <?= $LewCaptcha->showCaptcha() ?>
  </form>

</body>


</html>
```

## Custom fonts
Place your prefered fonts in the "fonts" directory. LewCaptcha will use these fonts automatically. 
