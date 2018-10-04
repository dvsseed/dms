<?php

namespace App\Http\Controllers;

use Acme\PostsRepo;
use App\Category;
use App\Post;
use Illuminate\Http\Request;

use App\Http\Requests;

class PagesController extends Controller
{
    /**
     * Display the homepage.
     */
    public function home() {
        // Laravel 5 Facade for Goutte
        // $crawler = Goutte::request('GET', 'https://duckduckgo.com/html/?q=Laravel');
        // $crawler->filter('.result__title .result__a')->each(function ($node) {
        //    dump($node->text());
        // });
        $posts = PostsRepo::getPosts(10, 'owner');
        return view(config('theme.default.pages').'.index')->withPosts($posts);
    }

    /**
     * Display the specified post.
     *
     * @param \App\Post $post
     */
    public function post(Post $post){
        return view(config('theme.default.pages').'.post')->withPost($post);
    }

    /**
     * Display the posts of specified category.
     *
     * @param \App\Category $category
     */
    public function category(Category $category){
        $posts = PostsRepo::getCategoryPosts($category, 10, 'owner');
        return view(config('theme.default.pages').'.category')->withPosts($posts);
    }
}
