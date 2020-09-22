<?php
$css = <<<CSS

.acc dd > p {
  padding: 1em 2em 1em 2em;
}

.container {
  max-width: 960px;
  margin: 0 auto;
  padding: 40px 0 0 0;
}


.acc_panel {
  height: auto;
  overflow: hidden;
}

@media all {
  .acc_panel {
    max-height: 50em;
    -webkit-transition: max-height 1s;
    transition: max-height 1s;
  }
}
@media screen and (min-width: 48em) {
  .acc_panel {
    max-height: 15em;
    -webkit-transition: max-height 0.5s;
    transition: max-height 0.5s;
  }
}
.acc_panel_col {
  max-height: 0;
}


@-webkit-keyframes acc_in {
  0% {
    opacity: 0;
    -webkit-transform: scale(0.8);
            transform: scale(0.8);
  }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
            transform: scale(1);
  }
}

@keyframes acc_in {
  0% {
    opacity: 0;
    -webkit-transform: scale(0.8);
            transform: scale(0.8);
  }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
            transform: scale(1);
  }
}
@-webkit-keyframes acc_out {
  0% {
    opacity: 1;
    -webkit-transform: scale(1);
            transform: scale(1);
  }
  100% {
    opacity: 0;
    -webkit-transform: scale(0.8);
            transform: scale(0.8);
  }
}
@keyframes acc_out {
  0% {
    opacity: 1;
    -webkit-transform: scale(1);
            transform: scale(1);
  }
  100% {
    opacity: 0;
    -webkit-transform: scale(0.8);
            transform: scale(0.8);
  }
}
CSS;
$this->registerCss($css);
$format = '<div class="acc">
    <dl>
        %1$s
    </dl>
</div>';
$items = '';
if (!empty($data)) {
    foreach ($data as $value) {
        $thoigian_batdau = !empty($value['thoi_gian_bat_dau']) ? Yii::$app->formatter->asDatetime($value['thoi_gian_bat_dau'], 'php:d:m:Y') : '';
        $thoigian_ketthuc = !empty($value['thoi_gian_ket_thuc']) ? Yii::$app->formatter->asDatetime($value['thoi_gian_ket_thuc'], 'php:d:m:Y') : '';
        $thoigian = $thoigian_batdau . '----' . $thoigian_ketthuc;
        switch ($value['status']) {
            case 1:
                $status = 'Hoàn Thành';
                break;
            default:
                $status = 'Chưa Hoàn Thành';
        }
        $items .= sprintf('<dt><a class="acc_title tieuchi" href="#">%1$s</a></dt>
        <dd class="acc_panel acc_panel_col">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <p class="card-text thoigian">Thời Hạn: %2$s</p>
                    <p class="card-text status">Trạng thái: %3$s</p>
                    <p href="#" class="nd_hoanthanh">Nội dung hoàn thành:%4$s</p>
                </div>
            </div>
        </dd>', $value['tieu_chi'], !empty($thoigian) ? $thoigian : '', $status, $value['nd_hoan_thanh']);
    }
}
printf($format, $items);
$js = <<<JS

( function( window ) {

'use strict';

// class helper functions from bonzo https://github.com/ded/bonzo

function classReg( className ) {
  return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
}

// classList support for class management
// altho to be fair, the api sucks because it won't accept multiple classes at once
var hasClass, addClass, removeClass;

if ( 'classList' in document.documentElement ) {
  hasClass = function( elem, c ) {
    return elem.classList.contains( c );
  };
  addClass = function( elem, c ) {
    elem.classList.add( c );
  };
  removeClass = function( elem, c ) {
    elem.classList.remove( c );
  };
}
else {
  hasClass = function( elem, c ) {
    return classReg( c ).test( elem.className );
  };
  addClass = function( elem, c ) {
    if ( !hasClass( elem, c ) ) {
      elem.className = elem.className + ' ' + c;
    }
  };
  removeClass = function( elem, c ) {
    elem.className = elem.className.replace( classReg( c ), ' ' );
  };
}

function toggleClass( elem, c ) {
  var fn = hasClass( elem, c ) ? removeClass : addClass;
  fn( elem, c );
}

var classie = {
  // full names
  hasClass: hasClass,
  addClass: addClass,
  removeClass: removeClass,
  toggleClass: toggleClass,
  // short names
  has: hasClass,
  add: addClass,
  remove: removeClass,
  toggle: toggleClass
};

// transport
if ( typeof define === 'function' && define.amd ) {
  // AMD
  define( classie );
} else {
  // browser global
  window.classie = classie;
}

})( window );

//fake jQuery
var $ = function(selector){
  return document.querySelector(selector);
}
var accordion = $('.acc');

//add event listener to all anchor tags with accordion title class
accordion.addEventListener("click",function(e) {
  e.stopPropagation();
  e.preventDefault();
  if(e.target && e.target.nodeName == "A") {
    var classes = e.target.className.split(" ");
    if(classes) {
      for(var x = 0; x < classes.length; x++) {
        if(classes[x] == "acc_title") {
          var title = e.target;

          //next element sibling needs to be tested in IE8+ for any crashing problems
          var content = e.target.parentNode.nextElementSibling;
          
          //use classie to then toggle the active class which will then open and close the accordion
         
          classie.toggle(title, 'acc_title_active');
          //this is just here to allow a custom animation to treat the content
          if(classie.has(content, 'acc_panel_col')) {
            if(classie.has(content, 'anim_out')){
              classie.remove(content, 'anim_out');
            }
            classie.add(content, 'anim_in');

          }else{
             classie.remove(content, 'anim_in');
             classie.add(content, 'anim_out');
          }
          //remove or add the collapsed state
          classie.toggle(content, 'acc_panel_col');
        }
      }
    }
    
  }
});



JS;

$this->registerJs($js);

?>



