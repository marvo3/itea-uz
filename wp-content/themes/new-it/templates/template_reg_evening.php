<?php /* Template Name: Заявка на вечерний курс */ ?>

<?php
/**
 * @param $segmentType
 * @return string
 */
function getAttrActionAndIdForForm($segmentType) {
    if ($segmentType == 'b2c_first_lesson') {
        return ' action="javascript:void(null);" id="for_payment" ';
    } else  {
        return 'action="' .esc_url(add_query_arg('action', 'regForEveningCourses', admin_url('admin-post.php'))). '"';
    }
}
if (empty($_POST['priseWithDiscount'])){
    $full_price = $_POST['full_price'];
    $full_price = (int)str_replace(array(" ", ".", ","), "", $full_price);
    $discount_p = $_POST['discount_price'];
    $discount_p = (int)str_replace(array(" ", ".", ","), "", $discount_p);
    $discount =  100-($discount_p/$full_price)*100;
    $totalPrice = $full_price - ($full_price / 100 * $discount);
    if ($discount == 100){
      $totalPrice = $_POST['full_price'];
      $discount = 0;
    }
} else {
    $totalPrice = $_POST['priseWithDiscount'];
}
if ( $totalPrice < 0 ){
  if(!empty($_POST['discount'])){
    $totalPrice = $_POST['discount'];
  }
  else {
    $totalPrice = 0;
  }
}
?>


<?
global $post;
$segment_type = str_replace('_step1', '', $post->post_name);
$course_id    = array_key_exists('course_id', $_POST) ? $_POST['course_id'] : 0;
$road_id      = array_key_exists('road_id',   $_POST) ? $_POST['road_id']   : 0;

$subtitle = '';
$hidensInputs = array();
$prprs = $_POST['parts_price'];
$prprs = preg_replace('/[a-z] [0-9]|[a-z][0-9]/','',$prprs);
$hidensInputs[] = (empty($_POST['price'])       ? '' : '<input type="hidden" data-parts_weeks="'. $_POST['parts_weeks'] .'" data-discount_right="'. $_POST['discount_right'].'" data-full_price="'. $_POST['full_price'] .'" data-discount_price="'. $_POST['discount_price'] .'" data-full_parts_price="'. $_POST['full_parts_price'] .'" data-new_parts_price="'. $prprs .'" data-discount="'. $_POST['discount'] .'" name="price" value="' .$_POST['full_price']. '">');
$hidensInputs[] = (empty($_POST['parts_price']) ? '' : '<input type="hidden" name="parts_price" value="'.$_POST['parts_price']. '">');
$hidensInputs[] = '<input type="hidden" name="discountFromSite" value="'.(($discount <= 0 ) ? 0 :round($discount,2)).'"><input type="hidden" name="priseWithDiscount" value="'.$totalPrice.'">';

switch ($segment_type) {
    case 'b2c_order':
    case 'b2c_free':
    case 'b2c_first_lesson':
        $hidensInputs[] = '<input type="hidden" name="course_id" value="' .$course_id. '">';

        if ($course_id)
        {
            $subtitle .= get_the_title($course_id);
        }

        break;
    case 'roadmap_order':
        $hidensInputs[] = '<input type="hidden" 
          name="road_id" value="' .$road_id. '" 
          data-roadmap-full-price="' . (($_POST['roadmap-full-price'] != $_POST['roadmap-part-price']) ? $_POST['roadmap-full-price'] : '')  . '" 
          data-roadmap-part-price="' . $_POST['roadmap-part-price'] . '"
          data-di-full="' . (empty($_POST['roadmap-di-full']) ? $discount : $_POST['roadmap-di-full']). '">';
          // $hidensInputs[] = '<input type="hidden" name="priseWithDiscount" value="' . $_POST['priseWithDiscount']. '">';                    
          $hidensInputs[] = '<input type="hidden" name="parts_price" value="' . $_POST['roadmap-part-price'] . ' x4">';                    

        if ($road_id)
        {
            $courseNames = array();
            if (array_key_exists('roadChoice_payOnce', $_POST)) {
                $courseSet = (array_key_exists('course-items', $_POST) ? $_POST['course-items'] : array());
                foreach($courseSet as $id) {
                    $courseNames[] = get_the_title((int)$id);
                }
                $courseSet = implode(', ', $courseSet);
                $hidensInputs[] = '<input type="hidden" name="course-items" value="' . $courseSet . '">';
                $hidensInputs[] = '<input type="hidden" name="parts_price" value="' . $_POST['roadmap-part-price'] . ' x4">';
                $hidensInputs[] = '<input type="hidden" name="course-items" value="' .$courseSet. '">';
                if ($_POST['checkboxed'] == 1){
                  // var_dump($_POST['full_price'],$_POST['roadChoice_price'],'foo3');
                  $hidensInputs[] = '<input type="hidden" data-parts_weeks="'. $_POST['parts_weeks'] .'" data-full_price="'. (($_POST['full_price']==$_POST['roadChoice_price']) ? ''  : $_POST['full_price']) .'" data-discount_price="'. $_POST['roadChoice_price'] .'"  data-new_parts_price="'. preg_replace('/[a-z] [0-9]|[a-z][0-9]/','',$_POST['roadmap-part-price']) .'" data-discount="'. $_POST['discount'] .'" name="price" value="' .((empty($_POST['full_price'])) ? $_POST['roadChoice_price']  : $_POST['full_price'] ). '">';
                } else {
                  
                  $hidensInputs[] = '<input type="hidden" data-parts_weeks="'. $_POST['parts_weeks'] .'" data-full_price="'. (($_POST['full_price']==$_POST['roadChoice_price']) ? '' : $_POST['full_price']) .'" data-discount_price="'. $_POST['roadChoice_price'] .'"  data-new_parts_price="'. preg_replace('/[a-z] [0-9]|[a-z][0-9]/','',$_POST['new_parts_price']) .'" data-discount="'. $_POST['discount'] .'" di-full="'. $_POST['discount'] .'" name="price" value="' .$_POST['full_price']. '">';
                  
                }
                $hidensInputs[] = '<input type="hidden" name="priseWithDiscount" value="' .$_POST['roadChoice_price']. '">';
                if (!empty($_POST['discont'])){
                  $hidensInputs[] = '<input type="hidden" name="discountFromSite" value="' . round($_POST['discont'],2). '">';
                }
               
            } elseif (array_key_exists('roadChoice_payPart', $_POST)) {
                $courseSet = (array_key_exists('course-items', $_POST) ? $_POST['course-items'] : array());
                foreach($courseSet as $id) {
                    $courseNames[] = get_the_title((int)$id);
                }
                $courseNames[] = 'в рассрочку';
                $courseSet = implode(', ', $courseSet);

                $hidensInputs[] = '<input type="hidden" name="course-items" value="' .$courseSet. '">';
                if ($_POST['checkboxed'] == 1){
                  $hidensInputs[] = '<input type="hidden" data-parts_weeks="'. $_POST['parts_weeks'] .'"  data-full_price="'. (($_POST['full_price'] == $_POST['roadChoice_parts_price']) ? '' : $_POST['full_price']) .'" data-discount_price="'. $_POST['roadChoice_parts_price'] .'"  data-new_parts_price="'. preg_replace('/[a-z] [0-9]|[a-z][0-9]/','',$_POST['parts_price']) .'" data-discount="'. $_POST['discont'] .'" name="price" value="' .((empty($_POST['full_price'])) ? $_POST['roadChoice_parts_price']: $_POST['full_price']).'">';
                  $hidensInputs[] = '<input type="hidden" name="priseWithDiscount" value="' .$_POST['roadChoice_parts_price']. '">';
                  $hidensInputs[] = '<input type="hidden" name="parts_price" value="' .$_POST['parts_price']. ' x4">';
                  
                } else {
                  $hidensInputs[] = '<input type="hidden" data-parts_weeks="'. $_POST['parts_weeks'] .'"  data-full_price="'. (($_POST['roadChoice_parts_price']==$_POST['price'])? ' ' : $_POST['roadChoice_parts_price']) .'" data-discount_price="'. $_POST['price'] .'"  data-new_parts_price="'. preg_replace('/[a-z] [0-9]|[a-z][0-9]/','',$_POST['roadChoice_parts_price']) .'" data-discount="'. $_POST['discont'] .'" name="price" value="' .$_POST['full_price']. '">';
                }
                
               
                
                
            }
            $courseNames = implode(', ', $courseNames);

            $subtitle .= get_cat_name($road_id) . (empty($courseNames) ? '' : ': '.$courseNames);
        }

        break;
    default:
        wp_redirect( get_permalink(11993) ); exit;
}

$hidensInputs = implode(' ', $hidensInputs);
?>

<?php
hideLangSwitchAndSetCorrectLang();
get_header();
$lang = (get_locale() == 'ru_RU');
?>

<section class="evening-form-reg">
  <div class="container">
    <div class="evening-form-reg__row">
      <?php

        if ($subtitle === '') {
          ?>
          <h1><?php echo $lang ? 'Форма записи на вечерний курс' : 'Форма запису на вечірній курс' ?></h1>
          <?php
        } else {
          ?>
          <h1><?php echo $lang ? 'Форма записи на курс' : 'Форма запису на курс' ?> «<?php echo $subtitle; ?>»</h1>
        <?php } ?>
    </div>
    <div class="evening-form-reg__row">
      <div class="evening-form-reg__left">

        <form method="POST" class="user-data-form" <?php echo getAttrActionAndIdForForm($segment_type); ?>>
          <input type="hidden" name="verification" value="<?php echo wp_create_nonce('ITEA_of_the_best!'); ?>">
          <input type="hidden" name="segment_type" value="<?php echo $segment_type; ?>">

          <?php
            if (!($road_id+$course_id)) {
              ?>
              <input type="hidden" name="price" value="">
            <?php } ?>

          <?php echo(isset($hidensInputs) ? $hidensInputs : ''); ?>

          <!--          <p class="b-courses-sing-up-hidden-tip">-->
          <!--            Будь ласка, заповніть всі обов'язкові поля форми-->
          <!--          </p>-->

          <div class="items">
            <?php
              if (!($road_id + $course_id)) {
                ?>
                <div class="user-data-form__item group">
                  <div class="user-data-form__item-list">Курс *</div>
                  <div id="courses-list">
                    <div class="courses-list-option-default">Выберите курс</div>
                    <div class="courses-list-set">
                      <input type="text" placeholder="Поиск">
                      <input name="course" type="text" style="display: none;">
                      <?php
                        $posts = get_posts([
                          'numberposts' => -1,
                          'category'    => '22',
                          'post_type'   => 'post',
                          'post_status' => 'publish',
                          'orderby'     => 'title',
                        ]);

                        $options_id = [];
                        foreach ($posts as $_post) {
                          $options_id[] = $_post->ID;
                          echo '<div class="courses-list-option">' . $_post->post_title . '</div>';
                        }
                      ?>
                    </div>
                  </div>
                </div>
                <?php
              }
            ?>

            <div class="user-data-form__item">
              <label for="name">Имя и Фамилия *</label>
              <input name="name" type="text" id="name" class="user-data-form__input-item">
              <input type="hidden" name="sourceUuid">
              <input type="hidden" name="utm_medium">
              <input type="hidden" name="utm_campaign">
              <input type="hidden" name="utm_content">
              <input type="hidden" name="utm_term">
                <input type="hidden" name="gclid">
                <input type="hidden" name="fbclid">
            </div>
            <div class="user-data-form__item">
              <label for="email">E-mail *</label>
              <input name="mail" type="email" id="email" class="user-data-form__input-item">
            </div>
            <div class="user-data-form__item">
              <label for="phone">Телефон *</label>
              <input name="phone" type="tel" id="phone" class="user-data-form__input-item">
            </div>
            <?php
              if( $road_id == 105 || $road_id == 226 ||
                in_category(105, $course_id) || in_category(226, $course_id)) {
                ?>
                <div class="group">
                  <input name="name_of_child" type="text">
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label><span class="red">* </span>Ім'я дитини</label>
                </div>
                <div class="group">
                  <input name="age_of_child" type="text">
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label><span class="red">* </span>Вік дитини</label>
                </div>
              <?php } ?>
            <div class="user-data-form__item user-data-form__item-comment">
              <label>
                <?php if ($lang) { ?>
                  Комментарий
                <?php } else { ?>
                  Коментар
                <?php } ?>
              </label>
              <textarea name="comment" type="text" class="user-data-form__input-item" rows="4" cols="50"> </textarea>
            </div>
            <div class="user-data-form__item user-data-form__item-button">
              <input type="submit" class="submit " value="<?php echo $lang ? 'Записаться' : 'Записатися'; ?>" />
            </div>
          </div>

          <style>
            #privacy-policy {
              width: 100%;
              display: flex;
              margin-top: 25px
            }

            #privacy-policy > label {
              padding-right: 10px;
              box-sizing: border-box;
              position: relative;
              display: block;
              top: 0;
              left: 0;
              margin: 0;
            }

            #privacy-policy > label > input {
              display: none;
            }

            #privacy-policy > label > span {
              display: block;
              width: 20px;
              height: 20px;
              box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
              border-radius: 3px;
              background-color: #ffffff;
              border: 1px solid #010101;
              position: relative;
              cursor: pointer;
            }

            #privacy-policy > label > input.error + span {
              border-color: #e61a4b;
              box-shadow: 0 2px 10px rgba(230, 26, 75, 0.2);
            }

            #privacy-policy > label > input.error:checked + span {
              border-color: #010101;
              box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            #privacy-policy > label > span:after {
              content: "";
              position: absolute;
              width: 11px;
              height: 5px;
              border: 2px solid #0bac7c;
              border-top: 0;
              border-right: 0;
              top: 42%;
              left: 50%;
              -webkit-transform: translate(-50%, -50%) rotate(-45deg);
              transform: translate(-50%, -50%) rotate(-45deg);
              display: none;
            }

            #privacy-policy > label > input:checked + span:after {
              display: block;
            }

            #privacy-policy > p, #privacy-policy > p > a {
              font-size: 11px;
              color: #606f7e;
              line-height: 16px;
            }

            #privacy-policy > p > a {
              color: #e61a4b;
            }
          </style>
          <div class="user-data-form__item-policy">
            <div id="privacy-policy">
              <label for="input-privacy-policy">
                <input type="checkbox" id="input-privacy-policy" name="inputPrivacyPolicy">
                <span></span>
              </label>
              <p>
                Подписанием и отправкой настоящей заявки я подтверждаю, что я ознакомлен с <br><a href="/politika-konfidentsialnosti/" target="_blank">Политикой конфиденциальности</a> и принимаю ее условия, включая регламентирующие обработку моих персональных данных, и согласен с ней.
              </p>
            </div>
          </div>
          <?php /*?><p class="hiddenText">
                            * Відправляючи заявку, я даю згоду на обробку моїх персональних даних.
                            Компанія ITEA зобов'язується не передавати цю інформацію 3-особам.
                        </p><?php /**/ ?>
        </form>
        <div class="evening-form-reg__price">
          <div class="evening-form-reg__price-wrapper">
            <div class="evening-form-reg__right-price">
              <h3><?php echo $lang ? 'Цена курса' : 'Ціна курса' ?></h3>

              <?php

                if ($subtitle !== '') {
                  ?>
                  <div class="evening-form-reg__right-price-block">
                    <div class="right-full-price">
                      <span class="right-full-price__course"></span>
                      <span class="right-discount-full-price__course"> </span> UZS.
                    </div>

                    <?php if ($_POST['parts_weeks'] !== '') { ?>

                      <div class="evening-part-price__title"><?php echo $lang ? 'Оплата по частям' : 'Оплата по частинам' ?></div>

                      <div class="right-part-price">
                        <span class="right-part-price__course"></span>
                        <span class="right-discount-part-price__course"> </span> UZS. <span class="partlyPay-x">x</span><span
                          class="partlyPay-weeks"><?php echo $weeks ?></span>
                      </div>

                    <?php } else { ?>
                      <div class="right-part-price">
                        <span class="partlyPay-x">Не предусмотрено </span>
                      </div>
                    <?php } ?>
                  </div>
                  <?php
                } else {
                  ?>
                  <div class="evening-form-reg__right-price-block  order-new-course">
                    <div class="right-full-price">
                      <span class="right-full-price__course"></span>
                      <span class="right-discount-full-price__course"> </span> UZS.
                    </div>
                    <div
                      class="evening-part-price__title"><?php echo $lang ? 'Оплата по частям' : 'Оплата по частинам' ?></div>
                    <?php if ($_POST['parts_weeks'] !== '') { ?>
                      <div class="right-part-price">
                        <span class="right-part-price__course"></span>
                        <span class="right-discount-part-price__course"> </span> UZS. <span class="partlyPay-x">x</span><span
                          class="partlyPay-weeks"><?php echo $weeks ?></span>
                      </div>

                    <?php } else { ?>
                      <div class="right-part-price">
                        <span class="partlyPay-x">Не предусмотрено </span>
                      </div>
                    <?php } ?>
                  </div>
                  <?php
                }
              ?>
            </div>
<!--            <div class="evening-form-reg__right-phone">-->
<!--              <a href="tel:+380445990179">+380 44 599 01 79</a>-->
<!--              <p>--><?php //echo $lang ? 'Свяжитесь с нами, если у вас возникли вопросы или предложения' : 'Зв\'яжіться з нами, якщо у вас виникли питання або пропозиції' ?><!--</p>-->
<!--            </div>-->
          </div>
        </div>
      </div>

      <div class="evening-form-reg__right">
        <div class="evening-form-reg__right-top">
          <h2><?php echo $lang ? 'Что вы получите' : 'Що ви отримаєте' ?></h2>
          <ul>
            <li
              class="test-level-evening"><?php echo $lang ? 'Тест на определение уровня' : 'Тест на визначення рівня' ?></li>
            <li
              class="sertification-evening"><?php echo $lang ? 'Сертификат об окончании' : 'Сертифікат про закінчення' ?></li>
            <li
              class="work-help-evening"><?php echo $lang ? 'Помощь в трудоустройстве' : 'Допомога в працевлаштуванні' ?></li>
          </ul>
          <ul>
            <li
              class="wallet-evening"><?php echo $lang ? 'Возможность оплаты частями' : 'Можливість оплати частинами' ?></li>
            <li
              class="train-evening"><?php echo $lang ? 'Стажировка в ИТ-компаниях лучшим студентам' : 'Стажування в ІТ-компаніях кращим студентам' ?></li>
            <li
              class="disscount-evening"><?php echo $lang ? 'Скидка -10% на следующий курс' : 'Знижка -10% на наступний курс' ?></li>
          </ul>
        </div>
        <div class="evening-form-reg__right-bottom">
          <div class="evening-form-reg__right-price">
            <h3><?php echo $lang ? 'Цена курса' : 'Ціна курса' ?></h3>


            <?php

              if ($subtitle !== '') {
                ?>
                <div class="evening-form-reg__right-price-block">
                  <div class="right-full-price">
                    <span class="right-full-price__course"></span>
                    <span class="right-discount-full-price__course"> </span> UZS.
                  </div>

                  <div class="evening-part-price__title"><?php echo $lang ? 'Оплата по частям' : 'Оплата по частинам' ?></div>
                  <?php if ($_POST['parts_weeks'] !== '') { ?>
                    <div class="right-part-price">
                      <span class="right-part-price__course"></span>
                      <span class="right-discount-part-price__course"> </span> UZS. <span
                        class="partlyPay-x">x</span><span class="partlyPay-weeks"><?php echo $weeks ?></span>
                    </div>
                  <?php } else { ?>
                    <div class="right-part-price">
                      <span class="partlyPay-x">Не предусмотрено </span>
                    </div>
                  <?php } ?>
                </div>
                <?php
              } else {
                ?>
                <div class="evening-form-reg__right-price-block  order-new-course">
                  <div class="right-full-price">
                    <span class="right-full-price__course"></span>
                    <span class="right-discount-full-price__course"> </span> UZS.
                  </div>
                  <div
                    class="evening-part-price__title"><?php echo $lang ? 'Оплата по частям' : 'Оплата по частинам' ?></div>
                  <?php if ($_POST['parts_weeks'] !== '') { ?>
                    <div class="right-part-price">
                      <span class="right-part-price__course"></span>
                      <span class="right-discount-part-price__course"> </span> UZS. <span
                        class="partlyPay-x">x</span><span class="partlyPay-weeks"><?php echo $weeks ?></span>
                    </div>
                  <?php } else { ?>
                    <div class="right-part-price">
                      <span class="partlyPay-x">Не предусмотрено </span>
                    </div>
                  <?php } ?>
                </div>
                <?php
              }
            ?>

          </div>
<!--          <div class="evening-form-reg__right-phone">-->
<!--            <a href="tel:+380445990179">+380 44 599 01 79</a>-->
<!--            <p>--><?php //echo $lang ? 'Свяжитесь с нами, если у вас возникли вопросы или предложения' : 'Зв\'яжіться з нами, якщо у вас виникли питання або пропозиції' ?><!--</p>-->
<!--          </div>-->
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?php bloginfo('template_directory'); ?>/js/form_evening_validation.js"></script>
<script>
  (function () {
    var list = document.querySelector('.courses-list-option-default');
    list.addEventListener('click', function (e) {
      toggleList();
    });
    var inp = document.querySelector('#courses-list input'),
      options = document.querySelectorAll('#courses-list .courses-list-option'),
      data = <?php echo json_encode($options_id); ?>,
      responseItem = {};

    $('.order-new-course').hide();

    function my_magic_function(item){
      $('.order-new-course').show();
      if (item['discont'] == '') {
        $('.order-new-course .right-full-price__course').hide();
        $('.order-new-course .right-part-price__course').hide();
        $('.order-new-course .right-discount-part-price__course').text(item['old_part_price']);
        $('.order-new-course .right-discount-full-price__course').text(item['price_right']).show();
        $('.order-new-course .partlyPay-weeks').text(item['weeks']);
      } else {
        $('.order-new-course .right-full-price__course').text(item['old_price']).show();
        $('.order-new-course .right-part-price__course').text(item['old_part_price']).show();
        $('.order-new-course .right-discount-part-price__course').text(item['new_part_price_right']).show();
        $('.order-new-course .right-discount-full-price__course').text(item['price_right']).show();
        $('.order-new-course .partlyPay-weeks').text(item['weeks']);
      }

      if (item['old_part_price'] === 0) {
        $('.right-part-price').hide();
        $('.order-new-course').append('<div class="not-found">Не передбачено</div>');
      } else {
        $('.not-found').remove();
        $('.right-part-price').show();
      }
    }

    for(var i = 0; i < options.length; i++) {
      (function(i) {
        options[i].addEventListener('click', function(e) {
          document.querySelector('.courses-list-option-default').innerText = e.target.innerText;
          toggleList();
          document.querySelector('.courses-list-option-default').style.borderColor = 'rgb(19, 59, 84)';
          (document.getElementById('courses-list')).classList.remove('survey_check_error');
          document.querySelector('#courses-list input[name=course]').value = data[i];
          // var response = [];
          $.ajax({
            url: '<?php echo admin_url("admin-ajax.php") ?>',
            type: 'POST',
            // dataType: "json",
            data: ({
              'action'    : 'my_action_callback',
              'courseID'  : $('#courses-list input[name=course]').val()
            }),
            success: function(data) {
              // response
              var response = JSON.parse(data);
              responseItem = JSON.parse(data);

              $('input[name=price]').val(response['old_price']);
              $('input[name=course_id]').val(response['ID']);
              my_magic_function(response);
            },
            error: function (errorThrown) {
              console.log(errorThrown)
            }
          });
        });
      })(i)
    }

    inp.addEventListener('input', function (e) {
      //Search implementation on Course Name
      var reg = new RegExp(e.target.value, 'i');
      for (var key in options) {
        if (!options.hasOwnProperty(key)) continue;

        if (!reg.test(options[key].innerText)) {
          options[key].style.display = 'none';
        } else {
          options[key].style.display = 'block';
        }
      }
    });

    function toggleList() {
      $('.courses-list-set').toggleClass('active');
    }

    function setId(i) {
      return (function (id) {
        console.log(data[id]);
      })(i)
    }
  })();
</script>
<script>
  var uuidFiliation = '788d3b28-a67c-4c55-ab96-69c3d8c69f4d';

  var currentPrice = $('input[name=price]');
  var currentPartPrice = $('input[name=parts_price]');

  var fullPrice = $(currentPrice).data('full_price');
  var fullPatrsPrice = $(currentPrice).data('full_parts_price');
  var newPatrsPrice = $(currentPrice).data('new_parts_price');
  var weeks = $(currentPrice).data('parts_weeks');
  var discount = $(currentPrice).data('discount');


  // Full price and price with discount

  var roadmap = $('input[name=road_id]');
  $('.right-discount-full-price__course').text($(currentPrice).data('discount_price'));
  $('.right-discount-part-price__course').text(newPatrsPrice);
  $('.partlyPay-weeks').text(weeks);

  // console.log(discount)

  if (!discount) {
    $('.right-full-price__course').text(fullPrice).hide();
    $('.right-part-price__course').text(fullPatrsPrice).hide();
  } else {
    $('.right-full-price__course').text(fullPrice).show();
    $('.right-part-price__course').text(fullPatrsPrice).show();
    $('input[name=discountFromSite]').val(discount);
  }


  if ($('input[name=segment_type]').val() == 'roadmap_order') {
    $('input[name=price]').val(fullPrice);
    $('.right-discount-full-price__course').text($(currentPrice).data('discount_price'));
    $('.right-full-price__course').text(roadmap.data('roadmap-full-price')).show();
    if (roadmap.data('di-full') == '') {
      $('.right-full-price__course').hide();
    } else {
      $('.right-full-price__course').show();
    }
    $('.right-part-price__course').hide();
    $('.right-discount-part-price__course').text(roadmap.data('roadmap-part-price')).show();
    $('.partlyPay-weeks').text('4');
  } else {
    $('input[name=price]').val(fullPrice);
    $('.right-discount-full-price__course').text($(currentPrice).data('discount_price'));

  }
  if ($('input[name=road_id]').data('di_full') != '' || $('input[name=road_id]').data('di_full') !='0'){
    $('input[name=road_id]').data('di_full',$('input[name=discountFromSite]').val())
  }
  function dontShowThisThingIfValueSame () {
                if ( $('.right-full-price__course').first().text() == $('.right-discount-full-price__course').first().text() ){
                  $('.right-full-price__course').hide();
                }
              } 
              dontShowThisThingIfValueSame();
              
</script>
<?php
if ($segment_type == 'b2c_first_lesson') {
    ?>
    <form action="https://secure.platononline.com/payment/auth" method="post" class="hidden payment">
        <input type="hidden" name="payment" value="" />
        <input type="hidden" name="key"     value="" />
        <input type="hidden" name="url"     value="" />
        <input type="hidden" name="data"    value="" />
        <input type="hidden" name="sign"    value="" />
        <input type="hidden" name="email"   value="" />
        <input type="hidden" name="phone"   value="" />
        <input type="hidden" name="ext1"    value="" />
        <input type="hidden" name="first_name" value="" />
    </form>

    <script type="text/javascript">
        var callNew;
        $( document ).ready(function() {
          
            callNew = function(e) {
                console.log('sending');
                $.ajax({
                    method: 'POST',
                    url: window.location.protocol + '//' + window.location.host + '/wp-admin/admin-ajax.php?action=payment_order',
                    data: $('#for_payment').serialize(),
                    success: function(data) {
                        if(data == 'error'){
                            location.href = "<?php echo get_permalink(11993); ?>";
                        }
                        data = JSON.parse(data);
                        $('.payment input[name="key"]').val( data['key'] );
                        $('.payment input[name="payment"]').val( data['payment'] );
                        $('.payment input[name="url"]').val( data['url'] );
                        $('.payment input[name="data"]').val( data['data'] );
                        $('.payment input[name="sign"]').val( data['sign'] );
                        $('.payment input[name="email"]').val( data['email'] );
                        $('.payment input[name="phone"]').val( data['phone'] );
                        $('.payment input[name="ext1"]').val( data['ext1'] );
                        $('.payment input[name="first_name"]').val( data['first_name'] );
                        // $('.payment').submit();
                        document.location.href = window.location.protocol + '//' + window.location.host + '/b2c_first_lesson/step2/';//data['redirect_link'];
                    },
                    error: function(xhr, str) {}
                });
            };
            // look at form_validations_v4.js
        });
    </script>
    <?php
}
?>

<?php get_footer(); ?>
