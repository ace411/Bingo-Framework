<?php

class __Mustache_28fb464e5b7a69f16a3a50e0f64aaa9c extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $blocksContext = array();

        $buffer .= $indent . '<!doctype HTML>
';
        $buffer .= $indent . '	<html>
';
        $buffer .= $indent . '		<head>
';
        $buffer .= $indent . '			<meta charset="utf-8">
';
        $buffer .= $indent . '			<title>';
        $value = $this->resolveValue($context->find('title'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '</title>
';
        $buffer .= $indent . '			<link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('stylesheet'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" type="text/css">
';
        $buffer .= $indent . '			<link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('font'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" type="text/css">
';
        $buffer .= $indent . '			<link rel="icon" type="image/png" href="';
        $value = $this->resolveValue($context->find('favicon'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        $buffer .= $indent . '		</head>
';
        $buffer .= $indent . '		<body>
';
        $buffer .= $indent . '            <!--This is the base Mustache template and is easily customizable-->
';
        $buffer .= $indent . '			<h1 align="center">Hello, ';
        $value = $this->resolveValue($context->find('firstname'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '</h1>
';
        $buffer .= $indent . '            <script src="';
        $value = $this->resolveValue($context->find('scriptOne'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '"></script>
';
        $buffer .= $indent . '		</body>
';
        $buffer .= $indent . '	</html>';

        return $buffer;
    }
}
