<?php

class RenderTest extends PHPUnit_Framework_TestCase
{
    public function testHeaderPresence()
    {
        $this->assertFileExists('App/Views/Raw/base_header.php');
    }
    
    public function testFooterPresence()
    {
        $this->assertFileExists('App/Views/Raw/base_footer.php');
    }
    
    public function testIndexRendering()
    {
        $this->assertFileExists('App/Views/Home/index.php');
    }
    
    public function testAboutRendering()
    {
        $this->assertFileExists('App/Views/Home/about.php');
    }
    
    public function testBaseTemplatePresence()
    {
        $this->assertFileExists('App/Views/Mustache/base.html');
    }
    
    public function testErrorTemplatePresence()
    {
        $this->assertFileExists('App/Views/Mustache/error.html');
    }
}