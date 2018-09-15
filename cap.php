<?php

namespace LewCaptcha;

class LewCaptcha{

  private $width;           //Width of image
  private $height;          //Height of image
  private $charLength;      //Char length of captcha code

  private $charsToUse = "abcdefghijklmnopqrstuvwxyz1234567890";   //Chars which can display in the captcha code
  private $fontDirectory = __DIR__."/fonts";                      //Directory containing the fonts

  //The CSS style of the captcha code
  private $styles = array(
    "background-color" => array(255, 255, 255),
    "font-color" => array(0, 0, 0),
    "border-color" => array(0, 0, 0)
  );

  public function __construct($w=300, $h=100, $l = 6){
    $this->width = $w;
    $this->height = $h;
    $this->charLength = $l;

    if (session_status() == PHP_SESSION_NONE) {
      session_start();  //Start session if not already started
    }

    $this->newCode(); //Generate new captcha code upon construction
  }

  private function getFont(){
    $files = scandir($this->fontDirectory);

    unset($files[0]);
    unset($files[1]);

    $files = array_values($files);

    return $this->fontDirectory."/".$files[rand(0, count($files)-1)];
  }

  private function newCode(){
    $_SESSION['LewCaptchaOld'] = $_SESSION['LewCaptcha'];
    $_SESSION['LewCaptcha'] = "";

    for($i = 0; $i < $this->charLength; $i++){
      $_SESSION['LewCaptcha'] .= strtolower(str_split($this->charsToUse)[rand(0, strlen($this->charsToUse)-1)]);
    }
  }

  private function generateImage(){
    $img = imagecreate($this->width, $this->height);
    $background = imagecolorallocate($img, $this->styles['background-color'][0], $this->styles['background-color'][1],
                                            $this->styles['background-color'][2]);
    $text_colour = imagecolorallocate($img, $this->styles['font-color'][0], $this->styles['font-color'][1], $this->styles['font-color'][2]);
    $font = $this->getFont();

    $charCount = count(str_split($_SESSION['LewCaptcha']));

    $lastLeft = 0;

    foreach(str_split($_SESSION['LewCaptcha']) as $i=>$item){

        $fontSize = ((($this->width+$this->height) / 2) - ($this->width)/$charCount)*0.5;
        $left = $this->width-$this->width+$lastLeft;
        $lastLeft = $left - ($fontSize/$charCount)+($this->width/$charCount+($this->width+$this->height)/25);
        $top = $fontSize+$fontSize/2; //rand($fontSize, $fontSize+$fontSize);
        $angle = rand(-10, 10);

        imagettftext($img, $fontSize, $angle, $left, $top, $text_colour, $font, $item);

    }

    //imagettftext($my_img, 10, 0, 205, 98, $text_colour, __DIR__ . "/fonts/Lato-Black.ttf", "Lewis Williams");

    return $img;
  }

  public function codeIsCorrect($code){
    return strtolower($code) == $_SESSION['LewCaptchaOld'];
  }

  public function showCaptcha($type = "PNG"){
    if($type=="PNG"){
      header("Content-type: image/png");
      imagepng($this->generateImage());
      imagedestroy($this->generateImage());
    }
    else if($type=="BASE64"){
      ob_start();
      imagepng($this->generateImage());
      imagedestroy($this->generateImage());
      $imagedata = ob_get_contents();
      ob_end_clean();

      return "data:image/png;base64,".base64_encode($imagedata);
    }
    else if($type=="HTML"){
      return "<img src='".$this->showCaptcha("BASE64")."' alt='LewCaptcha' style='border: 1px solid rgb(".implode(",", $this->styles['border-color']).")' />";
    }
  }

  public static function showCaptcha_quick($width=300, $height=150){
    $LewCaptcha = new LewCaptcha($width, $height);

    return $LewCaptcha->showCaptcha("HTML");
  }

  public static function codeIsCorrect_quick($code){
    $LewCaptcha = new LewCaptcha();

    return $LewCaptcha->codeIsCorrect($code);
  }
}
?>
