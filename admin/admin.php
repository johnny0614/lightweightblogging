<?php

// This is the composer autoloader. Used by
// the markdown parser, RSS feed builder and dispatch framework.
require '../vendor/autoload.php';

//import utility functions
require '../utility/utility.php';

// Load the configuration file
config('source', '../config.ini');

get('/admin/admin.php', function() {

            $action = from($_GET, 'action');

            if (strcasecmp($action, "edit") == 0) {

                $post_path = find_post_path(from($_GET, 'year'), from($_GET, 'month'), from($_GET, 'name'));
                $post = find_post_for_edit(from($_GET, 'year'), from($_GET, 'month'), from($_GET, 'name'));
                render('post_edit', array(
                    'title' => $post->title . ' ⋅ ' . config('blog.title'),
                    'p' => $post,
                    'path' => $post_path
                ));
                die();
            }


            $page = from($_GET, 'page');
            $page = $page ? (int) $page : 1;

            $posts = get_posts($page);

            if (empty($posts) || $page < 1) {
                // a non-existing page
                not_found();
            }

            render('admin', array(
                'page' => $page,
                'posts' => $posts,
                'has_pagination' => has_pagination($page)
            ));
        });

get('/admin', function() {

            $action = from($_GET, 'action');

            if (strcasecmp($action, "edit") == 0) {

                $post_path = find_post_path(from($_GET, 'year'), from($_GET, 'month'), from($_GET, 'name'));
                $post = find_post_for_edit(from($_GET, 'year'), from($_GET, 'month'), from($_GET, 'name'));
                render('post_edit', array(
                    'title' => $post->title . ' ⋅ ' . config('blog.title'),
                    'p' => $post,
                    'path' => $post_path
                ));
                die();
            }


            $page = from($_GET, 'page');
            $page = $page ? (int) $page : 1;

            $posts = get_posts($page);

            if (empty($posts) || $page < 1) {
                // a non-existing page
                not_found();
            }

            render('admin', array(
                'page' => $page,
                'posts' => $posts,
                'has_pagination' => has_pagination($page)
            ));
        });

post('/admin/admin.php', function() {

            //ajax interaction
            $action = from($_POST, 'action');

            if (strcasecmp($action, 'add') == 0) {
                $year = date('Y');
                $month = date('m');
                $name = from($_POST, 'title');
                $body = from($_POST, 'body');

                if (add_post($name, $body)) {
                    echo json_encode(find_post($year, $month, $name, $name));
                    die();
                } else {
                    
                }
            } else if (strcasecmp($action, 'delete') == 0) {

                $name = from($_POST, 'name');
                $title = from($_POST, 'title');
                $year = from($_POST, 'year');
                $month = from($_POST, 'month');

                $path = find_post_path($year, $month, $name, $title);

                if ($path) {
                    if (delete_post($path)) {
                        echo '';
                        die();
                    } else {
                        //failure handler
                    }
                } else {
                    //failure handler
                }
            } else if (strcasecmp($action, 'edit') == 0) {
                $path = from($_POST, 'path');
                $title = from($_POST, 'title');
                $body = from($_POST, 'body');
                $date = from($_POST, 'date');

                if (file_exists($path)) {
                    if (edit_post($path, $title, $body, $date)) {
                        echo "";
                        die();
                    }
                } else {
                    echo 'fail';
                }
            } else {
                not_found();
            }
        });
        
// If we get here, it means that
// nothing has been matched above

get('.*', function() {
            echo '<html><body><p>admin 404 not found !!!</p></body></html>';
        });

dispatch();
?>