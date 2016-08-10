<?php

class ApplicationController
{
	public static function create(\Slim\Http\Request $req, $res, $container, $user, $application)
	{
		$params = $req->getParams();
		$requireParams = ['name', 'description', 'permissions'];

		if (!hasRequireParams($params, $requireParams))
			return withFailure($res, 'required parameters are missing', $requireParams);

		try
		{
			$applicationFactory = new ApplicationFactory($container['database'], $container['config'], new \Utility\Regex());
			$applicationModel = new ApplicationModel($applicationFactory);
			$application = $applicationModel->create($user['id'], $params['name'], $params['description'], $params['permissions']);
		}
		catch(\Utility\ApiException $e)
		{
			return withFailure($res, $e->getMessage(), $e->getData(), $e->getStatus());
		}

		return withSuccess($res, ['application' => $application]);
	}

	public static function show(\Slim\Http\Request $req, $res, $container, $user, $application)
	{
		$params = $req->getParams();
		$requireParams = ['application-id'];

		if (!hasRequireParams($params, $requireParams))
			return withFailure($res, 'required parameters are missing', $requireParams);

		try
		{
			$applicationFactory = new ApplicationFactory($container['database'], $container['config'], new \Utility\Regex());
			$applicationModel = new ApplicationModel($applicationFactory);
			$application = $applicationModel->get($params['application-id']);
		}
		catch(\Utility\ApiException $e)
		{
			return withFailure($res, $e->getMessage(), $e->getData(), $e->getStatus());
		}

		return withSuccess($res, ['application' => $application]);
	}

	public static function applicationKeyGenerate(\Slim\Http\Request $req, $res, $container, $user, $application)
	{
		$params = $req->getParams();
		$requireParams = ['application-id'];

		if (!hasRequireParams($params, $requireParams))
			return withFailure($res, 'required parameters are missing', $requireParams);

		try
		{
			$applicationFactory = new ApplicationFactory($container['database'], $container['config'], new \Utility\Regex());
			$applicationModel = new ApplicationModel($applicationFactory);
			$applicationKey = $applicationModel->keyGenerate($params['application-id'], $user['id']);
		}
		catch(\Utility\ApiException $e)
		{
			return withFailure($res, $e->getMessage(), $e->getData(), $e->getStatus());
		}

		return withSuccess($res, ['application-key'=>$applicationKey]);
	}

	public static function applicationKeyShow(\Slim\Http\Request $req, $res, $container, $user, $application)
	{
		$params = $req->getParams();
		$requireParams = ['application-id'];

		if (!hasRequireParams($params, $requireParams))
			return withFailure($res, 'required parameters are missing', $requireParams);

		try
		{
			$applicationFactory = new ApplicationFactory($container['database'], $container['config'], new \Utility\Regex());
			$applicationModel = new ApplicationModel($applicationFactory);
			$applicationKey = $applicationModel->keyGet($params['application-id'], $user['id']);
		}
		catch(\Utility\ApiException $e)
		{
			return withFailure($res, $e->getMessage(), $e->getData(), $e->getStatus());
		}

		return withSuccess($res, ['application-key'=>$applicationKey]);
	}
}
