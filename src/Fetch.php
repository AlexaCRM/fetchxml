<?php

namespace AlexaCRM\FetchXML;

/**
 * FetchXML Query Builder.
 *
 * @package AlexaCRM\FetchXML
 */
class Fetch {

    /**
     * Retrieve only distinct records
     *
     * @var bool
     */
    private $isDistinct = false;

    /**
     * Entity name to fetch
     *
     * @var string
     */
    private $entityName;

    /**
     * Retrieve all attributes of the given entity
     *
     * @var bool
     */
    private $isAllAttributes = false;

    /**
     * List of attributes to fetch
     *
     * @var FetchAttribute[]
     */
    private $attributes = [];

    /**
     * Limit the result to a number of records
     *
     * @var int
     */
    private $count;

    /**
     * Fetch order
     *
     * @var array
     */
    private $order;

    /**
     * Specifies the entity to fetch.
     *
     * @param string $entityName
     *
     * @return $this
     */
    public function setEntity( $entityName ) {
        $this->entityName = $entityName;

        return $this;
    }

    /**
     * Specifies whether to retrieve only distinct records or not.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setDistinct( $value = false ) {
        if ( !is_bool( $value ) ) {
            throw new \InvalidArgumentException( 'Argument must be boolean' );
        }

        $this->isDistinct = $value;

        return $this;
    }

    /**
     * Specifies whether to fetch all available attributes.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setAllAttributes( $value = false ) {
        if ( !is_bool( $value ) ) {
            throw new \InvalidArgumentException( 'Argument must be boolean' );
        }

        $this->isAllAttributes = $value;

        return $this;
    }

    /**
     * Adds an attribute to the fetch-entity expression.
     *
     * @param string|FetchAttribute $attribute Attribute name or FetchAttribute instance
     *
     * @return $this
     */
    public function addAttribute( $attribute, $alias = null ) {
        $newAttribute = $attribute;

        if ( !( $attribute instanceof FetchAttribute ) ) {
            $newAttribute = new FetchAttribute( $attribute, $alias );
        }

        $this->attributes[$newAttribute->name] = $newAttribute;

        return $this;
    }

    /**
     * Adds a collection of attributes to the fetch-entity expression.
     *
     * @param string[]|FetchAttribute[] $attributes Array of strings, FetchAttribute, may be both
     *
     * @return $this|Fetch
     */
    public function addAttributes( $attributes ) {
        if ( !is_array( $attributes ) ) {
            return $this->addAttribute( $attributes );
        }

        foreach ( $attributes as $attributeKey => $attributeValue ) {
            if ( $attributeValue instanceof FetchAttribute ) {
                $this->addAttribute( $attributeValue );
                continue;
            }

            $attrName = $attributeValue; // by default no aliases
            $attrAlias = null;

            if ( is_string( $attributeKey ) ) {
                $attrName = $attributeKey;
                $attrAlias = $attributeValue;
            }

            $this->addAttribute( $attrName, $attrAlias );
        }

        return $this;
    }

    /**
     * Limits the result to a certain amount of records.
     *
     * @param int|null $value
     *
     * @return $this
     */
    public function setCount( $value ) {
        if ( !( is_int( $value ) || $value === null ) ) {
            throw new \InvalidArgumentException( 'Argument must be an integer or NULL' );
        }

        $this->count = $value;

        return $this;
    }

    /**
     * Specifies the fetch order.
     *
     * @param string $attribute Use NULL value to remove order statement
     * @param bool $descending Optional. Default: false
     *
     * @return $this
     */
    public function setOrder( $attribute, $descending = false ) {
        if ( $attribute === null ) {
            $this->order = null;

            return $this;
        }

        if ( trim( $attribute ) === '' ) {
            throw new \InvalidArgumentException( 'Attribute argument must be a string' );
        }

        if ( !is_bool( $descending ) ) {
            throw new \InvalidArgumentException( 'Descending argument must be a boolean' );
        }

        $this->order = [
            'attribute' => $attribute,
            'descending' => $descending
        ];

        return $this;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->toXML();
    }

    /**
     * Generates a FetchXML expression.
     *
     * @return string
     */
    public function toXML() {
        $queryDom = new \DOMDocument();

        $fetch = $queryDom->appendChild( $queryDom->createElement( 'fetch' ) );
        $fetch->setAttribute( 'mapping', 'logical' );
        $fetch->setAttribute( 'distinct', $this->isDistinct ? 'true' : 'false' );
        if ( $this->count > 0 ) {
            $fetch->setAttribute( 'count', $this->count );
        }

        if ( is_null( $this->entityName ) ) {
            return $queryDom->saveXML( $fetch );
        }

        $entity = $fetch->appendChild( $queryDom->createElement( 'entity' ) );
        $entity->setAttribute( 'name', $this->entityName );

        if ( $this->isAllAttributes ) {
            $entity->appendChild( $queryDom->createElement( 'all-attributes' ) );
        } elseif ( count( $this->attributes ) ) {
            foreach ( $this->attributes as $attribute ) {
                $entity->appendChild( $queryDom->importNode( $attribute->toDomNode() ) );
            }
        }

        if ( is_array( $this->order ) ) {
            $order = $entity->appendChild( $queryDom->createElement( 'order' ) );
            $order->setAttribute( 'attribute', $this->order['attribute'] );
            $order->setAttribute( 'descending', $this->order['descending']? 'true' : 'false' );
        }

        return $queryDom->saveXML( $fetch );
    }

}
