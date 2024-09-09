<?php

namespace EveryDefaultFolder\Extension;

use SilverStripe\ORM\DataExtension;
use EveryDefaultFolder\Model\EveryDefaultFolder;

/** EveryDataStore/EveryDefaultFolder v1.0
 * 
 * This class sets up default folder model relations
 * 
 */
class EveryDefaultFolderRecordExtension extends DataExtension {
    
    private static $db = [];
    
    private static $has_many = [
        'DefaultFolders' => EveryDefaultFolder::class
    ];
    
}