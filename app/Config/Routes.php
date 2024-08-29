<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// route for where the controllers are
$routes->get('/', 'PeopleController::home');
// route for home page.
$routes->get('/dashboard', 'PeopleController::home');
// route for messaging page.
$routes->get('/forums', 'ForumController::index');
// route for creating forum post.
$routes->post('/forums/createPost','ForumController::createPost');
// route for replying to forum post.
$routes->post('/forums/replyToPost/(:num)', 'ForumController::replyToPost/$1');
// route for getting to specific forums details page.
$routes->get('/forums/view/(:num)', 'ForumController::viewPost/$1');
// route for editing forum post.
$routes->post('/forums/updatePost/(:num)', 'ForumController::updatePost/$1');
// route for deleting posts from forum
$routes->delete('/forums/deletePost/(:num)', 'ForumController::deletePost/$1');
// route for personal messages page
$routes->get('/personalmessages', 'PersonalMessagesController::index');
// route for getting messages
$routes->get('/personalMessages/getMessages', 'PersonalMessagesController::getMessages');
// route for sending message to another user.
$routes->post('/personalMessages/sendMessage', 'PersonalMessagesController::sendMessage');
// route for editing a message
$routes->post('/personalMessages/editMessage', 'PersonalMessagesController::editMessage');
// route for marking messages as read
$routes->post('/personalMessages/mark-as-read', 'PersonalMessagesController::markAsRead');
// route for displaying the payroll page.
$routes->get('/timesheets', 'TimesheetsController::index');
// route for displaying the accountant payroll page
$routes->get('/accountantpayroll', 'PayrollController::index');
// route for searching accountant payroll view page.
$routes->get('/search_payroll', 'PayrollController::search');
// route for displaying the calendar.
$routes->get('/calendar', 'CalendarController::index');
// route for inserting calendar data into database.
$routes->post('/calendar/create', 'CalendarController::create');
// route for submitting timesheet to the database.
$routes->post('/submit-timesheets', 'TimesheetsController::submit');
// route for getting the timesheet information.
$routes->get('/timesheets/get/(:num)', 'TimesheetsController::getTimesheet/$1');
// route for showing the accountant specific user timesheets.
$routes->get('/timesheets/user/(:num)', 'TimesheetsController::viewTimesheets/$1');
// route for displaying the edit timesheet page.
$routes->get('/timesheets/edit/(:num)', 'TimesheetsController::editTimesheet/$1');
// route for executing the update of the timesheet record.
$routes->post('/timesheets/update', 'TimesheetsController::updateTimesheet');
// route for deleting timesheets out of database.
$routes->get('/timesheets/delete/(:num)', 'TimesheetsController::deleteTimesheet/$1');
// route for viewing specific timesheet.
$routes->get('/timesheets/view/(:num)', 'TimesheetsController::viewTimesheet/$1');
// route for exporting multiple timesheets at once.
$routes->post('/timesheets/export_multiple', 'TimesheetsController::exportMultipleTimesheets');
// route for displaying projects page.
$routes->get('/projects', 'ProjectsController::index');
// route for handling searching of projects.
$routes->get('/projects/search', 'ProjectsController::search');
// route for handling the filter function.
$routes->get('/projects/filter', 'ProjectsController::filter');
// route for showing the people page.
$routes->get('/people', 'PeopleController::index');
// route for displaying more detials of project in the projectdetails page.
$routes->get('projects/details/(:num)', 'ProjectsController::projectDetails/$1');
// route for getting projects associated with a specific user
$routes->get('projects/getProjectsForUser/(:num)', 'ProjectsController::getProjectsForUser/$1');
// route for unassociating users from a specific project(s)
$routes->post('/projects/unassign', 'ProjectsController::unassignProjectsFromUser');
// route to add project view.
$routes->get('/addProject', 'ProjectsController::addProjectsView');
// route to add projects to the database.
$routes->post('/projects/add', 'ProjectsController::add');
// route for adding updates to a project.
$routes->post('/projects/add_update', 'ProjectsController::addUpdate');
// route for editing updates to a project.
$routes->post('/projects/edit_update', 'ProjectsController::editUpdate');
// route for deleting updates from a project.
$routes->delete('projects/delete_update/(:num)', 'ProjectsController::deleteUpdate/$1');
// route for posting messages to messages page.
$routes->post('/messages/create', 'MessageController::store');
// route for deleting messages.
$routes->delete('/messages/delete/(:num)', 'MessageController::delete/$1');
// route for updating messages.
$routes->post('/messages/update', 'MessageController::update');
// route for project 3D test.
$routes->get('/projectsTest', 'ProjectsController::projectsTest');
// route for updating calendar event information.
$routes->post('/calendar/updateEvent', 'CalendarController::updateEvent');
// routes for deleting events from the calendar.
$routes->post('/calendar/deleteEvent', 'CalendarController::deleteEvent');
// route for displaying the assign users page.
$routes->get('/assignUsers', 'ProjectsController::assignUsersView');
// route for assigning projects to a user.
$routes->post('/projects/assign', 'ProjectsController::assignProjectsToUser');
// route for unassigning users from a project.
$routes->get('/unassignUsers', 'ProjectsController::unassignUsersView');
// route for displaying the page showing the user their current work.
$routes->get('/myWork', 'ProjectsController::myWork');
// route for searching the mywork table.
$routes->get('/myWork/search', 'ProjectsController::search');
// route for pulling up the my profile page.
$routes->get('/myprofile/(:num)', 'PeopleController::myProfileView/$1');
// route for displaying the form to update the user's profile.
$routes->get('/update_profile_view/(:num)', 'PeopleController::updateProfileView/$1');
// route for performing the update on user's profile.
$routes->post('/update_profile', 'PeopleController::updateProfile');
// route for displaying the settings page.
$routes->get('/settings', 'PeopleController::settingsView');
// route for displaying the update page.
$routes->get('/settings/profile', 'PeopleController::updateProfileView/$1');
// route for displaying the reports page.
$routes->get('/reports', 'PeopleController::reportsView');
// route for displaying the activity page.
$routes->get('/activity', 'PeopleController::activityView');
// route for displaying the change password page.
$routes->get('/user/change_password', 'UserController::changePasswordView');
// route for viewing timesheets by the week
$routes->get('/accountantpayroll/week/(:segment)', 'PayrollController::viewWeek/$1');
// route for actually changing the user's password in the database.
$routes->post('/change_password', 'UserController::changePassword');
service('auth')->routes($routes, ['register' => false]);
// Custom route for the register action.
$routes->match(['get', 'post'], 'doRegister', 'RegisterController::register');
// test email route
$routes->get('/send-test-email', 'EmailController::sendTestEmail');
$routes->get('timesheets/export/(:num)', 'TimesheetsController::exportTimesheet/$1');