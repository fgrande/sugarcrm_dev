<?php
require_once 'include/SugarEmailAddress/SugarEmailAddress.php';


/**
 * 
 * Bug 44338
 *
 */

class Bug44338Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function providerEmailAddressRegex()
	{
	    return array(
	        array('john@john.com',true),
	        array('jo&hn@john.com',true),
	        array('joh#n@john.com.br',true),
	        array('&#john@john.com', true),
	        array('atendimento-hd.@uol.com.br',true),
	        array('atendimento-hd.?uol.com.br',false),
	        array('atendimento-hd.?uol.com.br;aaa@com.it',false),
	        array('f.grande@pokerspa.it',true),
	        array('fabio.grande@softwareontheroad.it',true),
	        array('fabio$grande@softwareontheroad.it',true),
	        array('ettingshallprimaryschool@wolverhampton.gov.u',false),
	        );
	}
    
    /**
     * @group bug44338
     * @dataProvider providerEmailAddressRegex
     */
    public function testEmailAddressRegex($email, $valid) 
    {
        $startTime = microtime(true);
        $sea = new SugarEmailAddress;
     
        // Check for address syntax   
        if ( $valid ) {
            $this->assertRegExp($sea->regex,$email);
        }
        else {
            $this->assertNotRegExp($sea->regex,$email);
        }     

        // Checking for elapsed time. I expect that evaluation takes less than a second.
        $timeElapsed = microtime(true) - $startTime;
        $this->assertLessThan(1.0, $timeElapsed);
    }
}
