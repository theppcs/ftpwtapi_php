<?php
require_once __DIR__ . '/../config/connectdb.php';
require_once __DIR__ . '/simpleftp.php';

class FtpConfig
{
    public string $ftp_url;
    public string $ftp_username;
    public string $ftp_password;
    public int $ftp_port;
    public string $ftp_filepath;
    public string $ftp_filename;

    public function __construct(?string $url, ?string $username, ?string $password, ?int $port, ?string $filepath, ?string $filename)
    {
        $this->ftp_url = $url ?? "";
        $this->ftp_username = $username ?? "";
        $this->ftp_password = $password ?? "";
        $this->ftp_port = $port ?? 21;
        $this->ftp_filepath = $filepath ?? "";
        $this->ftp_filename = $filename ?? "";
    }
}
class FtpWeight
{
    const localFileName = __DIR__ . "/../apptemp/ftpweight.txt";

    public function __construct(?string $site, ?string $weightmachine)
    {
        $this->site = mb_strtoupper($site);
        $this->weightmachine = $weightmachine;
        if (!$this->getFTPDataFromDB()) {
            throw new Exception("site not found.");
        }
    }
    public function __destruct()
    {
        // print "Destroying " . __CLASS__ . "\n";
        // if (file_exists($this->cookiePath)) {
        //     unlink($this->cookiePath);
        // }
    }

    //private members & functions
    private string $site;
    private string $weightmachine;
    private string $ftp_data;
    private array $ftpConfigs;

    private function getFTPDataFromDB(): bool
    {
        try {
            $dbCon = connect_MySQL_DB();

            //test query
            $sql = "SELECT * FROM `machine_config` WHERE `site` = '$this->site' ";
            if (!IsNullOrEmptyString($this->weightmachine)) {
                $sql .= "AND `weightmachine` = '$this->weightmachine' ";
            }
            $pp_sel = $dbCon->prepare($sql);
            $sel_result = $pp_sel->execute();
            //$debug = pdo_debugStrParams($pp_sel);
            $result = $pp_sel->fetchAll(PDO::FETCH_ASSOC);

            $found = false;
            foreach ($result as $config) {
                if ($config["site"] === mb_strtoupper($this->site)) {
                    $found = true;
                    $ftpConfig = new FtpConfig(
                        $config["ftp_url"],
                        $config["ftp_username"],
                        $config["ftp_password"],
                        $config["ftp_port"],
                        $config["ftp_filepath"],
                        $config["ftp_filename"]
                    );
                    $this->ftpConfigs[$config["weightmachine"]] = $ftpConfig;
                    // break;
                }
            }
            return $found;
            $dbCon = null;
        } catch (PDOException $e) {
            //show exception
            echo $e->getMessage();
        }
    }

    private function getFileFTP(FtpConfig $ftpConfig): bool
    {
        // set SFTP object, use host, username and password
        $ftp = new SimpleFTP($ftpConfig->ftp_url, $ftpConfig->ftp_username, $ftpConfig->ftp_password, $ftpConfig->ftp_port);
        // connect to FTP server
        $ftp->passive = true;
        if ($ftp->connect()) {
            if ($ftp->cd($ftpConfig->ftp_filepath)) {
                //$ls = $ftp->ls();
                //$size = $ftp->filesize($ftpConfig->ftp_filename);
                ob_start();
                //if (1 === 1) { // 
                // if ($ftp->get($ftpConfig->ftp_filename, self::localFileName, FTP_BINARY)) {
                //     $data = file_get_contents(self::localFileName);
                // }
                if ($ftp->get($ftpConfig->ftp_filename, "php://output", FTP_BINARY)) {
                    $data = ob_get_contents();
                    ob_end_clean();
                }
            }
        }
        if ($data) {
            $this->ftp_data = $data; //$this->utf16_decode($data);
            return true;
        }
        return false;
    }

    private function HMIutf16Convert(string $str)
    {
        return mb_convert_encoding(ltrim($str), "UTF-8", "UTF-16LE");
    }

    private function findLine(string $palletno): array
    {
        $fileArray = explode("\n", $this->ftp_data);
        $acount = 0;
        for ($i = count($fileArray) - 1; $i >= 0; $i--) {
            //$line = trim($fileArray[$i]); 
            $line = $this->HMIutf16Convert($fileArray[$i]);
            if ($line !== "") {
                $lineArray = explode("\t", $line);
                if (count($lineArray) >= 5) {
                    $result['timeHMS'] = $lineArray[0];
                    $result['dateDMY'] = $lineArray[1];
                    $result['loadInOut'] = $lineArray[2];
                    $result['weight'] = $lineArray[3];
                    $result['palletno'] = $lineArray[4];

                    if ($result['palletno'] === $palletno) {
                        return $result;
                    }
                }
            }
            $acount++;
            if ($acount > 20) {
                break;
            }
        }
        return [];
    }

    //public functions
    public function GetWeight(?string $palletno)
    {
        if (IsNullOrEmptyString($palletno)) {
            throw new Exception("Blank pallet no. received.");
        }

        foreach ($this->ftpConfigs as $ftpConfig) {
            if ($this->getFileFTP($ftpConfig)) {
                $dataLine = $this->findLine($palletno);
                if (count($dataLine) !== 0) {
                    return $dataLine;
                }
            }
        }
        return false;
    }
}
