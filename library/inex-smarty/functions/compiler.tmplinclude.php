<?php
/*
 * Smarty plugin to include files based on INEX's IXP Manager
 * skin system.
 * 
 * -------------------------------------------------------------
 * Based On:
 * Author: Liu Song - loosen.copen@gmail.com
 * File: compiler.include_if_exists.php
 * Type: compiler
 * Name: include_if_exists
 * Version: 1.0.0
 * Source: http://code.google.com/p/smartyplugin-include-if-exists/
 * License: GNU LESSER GENERAL PUBLIC LICENSE
 * Purpose: Similar with "include" function, but only include the
    template file when it exists. Otherwise, a default file passed
    by parameter "else" will be included.
 * Example:
    1   {include_if_exists file="foo.tpl" assign="foo"}
    2   {include_if_exists file="foo.tpl" else="default.tpl"}
 * -------------------------------------------------------------
 */
function smarty_compiler_tmplinclude( $tag_attrs, &$compiler )
{
    $_params = $compiler->_parse_attrs( $tag_attrs );
    $arg_list = array();
    
    if( !isset( $_params['file'] ) ) 
    {
        $compiler->_syntax_error( "missing 'file' attribute in include_exists tag", E_USER_ERROR, __FILE__, __LINE__ );
        return;
    }

    foreach( $_params as $arg_name => $arg_value ) 
    {
        if( $arg_name == 'file' ) 
        {
            $include_file = $arg_value;
            continue;
        } 
        else if( $arg_name == 'assign' ) 
        {
            $assign_var = $arg_value;
            continue;
        }
        
        if( is_bool( $arg_value ) ) 
        {
            $arg_value = $arg_value ? 'true' : 'false';
        }
        
        $arg_list[] = "'$arg_name' => $arg_value";
    }

    $output = <<<MY_CODE
if( isset( \$this->_tpl_vars['___SKIN'] ) )
    \$skin = \$this->_tpl_vars['___SKIN'];
else 
    \$skin = false;

\$_include_file = {$include_file};
    
if( \$skin )
{
    if( \$this->template_exists( 'skins/' . \$skin . '/' . {$include_file} ) )
        \$_include_file = 'skins/' . \$skin . '/' . {$include_file};
}

if( \$this->template_exists( \$_include_file ) ) {

MY_CODE;
        
    if(isset($assign_var)) {
        $output .= "ob_start();\n";
    }

    $output .= "\$_smarty_tpl_vars = \$this->_tpl_vars;\n";

    $params = "array('smarty_include_tpl_file' => \$_include_file, 'smarty_include_vars' => array(".implode(',', (array)$arg_list)."))";

    $output .= "\$this->_smarty_include($params);\n" .
        "\$this->_tpl_vars = \$_smarty_tpl_vars;\n" .
        "unset(\$_smarty_tpl_vars);\n";

    if(isset($assign_var)) {
        $output .= "\$this->assign(" . $assign_var . ", ob_get_contents()); ob_end_clean();\n";
    }

    $output .= "unset( \$_include_file );\n}\n";

    return $output;
}
