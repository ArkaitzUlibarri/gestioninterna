<div class="loader">
	<div class="box">Loading...</div>
</div>

<style>

/* Start by setting display:none to make this hidden.
   Then we position it in relation to the viewport window
   with position:fixed. Width, height, top and left speak
   for themselves. Background we set to 80% white with
   our animation centered, and no-repeating */
.loader {
    display:    none;
    justify-content:center;
    position:   fixed;
    z-index:    1100;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .3 );
   /* background: rgba( 255, 255, 255, .8 ) 
                url('../images/loader.gif') 
                50% 50% 
                no-repeat;*/
}

.loader .box {
	/*margin: 40% auto;*/
	/*margin-top: 25%;*/
  align-self: center;
	margin-left: auto;
	margin-right: auto;

	font-size: 17px;
	width: 175px;
	padding-top: .8em;
	padding-bottom: .8em;
	text-align: center;
	background: #fff;
	border: 7px solid rgba(90, 90, 90, .5);
	-webkit-background-clip: padding-box; /* for Safari */
	background-clip: padding-box; /* for IE9+, Firefox 4+, Opera, Chrome */
}

/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden
body.loading {
    overflow: hidden;   
}
 */

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .loader {
    display: flex;
}


</style>