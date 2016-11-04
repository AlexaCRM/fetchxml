<?php

use AlexaCRM\FetchXML\FetchAttribute;

class FetchAttributeTest extends PHPUnit_Framework_TestCase {

    public function testToDomNode() {
        /*
         * Test correct attribute name serialization
         */
        $attribute = new FetchAttribute( 'test' );
        $actualXml = $this->getActualXml( $attribute );

        $expectedXml = '<attribute name="test" />';
        $this->assertXmlStringEqualsXmlString( $expectedXml, $actualXml );

        /*
         * Test correct attribute alias serialization
         */
        $attribute->setAlias( 'foobar' );
        $actualXml = $this->getActualXml( $attribute );

        $expectedXml = '<attribute name="test" alias="foobar" />';
        $this->assertXmlStringEqualsXmlString( $expectedXml, $actualXml );

        /*
         * Test that alias is removed
         */
        $attribute->setAlias( null );
        $actualXml = $this->getActualXml( $attribute );

        $expectedXml = '<attribute name="test" />';
        $this->assertXmlStringEqualsXmlString( $expectedXml, $actualXml );
    }

    protected function getActualXml( FetchAttribute $attribute ) {
        $xmlNode = $attribute->toDomNode();

        return $xmlNode->ownerDocument->saveXML( $xmlNode );
    }
}
