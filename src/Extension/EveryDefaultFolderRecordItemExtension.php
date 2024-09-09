<?php
namespace EveryDefaultFolder\Extension;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Assets\Folder;

/** EveryDataStore/EveryDefaultFolder v1.0
 * 
 * This class configures procedures for creating a default folder of a RecordSetItem 
 * 
 */

class EveryDefaultFolderRecordSetItemExtension extends DataExtension {
    
    public function onBeforeWrite() {
        parent::onBeforeWrite();
    }
    
    public function onAfterWrite() {
        parent::onAfterWrite();
        if ($this->owner->RecordSet()->DefaultFolders()) {
            self::setDefaultFolders($this->owner->RecordSet()->DefaultFolders()->filter(['ParentID'=> 0]), $this->owner->FolderID);
        }
    }
    
    /**
     * Creates default folder of a RecordSetItem
     * @param DataObject $DefaultFolders
     * @param Integer $FolderID
     */
    private static function setDefaultFolders($DefaultFolders, $FolderID) {
        $Parent = $FolderID ? Folder::get()->byID($FolderID) : null;
        if ($Parent) {
            foreach ($DefaultFolders as $Folder) {
                if ($Folder->Active == 1) {
                    if ($Folder->Children()->Count() > 0) {
                        $NewFolder = Folder::find_or_make($Parent->Filename . '/' . $Folder->Title);
                        self::setDefaultFolders($Folder->Children(), $NewFolder->ID);
                    } else {
                        Folder::find_or_make($Parent->Filename . '/' . $Folder->Title);
                    }
                }
            }
        }
    }

}


