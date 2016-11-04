<?php

namespace AlexaCRM\FetchXML;

/**
 * Represents entity attributes in the FetchXML query.
 *
 * @package AlexaCRM\FetchXML
 */
class FetchAttribute {

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    private $aliasName;

    /**
     * FetchAttribute constructor.
     *
     * @param string $name
     */
    public function __construct( $name, $alias = null ) {
        $this->name = $name;

        if ( is_string( $alias ) ) {
            $this->setAlias( $alias );
        }
    }

    /**
     * Specifies attribute alias
     *
     * @param string|null $aliasName
     *
     * @return $this
     */
    public function setAlias( $aliasName ) {
        $this->aliasName = $aliasName;

        return $this;
    }

    public function toDomNode() {
        $doc = new \DOMDocument();
        $attribute = $doc->createElement( 'attribute' );
        $attribute->setAttribute( 'name', $this->name );

        if ( !is_null( $this->aliasName ) ) {
            $attribute->setAttribute( 'alias', $this->aliasName );
        }

        return $attribute;
    }
}
