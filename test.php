<?php
// $Id$

require_once 'ipcalc.php';

function title ($s) {
	printf (' * IPCALC::%-20s => ', $s);
}
function result ($bool) {
	printf ("%s\n", $bool ? 'Success' : 'Failed');
}

echo "** Test IPCALC PHP pear API test\n\n";

$ip = new IPCALC;

title ('ip2long');
$src  = '222.222.222.222';
$dest = 3739147998;
$test = $ip->ip2long ($src);

result ($dest == $test);

title ('valid_ipv4_addr');
$test = $ip->valid_ipv4_addr ('1.1.1.1 :');
result (! $test);

title ('prefix2mask');
$test = $ip->prefix2mask (26);
result ($test == '255.255.255.192');

title ('mask2prefix');
$test = $ip->mask2prefix ('255.255.255.192');
result ($test == 26);

title ('network');
$test = $ip->network ('222.222.222.222', 26);
result ($test == '222.222.222.192');

title ('boradcast');
$test = $ip->broadcast ('222.222.222.222', 26);
result ($test == '222.222.222.255');

title ('guess_prefix');
$test = $ip->guess_prefix ('222.222.222.193', '222.222.222.229');
result ($test === 26);

title ('guess_netmask');
$test = $ip->guess_netmask ('222.222.222.193', '222.222.222.229');
result ($test === '255.255.255.192');

echo "\n\n";
?>
