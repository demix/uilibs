<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>jQuery ui plugin uuiForm</title>
    <style type="text/css">
        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed, 
        figure, figcaption, footer, header, hgroup, 
        menu, nav, output, ruby, section, summary,
        time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }
        /* HTML5 display-role reset for older browsers */
        article, aside, details, figcaption, figure, 
        footer, header, hgroup, menu, nav, section {
            display: block;
        }
        body {
            line-height: 1;
        }
        ol, ul {
            list-style: none;
        }
        blockquote, q {
            quotes: none;
        }
        blockquote:before, blockquote:after,
        q:before, q:after {
            content: '';
            content: none;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
    </style>
        <?php
        if(file_exists('conf.php'))require_once('conf.php');
        if(!isset($_GET['mobile'])){
            $dir = './css';
            if(is_dir($dir)){ 
                if($dp = opendir($dir)){ 
                    while(($file=readdir($dp)) != false){ 
                        if(!is_dir($dir.'/'.$file)){ 
                        echo '<link rel="stylesheet" href="./css/' . $file . '" type="text/css"/>';
                        } 
                    } 
                    closedir($dp); 
                }else{ 
                    exit('Not permission'); 
                } 
            }  
        }
        ?>
    <script src="http://d.123.sogou.com/jsn/lib/ufo.js"></script>
    <?php if(isset($uiconf)){?>
    <script>
    <?php 
        echo file_get_contents($uiconf['path'] . '/../base/base.js');
    ?>
    </script>
    <?php }?>
    <?php if(isset($_GET['mobile'])){ ?>
    <script src="./build/js/mobile.uuiForm.js"></script>
    <?php }else{ ?>
    <script src="./js/uuiForm.js"></script>
    <?php }?>
    <link rel="stylesheet" href="http://ufo.sogou-inc.com/pingback/main.css" type="text/css" media="screen"/>
</head>
<body>
<div class="header">
        <h1>
            <img src="http://ufo.sogou-inc.com/static/logo/logo-white.png" class="" alt="" style="padding:0 20px 0 0;position:relative;top:5px;">
            uuiForm TEST page
        </h1>
    </div>
    <div class="content">
        <div class="content-inner">
            <p><a href="./doc/uuiForm/index.html" target="_blank">preview doc[exc "ui doc first"]</a></p>
            <br />
                <p>                                                                                                           
                <?php if(isset($_GET['mobile'])){ ?>                                                                      
                      <a href="#" onclick="location.search=location.search.replace(/[&]?mobile=1/g,'')">test version for pc</a>      
                   <?php }else{ ?>                                                                                           
                    <a href="#" onclick="location.search+='&mobile=1'">test version for mobile[build first]</a>
                <?php }?>                                                                                                 
                </p>       
            <br />
            <p>test code here:</p>
            <textarea id="code" style="width:600px;height:400px;">
$('#uuiFormBox').uuiForm({
    type:'blur',
    onsinglefail:function($el , name){
        if( !$el.parent().find('.error').length ){
            $el.parent().append('<span class="error"></span>');
        }
        $el.parent().find('.error').show().html( name=='require'?'填空':'错误');
    },
    onsinglesuccess: function($el){
        $el.parent().find('.error').hide();
    },
    onformfail: function(){
        return false;
    },
    onformsuccess: function(){
        alert('Success')
        return false;
    }
});

$.uuiForm.addType('custom' , function(value){
    return value == 123;
});
            </textarea>
            <input type=button value="run test" onclick="eval($('#code').attr('value'))"/>
            <p>test dom here:</p>
            <div id="uuiFormBox" style="background:#fff;padding:10px;">
                uuiForm Test
                <style type="text/css" media="screen">
                    #uuiFormWrapper label{display:block;}
                </style>
                <form method="" id="uuiFormWrapper" action="">
                    <label>
                        必填：
                        <input type="text" name="" value="" uui-type="require" />
                    </label>
                    <label>
                        手机：
                        <input type="text" name="" value="" uui-type="cellphone" />
                    </label>
                    <label>
                        邮箱 必填：
                        <input type="text" name="" value="" uui-type="require email" />
                    </label>
                    <label>
                        数字：
                        <input type="text" name="" value="" uui-type="num" />
                    </label>
                    <label>
                        最小：
                        <input type="text" name="" value="" uui-type="min(5)" />
                    </label>
                    <label>
                        最大：
                        <input type="text" name="" value="" uui-type="max(5)" />
                    </label>
                    <label>
                        5-10之间：
                        <input type="text" name="" value="" uui-type="range(5,10)" />
                    </label>
                    <label>
                        自定义(必填只能填1)：
                        <input type="text" name="" value="" uui-reg="^1+$" />
                    </label>
                    <label>
                        自定义(必填只能123)：
                        <input type="text" name="" value="" uui-type="custom"/>
                    </label>
                    <button type="submit">submit</button>
                </form>
                       
            </div>
        </div>
    </div>
    <div class="footer">
        &copy;2012 ufo@sogou-inc
    </div>
<!--
    write ur test here
-->
<script>
    /*#main#*/
</script>
</body>
</html>
