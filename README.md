# Europeana plugin for Omeka

This plugin provides a means to search the Europeana REST API from with Omeka
and display the results with links to the full record on Europeana's website.

## Requirements

* Omeka 2.2+
* PHP 5.2+
* A Europeana API key, available from: http://labs.europeana.eu/api/registration/

## Installation

1. Download the plugin
2. Extract to the plugins directory of your Omeka installation
3. Enable the plugin at http://www.example.com/admin/plugins

## Configuration

1. Configure the plugin at http://www.example.com/admin/plugins/config?name=Europeana
  to set your API key, and other optional settings.
2. It is *highly recommended* to configure caching of results from the API to
  avoid exceeding your API key's usage limit, and to improve responsiveness of
  your Omeka site for common queries.
  
  To the configuration file `application/config/application.ini` add, for your
  environment, the following settings:
  
  ```INI
  resources.cachemanager.europeana.frontend.name = Core
  resources.cachemanager.europeana.frontend.options.automatic_serialization = true
  resources.cachemanager.europeana.frontend.options.lifetime = 86400
  resources.cachemanager.europeana.backend.name = File
  resources.cachemanager.europeana.backend.options.file_name_prefix = omeka_europeana_cache
  ```
  
  This will cache search results to the file system for one day. For full
  documentation of the settings available, see http://framework.zend.com/manual/1.12/en/zend.cache.html

## Usage

Visit http://www.example.com/europeana and enter your query into the search
form, adhering to the "Europeana REST API Query Syntax":http://labs.europeana.eu/api/query/

## Change log

See CHANGELOG.md
