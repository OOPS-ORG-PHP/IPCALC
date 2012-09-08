<?php
/**
 * Project: IPCALCLogic :: The internal API about IPCALC class
 * File:    IPCALC/IPCALC.php
 *
 * This class is subpackage of IPCALC class, and support various
 * api for IPCALC class
 *
 * @category    Networking
 * @package     IPCALC
 * @subpackage  IPCALCLogic
 * @author      JoungKyun.Kim <http://oops.org>
 * @copyright   (c) 2010, JoungKyun.Kim
 * @license     LGPL
 * @version     $Id$
 * @filesource
 */

class IPCALCLogic
{
	// {{{ (long) IPCALCLogic::signed_casting ($v)
	/**
	 * Convert unsigned long to signed long
	 *
	 * @access	private
	 * @return	long	signed long value
	 * @param	long	unsigned long value
	 */
	function signed_casting (&$v) {
		if ( $v > 0x7fffffff )
			$v -= 0xffffffff + 1;
	}
	// }}}

	// {{{ (long) IPCALCLogic::not_operand ($v)
	/**
	 * Support ~(NOT) operand
	 *
	 * @access	private
	 * @return	long
	 * @param	logn	decimical value
	 */
	function not_operand ($v) {
		$v = decbin ($v);
		$l = strlen ($v);
		$z = '';

		for ( $i=0; $i<$l; $i++ )
			$z .= $v[$i] ? 0 : 1;

		return $z ? bindec ($z) : false;
	}
	// }}}

	// {{{ (long) IPCALCLogic::ip2long ($ip)
	/**
	 * Return unsigned proper address about given dotted ipv4 address
	 *
	 * ip2long API of PHP is returnd signed long value, but this api
	 * is returned unsigned long value.
	 *
	 * @access	public
	 * @return	long	proper address of long type
	 * @param	string	dotted ipv4 address
	 */
	function ip2long ($ip) {
		return sprintf ('%lu', ip2long ($ip));
	}
	// }}}

	// {{{ (boolean) IPCALCLogic::valid_ipv4_addr ($ip)
	/**
	 * Check given adddress is valid or not
	 *
	 * @access	public
	 * @return	boolean
	 * @param	string	dotted ipv4 address
	 */
	function valid_ipv4_addr ($ip) {
		$ip = preg_replace ('/[\s]/', '', $ip);
		$p = self::ip2long ($ip);
		if ( ! $p || ! preg_match ('/[.]/', $ip) )
			return false;

		if ( (int) $p < 16777216 )
			return false;

		return true;
	}
	// }}}

	// {{{ (long) IPCALCLogic::prefix2long ($prefix)
	/**
	 * Convert network prefix to long type network mask
	 *
	 * @access	public
	 * @return	long	long type network maks or false
	 * @param	int		decimical network prefix
	 */
	function prefix2long ($prefix) {
		if ( ! is_numeric ($prefix) )
			return false;

		if ( (int) $prefix > 32 || (int) $prefix < 0 )
			return false;

		$l = 32;
		$shift = 30;
		$v = 0;

		$prefix = 32 - (int) $prefix;

		while ( $l > $prefix ) {
			if ( $shift < 0 )
				$v += 1;
			else
				$v += (2 << $shift);

			$shift--;
			$l--;
		}

		return $v;

	}
	// }}}

	// {{{ (int) IPCALCLogic::long2prefix ($mask)
	/**
	 * Convert long type network mask to decimical network prefix
	 *
	 * @access  public
	 * @return  int		Decimical netowrk prefix
	 * @param   long	long type network mask
	 */
	function long2prefix ($mask) {
		$count = 32;
		self::signed_casting ($mask);

		for ( $i = 0; $i < 32; $i++) {
			if ( !((int) $mask & ((2 << $i) - 1)) )
				$count--;
		}
		return $count;
	}
	// }}}

	// {{{ (long) IPCALCLogic::network ($ip, $mask)
	/**
	 * Get long type network address for given ip address
	 * and network mask
	 *
	 * @access	public
	 * @return	long
	 * @param	string	dotted ipv4 address or long proper address
	 * @param	string	dotted network mask or network prefix
	 */
	function network ($ip, $mask) {
		if ( ! is_numeric ($ip) )
			$ip = ip2long ($ip);
		self::signed_casting ($ip);

		if ( ! is_numeric ($mask) )
			$mask = ip2long ($mask);
		self::signed_casting ($mask);

		if ( (int) $mask >= 0 && (int) $mask < 33 )
			$mask = self::prefix2long ($mask);

		return (int) $ip & (int) $mask;
	}
	// }}}

	// {{{ (long) IPCALCLogic::broadcast ($ip, $mask)
	/**
	 * Get long type broadcast address for given ip address
	 * and network mask
	 *
	 * @access	public
	 * @return	long
	 * @param	string	dotted ipv4 address or long proper address
	 * @param	string	dotted network mask or network prefix
	 */
	function broadcast ($ip, $mask) {
		if ( ! is_numeric ($ip) )
			$ip = ip2long ($ip);
		self::signed_casting ($ip);

		if ( ! is_numeric ($mask) )
			$mask = ip2long ($mask);
		self::signed_casting ($mask);

		if ( (int) $mask >= 0 && (int) $mask < 33 )
			$mask = self::prefix2long ((int) $mask);

		$r = ((int) $ip & (int) $mask) | self::not_operand ($mask);

		return $r;
	}
	// }}}

	// {{{ (int) IPCALCLogic::guess_prefix ($start, $end)
	/**
	 * Get decimical network prefix about given start and end ip address
	 *
	 * @access	public
	 * @return	int		Decimical network prefix
	 * @param	string	Dotted ipv4 address or long proper address
	 * @param	string	Dotted ipv4 address or long proper address
	 */
	function guess_prefix ($start, $end) {
		$prefix = 0;
		if ( ! is_numeric ($start) )
			$start = ip2long ($start);
		self::signed_casting ($start);

		if ( ! is_numeric ($end) )
			$end   = ip2long ($end);
		self::signed_casting ($end);

		if ( $end < $start ) {
			$n = $start;
			$start = $end;
			$end = $n;
		}

		$n = $end - $start;
		if ( (int) $n === 0 )
			return 32;

		while ( ! ($n & 0x80000000) ) {
			$n <<= 1;
			$prefix++;
		}

		$n = 0;
		while ( (int) $end > (int) $n ) {
			if ( $n > 0 )
				$prefix--;

			$n = sprintf ('%u', self::broadcast ($start, $prefix));
		}

		return $prefix;
	}
	// }}}

	// {{{ (long) IPCALCLogic::guess_netmask ($start, $end)
	/**
	 * Get long type network mask about given start and end ip address
	 *
	 * @access	public
	 * @return	string	Dotted IPv4 address
	 * @param	string	Dotted ipv4 address or long proper address
	 * @param	string 	Dotted ipv4 address or long proper address
	 */
	function guess_netmask ($start, $end) {
		$r = self::prefix2long (self::guess_prefix ($start, $end));
		return long2ip ($r);
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
