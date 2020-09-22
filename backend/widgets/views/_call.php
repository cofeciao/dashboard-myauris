<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 21-Feb-19
 * Time: 3:12 PM
 */

?>
<div id="queue">
    <div class="call-buttons"></div><div class="call-list"></div>
</div>
<div class="my-call-header">
    <div class="phone-call-info">
        <div class="customer">Khách hàng: <span id="html-customer">Trống</span></div>
        <div class="employee">Phụ trách: <span id="html-nhanvien">Trống</span></div>
    </div>
    <div class="my-call-screen"><textarea></textarea></div>
    <div class="status"></div>
</div>
<div class="my-call-main">
    <div class="my-call-keyboard">
        <div class="my-call-row">
            <div class="my-call-button">
                <div class="button" data-button="7">7</div>
            </div>
            <div class="my-call-button">
                <div class="button" data-button="8">8</div>
            </div>
            <div class="my-call-button">
                <div class="button" data-button="9">9</div>
            </div>
        </div>
        <div class="my-call-row">
            <div class="my-call-button">
                <div class="button" data-button="4">4</div>
            </div>
            <div class="my-call-button">
                <div class="button" data-button="5">5</div>
            </div>
            <div class="my-call-button">
                <div class="button" data-button="6">6</div>
            </div>
        </div>
        <div class="my-call-row">
            <div class="my-call-button">
                <div class="button" data-button="1">1</div>
            </div>
            <div class="my-call-button">
                <div class="button" data-button="2">2</div>
            </div>
            <div class="my-call-button">
                <div class="button" data-button="3">3</div>
            </div>
        </div>
        <div class="my-call-row">
            <div class="my-call-button">
                <div class="button button-clear" data-button="clear">Xoá</div>
            </div>
            <div class="my-call-button">
                <div class="button" data-button="0">0</div>
            </div>
            <div class="my-call-button">
                <div class="button button-delete" data-button="delete"><span class="mobile-delete"></span></div>
            </div>
        </div>
    </div>
</div>
<div class="my-call-footer">
    <div class="my-call-tools my-call-row">
        <div class="my-call-button">
            <div class="button-tools button-phone-call" data-button="phoneCall"><span class="phone-call"></span></div>
        </div>
        <div class="my-call-button">
            <div class="button-tools button-phone-down" data-button="phoneDown"><span class="phone-down"></span>
            </div>
        </div>
    </div>
</div>
<audio id="my-call-audio"><source src="https://cdn.myauris.vn/assets/call/audio/phone-ring.mp3" type="audio/mpeg"></audio>


