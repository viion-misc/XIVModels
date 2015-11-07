<div class="welcome_window" style="display:none;">
    <img src="images/misc/cross.png" onclick="$('.welcome_window').fadeOut();" class="closebutton" style="margin: 10px 10px 0 15px;float: right;cursor:pointer;">
    <div class="hud-page-content" style="padding:50px;">

        <div align="center" class="hud-page-title">Welcome to XIVModels!</div>
        <div style="padding:10px 60px;" align="center"><? echo TranslateWord("Controls for the model viewer differ between browsers, see below for details! This model viewer will only work
        in up to date WebGL browsers such as Chrome and Firefox.", $Language); ?></div>

        <div align"left" style="font-family:Verdana;font-size:11px;">
           
            <div style="width:300px;float:left;margin:0 0 0px 50px;">
                <h3 style="font-weight:normal;">Mouse Controls: <span style="color:#53B1FF;">Chrome</span></h3>
                <ul style="line-height:23px;">
                    <span style="opacity:0.5;">Rotate:</span> Left Click + Drag<br>
                    <span style="opacity:0.5;">Pan:</span> Wheel Click + Drag<br>
                    <span style="opacity:0.5;">Zoom:</span> Wheel Scroll<br>
                </ul>
            </div>

            <div style="width:300px;float:right;margin:0 50px 0px 0;">
                <h3 style="font-weight:normal;">Mouse Controls: <span style="color:#53B1FF;">Firefox</span></h3>
                <ul style="line-height:23px;">
                    <span style="opacity:0.5;">Rotate:</span> Left Click + Drag<br>
                    <span style="opacity:0.5;">Pan:</span> CTRL + Left Click + Drag<br>
                    <span style="opacity:0.5;">Zoom:</span> Shift + Left Click + Drag (up/down)<br>
                </ul>
            </div>
            <br style="clear:both;" />

            <div style="margin:10px 0 0 270px;line-height:23px;">
                <h3>Keyboard Controls</h3>
                <span style="opacity:0.5;">Rotate:</span> Q,E,Z,X <span style="opacity:0.5;">or</span> I,J,K,L <span style="opacity:0.5;">or</span> (Numpad) 8,4,2,6<br>
                <span style="opacity:0.5;">Pan:</span> S,W,A,D <span style="opacity:0.5;">or</span> Arrow Keys<br>
                <span style="opacity:0.5;">Zoom:</span> (Numpad) -,+ <span style="opacity:0.5;">or</span> (Numpad) 9,3<br>
                <span style="opacity:0.5;">Reset Position:</span> Home key.
            </div>


        </div>

    </div>
</div>