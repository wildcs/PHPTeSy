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
*       Project:      PHPTeSy - PHP Template System
*       Filename:     index.php
*       Description:  PHPTesy Example index page
*       Author:       WildCS
*       Version:      1.0
*                          
************************************************************************/
/* INCLUDES */
require_once "src/phptesy/parser.php";

/* GLOBAL CONSTANTS */
define ( "ACTIVE_THEME", "default" );   // ACTIVE THEME
define ( "LOCALE", "en_EN" );           // LANGUAGE

/* DEFAULTS VARS*/
$site = "example";

/* SET VARS */
if ( isset( $_REQUEST["page"] )){
  $site = $_REQUEST["page"];
}

/* CREATE TEMPLATE CLASSSES */
$T = new TemplateParser();    // create class_alias
// $T = new TemplateParser( ACTIVE_THEME, LOCALE );    // create class_alias with theme and locale
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
    $T->renderThemeCSS();
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