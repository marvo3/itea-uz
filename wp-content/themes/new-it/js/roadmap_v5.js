/*
 * data:   25.04.16
 *
 * Функция nicePrice() идентична функцие nicePrice с файла functions.php
 *
 * При необходимости изменить логику, для корректности вывода информации, необходимо изменить функцию и на PHP и на JS
 *
 */
$(document).ready(function () {
  $('.b-level-course-list-table_header--btn-left').click(function () {
    $grid.isotope({filter: '.course-list'});
  });
  $('.b-level-course-list-table_header--btn-right').click(function () {
    $grid.isotope({filter: '.course-choice'});
  });
  $('.b-level-course-list-table_header--btn-left').click(function () {
    $('.b-level-course-list-table_header--btn-right').removeClass('active');
    $('.b-level-course-list-table_header--btn-left').addClass('active');
  });
  $('.b-level-course-list-table_header--btn-right').click(function () {
    $('.b-level-course-list-table_header--btn-left').removeClass('active');
    $('.b-level-course-list-table_header--btn-right').addClass('active');
  });
  function calcCoursePrice() {
    var requiredAamount = $('.number-of-percent').attr('data-num-courses');
    var salePercent = $('.number-of-percent').text();

    salePercent = salePercent / 100;

    var checkedCourses = $('.css-checkbox:checked'),
      coursesAll = $('.css-checkbox');

    if(checkedCourses.length == coursesAll.length)
      salePercent = -parseInt($('.full-sale-discount').text()) / 100;

    var coursItemVal = '';
    var coursePrice = 0;

    for (var i = 0; i < checkedCourses.length; i++) {
      coursItemVal = $(checkedCourses[i]).val().split('|');
      coursePrice += Number(coursItemVal[1]);
    }
    if (i >= requiredAamount) {
      coursePrice = parseInt(coursePrice - coursePrice * salePercent + 0.99, 10);
    }

    var form = $('#choice_course_form');
    if (checkedCourses[0] === undefined) {
      $('.form-disabled').fadeIn(300);
      $("button[name='roadChoice_payOnce']", form).attr('disabled', true).css('opacity', '0.5');
    } else {
      $('.form-disabled').fadeOut(300);
      $("button[name='roadChoice_payOnce']", form).attr('disabled', false).css('opacity', '1');
    }

    var partsPrice = parseInt((coursePrice + coursePrice * 0.1) / 4 + 0.99, 10);

    function nicePrice(price) {
      price = String(price);
      if (price.length > 2) {
        price = price.slice(0, -1);
        switch (price.slice(-1)) {
          case '1':
          case '2':
            price = price.slice(0, -1) + '00';
            break;
          case '3':
          case '4':
          case '5':
          case '6':
          case '7':
            price = price.slice(0, -1) + '50';
            break;
          case '8':
          case '9':
            price = String(Number(price.slice(0, -1)) + 1) + '00';
            break;
          default:
            price += '0';
        }
      }
      return price;
    }

    coursePrice = Math.round(nicePrice(coursePrice));
    partsPrice = Math.round(nicePrice(partsPrice));
    $('#price_road').val(coursePrice);
        $('#price_road_part').val(coursePrice);
        
    $('.choice-total-price').text((coursePrice+'').replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ') + " UZS");
    $('.choice-part-price').text((partsPrice+'').replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ') + " UZS");
    $("input[name='roadChoice_price']", form).val(coursePrice);
    $("#price_road").val(coursePrice);
    $("input[name='roadChoice_parts_price']", form).val(coursePrice);
    $("#roadmapPartPrice2").val(partsPrice);
    if (document.querySelectorAll('.css-checkbox').length !== 1 || document.querySelectorAll('.css-checkbox').length !== 0){
      $("input[name='roadmap-full-price']", form).val(coursePrice);
      $("input[name='full_price']", form).val(coursePrice);
  }
    $("input[name='roadmap-part-price']", form).val(partsPrice);
    $("#roadmap_parts_price").val(partsPrice);
        $("#roadmapPartPrice").val(partsPrice);
        
  }
  let rounded = function(number){
    return +number.toFixed(2);
}
  calcCoursePrice();
  $('.css-checkbox').change(function () {
    calcCoursePrice();
    let arrayCourses = document.querySelectorAll('.css-checkbox:checked');
    let courseValue = event.currentTarget.value;
    let fullPriceCourses = 0;



    
    arrayCourses.forEach(element => {
            

                let currentCourse= element.value;
                priceValue = parseInt(currentCourse.split(' | ')[1]);
                fullPriceCourses += priceValue;
                
        });

        var form = $('#choice_course_form');
        $("input[name='roadmap-full-price']", form).val(Math.round(nicePrice(fullPriceCourses)));
        $("input[name='full_price']", form).val(Math.round(nicePrice(fullPriceCourses)));
        if (arrayCourses.length == document.querySelectorAll('.css-checkbox').length ){
              $("#roadmapDiscountPrice").val($("input[name='di_full']").val());
            $("#roadmapDiscountPrice2").val($("input[name='di_full']").val());
            $('#discountPart').val($("input[name='full_parts_price']").val());
            $('#discountPart2').val($("input[name='full_parts_price']").val());
            $('#discountPart3').val($("input[name='full_parts_price']").val());
            $('#discountOnce').val($("input[name='full_parts_price']").val());
            $("input[name='full_roadmap']").val(1);
        } else if(arrayCourses.length  >= $('.number-of-percent').attr('data-num-courses') ) {
          let  salePercent = $('.number-of-percent').text();
            $('#discountPart').val(salePercent);
            $('#discountPart2').val(salePercent);
            $('#discountPart3').val(salePercent);
            $('#discountOnce').val(salePercent);
            $("#roadmapDiscountPrice").val(salePercent);
            $("#roadmapDiscountPrice2").val(salePercent);
            $("input[name='full_roadmap']").val(0);
          }
         
         
         else {
            $('#discountPart').val(0);
            $('#discountOnce').val(0);
            $('#discountPart2').val(0);
            $('#discountPart3').val(0);
            $("#roadmapDiscountPrice").val(0);
            $("#roadmapDiscountPrice2").val(0);
            $("input[name='full_roadmap']").val(0);
        }
        function nicePrice(price) {
          price = String(price);
          if (price.length > 2) {
            price = price.slice(0, -1);
            switch (price.slice(-1)) {
              case '1':
              case '2':
                price = price.slice(0, -1) + '00';
                break;
              case '3':
              case '4':
              case '5':
              case '6':
              case '7':
                price = price.slice(0, -1) + '50';
                break;
              case '8':
              case '9':
                price = String(Number(price.slice(0, -1)) + 1) + '00';
                break;
              default:
                price += '0';
            }
          }
          return price;
        }
        $('#fullPricePart').val(Math.round(nicePrice(fullPriceCourses)));
        $('#fullPriceOnce').val(Math.round(nicePrice(fullPriceCourses)));
      //   if (document.querySelectorAll('.css-checkbox').length == 1){ 
      //     let 
      //         fullprice = $('#fullPriceOnce').val() ,
      //         discount_price = $("#price_road").val(),
      //         percent = rounded(100 - (discount_price*100/fullprice));
      //     $('#discountPart').val(percent);
      //     $('#discountOnce').val(percent);
      //     $('#discountPart2').val(percent);
      //     $('#discountPart3').val(percent);
          
      // }
        

  });
  function crutchActive (){
    let inputs = document.querySelectorAll('.css-checkbox'),
        activeThing = document.querySelector('.b-level-course-list-table_header--btn-right');
      inputs.forEach(input => {
          input.addEventListener('change',()=>{
              activeThing.click();
          })
        });
  }
  crutchActive();
});
