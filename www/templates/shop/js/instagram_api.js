$(document).ready(function () {
    var user_id = "1791546862";
    //var token="a87de3aecdb74104ab6a82b0f1fa5f44";
    var token = "1791546862.cf0499d.90406e5803214a8a849d1b55c39a438d";
    var count = 20;
    $.ajax({
        type: "GET",
        dataType: "jsonp",
        url: "https://api.instagram.com/v1/users/" + user_id + "/media/recent/?count=" + count + "&access_token=" + token,
        success: function (data) {
            for (var i = 0; i < data["data"].length; i++) {

                var src = data.data[i].images.low_resolution["url"];
                var w = data.data[i].images.low_resolution["width"];
                var h = data.data[i].images.low_resolution["height"];
                var link = data.data[i].link;

                var txt = "<div><a href=" + link + " target='_blank' ><div class='img-wrap' onclick='cl_insta(this.id)' ><img src=" + src + " /><i class='fa fa-instagram'></i></div></a></div>";
                $("#instagram_image").append(txt);

            }
//------------------------------------------------------------------------------
//                      Слайдер Instagram                                     
//------------------------------------------------------------------------------    

            $('#instagram_image').slick({
                slidesToScroll: 5,
                autoplaySpeed: 3000,
                variableWidth: true,
                dots: true,
                responsive: [
                    {
                        breakpoint: 1234,
                        settings: {
                            slidesToScroll: 5,
                            infinite: true
                        }
                    },
                    {
                        breakpoint: 1050,
                        settings: {
                            slidesToScroll: 4,
                            infinite: true
                        }
                    },
                    {
                        breakpoint: 850,
                        settings: {
                            centerMode: true,
                            infinite: true
                        }
                    },
                    {
                        breakpoint: 650,
                        settings: {
                            centerMode: true,
                            dots: false
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            centerMode: true,
                            dots: false
                        }
                    }
                ]
            });
        }
    });
});
