<?php
    $uiconf = array(
        "uiname"    => "uuiForm", // protected
        "addCMD"    => "cd ../;svn add uuiForm;svn up;svn ci -m 'add uuiForm';",// add ui to svn,exc after create
        "ciCMD"     => "cd ../;svn up;svn add uuiForm/*;svn ci ",// commit code
        "deployCMD" => "cp -r build/* /search/uidoc/files/;cd ../;cp -r uuiForm /search/uidoc/doc/;", // cmd to deploy ur ui project
        "path"      => "/search/ui/bin", // protected
        "yui"       => "/etc/uyui/yui.jar", // where ur yui set up
        "arc"       => true,// use arc to review ur code
    );
?>
