<?php
/**
 * @version    $Id$
 * @package    IG_Plugin_Framework
 * @author     InnoThemes Team <support@innothemes.com>
 * @copyright  Copyright (C) 2012 InnoThemes.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innothemes.com
 * Technical Support:  Feedback - http://www.innothemes.com/contact-us/get-support.html
 */

class IG_Gadget_Uniform_Js_Form extends IG_Gadget_Base {

	/**
	 * Gadget file name without extension.
	 *
	 * @var  string
	 */
	protected $gadget = 'uniform-js-form';

	/**
	 * Constructor.
	 *
	 * @return  void
	 */
	public function __construct() {

	}

	/**
	 *  set default action
	 */
	public function default_action() {
		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
		auth_redirect();
		header( 'Content-Type: application/javascript' );
		$actionFormInit = array();
		$actionFormInit = apply_filters( 'ig_uniform_js_form_action_form_init', $actionFormInit );
		$createPrototypeForm = array();
		$createPrototypeForm[ 'init' ] = 'init:function () {
				                var self = this;
				                this.visualDesign = new JSNVisualDesign("#form-container", this.params);
				                this.JSNLayoutCustomizer = new JSNLayoutCustomizer(this.visualDesign, this.lang);
				                this.selectPostAction = $("#jform_form_post_action");
				                this.inputFormTitle = $("#jform_form_title");
				                this.btnAddPageForm = $(".new-page");
				                this.btnSelectFormStyle = $("#select_form_style");
				                var idForm = $("#jform_form_id").val();
				                this.menuToolBar = $("#ig-menu-item-toolbar-menu ul li a");
				                colorScheme = $("#jform_form_theme").val();
				                var editorCustomStyle = CodeMirror.fromTextArea(document.getElementById("style_custom_css"), {
				                    lineNumbers:true,
				                    styleActiveLine:true,
				                    matchBrackets:true
				                });
				                editorCustomStyle.on("keydown", function (cm, change) {
				                    $("#style_inline style.formstylecustom").html(cm.getValue());
				                });
				                editorCustomStyle.on("keyup", function (cm, change) {
				                    $("#style_inline style.formstylecustom").html(cm.getValue());
				                });

				                $(".jsn-tabs").tabs({
				                    selected:0,
				                    show:function (event, ui) {
				                        if ($(ui.tab).attr("href") == "#formCustomCss") {
				                            editorCustomStyle.refresh();
				                        }
				                    }
				                });

				                var Jsnwysiwyg = {
				                    // required
				                    name:"jsnwysiwyg",
				                    methodForRealLife:function (object, text) {
				                        // jQuery chain
				                        return object.each(function () {
				                            // standard operations
				                            var Wysiwyg = $(this).data("wysiwyg");
				                            if (!Wysiwyg) {
				                                return this;
				                            }
				                            // Plugin code
				                            // Wysiwyg gives access to all methods and properties, also
				                            // you can extend base functionality
				                            Wysiwyg.newPropertyName = "methodForRealLife";
				                            Wysiwyg.newMethodName = function () {
				                                this.setContent(text);
				                            };
				                            Wysiwyg.newMethodName();
				                        });
				                    }
				                };
				                // Register your plugin
				                $.wysiwyg.plugin.register(Jsnwysiwyg);
				                $("#action_data_uniform_show_message textarea").wysiwyg({
				                    controls:{
				                        bold:{ visible:true },
				                        italic:{ visible:true },
				                        underline:{ visible:true },
				                        strikeThrough:{ visible:true },
				                        justifyLeft:{ visible:true },
				                        justifyCenter:{ visible:true },
				                        justifyRight:{ visible:true },
				                        justifyFull:{ visible:true },
				                        indent:{ visible:true },
				                        outdent:{ visible:true },
				                        subscript:{ visible:true },
				                        superscript:{ visible:true },
				                        undo:{ visible:true },
				                        redo:{ visible:true },
				                        insertOrderedList:{ visible:true },
				                        insertUnorderedList:{ visible:true },
				                        insertHorizontalRule:{ visible:true },
				                        h4:{
				                            visible:true,
				                            className:"h4",
				                            command:($.browser.msie || $.browser.safari) ? "formatBlock" : "heading",
				                            arguments:($.browser.msie || $.browser.safari) ? "<h4>" : "h4",
				                            tags:["h4"],
				                            tooltip:"Header 4"
				                        },
				                        h5:{
				                            visible:true,
				                            className:"h5",
				                            command:($.browser.msie || $.browser.safari) ? "formatBlock" : "heading",
				                            arguments:($.browser.msie || $.browser.safari) ? "<h5>" : "h5",
				                            tags:["h5"],
				                            tooltip:"Header 5"
				                        },
				                        h6:{
				                            visible:true,
				                            className:"h6",
				                            command:($.browser.msie || $.browser.safari) ? "formatBlock" : "heading",
				                            arguments:($.browser.msie || $.browser.safari) ? "<h6>" : "h6",
				                            tags:["h6"],
				                            tooltip:"Header 6"
				                        },
				                        html:{ visible:true },
				                        increaseFontSize:{ visible:true },
				                        decreaseFontSize:{ visible:true }
				                    }
				                });
				                if ($("#jform_form_type").val() == 2) {
				                    $(".jsn-master #form-design #form-design-header").show();
				                } else {
				                    $(".jsn-master #form-design #form-design-header").hide();
				                }
				                this.selectPostAction.change(function () {
				                    $(".form-action-data .action-options").addClass("hide");
				                    $(".form-action-data .action-options#action_data_" + $(this).val()).removeClass("hide");

				                }).change();
				                $(".jsn-page-actions .prev-page").click(function () {
				                    $("#visualdesign-options").remove();
				                    $("#visualdesign-toolbox").remove();
				                    self.prevpaginationPage();
				                });
				                $(".jsn-page-actions .next-page").click(function () {
				                    $("#visualdesign-options").remove();
				                    $("#visualdesign-toolbox").remove();
				                    self.nextpaginationPage();
				                });
				                $("#jform_form_type").change(function () {
				                    if ($(this).val() == 1) {
				                        if (confirm(self.lang["IG_UNIFORM_CONFIRM_CONVERTING_FORM"])) {
				                            $(".jsn-master #form-design #form-design-header").hide();
				                            var dataValue = $(".jsn-page-list > li.page-items").attr("data-value");
				                            var dataText = $(".jsn-page-list > li.page-items > input").val();
				                            $("#form-design-header").attr("data-value", dataValue);
				                            $("#form-design-header .page-title h1").text(dataText);
				                            self.loadPage("join");
				                        } else {
				                            $("#jform_form_type option").each(function () {
				                                if ($(this).val() == 2) {
				                                    $(this).prop("selected", true);
				                                }
				                            });
				                        }
				                    } else {
				                        $(".jsn-master #form-design #form-design-header").show();
				                    }
				                });
				                this.btnAddPageForm.click(function () {
				                    self.addNewPage();
				                });

				                this.checkPage();

				                $("#publish").click(function (e) {
				                    var listOptionPage = [];
				                    var listContainer = [];
				                    $(document).trigger("click");

				                    $("body").append($("<div/>", {
				                        "class":"jsn-modal-overlay",
				                        "style":"z-index: 1000; display: inline;"
				                    })).append($("<div/>", {
				                        "class":"jsn-modal-indicator",
				                        "style":"display:block"
				                    })).addClass("jsn-loading-page");

				                    $(" ul.jsn-page-list li.page-items").each(function () {
				                        listOptionPage.push([$(this).find("input").attr("data-id"), $(this).find("input").attr("value")]);
				                    });
				                    $("#form-container .jsn-row-container").each(function () {
				                        var listColumn = [];
				                        $(this).find(".jsn-column-content").each(function () {
				                            var dataContainer = {};
				                            var columnName = $(this).attr("data-column-name");
				                            var columnClass = $(this).attr("data-column-class");
				                            dataContainer.columnName = columnName;
				                            dataContainer.columnClass = columnClass;
				                            listColumn.push(dataContainer);
				                        });
				                        listContainer.push(listColumn);
				                    });
				                    $.ajax({
				                        type:"POST",
				                        url:"admin-ajax.php?action=ig_uniform_save_page",
				                        data:{
				                            form_id:$("#jform_form_id").val(),
				                            form_content:self.visualDesign.serialize(),
				                            form_page_name:$("#form-design-header").attr("data-value"),
				                            form_list_page:listOptionPage,
				                            form_list_container:$.toJSON(listContainer)
				                        },
				                        success:function () {
				                            $("#post").submit();
				                        }
				                    });
				                    return false;
				                });

				                $("#form-design-header a.element-edit").click(function () {
				                    self.cerateEditPage($(this));
				                });
				                $("#form-design-header a.element-delete").click(function (e) {
				                    self.removePage(this);
				                    e.stopPropagation();
				                });
				                $(".jsn-modal-overlay,.jsn-modal-indicator").remove();
				                $("body").append($("<div/>", {
				                    "class":"jsn-modal-overlay",
				                    "style":"z-index: 1000; display: inline;"
				                })).append($("<div/>", {
				                    "class":"jsn-modal-indicator",
				                    "style":"display:block"
				                })).addClass("jsn-loading-page");
				                this.loadPage("defaultPage");
				                this.actionForm();
				                this.formCaptcha();
				                if (this.titleForm) {
				                    $("#jform_form_title").val(this.titleForm);
				                }
				                this.btnSelectFormStyle.click(function (e) {
				                    self.dialogFormStyle($(this));
				                    e.stopPropagation();
				                });

				                $("#jform_form_theme").select2({
				                    formatResult:self.formatSelect2,
				                    formatSelection:self.formatSelect2,
				                    escapeMarkup:function (m) {
				                        return m;
				                    }
				                });

				                $("#form-design-content").attr("class", $("#form-design-content").attr("class").replace(/\big-style[-_]*[^\s]+\b/, $("#jform_form_theme").val()));
				                $("#jform_form_style").change(function () {
				                    if ($(this).val() == "form-horizontal") {
				                        $("#form-design-content").addClass("form-horizontal");
				                    } else {
				                        $("#form-design-content").removeClass("form-horizontal");
				                    }
				                    //$("#form-design-content").attr("class",$(this).val())
				                }).trigger("change")
				                //self.changeTheme();
				                $("#theme_action_add").click(function () {
				                    $("#add-theme-select").removeClass("hide");
				                    $("#form-select").addClass("hide");
				                    $("#theme_action").addClass("hide");
				                    $("#input_new_theme").focus().focus().bind("keypress", function (e) {
				                        if (e.keyCode == 13) {
				                            $("#btn_add_theme").trigger("click");
				                            return false;
				                        }
				                        if (e.keyCode == 27) {
				                            $("#btn_cancel_theme").trigger("click");
				                        }
				                    });
				                    $(document).click(function () {
				                        $("#btn_cancel_theme").trigger("click");
				                    });
				                });
				                $("#btn_cancel_theme").click(function () {
				                    $("#add-theme-select").addClass("hide");
				                    $("#form-select").removeClass("hide");
				                    $("#theme_action").removeClass("hide");
				                    $("#input_new_theme").val("");
				                });

				                $("#btn_add_theme").click(function () {
				                    var theme = $("#input_new_theme").val();
				                    var check = false;
				                    if (theme == "") {
				                        return false;
				                    }
				                    $("#jform_form_theme option").each(function () {
				                        if ($(this).val() == "ig-style-" + theme) {
				                            check = true;
				                        }
				                    });
				                    if (check) {
				                        alert(self.lang["IG_UNIFORM_COLOR_CONFIRM_EXISTS"]);
				                        return false;
				                    }
				                    $("#jform_form_theme").append($("<option/>", {"value":"ig-style-" + theme, "text":theme}));
				                    $("#option_themes").append(
				                        $("<input/>", {"class":"ig-style-" + theme, "type":"hide", "name":"form_style[themes_style][" + theme + "]"})
				                    ).append(
				                        $("<input/>", {"value":theme, "type":"hide", "name":"form_style[themes][]"})
				                    )
				                    $("#add-theme-select").addClass("hide");
				                    $("#form-select").removeClass("hide");
				                    $("#theme_action").removeClass("hide");

				                    $("#jform_form_theme").select2({
				                        formatResult:self.formatSelect2,
				                        formatSelection:self.formatSelect2,
				                        escapeMarkup:function (m) {
				                            return m;
				                        }
				                    });

				                    $("#jform_form_theme").val("ig-style-" + theme).prop("selected", true);
				                    $("#jform_form_theme").trigger("change");
				                    self.resetTheme("ig-style-light");
				                    $("#input_new_theme").val("");
				                });
				                $("#jform_form_theme").change(function () {
				                    var theme = $(this).val();
				                    var styleTheme = {};
				                    $("#style_accordion_content input,#style_accordion_content select").each(function () {
				                        var nameStyle = $(this).attr("name");
				                        if (nameStyle) {
				                            nameStyle = nameStyle.match(/form_style\[(.*?)\]/);
				                            styleTheme[nameStyle[1]] = $(this).val();
				                        }
				                        $("#option_themes input[name$=\'[themes_style][" + colorScheme.replace("ig-style-", "") + "]\']").val($.toJSON(styleTheme));
				                    });
				                    var optionTheme = $("#option_themes input[name$=\'[themes_style][" + theme.replace("ig-style-", "") + "]\']").val();
				                    if (optionTheme) {
				                        var options = $.evalJSON(optionTheme);
				                        $("#style_accordion_content input").each(function () {
				                            if (!$(this).hasClass(\'select2-focusser\')) {
				                                var className = $(this).attr("id");
				                                if (className) {
				                                    var nameOption = className.replace("style_", "");
				                                    $(this).val(options[nameOption]);
				                                }
				                            }
				                        });
				                        $("#style_accordion_content select").each(function () {
				                            var className = $(this).attr("id");
				                            var nameOption = className.replace("style_", "");
				                            $(this).val(options[nameOption]).prop("selected", true);
				                        });
				                    } else {
				                        if (theme == "ig-style-light" || theme == "jsn-style-dark") {
				                            self.resetTheme($("#jform_form_theme").val());
				                        } else {
				                            $("#style_accordion_content input[type=text]").each(function () {
				                                $(this).val("");
				                            });
				                            $("#style_accordion_content select").each(function () {
				                                $(this).eq(1).prop("selected", true);
				                            });
				                        }
				                    }
				                    $(".jsn-select-color").each(function () {
				                        var inputParent = $(this).prev();
				                        $(this).find("div").css("background-color", $(inputParent).val());
				                        $(this).ColorPickerSetColor($(inputParent).val());
				                    });
				                    $("#form-design-content").attr("class", $("#form-design-content").attr("class").replace(/\big-style[-_]*[^\s]+\b/, theme));
				                    self.changeStyleInline();
				                    self.actionTheme();
				                    colorScheme = $(this).val();
				                });

				                $("#theme_action_refresh").click(function () {
				                    if (confirm(self.lang["IG_UNIFORM_COLOR_CONFIRM_RESET"])) {
				                        self.resetTheme($("#jform_form_theme").val());
				                    }
				                });
				                $("#jform_form_edit_submission0,#jform_form_edit_submission1").change(function () {
				                    if ($("#jform_form_edit_submission1").is(":checked")) {
				                        $("#ig-select-user-group").removeClass("hide");
				                    } else {
				                        $("#ig-select-user-group").addClass("hide");
				                    }
				                }).trigger("change");
				                $("#theme_action_delete").click(function () {
				                    if (confirm(self.lang["IG_UNIFORM_COLOR_CONFIRM_DELETE"])) {
				                        var valueSelectTheme = $("#jform_form_theme").val();
				                        if (valueSelectTheme == "ig-style-light" || valueSelectTheme == "jsn-style-dark") {
				                            return false;
				                        } else {
				                            $("#jform_form_theme option:selected").each(function () {
				                                if ($(this).val() != "ig-style-light" && $(this).val() != "jsn-style-dark") {
				                                    var classRemove = $(this).val();
				                                    var valueRemove = classRemove.replace("ig-style-", "");
				                                    $("#option_themes input").each(function () {
				                                        if ($(this).attr("class") == classRemove) {
				                                            $(this).remove();
				                                        }
				                                        if ($(this).val() == valueRemove) {
				                                            $(this).remove();
				                                        }
				                                    });
				                                    $(this).remove();
				                                }
				                            });
				                            $("#jform_form_theme").eq(1).prop("selected", true);
				                            $("#jform_form_theme").trigger("change");
				                        }
				                    }
				                });
				                self.actionTheme();
				                $("#button_submit_color").change(function () {
				                    if ($(".ig-sortable-disable .form-actions button.jsn-form-submit").hasClass("hide")) {
				                        $(".ig-sortable-disable .form-actions button.jsn-form-submit").attr("class", "jsn-form-submit hide " + $(this).val());
				                    } else {
				                        $(".ig-sortable-disable .form-actions button.jsn-form-submit").attr("class", "jsn-form-submit " + $(this).val());
				                    }
				                });
				                $("#button_reset_color").change(function () {

								    if ($(".ig-sortable-disable .form-actions button.jsn-form-reset").hasClass("hide")) {
								        $(".ig-sortable-disable .form-actions button.jsn-form-reset").attr("class", "jsn-form-reset hide " + $(this).val());
								    } else {
								        $(".ig-sortable-disable .form-actions button.jsn-form-reset").attr("class", "jsn-form-reset " + $(this).val());
								    }
								});
				                $("#button_prev_color").change(function () {
				                    if ($(".ig-sortable-disable .form-actions button.jsn-form-prev").hasClass("hide")) {
				                        $(".ig-sortable-disable .form-actions button.jsn-form-prev").attr("class", "jsn-form-prev hide " + $(this).val());
				                    } else {
				                        $(".ig-sortable-disable .form-actions button.jsn-form-prev").attr("class", "jsn-form-prev " + $(this).val());
				                    }
				                });
				                $("#button_next_color").change(function () {
				                    if ($(".ig-sortable-disable .form-actions button.jsn-form-next").hasClass("hide")) {
				                        $(".ig-sortable-disable .form-actions button.jsn-form-next").attr("class", "jsn-form-next hide " + $(this).val());
				                    } else {
				                        $(".ig-sortable-disable .form-actions button.jsn-form-next").attr("class", "jsn-form-next " + $(this).val());
				                    }
				                });
				                $("#button_position").change(function () {
				                    $(".ig-sortable-disable .form-actions .btn-toolbar").attr("class", $(this).val());
				                });
                                $("select#jform_form_type,select#jform_form_style").select2({
				                    minimumResultsForSearch:99,
				                    escapeMarkup:function (m) {
				                        return m;
				                    }
				                });
				                $("select.ig-select2").select2({
				                    formatResult:self.formatButtonSelect2,
				                    formatSelection:self.formatButtonSelect2,
				                    minimumResultsForSearch:99,
				                    escapeMarkup:function (m) {
				                        return m;
				                    }
				                });

				                if (!idForm) {
				                    self.resetTheme($("#jform_form_theme").val());
				                }
				                ' . implode( '', $actionFormInit ) . '

				            }';
		$createPrototypeForm[ 'formatButtonSelect2' ] = 'formatButtonSelect2:function (state) {
				                var imgName = state.id.split("-");
				                return "<img class=\'imgSelect2\' src=\'" + siteUrl + "/wp-content/plugins/ig-uniform/assets/images/icons-16/" + imgName[imgName.length - 1] + ".png\'/>" + state.text;
				            }';
		$createPrototypeForm[ 'formatSelect2' ] = 'formatSelect2:function (state) {
				                var self = this, imgName = "";
				                if (state.id.toLowerCase() == "jsn-style-dark" || state.id.toLowerCase() == "ig-style-light") {
				                    imgName = state.id.toLowerCase();
				                } else {
				                    imgName = "ig-style-custom";
				                }
				                return "<img class=\'imgSelect2\' src=\'" + siteUrl + "/wp-content/plugins/ig-uniform/assets/images/icons-16/" + imgName + ".png\'/>" + state.text;
				            }';
		$createPrototypeForm[ 'actionTheme' ] = 'actionTheme:function () {
				                var valueSelectTheme = $("#jform_form_theme").val();
				                if (valueSelectTheme == "ig-style-light" || valueSelectTheme == "jsn-style-dark") {
				                    $("#theme_action_refresh").removeClass("hide");
				                    $("#theme_action_delete").addClass("hide");
				                } else {
				                    $("#theme_action_refresh").addClass("hide");
				                    $("#theme_action_delete").removeClass("hide");
				                }
				            }';
		$createPrototypeForm[ 'resetTheme' ] = 'resetTheme:function (theme) {
				                var self = this;
				                $("#style_accordion_content input").val("");
				                $("#form-design-content").attr("class", $("#form-design-content").attr("class").replace(/\big-style[-_]*[^\s]+\b/, theme));
				                if (theme == "ig-style-light") {
				                    $("#style_background_active_color").val("#FCF8E3");
				                    $("#style_border_active_color").val("#FBEED5");
				                    $("#style_text_color").val("#333333");
				                    $("#style_font_size").val("14");
				                    $("#style_message_error_text_color").val("#FFFFFF");
				                    $("#style_message_error_background_color").val("#B94A48");
				                    $("#style_field_background_color").val("#ffffff");
				                    $("#style_field_shadow_color").val("");
				                    $("#style_field_text_color").val("#666666");
				                    $("#style_field_border_color").val("");
				                    $("#style_padding_space").val(10);
				                    $("#style_margin_space").val(0);
				                    $("#style_border_thickness").val(0);
				                    $("#style_rounded_corner_radius").val(0);
				                    $("#style_font_type option:eq(0)").prop("selected", true).trigger("change");
				                    //$("#button_submit_color option:eq(1)").prop("selected", true).trigger("change");
				                   // $("#button_prev_color option:eq(0)").prop("selected", true).trigger("change");
				                    //$("#button_next_color option:eq(0)").prop("selected", true).trigger("change");
				                    //$("#button_position option:eq(0)").prop("selected", true).trigger("change");

				                } else if (theme == "jsn-style-dark") {
				                    $("#style_background_active_color").val("#444444");
				                    $("#style_border_active_color").val("#666666");
				                    $("#style_text_color").val("#C6C6C6");
				                    $("#style_font_size").val("14");
				                    $("#style_message_error_text_color").val("#FFFFFF");
				                    $("#style_message_error_background_color").val("#B94A48");
				                    $("#style_field_background_color").val("#000000");
				                    $("#style_field_shadow_color").val("#000000");
				                    $("#style_field_text_color").val("#333333");
				                    $("#style_field_border_color").val("#111111");
				                    $("#style_padding_space").val(10);
				                    $("#style_margin_space").val(0);
				                    $("#style_border_thickness").val(0);
				                    $("#style_rounded_corner_radius").val(0);
				                    $("#style_font_type option:eq(0)").prop("selected", true).trigger("change");
				                   // $("#button_submit_color option:eq(1)").prop("selected", true).trigger("change");
				                   // $("#button_prev_color option:eq(0)").prop("selected", true).trigger("change");
				                   // $("#button_next_color option:eq(0)").prop("selected", true).trigger("change");
				                   // $("#button_position option:eq(0)").prop("selected", true).trigger("change");
				                }
				                $(".jsn-select-color").each(function () {
				                    var inputParent = $(this).prev();
				                    $(this).find("div").css("background-color", $(inputParent).val());
				                    $(this).ColorPickerSetColor($(inputParent).val());
				                });
				                self.changeStyleInline();
				            }';
		$createPrototypeForm[ 'hexToRgb' ] = 'hexToRgb:function (h) {
				                var r = parseInt((this.cutHex(h)).substring(0, 2), 16), g = ((this.cutHex(h)).substring(2, 4), 16), b = parseInt((this.cutHex(h)).substring(4, 6), 16)
				                return r + "," + b + "," + b;
				            }';
		$createPrototypeForm[ 'cutHex' ] = 'cutHex:function (h) {
				                return (h.charAt(0) == "#") ? h.substring(1, 7) : h
				            }';
		$createPrototypeForm[ 'changeStyleInline' ] = 'changeStyleInline:function () {
				                var self = this,
				                    styleField = ".jsn-master #form-design-content .jsn-element-container .jsn-element .controls input,.jsn-master #form-design-content .jsn-element-container .jsn-element .controls select,.jsn-master #form-design-content .jsn-element-container .jsn-element .controls textarea{\n",
				                    styleFormElement = ".jsn-master #form-design-content .jsn-element-container .jsn-element {\n",
				                    styleActive = ".jsn-master #form-design-content .jsn-element-container .jsn-element.ui-state-edit {\n",
				                    styleTitle = ".jsn-master #form-design-content .jsn-element-container .jsn-element .control-label {\n";
				                $("#style_accordion_content input,#style_accordion_content select").each(function () {
				                    var dataValue = $(this).attr("data-value");
				                    var valueInput = $(this).val();
				                    if (valueInput) {
				                        if ($(this).attr("type") == "number") {
				                            if (dataValue == "border") {
				                                valueInput = valueInput + "px solid";
				                            } else if (dataValue == "margin") {
				                                valueInput = valueInput + "px 0px";
				                            } else {
				                                valueInput = valueInput + "px";
				                            }
				                        }
				                        var dataType = $(this).attr("data-type");
				                        switch (dataType) {
				                            case "jsn-element":
				                                if (dataValue) {
				                                    var items = dataValue.split(",");
				                                    if (items.length > 1) {
				                                        $.each(items, function (value, key) {
				                                            styleFormElement += key + ":" + valueInput + ";\n";
				                                        });
				                                    } else {
				                                        styleFormElement += items + ":" + valueInput + ";\n";
				                                    }
				                                }
				                                break;
				                            case "ui-state-edit":
				                                styleActive += dataValue + ":" + valueInput + ";\n";
				                                break;
				                            case "control-label":
				                                styleTitle += dataValue + ":" + valueInput + ";\n";
				                                break;
				                            case "field":
				                                if (dataValue == "background-color") {
				                                    styleField += "background:" + valueInput + ";\n";
				                                } else if (dataValue == "box-shadow") {
				                                    valueInput = self.hexToRgb(valueInput);
				                                    styleField += "box-shadow:0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 7px 0 rgba(" + valueInput + ", 0.8) inset;\n";
				                                } else {
				                                    styleField += dataValue + ":" + valueInput + ";\n";
				                                }
				                                break;
				                        }
				                    }
				                });
				                styleFormElement += "}\n";
				                styleActive += "}\n";
				                styleTitle += "}\n";
				                styleField += "}\n";
				                $("#style_inline style.formstyle").html(styleFormElement + styleActive + styleTitle + styleField);
				            }';
		$createPrototypeForm[ 'dialogFormStyle' ] = 'dialogFormStyle:function (_this) {
				                var self = this;
				                var dialog = $("#container-select-style"), parentDialog = $("#container-select-style").parent();
				                dialog.width("500");
				                $(dialog).appendTo("body");
				                var elmStyle = JSNVisualDesign.getBoxStyle($(dialog)),
				                    parentStyle = JSNVisualDesign.getBoxStyle($(_this)),
				                    position = {};
				                position.left = parentStyle.offset.left - elmStyle.outerWidth + parentStyle.outerWidth;
				                //  position.left = parentStyle.offset.left + (parentStyle.outerWidth - elmStyle.outerWidth) / 2;
				                position.top = parentStyle.offset.top + parentStyle.outerHeight;

				                $(dialog).find(".arrow").css("left", elmStyle.outerWidth - (parentStyle.outerWidth / 2));
				                dialog.css(position).click(function (e) {
				                    e.stopPropagation();
				                });
				                $(".jsn-select-color").each(function () {
				                    var inputParent = $(this).prev();
				                    var selfColor = this;
				                    $(this).find("div").css("background-color", $(inputParent).val());

				                    $(this).ColorPicker({
				                        color:$(inputParent).val(),
				                        onChange:function (hsb, hex, rgb) {
				                            $(selfColor).prev().val("#" + hex);
				                            var idInput = $(selfColor).prev().attr("id");
				                            $(selfColor).find("div").css("background-color", "#" + hex);
				                            self.changeStyleInline();
				                            var styleTheme = {};
				                            $("#style_accordion_content input,#style_accordion_content select").each(function () {
				                                var nameStyle = $(this).attr("name");
				                                if (nameStyle) {
				                                    nameStyle = nameStyle.match(/form_style\[(.*?)\]/);
				                                    styleTheme[nameStyle[1]] = $(this).val();
				                                }

				                                $("#option_themes input[name$=\'[themes_style][" + colorScheme.replace("ig-style-", "") + "]\']").val($.toJSON(styleTheme));
				                            });
				                        }
				                    });
				                });
				                $("#style_accordion_content input,#style_accordion_content select").change(function () {
				                    self.changeStyleInline();
				                    var styleTheme = {};
				                    $("#style_accordion_content input,#style_accordion_content select").each(function () {
				                        var nameStyle = $(this).attr("name");
				                        if (nameStyle) {
				                            nameStyle = nameStyle.match(/form_style\[(.*?)\]/);
				                            styleTheme[nameStyle[1]] = $(this).val();
				                        }
				                        $("#option_themes input[name$=\'[themes_style][" + colorScheme.replace("ig-style-", "") + "]\']").val($.toJSON(styleTheme));
				                    });
				                });
				                $(".CodeMirror-scroll").click();
				                $(dialog).show();
				                $("#container-select-style .popover").show();
				                $(".jsn-input-number").keypress(function (e) {
				                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				                        return false;
				                    }
				                });
				                $(document).click(function (e) {
				                    if ($(e.target).parents(".colorpicker").css("display") == "block" || ( $(e.target).hasClass("colorpicker") && $(e.target).css("display") == "block")) {
				                        return false;
				                    }
				                    if ($(e.target).parent().parent().hasClass("ui-autocomplete")) {
				                        return false;
				                    }
				                    if ($(e.target).parents(".ui-autocomplete").css("display") == "block") {
				                        return false;
				                    }
				                    if ($(dialog).css("display") != "none") {
				                        $(dialog).appendTo($(parentDialog));
				                        dialog.hide();
				                        dialog.width("0");
				                    }
				                });
				            }';
		$createPrototypeForm[ 'formCaptcha' ] = 'formCaptcha:function () {
				                var self = this;
				                $(".form-captcha .jsn-iconbar a.element-edit").click(function () {
				                    var sender = $(this).parents(".form-captcha");
				                    $(sender).addClass("ui-state-edit");
				                    var type = "form-captcha";
				                    var params = {};
				                    var action = $(this);
				                    JSNVisualDesign.openOptionsBox(sender, type, params, action);
				                    $("#option-formCaptcha-select option").each(function () {
				                        if ($(this).val() == $("#jform_form_captcha").val()) {
				                            $(this).attr("selected", "selected");
				                        }
				                    });
				                    $("#option-formCaptcha-select").change(function () {
				                        $("#jform_form_captcha").val($(this).val());
				                        if ($(this).val() == 0) {
				                            $(".recaptcha-content > div").addClass("img-captcha");
				                            $(".recaptcha-content > div span.label").show();
				                        } else {
				                            $(".recaptcha-content > div").removeClass("img-captcha");
				                            $(".recaptcha-content > div span.label").hide();
				                            if ($(this).val() == 1) {
				                                $(".form-captcha .recaptcha-content img").attr("src", $(".form-captcha .recaptcha-content img").attr("data-recaptcha"));
				                            } else {
				                                $(".form-captcha .recaptcha-content img").attr("src", $(".form-captcha .recaptcha-content img").attr("data-securityimages"));
				                            }
				                        }
				                    }).trigger("change");
				                });
				                if ($("#jform_form_captcha").val() == 0) {
				                    $(".recaptcha-content > div").addClass("img-captcha");
				                    $(".recaptcha-content > div span.label").show();
				                } else {
				                    $(".recaptcha-content > div").removeClass("img-captcha");
				                    $(".recaptcha-content > div span.label").hide();
				                    if ($("#jform_form_captcha").val() == 1) {
				                        $(".form-captcha .recaptcha-content img").attr("src", $(".form-captcha .recaptcha-content img").attr("data-recaptcha"));
				                    } else {
				                        $(".form-captcha .recaptcha-content img").attr("src", $(".form-captcha .recaptcha-content img").attr("data-securityimages"));
				                    }
				                }
				            }';
		$createPrototypeForm[ 'actionForm' ] = 'actionForm:function () {
				                var self = this;
				                $(".form-actions  .jsn-iconbar a.element-edit").click(function () {
				                    var sender = $(this).parents(".form-actions");
				                    $(sender).addClass("ui-state-edit");
				                    var type = "form-actions";
				                    var params = {};
				                    var action = $(this);
				                    JSNVisualDesign.openOptionsBox(sender, type, params, action);
				                    $("#option-btnNext-text").val($("#jform_form_btn_next_text").val()).keyup(function () {
				                        var btnNext = $("#option-btnNext-text").val() ? $("#option-btnNext-text").val() : "Next";
				                        $("#jform_form_btn_next_text").val(btnNext);
				                        $(".form-actions .btn-toolbar .jsn-form-next").text(btnNext);
				                        $("#button_next_color").parents(".control-group").find("label").text(btnNext);
				                    });
				                    $("#option-btnPrev-text").val($("#jform_form_btn_prev_text").val()).keyup(function () {
				                        var btnPrev = $("#option-btnPrev-text").val() ? $("#option-btnPrev-text").val() : "Prev";
				                        $("#jform_form_btn_prev_text").val(btnPrev);
				                        $(".form-actions .btn-toolbar .jsn-form-prev").text(btnPrev);
				                        $("#button_prev_color").parents(".control-group").find("label").text(btnPrev);
				                    });
				                    $("#option-btnSubmit-text").val($("#jform_form_btn_submit_text").val()).keyup(function () {
				                        var btnSubmit = $("#option-btnSubmit-text").val() ? $("#option-btnSubmit-text").val() : "Submit";
				                        $("#jform_form_btn_submit_text").val(btnSubmit);
				                        $(".form-actions .btn-toolbar .jsn-form-submit").text(btnSubmit);
				                        $("#button_submit_color").parents(".control-group").find("label").text(btnSubmit);
				                    });
				                    $("#option-btnReset-text").val($("#jform_form_btn_reset_text").val()).keyup(function () {
				                        var btnReset = $("#option-btnReset-text").val() ? $("#option-btnReset-text").val() : "Reset";
				                        $("#jform_form_btn_reset_text").val(btnReset);
				                        $(".form-actions .btn-toolbar .jsn-form-reset").text(btnReset);
				                        $("#button_reset_color").parents(".control-group").find("label").text(btnReset);
				                    });
				                    if ($("#jform_form_state_btn_reset_text").val() == "Yes") {
				                        $("#option-stateBtnReset-radio-Yes").prop("checked", true);
				                        $("#option-stateBtnReset-radio-No").prop("checked", false);
				                        $("#option-btnReset-text").parents(".control-group").show();
				                        $(".form-actions .btn-toolbar .jsn-form-reset").show();
				                    } else {
				                        $("#option-stateBtnReset-radio-Yes").prop("checked", false);
				                        $("#option-stateBtnReset-radio-No").prop("checked", true);
				                        $("#option-btnReset-text").parents(".control-group").hide();
				                        $(".form-actions .btn-toolbar .jsn-form-reset").hide();
				                    }
				                    $("input[name=stateBtnReset]").change(function () {
				                        $("#jform_form_state_btn_reset_text").val($(this).val());
				                        if ($(this).val() == "Yes") {
				                            $("#option-btnReset-text").parents(".control-group").show();
				                            $(".form-actions .btn-toolbar .jsn-form-reset").show();
				                        } else {
				                            $("#option-btnReset-text").parents(".control-group").hide();
				                            $(".form-actions .btn-toolbar .jsn-form-reset").hide();
				                        }
				                    });
				                });
				                $(".settings-footer .jsn-iconbar a.element-delete").click(function () {
				                    self.JSNUniformDialogEdition = new JSNUniformDialogEdition(self.params);
				                    JSNUniformDialogEdition.createDialogLimitation($(this), self.lang["IG_UNIFORM_YOU_CAN_NOT_HIDE_THE_COPYLINK"]);
				                    return false;
				                });
				            }';
		$actionPrototypeFormLoadPage = array();
		$actionPrototypeFormLoadPage = apply_filters( 'ig_uniform_js_form_action_prototype_form_load_page', $actionPrototypeFormLoadPage );
		$createPrototypeForm[ 'loadPage' ] = 'loadPage:function (action) {
							    if (action == "defaultPage") {
							       $("#wpbody-content").show();
                                   $("#ig_uniform_master").show();
                                   $("#post-body-content").show();
                                }
				                var self = this;
				                var listOptionPage = [];
				                var listContainer = [];
				                $(" ul.jsn-page-list li.page-items").each(function () {
				                    listOptionPage.push([$(this).find("input").attr("data-id"), $(this).find("input").attr("value")]);
				                });
				                $("#form-container .jsn-row-container").each(function () {
				                    var listColumn = [];
				                    $(this).find(".jsn-column-content").each(function () {
				                        var dataContainer = {};
				                        var columnName = $(this).attr("data-column-name");
				                        var columnClass = $(this).attr("data-column-class");
				                        dataContainer.columnName = columnName;
				                        dataContainer.columnClass = columnClass;
				                        listColumn.push(dataContainer);
				                    });
				                    listContainer.push(listColumn);
				                });
				                $("#form-design-content #page-loading").show();
				                $("#form-design-content .jsn-column-container ").hide();
				                $(".jsn-page-actions").hide();
				                $("#form-design-header .jsn-iconbar").css("display", "none");
				                $.ajax({
				                    type:"POST",
				                    dataType:"json",
				                    url:"admin-ajax.php?action=ig_uniform_load_page",
				                    data:{
				                        form_page_name:$("#form-design-header").attr("data-value"),
				                        form_page_old_name:oldValuePage,
				                        form_page_old_content:self.visualDesign.serialize(),
				                        form_page_old_container:$.toJSON(listContainer),
				                        form_id:$("#jform_form_id").val(),
				                        form_list_page:listOptionPage,
				                        join_page:action
				                    },
				                    complete:function (response) {
				                        if (response && response.responseJSON) {
				                            var data = response.responseJSON;
				                            self.JSNLayoutCustomizer.renderContainer(data.containerPage);

				                            if ($("#jform_form_id").val() > 0 && self.pageContent) {
				                                var pageContent = $.evalJSON(self.pageContent);
				                                if (!data.dataField && action == "defaultPage" && $.inArray(oldValuePage, pageContent) != -1) {
				                                    location.reload();
				                                }
				                            }
				                            self.visualDesign.clearElements();

				                            if (data.dataField) {
				                                var dataField = $.evalJSON(data.dataField);

				                                self.visualDesign.setElements(dataField);
				                            }
				                            if (action == "join") {
				                                $(".jsn-page-list li.page-items").each(function (index) {
				                                    if (index != 0) {
				                                        $(this).remove();
				                                    }
				                                });
				                                self.checkPage();
				                            }
				                            if (action == "defaultPage") {
				                                JSNVisualDesign.emailNotification();
				                                $(".jsn-modal-overlay,.jsn-modal-indicator").remove();
				                            }
				                            $(".jsn-page-actions").show();
				                            $("#form-design-content #page-loading").hide();
				                            $("body").removeClass("jsn-loading-page");
				                            $("#form-design-content .jsn-column-container ").show();
				                            $("#form-design-header .jsn-iconbar").css("display", "");

				                            ' . implode( '', $actionPrototypeFormLoadPage ) . '
				                        }


				                    }
				                });
				                oldValuePage = $("#form-design-header").attr("data-value");
				            }';
		$createPrototypeForm[ 'addNewPage' ] = 'addNewPage:function () {
				                JSNVisualDesign.savePage();
				                $("#form-container .jsn-row-container").remove();
				                this.JSNLayoutCustomizer.renderContainer();
				                this.visualDesign.clearElements();
				                var randomMath = Math.floor((Math.random() * 100000000) + 10000);
				                var countSelect = $("ul.jsn-page-list li.page-items").size() + 1;
				                var selectAdd = "<li id=\'new_" + randomMath + "\' data-value=\'" + randomMath + "\' class=\"page-items\"><a href=\"#\">Page " + countSelect + "</a><input type=\"hidden\" value=\"Page " + countSelect + "\" data-id=\"" + randomMath + "\" name=\"name_page[" + randomMath + "]\"/></li>";
				                $("ul.jsn-page-list").append(selectAdd);
				                $("#form-design #form-design-header").attr("data-value", $("#new_" + randomMath).attr("data-value"));
				                $("#form-design #form-design-header .page-title h1").text($("#new_" + randomMath).find("input").val());
				                oldValuePage = $("#form-design-header").attr("data-value");
				                this.checkPage();
				                $("#form-design-header .icon-pencil").trigger("click")

				            }';
		$createPrototypeForm[ 'cerateEditPage' ] = 'cerateEditPage:function (_this) {
				                var item = $(_this).parent().parent().parent();

				                $("#form-design-header .jsn-page-actions").hide();
				                $("#form-design-header .page-edit-form").remove();
				                var self = this;
				                item.find(".page-title").hide();
				                $("#form-design-header").addClass("edit-page-item").append(
				                    $("<div/>", {
				                        "class":"page-edit-form form-inline"
				                    }).append(
				                        $("<input>", {
				                            "type":"text",
				                            "value":item.find("h1").text(),
				                            "class":"page-input-tmp input-xlarge"
				                        })).append(
				                        $("<button/>", {
				                            "onclick":"return false;",
				                            "class":"btn btn-icon save-page"
				                        }).append(
				                            $("<i/>", {
				                                "class":"icon-ok"
				                            })).click(function () {
				                                self.saveEditPage();
				                                return false;
				                            })).append(
				                        $("<button/>", {
				                            "onclick":"return false;",
				                            "class":"btn btn-icon cancel-page"
				                        }).append(
				                            $("<i/>", {
				                                "class":"icon-remove"
				                            })).click(function () {
				                                self.cancelEditPage();
				                                return false;
				                            })))
				                $(" .edit-page-item .page-input-tmp").focus().bind("keypress", function (e) {
				                    if (e.keyCode == 13) {
				                        self.saveEditPage();
				                        return false;
				                    }
				                    if (e.keyCode == 27) {
				                        self.cancelEditPage();
				                    }
				                });
				            }';
		$createPrototypeForm[ 'removePage' ] = 'removePage:function (_this) {
				                var self = this;
				                var liActive = $(_this).parent().parent().parent();
				                var itemRemove = liActive.attr("data-value");
				                if (confirm("Are you sure you want to delete page " + liActive.find("h3").text() + " with all fields?")) {
				                    if ($("ul.jsn-page-list li.page-items").size() > 1) {
				                        $("ul.jsn-page-list li.page-items").each(function () {
				                            if ($(this).attr("data-value") == itemRemove) {
				                                if ($(this).next().attr("data-value")) {
				                                    $("#form-design #form-design-header").attr("data-value", $(this).next().attr("data-value"));
				                                    $("#form-design #form-design-header .page-title h1").html($(this).next().find("input").val());
				                                    $(this).remove();

				                                } else if ($(this).prev().attr("data-value")) {
				                                    $("#form-design #form-design-header").attr("data-value", $(this).prev().attr("data-value"));
				                                    $("#form-design #form-design-header .page-title h1").html($(this).prev().find("input").val());
				                                    $(this).remove();

				                                }
				                            }
				                        });
				                        $("#form-design-content #page-loading").show();
				                        $("#form-design-content .jsn-column-container ").hide();
				                        $.ajax({
				                            type:"POST",
				                            dataType:"json",
				                            url:"admin-ajax.php?action=ig_uniform_load_page",
				                            data:{
				                                form_id:$("#jform_form_id").val(),
				                                form_page_name:$("#form-design-header").attr("data-value"),
				                                form_page_old_name:oldValuePage,
				                                form_page_old_content:this.visualDesign.serialize()
				                            },
				                            success:function (response) {
				                                self.JSNLayoutCustomizer.renderContainer(response.containerPage);
				                                self.visualDesign.clearElements();
				                                if (response.dataField) {
				                                    var dataField = $.evalJSON(response.dataField);
				                                    self.visualDesign.setElements(dataField);
				                                }
				                                JSNVisualDesign.savePage();
				                                $("#form-design-content #page-loading").hide();
				                                $("#form-design-content .jsn-column-container ").show();
				                            }
				                        });

				                        self.checkPage();
				                        oldValuePage = $("#form-design-header").attr("data-value");
				                    }

				                }
				            }';
		$createPrototypeForm[ 'cancelEditPage' ] = 'cancelEditPage:function () {
				                var editPageItem = $(".edit-page-item");
				                editPageItem.find(".page-title").show();
				                editPageItem.find(".page-edit-form").hide();
				                editPageItem.removeClass("edit-page-item");
				                this.checkPage();

				            }';
		$createPrototypeForm[ 'saveEditPage' ] = 'saveEditPage:function (e) {
				                var self = this;
				                var inputTmpPage = $(".edit-page-item .page-input-tmp");
				                if (inputTmpPage.val() != "") {
				                    $("ul.jsn-page-list li.page-items input").each(function () {
				                        if ($(this).attr("data-id") == $("#form-design-header").attr("data-value")) {
				                            $(this).val(inputTmpPage.val());
				                            $(this).prev().text(inputTmpPage.val());
				                            $("#form-design-header .page-title h1").text(inputTmpPage.val());
				                        }
				                    });
				                    JSNVisualDesign.savePage();
				                    self.cancelEditPage();
				                    self.checkPage();
				                } else {
				                    $(".page-input-tmp").addClass("error");
				                    if (e) {
				                        e.stopPropagation();
				                    }
				                }
				            }';
		$createPrototypeForm[ 'loadDefaultPage' ] = 'loadDefaultPage:function (value) {
				                var self = this;
				                $("ul.jsn-page-list li.page-items").each(function () {
				                    if ($(this).attr("data-value") == value) {
				                        var dataValue = $(this).attr("data-value");
				                        var dataText = $(this).find("input").val();
				                        $("#form-design-header").attr("data-value", dataValue);
				                        $("#form-design-header .page-title h1").text(dataText);
				                        return false;
				                    }
				                });
				                self.loadPage("defaultPage");

				            }';
		$createPrototypeForm[ 'nextpaginationPage' ] = 'nextpaginationPage:function () {
				                var self = this;
				                $("ul.jsn-page-list li.page-items").each(function () {
				                    if ($(this).attr("data-value") == $("#form-design-header").attr("data-value")) {
				                        var dataValue = $(this).next().attr("data-value");
				                        var dataText = $(this).next().find("input").val();
				                        $("#form-design-header").attr("data-value", dataValue);
				                        $("#form-design-header .page-title h1").text(dataText);
				                        return false;
				                    }
				                });
				                self.checkPage();
				                self.loadPage();
				            }';
		$createPrototypeForm[ 'prevpaginationPage' ] = 'prevpaginationPage:function () {
				                var self = this;
				                $("ul.jsn-page-list li.page-items").each(function () {
				                    if ($(this).attr("data-value") == $("#form-design-header").attr("data-value")) {
				                        var dataValue = $(this).prev().attr("data-value");
				                        var dataText = $(this).prev().find("input").val();
				                        $("#form-design-header").attr("data-value", dataValue);
				                        $("#form-design-header .page-title h1").text(dataText);
				                    }
				                });
				                self.checkPage();
				                self.loadPage();
				            }';
		$createPrototypeForm[ 'checkPage' ] = 'checkPage:function () {
				                var self = this;
				                $("#form-design-header .jsn-page-actions").show();
				                var pageItems = $("ul.jsn-page-list li.page-items");
				                if (pageItems.size() <= 1) {
				                    $("#form-design-header a.element-delete").hide();
				                    $(".form-actions .btn-toolbar .jsn-form-submit").removeClass("hide");
				                } else {
				                    $("#form-design-header a.element-delete").show();
				                    $(".form-actions .btn-toolbar .jsn-form-submit").addClass("hide");
				                }
				                pageItems.each(function () {
				                    if ($(this).attr("data-value") == $("#form-design-header").attr("data-value")) {
				                        if ($(this).next().attr("data-value")) {
				                            $(".jsn-page-actions .next-page").removeAttr("disabled");
				                            $(".form-actions .btn-toolbar .jsn-form-next").removeClass("hide");
				                        } else {
				                            $(".jsn-page-actions .next-page").attr("disabled", "disabled");
				                            $(".form-actions .btn-toolbar .jsn-form-next").addClass("hide");
				                        }
				                        if ($(this).prev().attr("data-value")) {
				                            $(".jsn-page-actions .prev-page").removeAttr("disabled");
				                            $(".form-actions .btn-toolbar .jsn-form-prev").removeClass("hide");
				                        } else {
				                            $(".jsn-page-actions .prev-page").attr("disabled", "disabled");
				                            $(".form-actions .btn-toolbar .jsn-form-prev").addClass("hide");
				                        }
				                        if ($(this).prev().attr("data-value") && !$(this).next().attr("data-value") || !$(this).next().attr("data-value") && !$(this).next().attr("data-value")) {
				                            $(".form-actions .btn-toolbar .jsn-form-submit").removeClass("hide");
				                            if ($("#jform_form_state_btn_reset_text").val() == "Yes") {
				                                $(".form-actions .btn-toolbar .jsn-form-reset").removeClass("hide");
				                            }
				                        } else {
				                            $(".form-actions .btn-toolbar .jsn-form-submit").addClass("hide");
				                            $(".form-actions .btn-toolbar .jsn-form-reset").addClass("hide");
				                        }
				                    }
				                });
				            }';
		$mainContent = array();
		$mainContent = apply_filters( 'ig_uniform_js_form_main_content', $mainContent );
		$createPrototypeForm = apply_filters( 'ig_uniform_js_form_add_proto_type_form', $createPrototypeForm );
		$javascript = '(function ($) {
				    $(function () {
				        var urlBase = "";
				        var colorScheme;
				        var siteUrl = "";

				        function JSNUniformFormView(params) {
				            this.params = params;
				            this.lang = params.language;
				            this.formStyle = params.form_style;
				            this.urlAction = params.urlAction;
				            this.checkSubmitModal = params.checkSubmitModal;
				            this.baseZeroClipBoard = params.baseZeroClipBoard;
				            this.pageContent = params.pageContent;
				            this.opentArticle = params.opentArticle;
				            this.titleForm = params.titleForm;
				            urlBase = params.urlBase;
				            siteUrl = params.siteUrl;
				            this.init();
				        }

				        var oldValuePage = $("#form-design-header").attr("data-value");
				        JSNUniformFormView.prototype = {
				            ' . implode( ',', $createPrototypeForm ) . '
				        }
				        $("body").addClass("jsn-master");
				        $("#ig_uniform_master").appendTo($("#ig_uniform_form_settings").parent().parent());
				        $("#ig_uniform_form_settings").parents("#advanced-sortables").hide();
				        $("#slugdiv").parent().remove();
				        var params = {};
				        params.language = $.evalJSON($("#ig_uniform_languages").val());

				        params.form_style = $.evalJSON($("#ig_uniform_formStyle").val());
				        params.dataEmailSubmitter = $.evalJSON($("#ig_uniform_dataEmailSubmitter").val());
				        params.urlBase = $("#ig_uniform_urlBase").val();
				        params.siteUrl = $("#ig_uniform_urlBase").val();
				        $("#post-body-content .ig-editor-wrapper").remove();
						$("#wp-admin-bar-view a.ab-item").html("View Form").show();
				        $("#screen-meta-links").remove();
				        $("#edit-slug-box").remove();
						' . implode( '', $mainContent ) . '
				        if ($("#jform_form_id").val()) {
				            $("#titlediv .inside").append(
				                $("<div/>", {"class":"uniform-get-shortcode","id":"edit-slug-box"}).append(
				                    \'<strong for="title" original-title="Copy the shortcode below and paste it to any page or post" class="ig-label-des-tipsy">Form short code: </strong><span>[ig_uniform id=\' + $("#jform_form_id").val() + \']</span> \' +
				                    \'<span id="view-post-btn"><a id="jsn_uniform_copy_text" data-clipboard-text="[ig_uniform id=\' + $("#jform_form_id").val() + \']" class="button button-small" href="javascript:void(0);">Copy to Clipboard</a></span>\'
				                )
				            )
				            var client = new ZeroClipboard( $("#jsn_uniform_copy_text"), {
				                moviePath: params.urlBase+"/wp-content/plugins/ig-uniform/assets/3rd-party/zeroclipboard/ZeroClipboard.swf"
				            } );
				            client.on("load", function (client) {
				                client.on("complete", function (client, args) {
				                      $("#jsn_uniform_copy_text").html("Done!");
					                  setTimeout(function(){
					                    $("#jsn_uniform_copy_text").html("Copy to Clipboard");
					                  },1800);
				                });
				            });
				            client.on("noFlash", function (client) {
				                $(".uniform-get-shortcode").hide();
				                alert("Your browser has no Flash.");
				            });
				            client.on("wrongFlash", function (client, args) {
				                $(".uniform-get-shortcode").hide();
				                alert("Flash 10.0.0+ is required but you are running Flash " + args.flashVersion.replace(/,/g, "."));
				            });
				        }
				        $(".ig-label-des-tipsy").tipsy({
				            gravity:"w",
				            fade:true
				        });

				        if (!$("#jform_form_id").val()) {
				            $("#titlediv #title").val($("#jform_form_title").val());
				            $("#title-prompt-text").text("");
				        }
				        new JSNUniformFormView(params);
				    });

				})(jQuery);';
		echo '' . $javascript;
		exit();
	}


}
