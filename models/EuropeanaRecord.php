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
        if (property_exists($this, $property)) {
            return $this->$property;
        } else {
            foreach ($this as $key => $value) {
                if (strtolower($key) == $property) {
                    return $this->getProperty($key);
                }
            }
        }
        throw new InvalidArgumentException(__("'%s' is an invalid special value.", $property));
    }

}
