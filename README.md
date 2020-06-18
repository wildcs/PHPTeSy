# PHPTeSy
PHP Template System

##HowTo:
For each Template you can create a HTML, and a PHP file in /templates folder.
The HTML-File Contains the design template.
You can use normal html and js code here. You've to declare an <head>(optional) and <body>-tag.
There are some special "magic" tags to use locales and php functions, which must be declared in the template.php-file. 

###Example of 'template.html':


### RENDER TEMPLATES
Init parser class and render templates. Use this in your main/index php-file.
``` 
$T = new TemplateParser();                  // create parser class
$T->getPageName( 'your-template-name' );    // returns title from <head> of template
$T->renderHead( 'your-template-name' );     // put this into <head> of your index.php. Pastes content of template's head there
$T->renderBody( 'your-template-name' );     // put this into <body> of your index.php. Pastes content of template's body there
```

### USING THEMES 
When themes should be used, put this into <head> of your main/index php-file. 
```
$T->renderThemeCSS( 'your-theme-name' );    // pastes css-file of theme there 
```

### TEMPLATE MAGIC-TAGS 
In template.html u can use "magic-tags"
Call a function: 
```   
_$F{ fnName, 'par1', 'p....', 'parX' }
```
If function returns a value, the value is printed there

Language Parser:    
```
_$L{'PARSER_EXAMPLE'}
_$L{'This is just a text, which will not be found in the language-file. So it is shown as it is'}
_$L{'USE_ALT_TEXT', 'This shortkey is also not found, but an alt-text parameter is set'}
```                   
                    