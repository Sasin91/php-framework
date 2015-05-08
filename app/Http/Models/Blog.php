<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 05-05-15
 * Time: 13:48
 */

namespace Http\Models;

use System\Exception\AccessDeniedException;
use System\Reflector;

class Blog extends BaseModel {


    /**
     * Define which database to use.
     * @var string
     */
    protected $database = 'Blog';
    
    private $permittedTables = array('Categories', 'Posts');

    protected $categories;

    protected $posts;
    
    /**
     * Initiates the Model and sets the permitted columns in database.
     */
    public function __construct()
    {
        parent::__construct(
            'label', 'description', // Categories
            'slug', 'title', 'content', 'created_at', 'modified_at', 'owner', 'fk_category_id' // Posts
        );
        $this->categories = Reflector::reflect('Http\Models\Blog\Category');
        $this->posts = Reflector::reflect('Http\Models\Blog\Post');
        $this->populate();

    }

    private function populate()
    {
       $query[] = $this->db->singleQuery('SELECT * FROM Categories INNER JOIN Posts ON Posts.fk_category_id = Categories.category_id');

        foreach ($query[0] as $column) {
                    $this->categories->newCategory($column->label, $column->description);
                    $this->posts->newPost($column->slug, $column->title, $column->content, $column->created_at, $column->modified_at, $column->owner);
        }

    }

    /**
     * Returns either all or specified.
     * @param string $columns
     * @param array $clauses
     * @return mixed
     */
    public function get($table, $labelOrSlug = '*')
    {
        if(in_array($table, $this->permittedTables)) {
            $method = strtolower($table);
            if($labelOrSlug === '*')
            {
                return $this->$method->available();
            }
            return $this->$method->get($labelOrSlug);
            }
        throw new AccessDeniedException($table.' is NOT a part of $permittedTables.');
    }
    
    private $columns;
    private $clauses = array();
    private $table;
    /**
     * Used for making modification to a blog post without posting to database.
     * @param $table
     * @param string $columns
     * @param array $clauses
     */
    public function set($table, $columns = '*', array $clauses = array())
    {
        $this->table = $table;
        $this->columns = $columns;
        $this->clauses = $clauses;
        
    }

    /**
     * Paste the edited post onto the database.
     * @return mixed
     */
    public function commit()
    {
        return $this->update($this->columns, $this->table)->where($this->clauses)->execute();
    }

}