<?php

require_once "modules/Leads/Lead.php";
require_once "include/Popups/PopupSmarty.php";
require_once "modules/Leads/metadata/searchdefs.php";


class Bug43452Test extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @group bug43452
     */

    function testGenerateSearchWhereWithUnsetBool()
    {
        global $searchdefs;

        // Looking for a NON Converted Lead named "Fabio".
        // Without changes, PopupSmarty return a bad query, with AND and OR at the same level.
        // With this fix we get parenthesis:
        //     1) From SearchForm2->generateSearchWhere, in case of 'bool' (they surround "converted = '0' or converted IS NULL")
        //     2) From PopupSmarty->_get_where_clause, when items of where's array are imploded.

        $tBadWhere = "leads.first_name like 'Fabio%' and leads.converted = '0' OR leads.converted IS NULL";
        $tGoodWhere = "(leads.first_name like 'Fabio%' ) AND ( (leads.converted = '0' OR leads.converted IS NULL))";

        $_searchFields['Leads'] = array ('first_name'=> array('value' => 'Fabio', 'query_type'=>'default'),
                                         'converted'=> array('value' => '0', 'query_type'=>'default'),
                                        );

        $bean = $this->getMock('Lead');
        $popup = new PopupSmarty($bean, "Leads");
        $popup->searchForm->searchdefs =  $searchdefs['Leads'];
        $popup->searchForm->searchFields = $_searchFields['Leads'];
        $tWhere = $popup->_get_where_clause();

        $this->assertEquals($tGoodWhere, $tWhere);
        $this->assertNotEquals($tBadWhere, $tWhere);
    }

}

?>
