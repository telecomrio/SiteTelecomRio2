<php?
	<div id="flashContent">

<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
        width="940" height="85">
           
    <param name="movie" value="<?php echo $live_site; ?>/modules/mod_RelogioRegiao/tmpl/hora.swf?cidade1=LosAngeles&cidade2=NovaIorque&cidade3=Rio&cidade4=Londres&cidade5=Toquio&HorarioDeVerao=<?php print $veraoServer; ?>" />
    <param name="quality" value="high" />
    <param name="bgcolor" value="#FFFFFF" />
    <param name="FlashVars" 
    value="hora1=<?php print $LosAngeles; ?>
    &minutos1=<?php print $minutoLosAngeles; ?>
    &segundos1=<?php print $segundoLosAngeles; ?>
    &hora2=<?php print $NovaIorque; ?>
    &minutos2=<?php print $minutoNovaIorque; ?>
    &segundos2=<?php print $segundoNovaIorque; ?>
    &hora3=<?php print $Rio; ?>
    &minutos3=<?php print $minutoRio; ?>
    &segundos3=<?php print $segundoRio; ?>
    &hora4=<?php print $Londres; ?>
    &minutos4=<?php print $minutoLondres; ?>
    &segundos4=<?php print $segundoLondres; ?>
    &hora5=<?php print $Toquio; ?>
    &minutos5=<?php print $minutoToquio; ?>
    &segundos5=<?php print $segundoToquio; ?>"/>
    <!--<param name="FlashVars" value="teste=coisa" />-->
   
    <!--[if !IE]> <-->
    <object data="<?php echo $live_site; ?>/modules/mod_RelogioRegiao/tmpl/hora.swf?cidade1=LosAngeles&cidade2=NovaIorque&cidade3=Rio&cidade4=Londres&cidade5=Toquio&HorarioDeVerao=<?php print $veraoServer; ?>"
            width="940" height="85"
            type="application/x-shockwave-flash">
        <param name="quality" value="high" />
        <param name="bgcolor" value="#FFFFFF" />
        <param name="pluginurl" value="http://www.adobe.com/go/getflashplayer" />
        <param name="FlashVars" 
        value="hora1=<?php print $LosAngeles; ?>
        &minutos1=<?php print $minutoLosAngeles; ?>
        &segundos1=<?php print $segundoLosAngeles; ?>
        &hora2=<?php print $NovaIorque; ?>
    	&minutos2=<?php print $minutoNovaIorque; ?>
    	&segundos2=<?php print $segundoNovaIorque; ?>
        &hora3=<?php print $Rio; ?>
        &minutos3=<?php print $minutoRio; ?>
    	&segundos3=<?php print $segundoRio; ?>
        &hora4=<?php print $Londres; ?>
    	&minutos4=<?php print $minutoLondres; ?>
    	&segundos4=<?php print $segundoLondres; ?>
        &hora5=<?php print $Toquio; ?> 
        &minutos5=<?php print $minutoToquio; ?>
    	&segundos5=<?php print $segundoToquio; ?>"/>
        <!--<param name="FlashVars" value="teste=coisa" />-->
    </object>
    <!--> <![endif]-->
   
</object>
</div>
