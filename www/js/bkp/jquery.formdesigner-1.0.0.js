/*
* jQuery Form-Designer plugin v1.0.0 <http://code.google.com/p/jquery-formdesigner-plugin/> 
* @requires jQuery v1.3.2 or later 
* is released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
(function($){
		/*
		Example 1 / form:
		-------------------------------------------
		<form class="formDesigner formDesignerSkip" action="" method="post">
				
			<select name="myTestBox_1" class="myCustomClass">
				<option value="">please choose</option>
				<option value="1">test 1</option>
				<option value="2">test 2</option>
				<option value="3">test 3</option>
			</select>
			<br /><br />
			
			
			<input id="inputRadio_1" class="myCustomClassForRadio" type="radio" name="myTestBox_3" value="1" /> 
			<label for="inputRadio_1">Radio Button 1</label>
			
			<input id="inputRadio_2" class="myCustomClassForRadio" type="radio" name="myTestBox_3" value="2" /> 
			<label for="inputRadio_2">Radio Button 2</label>
			
			<input id="inputRadio_3" class="myCustomClassForRadio" type="radio" name="myTestBox_3" value="3" /> 
			<label for="inputRadio_3">Radio Button 3</label>
			<br /><br />
			
			
			<br /><br />
			<input id="inputCheckbox_1" class="myCustomClassForCheckbox" type="checkbox" name="myTestBox_5" value="1" /> 
			<label for="inputCheckbox_1">Checkbox 1</label>
			
			<input id="inputCheckbox_2" class="myCustomClassForCheckbox" type="checkbox" name="myTestBox_5" value="2" /> 
			<label for="inputCheckbox_2">Checkbox 2</label>
			
			<input id="inputCheckbox_3" class="myCustomClassForCheckbox" type="checkbox" name="myTestBox_5" value="3" /> 
			<label for="inputCheckbox_3">Checkbox 3</label>
			
			
			<br /><br />
			<label for="inputText_1" class="clearfix">Input Textfield 1</label>
			<input id="inputText_1" class="myCustomClassForInput" type="text" name="myTestBox_7" value="" maxlength="255" />
			
			
			<br /><br />
			<div class="clearfix">
				<label for="textarea_1" class="clearfix">Textarea</label>
				<textarea id="textarea_1" class="myCustomClassForTextarea" name="myTestBox_9" cols="50" rows="10"></textarea>
			</div>
			
			
			<br /><br />
			<input type="submit" name="submitFormSubmit" value="Submit">
				
		</form>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery(".formDesigner").formDesigner();
			});
		</script>
	*/
	
	
	/*
		Example 2 / div style - select:
		-------------------------------------------
		<div class="formDesigner">
			<div class="formDesignerHeader">Testheader</div>
			<div class="formDesignerOptions">
				<a rel="nofollow" href="javascript:myCustomFunction();">call myCustomFunction()</a>
				<a rel="nofollow" href="http://www.steffenhollstein.de">www.steffenhollstein.de</a>
				<a rel="nofollow" href="">Option 3</a>
			</div>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery(".formDesigner").formDesigner();
			});
		</script>
	*/
	
		
	// Default options
	var defaults = {
		cssSkipParameter : {
			position : 'absolute',
			left : '-9999px',
			top : '-9999px',
			width : 'auto',
			height : 'auto'
		},
		SelectboxOptions_BorderSpacing : 2,
		SelectboxOptions_MaxHeight : 300,
		SelectboxOptions_OpenOptionListOnHover : false,
		
		elementWrapper : '.formDesigner',
		elementWrapperHeader : '.formDesignerHeader',
		elementWrapperOptions : '.formDesignerOptions',
		
		classNameHover : 'formDesignerHover',
		classNameChecked : 'formDesignerChecked',
		classNameDisabled : 'formDesignerDisabled',
		classNameSkip : 'formDesignerSkip',
		
		layoutAppendContainer_SelectBox : '<div class="formDesignerHeader"><div class="formDesignerHeaderText"></div><div class="formDesignerHeaderIcon"><!-- - --></div></div><div class="formDesignerOptions"></div>',
		layoutAppendContainer_InputRadio : '<div class="formDesignerHeader"><div class="formDesignerHeaderIcon"><!-- - --></div></div>',
		layoutAppendContainer_InputCheckbox : '<div class="formDesignerHeader"><div class="formDesignerHeaderIcon"><!-- - --></div></div>',
		layoutAppendContainer_InputSubmit : '<div class="formDesignerHeader"><div class="formDesignerHeaderIcon"><!-- - --></div></div>',
		
		layoutWrapContainer_SelectBox : '<div class="formDesigner formDesignerSelectBox"></div>',
		layoutWrapContainer_InputRadio : '<div class="formDesigner formDesignerInputRadio"></div>',
		layoutWrapContainer_InputCheckbox : '<div class="formDesigner formDesignerInputCheckbox"></div>',
		layoutWrapContainer_InputSubmit : '<div class="formDesigner formDesignerInputSubmit"></div>',
		layoutWrapContainer_InputText : '<div class="formDesigner formDesignerInputText"><div class="formDesignerHeader"><div class="formDesignerHeaderIcon"></div></div></div>',
		layoutWrapContainer_Textarea : '<div class="formDesigner formDesignerTextarea"><div class="formDesignerHeader formDesignerTextareaWrapper_0"><div class="formDesignerTextareaWrapper_1"><div class="formDesignerTextareaWrapper_2"></div></div><div class="formDesignerTextareaWrapper_3"><div class="formDesignerTextareaWrapper_4"><!-- - --></div></div></div></div>',
		
		copyClassNamesFromFormElement : true,
		
		callFunctionAfterInit : function(){}
	};
	

	jQuery.fn.formDesigner = function( options ) {
		
		
		// Merge defaults with user options
		var options = jQuery.extend( {}, defaults, options);
		
		
		// Return jQuery object to maintain chainability
		return this.each(function() {
			
			
			var thisObj = jQuery(this);
			var isForm = false;
			
			
			/*~~~~~~~~~~~~~ format form elements / BEGIN ~~~~~~~~~~~~~*/
			if( thisObj.is("form") ){// prepare layout Container
				
				
				isForm = true;
				
				
				// select
				var prepareObjSelect = jQuery("select", thisObj);
				
				prepareObjSelect.css( 
					options.cssSkipParameter 
				).wrap(
					options.layoutWrapContainer_SelectBox
				);
				
				jQuery("div.formDesignerSelectBox", thisObj).append( 
					options.layoutAppendContainer_SelectBox 
				);
				
				
				// input radio
				var prepareObjInputRadio = jQuery("input[type='radio']", thisObj);
				
				prepareObjInputRadio.css( 
					options.cssSkipParameter 
				).wrap(
					options.layoutWrapContainer_InputRadio 
				);
				
				jQuery("div.formDesignerInputRadio", thisObj).append( 
					options.layoutAppendContainer_InputRadio 
				);
				
				
				// input checkbox
				var prepareObjInputCheckbox = jQuery("input[type='checkbox']", thisObj);
				
				prepareObjInputCheckbox.css( 
					options.cssSkipParameter 
				).wrap(
					options.layoutWrapContainer_InputCheckbox 
				);
				
				jQuery("div.formDesignerInputCheckbox", thisObj).append( 
					options.layoutAppendContainer_InputCheckbox 
				);
				
				
				// input submit
				var prepareObjInputCheckbox = jQuery("input[type='submit'], input[type='button']", thisObj);
				
				prepareObjInputCheckbox.css( 
					options.cssSkipParameter 
				).wrap(
					options.layoutWrapContainer_InputSubmit
				);
				
				jQuery("div.formDesignerInputSubmit", thisObj).append( 
					options.layoutAppendContainer_InputCheckbox 
				);
				
				
				// input text
				jQuery("input[type='text']", thisObj).wrap(
					options.layoutWrapContainer_InputText 
				);
				
				// input password
				jQuery("input[type='password']", thisObj).wrap(
					options.layoutWrapContainer_InputPassword 
				);
				
				// textarea
				jQuery("textarea", thisObj).wrap(
					options.layoutWrapContainer_Textarea 
				);
				
				
			}
			/*~~~~~~~~~~~~~ format form elements / END ~~~~~~~~~~~~~*/
			
			
			
			
			/*~~~~~~~~~~~~~ initialize events / BEGIN ~~~~~~~~~~~~~*/
			jQuery(options.elementWrapper, thisObj).each(function(){
					
				if( isForm ){
					
					
					var formDesignerContainerObj = jQuery(this);
					var formDesignerHeaderObj = jQuery(".formDesignerHeader", formDesignerContainerObj);
					var formObjSelect = jQuery("select", formDesignerContainerObj);
					var formObjRadioCheckbox = jQuery("input[type='radio'], input[type='checkbox']", formDesignerContainerObj);
					var formObjSubmit = jQuery("input[type='submit'], input[type='button']", formDesignerContainerObj);
					var formObjText = jQuery("input[type='text']", formDesignerContainerObj);
					var formObjPassword = jQuery("input[type='password']", formDesignerContainerObj);
					var formObjTextarea = jQuery("textarea", formDesignerContainerObj);
					var headerObj = jQuery(options.elementWrapperHeader, formDesignerContainerObj);
					
					
					// select boxes - BEGIN
					if( formObjSelect.length > 0 ){
						
						
						if( options.SelectboxOptions_OpenOptionListOnHover ){
							var additionalClassName = '';
						} else {
							var additionalClassName = ' doNotOpenOnHover';
						}
						
						if( options.copyClassNamesFromFormElement ){
							formObjSelect.parent( options.elementWrapper ).addClass( formObjSelect.attr("class") + additionalClassName );
						} else {
							formObjSelect.parent( options.elementWrapper ).addClass( additionalClassName );
						}
						
						
						jQuery(".formDesignerHeaderText", formDesignerHeaderObj).text(
							jQuery("option:selected", formObjSelect).text()
						);
						
						
						var generatedOptionValues = '';
						
						jQuery("option", formObjSelect).each(function(i){
							
							var thisObj = jQuery(this);
							
							thisObj.attr({ 'formdesignerindex' : i });
							
							generatedOptionValues += '<a rel="nofollow" href="javascript:void(0);" formdesignerindex="' + i + '">' + thisObj.text() + '</a>';
							
						});
						
						
						var formDesignerOptionsObj = jQuery(".formDesignerOptions", formDesignerContainerObj);
						formDesignerOptionsObj.append( generatedOptionValues );
						
						jQuery("a", formDesignerOptionsObj).die("click").live("click", function(){
							
							var thisObj = jQuery(this);
							var getLinkAttribut = thisObj.attr("formdesignerindex");
							
							jQuery("option", formObjSelect).each(function(i){
								if( jQuery(this).attr("formdesignerindex") == getLinkAttribut ){
									jQuery(this).attr("selected", true);
								}
							});
							
							jQuery(".formDesignerHeaderText", formDesignerHeaderObj).text( 
								thisObj.text() 
							);
							
						});
					}
					// select boxes - END
					
					
					
					// input radio, checkbox - BEGIN
					if( formObjRadioCheckbox.length > 0 ){
						
						if( options.copyClassNamesFromFormElement ){
							formDesignerContainerObj.addClass( formObjRadioCheckbox.attr("class") );
						}
						
						
						// move <label>
						var getLabelObjPrev = formDesignerContainerObj.prev("label");
						var getLabelObjNext = formDesignerContainerObj.next("label");
						
						if( getLabelObjPrev.length > 0 ){
							getLabelObjPrev.prependTo( formDesignerContainerObj ).addClass("formDesignerLabel formDesignerLabelLeft");
						} else if( getLabelObjNext.length > 0 ){
							getLabelObjNext.appendTo( formDesignerContainerObj ).addClass("formDesignerLabel formDesignerLabelRight");
						}
						
						
						// is checked
						if( formObjRadioCheckbox.attr("checked") && !headerObj.hasClass( options.classNameChecked ) ){
							headerObj.addClass( options.classNameChecked );
						}
						
						// is disabled
						if( formObjRadioCheckbox.attr("disabled") && !formDesignerContainerObj.hasClass( options.classNameDisabled ) ){
							formDesignerContainerObj.addClass( options.classNameDisabled );
						}
						
						jQuery(".formDesignerHeader", formDesignerContainerObj).die("click").live("click", function(){
							
							if( !formDesignerContainerObj.hasClass( options.classNameDisabled ) ){
								
								if( formDesignerContainerObj.hasClass( "formDesignerInputCheckbox" ) ){
									
									if( headerObj.hasClass( options.classNameChecked ) ){
										formObjRadioCheckbox.attr("checked", false);
									} else {
										formObjRadioCheckbox.attr("checked", true);
									}
									
								} else {
									
									if( !headerObj.hasClass( options.classNameChecked ) ){
										formObjRadioCheckbox.attr("checked", true);
									}
								}
							}
						});
						
					}
					// input radio, checkbox - END
					
					
					
					// input submit - BEGIN
					if( formObjSubmit.length > 0 ){
						
						if( options.copyClassNamesFromFormElement ){
							formDesignerContainerObj.addClass( formObjSubmit.attr("class") );
						}
						
						jQuery( ".formDesignerHeader .formDesignerHeaderIcon", formDesignerContainerObj ).text( formObjSubmit.val() );
						
						// is disabled
						if( formObjSubmit.attr("disabled") && !formDesignerContainerObj.hasClass( options.classNameDisabled ) ){
							formDesignerContainerObj.addClass( options.classNameDisabled );
						}
						
						jQuery(".formDesignerHeader", formDesignerContainerObj).die("click").live("click", function(){
							if( !formDesignerContainerObj.hasClass( options.classNameDisabled ) ){
								formObjSubmit.click();
							}
						});
						
					}
					// input submit - END
					
					

					// input text - BEGIN
					if( formObjText.length > 0 ){
						
						if( options.copyClassNamesFromFormElement ){
							formDesignerContainerObj.addClass( formObjText.attr("class") );
						}
						
						// move <label>
						var getLabelObjPrev = formDesignerContainerObj.prev("label");
						var getLabelObjNext = formDesignerContainerObj.next("label");
						
						if( getLabelObjPrev.length > 0 ){
							getLabelObjPrev.prependTo( formDesignerContainerObj ).addClass("formDesignerLabel formDesignerLabelLeft");
						} else if( getLabelObjNext.length > 0 ){
							getLabelObjNext.appendTo( formDesignerContainerObj ).addClass("formDesignerLabel formDesignerLabelRight");
						}
						
						// is disabled
						if( formObjText.attr("disabled") && !formDesignerContainerObj.hasClass( options.classNameDisabled ) ){
							formDesignerContainerObj.addClass( options.classNameDisabled );
						}
						
					}
					// input text - END
					
					
					
					// input password - BEGIN
					if( formObjPassword.length > 0 ){
						
						if( options.copyClassNamesFromFormElement ){
							formDesignerContainerObj.addClass( formObjPassword.attr("class") );
						}
						
						// move <label>
						var getLabelObjPrev = formDesignerContainerObj.prev("label");
						var getLabelObjNext = formDesignerContainerObj.next("label");
						
						if( getLabelObjPrev.length > 0 ){
							getLabelObjPrev.prependTo( formDesignerContainerObj ).addClass("formDesignerLabel formDesignerLabelLeft");
						} else if( getLabelObjNext.length > 0 ){
							getLabelObjNext.appendTo( formDesignerContainerObj ).addClass("formDesignerLabel formDesignerLabelRight");
						}
						
						// is disabled
						if( formObjPassword.attr("disabled") && !formDesignerContainerObj.hasClass( options.classNameDisabled ) ){
							formDesignerContainerObj.addClass( options.classNameDisabled );
						}
						
					}
					// input password - END
					
					
					
					// textarea - BEGIN
					if( formObjTextarea.length > 0 ){
						
						if( options.copyClassNamesFromFormElement ){
							formDesignerContainerObj.addClass( formObjTextarea.attr("class") );
						}
						
						// move <label>
						var getLabelObjPrev = formDesignerContainerObj.prev("label");
						var getLabelObjNext = formDesignerContainerObj.next("label");
						
						if( getLabelObjPrev.length > 0 ){
							getLabelObjPrev.prependTo( formDesignerContainerObj ).addClass("formDesignerLabel formDesignerLabelLeft");
						} else if( getLabelObjNext.length > 0 ){
							getLabelObjNext.appendTo( formDesignerContainerObj ).addClass("formDesignerLabel formDesignerLabelRight");
						}
						
						// is disabled
						if( formObjTextarea.attr("disabled") && !formDesignerContainerObj.hasClass( options.classNameDisabled ) ){
							formDesignerContainerObj.addClass( options.classNameDisabled );
						}
						
					}
					// textarea - END
					
				}
				
			}).hover(function(){
				
				var thisObj = jQuery(this);
				var formObjSelect = jQuery("select", thisObj);
				
				if( formObjSelect.length > 0 && thisObj.hasClass("doNotOpenOnHover") ){
					thisObj.addClass("isSelectAndHovered");
				}
				
				if( !thisObj.hasClass( options.classNameDisabled ) ){
					initMouseOver({ 
						obj : thisObj,
						isSelectAndHovered : thisObj.hasClass("isSelectAndHovered")
					});
				}
				
			}, function (){
				
				var thisObj = jQuery(this);
				var formObjSelect = jQuery("select", thisObj);
				
				if( formObjSelect.length > 0 ){
					thisObj.removeClass("isSelectAndHovered");
				}
				
				initMouseOut({ obj : thisObj });
				
			}).click(function(){
				
				var thisObj = jQuery(this);
				var headerObj = jQuery(options.elementWrapperHeader, thisObj);
				var formObjSelect = jQuery("select", thisObj);
				var formObjRadioCheckbox = jQuery("input[type='radio'], input[type='checkbox']", thisObj);
				
				// select
				if( formObjSelect.length > 0 ){
					
					if( !headerObj.hasClass( options.classNameHover ) || thisObj.hasClass("isSelectAndHovered") ){
						
						thisObj.removeClass("isSelectAndHovered")
						headerObj.addClass( options.classNameHover );
						initMouseOver({ obj : thisObj });
						
					} else {
						initMouseOut({ obj : thisObj });
					}
					
				}
				
				// input radio, checkbox
				if ( formObjRadioCheckbox.length > 0  ) {
					
					if( thisObj.hasClass( "formDesignerInputCheckbox" ) ){
						
						if( formObjRadioCheckbox.attr("checked") && !headerObj.hasClass( options.classNameChecked ) ){
							headerObj.addClass( options.classNameChecked );
						} else {
							headerObj.removeClass( options.classNameChecked );
						}
						
					} else {
						
						if( formObjRadioCheckbox.attr("checked") && !headerObj.hasClass( options.classNameChecked ) ){
							
							var currentInputName = formObjRadioCheckbox.attr("name");
							
							jQuery("input[type='radio'][name='" + currentInputName + "'], input[type='checkbox'][name='" + currentInputName + "']").next(
								headerObj
							).removeClass( 
								options.classNameChecked 
							);
							
							headerObj.addClass( options.classNameChecked );
							
						}
					}
				}
				
			});
			/*~~~~~~~~~~~~~ initialize events / END ~~~~~~~~~~~~~*/
			
			
			
			
			/*~~~~~~~~~~~~~ after init / BEGIN ~~~~~~~~~~~~~*/
			options.callFunctionAfterInit();
			
			thisObj.removeClass( options.classNameSkip );
			/*~~~~~~~~~~~~~ after init / END ~~~~~~~~~~~~~*/
			
			
			
			
			/************ MouseOver - BEGIN ************/
			function initMouseOver(settings){
				
				
				var settings = jQuery.extend({
					obj : null,
					isSelectAndHovered : false
				}, settings || {} );
				
				
				if( settings.obj ){
					
					var thisObj = settings.obj;
					var headerObj = jQuery(options.elementWrapperHeader, thisObj);
					
					if( !headerObj.hasClass("active") ){
						headerObj.addClass(options.classNameHover);
					}
					
					if( thisObj.hasClass("formDesignerSelectBox") && !settings.isSelectAndHovered ){
						
						// header
						var getPosition = headerObj.position();
						var getHeight = headerObj.outerHeight();
						var getWidth = headerObj.outerWidth();
						
						// options
						var optionObj = jQuery(options.elementWrapperOptions, thisObj);
						
						if( getWidth > optionObj.outerWidth() ){
							optionObj.width( Math.abs( getWidth - options.SelectboxOptions_BorderSpacing ) );
						}
						
						if( options.SelectboxOptions_MaxHeight < optionObj.outerHeight() ){
							optionObj.height( options.SelectboxOptions_MaxHeight ).width( Math.abs( optionObj.width() + 18 ) );
						}
						
						optionObj.css({
							position : 'absolute',
							left : getPosition.left + 'px',
							top :  Math.abs( (getPosition.top + getHeight) - 1 ) + 'px'
						});
					}
				}
			}
			/************ MouseOver - END ************/
			
			
			
			
			/************ MouseOut - BEGIN ************/
			function initMouseOut(settings){
				
				var settings = jQuery.extend({
					obj : null
				}, settings || {} );
				
				if( settings.obj ){
					
					var thisObj = settings.obj;
					
					if( !jQuery(options.elementWrapperHeader, thisObj).hasClass("active") ){
						jQuery(options.elementWrapperHeader, thisObj).removeClass(options.classNameHover);
					}
					
					if( thisObj.hasClass("formDesignerSelectBox") ){
						jQuery(options.elementWrapperOptions, thisObj).css( options.cssSkipParameter );
					}
				}
			}
			/************ MouseOut - END ************/
			
			
			
		});
	};
	
	
})( jQuery );