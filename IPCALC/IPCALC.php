<?php
/**
 * PHP Version 5
 *
 * Copyright (c) 1997-2010 JoungKyun.Kim
 *
 * LICENSE: LGPL
 *
 * @category    Networking
 * @package     IPCACLLogic
 * @author      JoungKyun.Kim <http://oops.org>
 * @copyright   1997-2010 OOPS.org
 * @license     LGPL
 * @version     CVS: $Id: IPCALC.php,v 1.7 2010-08-04 15:15:25 oops Exp $
 */

class IPCALCLogic
{
	// {{{ (long) not_operand ($v)
	/**
	 * Support ~(NOT) operand
	 *
	 * @access	public
	 * @return	long
	 * @param	value	decimical value
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
	/*
	 * Return unsigned proper address about given dotted ipv4 address
	 *
	 * ip2long API of PHP is returnd signed long value, but this api
	 * is returned unsigned long value.
	 *
	 * @access	public
	 * @return	long	proper address of long type
	 * @param	ip		dotted ipv4 address
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
	 * @param	ip		dotted ipv4 address
	 */
	function valid_ipv4_addr ($ip) {
		$ip = preg_replace ('/[\s]/', '', $ip);
		$p = self::ip2long ($ip);
		if ( ! $p || ! preg_match ('/[.]/', $ip) )
			return false;

		if ( $p < 16777216 )
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
	 * @param	prefix	decimical network prefix
	 */
	function prefix2long ($prefix) {
		if ( ! is_numeric ($prefix) )
			return false;

		if ( $prefix > 32 || $prefix < 0 )
			return false;

		$l = 32;
		$shift = 30;
		$v = 0;

		$prefix = 32 - $prefix;

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
	 * @parma   long	long type network mask
	 */
	function long2prefix ($mask) {
		$count = 32;
		for ( $i = 0; $i < 32; $i++) {
			if ( !($mask & ((2 << $i) - 1)) )
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
	 * @param	ip		dotted ipv4 address or long proper address
	 * @param	mask	dotted network mask or network prefix
	 */
	function network ($ip, $mask) {
		if ( ! is_numeric ($ip) )
			$ip = ip2long ($ip);

		if ( ! is_numeric ($mask) )
			$mask = ip2long ($mask);

		if ( $mask < 33 )
			$mask = self::prefix2long ($mask);

		return $ip & $mask;
	}
	// }}}

	// {{{ (long) IPCALCLogic::broadcast ($ip, $mask)
	/**
	 * Get long type broadcast address for given ip address
	 * and network mask
	 *
	 * @access	public
	 * @return	long
	 * @param	ip		dotted ipv4 address or long proper address
	 * @param	mask	dotted network mask or network prefix
	 */
	function broadcast ($ip, $mask) {
		if ( ! is_numeric ($ip) )
			$ip = ip2long ($ip);

		if ( ! is_numeric ($mask) )
			$mask = ip2long ($mask);

		if ( $mask < 33 )
			$mask = self::prefix2long ($mask);

		$r = ($ip & $mask) | self::not_operand ($mask);

		return $r;
	}
	// }}}

	// {{{ (int) IPCALCLogic::guess_prefix ($start, $ip)
	/**
	 * Get decimical network prefix about given start and end ip address
	 *
	 * @access	public
	 * @return	int		Decimical network prefix
	 * @param	start	Dotted ipv4 address or long proper address
	 * @param	end		Dotted ipv4 address or long proper address
	 */
	function guess_prefix ($start, $end) {
		$prefix = 0;
		if ( ! is_numeric ($start) )
			$start = ip2long ($start);
		if ( ! is_numeric ($end) )
			$end   = ip2long ($end);

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
	 * @param	start	Dotted ipv4 address or long proper address
	 * @param	end  	Dotted ipv4 address or long proper address
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
