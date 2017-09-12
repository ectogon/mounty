<?php

Namespace CafeMedia\Operations\SshFs;

class MountConfig {

    private $mountPoints;
	private $defaultConfigFile = "/etc/cmi/dev-mounts.json";

    /**
        Load config file
        @todo detect error
     */
	public function __construct($config = null) {
        $config = is_string($config) ? $config : $this->defaultConfigFile;
        $mounts = file_get_contents(($config));
        $this->mountPoints = json_decode($mounts);
	}

    private function load($configFile) {

    }

    public function save($config) {

    }

    public function get() {
        return $this->mountPoints;
    }

}
