<?php  return array (
  '/' => 
  array (
    'uri' => '/',
    'method' => 'GET',
    'controller' => 'App\\Http\\Controllers\\HomeController',
    'action' => 'index',
    'parameters' => 
    array (
    ),
    'name' => 'homePage',
  ),
  '/about' => 
  array (
    'uri' => '/about',
    'method' => 'GET',
    'controller' => 'App\\Http\\Controllers\\HomeController',
    'action' => 'about',
    'parameters' => 
    array (
    ),
    'name' => 'aboutPage',
  ),
  '/post' => 
  array (
    'uri' => '/post',
    'method' => 'POST',
    'controller' => 'App\\Http\\Controllers\\HomeController',
    'action' => 'post',
    'parameters' => 
    array (
    ),
    'name' => 'post',
  ),
);