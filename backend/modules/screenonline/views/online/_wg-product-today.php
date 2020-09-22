<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 6/23/2020
 * Time: 14:44
 */
?>
<div class="card bg-gradient-directional-primary mb-1">
    <div class="card-body p-0">
        <div class="table-wrap">
            <div class="table-responsive">
                <table class="table table-hovered mb-0">
                    <tbody>
                    <?php
                    arsort($products);
                    foreach ($products as $key => $value) {
                        ?>
                        <tr>
                            <td class="text-left"><span class="font-16"><?= $value['name']; ?></span></td>
                            <td class="text-center"><span class="font-18"><?= $value['sl']; ?></span></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
