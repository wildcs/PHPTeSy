<?php
/***********************************************************************                                                             
*      ,------. ,--.  ,--.,------. ,--------.       ,---.              *
*      |  .--. '|  '--'  ||  .--. ''--.  .--',---. '   .-',--. ,--.    *
*      |  '--' ||  .--.  ||  '--' |   |  |  | .-. :`.  `-. \  '  /     *
*      |  | --' |  |  |  ||  | --'    |  |  \   --..-'    | \   '      *
*      `--'     `--'  `--'`--'        `--'   `----'`-----'.-'  /       *
*                                                         `---'        *
*                        PHPTemplateSystem                             *
*                     © WildCS - Christian Wild                        *
*                  https://github.com/wildcs/PHPTeSy/                  *
*                _      __   _    __     __  _____   ____              *
*               | | /| / /  (_)  / / ___/ / / ___/  / __/              *
*               | |/ |/ /  / /  / / / _  / / /__   _\ \                *
*               |__/|__/  /_/  /_/  \_,_/  \___/  /___/                *
*                                                                      *
************************************************************************
*                          TEMPLATE RENDERER
*
*       Filename:     parser.php
*       Description:  PHPTesy Template Parser
*       Author:       WildCS
*       Version:      1.0
*       Remarks:      parsing and rendereing templates to your
*                     html or php files. A language parser is also
*                     included.    
*                          
************************************************************************/
require_once "language.php";


/**********************************************************************
*                        SETTINGS
***********************************************************************/

/* directories */
define ( "TEMPLATE_DIR",  "templates/" );   // directory site templates
define ( "THEME_DIR",     "themes/" );       // directory style-themes
define ( "LOCALE_DIR",      "locale/" );      // directory language-files

/* defaults (do not change) */
define ( "DEFAULT_THEME", "default" );      // default theme-name (THEME_DIR . DEFAULT_THEME.css file) 
define ( "DEFAULT_LANG",  "en_EN" );        // default language-file (LOCALE_DIR . DEFAULT_LANG.json file) 



/* CLASS TEMPLATE PARSER */
class TemplateParser
{
  /* local vars */
  private $m_lang;                    // language parser class
  /* template vars */
  private $m_HTMLfile = [];           // HTML-template file
  private $m_PHPfile = [];            // PHP-code file
  private $m_HTML = [];               // HTML-Code
  private $m_head = [];               // inner HEAD-CODE
  private $m_body = [];               // inner Body-CODE
  private $m_pageTitle = [];          // Page-Title
  private $m_templateName = [];       // Name of template
  private $m_warning = [];            // Array with class warnings
  /* theme vars */
  private $m_themeFile;               // Theme-File
  private $m_themeName;               // Theme-Name

/**************************************************************
  *
  *                     MAGIC FUNCTIONS
  *
  ***************************************************************/
  
  /* CONSTRUCT CLASS */
  /*  class constructor
  *
  *   @param string $theme  optional theme name (default = DEFAULT_THEME)
  *   @param string $lang   optional locale  (default = DEFAULT_LANG)
  */
  public function __construct( $theme = DEFAULT_THEME, $lang = DEFAULT_LANG)
  {
    /* set theme */
    $this->_loadTheme( $theme );
    /* create language parser class*/
    $this->m_lang = new languageParser( $lang );   // init languageParser class$this->setLang( $locale );
  }
  
  
  /* TO__STRING */
  /*  
  */
  function __toString()
  {
    return "WildCS-TemplateParser";
  }
  

  /**********************************************************************
  *
  *                       TEMPLATE HANDLING
  *
  ***********************************************************************/


  /* INIT TEMPLATE VARS (PRIVATE) */
  /*  set default template vars
  *
  *   @param string  $template  - name of template file (without .php)
  */
  private function _initTemplate( $template )
  {
    $this->m_templateName[ $template ] = $template;         // Template Name
    $this->m_HTMLfile[ $template ] = TEMPLATE_DIR . $template . ".html";    // HTML-File
    $this->m_PHPfile[ $template ] = TEMPLATE_DIR . $template . ".php";      // PHP-File

    $this->m_head[ $template ] = "";                // inner HEAD-CODE
    $this->m_body[ $template ] = "";                // inner Body-CODE
    $this->m_pageTitle[ $template ] = "";           // Page-Title     
  }


  /* GET TEMPLATE-FILE (PRIVATE) */
  /*  open template file
  *
  *   @param  string  $template  - name of template file (without .php)
  *   @return bool    true if template file was found and loaded
  */
  private function _loadTemplate( $template )
  {
    /* check if requestet template is already loaded */
    if ( ! isset( $this->m_templateName[ $template ] )){
      /* set default vars for template["name"]*/
      $this->_initTemplate( $template );
      
      /* include PHP-Codefile */
      if ( file_exists( $this->m_PHPfile[ $template ] ) ) {
        include_once $this->m_PHPfile[ $template ];
      }
      
      /* open HTML-Template */
      if ( file_exists( $this->m_HTMLfile[ $template ] ) ) {
        $html = file_get_contents( $this->m_HTMLfile[ $template ] );
        $this->m_HTML[ $template ] = $html;
        $this->_parseHTML( $template );
      } else {
        return false;
      }
    } 
    return true;
  }


  /* GET THEME-FILE (PRIVATE) */
  /*  open theme file
  *
  *   @param  string  $theme  - name of theme file (without .css)
  *   @return string  path to theme css-file
  */
  private function _loadTheme( $theme ){
    /* include template file */
    $file = THEME_DIR . $theme . ".css";  
    //echo "TEMPLATE-FILE: $file";
    if ( ! file_exists( $file ) ) {
      $file = THEME_DIR . DEFAULT_THEME . ".css";  
      $this->m_warning["css-missing"] = $theme;
    }
    $this->m_themeFile = $file;
    $this->m_themeName = $theme;
    return $file;
  }
  
  /**********************************************************************
  *
  *                    TEMPLATE PARSING
  *
  ***********************************************************************/
  
  /* PARSE HTML CODE */
  /*  parse whole HTML Code
  *
  *   @param  string  $template  - name of template file (without .php)
  */
  private function _parseHTML( $template ){
    $this->_parseHead( $template );
    $this->_parseBody( $template );
  }
  
  /* PARSE HTML-HEAD */
  /*  get inner HTML-HEAD from template
  *
  *   @param  string  $template  - name of template file (without .php)  
  */
  private function _parseHead( $template ){
    $code =  $this->m_HTML[ $template ];
    $pageTitle = "";
    $innerHead = "";
    
    /* get inner-head */
    $re = '/\<head\>([\s\S]*)\<\/head\>/im';
    preg_match( $re, $code, $matches );
    if ( isset( $matches[1] )){
      $innerHead = $matches[1];
    } else {
      $this->m_warning["head-missing"] = $template; // has no <head>
    }
    
    /* get page-title */
    $re = '/<TITLE\>(.*)\<\/TITLE\>/im';
    preg_match($re, $innerHead, $matches );
    if ( isset( $matches[1] )){
      $pageTitle = $matches[1];
    }
    /* remove title from head */
    $innerHead = preg_replace($re, "", $innerHead );

    /* set vars */
    $this->m_pageTitle[ $template ] = $pageTitle;
    $this->m_head[ $template ] =  $innerHead;
  }
  
  /* PARSE HTML-Body */
  /*  get inner HTML-Body from template
  *
  *   @param  string  $template  - name of template file (without .php)  
  */
  private function _parseBody( $template ){
    $code =  $this->m_HTML[ $template ];
    $innerBody = "";
    /* get inner-body */
    $re = '/\<body\>([\s\S]*)\<\/body\>/im';
    preg_match( $re, $code, $matches );
    if ( isset( $matches[1] )){
      $innerBody = $matches[1];
      $innerBody = $this->_replaceTags( $innerBody );     
    } else {
      $this->m_warning["body-missing"] = $template; // has no <body>
    }
    $this->m_body[ $template ] = $innerBody;
  }


  /**********************************************************************
  *                    TAG REPLACEMENT
  ***********************************************************************/

  /* REPLACE ALL TAGs */
  /*  replace all tags
  *
  *   @param  string  $text (STR)  - full text where tags should be replaced
  *   @return string  full text with replaced tags
  */
  private function _replaceTags( $text )
  {
    $text = $this->_replaceLang( $text );
    $text = $this->_replaceFunction( $text );
    return $text;
  }


  /* REPLACE LANGUAGE TAGs */
  /*  replace language tags with translation 
  *
  *   @param  string  $text (STR)  - full text where tags should be replaced
  *   @return string  full text with replaced tags
  */
  private function _replaceLang( $text ){
    /* replace tags */
    $re = '/_\$L\{(.*)}/im';

    while ( preg_match($re, $text, $matches) ){
      $par = $this->_splitPar( $matches[1], 2 );
      $replacement = $this->getTranslation( $par[0], $par[1] );  
      //$replacement = " x" .call_user_func( getTranslation, $par );
      $text = str_replace( $matches[0], $replacement, $text );
    };
    return $text;
  }
  
  
  /* REPLACE FUNCTION-TAGS */
  /*  replace function tags with return of php funtions
  *
  *   @param  string  $text (STR)  - full text where tags should be replaced
  *   @return string  full text with replaced tags
  */
  private function _replaceFunction( $text ){
    /* replace tags */
    $re = '/_\$F\{(.*)}/im';

    while ( preg_match($re, $text, $matches) ){
      /* parse parameters */
      $par = $this->_splitPar( $matches[1] );   
      $fn = array_shift( $par );  //get function name and remove from parameter array  
      /* call function */
      if ( function_exists( $fn ) ){
        $replacement = call_user_func_array( $fn, $par );
      } else {
        $replacement = "F{" .$matches[1]. "}";
        $this->m_warning["function-missing"] = "'" .$fn . "' in (". $this->m_PHPfile. ")"  ;
      }
      /* replace text */
      $text = str_replace( $matches[0], $replacement, $text );
    };
    return $text;
  }
  


  /**********************************************************************
  *
  *                         RENDERING 
  *
  ***********************************************************************/

  /* GET PAGENAME */
  /*  get pagename from template 
  *   read constant 'PAGENAME'
  *
  *   @param  string  $template  - name of template file (without .php)
  *   @return string  page title declared in <head>
  */
  public function getPageName( $template ){
    /* include template file */
    if ( $this->_loadTemplate( $template ) ) {
      return $this->m_pageTitle[ $template ];
    }
  }

  /* RENDER HEAD */
  /*  render html header
  *
  *   @param  string  $template  - name of template file (without .php)  
  */
  public function renderHead( $template ){
    /* include template file */
    if ( $this->_loadTemplate( $template ) ) {
      echo $this->m_head[ $template ];
    }
  }

  /* RENDER BODY */
  /*  render html body
  *
  *   @param  string  $template  - name of template file (without .php)  
  */
  public function renderBody( $template ){
    /* include template file */
    if ( $this->_loadTemplate( $template ) ) {
      $this->_showWarnings(); // show renderer warnings
      echo $this->m_body[ $template ];
    } else {
      $this->_404( $template );
    }
  }

  /* RENDER THEME-CSS-FILE */
  /*  render theme css
  *
  *   @param  string $theme - Name of theme file (without .css)
  */
  function renderThemeCSS( $theme ){
    if( ! isset( $this->m_warning["css-missing"] )){
      ?>
      <link rel="stylesheet" type="text/css" href="<?php echo $this->m_themeFile;?>"  />
      <?php
    }
  }


  /* GET LANGUAGE TRANSLATION */
  /*  load text from language-file
  *
  *   @param  string $textID  ID of TextSnippet in language-file
  *   @param  string $alt     [optional] Alternative text if not found 
  *   @return string          translated text
  */
  function getTranslation( $textID, $alt = null ){
    $m_lang = $this->m_lang;
    return $m_lang->translate( $textID, $alt);
  }


  /**************************************************************
  *
  *                     PRIVATE HELP-FUNCTIONS
  *
  ***************************************************************/
 
  /* SPLIT PARAMETERS */
  /*  split parameters from a string into array 
  *
  *   @param string  $string  string contains , seperated parameters
  *   @param int $len         [optional] lenght of return array 
  *   @return array|string    array with parameters or single string
  */
  private function _splitPar( $string, $len = 0)
  {
    $re = '/\'[^\']*\'(*SKIP)(*F)|,/im';
    $array = preg_split( $re, $string ); 
    if ( is_array($array) ){
      foreach($array as $key => $value)
      {
        $array[$key] = $this->_trimPar( $value );
      }
      return $array;
    } else {
      return _trimPar( $string );
    }
  } 

  /* TRIM PARAMETERS */
  /*  remove '" and whitespaces from begin and end
  *
  *   @param  string  $string  string to be trimmed
  *   @return string           trimmed string
  */
  private function _trimPar( $string )
  {
    $string = trim( $string );
    $string = trim( $string ,"'" );
    $string = trim( $string ,'"' );
 
    return $string;
  } 

  /**********************************************************************
  *
  *                        ERROR HANDLING
  *
  ***********************************************************************/

  /* RETURN 404 */
  /*  show 404 error
  *
  *   @param  string $sitename  - name of site not found
  */
  function _404( $sitename ){
    ?>
    <p>
      <h3>404 - Site not Found</h3>
    </p>
    <p><?php echo $sitename; ?></p>
    <?php
  }

  /* SHOW WARNINGS */
  /*  show warning messages on template body
  */
  function _showWarnings(){
    /* template warnings */
    if ( isset( $this->m_warning['body-missing'] )){
      ?><p><b>WARNING: The Template for '<?php echo $this->m_warning['body-missing']; ?>' may be broken.<br />Can't find 'body'</b></p><?php
    }
    if ( isset( $this->m_warning['css-missing'] )){
      ?><p><b>WARNING: Theme '<?php echo $this->m_warning['css-missing']; ?>' is missing</b></p><?php
    }
    if ( isset( $this->m_warning['function-missing'] )){
      ?><p><b>WARNING: Function missing or not callable <?php echo $this->m_warning['function-missing']; ?></b></p><?php
    }
    
    /* language parser warnings */
    $m_lang = $this->m_lang;
    if ( $m_lang->languageBroken ){
      ?><p><b>WARNING: Language-File seems to be broken</b><p><?php
    }
    if ( $m_lang->languageMissing ){
      ?><p><b>WARNING: Language-File is missing</b><p><?php
    }

  }
}
?>