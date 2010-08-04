<?php
/**
 * Project: IPCALC :: IP calculator / subnetting
 * File:    IPCALC.php
 *
 * PHP Version 5
 *
 * Copyright (c) 1997-2010 JoungKyun.Kim
 *
 * LICENSE: LGPL
 *
 * @category    Networking
 * @package     IPCALC
 * @author      JoungKyun.Kim <http://oops.org>
 * @copyright   1997-2010 OOPS.org
 * @license     LGPL
 * @version     CVS: $Id: ipcalc.php,v 1.2 2010-08-04 11:48:30 oops Exp $
 * @link        http://pear.oops.org/package/ipcalc
 * @since       File available since release 0.0.1
 */

require_once 'IPCALC/IPCALC.php';

/**
 * Base class for IPCALC API
 * @package	IPCALC
 */
class IPCALC
{
	// {{{ properties
	/**
	 * CLI check
	 * @access	private
	 * @var		boolean
	 */
	static private $climode = false;
	// }}}

	// {{{ (void) IPCALC::__construct (void)
	/*
	 * @access	public
	 * @return	void
	 * @param	void
	 */
	function __construct () {
		self::init ();

		$this->climode = &self::$climode;
	}
	// }}}

	// {{{ (void) function IPCALC::init (void)
	/*
	 * Initialize IPCALC class
	 *
	 * @access	public
	 * @return	void
	 * @param	void
	 */
	function init () {
		if ( php_sapi_name () == 'cli' )
			self::$climode = true;
	}
	// }}}

	// {{{ (long) IPCALC::ip2long ($ip)
	/*
	 * Return unsigned proper address about given Dotted ipv4 address
	 *
	 * ip2long API of PHP is returnd signed long value, but this api
	 * is returned unsigned long value.
	 *
	 * @access	public
	 * @return	long	proper address of long type
	 * @param	ip		Dotted ipv4 address
	 */
	function ip2long ($ip) {
		return IPCALCLogic::ip2long ($ip);
	}
	// }}}

	// {{{ (boolean) IPCALC::valid_ipv4_addr ($ip)
	/**
	 * Check given adddress is valid or not
	 *
	 * @access	public
	 * @return	boolean
	 * @param	ip		Dotted ipv4 address
	 */
	function valid_ipv4_addr ($ip) {
		return IPCALCLogic::valid_ipv4_addr ($ip);
	}
	// }}}

    // {{{ (string) IPCALC::prefix2mask ($prefix)
	/**
	 * convert prefix to Dotted network mask
	 *
	 * @access  public
	 * @return  string	Dotted network mask
	 * @param   int		Decimical network prefix
	 */
	function prefix2mask ($prefix) {
		$r = IPCALCLogic::prefix2long ($prefix);
		return long2ip ($r);
	}
	// }}}

	// {{{ (int) IPCALC::mask2prefix ($mask)
	/**
	 * Convert dotted network mask to decimical network prefix
	 *
	 * @access  public
	 * @return  int
	 * @param   string      Dotted network mask
	 */
	function mask2prefix ($mask) {
		$mask = ip2long ($mask);
		return IPCALCLogic::long2prefix ($mask);
	}
	// }}}

	// {{{ (long) IPCALC::network ($ip, $mask) {
	/**
	 * get network address about given ip address and network mask
	 *
	 * @access  public
	 * @return  string
	 * @param   string      Dotted ipv4 address
	 * @param   string      Dotted network mask or decimical prefix
	 */
	function network ($ip, $mask) {
		$r = IPCALCLogic::network ($ip, $mask);
		return long2ip ($r);
	}
	// }}}

	// {{{ (string) IPCALC::broadcast ($ip, $mask)
	/**
	 * Get broadcast address for given ip address and network mask
	 *
	 * @access	public
	 * @return	string
	 * @param	ip		Dotted ipv4 address or long proper address
	 * @param	mask	Dotted network mask or network prefix
	 */
	function broadcast ($ip, $mask) {
		$r = IPCALCLogic::broadcast ($ip, $mask);
		return long2ip ($r);
	}
	// }}}

	// {{{ (int) IPCALC::guess_prefix ($start, $end)
	/**
	 * Get decimical network prefix about given start and end ip address
	 *
	 * @access  public
	 * @return  int			Decimical network prefix
	 * @param   string      Dotted start ipv4 address of range
	 * @param   string      Dotted end ipv4 address of range
	 */
	function guess_prefix ($start, $end) {
		return IPCALCLogic::guess_prefix ($start, $end);
	}
	// }}}

	// {{{ (string) IPCALC::guess_netmask ($start, $end)
	/**
	 * Get dotted network address about given start and end ip address
	 *
	 * @access  public
	 * @return  string		Dotted network mask
	 * @param   string      Dotted start ipv4 address of range
	 * @param   string      Dotted end ipv4 address of range
	 */
	function guess_netmask ($start, $end) {
		return IPCALCLogic::guess_netmask ($start, $end);
	}
	// }}}

	// {{{ (binary) IPCALC::htonl ($v)
	/**
	 * Converts the unsigned integer hostlong from host byte
	 * order to network byte order.
	 *
	 * On x86/x86_64 cpu, converts little endian to big endian.
	 *
	 * @access	public
	 * @return	binary		binary data that converted big endian
	 * @param	int			4 Byte unsigned long
	 */
	function htonl ($v) {
		return pack ('N', $v);
	}
	// }}}

	// {{{ (long) IPCALC::ntohl ($v)
	/**
	 * function converts the unsigned integer netlong from
	 * network byte order to host byte order.
	 *
	 * On x86/x86_64 cpu, converts big endian to little endian.
	 *
	 * @access	public
	 * @return	long		4 Byte signed long
	 * @param	binary		binary data that 4 Byte network byte order
	 */
	function ntohl ($v) {
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
