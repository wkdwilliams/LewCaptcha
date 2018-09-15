<html>

<head>

</head>

<body>

  <?php

  require 'cap.php';

  /*$LewCaptch = new LewCaptcha\LewCaptcha(300, 150);

  if(isset($_POST['code'])){
    if($LewCaptch->codeIsCorrect($_POST['code'])){
      echo "Correct code";
    }
    else{
      echo "Incorrect code";
    }
  }*/

  if(isset($_POST['code'])){
    if(LewCaptcha\LewCaptcha::codeIsCorrect_quick($_POST['code'])){
      echo "Correct code";
    }
    else{
      echo "Incorrect code";
    }
  }


   ?>

  <form method="post">

    <input type="text" name="code" />
    <input type="submit" value="Go" style='margin-bottom: 20px;' /><br />

    <?= LewCaptcha\LewCaptcha::showCaptcha_quick() ?>
  </form>

</body>


</html>
