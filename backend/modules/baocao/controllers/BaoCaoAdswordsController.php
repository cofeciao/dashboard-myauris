<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22-Apr-19
 * Time: 3:30 PM
 */

namespace backend\modules\baocao\controllers;

use backend\components\MyController;
use backend\modules\baocao\components\BaoCaoFacebook;
use backend\modules\baocao\models\BaocaoChayAdsAdswords;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use common\models\User;
use yii\db\Query;
use yii\web\Response;

class BaoCaoAdswordsController extends MyController
{
    public function actionIndex()
    {
        return $this->render('index', []);
    }


    /*
     * Lấy ra data chạy adsword
     */
    public function actionGetData()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $startDateReport             = strtotime(\Yii::$app->request->post('startDateReport'));
            $endDateReport               = strtotime(\Yii::$app->request->post('endDateReport'));
            $post_type                   = !empty(\Yii::$app->request->post('post_type')) ? \Yii::$app->request->post('post_type') : 1;

            $metadata = !empty(\Yii::$app->request->post('meta_name')) ? \Yii::$app->request->post('meta_name') : 'amount_money';

            $result = $this->getData($startDateReport, $endDateReport, $post_type, $metadata);

            return $result;
        }
    }

    protected function getData($from, $to, $post_type = 1, $metadata = 'amount_money')
    {
        $data = [];


        //data Adwords combined chart.

        $query = BaocaoChayAdsAdswords::find();

        if (!empty($from) && !empty($to)) {
            $query = $query->where(['between', 'ngay_tao', $from, $to])->orderBy('ngay_tao');
        }
        if (!empty($post_type)) {
            //            $query = $query->andFilterWhere(['post_type' => $post_type]);
        }
        $query = $query->asArray()->all();

        $data['dataKey'] = \Yii::$app->controller->module->params['adsword-type'][$post_type];

        //get name metadata
        $attribute_labels                 = BaocaoChayAdsAdswords::instance()->attributeLabels();
        $data['dataSet']['meta']['label'] = $attribute_labels[$metadata];


        //data Date
        for ($cur = $from; $cur <= $to; $cur += (86400)) {
            //timestamp
            $data['dataDate'][] = $cur;
        }
        //value metadata  : amount_money
        if (!empty($query)) {
            foreach ($query as $item) {
                $data['dataSet']['meta']['value'][$item['ngay_tao']] = $item[$metadata];
            }
        }

        //--------------------done data combined chart.


        //data ketqua, lich.
        $query = Dep365CustomerOnline::find();
        if (!empty($from) && !empty($to)) {
            $query = $query->where(['between', 'ngay_tao', $from, $to])->orderBy('ngay_tao');
        }
        $query = $query->asArray()->all();

        //init label and value chart
        $this->chartLabelInitialize($data);


        //data nguon` online
        if (!empty($query)) {
            foreach ($query as $item) {
                //4:hotline
                //10: affiliate
                //2:zalo
                //3:website
                //11:messweb
                // 12 : HotlineSEO
                if (in_array($item['nguon_online'], [4, 10, 2, 3, 11, 12])) {
                    if (isset($data['dataDate']) && in_array($item['ngay_tao'], $data['dataDate'])) {
                        $this->addLichdata($data['dataSet'], $item);
                        switch ($item['nguon_online']) {
                            case 4:
                                if (!isset($data['dataSet']['hotline']['value'][$item['ngay_tao']])) {
                                    $data['dataSet']['hotline']['value'][$item['ngay_tao']] = 1;
                                } else {
                                    $data['dataSet']['hotline']['value'][$item['ngay_tao']]++;
                                }
                                $this->addLichdata($data['dataSet']['hotline'], $item);
                                break;
                            case 10:
                                if (!isset($data['dataSet']['affiliate']['value'][$item['ngay_tao']])) {
                                    $data['dataSet']['affiliate']['value'][$item['ngay_tao']] = 1;
                                } else {
                                    $data['dataSet']['affiliate']['value'][$item['ngay_tao']]++;
                                }
                                $this->addLichdata($data['dataSet']['affiliate'], $item);

                                break;
                            case 2:
                                if (!isset($data['dataSet']['zalo']['value'][$item['ngay_tao']])) {
                                    $data['dataSet']['zalo']['value'][$item['ngay_tao']] = 1;
                                } else {
                                    $data['dataSet']['zalo']['value'][$item['ngay_tao']]++;
                                }
                                $this->addLichdata($data['dataSet']['zalo'], $item);

                                break;
                            case 3:
                                if (!isset($data['dataSet']['website']['value'][$item['ngay_tao']])) {
                                    $data['dataSet']['website']['value'][$item['ngay_tao']] = 1;
                                } else {
                                    $data['dataSet']['website']['value'][$item['ngay_tao']]++;
                                }
                                $this->addLichdata($data['dataSet']['website'], $item);

                                break;
                            case 11:
                                if (!isset($data['dataSet']['mess']['value'][$item['ngay_tao']])) {
                                    $data['dataSet']['mess']['value'][$item['ngay_tao']] = 1;
                                } else {
                                    $data['dataSet']['mess']['value'][$item['ngay_tao']]++;
                                }
                                $this->addLichdata($data['dataSet']['mess'], $item);

                                break;
                            case 12:
                                if (!isset($data['dataSet']['hotlineSEO']['value'][$item['ngay_tao']])) {
                                    $data['dataSet']['hotlineSEO']['value'][$item['ngay_tao']] = 1;
                                } else {
                                    $data['dataSet']['hotlineSEO']['value'][$item['ngay_tao']]++;
                                }
                                $this->addLichdata($data['dataSet']['hotlineSEO'], $item);

                                break;
                        }
                    }
                }
            }
        }


        //fill empty date does not have value
        if (!empty($data['dataDate'])) {
            foreach ($data['dataDate'] as $date) {
                //data metadata
                if (!isset($data['dataSet']['meta']['value'][$date])) {
                    $data['dataSet']['meta']['value'][$date] = 0;
                }
                if (!isset($data['dataSet']['dathen']['value'][$date])) {
                    $data['dataSet']['dathen']['value'][$date] = 0;
                }
                //status dat_hen
                if (!isset($data['dataSet']['dathen']['status']['khongden']['value'][$date])) {
                    $data['dataSet']['dathen']['status']['khongden']['value'][$date] = 0;
                }
                if (!isset($data['dataSet']['dathen']['status']['lam']['value'][$date])) {
                    $data['dataSet']['dathen']['status']['lam']['value'][$date] = 0;
                }
                if (!isset($data['dataSet']['dathen']['status']['khonglam']['value'][$date])) {
                    $data['dataSet']['dathen']['status']['khonglam']['value'][$date] = 0;
                }
                if (!isset($data['dataSet']['fail']['value'][$date])) {
                    $data['dataSet']['fail']['value'][$date] = 0;
                }
                if (!isset($data['dataSet']['kbm']['value'][$date])) {
                    $data['dataSet']['kbm']['value'][$date] = 0;
                }

                //ket qua
                if (!isset($data['dataSet']['hotline']['value'][$date])) {
                    $data['dataSet']['hotline']['value'][$date] = 0;
                }
                if (!isset($data['dataSet']['affiliate']['value'][$date])) {
                    $data['dataSet']['affiliate']['value'][$date] = 0;
                }
                if (!isset($data['dataSet']['zalo']['value'][$date])) {
                    $data['dataSet']['zalo']['value'][$date] = 0;
                }
                if (!isset($data['dataSet']['website']['value'][$date])) {
                    $data['dataSet']['website']['value'][$date] = 0;
                }
                if (!isset($data['dataSet']['mess']['value'][$date])) {
                    $data['dataSet']['mess']['value'][$date] = 0;
                }
                $this->fillEmptyDateDataDathen($data['dataSet']['hotline'], $date);
                $this->fillEmptyDateDataDathen($data['dataSet']['affiliate'], $date);
                $this->fillEmptyDateDataDathen($data['dataSet']['zalo'], $date);
                $this->fillEmptyDateDataDathen($data['dataSet']['website'], $date);
                $this->fillEmptyDateDataDathen($data['dataSet']['mess'], $date);
            }
        }
        //-----------------done data detail ketqua, lich.

        //data dat hen

        //--------------------done data dathen

        return $data;
    }

    public function chartLabelInitialize(&$data)
    {
        $list_source_online_label = Dep365CustomerOnline::getNguonCustomerOnline();

        //        $data['dataSet']['lich']['label'] = 'Lịch';
        $data['dataSet']['hotline']['label']           = isset($list_source_online_label[4]) ? $list_source_online_label[4] : null;
        $data['dataSet']['hotline']['dathen']['value'] = null;
        $data['dataSet']['hotline']['fail']['value']   = null;
        $data['dataSet']['hotline']['kbm']['value']    = null;
        //        $data['dataSet']['affiliate']['label']            = $list_source_online_label[10];
        $data['dataSet']['affiliate']['label']           = 'Số Điện Thoại';
        $data['dataSet']['affiliate']['dathen']['value'] = null;
        $data['dataSet']['affiliate']['fail']['value']   = null;
        $data['dataSet']['affiliate']['kbm']['value']    = null;
        $data['dataSet']['zalo']['label']                = isset($list_source_online_label[2]) ? $list_source_online_label[2] : null;
        $data['dataSet']['zalo']['dathen']['value']      = null;
        $data['dataSet']['zalo']['fail']['value']        = null;
        $data['dataSet']['zalo']['kbm']['value']         = null;
        //        $data['dataSet']['website']['label']           = $list_source_online_label[3];
        $data['dataSet']['website']['label']           = "Đặt lịch";
        $data['dataSet']['website']['dathen']['value'] = null;
        $data['dataSet']['website']['fail']['value']   = null;
        $data['dataSet']['website']['kbm']['value']    = null;

        $data['dataSet']['mess']['label']              = isset($list_source_online_label[11]) ? $list_source_online_label[11] : null;
        $data['dataSet']['mess']['dathen']['value']    = null;
        $data['dataSet']['mess']['fail']['value']      = null;
        $data['dataSet']['mess']['kbm']['value']       = null;

        /**
         * Holine SEO : 12
         */
        $data['dataSet']['hotlineSEO']['label']              = "Hotline SEO"; //isset($list_source_online_label[12]) ? $list_source_online_label[12] : null;
        $data['dataSet']['hotlineSEO']['dathen']['value']    = null;
        $data['dataSet']['hotlineSEO']['fail']['value']      = null;
        $data['dataSet']['hotlineSEO']['kbm']['value']       = null;

        $data['dataSet']['dathen']['label']                       = 'Đặt Hẹn';
        $data['dataSet']['dathen']['status']['lam']['label']      = 'Làm';
        $data['dataSet']['dathen']['status']['khonglam']['label'] = 'Không làm';
        $data['dataSet']['dathen']['status']['khongden']['label'] = 'Không đến';

        $data['dataSet']['fail']['label'] = 'Fail';
        $data['dataSet']['kbm']['label']  = 'Không bắt máy';
    }


    public function fillEmptyDateDataDathen(&$data, $date)
    {
        if (!isset($data['dathen']['value'][$date])) {
            $data['dathen']['value'][$date] = 0;
        }
        if (!isset($data['fail']['value'][$date])) {
            $data['fail']['value'][$date] = 0;
        }
        if (!isset($data['kbm']['value'][$date])) {
            $data['kbm']['value'][$date] = 0;
        }
    }


    public function addLichdata(&$data, $item)
    {
        switch ($item['status']) {
            case 1:
                if (!isset($data['dathen']['value'][$item['ngay_tao']])) {
                    $data['dathen']['value'][$item['ngay_tao']] = 1;
                } else {
                    $data['dathen']['value'][$item['ngay_tao']]++;
                }
                switch ($item['dat_hen']) {
                    case 1:
                        //khach den'
                        break;
                    case 2:
                        if (!isset($data['dathen']['status']['khongden']['value'][$item['ngay_tao']])) {
                            $data['dathen']['status']['khongden']['value'][$item['ngay_tao']] = 1;
                        } else {
                            $data['dathen']['status']['khongden']['value'][$item['ngay_tao']]++;
                        }
                        break;
                }
                switch ($item['customer_come_time_to']) {
                    case 1:
                        if (!isset($data['dathen']['status']['lam']['value'][$item['ngay_tao']])) {
                            $data['dathen']['status']['lam']['value'][$item['ngay_tao']] = 1;
                        } else {
                            $data['dathen']['status']['lam']['value'][$item['ngay_tao']]++;
                        }
                        break;
                    case 2:
                        if (!isset($data['dathen']['status']['khonglam']['value'][$item['ngay_tao']])) {
                            $data['dathen']['status']['khonglam']['value'][$item['ngay_tao']] = 1;
                        } else {
                            $data['dathen']['status']['khonglam']['value'][$item['ngay_tao']]++;
                        }
                        break;
                }
                break;
            case 2:
                if (!isset($data['fail']['value'][$item['ngay_tao']])) {
                    $data['fail']['value'][$item['ngay_tao']] = 1;
                } else {
                    $data['fail']['value'][$item['ngay_tao']]++;
                }
                break;
            case 3:
                if (!isset($data['kbm']['value'][$item['ngay_tao']])) {
                    $data['kbm']['value'][$item['ngay_tao']] = 1;
                } else {
                    $data['kbm']['value'][$item['ngay_tao']]++;
                }
                break;
        }
    }
}
