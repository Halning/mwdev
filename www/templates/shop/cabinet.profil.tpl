<div class="profile-profil-container profile-container-all">
    <div class="content">
        <div class="content_block">
            <article>
                <div class="title">персональная информация</div>
		<div class="inform_wrapper">
                    <div class="inform inform_long">
                        <div class="inform_block">
                            <p>ID: {$userId}</p>
                            <fieldset disabled>
                                <div class="input_block form-group">
                                    <label for="surname">Фамилия:</label>
                                    <input type="text" class="form-control" name="surname" maxlength="20" value="{$userLastName}">
                                </div>
                            </fieldset>
                            <fieldset disabled>
                                <div class="input_block form-group">
                                    <label for="name">Имя:</label>
                                    <input type="text" class="form-control" name="name"  maxlength="20" value="{$userFirstName}">
                                </div>
                            </fieldset>
                            <!--<div class="input_block form-group">
                                <label for="secondname">Отчество:</label>
                                <input type="text" name="secondname">
                            </div>-->
                        </div>
                    </div>
                </div>
               <!-- <div class="avatar">
                    <a href=""><img src="/templates/shop/image/profile_imgs/ava_img.jpg" alt=""></a>
                </div>-->
            </article>                                
                     
            <style>
                #balance {
                    height: 50px;
                    background: #fff;
                    margin-bottom: 50px;
                }
                .balance-top #balance-title {
                    text-align: right;
                    margin-left: 0px;
                }
                #balance .inform_long {
                    width: 40% !important;
                }
                .balance-top {
                    width: 100%;
                }
                #balance .inform_long  .inform_block {
                    text-align: center;
                }
                #balance .inform_long  .inform_block span {
                    font-size: 15px;
                    font-weight: 700;
                }
                #balance .inform_long  .inform_block span:nth-child(2) {
                    color: #51a3af;
                }
                #balance .inform_long  .inform_block button {
                    font-family: 'CenturyGothic-Bold';
                    padding: 0px 25px;
                }
                @media only screen and (max-width: 1024px) {
                    #balance .inform_long {
                        float: none !important;
                        margin-left: 28px;
                        width: 45% !important;
                    }
                    .balance-top #balance-title {
                        text-align: center;
                        margin-left: 28px;
                    }
                }
                @media only screen and (max-width: 480px) {
                    #balance .inform_long {
                        width: 75% !important;
                        margin-left: 0px;
                    }
                }
            </style>
            <article>
                <div class="balance-top">
                    <div class="title" id="balance-title">Баланс</div>
                </div>
                <div id="balance" class="inform_wrapper">                  
                    <div class="inform inform_long pull-right">
                        <div class="inform_block">
                            <span>Cумма:</span> <span>{$discountGift} {$curSelect}</span>
                            <button type="submit" class="btn btn-primary" disabled="disabled">Пополнить</button>
                        </div>
                    </div>
                </div>
                
                <!-- popup link -->
                <a href="#discountGift-popup" class="open-popup-link hidden">Show inline popup</a>

                <div class="title">Данные для доставки</div>
				<div class="inform_wrapper">
                    <div class="inform inform_long">
                        <div class="inform_block">
                            <!--<div class="input_block form-group">
                                <label for="recipient">Получатель:</label>
                                <input type="text" class="form-control" maxlength="50" name="recipient">
                            </div>-->
                            <div class="input_block form-group">
                                <label for="delivery_method">Перевозчик:</label>
                                <select data-placeholder="Выберете перевозчика" id='profile-delivery-method' name='delivery' data-delivery="{$userDelivery}">
                                    <option></option>
                                </select>
                            </div>
                            <div class="input_block form-group">
                                <label for="warehouse-number">Номер склада</br>
                                    и улица:</label>
                                <select data-placeholder="{$userWarehouse}" name='warehouse' id='profile-warehouse'>
                                    <option></option>
                                </select> 
                                <input type="text" id="write_warehouse_profile" class="form-control hidden" maxlength="50" name="warehouse" value="{$userWarehouse}">
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            <article>
                <div class="title">Контакты</div>
				<div class="inform_wrapper">
                    <div class="inform inform_long">
                        <div class="inform_block">
                            <div class="input_block">
                                <div id="select-country-radio-profile">
                                    <label for="country">Укажите страну:</label>
                                    <div class="countries-inputs-wrapper">
	                                    <input type="radio" name = "country" value = "1" {$countryChecked1} id = "country-profile-radio1"><label for="country-profile-radio1">Украина</label>
	                                    <input type="radio" name = "country" value = "2" {$countryChecked2} id = "country-profile-radio2"><label for="country-profile-radio2">Россия</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input_block form-group">
                                <label for="birthday">Дата рождения:</label>
                                <input data-inputmask="'alias': 'date'" class="form-control" name="birthday" value="{$userBirthDay}"/>
                            </div>
                            <fieldset disabled>
                                <div class="input_block form-group">
                                    <label for="email">Электронная Почта:</label>
                                    <input type="text" class="form-control" name="email" value="{$userEmail}">
                                </div>
                            </fieldset>
                            <div class="input_block form-group">
                                <label for="city">Город:</label>
                                <input type="text" id="autocomplete_city_profile" class="form-control" maxlength="50" name="city" value="{$userCity}" placeholder="Город" title=" ">
                            </div>
                            <div class="input_block form-group">
                                <label for="address">Адрес:</label>
                                <input type="text" class="form-control" id="autocom_adres_profile" maxlength="70" name="address" value="{$userAddress}" placeholder="Адрес">
                            </div>
                            <div class="input_block form-group">
                                <label for="phone">Телефон:</label>
                                <input type="text" class="form-control" id="profile-phone" name="phone" value="{$userPhone}">
                            </div>
                            <div class="input_block form-group">
                                <label for="skype">Skype:</label>
                                <input type="text" class="form-control" maxlength="50" name="skype" value="{$userSkype}">
                            </div>
                            <div class="input_block form-group">
                                <label for="site">Web-сайт:</label>
                                <input type="text" class="form-control" maxlength="50" name="site" value="{$userSite}">
                            </div>
                        </div>
                    </div>
                </div>
            </article>					
            <article>
                <div class="title">Безопасность</div>
				<div class="inform_wrapper">
                    <div class="inform inform_long">
                        <div class="inform_block">
                            <form id="change-password-form" role="form">
                                <p>Сменить пароль:</p>
                                <div class="input_block form-group">
                                    <label for="name">Введите старый пароль:</label>
                                    <input type="password" name="current-password" class="form-control password-necessary">
                                </div>
                                <div class="input_block form-group">
                                    <label for="name">Введите новый пароль:</label>
                                    <input type="password" name="new-password" class="form-control password-necessary password-field">
                                </div>
                                <div class="input_block form-group">
                                    <label for="name">Повторите новый пароль:</label>
                                    <input type="password" name="confirm-new-password" class="form-control password-necessary password-field">
                                </div>
                                <button type="submit" class="btn btn-primary" id="password-btn" data-loading-text="Loading..." disabled="disabled">применить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>

<style>
.discountGift-popup {
    width: 320px;
    background-color: #fff;
    padding: 5px;
    margin: 0 auto;
    font-family: 'CenturyGothic';
    box-shadow: 0 0 10px 2px rgba(0,0,0,.3);
    position: relative;
    color: #666;
}
.discountGift-popup .custom-border {
    padding: 30px 20px 20px;
}
#discountGift-popup .custom-border > p, .discountGift-popup .popup-title {
    width: 100%;
    font-size: 14px;
}
#discountGift-popup .custom-border > p {
        text-align: center;
}
.discountGift-popup .popup-title p {
    line-height: 24px;
    font-size: 16px;
    display: inline-block;
    position: relative;
    text-transform: uppercase;
    font-family: 'CenturyGothic-Bold';
    color: #666;
    letter-spacing: 2px;
}
.discountGift-popup .d-table {
    border-collapse: separate;
    border-spacing: 4px;
    margin: 7px auto;
}
.discountGift-popup .d-table .d-table-cell {
    background-color: #f7f7f7;
    padding: 15px;
    vertical-align: middle;
}
.discountGift-popup .d-table .d-table-cell .item-title {
    text-transform: uppercase;
    color: #51a3af;
    font-family: 'CenturyGothic-Bold';
    font-size: 33px;
}
.discountGift-popup .btn-green {
    width: 100%;
    float: left;
    display: inline-block;
    color: #fff;
    margin-top: 5px;
    font-size: 18px;
    font-family: 'CenturyGothic-Bold';
    border-radius: 0;
}
.discountGift-popup .popup-title p::before {
    content: " ";
    position: absolute;
    right: -55px;
    top: -5px;
    background: url(../image/profile_imgs/money.png) no-repeat;
    width: 47px;
    height: 39px;
}
</style>                            
<!--  action 150 profile popup   -->
<div id="discountGift-popup" class="discountGift-popup mfp-hide">
        <div class="custom-border">
            <div class="popup-title"><p>Ваш баланс пополнен</p></div>
            <p>Сумма зачисления состовляет</p>
            <div class="d-table">
                <div class="d-table-row">
                <div class="d-table-cell">
                    <p class="item-title">
                        {$discountGift}.00 {$curSelect}
                    </p>
                </div>
            </div>
            </div>
            <button type="button" class="btn btn-green btn-3d">Ок</button>                          
        <div class="clear"></div>
    </div>
</div>                             