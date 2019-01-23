export default {
    /**
     * @param  {string} Url to post the request
     * @param  {object} A js object to send
     * @param  {function} The callback function to execute, receive data (status, message etc…)
     */
    dJax(url, data, success) {
        var params =
            typeof data == "string"
                ? data
                : Object.keys(data)
                      .map(function(k) {
                          return (
                              encodeURIComponent(k) +
                              "=" +
                              encodeURIComponent(data[k])
                          )
                      })
                      .join("&")

        var xhr = window.XMLHttpRequest
            ? new XMLHttpRequest()
            : new ActiveXObject("Microsoft.XMLHTTP")
        xhr.open("POST", url)
        xhr.onreadystatechange = function() {
            if (xhr.readyState > 3 && xhr.status == 200) {
                success(xhr)
            }
        }
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest")
        xhr.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
        )
        xhr.send(params)
        return xhr
    },

    dStat(optinId) {
        if (this.dCookieRead(`optin-${optinId}`) != "true") {
            this.dJax(
                ajaxurl,
                {
                    action: "stat_optin",
                    optin_id: optinId
                },
                result => {
                    const res = JSON.parse(result.response)
                    if (res.success == true) {
                        this.dCookieCreate(`optin-${optinId}`, "true", {
                            number: 7,
                            range: "day"
                        })
                    }
                }
            )
        }
    },

    /**
     * Create a cookie
     * Takes 3 parameters :
     * Cookie name
     * Cookie value
     * Cookie expire date in days eg: 7
     *
     * Usage:
     * dCookieCreate('cookie-name', 'cookie-value', 7)
     */

    dCookieCreate(name, value, duration) {
        var expires
        name = "delipress-" + name
        if (duration.range != 0) {
            var date = new Date()
            // Get the good duration in ms
            let timeMs = duration.number
            switch (duration.range) {
                case "hour":
                    timeMs = timeMs * 60 * 60 * 1000
                    break
                case "day":
                    timeMs = timeMs * 24 * 60 * 60 * 1000
                    break
                case "week":
                    timeMs = timeMs * 7 * 24 * 60 * 60 * 1000
                    break
                case "month":
                    timeMs = timeMs * 30.436875 * 24 * 60 * 60 * 1000
                    break
                default:
                    timeMs = timeMs * 24 * 60 * 60 * 1000
            }
            date.setTime(date.getTime() + timeMs)
            expires = "; expires=" + date.toGMTString()
        } else {
            expires = ""
        }
        document.cookie = name + "=" + value + expires + "; path=/"
    },

    /**
     * Read a cookie
     * Takes 1 parameter - the cookie name
     *
     * Usage:
     * var cook = dCookieRead('cookie-name');
     */
    dCookieRead(name) {
        var nameEQ = "delipress-" + name + "="
        var ca = document.cookie.split(";")
        var result = undefined
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i]
            c = c.trim()
            if (c.indexOf(nameEQ) === 0) {
                result = c.substring(nameEQ.length, c.length)
            }
        }
        return result
    },

    /**
     * Delete a cookie
     * Takes 1 parameter - the cookie name
     *
     * Usage:
     * dCookieDelete('cookie-name'); // Return true
     */
    dCookieDelete(name) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`
    },

    /**
     * @param  {string} Name of the cookie optin
     * @param  {int} Hide the optin for X number of days
     * @return {[bool]} True if we hide the plugin
     *
     * This function can be use inside any constructor to check if we should render or not the optin
     */
    dOptinVisible(name, duration) {
        const value = duration.number + duration.range
        if (duration.range != 0) {
            let cook = this.dCookieRead(name)
            if (cook == value) {
                // Same visibility as before and cookie still present we hide the component
                return true
            }
        } else {
            // Visibility at each visit so we delete the cookie if present
            this.dCookieDelete(name)
        }
        return false
    },

    //  =====================
    //  = Helpers functions =
    //  =====================

    /**
     * Helper function to get css value of an element
     */

    dCss(el, property) {
        return window.getComputedStyle(el, null).getPropertyValue(property)
    },

    /**
     * Get integer value of a px property
     */
    dCssParsed(el, property) {
        let value = this.dCss(el, property)
        return parseInt(value.replace(/(\d+).+/g, "$1"), 10)
    },

    // /**
    //  * Small Throttle function for us
    //  */
    // dThrottle(fn, threshhold, scope) {
    //   threshhold || (threshhold = 250);
    //   var last,
    //       deferTimer;
    //   return function () {
    //     var context = scope || this;

    //     var now = +new Date,
    //         args = arguments;
    //     if (last && now < last + threshhold) {
    //       // hold on to it
    //       clearTimeout(deferTimer);
    //       deferTimer = setTimeout(function () {
    //         last = now;
    //         fn.apply(context, args);
    //       }, threshhold);
    //     } else {
    //       last = now;
    //       fn.apply(context, args);
    //     }
    //   };
    // }

    dExtend() {
        var extended = {}

        for (key in arguments) {
            var argument = arguments[key]
            for (prop in argument) {
                if (Object.prototype.hasOwnProperty.call(argument, prop)) {
                    extended[prop] = argument[prop]
                }
            }
        }

        return extended
    }
}
