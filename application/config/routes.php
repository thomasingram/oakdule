<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

// $route['default_controller'] = 'pages/view';
$route['default_controller'] = 'lst/index';
$route['404_override'] = '';
// $route['habit/(:any)/comment/edit/(:num)'] = 'habit/comment_edit/$2/$1';
// $route['habit/(:any)/comment/delete/(:num)'] = 'habit/comment_delete/$2/$1';
// $route['habit/(:any)'] = 'habit/view/$1';
$route['list'] = 'lst';
$route['list/(:num)'] = 'lst/index//$1';
$route['list/add'] = 'lst/add';
// $route['list/add/(:num)'] = 'lst/add/$1';
// $route['list/archive'] = 'lst/archive';
$route['list/delete/(:num)'] = 'lst/delete/$1';
// $route['list/notes/(:num)'] = 'lst/note_index/$1';
// $route['list/notes/(:num)/edit/(:num)'] = 'lst/note_edit/$2/$1';
// $route['list/notes/(:num)/delete/(:num)'] = 'lst/note_delete/$2/$1';
// $route['list/(:any)/archive'] = 'lst/archive/$1';
// $route['list/(:any)'] = 'lst/index/$1';
// $route['list/(:any)/(:num)'] = 'lst/index/$1/$2';
$route['privacy'] = 'pages/view/privacy';
// $route['(:any)'] = 'habit/view/$1';


/* End of file routes.php */
/* Location: ./application/config/routes.php */