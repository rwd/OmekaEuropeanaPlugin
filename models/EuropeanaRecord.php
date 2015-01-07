<?php

/**
 * Models a Europeana record as retrieved from the Europeana API.
 *
 * Note that this model has no corresponding database table and is only declared
 * as extending Omeka_Record_AbstractRecord for compatibility with the 
 * metadata() view helper function.
 *
 * @todo Provide a method to retrieve a record from the API and instantiate an
 *   object from the response.
 */
class EuropeanaRecord extends Omeka_Record_AbstractRecord {
    
    protected $_apiProperties = array(
        "dataProvider", "dcCreator", "edmIsShownAt", "edmPlaceLatitude", 
        "edmPlaceLongitude", "edmPreview", "europeanaCompleteness", 
        "guid", "id", "link", "provider", "rights", "score", "title", "type", 
        "year", "edmConceptTerm", "edmConceptPrefLabel", "edmConceptBroaderTerm", 
        "edmConceptBroaderLabel", "edmTimespanLabel", "edmTimespanBegin", 
        "edmTimespanEnd", "edmTimespanBroaderTerm", "edmTimespanBroaderLabel", 
        "recordHashFirstSix", "ugc", "completeness", "country", 
        "europeanaCollectionName", "edmPlaceBroaderTerm", "edmPlaceAltLabel", 
        "dctermsIsPartOf", "timestampCreated", "timestampUpdate", "language", 
        "dctermsSpatial", "edmPlace", "edmTimespan", "edmAgent", 
        "edmAgentLabel", "dcContributor", "edmIsShownBy", "dcDescription", 
        "edmLandingPage"
    );
    
    public function construct()
    {
        $this->lock();
    }
    
    /**
     * Get a property about the record for display purposes.
     *
     * Europeana record properties will have camel case names like edmIsShownAt,
     * but Omeka expects the $property argument to this function to be lowercase.
     * The method here accounts for that by checking for the presence of a
     * property on the record with the lowercased version of its name matching
     * the $property argument.
     *
     * @param string $property Property to get. Always lowercase.
     * @return mixed
     */
    public function getProperty($property)
    {
        static $lowApiProperties;
        if (empty($lowApiProperties)) {
            $lowApiProperties = array();
            foreach ($this->_apiProperties as $apiProperty) {
                $lowApiProperties[strtolower($apiProperty)] = $apiProperty;
            }
        }
        
        $field = array_key_exists($property, $lowApiProperties) ? $lowApiProperties[$property] : $property;
        
        if ($field == 'edmLandingPage') {
            return "http://www.europeana.eu/portal/record{$this->id}.html";
        } else if (in_array($field, $this->_apiProperties)) {
            if (property_exists($this, $field)) {
                return $this->$field;
            } else {
                return null;
            }
        }
        
        throw new InvalidArgumentException(__("'%s' is an invalid special value.", $property));
    }

}
