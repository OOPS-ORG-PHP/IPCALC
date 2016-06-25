# IPCALC pear package

## License

Copyright (c) 2016 JoungKyun.Kim &lt;http://oops.org&gt; All rights reserved

This program is under LGPL license

## Description

IPv4 calculationg and subnetting

## Installation

We recommand to install with pear command cause of dependency pear packages.

### 1. use pear command

```bash
[root@host ~]$ # add pear channel 'pear.oops.org'
[root@host ~]$ pear channel-discover pear.oops.org
Adding Channel "pear.oops.org" succeeded
Discovery of channel "pear.oops.org" succeeded
[root@host ~]$ # add IPCALC pear package
[root@host ~]$ pear install oops/ipcalc
downloading IPCALC-1.0.4.tgz ...
Starting to download ipcalc-1.0.4.tgz (3,965 bytes)
...done: 3,965 bytes
install ok: channel://pear.oops.org/ipcalc-1.0.4
[root@host ~]$
```

If you wnat to upgarde version:

```bash
[root@host ~]$ pear upgrade oops/ipcalc
```


### 2. install by hand

Get last release at https://github.com/OOPS-ORG-PHP/IPCALC/releases and uncompress pakcage within PHP include_path.

## Usages

Refence siste: http://pear.oops.org/docs/IPCALC/IPCALCLogic/IPCALC.html

reference is written by Korean. If you can't read korean, use [google translator](https://translate.google.com/translate?hl=ko&sl=ko&tl=en&u=http%3A%2F%2Fpear.oops.org%2Fdocs%2FIPCALC%2FIPCALCLogic%2FIPCALC.html&sandbox=1).


```php
<?php
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
```
