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
*                           TEMPLATE PHP-FILE
*
*       Filename:     example.php
*       Description:  PHPTesy PHP-Function-File for 'example' template
*       Author:       WildCS
*       Version:      1.0
*       Remark:       PHP Code for templates will be defined here
*                          
************************************************************************/

/* DEFINE CONSTANT */
define ( "CONSTANT_STRING", "CALL SUCCESS" );

/* A FUNCTION */
function printString( $string )
{
  $string = str_replace( " is "," was returned by ", $string);
  return "'" .$string. "'";
}

/* A FUNCTION */
function addUp( $a, $b, $c ) 
{
  return $a+$b+$c;
}

/* A FUNCTION */
function aFunction(){
  return CONSTANT_STRING;
}
?>