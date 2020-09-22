<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 15-Apr-19
 * Time: 3:19 PM
 */

namespace common\grid;

use yii\grid\GridView;
use yii\helpers\Html;

class MyGridView extends GridView
{
    public $myOptions = ['class' => "grid-content"];

    public function renderItems()
    {
        $caption = $this->renderCaption();
        $columnGroup = $this->renderColumnGroup();
        $tableHeader = $this->showHeader ? $this->renderTableHeader() : false;
        $tableBody = $this->renderTableBody();

        $tableFooter = false;
        $tableFooterAfterBody = false;

        if ($this->showFooter) {
            if ($this->placeFooterAfterBody) {
                $tableFooterAfterBody = $this->renderTableFooter();
            } else {
                $tableFooter = $this->renderTableFooter();
            }
        }

        $content = array_filter([
            $caption,
            $columnGroup,
            $tableHeader,
            $tableFooter,
            $tableBody,
            $tableFooterAfterBody,
        ]);

        return Html::tag('div', implode("\n", $content), $this->tableOptions);
    }

    /**
     * Xây dựng lại Gridview
     * @return string the rendering result.
     */
    public function renderTableHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        if ($this->filterPosition === self::FILTER_POS_HEADER) {
            $content = $this->renderFilters() . $content;
        } elseif ($this->filterPosition === self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
        }

        return "<div class='grid-header'><table>" . $this->renderColGroup() . "<thead>\n" . $content . "\n</thead></table></div>";
    }

    public function renderTableBody()
    {
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            if ($this->beforeRow !== null) {
                $row = call_user_func($this->beforeRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }

            $rows[] = $this->renderTableRow($model, $key, $index);

            if ($this->afterRow !== null) {
                $row = call_user_func($this->afterRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
        }

        if (empty($rows) && $this->emptyText !== false) {
            $colspan = count($this->columns);
            $myContent = "<table>" . $this->renderColGroup() . "<tbody>\n<tr><td colspan=\"$colspan\">" . $this->renderEmpty() . "</td></tr>\n</tbody></table>";
        } else {
            $myContent = "<table>" . $this->renderColGroup() . "<tbody>\n" . implode("\n", $rows) . "\n</tbody></table>";
        }
        return Html::tag('div', $myContent, $this->myOptions);
    }

    public function renderTableFooter()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderFooterCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        if ($this->filterPosition === self::FILTER_POS_FOOTER) {
            $content .= $this->renderFilters();
        }

        return "<div class='grid-footer'><table>" . $this->renderColGroup() . "<thead>\n" . $content . "\n</thead></table></div>";
    }

    public function renderColGroup()
    {
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cols = [];
            foreach ($this->columns as $col) {
                $cols[] = Html::tag('col', '', $col->options);
            }

            return Html::tag('colgroup', implode("\n", $cols));
        }

        return false;
    }
}
