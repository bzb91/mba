/**
 * Created by Burak on 2.5.2018.
 */
$.ajaxSetup({
    async: false
});

var serviceurl = "http://192.168.0.150:8059/egitim/services/";
var mainurl = "http://192.168.0.150:8059/egitim/";

/*var serviceurl = "http://192.168.0.150:8059/egitim/services/";
var mainurl = "http://192.168.0.150:8059/egitim/";*/
function postOgrenci(param1,param2,param3,param4){

    $.getJSON( serviceurl+"?method=postOgrenci&param1="+param1+"&param2="+param2+"&param3="+param3+"&param4="+param4, function( data ) {

        $.each(data, function (i, item) {

            window.localStorage.setItem('postsuccess',item.postsuccess);
            window.localStorage.setItem('ogrenci_id',item.ogrenci_id);
            var postsuccess=window.localStorage.getItem('postsuccess');
            console.log(postsuccess);

            if(postsuccess == 1){
                window.location.href = mainurl+"lesson_qrcode_tablet.html";
            }
        });
    });


}
function getBostaVideoGetir(param1,param2){

    String.prototype.format = function () {
        var args = arguments;
        return this.replace(/\{(\d+)\}/g, function (m, n) { return args[n]; });
    };
    var ReturnHtml = "";

    var videovarmi = 0;

    console.log("param1 "+param1);
    $.getJSON( serviceurl+"?method=getBostaVideoGetir&param1="+param1+"&param2="+param2, function( data ) {
        $.each(data, function (i, item) {
            videovarmi = 1;
            console.log("asgdfg "+item.video_id);
            window.localStorage.setItem('video_id',item.video_id);
            window.localStorage.setItem('sure',item.sure);
            $("#video_baslik").html(item.video_baslik);
            $("#fullscreen").show();
            $("#devam").show();

            ReturnHtml =  "<video id=\"MY_VIDEO_1\" class=\"video-js vjs-default-skin\" controls preload=\"auto\" poster=\"MY_VIDEO_POSTER.jpg\""+
                "data-setup='{}'>"+
                "<source  src='{0}' type='video/mp4'>"+
                "<source  src='{1}'type='video/webm'>"+
                "<p class=\"vjs-no-js\">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href=\"http://videojs.com/html5-video-support/\" target=\"_blank\">supports HTML5 video</a></p>"+
                "</video>";

            $(ReturnHtml.format(item.video_url,item.video_url.replace("mp4","webm"))).appendTo("#videos");



        });
    });

    console.log(videovarmi);
    if(videovarmi == 0){
        console.log("asda");
        $("#video_aciklama").html("Müsait video bulunamadı, lütfen bekleyiniz. Kısa süre içinde video yüklenecektir.");
        $("#fullscreen").hide();
        $("#devam").hide();
        window.localStorage.setItem('video_id',"");
        window.localStorage.setItem('sure',"");
    }
}

function getVideoyuSifirla(param1,param2){

    String.prototype.format = function () {
        var args = arguments;
        return this.replace(/\{(\d+)\}/g, function (m, n) { return args[n]; });
    };
    var ReturnHtml = "";
    //console.log(param1);
    $.getJSON( serviceurl+"?method=getVideoyuSifirla&param1="+param1, function( data ) {
        //console.log('Video sifirlamaya girildi');
        $.each(data, function (i, item) {
            console.log(item.postsuccess);
        });
    });
}

function postVideoIzlenme(param1,param2){

    $.getJSON( serviceurl+"?method=postVideoIzlenme&param1="+param1+"&param2="+param2, function( data ) {

        $.each(data, function (i, item) {

            window.localStorage.setItem('postsuccess',item.postsuccess);
            var postsuccess=window.localStorage.getItem('postsuccess');
            //console.log(postsuccess);
            if(postsuccess == 1){
                window.location.href = mainurl+"lesson_videos.html";
            }
        });
    });


}
function TumVideolarIzlendimi(param1){

    $.getJSON( serviceurl+"?method=TumVideolarIzlendimi&param1="+param1, function( data ) {

        $.each(data, function (i, item) {
            window.localStorage.setItem('postsuccess',item.postsuccess);
            var postsuccess=window.localStorage.getItem('postsuccess');
            //console.log(postsuccess);
            if(postsuccess == 1){
                //window.location.href = mainurl+"lesson_videos.html";
                $("#devam").show();
                $("#fullscreen").hide();
                $("#video_aciklama").html("Tüm ders videolarını izlediniz, dersi bitirip teste geçebilirsiniz.");
                return true;
            }
            else{
                return false;
            }

        });
    });


}
