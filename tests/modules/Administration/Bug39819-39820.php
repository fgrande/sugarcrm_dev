<?php

require_once 'PHPUnit/Extensions/OutputTestCase.php';

class Bugs39819_39820 extends Sugar_PHPUnit_Framework_TestCase
{

    /**
     * @group Bug39819-39820
     */
    var $customDir = "custom/modules/Accounts/language";

    public function setUp()
    {
        
        if (!is_dir($this->customDir))
          mkdir($this->customDir, 0700, TRUE); // Creating nested directories at a glance

        file_put_contents($this->customDir . "/it_it.help.DetailView.html", "<h1>Bugs39819-39820</h1>");
    }


    public function tearDown()
    {
        unlink($this->customDir . "/it_it.help.DetailView.html");
    }

    public function testLoadCustomItHelp()
    {
        // Custom help (NOT en_us) on a standard module.

        $_SERVER['HTTP_HOST'] = "";
        $_SERVER['SCRIPT_NAME'] = "";
        $_SERVER['QUERY_STRING'] = "";

        $_REQUEST['view'] = 'documentation';
        $_REQUEST['lang'] = 'it_it';
        $_REQUEST['help_module'] = 'Accounts';
        $_REQUEST['help_action'] = 'DetailView';

        ob_start();
        require_once "modules/Administration/SupportPortal.php";

        $tStr = ob_get_contents();
        ob_clean();

        // I expect to get the custom help....
        $this->assertRegExp("/.*Bugs39819\-39820.*/", $tStr);
    }

}

