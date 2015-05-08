<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 05-05-15
 * Time: 13:23
 */

namespace Http\Models\Blog;


use Http\Models\Blog;

class Post extends Blog {

    public $posts = array();
    private $keys = array();

    /**
     * Create a post
     * @param $slug
     * @param $title
     * @param $content
     * @param $created_at
     * @param string $modified_at
     * @param $owner
     */
    public function newPost($slug, $title, $content, $created_at, $modified_at = '', $owner)
    {
        $this->posts[$slug] = compact('slug' ,'title', 'content', 'created_at', 'modified_at', 'owner');
        $this->keys = array_keys($this->posts[$slug]);
    }

    /**
     * Return a post
     * @param $slug
     * @return mixed
     */
    public function get($slug)
    {
        return $this->posts[$slug];
    }

    public function available()
    {
        return $this->posts;
    }

    /**
     * Change a post
     * @param $slug
     * @param array $content
     */
    public function change($slug, array $content = array())
    {
        foreach ($content as $key => $value) {
            if(in_array($key, $this->keys))
            {
                $this->posts[$slug] = array(
                    $key => $value
                );
            }
        }

    }

}