<?php

class Feat40128Test2 extends Sugar_PHPUnit_Framework_TestCase
{
   
    /**
     * @group feat40128
     */
    var $customDir = "custom/modules/Schedulers/jobs";

    public function setUp()
    {
        
        if (!is_dir($this->customDir))
          mkdir($this->customDir);

        file_put_contents($this->customDir . "/job1.php", "<?php\n" . "$" . "job_strings[count(" . "$" . "job_strings)+1] = 'NewProcedure1';\n?>");
        file_put_contents($this->customDir . "/job2.php", "<?php\n" . "$" . "job_strings[count(" . "$" . "job_strings)+1] = 'NewProcedure2';\n?>");
    }


    public function tearDown()
    {
        unlink($this->customDir . "/job1.php");
        unlink($this->customDir . "/job2.php");
        rmdir($this->customDir);
    }

    public function testRequireMoreCustomJobs()
    {
        // Requiring the standard file, that require (optionally) the custom file and (optionally) more custom files
        require_once "modules/Schedulers/_AddJobsHere.php";

        // In this test I'm expecting 6 standard jobs plus 2 "dummy" jobs defined in setUp method (in this test).

        $expectedIndexes = 8;
        $this->assertEquals($expectedIndexes, count($job_strings));
    }
}
