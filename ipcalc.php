<?php
/**
 * Project: IPCALC :: IP calculator / subnetting<br>
 * File:    IPCALC.php
 *
 * 이 class는 IP 계산과 서브네팅을 지원한다.
 *
 * @category    Networking
 * @package     IPCALC
 * @author      JoungKyun.Kim <http://oops.org>
 * @copyright   (c) 2015, JoungKyun.Kim
 * @license     LGPL
 * @version     $Id$
 * @link        http://pear.oops.org/package/ipcalc
 * @since       File available since release 0.0.1
 * @example     pear_IPCALC/test.php Sample code of IPCLAC class
 * @filesource
 */

/**
 * import IPCALCLogic class
 */
require_once 'IPCALC/IPCALC.php';

/**
 * IPCALC 의 frontend Class
 * @package	IPCALC
 */
class IPCALC extends IPCALCLogic
{
	// {{{ (void) IPCALC::__construct (void)
	/**
	 * Initialize IPCALC class
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct () { }
	// }}}

	// {{{ (long) IPCALC::ip2long ($ip)
	/**
	 * Dot로 구분된 IPv4 주소를 정수형 주소로 변환
	 *
	 * PHP의 ip2long API가 singed 형이기 때문에 음수의 값이 나올 수
	 * 있어, 이 API를 제공하여 항상 양수의 값이 나올 수 있도록 지원.
	 *
	 * {@example pear_IPCALC/test.php 18 3}
	 *
	 * @access	public
	 * @return	long   unsigned 형의 정수형 네트워크 주소
	 * @param	string Dot로 구분된 IPv4 주소
	 */
	static function ip2long ($ip) {
		return parent::ip2long ($ip);
	}
	// }}}

	// {{{ (boolean) IPCALC::valid_ipv4_addr ($ip)
	/**
	 * 인자로 주어진 값이 정상적인 IPv4 주소인지를 체크
	 *
	 * {@example pear_IPCALC/test.php 24 2}
	 *
	 * @access	public
	 * @return	boolean 정상적인 IP일 경우 true, 그 외 false
	 * @param	string  Dot로 구분된 IPv4 주소
	 */
	static function valid_ipv4_addr ($ip) {
		return parent::valid_ipv4_addr ($ip);
	}
	// }}}

    // {{{ (string) IPCALC::prefix2mask ($prefix)
	/**
	 * 네트워크 prefix를 네트워크 mask로 변환
	 *
	 * {@example pear_IPCALC/test.php 29 2}
	 *
	 * @access  public
	 * @return  string  네트워크 mask
	 * @param   integer	네트워크 prefix
	 */
	static function prefix2mask ($prefix) {
		$r = parent::prefix2long ($prefix);
		return long2ip ($r);
	}
	// }}}

	// {{{ (int) IPCALC::mask2prefix ($mask)
	/**
	 * 네트워크 mask를 네트워크 prefix로 변환
	 *
	 * {@example pear_IPCALC/test.php 33 2}
	 *
	 * @access  public
	 * @return  int    네트워크 prefix
	 * @param   string 네트워크 mask
	 */
	static function mask2prefix ($mask) {
		$mask = ip2long ($mask);
		return parent::long2prefix ($mask);
	}
	// }}}

	// {{{ (string) IPCALC::network ($ip, $mask) {
	/**
	 * 주어진 IPv4 주소와 네트워크 mask로 구성된 서브넷의 네트워크
	 * 주소를 반환
	 *
	 * {@example pear_IPCALC/test.php 37 2}
	 *
	 * @access  public
	 * @return  string 해당 서브넷의 네트워크 주소
	 * @param   string IPv4 주소
	 * @param   string 네트워크 mask 또는 prefix
	 */
	static function network ($ip, $mask) {
		$r = parent::network ($ip, $mask);
		return long2ip ($r);
	}
	// }}}

	// {{{ (string) IPCALC::broadcast ($ip, $mask)
	/**
	 * 주어진 IPv4 주소와 네트워크 mask로 구성된 서브넷의 브로드캐스트
	 * 주소를 반환
	 *
	 * {@example pear_IPCALC/test.php 41 2}
	 *
	 * @access	public
	 * @return	string  해당 서브넷의 브로드캐스트 주소
	 * @param	string	IPv4 주소 또는 long형 주소값
	 * @param	string	네트워크 mask
	 */
	static function broadcast ($ip, $mask) {
		$r = parent::broadcast ($ip, $mask);
		return long2ip ($r);
	}
	// }}}

	// {{{ (int) IPCALC::guess_prefix ($start, $end)
	/**
	 * 시작 주소와 마지막 주소를 포함한 서브넷의 prefix를 반환
	 *
	 * {@example pear_IPCALC/test.php 45 2}
	 *
	 * @access  public
	 * @return  int			네트워크 prefix
	 * @param   string      범위의 시작 IPv4 주소
	 * @param   string      범위의 마지막 IPv4 주소
	 */
	static function guess_prefix ($start, $end) {
		return parent::guess_prefix ($start, $end);
	}
	// }}}

	// {{{ (string) IPCALC::guess_netmask ($start, $end)
	/**
	 * 시작 주소와 마지막 주소를 포함한 서브넷의 최소 mask를 반환
	 *
	 * {@example pear_IPCALC/test.php 49 2}
	 *
	 * @access  public
	 * @return  string		네트워크 mask
	 * @param   string      범위의 시작 IPv4 주소
	 * @param   string      범위의 마지막 IPv4 주소
	 */
	static function guess_netmask ($start, $end) {
		return parent::guess_netmask ($start, $end);
	}
	// }}}

	// {{{ (binary) IPCALC::htonl ($v)
	/**
	 * 4 Byte unsigned 정수를 host byte order에서 network byte
	 * order로 변환
	 *
	 * x86/x86_64 cpu에서는 little endian을 big endian으로 변환
	 * 하는 것과 동일 함
	 *
	 * @access	public
	 * @return	binary		network byte order로 변환된 binary
	 * @param	int			4 Byte 양수형 정수
	 */
	static function htonl ($v) {
		return pack ('N', $v);
	}
	// }}}

	// {{{ (long) IPCALC::ntohl ($v)
	/**
	 * Network oerder byte로 구성된 4 Byte 정수 binary data를
	 * signed 정수로 변환
	 *
	 * x86/x86_64 cpu에서는 big endian을 little endian으로 변환
	 * 하는 것과 동일 함
	 *
	 * @access	public
	 * @return	long		4 Byte signed 정수
	 * @param	binary		Network byte order로 구성된 4Byte 정수 binary
	 */
	static function ntohl ($v) {
		for ( $i=3; $i>=0; $i-- )
			$v1 .= $v[$i];

		$val = unpack ('ilittle', $v1);
		return $val['little'];
	}
	// }}}
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
?>
