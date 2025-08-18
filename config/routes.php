<?php
/**
 * Configuration des routes de l'application ChatMe
 * Définit toutes les routes disponibles et leurs contrôleurs associés
 */

// Routes publiques (accessibles sans authentification)
$publicRoutes = [
    '' => 'HomeController@index',
    'home' => 'HomeController@index',
    'login' => 'AuthController@login',
    'register' => 'AuthControllers@register',
    'about' => 'HomeController@about',
    'contact' => 'HomeController@contact',
    'terms' => 'HomeController@terms',
    'privacy' => 'HomeController@privacy'
];

// Routes protégées (nécessitent une authentification)
$protectedRoutes = [
    'profile' => 'UserController@profile',
    'profile/edit' => 'UserController@edit',
    'profile/update' => 'UserController@update',
    'feed' => 'PostController@feed',
    'post/create' => 'PostController@create',
    'post/store' => 'PostController@store',
    'post/([0-9]+)' => 'PostController@show',
    'post/([0-9]+)/edit' => 'PostController@edit',
    'post/([0-9]+)/update' => 'PostController@update',
    'post/([0-9]+)/delete' => 'PostController@delete',
    'post/([0-9]+)/like' => 'PostController@like',
    'post/([0-9]+)/unlike' => 'PostController@unlike',
    'post/([0-9]+)/comment' => 'CommentController@store',
    'comment/([0-9]+)/delete' => 'CommentController@delete',
    'messages' => 'MessageController@index',
    'messages/([0-9]+)' => 'MessageController@show',
    'messages/send' => 'MessageController@send',
    'search' => 'SearchController@index',
    'search/users' => 'SearchController@users',
    'search/posts' => 'SearchController@posts',
    'user/([0-9]+)' => 'UserController@show',
    'user/([0-9]+)/follow' => 'UserController@follow',
    'user/([0-9]+)/unfollow' => 'UserController@unfollow',
    'user/([0-9]+)/block' => 'UserController@block',
    'user/([0-9]+)/unblock' => 'UserController@unblock',
    'story/create' => 'StoryController@create',
    'story/([0-9]+)' => 'StoryController@show',
    'story/([0-9]+)/view' => 'StoryController@view',
    'notifications' => 'NotificationController@index',
    'notifications/mark-read' => 'NotificationController@markRead',
    'settings' => 'SettingsController@index',
    'settings/update' => 'SettingsController@update',
    'settings/password' => 'SettingsController@password',
    'logout' => 'AuthController@logout'
];

// Routes d'administration (nécessitent des droits admin)
$adminRoutes = [
    'admin' => 'AdminController@dashboard',
    'admin/users' => 'AdminController@users',
    'admin/posts' => 'AdminController@posts',
    'admin/reports' => 'AdminController@reports',
    'admin/user/([0-9]+)/ban' => 'AdminController@banUser',
    'admin/user/([0-9]+)/unban' => 'AdminController@unbanUser',
    'admin/post/([0-9]+)/delete' => 'AdminController@deletePost',
    'admin/report/([0-9]+)/resolve' => 'AdminController@resolveReport'
];

// Fusion de toutes les routes
$allRoutes = array_merge($publicRoutes, $protectedRoutes, $adminRoutes);

// Export des routes pour utilisation dans l'application
return $allRoutes;
?>
