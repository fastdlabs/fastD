<?php 

Routes::get('base', '/base', \WelcomeBundle\Controllers\Index::class.'@indexAction');

Routes::post('base_post', '/base', \WelcomeBundle\Controllers\Index::class.'@postAction');

Routes::get('dynamic', '/{name}', \WelcomeBundle\Controllers\Index::class.'@dynamicAction');

Routes::group('/user', function () {
    Routes::get('user_profile', '/profile', \WelcomeBundle\Controllers\Index::class.'@profileAction');
});