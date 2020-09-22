<?php

namespace backend\modules\chi\filter;

use yii\base\Model;
use yii\grid\DataColumn;
use yii\helpers\Html;

/**
 * Class AppendFilterColumn
 * [
 *      'class' => 'backend\modules\chi\filter',
 *      append more html after filter input
 * ]
 * @package backend\modules\chi\filter
 */
class AppendFilterColumn extends DataColumn {

	protected function renderFilterCellContent() {
		if ( is_string( $this->filter ) ) {
			return $this->filter;
		}

		$model = $this->grid->filterModel;

		if ( $this->filter !== false && $model instanceof Model && $this->attribute !== null && $model->isAttributeActive( $this->attribute ) ) {
			if ( $model->hasErrors( $this->attribute ) ) {
				Html::addCssClass( $this->filterOptions, 'has-error' );
				$error = ' ' . Html::error( $model, $this->attribute, $this->grid->filterErrorOptions );
			} else {
				$error = '';
			}
			$rawhtml = '';
			if ( isset( $this->filterOptions['rawhtml'] ) && ! empty( $this->filterOptions['rawhtml'] ) ) {
				$rawhtml = $this->filterOptions['rawhtml'];
			}
			if ( is_array( $this->filter ) ) {
				$options = array_merge( [ 'prompt' => '' ], $this->filterInputOptions );

				return Html::activeDropDownList( $model, $this->attribute, $this->filter, $options ) . $error;
			} elseif ( $this->format === 'boolean' ) {
				$options = array_merge( [ 'prompt' => '' ], $this->filterInputOptions );

				return Html::activeDropDownList( $model, $this->attribute, [
						1 => $this->grid->formatter->booleanFormat[1],
						0 => $this->grid->formatter->booleanFormat[0],
					], $options ) . $error;
			}

			return Html::activeTextInput( $model, $this->attribute, $this->filterInputOptions ) . $rawhtml . $error;
		}

		return parent::renderFilterCellContent();

	}
	/**
	 * @param mixed $model
	 * @param mixed $key
	 * @param int $index
	 *
	 * @return mixed
	 */

}
