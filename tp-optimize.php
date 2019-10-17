<html>
<head>
    <title>Download Redirect</title>
    <meta http-equiv="refresh" content="" id="reload" />
    <meta name="robots" content="noindex" />
    <?php wp_head();?>
</head>
<body class="wx-page-download">
<?php
global $wp;
$url= add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) );
$arrURL = explode('/', $url);
$call = new WX_Optimize_Download_Buttom();


?>
    <div class="wx-box-page">
        <div class="wx-ads-box">
            <?php if(isset($call->wx_odb_data['ads1'])) echo $call->wx_odb_data['ads1'];?>
        </div>
        <div class="wx-box-second">
            <img src="<?php echo plugin_dir_url( __FILE__ ).'images/loading.gif'?>">
            <span id="countdowntimer"><?php echo $call->wx_odb_data['second']?></span>
        </div>
        <p class="wx-note-second"><?php _e('Bạn vui lòng đợi sau vài giây kết thúc để chuyển hướng tải xuống...', 'wx-odb')?></p>
        <div class="wx-ads-box">
            <?php if(isset($call->wx_odb_data['ads2'])) echo $call->wx_odb_data['ads2'];?>
        </div>
        <script type="text/javascript">
            document.addEventListener('contextmenu', event => event.preventDefault());
            document.onkeydown = function(e) {
                if (e.ctrlKey &&
                    (e.keyCode === 67 ||
                        e.keyCode === 86 ||
                        e.keyCode === 85 ||
                        e.keyCode === 117)) {
                    return false;
                } else {
                    return true;
                }
            };
            var timeleft = <?php echo $call->wx_odb_data['second']?>;
            var downloadTimer = setInterval(function(){
                timeleft--;
                document.getElementById("countdowntimer").textContent = timeleft;
                if(timeleft <= 0){
                    var urldecode = '<?php echo WX_Optimize_Download_Buttom::decode(end($arrURL), 'wx-odp-url');?>';
                    jQuery('#reload').attr('content', "0, URL="+urldecode+"");
                    clearInterval(downloadTimer);
                }

            },1000);
        </script>
    </div>
    <?php wp_footer();?>
</body>
</html>