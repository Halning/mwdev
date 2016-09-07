
<button type="button" class="btn btn-default" aria-label="Left Align">
  <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>
</button>

<ul>
    <!--<li><a href="/myaccount/main/"{$activeMenuMain}>Главная</a></li>-->
    <li><a href="/myaccount/profile/"{$activeMenuProfile}>Мой профиль</a></li>
    <!--<li><a href="/myaccount/history/"{$activeHistory}>История</a></li>-->
    <!--<li><a href="/myaccount/confirm-payment/"{$activeConfirmPayment}>Подтверждение оплаты</a></li>-->
    <li><a href="/myaccount/wish/"{$activeMenuWish}>Мои желания</a></li>
    <li id="watch-menu-item"><a href="/myaccount/watch/"{$activeMenuWatch}>Лист наблюдений</a><span>{$watchedAmount}</span></li>
</ul>
