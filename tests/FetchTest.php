<?php

use AlexaCRM\FetchXML\Fetch;

class FetchTest extends PHPUnit_Framework_TestCase {

    public function testNew() {
        $fetch = new Fetch();

        $expectedXml = '<fetch distinct="false" mapping="logical" />';
        $actualXml = $fetch->toXML();
        $this->assertXmlStringEqualsXmlString( $expectedXml, $actualXml );
    }

    public function testSetDistinct() {
        $fetch = new Fetch();
        $fetch->setDistinct( true );

        $expectedXml = '<fetch distinct="true" mapping="logical" />';
        $actualXml = $fetch->toXML();
        $this->assertXmlStringEqualsXmlString( $expectedXml, $actualXml );

        $fetch->setDistinct( false );

        $expectedXml = '<fetch distinct="false" mapping="logical" />';
        $actualXml = $fetch->toXML();
        $this->assertXmlStringEqualsXmlString( $expectedXml, $actualXml );
    }

    public function testSetEntity() {
        $fetch = new Fetch();
        $fetch->setEntity( 'foobar' );

        $expectedXml = '<fetch distinct="false" mapping="logical"><entity name="foobar"/></fetch>';
        $actualXml = $fetch->toXML();
        $this->assertXmlStringEqualsXmlString( $expectedXml, $actualXml );
    }

    public function testAddAttribute() {
        $fetch = new Fetch();
    }
}
