<?php

require_once(__DIR__ . '/../Utils/LoggerUtil.php');
define('ITEA_PROD', (bool) ($_SERVER['HTTP_HOST'] == 'itea.uz' || $_SERVER['SERVER_NAME'] == 'itea.uz'));
//define('ITEA_PROD', (bool) ($_SERVER['HTTP_HOST'] == 'itea.uz.loc' || $_SERVER['SERVER_NAME'] == 'itea.uz.loc'));

require_once('OAuth2CrmAspNetService.php');

require_once('Bitrix.php');

require_once('OAuth2CrmSymfonyService.php');
require_once('OAuth2CrmSymfonyService_demo.php');
require_once('OAuth2CrmSymfonyService_dev.php');


class OrderService
{
    const TYPE_DAY = 'day';
    const TYPE_EVENING = 'evening';
    const TYPE_DEBUG = 'debug';

    private static $arrayKeyTranslation = [
        'segment_type'  => 'Сегмент',
        'name'          => 'Имя',
        'phone'         => 'Номер телефона',
        'mail'          => 'Эл. почта',
        'name_of_child' => 'Имя ребенка',
        'age_of_child'  => 'Возраст ребенка',
        'comment'       => 'Комментарий',
        'road_id'       => 'Roadmap',
        'course_id'     => 'Курс',
        'course'        => 'Курс',
        'course-items'  => 'Выборочно',
        'price'         => 'Полная стоимость',
        'parts_price'   => 'Оплата частями',
        'rental_date'   => 'Выбранная дата аренды',
        'user_selected_profession_IT' => 'Направление IT для консультации',
        'date_birth'    => 'День Рожденья',
        'id'            => 'Ссылка на резюме',
        'linkedin'      => 'Linkedin',
        'portfolio'     => 'Портфолио',
        'email'         => 'Эл. почта',

        'format'      => 'format',
        'discountFromSite'  => 'discountFromSite'
    ];

    private $segmentType;
    private $segmentInfo = [
        'b2b_rent'         => ['redirect' => '11731', 'title' => 'Заявка на аренду помещения'],
        'b2b_order'        => ['redirect' => '6874',  'title' => 'Заявка на дневной курс'],
        'b2c_order'        => ['redirect' => '6868',  'title' => 'Заявка на вечерний курс'],
        'roadmap_order'    => ['redirect' => '7613',  'title' => 'Запись на комплекс курсов'],
        'b2c_free'         => ['redirect' => '6870',  'title' => 'Заявка на бесплатную консультацию'],
        'b2c_first_lesson' => ['redirect' => NULL,    'title' => 'Заявка на первое пробное занятие'],        // AJAX
        //'callback_order' => ['redirect' => NULL,    'title' => 'Заказ обратного звонка для консультации'], // AJAX
        'consultation'     => ['redirect' => '12441', 'title' => 'Заявка на участие в акции и бесплатной консультации'],
    ];

    private $logger;

    /**
     * OrderService constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $user_phone   = (array_key_exists('phone', $_POST) ? $_POST['phone'] : '');
        if(!empty($_POST['id'])){
            $user_email2   = (array_key_exists('email', $_POST)  ? $_POST['email']  : '');
        }else{
            $segment_type = (array_key_exists('segment_type', $_POST) ? $_POST['segment_type'] : '');
            $user_name    = (array_key_exists('name', $_POST)  ? $_POST['name']  : '');
            $user_email   = (array_key_exists('mail', $_POST)  ? $_POST['mail']  : '');
            $num_of_IDs   = array_key_exists('course_id', $_POST) + array_key_exists('road_id', $_POST);
        }

        $error = 0;
        @$error += empty($user_phone);
        if(!empty($_POST['id'])){
            @$error += empty($user_email2);
        }else {
            @$error += !array_key_exists($segment_type, $this->segmentInfo);
            @$error += $num_of_IDs > 1;
            @$error += empty($user_name);
            @$error += empty($user_email);
        }

        if ($error) {
            throw new Exception();
        } else {
            if(empty($_POST['id'])){
                $this->segmentType = $segment_type;
            }
            $this->logger = new LoggerUtil;
        }
//        $segment_type = (array_key_exists('segment_type', $_POST) ? $_POST['segment_type'] : '');
//        $user_name    = (array_key_exists('name', $_POST)  ? $_POST['name']  : '');
//        $user_phone   = (array_key_exists('phone', $_POST) ? $_POST['phone'] : '');
//        $user_email   = (array_key_exists('mail', $_POST)  ? $_POST['mail']  : '');
//        $num_of_IDs   = array_key_exists('course_id', $_POST) + array_key_exists('road_id', $_POST);
//
//        $error = 0;
//        @$error += !array_key_exists($segment_type, $this->segmentInfo);
//        @$error += $num_of_IDs > 1;
//        @$error += empty($user_name);
//        @$error += empty($user_phone);
//        @$error += empty($user_email);
//
//        if ($error) {
//            throw new Exception();
//        } else {
//            $this->segmentType = $segment_type;
//            $this->logger = new LoggerUtil;
//        }
    }

    /**
     * @return string
     */
    public function getSegmentType()
    {
        return $this->segmentType;
    }

    /**
     * @return string
     */
    public function getSegmentTitle()
    {
        return $this->segmentInfo[$this->segmentType]['title'];
    }

    /**
     * @return string
     */
    public function getRedirectId()
    {
        return $this->segmentInfo[$this->segmentType]['redirect'];
    }

    /**
     * @param string $str
     * @return string
     */
    private static function getSendersLabel($str)
    {
        $stack = explode('_', $str);
        return (isset($stack[0]) ? $stack[0] : '');
    }

    /**
     * @return string
     */
    private function getTotalPrice()
    {
        $sumPrices = '';

        if (!empty($_POST['price'])) {
            $sumPrices = $_POST['price'];
        } elseif (!empty($_POST['parts_price'])) {
            $numbers = preg_split('/[\D]+/', $_POST['parts_price'], NULL, PREG_SPLIT_NO_EMPTY);

            if (count($numbers) >= 2)
            {
                $sumPrices = (int) $numbers[0] * (int) $numbers[1];
            }
        }
       
        if (empty($_POST['price']) && !empty($_POST['priseWithDiscount']) && empty($_POST['discountFromSite'])) {
            $sumPrices = $_POST['priseWithDiscount'];
        }
        return (string) $sumPrices;
    }

    /**
     * @return string
     */
    private function getDiscountPrice()
    {
        $totalPrice = $this->getTotalPrice();
        
        if (!empty($_POST['discountFromSite'])) {
           
            $totalPrice = $_POST['priseWithDiscount'];
        }
        

        return (string) $totalPrice;
    }

    /**
     * @param $course_id
     * @return string
     */
    private function getCourseUuid($course_id)
    {
        $uuid = get_post_meta(pll_get_post($course_id, 'ru'), 'uuid_for_itea_crm', true);
        return $uuid;
    }

    /**
     * @param $road_id
     * @return string
     */
    private function getRoadUuid($road_id)
    {
        $uuid = get_term_meta(pll_get_term($road_id, 'ru'), 'uuid_for_itea_crm', true);
        return $uuid;
    }

    /**
     * @param $road_id
     * @return array
     */
    private function getRoadCoursesUuid($road_id)
    {
        $coursesUuid = [];

        $roadCourses = get_posts([
            'numberposts' => -1,
            'cat' => pll_get_term($road_id, 'ru'),
        ]);

        foreach ($roadCourses as $course) {
            $coursesUuid[] = $this->getCourseUuid($course->ID);
        }

        return $coursesUuid;
    }

    /**
     * @param string $stack
     * @return string
     */
    private function id_price__to__name_price($stack)
    {
        $stack = explode(' | ', $stack);
        return get_the_title($stack[0]) . ' – ' . $stack[1] . ' UZS';
    }

    /**
     * @param string $stack
     * @return array
     */
    private function id_price__to__uuid_price($stack)
    {
        $stack = explode(' | ', $stack);
        $uuid  = $this->getCourseUuid($stack[0]);
        return [$uuid => $stack[1]];
    }

    /**
     * @param string $stack
     * @return string
     */
    private function id_price__to__uuid($stack)
    {
        $uuid = $this->getCourseUuid((int) $stack);
        return $uuid;
    }

    /**
     * @param string $key
     * @param string $value
     * @return array
     */
    private static function _as($key, $value)
    {
        switch ($key) {
            case 'course_id':
                $value = (empty($value) ? 'не выбран' : get_the_title($value));
                break;
            case 'road_id':
                $value = (empty($value) ? 'не выбран' : get_cat_name($value));
                break;
            case 'course-items':
                $courseSet = explode(', ', $value);
                $courseSet = array_map('self::id_price__to__name_price', $courseSet);
                $value = implode(', ', $courseSet);
                break;
            case 'course':
                $value = (empty($value) ? '' : get_the_title($value));
                break;
        }

        if (array_key_exists($key, self::$arrayKeyTranslation)) {
            $key = self::$arrayKeyTranslation[$key];
        }

        return ['key' => $key, 'value' => $value];
    }

    /**
     * @param array $needles
     * @param array $outputArray
     * @return array
     */
    private function addNeedlesToArray($needles, $outputArray=[])
    {
        foreach ($needles as $key) {
            if (!empty($_POST[$key])) {
                $stack = self::_as($key, $_POST[$key]);
                $outputArray[] = "{$stack['key']}:{$stack['value']}";
            }
        }

        return $outputArray;
    }

    /**
     * @param string $segment
     * @param string $setting
     * @param array $messages
     * @return bool
     */
    public static function sendToEmail($segment, $setting = '', $messages = [])
    {
        $to = array();
        $subject = 'Новое письмо с сайта ITEA Ташкент';
        $messages = (array) $messages;

        switch ($setting) {
            case self::TYPE_EVENING:
                $to[] = 'miroslav@itea.ua';
                $to[] = 'galina.karelina@itea.ua';
                $to[] = 'nykolay@gns-it.com';
                $to[] = 'taras.kolba@itea.ua';
                $to[] = 'mirek.byk@gmail.com';
//                $to[] = 'bogdan.illyashik@gns-it.com';
                break;
            case self::TYPE_DAY:
                $to[] = 'miroslav@itea.ua';
                $to[] = 'galina.karelina@itea.ua';
                $to[] = 'nykolay@gns-it.com';
                $to[] = 'mirek.byk@gmail.com';
                $to[] = 'taras.kolba@itea.ua';
//                $to[] = 'bogdan.illyashik@gns-it.com';
                break;
            case self::TYPE_DEBUG:
            default:
                $to[] = 'miroslav@itea.ua';
                $to[] = 'galina.karelina@itea.ua';
                $to[] = 'nykolay@gns-it.com';
//                $to[] = 'bogdan.illyashik@gns-it.com';
                $messages[] = "SETTING : {$setting}";
        }

        $message  = '<html><head><title>Новое сообщение</title><meta charset="utf-8"></head><body>';
        foreach ($messages as $line) {
            $message .= "<p>{$line}</p>";
        }

        foreach ($_POST as $key => $value) {
            switch ($key) {
                case 'action':
                case 'verification':
                case 'rental_date_submit':
                    $message .= ($setting == 'debug' ? "<p>{$key}: {$value}</p>" : '');
                    break;
                case 'comment':
                    $message .= (empty($value) ? '' : "<p>Заявка сопровождалась комментарием: {$value}</p>");
                    break;
                default:
                    $stack = self::_as($key, $value);
                    $message .= "<p>{$stack['key']}: {$stack['value']}</p>";
            }
        }


        $roadmapUuid = '';
        $coursesUuid = [];

        if (!empty($_POST['road_id']) || !empty($_POST['course-items']) || !empty($_POST['course_id']) ) {
            $order_service = new OrderService();
            if (!empty($_POST['road_id']) && strpos($_POST['road_id'],"|") === false) {
                $roadmapUuid = $order_service->getRoadUuid($_POST['road_id']);

                if (!empty($_POST['course-items'])) {
                    $coursesUuid = array_map([$order_service, 'id_price__to__uuid'], explode(', ', $_POST['course-items']));
                } else {
                    $coursesUuid = $order_service->getRoadCoursesUuid($_POST['road_id']);
                }
            } elseif (!empty($_POST['course_id'])) {

                $coursesUuid[] = $order_service->getCourseUuid($_POST['course_id']);
            } else {

                $roadmapUuid = $_POST['road_id'];
            }
        }

        if (!empty($coursesUuid)) {
            $message .= '<p>Uuid Курса(ов): ' . implode($coursesUuid, ", ") . '</p>';
        }
        if (!empty($roadmapUuid)) {
            $message .= '<p>Uuid Роадмап: ' . $roadmapUuid . '</p>';
        }

        $message .= '<p>Время заявки: ' . date('d.m.Y , H:i:s') . '</p>';
        $message .= '<p>Отправленно с IP: ' . $_SERVER['REMOTE_ADDR'] . '</p>';
        $message .= '</body></html>';

        $headers[] = 'From: IT Education Academy ('.self::getSendersLabel($segment).") <{$segment}@itea.ua>";
        $headers[] = 'Content-Type: text/html; charset=utf-8';

//        $bitrix = new Bitrix();
//        $bitrix->createLeadBitrix('itea.ua', null, $message);

        if (ITEA_PROD) {
            $send_status = wp_mail(implode(',', $to), $subject, $message, $headers);
            return (bool) $send_status;
        } else {
            return TRUE;
        }
    }

    /**
     * @param array $arrayMessages
     */
    public function sendEmailEveningDepartment($arrayMessages=[])
    {
        //session_worm
        if(!isset($_SESSION)){
            session_start();
        }

        $arrayMessages = (array) $arrayMessages;
        $arrayMessages[] = $this->getSegmentTitle();

        $send_status = self::sendToEmail(
            $this->getSegmentType(),
            self::TYPE_EVENING,
            $arrayMessages
        );

//        $bitrix = new Bitrix();
//        $message = '';
//        foreach ($arrayMessages as $line) {
//            $message .= "<p>{$line}</p>";
//        }
//        $bitrix->createLeadBitrix('itea.ua Consultation', null, $message);

        if (!$send_status) {

            $_SESSION['session_worm'].="<br>&#9;&nbsp;".($_SESSION['worm_counter']++).") -> IN method <b>sendEmailEveningDepartment()</b> of <i>OrderService.php</i> <small>do not working self::sendToEmail</small>";

            $this->logger->log('sendEmailEveningDepartment');
        }
    }

    /**
     * @param array $arrayMessages
     */
    public function sendEmailDayDepartment($arrayMessages=[])
    {
        $arrayMessages = (array) $arrayMessages;
        $arrayMessages[] = $this->getSegmentTitle();

        $send_status = self::sendToEmail(
            $this->getSegmentType(),
            self::TYPE_DAY,
            $arrayMessages
        );

        if (!$send_status) {
            $this->logger->log('sendEmailDayDepartment');
        }
    }

    /**
     * @param array $arrayMessages
     */
    public function checkAndReportByEmailWhenFail($arrayMessages=[])
    {
        if ( !$this->logger->isEmpty() ) {
            $arrayMessages = (array) $arrayMessages;

            self::sendToEmail(
                self::TYPE_DEBUG,
                self::TYPE_DEBUG,
                array_merge($this->logger->getAllLogs(), $arrayMessages)
            );
        }
    }

    /**
     * DEPRECATION | OUTDATED
     * @param string $ik_amount
     * @param string $ik_description
     * @return array
     */
    public function getDataSetForPayment($ik_amount, $ik_description)
    {
        $dataSet = array('ik_co_id' => '58d29eeb3c1eafae368b4567');

        $dataSet['ik_am']    = $ik_amount;
        $dataSet['ik_desc']  = $ik_description;
        $dataSet['ik_cur']   = 'KZT';
        $dataSet['ik_pm_no'] = preg_replace('/[^0-9]/', '', $_POST['phone']);
        $dataSet['ik_cli']   = $_POST['mail'];

        ksort($dataSet, SORT_STRING);
        array_push($dataSet, 'Sgk1Y7naXflS0ztW');
        $signString = implode(':', $dataSet);
        $signString = base64_encode(md5($signString, true));
        array_pop($dataSet);
        $dataSet['ik_sign'] = $signString;

        return $dataSet;
    }

    /**
     * @param string $description
     * @return array
     */
    public function getDataPlaton($description)
    {
        $data['first_name'] = $_POST['name'];
        $data['email'] = $_POST['mail'];
        $data['phone'] = $_POST['phone'];
        $data['ext1']  = 'Kiev';

        $pass = 'KdMwfTynqcfVh5dpPDMM7wJYYSBu7jX1';
        $data['key'] = '0K9HBL3KDB';
        $data['url'] = 'https://itea.uz/payment-success/';
        $data['data'] = base64_encode(json_encode([
                            'amount'      => $_POST['mail'] === 'info@itea.ua' ? '1.00' : '200.00',
                            'currency'    => 'KZT',
                            'description' => $description,
        ]));
        $data['payment'] = 'CC';
        $data['sign'] = md5(strtoupper(
            strrev($data['key']).
            strrev($data['payment']).
            strrev($data['data']).
            strrev($data['url']).
            strrev($pass)
        ));

        return $data;
    }

    public function sendToCrmAspNet()
    {
        $needlesForApiComment = [
            'segment_type',
            'course-items',
            'course',
            'price',
            'parts_price',
            'name_of_child',
            'age_of_child',
            'comment',
            'user_selected_profession_IT',
        ];
        $apiComment = $this->addNeedlesToArray($needlesForApiComment);

        if (!empty($_POST['road_id'])) {
            $paramCourse = get_cat_name($_POST['road_id']);
        } elseif (!empty($_POST['course_id'])) {
            $paramCourse = get_the_title($_POST['course_id']);
        } else {
            $paramCourse = 'не выбран';
        }

        $params = [
            'ClientName'  => (string) $_POST['name'],
            'ClientEmail' => (string) $_POST['mail'],
            'ClientTel'   => (string) $_POST['phone'],
            'Course'      => (string) $paramCourse,
            'Location'    => 'Ташкент',
            'Date'        => date('d.m.y , H:i'),
            'Comment'     => (string) implode('; ', $apiComment)
        ];

        if (ITEA_PROD) {

            //session_worm
            if(!isset($_SESSION)){
                session_start();
            }
            try {
                $service = new OAuth2CrmAspNetService;

                $_SESSION['session_worm'].="<br>&#9;&nbsp;&nbsp;".($_SESSION['worm_counter']++).") -> <font color='green'>BEFORE</font> method <b>sendOrder()</b> of function <b>sendToCrmAspNet()</b> in <i>OrderService.php</i>";
                $result  = $service->sendOrder($params);
                $_SESSION['session_worm'].="<br>&#9;&nbsp;&nbsp;".($_SESSION['worm_counter']++).") -> <font color='blue'>AFTER</font> method <b>sendOrder()</b> of function <b>sendToCrmAspNet()</b> in <i>OrderService.php</i>";

                if (!$result->isSuccessCode()) {
                    $_SESSION['session_worm'].="<br>&#9;&nbsp;".($_SESSION['worm_counter']++).") -> <font color='red'>ERROR</font> method <b>isSuccessCode()</b> of function <b>sendToCrmAspNet()</b> in <i>OrderService.php</i>";
                    $this->logger->log($result->getCodeAndMessage());
                }
            } catch (Exception $ex) {
                $_SESSION['session_worm'].="<br>&#9;&nbsp;".($_SESSION['worm_counter']++).") -> <font color='red'>ERROR</font> method <b>catch()</b> of function <b>sendToCrmAspNet()</b> in <i>OrderService.php</i> | <small>".$ex->getMessage()."</small>";
                $this->logger->log($ex->getMessage());
            }
        }
    }

    public function sendToCrmSymfony()
    {
        $needlesForApiComment = [
            'segment_type',
            'course-items',
            'course',
            'price',
            'parts_price',
            'name_of_child',
            'age_of_child',
            'comment',
        ];
        $apiComment = $this->addNeedlesToArray($needlesForApiComment);

        $roadmapUuid = '';
        $coursesUuid = [];

        if (!empty($_POST['road_id'])) {
            $roadmapUuid = $this->getRoadUuid($_POST['road_id']);

            if (!empty($_POST['course-items'])) {
                $coursesUuid = array_map([$this, 'id_price__to__uuid'], explode(', ', $_POST['course-items']));
            } else {
                $coursesUuid = $this->getRoadCoursesUuid($_POST['road_id']);
            }
        } elseif (!empty($_POST['course_id'])) {
            $coursesUuid[] = $this->getCourseUuid($_POST['course_id']);
        }

        $params = [
            'coursesUuid' => $coursesUuid,
            'roadmapUuid' => $roadmapUuid,
            'sum'         => $this->getDiscountPrice(),
            'name'        => $_POST['name'],
            'phone'       => $_POST['phone'],
            'email'       => $_POST['mail'],
            'format'      => 'OFFLINE',
            'discountFromSite'      => $_POST['discountFromSite'],
            'comment'     => implode('; ', $apiComment) .' — '. $_SERVER['HTTP_HOST'],
            'cityUuid'    => '5e8c052d-c369-4bc0-9728-851ca2714af9',
            'courseType'  => 'INNER_EVENING',
            'sourceUuid'  => '',
        ];
        if (!empty($_POST['sourceUuid'])) {
            switch ($_POST['sourceUuid']) {
                case 'ppc':
                case 'google':
                    $params['sourceUuid'] = '40c4b34f-3169-4121-8d55-1154fe26964f';
                    break;
                case 'smm':
                case 'facebook':
                case 'instagram':
                case 'fb':
                    $params['sourceUuid'] = '6722603c-a86b-47a1-b75d-d76945ad6a7d';
                    break;
                case 'telegram':
                case 'tg':
                    $params['sourceUuid'] = '52617945-2f66-4ba3-8d1f-92759b147c78';
                    break;
                case 'dou':
                    $params['sourceUuid'] = 'a8a94c21-0c08-4fc0-a964-4528e5bd4da8';
                    break;
                case 'pr':
                    $params['sourceUuid'] = '82e7aba0-26de-4d1b-a609-59192fe8fe5c';
                    break;
                case 'pathfinder':
                    $params['sourceUuid'] = '26249377-9bff-4854-9e62-c74367541472';
                    break;
                case 'linkedin':
                    $params['sourceUuid'] = '6a394f56-f5e7-4051-a0b4-3814011379f6';
                    break;
                case 'digitaltest':
                case 'dt':
                    $params['sourceUuid'] = 'ee798910-1b99-4d07-bbd4-478f46884d1f';
                    break;
                case 'esputnik-promo':
                case 'email':
                    $params['sourceUuid'] = '588439dc-d650-4b20-a5ee-22079374eebe';
                    break;
//                case 'instagram':
//                    $params['sourceUuid'] = 'b87ff1d0-36ec-4063-92a7-410c64e39e64';
//                    break;
                default:
                    $params['sourceUuid'] = '';
                    break;
            }
        }
        if (!empty($_POST["gclid"])){
            $params['sourceUuid'] = '40c4b34f-3169-4121-8d55-1154fe26964f';
        }
        if (!empty($_POST["fbclid"])){
            $params['sourceUuid'] = '6722603c-a86b-47a1-b75d-d76945ad6a7d';
        }


        // FOR api.itea-crm-dev.demo.gns-it.com
        //$serviceDev = new OAuth2CrmSymfonyService_dev;
        //$resultDev  = $serviceDev->sendOrder($params);
        //if (!$resultDev->isSuccessCode()) {
        //    $this->logger->log($resultDev->getCodeAndMessage());
        //}

        if (ITEA_PROD) {
            //session_worm
            if(!isset($_SESSION)){
                session_start();
            }
            try {

                $serviceProd = new OAuth2CrmSymfonyService;

                $_SESSION['session_worm'].="<br>&#9;&nbsp;".($_SESSION['worm_counter']++).") -> <font color='green'>BEFORE</font> method <b>serviceProd->sendOrder()</b> in function <b>sendToCrmSymfony()</b> of <i>OrderService.php</i>";
                $resultProd = $serviceProd->sendOrder($params);
                $_SESSION['session_worm'].="<br>&#9;&nbsp;".($_SESSION['worm_counter']++).") -> <font color='blue'>AFTER</font> method <b>serviceProd->sendOrder()</b> in function <b>sendToCrmSymfony()</b> of <i>OrderService.php</i>";


                $bitrix = new Bitrix();
                $bitrix->createLeadBitrix('itea.uz', $this->getDiscountPrice(), $params['comment']);


                if (!$resultProd->isSuccessCode()) {
                    $_SESSION['session_worm'].="<br>&#9;&nbsp;".($_SESSION['worm_counter']++).") -> <font color='red'>ERROR</font> method <b>resultProd->isSuccessCode()</b> of function <b>sendToCrmSymfony()</b> in <i>OrderService.php</i>";
                    $this->logger->log($resultProd->getCodeAndMessage());
                }

//                $serviceProdDemo = new OAuth2CrmSymfonyService_demo;
//
//                $_SESSION['session_worm'].="<br>&#9;&nbsp;".($_SESSION['worm_counter']++).") -> <font color='green'>BEFORE</font> method <b>serviceProdDemo->sendOrder()</b> in function <b>sendToCrmSymfony()</b> of <i>OrderService.php</i>";
//                $resultProdDemo = $serviceProdDemo->sendOrder($params);
//                $_SESSION['session_worm'].="<br>&#9;&nbsp;".($_SESSION['worm_counter']++).") -> <font color='blue'>AFTER</font> method <b>serviceProdDemo->sendOrder()</b> in function <b>sendToCrmSymfony()</b> of <i>OrderService.php</i>";
//
//                if (!$resultProdDemo->isSuccessCode()) {
//                    $_SESSION['session_worm'].="<br>&#9;&nbsp;".($_SESSION['worm_counter']++).") -> <font color='red'>ERROR</font> method <b>resultProdDemo->isSuccessCode()</b> of function <b>sendToCrmSymfony()</b> in <i>OrderService.php</i>";
//                    $this->logger->log($resultProdDemo->getCodeAndMessage());
//                }
            } catch (Exception $ex) {
                $_SESSION['session_worm'].="<br>&#9;&nbsp;".($_SESSION['worm_counter']++).") -> <font color='red'>ERROR</font> method <b>catch()</b> of function <b>sendToCrmSymfony()</b> in <i>OrderService.php</i> | <small>".$ex->getMessage()."</small>";
                $this->logger->log($ex->getMessage());
            }
        }
    }

    public function sendResumeToCrmSymfony(){
        $newUrlResume = get_permalink(7633) . '?id=' . $_POST['id'];
        $params = [
            'resume'        => $newUrlResume,
            'birthday'      => $_POST['date_birth'],
            'linkedin'      => $_POST['linkedin'],
            'portfolio'     => $_POST['portfolio'],
            'email'         => $_POST['email'],
            'phone'         => $_POST['phone'],
        ];
        if (ITEA_PROD) {
            try {

                $bitrix = new Bitrix();
                $bitrix->createLeadBitrix('itea.uz', $this->getDiscountPrice(), $params['comment']);

                $serviceProd = new OAuth2CrmSymfonyService;
                $resultProd = $serviceProd->sendResume($params);
                if (!$resultProd->isSuccessCode()) {
                    $this->logger->log($resultProd->getCodeAndMessage());
                }

//                $serviceProdDemo = new OAuth2CrmSymfonyService_demo;
//                $resultProdDemo = $serviceProdDemo->sendResume($params);
//                if (!$resultProdDemo->isSuccessCode()) {
//                    $this->logger->log($resultProdDemo->getCodeAndMessage());
//                }
            } catch (Exception $ex) {
                $this->logger->log($ex->getMessage());
            }
        }
    }

    public static function sendCallbackOrder()
    {
        $params = [
            'phone'       => $_POST['phone'],
            'cityUuid'    => '5e8c052d-c369-4bc0-9728-851ca2714af9',
        ];

        if (ITEA_PROD) {
            try {
                $serviceProd = new OAuth2CrmSymfonyService;
                $resultProd = $serviceProd->sendCallbackOrder($params);
                if (!$resultProd->isSuccessCode()) {
                    self::sendToEmail(
                        self::TYPE_DEBUG,
                        self::TYPE_DEBUG,
                        [$resultProd->getCodeAndMessage()]
                    );
                }

                $bitrix = new Bitrix();
                $bitrix->createLeadBitrix('itea.uz Callback', null);

//                $serviceProdDemo = new OAuth2CrmSymfonyService_demo;
//                $resultProdDemo = $serviceProdDemo->sendCallbackOrder($params);
//                if (!$resultProdDemo->isSuccessCode()) {
//                    self::sendToEmail(
//                        self::TYPE_DEBUG,
//                        self::TYPE_DEBUG,
//                        [$resultProdDemo->getCodeAndMessage()]
//                    );
//                }
            } catch (Exception $ex) {
                self::sendToEmail(
                    self::TYPE_DEBUG,
                    self::TYPE_DEBUG,
                    [$ex->getMessage()]
                );
            }
        }
    }
}
