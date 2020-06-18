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
*                          LOCALE PARSER 
*
*       Project:      PHPTeSy - PHP Template System
*       Filename:     language.php
*       Description:  PHPTesy Locale (Language) Parser
*       Author:       WildCS
*       Version:      1.0
*       Remarks:      uses json-formated locale-file to translate
*                     _$L{'JSON-KEY'} tags in templates
*                     'LOCALE_DIR' constant is defined in parser.php 
*                          
************************************************************************/


/* CLASS LANGUAGE PARSER */
class languageParser{
  /* public vars */
  public $languageBroken;     // bool - true if file is broken
  public $languageMissing = false;    // bool - true if file is missing
  public $fileVersion = "UNKNOWN";    // Version of language-file
  /* private vars */
  private $m_oLang = null;
  private $m_locale;
  private $m_file ="" ;

  
  /**************************************************************
  *
  *                     MAGIC FUNCTIONS
  *
  ***************************************************************/
  
  
  /* CONSTRUCT CLASS */
  /*  set default settings
  *
  *   @param string $locale  Locale code ( eg. en_EN, de_DE ) 
  */
  public function __construct( $locale )
  {
    /* set default vars */
    $this->languageBroken = false;
    $this->languageMissing = false;
    /* set language */
    $this->setLang( $locale );
  }
  
  /* RETURN TRANSLATION */
  /*  
  */
  function __toString()
  {
    return "WildCS-LanguageParser";
  }
  
  
  /**************************************************************
  *
  *                     PUBLIC FUNCTIONS
  *
  ***************************************************************/
  
  /* USE DEFAULT LANGUAGE */
  /*  use default language (eg. in case of error)
  */
  public function useDefaultLang()
  {
    $this->m_locale = DEFAULT_LANG;
    $this->_loadLanguage( DEFAULT_LANG );
  }
  
  /* SET LANGUAGE */
  /*  set new language
  *
  *   @param string $locale   new locale  
  */
  public function setLang( $locale )
  {
    $this->m_locale = $locale;
    $this->_loadLanguage( $locale );
    $this->getFileInfo();
  }
  
  /* GET FILEINFO */
  /*  get fileinfo of language-file
  *
  *   @return json            return full Info (JSON)
  */
  public function getFileInfo()
  {
    if ( isset($this->m_oLang["__INFO"] )){
      $this->fileVersion = $this->_getInfo("Version");
      return $this->m_oLang["__INFO"];
    }
  }
  
  /* TRANSLATE TEXT */
  /*  return translated text
  *  
  *   @param  string $textID    ID of TextSnippet in language-file
  *   @param  string $alt       [optional] Alternative text if not found 
  *   @return string            return translated text
  */
  public function translate( $textID, $alt = null )
  {
    if ( isset( $this->m_oLang[ $textID ] )){
      return $this->m_oLang[ $textID ];
    } else {
      if ( is_null ( $alt )){
        return $textID;
      } else {
        return $alt;
      }
    }
  }
  
  /* HAS ERROR */
  /*  return true if language files has error
  *
  *   @return bool    true if language file is missing or not valid json
  */
  public function hasErrors()
  {
   return ( $this->languageBroken OR $this->languageMissing );
  }
  
  
  /**************************************************************
  *
  *                     PRIVATE FUNCTIONS
  *
  ***************************************************************/
  
  /* GET LANGUAGE-FILE (PRIVATE) */
  /*  open language file
  *
  *   @param string $lang  - name of language file (without .json)
  */
  private function _loadLanguage( $lang ){  
    $bErrMissing = false;
    $bErrBroken = false;
     
    /* get locale file */
    $file = LOCALE_DIR . $lang . ".json";
    $this->m_file = $file;    

    /* open file */
    if ( file_exists( $file ) ) {
      $filecontent = file_get_contents( $file );
      //$filecontent = preg_replace( "/\/\*.*?\*\/|\/\/.*/mi", "", $filecontent ); // remove comments
      $this->m_oLang = json_decode( $filecontent, true );
      if ( json_last_error() > 0 ){
        $bErrBroken = true;
        $this->languageBroken = true;
      }
    } else {
      $bErrMissing = true; 
      $this->languageMissing = true;
    }
    
    /* error with language-file occured */
    if ( $bErrMissing OR $bErrBroken ){
      // use default lang or set zero array if default language is broken
      if ( $lang != DEFAULT_LANG ){
        $this->m_oLang = $this->useDefaultLang();
      } else {
        $this->m_oLang = array();
      }
    }
  }
  
  /* GET FILEINFO */
  /*  get special fileinfo of language-file
  *
  *   @param  string $infoKey key of info should be returned from language file 
  *   @return string          info found in language file
  */
  private function _getInfo( $infoKey )
  {
    if( isset( $this->m_oLang["__INFO"][$infoKey] )) { 
      return $this->m_oLang["__INFO"][$infoKey];
    }
  }    
}

?>
