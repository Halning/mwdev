
<div class="comments-wrap">
    <div class="user-name"><i class="fa fa-user"></i>{$userName}</div>
    <ul class="rating-small">
        {$commentsRating}
    </ul>
    <span>{$commentsDate}</span>
    <p>{$commentsText}</p>
    <p><b>Достоинства: </b>{$commentsPlus}</p>
    <p><b>Недостатки: </b>{$commentsMinus}</p>
    <div class="row">
        <div class="show-answers">Смотреть ответы (<span>{$answersAmount}</span>)</div>
        <div class="toggle-answer btn-gray btn-gray-3d">Ответить</div>
    </div>
</div>  

<div class="write-answer">
    <div class="comments-wrap">
        <form action="" class="coments-form clearfix" role="form">
            <div class="input-grup clearfix">
                <div class="coments-textarea">
                    <div class="form-group">
                        <textarea class="form-control comment-field answer-necessary" maxlength="300" placeholder="Комментарий *"></textarea>
                    </div>  
                </div>
                        
                <div class="input-area">
                    <div class="radio clearfix">
                        <div class="form-group">
                            <input type="text" class="form-control comment-field answer-necessary" id="comment-user-name"  value="{$commentUserName}" maxlength="30" placeholder="Имя *">
                        </div>
                    </div>
                    <div class="submit clearfix">
                        <button class="btn btn-md btn-block btn-primary btn-answer-form" disabled="disabled" value="" data-id="{$commentId}">Ответить</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div> 

<div class="answers">
    {$answers}
</div>