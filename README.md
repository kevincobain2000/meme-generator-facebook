meme-generator-facebook
=======================

MEME GENERATOR using PHP and Post the resulting image to Facebook

<pre>



define("ROOT_DIR", "/Library/WebServer");
//Your path to the Smarty Class
require_once(ROOT_DIR."/libs/Smarty.class.php");

class MySmarty extends Smarty {
        public function __construct(){
                parent::__construct();
                $this->template_dir = ROOT_DIR."/smarty/templates";
                $this->compile_dir = ROOT_DIR."/smarty/templates_c";
                $this->left_delimiter = '{{';
                $this->right_delimiter = '}}';
        }

}



</pre>
