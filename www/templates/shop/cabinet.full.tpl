<!--main-container-->
<div class="main-container">
    <!--main-content-->
    <div class="main-content row main-width" id="profile-conteiner-full">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 left-menu">
            {$cabineLeftMenu}
        </div>    
        <div id="pjax-profile-content" class="col-lg-10 col-md-10 col-sm-12 col-xs-12" data-salut="{$salutation}">
            {$profileContent}
        </div>
    </div>
    {$info}    
</div>
<script type="text/javascript" src="/templates/shop/js/profile.js"></script>
