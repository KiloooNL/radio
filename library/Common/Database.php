<?php

/**
 *
 * This class instantiates an instance of the correct Zend_Db_Adapter
 * as specified in the configFile. The database object is exposed
 * through a Singleton Pattern.
 *
 * See http://framework.zend.com for more information on Zend_Db
 *
 * @author SpacialDev
 * @copyright 2010
 */
class Database
{
	/**
	 *
	 * There can only be one database instance at a given time.
	 *
	 * @var Zend_Db_Adapter $onlyInstance
	 */
	public static $onlyInstance = null;

	/**
	 *
	 * This static method should initially be called with a configFile as parameter
	 * When the method is called a second time, no configFiles is necessary as the
	 * database object was created the first time, and will be returned.
	 *
	 * @param $configFile
	 * @return Zend_Db_Adapter
	 */
	public static function getInstance($configFile = null)
	{
		if (is_null(self::$onlyInstance))
		{
			if($configFile == null)
			{
				throw new Exception('Config file not provided to boostrap database.');
			}
			self::$onlyInstance = self::InitFromXMLConfig($configFile);
		}

		return self::$onlyInstance;
	}

	/**
	 *
	 * Instantiate the database from the specified configFile
	 * and return the appropriate Zend_Db_Adapter after successfully
	 * connected to the database.
	 *
	 * @param $configFile
	 * @return Zend_Db_Adapter_Abstract
	 */
	private static function InitFromXMLConfig($configFile)
	{
		include_once('form.php');
		include_once('xml.php');

		$dbdata = File2Str($configFile);

		//Remove PHP comments
		$dbdata = str_replace("<?php/*","",$dbdata);
		$dbdata = str_replace("<?/*","",$dbdata); //Legacy tag
		$dbdata = str_replace("*/?>","",$dbdata);
		$dbdata = trim($dbdata);

		$dbdata = XML2Arr($dbdata);
		$dbdata = $dbdata["CONFIG"]["DATABASE"];
		EmptyNodeCleanUp($dbdata);

		$adapterNamespace = 'Zend_Db_Adapter';
		switch($dbdata["DRIVER"])
		{
			case "FIREBIRD"   : $adapter = 'firebird';
								$adapterNamespace = 'ZendX_Db_Adapter'; break;
			case "MSSQL"      :
			case "ADO"        : $adapter = 'Pdo_Mssql'; break;
			case "MYSQL"      : $adapter = 'Mysqli'; break;
			case "POSTGRESQL" :
			case "POSTGRES"   : $adapter = 'Pdo_Pgsql'; break;
			default           : throw new Exception('Error: Unsupported driver: ' . $dbdata['DRIVER'] . '<br />' .
													'SAM currently supports: <br />' .
													'Firebird<br />' .
													'ADO<br />' .
													'MSSQL<br />');
		}

		require_once('Zend/Loader/Autoloader.php');
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('database_');

		$options = array(
			Zend_Db::CASE_FOLDING => Zend_Db::CASE_UPPER,
			Zend_Db::AUTO_QUOTE_IDENTIFIERS => false,
		);

		$dbConfig = array(
					'adapter' => $adapter,
					'params' => array (
							'host' => $dbdata['HOST'],
							'port' => $dbdata['PORT'],
							'username' => $dbdata['USERNAME'],
							'password' => $dbdata['PASSWORD'],
							'dbname' => $dbdata['DATABASE'],
							'adapterNamespace' => $adapterNamespace,
							'options' => $options,
					),
		);

		return Zend_Db::factory($dbConfig['adapter'],$dbConfig['params']);
	}
}