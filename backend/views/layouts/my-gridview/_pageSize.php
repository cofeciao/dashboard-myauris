<?php

use backend\components\MyComponent;

?>
<div class="pageSize pull-right mr-1">
    <select id="page-size-widget" class="page-size-widget ui dropdown" data-pjax="0">
        <option value="10" <?php if (MyComponent::hasCookies('pageSize') && MyComponent::getCookies('pageSize') == '10') {
    echo 'selected';
} ?>>
            10
        </option>
        <option value="20" <?php if (MyComponent::hasCookies('pageSize') && MyComponent::getCookies('pageSize') == '20') {
    echo 'selected';
} ?>>
            20
        </option>
        <option value="50" <?php if (MyComponent::hasCookies('pageSize') && MyComponent::getCookies('pageSize') == '50') {
    echo 'selected';
} ?>>
            50
        </option>
        <option value="100" <?php if (MyComponent::hasCookies('pageSize') && MyComponent::getCookies('pageSize') == '100') {
    echo 'selected';
} ?>>
            100
        </option>
        <option value="200" <?php if (MyComponent::hasCookies('pageSize') && MyComponent::getCookies('pageSize') == '200') {
    echo 'selected';
} ?>>
            200
        </option>
    </select>
    <label for="SmallSelect">dữ liệu</label>
</div>