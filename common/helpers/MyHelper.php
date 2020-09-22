<?php

namespace common\helpers;

use DateTime;

class MyHelper
{
    public static function NumberToByte($num, $arr = 0)
    {
        $ext = [' Byte', ' Kilobyte', ' Megabyte', ' Gigabyte', ' Terabyte', ' Petabyte', ' Exabyte', ' Zetabyte', ' Yottabyte', ' Brontobyte', ' Geopbyte'];
        $res = $num;
        $coutExt = count($ext);
        if ($arr >= ($coutExt - 1)) {
            $arr = 10;
            return number_format($res, 0, ',', '.') . $ext[$arr];
        }

        if ($num < 1024) {
            return round($res, 2) . $ext[$arr];
        }
        $res = $num / 1024;
        $arr++;

        return self::NumberToByte($res, $arr);
    }

    public static function SecondsToTime($seconds = 0)
    {
        $second = $seconds % 60;
        if (strlen($second) == 1) {
            $second = '0' . $second;
        }

        $minutes = floor($seconds / 60);
        if (strlen($minutes) == 1) {
            $minutes = '0' . $minutes;
        }
        $hours = floor($seconds / 3600);
        if (strlen($hours) == 1) {
            $hours = '0' . $hours;
        }

        return $hours . ":" . $minutes . ":" . $second;
    }

    public static function TimeBefore($from, $to = null)
    {
        if (empty($to) || $to = null) {
            $to = time();
        }
        $diff = (int)abs($to - $from);

        if ($diff < HOUR_IN_SECONDS) {
            $mins = round($diff / MINUTE_IN_SECONDS);
            if ($mins <= 1) {
                $mins = 1;
            }
            $since = \Yii::t('frontend', '{mins} minute ago', ['mins' => $mins]);
        } elseif ($diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS) {
            $hours = round($diff / HOUR_IN_SECONDS);
            if ($hours <= 1) {
                $hours = 1;
            }
            $since = \Yii::t('frontend', '{hours} hours ago', ['hours' => $hours]);
        } elseif ($diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS) {
            $days = round($diff / DAY_IN_SECONDS);
            if ($days <= 1) {
                $days = 1;
            }
            $since = \Yii::t('frontend', '{days} days ago', ['days' => $days]);
        } elseif ($diff < MONTH_IN_SECONDS && $diff >= WEEK_IN_SECONDS) {
            $weeks = round($diff / WEEK_IN_SECONDS);
            if ($weeks <= 1) {
                $weeks = 1;
            }
            $since = \Yii::t('frontend', '{weeks} weeks ago', ['weeks' => $weeks]);
        } elseif ($diff < YEAR_IN_SECONDS && $diff >= MONTH_IN_SECONDS) {
            $months = round($diff / MONTH_IN_SECONDS);
            if ($months <= 1) {
                $months = 1;
            }
            $since = \Yii::t('frontend', '{months} months ago', ['months' => $months]);
        } elseif ($diff >= YEAR_IN_SECONDS) {
            $years = round($diff / YEAR_IN_SECONDS);
            if ($years <= 1) {
                $years = 1;
            }
            $since = \Yii::t('frontend', '{years} years ago', ['years' => $years]);
        }

        return $since;
    }

    public static function YoutubeVideoInfo($video_id)
    {
        $url = 'https://www.googleapis.com/youtube/v3/videos?id=' . $video_id . '&key=AIzaSyDYwPzLevXauI-kTSVXTLroLyHEONuF9Rw&part=snippet,contentDetails';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);
        //print_t($response_a); if you want to get all video details
        $pattern = '/PT(\d+)M(\d+)S/';
        $VidDuration = $response_a->items[0]->contentDetails->duration;
        preg_match($pattern, $VidDuration, $matches);
//        $seconds = $matches[1] * 60 + $matches[2]; return seconds
        if (strlen($matches[2]) == 1) {
            $matches[2] = '0' . $matches[2];
        }
        $duration = $matches[1] . ':' . $matches[2];
        return $duration;
    }

    /**
     * [createAlias description]
     * @param string $string [description]
     * @return [type]         [description]
     */
    public static function createAlias($string = '')
    {
        $replacement = '-';
        $map = array();
        $quotedReplacement = preg_quote($replacement, '/');
        $default = array(
            '/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|å/' => 'a',
            '/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|ë/' => 'e',
            '/ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ|î/' => 'i',
            '/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|ø/' => 'o',
            '/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|ů|û/' => 'u',
            '/ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ/' => 'y',
            '/đ|Đ/' => 'd',
            '/ç/' => 'c',
            '/ñ/' => 'n',
            '/ä|æ/' => 'ae',
            '/ö/' => 'oe',
            '/ü/' => 'ue',
            '/Ä/' => 'Ae',
            '/Ü/' => 'Ue',
            '/Ö/' => 'Oe',
            '/ß/' => 'ss',
            '/[^\s\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
            '/\\s+/' => $replacement,
            sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
        );
        //Some URL was encode, decode first
        $string = urldecode($string);
        $map = array_merge($map, $default);
        return strtolower(preg_replace(array_keys($map), array_values($map), $string));
    }

    public static function smsKhongDau($string = '')
    {
        $replacement = ' ';
        $map = array();
        $quotedReplacement = preg_quote($replacement, '/');
        $default = array(
            '/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|å/' => 'a',
            '/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/' => 'A',
            '/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|ë/' => 'e',
            '/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/' => 'E',
            '/ì|í|ị|ỉ|ĩ|î/' => 'i',
            '/Ì|Í|Ị|Ỉ|Ĩ/' => 'I',
            '/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|ø/' => 'o',
            '/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/' => 'O',
            '/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|ů|û/' => 'u',
            '/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/' => 'U',
            '/ỳ|ý|ỵ|ỷ|ỹ/' => 'y',
            '/Ỳ|Ý|Ỵ|Ỷ|Ỹ/' => 'Y',
            '/đ/' => 'd',
            '/Đ/' => 'D',
            '/ç/' => 'c',
            '/ñ/' => 'n',
            '/ä|æ/' => 'ae',
            '/ö/' => 'oe',
            '/ü/' => 'ue',
            '/Ä/' => 'Ae',
            '/Ü/' => 'Ue',
            '/Ö/' => 'Oe',
            '/ß/' => 'ss',
//            '/[^\s\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
            '/\\s+/' => $replacement,
            sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
        );
        //Some URL was encode, decode first
        $string = urldecode($string);
        $map = array_merge($map, $default);
        return preg_replace(array_keys($map), array_values($map), $string);
    }

    // kiểm tra xem ký tự $word có tồn tại trong chuỗi $str hay không, nếu có trả về true
    public static function containsWord($str, $word)
    {
        return !!preg_match('#\\b' . preg_quote($word, '#') . '\\b#i', $str);
    }

    public static function randomString($length = 5)
    {
        return substr(str_shuffle(implode(array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9)))), 0, $length);
    }

    public static function randomStringLowercase($length = 5)
    {
        return substr(str_shuffle(implode(array_merge(range('a', 'z'), range(0, 9)))), 0, $length);
    }

    public static function urlImageExists($url)
    {
        if (!$fp = curl_init($url)) {
            return false;
        }
        return true;
    }

    public static function formatPhoneNumber($phone)
    {
        $phone = preg_replace("/[^0-9]/", "", $phone);
        if (strlen($phone) == 11) {
            return preg_replace("/(\d{2})(\d{2})(\d{3})(\d{4})/", "(+$1) $2 $3 $4", $phone);
        } elseif (strlen($phone) == 10) {
            return preg_replace("/(\d{3})(\d{3})(\d{4})/", "($1) $2 $3", $phone);
        } elseif (strlen($phone) == 9) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})/", "(+84) $1 $2 $3", $phone);
        } else {
            return $phone;
        }
    }

    public static function remove_numbers($string)
    {
        return preg_replace('/[0-9]+/', null, $string);
    }

    public static function get_numbers($string)
    {
        return preg_replace("/[^0-9\.]/", null, $string);
    }

    public static function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'năm',
            'm' => 'tháng',
            'w' => 'tuần',
            'd' => 'ngày',
            'h' => 'giờ',
            'i' => 'phút',
            's' => 'giây',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }
        return $string ? implode(', ', $string) . ' trước' : 'vừa đây';
    }
}
