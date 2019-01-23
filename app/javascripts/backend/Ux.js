import * as _ from "underscore"
import WizardSlide from "./WizardSlide"
import { createCookie, readCookie, eraseCookie } from "./misc/Cookie"
import Clipboard from "clipboard"
import anime from "animejs"
;import { createOnChangeTeampltePreview } from "./onChangeTemplatePreview";
(function($) {

    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    // Delipress app
    var $delipress = $(".delipress")

    // Fixed menu on scroll
    var latestKnownScrollY = 0
    var ticking = false
    var $notice = $(".delipress__notice")
    var trigger = $notice.length > 0 ? 162 : 100

    // if ($(".delipress-is-builder").length == 0) {
    //     window.addEventListener("scroll", scrollCheck, false)
    //     scrollCheck()
    // }
    //
    // function scrollCheck() {
    //     latestKnownScrollY = window.scrollY
    //     if (!ticking) {
    //         requestAnimationFrame(function() {
    //             if (
    //                 latestKnownScrollY > trigger &&
    //                 $delipress.hasClass("delipress--has-menu")
    //             ) {
    //                 $delipress.addClass("delipress--fixmenu")
    //             } else if (
    //                 latestKnownScrollY < trigger &&
    //                 $delipress.hasClass("delipress--fixmenu")
    //             ) {
    //                 $delipress.removeClass("delipress--fixmenu")
    //             }
    //             ticking = false
    //         })
    //     }
    //     ticking = true
    // }

    // Notice hiding
    $delipress.on("click", ".js-delipress-notice-close", function(e) {
        e.preventDefault()
        $(this).slideUp(function() {
            $(this).remove()
        })
    })

    // Notice hiding
    $delipress.on("click", ".js-delipress-notice-provider-close", function(e) {
        e.preventDefault()
        $(this).parents(".delipress__notice--provider").slideUp(function() {
            $(this).remove()
        })
    })

    // Section opener
    $delipress.on("click", ".js-delipress-opener", function(e) {
        e.preventDefault()
        $(this).toggleClass("delipress__opener--is-open")
    })

    // Select

    // Value default
    $delipress
        .find(".js-delipress-select .delipress__select__list li")
        .each(function(key, value) {
            if ($(value).data("selected")) {
                var $select = $(this).parents(".js-delipress-select")
                var value = $(this).html()
                var valueSlug = $(this).attr("data-value")

                $select.find(".delipress__select__selected").html(value)
                $select.find("input").val(valueSlug)
            }
        })

    // Open
    $delipress.on("click", ".delipress__select", function(e) {
        e.stopPropagation()
        $(this).toggleClass("delipress__select--is-open")
    })

    // Select
    $delipress.on(
        "click",
        ".js-delipress-select .delipress__select__list li",
        function(e) {
            e.stopPropagation()
            var $select = $(this).parents(".js-delipress-select")
            var value = $(this).html()
            var valueSlug = $(this).attr("data-value")

            $select.removeClass("delipress__select--is-open")
            $select.find(".delipress__select__selected").html(value)
            $select.find("input").val(valueSlug)
        }
    )

    // Close
    var $selects = $(".js-delipress-select")

    if ($selects.length > 0) {
        $(window).click(function() {
            $selects.removeClass("delipress__select--is-open")
        })
    }

    // Dropdown delete preventDefault
    $delipress.on(
        "click",
        ".delipress__more .delipress__button.delipress__button--soft",
        e => {
            e.preventDefault()
        }
    )

    // Multi Select

    // Open

    const openMultiSelect = _.debounce(el => {
        $(el).parent().toggleClass("delipress__multiselect--is-open")
    }, 100)

    $delipress.on(
        "click",
        ".js-delipress-multiselect .delipress__multiselect__add",
        function(e) {
            e.preventDefault()
            openMultiSelect(this)
        }
    )

    $delipress.on(
        "focus",
        ".js-delipress-multiselect .delipress__multiselect__add",
        function(e) {
            e.preventDefault()
            $(this).trigger("click")
        }
    )

    // Add
    $delipress.on(
        "click",
        ".js-delipress-multiselect .delipress__multiselect__list li",
        function(e) {
            e.stopPropagation()

            var value = $(this).attr("data-value")

            if (!_.isUndefined(value)) {
                // Get datas
                var $component = $(this).parents(".js-delipress-multiselect")
                var limit = parseInt($component.attr("data-limit"))

                var style = $(this)
                    .children(".delipress__multiselect__colordot")
                    .attr("style")
                var name = $(this)
                    .children(".delipress__multiselect__list__name")
                    .html()

                var template = $component.children("template").html()
                var $addButton = $component.find(".delipress__multiselect__add")

                // Insert template
                $addButton.before(template)

                var $newAddedList = $addButton.prev()

                // Set datas
                $newAddedList.attr("data-value", value)
                // $newAddedList
                //     .find(".delipress__multiselect__colordot")
                //     .attr("style", style)
                $newAddedList
                    .find(".delipress__multiselect__item__name")
                    .html(name)

                // Select in true select field
                $component
                    .find("select option[value=" + value + "]")
                    .prop("selected", true)

                // hide entry from list
                $(this).hide()

                // hide Add button if limit reached
                var activeItems = $component.children("span").length

                if (!isNaN(limit) && activeItems >= limit) {
                    $addButton.hide()
                    $component.removeClass("delipress__multiselect--is-open")
                }

                // If no more left : display "no more" message
                if (
                    $(this).siblings(".delipress__multiselect__list__item")
                        .length ==
                    $(this).siblings(
                        ".delipress__multiselect__list__item:hidden"
                    ).length
                ) {
                    $(this)
                        .siblings(".delipress__multiselect__list__placeholder")
                        .show()
                }
            }
        }
    )

    // Remove
    $delipress.on(
        "click",
        ".js-delipress-multiselect .delipress__multiselect__item__delete",
        function(e) {
            e.stopPropagation()
            e.preventDefault()

            var $component = $(this).parents(".js-delipress-multiselect")

            var value = $(this).parent().attr("data-value")
            var $item = $(this).parent().remove()

            $component
                .find(
                    ".delipress__multiselect__list__item[data-value=" +
                        value +
                        "]"
                )
                .show()

            // Unselect in true field
            $component
                .find("select option[value=" + value + "]")
                .attr("selected", false)

            // Hide placeholder message because there is now at least one element
            $component.find(".delipress__multiselect__list__placeholder").hide()

            // show button add in case it was hidden due to entries limitations
            $component.find(".delipress__multiselect__add").show()
        }
    )

    // Close
    var $multiselects = $(".js-delipress-multiselect")

    if ($multiselects.length > 0) {
        $(window).click(function() {
            $multiselects.removeClass("delipress__multiselect--is-open")
        })
    }

    // Simple color Picker
    $delipress.on("click", ".js-delipress-simplepicker", function(e) {
        $(this).toggleClass("delipress__simplepicker--is-visible")
    })

    $delipress.on(
        "click",
        ".js-delipress-simplepicker .delipress__simplepicker__list li",
        function(e) {
            var color = $(this).css("background-color")
            var $component = $(this).parents(".delipress__simplepicker")

            $component.css("background-color", color).find("input").val(color)
        }
    )

    // Checkboxes list all/none
    $delipress.on("click", ".js-delipress-checkbox-sync", function() {
        var state = $(this).prev("input").prop("checked")
        var linkedList = $(this).attr("data-list")
        var $list = $delipress.find("ul[data-list=" + linkedList + "]")

        $list.find(".delipress__checkbox__input").prop("checked", !state)
    })

    // Hover behavior in the builder to switch from a wpmenu to a builder
    // scroll
    $("#adminmenumain").mouseenter(e => {
        $("body").addClass("menu-scroll")
    })

    $("#adminmenuwrap").mouseleave(e => {
        $("body").removeClass("menu-scroll")
    })

    // On resize if width inferior to 1100px we auto-fold menu
    $(window).on("resize", event => {
        checkAutoFold()
    })

    function checkAutoFold() {
        if ($(window).width() < 1200 && !$("body").hasClass("sticky-menu")) {
            $("body").addClass("auto-fold sticky-menu")
        } else if (
            $(window).width() >= 1200 &&
            $("body").hasClass("sticky-menu")
        ) {
            $("body").removeClass("auto-fold sticky-menu")
        }
    }

    checkAutoFold()

    // ==================
    // == Wizard slide ==
    // ==================
    new WizardSlide()

    // Notice dismiss cookie
    const delipress_cookieNoticeLocal = readCookie("delipress__notice-local")
    const delipress_cookieNoticeTracking = readCookie(
        "delipress__notice-tracking"
    )
    const delipress_cookieNoticeProvider = readCookie(
        "delipress__notice-provider"
    )

    $(".js-delipress-notice").on("click", ".notice-dismiss", function(e) {
        const noticeId = $(this).parents(".js-delipress-notice").attr("id")
        createCookie(noticeId, "true", 30)
    })

    // Then do some conditional crap with it
    if (delipress_cookieNoticeLocal != "true") {
        $("#delipress__notice-local").show()
    }
    if (delipress_cookieNoticeProvider != "true") {
        $("#delipress__notice-provider").show()
    }
    if (delipress_cookieNoticeTracking != "true") {
        $("#delipress__notice-tracking").show()
    }

    // clipboard
    if ($(".delipress-copy").length > 0) {
        var clipboard = new Clipboard(".delipress-copy")

        clipboard.on('success', function(e) {
            let copied = $(e.trigger).attr('data-copied-text')
            $(e.trigger).removeClass('delipress__button--soft').addClass('delipress__button--save').html(copied)
        });
    }


    // Template select
    if ($(".delipress__template-list").length > 0) {
        const offsetLeftParent = $(".delipress__template-list").offset().left

        const offsetTopParent = $(".delipress__template-list").offset().top
    }

    const listChoiceItem = []
    $(".delipress__template-list__choice").each((i, e) => {
        listChoiceItem.push($(e).offset())
    })

    $(
        document
    ).on(
        "click",
        ".delipress__template-list:not(.delipress__template-list--step2) .delipress__template-list__choice:not(.js-delipress-choose-template):not(.js-template-list-choice-inactive)", // We listen for click for the template selection
        function(e) {
            e.preventDefault()
            const $this = $(this)

            if ($this.hasClass("js-template-list-choice-inactive")) {
                return false
            }

            anime({
                targets:
                    ".delipress__template-list__choice p:not(.delipress__template-list__choice__premium-incentive)",
                opacity: 0,
                duration: 200,
                delay: 100,
                elasticity: 100,
                complete: () => {
                    $(
                        ".delipress__template-list__choice p:not(.delipress__template-list__choice__premium-incentive)"
                    ).css("display", "none")
                }
            })

            anime({
                targets: ".delipress__template-list__choice h2",
                opacity: 0,
                duration: 200,
                fontSize: 14,
                lineHeight: 25,
                delay: 100,
                elasticity: 100,
                complete: el => {
                    $(
                        ".delipress__template-list__choice h2"
                    ).each((ind, el) => {
                        $(el).text($(el).data("title"))
                    })

                    anime({
                        targets: ".delipress__template-list__choice h2",
                        opacity: 1,
                        duration: 200,
                        delay: 100,
                        elasticity: 100
                    })
                }
            })

            anime({
                targets: ".delipress__template-list__choices",
                maxWidth: 260,
                margin: 0,
                duration: 1000,
                easing: "easeOutElastic",
                elasticity: 100
            })

            anime({
                targets: ".delipress__template-list__choice",
                marginTop: 10,
                marginBottom: 0,
                marginLeft: 0,
                marginRight: 0,
                padding: 10,
                height: 45,
                width: 240,
                begin: () => {
                    $(".delipress__template-list__choice").css({
                        textAlign: "left"
                    })
                },
                complete: () => {
                    $this.addClass("delipress--is-active")
                    $(`#${$this.data("tab")}`).addClass("delipress--is-active")
                    $(".delipress__template-list").addClass(
                        "delipress__template-list--step2"
                    )
                    anime({
                        targets: ".delipress__template-list__items",
                        opacity: 1,
                        duration: 400
                    })
                    $(document).trigger("template:selected")
                },
                duration: 1000,
                easing: "easeOutElastic",
                elasticity: 100
            })

            anime({
                targets: ".delipress__template-list__choice__picto",
                fontSize: "25px",
                duration: 600,
                marginTop: 0,
                marginBottom: 0,
                marginLeft: 0,
                marginRight: 10,
                easing: "easeOutElastic",
                begin: () => {
                    $(".delipress__template-list__choice__picto").css({
                        float: "left"
                    })
                },
                elasticity: 100
            })
        }
    )
    $(document).on("template:selected", () => {
        $(
            document
        ).on(
            "click",
            ".delipress__template-list--step2 .delipress__template-list__choice:not(.js-delipress-choose-template):not(.delipress--is-active):not(.js-template-list-choice-inactive)",
            function(e) {
                e.preventDefault()
                if ($(this).hasClass("js-template-list-choice-inactive")) {
                    return false
                }

                $(".delipress--is-active").removeClass("delipress--is-active")
                $(this).addClass("delipress--is-active")
                $(`#${$(this).data("tab")}`).addClass("delipress--is-active")
            }
        )
    })

    //  =========
    //  = Modal =
    //  =========

    $delipress.on("click", ".delipress-modal-trigger", function(e) {
        e.preventDefault()
        const $this = $(this)
        const modalId = $this.data("modalId")
        const $modalEl = $("#" + modalId)
        const iframeSrc = $this.data("iframeSrc")
        if (iframeSrc.length > 0) {
            $modalEl.find("iframe").attr("src", iframeSrc)
        }
        $modalEl.toggleClass("delipress__is-visible")
    })

    $delipress.on("click", ".delipress-modal-trigger-template-saved", function(e) {
        e.preventDefault()
        const $this = $(this)
        const modalId = $this.data("modalId")
        const $modalEl = $("#" + modalId)
        const templateId = $this.data("templateId")

        $modalEl.toggleClass("delipress__is-visible")

        document.dispatchEvent(createOnChangeTeampltePreview(templateId));
    })

    $delipress.on(
        "click",
        ".delipress__modal__close, .delipress__modal__overlay",
        function(e) {
            e.preventDefault()
            $(this)
                .parents(".delipress__modal")
                .toggleClass("delipress__is-visible")
        }
    )

    // =============
    // == Tooltip ==
    // =============
    const $tooltips = $(".delipress__tooltip")

    $.each($tooltips, (ind, $t) => {
        new Tooltip($t, {
            trigger: "click",
            template:
                '<div class="tooltip delipress__tooltip-container" role="tooltip"><div class="tooltip-arrow delipress__tooltip-arrow"></div><div class="delipress__tooltip-inner tooltip-inner"></div></div>',
            html: true
        })
    })

    // ===================
    // = Checkbox reveal =
    // ===================
    $delipress.on("click", ".delipress__checkbox__reveal", function(e) {
        const revealId = $(this).data("reveal")
        $("#" + revealId).toggleClass("delipress__is-visible")
    })
})(jQuery)
