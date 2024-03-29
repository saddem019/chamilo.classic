<?php

namespace Chash\Helpers;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Yaml\Parser;

class ConfigurationHelper extends Helper
{
    protected $configuration;

    public function __construct()
    {

    }

    /**
     * Get chamilo versions
     * @return array
     */
    public function chamiloVersions()
    {
        $versionList = array(
            '1.8.7',
            '1.8.8',
            '1.8.8.2',
            '1.8.8.4',
            '1.8.8.6',
            '1.9.0',
            '1.9.2',
            '1.9.4',
            '1.9.6',
            '1.9.8',
            '1.10.0'
        );
        return $versionList;
    }

    /**
     * Get configuration file
     * @param string $path
     * @return bool|string
     */
    public function getConfigurationPath($path = null)
    {
        if (empty($path)) {
            $chamiloPath = getcwd();
        } else {
            $chamiloPath = $path;
        }

        $dir = $chamiloPath.'/main/inc/conf/';

        if (is_dir($dir)) {
            return $dir;
        }

        return false;
    }

    /**
     * Reads the Chamilo configuration file.
     * Merges the configuration.php with the configuration.yml if it exists
     * @param null $path
     * @return array|bool|mixed
     */
    public function readConfigurationFile($path = null)
    {
        $confPath = $this->getConfigurationPath($path);

        if (!empty($confPath)) {
            $confFile = $confPath.'configuration.php';

            $_configuration = array();

            if (file_exists($confFile)) {
                require $confFile;
            }

            $confYML = $confPath.'configuration.yml';
            if (file_exists($confYML)) {
                $yaml = new Parser();
                $_configurationYML = $yaml->parse(file_get_contents($confYML));
                if (isset($_configurationYML) && !empty($_configurationYML)) {
                    if (isset($_configuration) && !empty($_configuration) ) {
                        $_configuration = array_merge($_configuration, $_configurationYML);
                    } else {
                        $_configuration = $_configurationYML;
                    }
                }
            }

            return $_configuration;
        }

        return false;
    }

    /**
     * Set configuration var
     * @param $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConfigFiles()
    {
        $configFiles = array();

        $_configuration = $this->getConfiguration();

        $sysPath = isset($_configuration['sys_path']) ? $_configuration['sys_path'] : null;

        if (file_exists($sysPath.'main/inc/conf/configuration.php')) {
            $configFiles[] = $sysPath.'main/inc/conf/configuration.php';
        }

        if (file_exists($sysPath.'main/inc/conf/configuration.yml')) {
            $configFiles[] = $sysPath.'main/inc/conf/configuration.yml';
        }

        $versions = $this->chamiloVersions();
        foreach ($versions as $version) {
            $migrationFile = $sysPath."main/inc/conf/db_migration_status_".$version."_pre.yml";
            if (file_exists($migrationFile)) {
                $configFiles[] = $migrationFile;
            }
            $migrationFile = $sysPath."main/inc/conf/db_migration_status_".$version."_post.yml";
            if (file_exists($migrationFile)) {
                $configFiles[] = $migrationFile;
            }
        }
        return $configFiles;
    }

    /**
     * Connect to the database
     * @return object Database handler
     */
    public function getConnection()
    {
        $conf = $this->getConfiguration();

        $dbh = false;

        if (isset($conf['db_host']) && isset($conf['db_host']) && isset($conf['db_password'])) {
            $dbh  = mysql_connect($conf['db_host'], $conf['db_user'], $conf['db_password']);

            if (!$dbh) {

                return false;
                //die('Could not connect to server: '.mysql_error());
            }
            $db = mysql_select_db($conf['main_database'], $dbh);
            if (!$db) {

                return false;
                //die('Could not connect to database: '.mysql_error());
            }
        }
        return $dbh;
    }

    /**
     * Gets an array with all the databases (particularly useful for Chamilo <1.9)
     * @return mixed Array of databases
     */
    function getAllDatabases()
    {
        $_configuration = $this->getConfiguration();
        $dbs            = array();

        $dbs[] = $_configuration['main_database'];

        if (isset($_configuration['statistics_database']) && !in_array(
            $_configuration['statistics_database'],
            $dbs
        ) && !empty($_configuration['statistics_database'])
        ) {
            $dbs[] = $_configuration['statistics_database'];
        }

        if (isset($_configuration['scorm_database']) && !in_array(
            $_configuration['scorm_database'],
            $dbs
        ) && !empty($_configuration['scorm_database'])
        ) {
            $dbs[] = $_configuration['scorm_database'];
        }

        if (isset($_configuration['user_personal_database']) && !in_array(
            $_configuration['user_personal_database'],
            $dbs
        ) && !empty($_configuration['user_personal_database'])
        ) {
            $dbs[] = $_configuration['user_personal_database'];
        }

        $courseTable = $_configuration['main_database'].'.course';

        $singleDatabase = isset($_configuration['single_database']) ? $_configuration['single_database'] : false;

        if ($singleDatabase == false) {
            $sql = 'SELECT db_name from '.$courseTable;
            $res = mysql_query($sql);
            if (mysql_num_rows($res) > 0) {
                while ($row = mysql_fetch_array($res)) {
                    if (!empty($row['db_name'])) {
                        $dbs[] = $row['db_name'];
                    }
                }
            }
        }

        return $dbs;
    }

    public function getConfiguration()
    {
        if (empty($this->configuration)) {
            $this->configuration = $this->readConfigurationFile();
        }
        return $this->configuration;
    }

    public function getName()
    {
        return 'configuration';
    }
}
