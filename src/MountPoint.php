<?php

Namespace CafeMedia\Operations\SshFs;

class MountPoint
implements \JsonSerializable {

    public  $id;
    public  $status;
    public $isMounted = null;
    private $name;
    private $userAccount;
	private $remoteHost;
	private $remotePath;
	private $localPath;
    private $mountCmd = "sshfs";
    private $unMountCmd = "fusermount -u";
    private $checkMountCmd = "mountpoint";


	public function __construct($config) {
        $this->id = !empty($config->id) ? $config->id : null;
        $this->name = $config->name;
        $this->userAccount = $config->user;
        $this->remoteHost = $config->hostname;
        $this->remotePath = $config->remotePath;
        $this->localPath = $config->mountPoint;
        $this->status();
	}

    public function getId() {
        return $this->id;
    }

    // Checks:
    // Paths are absolute
    private function validate($config) {

    }

	public function add() {
        echo print_r("adding MountPoint",1);
	}

    public function remove() {
        echo print_r("remove MountPoint",1);
    }

    public function status() {
        $cmd = $this->checkMountCmd." ".$this->localPath." 2>&1";
        $output = '';
        $stderr = null;
        $return = null;
        exec($cmd, $output, $return);
        $this->isMounted = !$return;
        $this->status = !$return ? "mounted" : "not mounted";

        return [$return,$output, $this->status, $this->localPath];

        //echo "status MountPoint: ".print_r([$return, $output, $this->localPath],1);
    }

    public function mount() {
        $cmd = $this->mountCmd." ".$this->fullRemotePath()." ".$this->localPath." 2>&1";
        $output = '';
        $stderr = null;
        $return = null;
        exec($cmd, $output, $return);

        return $return;
        //$return = $this->cmd($cmd, $output, $stderr);
        //echo print_r([$cmd, $output, $stderr, $return],1)."\n";
    }

    public function unMount() {
        $cmd = $this->unMountCmd." ".$this->localPath." 2>&1";
        $output = '';
        $stderr = null;
        $return = null;
        exec($cmd, $output, $return);

        return $return;
        //$return = $this->cmd($cmd, $output, $stderr);
        //echo print_r([$cmd, $output, $stderr, $return],1)."\n";
    }

    private function cmd($cmd, &$stdout=null, &$stderr=null) {
        $proc = proc_open($cmd,[
            1 => ['pipe','w'],
            2 => ['pipe','w'],
        ],$pipes);

        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        return proc_close($proc);
    }

    private function fullRemotePath() {
        $userAccount = !empty($this->userAccount) ? $this->userAccount."@" : "";
        $remoteHost = !empty($this->remoteHost) ? $this->remoteHost.":" : "";
        return $userAccount.$remoteHost.$this->remotePath;
    }

    public function getLocalPath() {
        return $this->localPath;
    }

    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "user" => $this->userAccount,
            "hostname" => $this->remoteHost,
            "remotePath" => $this->remotePath,
            "mountPoint" => $this->localPath,
        ];
    }
}
