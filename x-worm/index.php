<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 04.06.18
 * Time: 15:23
 */

//
if(isset($_POST['x-worm-pass']) && $_POST['x-worm-pass']!='' && $_POST['x-worm-pass']==='u4^m)#VR!UWd4<w9'){

    session_start();
    echo "<pre>";
    print_r($_SESSION);
    echo "<pre>";
    $_SESSION['worm_counter']=0;
    $_SESSION['session_worm']="";
}else{
?>
    <h1>Без пароля не пущу!</h1>
    <form action="/x-worm/" method="post">
        <label for="w-worm-pass">
            <input id="w-worm-pass" type="password" name="x-worm-pass" required>
        </label>
        <input type="submit" value="Ввойти">
    </form>
<?php
}
?>