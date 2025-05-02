<?php

namespace EveryDefaultFolder\Model;

use EveryDataStore\Helper\EveryDataStoreHelper;
use EveryDataStore\Model\RecordSet\RecordSet;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\TextField;
use SilverStripe\Security\PermissionProvider;
use SilverStripe\Core\ClassInfo;

class EveryDefaultFolder extends DataObject implements PermissionProvider 
{
    private static $table_name = 'EveryDefaultFolder';
    private static $singular_name = 'EveryDefaultFolder';
    private static $plural_name = 'EveryDefaultFolders';
    private static $db = [
        'Slug' => 'Varchar(110)',
        'Active' => 'Boolean',
        'Title' => 'Varchar(40)',
    ];
    
    private static $default_sort = "\"Title\"";
    
    private static $has_one = [
        'RecordSet' => RecordSet::class,
        'Parent' => self::class,
    ];
    
  
    
    private static $summary_fields = [
        'Active',
        'Title',
        'RecordSetTitle'
    ];
    
    private static $searchable_fields = [
        'Title' => [
            'field' => TextField::class,
            'filter' => 'PartialMatchFilter',
        ]
    ];
    
    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels(true);
        if(!empty(self::$summary_fields)){
           $labels = EveryDataStoreHelper::getNiceFieldLabels($labels, __CLASS__, self::$summary_fields);
        }
        return $labels;
    }
    
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab('Root.Main', ['Slug', 'CreatedBy', 'UpdatedBy']);
        $fields->addFieldToTab('Root.Main', HiddenField::create('Slug', 'Slug', $this->Slug), 'RecordSet');
        $fields->addFieldToTab('Root.Main', TextField::create('Title', _t(__Class__ .'.TITLE', 'Title')));
        
     
        return $fields;
    }
    
    public function onBeforeWrite() {
        parent::onBeforeWrite();
            if (!$this->owner->Slug) {
                 $this->Slug = EveryDataStoreHelper::getAvailableSlug(__CLASS__);
            }
            if (!$this->owner->CreatedByID) {
                $this->owner->CreatedByID = EveryDataStoreHelper::getMemberID();
            }
            $this->owner->UpdatedByID = EveryDataStoreHelper::getMemberID();
    }
    
    public function onAfterWrite()
    {
        parent::onAfterWrite();
    }
    
     public function onBeforeDelete()
    {
        parent::onBeforeDelete();
    }
    
    public function onAfterDelete()
    {
        parent::onAfterDelete();
    }
    
    public function RecordSetTitle() {
        return $this->RecordSet()->Title;
    }


    /**
     * 
     * @return type
     */
    public function Children() {
        return self::get()->filter(['ParentID' => $this->ID]);
    }
    
    
    /**
     * This function should return true if the current user can view an object
     * @see Permission code VIEW_CLASSSHORTNAME e.g. VIEW_MEMBER
     * @param Member $member The member whose permissions need checking. Defaults to the currently logged in user.
     * @return bool True if the the member is allowed to do the given action
     */
    public function canView($member = null) {
        return EveryDataStoreHelper::checkPermission(EveryDataStoreHelper::getNicePermissionCode("VIEW", $this));
    }

    /**
     * This function should return true if the current user can edit an object
     * @see Permission code VIEW_CLASSSHORTNAME e.g. EDIT_MEMBER
     * @param Member $member The member whose permissions need checking. Defaults to the currently logged in user.
     * @return bool True if the the member is allowed to do the given action
     */
    public function canEdit($member = null) {
        return EveryDataStoreHelper::checkPermission(EveryDataStoreHelper::getNicePermissionCode("EDIT", $this));
    }

    /**
     * This function should return true if the current user can delete an object
     * @see Permission code VIEW_CLASSSHORTNAME e.g. DELTETE_MEMBER
     * @param Member $member The member whose permissions need checking. Defaults to the currently logged in user.
     * @return bool True if the the member is allowed to do the given action
     */
    public function canDelete($member = null) {
        return EveryDataStoreHelper::checkPermission(EveryDataStoreHelper::getNicePermissionCode("DELETE", $this));
    }

    /**
     * This function should return true if the current user can create new object of this class.
     * @see Permission code VIEW_CLASSSHORTNAME e.g. CREATE_MEMBER
     * @param Member $member The member whose permissions need checking. Defaults to the currently logged in user.
     * @param array $context Context argument for canCreate()
     * @return bool True if the the member is allowed to do this action
     */
    public function canCreate($member = null, $context = []) {
        return EveryDataStoreHelper::checkPermission(EveryDataStoreHelper::getNicePermissionCode("CREATE", $this));
    }

    /**
     * Return a map of permission codes for the Dataobject and they can be mapped with Members, Groups or Roles
     * @return array 
     */
    public function providePermissions() {
        return array(
            EveryDataStoreHelper::getNicePermissionCode("CREATE", $this) => [
                'name' => _t('SilverStripe\Security\Permission.CREATE', "CREATE"),
                'category' => ClassInfo::shortname($this),
                'sort' => 1
            ],
            EveryDataStoreHelper::getNicePermissionCode("EDIT", $this) => [
                'name' => _t('SilverStripe\Security\Permission.EDIT', "EDIT"),
                'category' => ClassInfo::shortname($this),
                'sort' => 1
            ],
            EveryDataStoreHelper::getNicePermissionCode("VIEW", $this) => [
                'name' => _t('SilverStripe\Security\Permission.VIEW', "VIEW"),
                'category' => ClassInfo::shortname($this),
                'sort' => 1
            ],
            EveryDataStoreHelper::getNicePermissionCode("DELETE", $this) => [
                'name' => _t('SilverStripe\Security\Permission.DELETE', "DELETE"),
                'category' => ClassInfo::shortname($this),
                'sort' => 1
        ]);
    }
}