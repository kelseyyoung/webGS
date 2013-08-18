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

//Assignments
$route['assignments/create'] = 'assignments/create';
$route['assignments/results/(:num)'] = 'assignments/results/$1';
$route['assignments/view_submissions/(:num)'] = 'assignments/view_submissions/$1';
$route['assignments/edit/(:num)/(:num)'] = 'assignments/edit/$1/$2';
$route['assignments/view_grades/(:num)/(:num)'] = 'assignments/view_grades/$1/$2';
$route['assignments/download_grades'] = 'assignments/download_grades';
$route['assignments/change_grade_instructors'] = 'assignments/change_grade_instructors';
//Classes
$route['classes/view/(:num)'] = 'classes/view/$1';
$route['classes/student_view/(:num)'] = 'classes/student_view/$1';
$route['classes/submit_assignment/(:num)'] = 'classes/submit_assignment/$1';
$route['classes/add_student/(:num)'] = 'classes/add_student/$1';
$route['classes/add_instructor/(:num)'] = 'classes/add_instructor/$1';
$route['classes/remove_student/(:num)/(:num)'] = 'classes/remove_student/$1/$2';
$route['classes/remove_instructor/(:num)/(:num)'] = 'classes/remove_instructor/$1/$2';
$route['classes/create'] = 'classes/create';
//Instructors
$route['instructors/view'] = 'instructors/view';
$route['instructors/view_grades/(:num)/(:num)'] = 'instructors/view_grades/$1/$2';
//Students
$route['students/view'] = 'students/view';
//Unauthorized
$route['unauthorized'] = 'unauthorized';
//Login
$route['login'] = 'login';
//Logout
$route['logout'] = 'logout';
//Default = home page
$route['default_controller'] = 'home/index';
//$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */
