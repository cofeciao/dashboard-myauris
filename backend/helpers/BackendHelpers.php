<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27-Feb-19
 * Time: 5:31 PM
 */

namespace backend\helpers;

use backend\helpers\moneytotext\MoneyToTextModel;

class BackendHelpers {
	public static function getRatings( $num ) {
		if ( $num == 0 ) {
			return null;
		}
		$html = '<div id="read-only-stars" title="regular">';
		if ( $num == - 3 ) {
			$html .= '<img alt="3" src="/images/raty/star-on.png" title="regular">';
		}
		if ( $num == 1 ) {
			$html .= '<img alt="3" src="/images/raty/star-on.png" title="regular"> <img alt="3" src="/images/raty/star-on.png" title="regular">';
		}
		if ( $num == 2 ) {
			$html .= '<img alt="3" src="/images/raty/star-on.png" title="regular"> <img alt="3" src="/images/raty/star-on.png" title="regular"> <img alt="3" src="/images/raty/star-on.png" title="regular">';
		}
		$html .= '</div>';

		return $html;
	}

	/**
	 * Viết tắt string bằng chữ cái đầu: Ví dụ: Bộ phận => Bp
	 * Trả lại nếu string chỉ 1 từ.
	 *
	 * @param string str
	 *
	 * @return string
	 */
	public static function acronysm_string( $str ) {

		$temp_first_key = substr( $str, 0, 1 );
		preg_match_all( '#\s(.)#', $str, $match );
		if ( ! empty( $match[1] ) ) {
			$name = ! empty( $match ) ? implode( '', $match[1] ) : '';
			$str  = $temp_first_key . $name;
		}

		return $str;
	}


	/**
	 * Flattens an array, or returns FALSE on fail.
	 * PHP has no native method to flatten an array .. so there you go.
	 */

	public static function array_flatten( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}
		$result = array();
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				// flat all layout
//                $result = array_merge($result, $this->array_flatten($value));
				//flat one layout;
				$result = array_merge( $result, $value );
			} else {
				$result[ $key ] = $value;
			}
		}

		return $result;
	}


	/**
	 * Flatten an array, or return FALSE on fail.
	 * It's suit with only type of array below. Do not use this fucking function unless you know what tfuck are you doing.
	 *    [82] => Array
	 * (
	 * [298] => Phòng 202 - CS1
	 * )
	 *
	 * [83] => Array
	 * (
	 * [299] => Phòng 203 - CS1
	 * )
	 *
	 * [84] => Array
	 * (
	 * [300] => Phòng Test - CS1
	 * )
	 *
	 * [85] => Array
	 * (
	 * [301] => [Cs1-Q3] Bác sĩ Khoa CS1
	 * )
	 *
	 * Result:
	 *
	 *
	 *
	 *
	 * array(
	 * [298] => Phòng 202 - CS1
	 *
	 * [299] => Phòng 203 - CS1
	 *
	 * [300] => Phòng Test - CS1
	 *
	 * [301] => [Cs1-Q3] Bác sĩ Khoa CS1
	 * )
	 */
	public static function flattenArrayWithKeyAndValue( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}
		$result = array();
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $k => $v ) {
					$result[ $k ] = $v;
				}
			}
		}

		return $result;
	}


	/**
	 * Không xài. Chơi ngu viết ra nhưng cũng là 1 giải thuật.
	 * Recursive split array by 3:
	 * Input example:
	 * Array
	 * (
	 * [0] => asd
	 * [1] => faw
	 * [2] => ehg
	 * [3] => fak
	 * [4] => jsd
	 * [5] => hgf
	 * [6] => kda
	 * [7] => wel
	 * [8] => fja
	 * [9] => wef
	 * [10] => asd
	 * [11] => fas
	 * [12] => jas
	 * [13] => dfa
	 * [14] => sdf
	 * [15] => asd
	 * [16] => fas
	 * [17] => dfa
	 * [18] => sdf
	 * [19] => fja
	 * [20] => wef
	 * [21] => asd
	 * [22] => fas
	 * [23] => jas
	 * [24] => dfa
	 * [25] => sdf
	 * [26] => asd
	 * [27] => fas
	 * [28] => dfa
	 * [29] => sdf
	 * [30] => fja
	 * [31] => wef
	 * [32] => asd
	 * [33] => fas
	 * )
	 *
	 * Result example:
	 * Array
	 * (
	 * [0] => Array
	 * (
	 * [0] => Array
	 * (
	 * [0] => asd
	 * [1] => faw
	 * [2] => ehg
	 * [3] => fak
	 * )
	 *
	 * [1] => Array
	 * (
	 * [0] => jsd
	 * [1] => hgf
	 * [2] => kda
	 * [3] => wel
	 * )
	 *
	 * [2] => Array
	 * (
	 * [0] => fja
	 * [1] => wef
	 * [2] => asd
	 * [3] => fas
	 * )
	 *
	 * )
	 *
	 * [1] => Array
	 * (
	 * [0] => Array
	 * (
	 * [0] => jas
	 * [1] => dfa
	 * [2] => sdf
	 * [3] => asd
	 * )
	 *
	 * [1] => Array
	 * (
	 * [0] => fas
	 * [1] => dfa
	 * [2] => sdf
	 * )
	 * [2] => Array
	 * (
	 * [0] => fja
	 * [1] => wef
	 * [2] => asd
	 * [3] => fas
	 * )
	 *
	 * )
	 * [2] => Array
	 * (
	 * [0] => Array
	 * (
	 * [0] => jas
	 * [1] => dfa
	 * [2] => sdf
	 * [3] => asd
	 * )
	 *
	 * [1] => Array
	 * (
	 * [0] => fas
	 * [1] => dfa
	 * [2] => sdf
	 * )
	 * [2] => Array
	 * (
	 * [0] => fja
	 * [1] => wef
	 * [2] => asd
	 * [3] => fas
	 * )
	 *
	 * )
	 * )*/
	public function splitArray( &$arr_split ) {
		$count = count( $arr_split );

		if ( $count > 3 ) {
			if ( $count % 3 == 0 ) {
				$arr_split = array_chunk( $arr_split, 3 );
				$this->splitArray( $arr_split );
			} else {

				$arr_split = array_chunk( $arr_split, $count % 3 );
				//get first odd number
				$arr_temp = $arr_split[0];
				//unset odd number from the beginning
				unset( $arr_split[0] );
				//flat one level of array
				$arr_split = self::array_flatten( $arr_split );
				//chunk 4 again;
				$arr_split = array_chunk( $arr_split, 3 );
				//push array temp to the beginning of $array_split
				array_unshift( $arr_split, $arr_temp );

				if ( count( $arr_split ) > 3 ) {
					$this->splitArray( $arr_split );
				}
			}
		}

		return ( $arr_split );

	}

}
