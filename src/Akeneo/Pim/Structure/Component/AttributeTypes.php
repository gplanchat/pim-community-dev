<?php

namespace Akeneo\Pim\Structure\Component;

/**
 * Attribute types dictionary
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
final class AttributeTypes
{
    const string BOOLEAN = 'pim_catalog_boolean';
    const string DATE = 'pim_catalog_date';
    const string FILE = 'pim_catalog_file';
    const string IDENTIFIER = 'pim_catalog_identifier';
    const string IMAGE = 'pim_catalog_image';
    const string METRIC = 'pim_catalog_metric';
    const string NUMBER = 'pim_catalog_number';
    const string OPTION_MULTI_SELECT = 'pim_catalog_multiselect';
    const string OPTION_SIMPLE_SELECT = 'pim_catalog_simpleselect';
    const string PRICE_COLLECTION = 'pim_catalog_price_collection';
    const string TEXTAREA = 'pim_catalog_textarea';
    const string TEXT = 'pim_catalog_text';
    const string REFERENCE_DATA_MULTI_SELECT = 'pim_reference_data_multiselect';
    const string REFERENCE_DATA_SIMPLE_SELECT = 'pim_reference_data_simpleselect';
    const string REFERENCE_ENTITY_SIMPLE_SELECT = 'akeneo_reference_entity';
    const string REFERENCE_ENTITY_COLLECTION = 'akeneo_reference_entity_collection';
    const string ASSET_COLLECTION = 'pim_catalog_asset_collection';
    const string LEGACY_ASSET_COLLECTION = 'pim_assets_collection';
    const string TABLE = 'pim_catalog_table';

    const string BACKEND_TYPE_BOOLEAN = 'boolean';
    const string BACKEND_TYPE_COLLECTION = 'collections';
    const string BACKEND_TYPE_DATE = 'date';
    const string BACKEND_TYPE_DATETIME = 'datetime';
    const string BACKEND_TYPE_DECIMAL = 'decimal';
    const string BACKEND_TYPE_ENTITY = 'entity';
    const string BACKEND_TYPE_INTEGER = 'integer';
    const string BACKEND_TYPE_MEDIA = 'media';
    const string BACKEND_TYPE_METRIC = 'metric';
    const string BACKEND_TYPE_OPTION = 'option';
    const string BACKEND_TYPE_OPTIONS = 'options';
    const string BACKEND_TYPE_PRICE = 'prices';
    const string BACKEND_TYPE_REF_DATA_OPTION = 'reference_data_option';
    const string BACKEND_TYPE_REF_DATA_OPTIONS = 'reference_data_options';
    const string BACKEND_TYPE_TEXTAREA = 'textarea';
    const string BACKEND_TYPE_TEXT = 'text';
    const string BACKEND_TYPE_TABLE = 'table';
}
