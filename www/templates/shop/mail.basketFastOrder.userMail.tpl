
<tr height="200">
    <td width="100%">
        <a style="margin-left:10px;" href="http://{$hostName}/" target="_blank"><img src="http://{$hostName}/templates/shop/image/logo.png" alt="makewear.com" border="0"></a>
        <p style="font-family:CenturyGothic;font-size:16px;margin:30px 0 10px 10px;">Здравствуйте, {$userName}</p>
        <p style="font-family:CenturyGothic;font-size:16px;margin:10px 0 10px 10px;">
            Ваш заказ принят в обработку<!--, за его состоянием вы можете наблюдать в <a>личном кабинете</a>.-->
        </p>
        <p style="font-family:CenturyGothic;font-size:16px;margin:50px 0 0 10px;font-weight:600">Заказ № {$orderCode} от {$orderDate}</p>
        <hr color="#696969" noshade size="1">
    </td>
</tr>

<tr>
    <td>
        <table width="400px" cellpadding="0" cellspacing="0" border="1" style="border-color:#000;border-collapse:collapse;margin:5px 0;padding:5px;">
            <tbody>
                <tr style="background-color: rgb(100,100,100);">
                    <th colspan="2">
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#fff" style="font-size:16px;"><b>Детали заказа</b></font></p>
                    </th>
                </tr>
                <tr>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;"><b>Время заказа</b></font></p>
                    </td>

                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;">{$orderDate}</font></p> 
                    </td>
                </tr> 
                <tr>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;"><b>Статус</b></font></p>
                    </td>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;">Проверка наличия</font></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;"><b>Получатель</b></font></p>
                    </td>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;">{$userName}</font></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;"><b>Город</b></font></p>
                    </td>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;">{$userCity}</font></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;"><b>Курьерская служба</b></font></p>
                    </td>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;">{$deliveryMethodName}</font></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;"><b>Адрес доставки</b></font></p>
                    </td>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;">{$userAddress}</font></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;"><b>Телефон</b></font></p>
                    </td>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;">{$userTel}</font></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;"><b>Email</b></font></p>
                    </td>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;">{$userEmail}</font></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;"><b>Комментарий</b></font></p>
                    </td>
                    <td>
                        <div style="width: 250px;word-wrap: break-word;">
                            <p style="margin:3px 5px"><font face="CenturyGothic" color="#000" style="font-size:14px;">{$userComments}</font></p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="99%" cellpadding="0" cellspacing="0" border="1" style="border-bottom:none;border-left:none;border-right:none;border-color:#000;border-collapse:collapse;margin:20px 0;padding:5px;">
            <tbody>
                <tr style="background-color: rgb(100,100,100);">
                    <th align="center">
                        <p style="margin:5px 2px;text-align: center;"><font face="CenturyGothic" color="#fff" style="font-size:16px;"><b>Бренд</b></font></p>
                    </th>
                    <th>
                        <p style="margin:5px 2px;text-align: center;"><font face="CenturyGothic" color="#fff" style="font-size:16px;"><b>Артикул</b></font></p>
                    </th>
                    <th>
                        <p style="margin:5px 2px;text-align: center;"><font face="CenturyGothic" color="#fff" style="font-size:16px;"><b>Цвет</b></font></p>
                    </th>
                    <th>
                        <p style="margin:5px 2px;text-align: center;"><font face="CenturyGothic" color="#fff" style="font-size:16px;"><b>Размер</b></font></p>
                    </th>
                    <th>
                        <p style="margin:5px 2px;text-align: center;"><font face="CenturyGothic" color="#fff" style="font-size:16px;"><b>Кол-во</b></font></p>
                    </th>
                    <th>
                        <p style="margin:5px 2px;text-align: center;"><font face="CenturyGothic" color="#fff" style="font-size:16px;"><b>Цена</b></font></p>
                    </th>
                    <th>
                        <p style="margin:5px 2px;text-align: center;"><font face="CenturyGothic" color="#fff" style="font-size:16px;"><b>Сумма</b></font></p>
                    </th>
                    <th>
                        <p style="margin:5px 2px;text-align: center;"><font face="CenturyGothic" color="#fff" style="font-size:16px;"><b>Ссылка на товар</b></font></p>
                    </th>
                </tr>                  
                {$basketFastOrderMailLines}                                                                 
                <tr>
                    <td style="border:none"><p style="text-align:center"><font face="CenturyGothic" color="#000" style="font-size:16px;"><b>Итого:</b></font></p></td>
                    <td style="border:none"></td>
                    <td style="border:none"></td>
                    <td style="border:none"></td>
                    <td style="border:none"><p style="text-align:center"><font face="CenturyGothic" color="#000" style="font-size:16px;"><b>{$comTotalCount} ед.</b></font></p></td>
                    <td style="border:none"></td>
                    <td style="border:none"><p style="text-align:center"><font face="CenturyGothic" color="#000" style="font-size:16px;"><b>{$comSumPrice} {$cur_show}</b></font></p></td>
                    <td style="border:none"></td>
                    <td style="border:none"></td>
                </tr>

            </tbody>         
        </table> 
        <table width="400px" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;margin:20px 5px 5px;padding:5px;"> 
            <tbody>
                <tr>
                    <td>
                        <p style="margin:5px"><font face="CenturyGothic" color="#000" style="font-size:16px;"><b>Количество товаров:</b></font></p>
                    </td>                                                           
                    <td>
                        <p style="margin:5px"><font face="CenturyGothic" color="#000" style="font-size:16px;">{$comTotalCount} ед.</font></p> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin:5px"><font face="CenturyGothic" color="#000" style="font-size:16px;"><b>Стоимость товара:</b></font></p>
                    </td>
                    <td>
                        <p style="margin:5px"><font face="CenturyGothic" color="#000" style="font-size:16px;">{$comSumPrice} {$cur_show}</font></p>
                    </td>
                </tr>
                <!--<tr>
                    <td>
                        <p style="margin:5px"><font face="CenturyGothic" color="#000" style="font-size:16px;"><b>Комиссия(3%):</b></font></p>
                    </td>
                    <td>
                        <p style="margin:5px"><font face="CenturyGothic" color="#000" style="font-size:16px;">{$commisionPrice} {$cur_show}</font></p>
                    </td>
                </tr>-->
                <tr>
                    <td>
                        <p style="margin:5px"><font face="CenturyGothic" color="#000" style="font-size:16px;"><b>Стоимость доставки:</b></font></p>
                    </td>
                    <td>
                        <p style="margin:5px"><font face="CenturyGothic" color="#000" style="font-size:16px;">{$deliveryPrice} {$cur_show}</font></p>
                    </td>
                </tr>
                <tr {$hidden}>
                    <td>
                        <p style="margin:5px"><font face="CenturyGothic" color="#000" style="font-size:16px;"><b>Подарок:</b></font></p>
                    </td>
                    <td>
                        <p style="margin:5px"><font face="CenturyGothic" color="#000" style="font-size:16px;">{$discountGift} {$cur_show}</font></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin:5px"><font face="CenturyGothic" color="#000" style="font-size:16px;"><b>Сумма к оплате:</b></font></p>
                    </td>
                    <td>
                        <p style="margin:5px"><font face="CenturyGothic" color="#000" style="font-size:16px;"><b>{$totalSumm} {$cur_show}</b></font></p> 
                    </td>
                </tr> 
            </tbody>
        </table>
        <hr color="#696969" noshade size="1">            
</tr>
