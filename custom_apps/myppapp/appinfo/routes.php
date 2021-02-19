<?php
/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\MyPPApp\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
  'resources' => [
    'customer' => ['url' => '/customers'],
  ],
  'routes' => [
    ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
		['name' => 'customer#timesheet', 'url' => '/timesheet', 'verb' => 'GET'],
		['name' => 'customer#projectDetails', 'url' => '/project', 'verb' => 'GET'],
		['name' => 'customer#wikipageDetails', 'url' => '/wikipage', 'verb' => 'GET'],

		['name' => 'wiki#wikipageDetails', 'url' => '/wiki/page', 'verb' => 'GET'],
		['name' => 'wiki#updateWikipage', 'url' => '/wiki/page', 'verb' => 'POST'],

		['name' => 'orgMode#getTasks', 'url' => '/om/tasks', 'verb' => 'GET'],
		['name' => 'task#taskList', 'url' => '/tasks', 'verb' => 'GET'],

		['name' => 'page#do_echo', 'url' => '/echo', 'verb' => 'POST'],
    ['name' => 'api#dummy', 'url' => '/dummy', 'verb' => 'GET'],

    ['name' => 'orgMode#createTimesheet', 'url' => '/omts', 'verb' => 'POST'],
		['name' => 'orgMode#getNodes', 'url' => '/om/nodes', 'verb' => 'GET'],
		['name' => 'orgMode#getLogbook', 'url' => '/om/logbook', 'verb' => 'GET'],

  ]
];
