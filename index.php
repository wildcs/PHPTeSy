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
*                           INDEX EXAMPLE 
*
*       Filename:     index.php
*       Description:  PHPTesy Example index page
*       Author:       WildCS
*       Version:      1.0
*                          
************************************************************************/
/* GLOBAL CONSTANTS */
define ( "ACTIVE_THEME", "default" );   // ACTIVE THEME
define ( "LOCALE", "en_EN" );           // LANGUAGE

/* INCLUDES */
require_once "src/phptesy/parser.php";



/* DEFAULTS VARS*/
$site = "example";
$theme = ACTIVE_THEME;

/* SET VARS */
if ( isset( $_REQUEST["page"] )){
  $site = $_REQUEST["page"];
}

/* CREATE TEMPLATE CLASSSES */
$T = new TemplateParser();    // create class_alias
//$T = new TemplateParser();    // create class_alias

?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PHPTeSy <?php echo $T->getPageName( $site ) ;?></title>
    
    <?php
    /* get head-files from template */
    $T->renderHead( $site );
    /* get css-files from theme */
    $T->renderThemeCSS( $theme );
    ?>
  </head>
  
  <body>
    <?php
    /* get body from templates */
    $T->renderBody( 'header' );
    $T->renderBody( $site );
    ?>
  </body>
</html>