<?php
/**
 * Created by PhpStorm.
 * User: abc
 * Date: 4/1/2020
 * Time: 2:55 PM
 */

namespace common\models;


use yii\base\Exception;

class MoneyToTextModel
{
    private $ten = 'mươi';
    private $hundred = 'trăm';
    private $odd = 'lẻ';

    private $arr_text_num = ['không', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];
    private $ten_f = 'mười';
    private $one_second = 'mốt';
    private $text_number = '';

    /*
    Array convert ra được sẽ như vậy. Mỗi cấp là $billion.='tỷ'
    Array
(
    [0] => Array
        (
            [0] => 1
            [1] => 034
            [2] => 254
        )

    [1] => Array
        (
            [0] => 355
            [1] => 645
            [2] => 330
        )

    [2] => Array
        (
            [0] => 355
            [1] => 645
            [2] => 330
        )

)*/
    private $number;

    /**
     *
     * Convert money to text general function
     *
     * */
    public function convertMoneyToText($number)
    {
        $arr_split = [];
        $num_len = strlen($number);
        if (($num_len % 3) != 0) {
            $arr_split[] = substr($number, 0, $num_len % 3);
            $number = substr($number, $num_len % 3, $num_len);
        }
        if (strlen($number) != 0) {
            $arr_split = array_merge($arr_split, str_split($number, 3));
        }
        $arr_splited = $this->chunk_array_splitted($arr_split);

        $this->convertToText($arr_splited);
    }

    /**
     *Chuck array to seperated part with format:
     *Array
     *(
     *[0] => Array
     *        (
     *        [0] => 1
     *        )
     *[1] => Array
     *        (
     *        [0] => 355
     *       [1] => 645
     *        [2] => 330
     *       )
     *[2] => Array
     *        (
     *        [0] => 355
     *        [1] => 645
     *        [2] => 330
     *        )
     * */
    public function chunk_array_splitted($arr)
    {
        $res = [];
        if (count($arr) % 3 != 0) {
            $array_chunk = array_chunk($arr, count($arr) % 3);
            $res = $array_chunk[0];
            for ($i = 0; $i <= count($arr) % 3; $i++) {
                unset($arr[$i]);
            }
        }
        if(!empty($res)){
            $res = array_merge([$res], array_chunk($arr, 3));

        }else{
            $res = array_merge(array_chunk($arr, 3));

        }


        return $res;
    }

    private $arr_digit_zero = ['triệu', 'ngàn', ''];
    private $billion = 'tỷ';

    public function convertToText($arr, $count = 0)
    {
        if (!empty($arr)) {
            foreach ($arr as $index => $value) {
                if (!is_array($value)) {
                    $str = !empty($this->arr_digit_zero[$index + 3 % count($arr)]) ? $this->arr_digit_zero[$index + 3 % count($arr)] . ' ' : $this->arr_digit_zero[$index + 3 % count($arr)];
                    $converted = $this->convert($value);
                    if (!empty($converted)) {
                        $this->text_number .= $converted . ' ' . $str;
                    }
                } else {
                    if ($count == 0) {
                        $count = count($arr);
                    } else {
                        $billion = '';
                        for ($i = 1; $i < $count; $i++) {
                            $billion .= $this->billion;

                        }
                        $this->text_number .= $billion . ' ';
                        $count--;
                    }
                    $this->convertToText($arr[$index], $count);
                }
            }
        } else {
            return [];
        }
    }

    public $array_temp = [];

    public function convert($number)
    {
        $strlen = strlen($number);
        if ($strlen > 2) {

            $threedigit = substr($number, 0, 3);

            $twodigit = substr($threedigit, 1, 2);

            $textnum = $threedigit != 0 ? $this->convertThreeDigit($threedigit, $strlen) : '';
            $textnum .= $twodigit != 0 ? ' ' . $this->convertTwoDigit($twodigit, $strlen) : '';
        } else {
            $textnum = $this->convertTwoDigit($number, $strlen);
        }
        return $textnum;
    }


    /**
     * Convert three digits into text
     * */
    public function convertThreeDigit($number, $strlen)
    {
        $text_number = '';
        if ($strlen > 2) {
            $num_temp = substr($number, 0, 1);
            $text_number .= $this->arr_text_num[$num_temp] . ' ' . $this->hundred;
        } else {
            $text_number = $this->convertTwoDigit($number, $strlen);
        }
        return $text_number;
    }

    /**
     *
     * Convert two digits into text
     *
     */
    private function convertTwoDigit($number, $strlen)
    {

        $len = strlen($number);
        $text_number = '';
        switch ($len) {
            case 1:
                $text_number = $this->arr_text_num[$number];
                break;
            case 2:
                if ($strlen < 3) {
                    //char 1
                    $f_char = substr($number, 0, 1);
                    //case 10
                    if ($f_char == 1) {
                        $text_number .= $this->ten_f;
                    } else {
                        $text_number .= $this->arr_text_num[$f_char];
                    }
                    //char 2
                    $s_char = $number % 10;
                    //case 20->99
                    if ($s_char != 0 && $f_char != 1) {
                        $text_number .= ' ' . $this->ten;
                        if ($s_char != 1) {
                            //hai mươi 2 -> 9
                            $text_number .= ' ' . $this->arr_text_num[$s_char];
                        } else {
                            //hai mươi mốt
                            $text_number .= ' ' . $this->one_second;
                        }
                        //case 20 30 40
                    } elseif ($s_char == 0 && $f_char != 1) {
                        $text_number .= ' ' . $this->ten;
                        //case 11->19
                    } elseif ($f_char == 1 && $s_char != 0) {
                        $text_number .= ' ' . $this->arr_text_num[$s_char];
                        //case 10
                    } else {
                        $text_number .= '';
                    }
                    //case more than 2 digit in general
                } else {
                    $f_char = substr($number, 0, 1);

                    $s_char = substr($number, 1, 1);

                    if ($f_char == 0 && $s_char != 0) {
                        $text_number .= $this->odd . ' ' . $this->arr_text_num[$s_char];
                    } elseif ($f_char != 0) {

                        //char 1
                        $f_char = substr($number, 0, 1);
                        //case 10
                        if ($f_char == 1) {
                            $text_number .= $this->ten_f;
                        } else {
                            $text_number .= $this->arr_text_num[$f_char];
                        }
                        //char 2
                        $s_char = $number % 10;
                        //case 20->99
                        if ($s_char != 0 && $f_char != 1) {
                            $text_number .= ' ' . $this->ten;
                            if ($s_char != 1) {
                                //hai mươi 2 -> 9
                                $text_number .= ' ' . $this->arr_text_num[$s_char];
                            } else {
                                //hai mươi mốt
                                $text_number .= ' ' . $this->one_second;
                            }
                            //case 20 30 40
                        } elseif ($s_char == 0 && $f_char != 1) {
                            $text_number .= ' ' . $this->ten;
                            //case 11->19
                        } elseif ($f_char == 1 && $s_char != 0) {
                            $text_number .= ' ' . $this->arr_text_num[$s_char];
                            //case 10
                        } else {
                            $text_number .= '';
                        }
                    }

                }
                break;
        }
        return $text_number;
    }


    public function getTextNumber($number)
    {
        $this->number = (int)str_replace([',', '.'], '', $number);

        if (is_numeric($this->number)) {
            $this->convertMoneyToText($this->number);
        } else {
            echo(("Không phải là số"));
        }
        return $this->text_number;
    }


}