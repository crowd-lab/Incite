
<?php

class Table_InciteTagcategory extends Omeka_Db_Table
{
    public function findAllCategoriesWithSubcategories()
    {
        $db = get_db();
        $select = new Omeka_Db_Select;
        $select->from(array('cat' => $db->InciteTagcategory), array('catName' => 'name'));
        $select->joinInner(array('subs' => $db->InciteTagsubcategory), 'cat.id = subs.category_id');

        $results = $this->fetchObjects($select);
        $cats = array();
        foreach ((array) $results as $result) {
            if (!isset($cats[$result->category_id])) {
                $cats[$result->category_id] = array('name' => $result->catName, 'subcategory' => array());
            } 
            $cats[$result->category_id]['subcategory'][] = array("subcategory_id" => $result->id, "subcategory" => $result->name);
        }
        return $cats;
    }
    public function getCategoryIdToNameMap()
    {
        $select = $this->getSelect();
        $results = $this->fetchObjects($select);
        $idToName = array();
        foreach ((array) $results as $result) {
            $idToName[$result->id] = $result->name;
        }
        return $idToName;
    }
    public function getCategoryNameToIdMap()
    {
        $select = $this->getSelect();
        $results = $this->fetchObjects($select);
        $nameToId = array();
        foreach ((array) $results as $result) {
            $nameToId[$result->name] = $result->id;
        }
        return $nameToId;
    }
}

?>
