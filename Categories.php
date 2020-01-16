<?php
require_once('config.php');

class Categories {
    /**
     * Categories
     *
     * @var array
     */
    public $categories = [];

    /**
     * Category tree
     *
     * @var array
     */
    public $categoriesTree = [];

    /**
     * Categories where parents are not found
     *
     * @var array
     */
    public $notFoundParents = [];

    private $db = null;

    public function __construct() {
        $this->db = new PDO('mysql:dbname='.DB_DATABASE.';host='.DB_HOSTNAME.';charset=UTF8', DB_USERNAME, DB_PASSWORD);
        // Check for errors
        if(mysqli_connect_errno()){
            echo mysqli_connect_error();
            die();
        }
        $this->categories = $this->getCategories();
        $this->categoriesTree = $this->getTree();
    }

    /**
     * Get Categories list
     *
     * @return array
     */
    private function getCategories(){
        $result = [];
        $row = $this->db->query('SELECT * FROM `categories`');
        while ($category = $row->fetch((PDO::FETCH_ASSOC))) {
            $result[$category['id']] = $category;
        }
        return $result;
    }

    /**
     * Get all categories as tree
     *
     * @return array
     */
    private function getTree() {
        $result = [] ;
        $categories = $this->categories;
        foreach($categories as $id => &$category) {
            if(!$category['parent_id']){
                $result[$id] = &$category ;
            }else{
                if($this->checkParent($category))
                    $categories[$category['parent_id']]['child'][$id] = &$category;
            }
        }
        return $result;
    }

    /**
     * Parent category check
     *
     * @array $category
     * @return bool
     */
    private function checkParent($category)
    {
        if(!$this->categories[$category['parent_id']]) {
            //if not found parent category, add this id to notFoundParents
            $this->notFoundParents[] = $category['id'];
            return false;
        }
        else
            return true;
    }

}
