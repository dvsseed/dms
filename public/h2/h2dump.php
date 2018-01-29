#!/usr/local/bin/php
<?php
// H2-Dumper/0.3b
/* Usage:
php scripts/h2dump.php auth
php scripts/h2dump.php diary
php scripts/h2dump.php diary after_date=2015-07-28T00:00:00+08:00
php scripts/h2dump.php diary "after_date=2015-07-28T00:00:00+08:00&page=2"
 */
if (!function_exists('curl_init')) {
    die('Sorry cURL is not installed!');
}

$BASE_URL = 'https://www.health2sync.com/';
$LOGIN_URL = $BASE_URL . 'api/wellness/login';
// Remark by Eric as 2016/12/09 : $DUMP_URL = $BASE_URL.'api/wellness/fetch';
$DUMP_URL = $BASE_URL . 'api/wellness/fetch_v2';

$CONFIG_FILE = 'h2dump.cfg';
$USER_AGENT = 'H2-Dumper/0.3b';
// $CLIENT_ID = 'doctor_yo_server_0.2b';
$CLIENT_ID = 'dm_clinic_yu_server';

include_once "readdb.php";
include "readdump.php";

if (count($argv) < 2) {
    showUsage();
} else {
    echo ">>>Here is [[argv >= 2]] section:<<<--Start\n";
    $cmd = $argv[1];
    $args = $argv[2];
    $afterdate = "";

    $str = "select concat( replace( left(addtime(max(mr_date) ,'0 0:0:1'),19),' ','T'),'+00:00') as NEWTIME FROM mresult_top_pid ";
//    $str = "select '2016-12-02T02:44:17+00:00'";
    $rs = mysqli_query($dbh, $str);
    if (!$rs) {
        die("Mysql Error:" . $sql . "\n" . mysqli_error($dbh));
    } else {
        $row = mysqli_fetch_row($rs);
        $afterdate = $row[0];
        $args = "after_date=" . $row[0];
        mysqli_free_result($rs);
        //echo "args: ".$args."\n";
    }
	mysqli_close($dbh);
    echo "args: " . $args . "\n";

    // $file = "test/dumptest.json";
    // $newfile = "test/dumptest_1.json";
	$cmdResult = false;

    if (!in_array($cmd, array('auth', 'diary'))) {
        die("ERROR: unknown command: \"" . $cmd . "\"");
    } else {
        echo "exec cmd(args) ... \n";
        $cmdResult = $cmd($args);
        // if (!copy($file, $newfile)) {
            // echo "failed to copy $file...\n";
        // }
    }

    if ($cmdResult) {
		$current = 0;
		$hasNext = true;
		$total = 0;
		// echo "while outside:\n";
		while ($hasNext && $cmd == "diary") {
			echo "while inside: $current \n";
			$retext = do_read(); // return $hasNext.",".$current.",".$total;
			echo "after do_read: $retext \n";

			$getargs = explode(",", $retext);
			$hasNext = $getargs[0];
			$current = $getargs[1] + 1;
	 
			if ($hasNext) {
				// hasNext = true
				$args = "after_date=" . $afterdate . "&page=" . $current;
				echo "args: " . $args . "\n";
				$cmd($args);
				echo "after cmd : " . $cmd . "\n";
				$newfile = "dump_diaries_" . $current . ".json";
				if (!copy($file, $newfile)) {
					echo "failed to copy $file...\n";
				}
			}
		} // end of  while($hasNext == "true" && $cmd == "diary")
	}
    echo ">>>Here is [[argv >= 2]] section:<<<---End\n";
}

function showUsage()
{
    echo "
Usage: php h2dump.php <command> [options]\n

Commands:
    auth               Get authorize
    diary              Fetch diary records
    \n
    ";
    exit;
}

function auth()
{
    global $LOGIN_URL;
    global $CLIENT_ID;

    echo "Getting authorize ...\n";
    $email = readline('Enter email: ');
    $password = prompt_silent();

    $fields = array (
        'email' => $email,
        'password' => $password,
        'client_id' => $CLIENT_ID,
    );

    $res = curl($LOGIN_URL, 'POST', json_encode($fields));
    $json = json_decode($res, true);

    if (empty($json)) {
        echo "Connect server failed.";
    } else if ($json['status'] != 0) {
        echo $json['message'];
    } else {
        save_access_token($json['data']['access_token']);
        echo 'Get Authorized successful.';
    }
}

function diary($args)
{
    echo ">>>Here is [[diary]] section:<<<---Start\n";
    global $DUMP_URL;
    global $CLIENT_ID;

    if (strlen($args) > 0) {
        $queryString = array();
        foreach (explode("&", $args) as $value) {
            preg_match_all("/([^,= ]+)=([^,= ]+)/", $value, $r);
            array_push($queryString, http_build_query(array_combine($r[1], $r[2])));
        }
        $queryString = implode('&', $queryString);
        $queryString = '?' . $queryString;
    }

    echo "Dumping data ... \n";

    $access_token = read_access_token();
    $fields = array();
    $headers = array(
        'X-Access-Token:' . $access_token,
        'X-Client-Id:' . $CLIENT_ID,
    );
    $res = curl($DUMP_URL . $queryString, 'GET', json_encode($fields), $headers);
    $json = json_decode($res, true);

    if (empty($json)) {
        echo "Connect server failed.\n";
    } else if ($json['status'] != 0) {
        echo "Message: " . $json['message'] . "\n";
    } else {
        file_put_contents("dump_diaries.json", $res);
        echo 'Dumping data successful.\n';
		return true;
    }
    echo ">>>Here is [[diary]] section:<<<---End\n";
	return false;
}

function save_access_token($access_token)
{
    global $CONFIG_FILE;
    file_put_contents($CONFIG_FILE, $access_token);
}

function read_access_token()
{
    global $CONFIG_FILE;
    if (file_exists($CONFIG_FILE)) {
        return file_get_contents($CONFIG_FILE);
    } else {
        return '';
    }

}

function curl($url, $method = 'GET', $fields_string = null, $custom_headers = array())
{
    global $USER_AGENT;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if (!is_null($fields_string)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        $param_headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($fields_string),
        );
    }

    $headers = array_merge($custom_headers, $param_headers);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    // Set a referer
    // curl_setopt($ch, CURLOPT_REFERER, "doctor_yo_server");

    // User agent
    curl_setopt($ch, CURLOPT_USERAGENT, $USER_AGENT);

    // Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_HEADER, 0);

    // Should cURL return or print out the data? (true = return, false = print)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    // Download the given URL, and return output
    $output = curl_exec($ch);

    // Close the cURL resource, and free system resources
    curl_close($ch);

    return $output;
}

function prompt_silent($prompt = "Enter Password:")
{
    if (preg_match('/^win/i', PHP_OS)) {
        $vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
        file_put_contents(
            $vbscript, 'wscript.echo(InputBox("'
            . addslashes($prompt)
            . '", "", "password here"))');
        $command = "cscript //nologo " . escapeshellarg($vbscript);
        $password = rtrim(shell_exec($command));
        unlink($vbscript);
        return $password;
    } else {
        $command = "/usr/bin/env bash -c 'echo OK'";
        if (rtrim(shell_exec($command)) !== 'OK') {
            trigger_error("Can't invoke bash");
            return;
        }
        $command = "/usr/bin/env bash -c 'read -s -p \""
        . addslashes($prompt)
            . "\" mypassword && echo \$mypassword'";
        $password = rtrim(shell_exec($command));
        echo "\n";
        return $password;
    }
}
?>
