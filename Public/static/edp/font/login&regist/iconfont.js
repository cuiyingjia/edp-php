;(function(window) {

  var svgSprite = '<svg>' +
    '' +
    '<symbol id="icon-bianji" viewBox="0 0 1000 1000">' +
    '' +
    '<path d="M939.3396 138.0774c-15.6231-15.6224-40.952-15.6224-56.573 0L486.7604 534.0669c-15.6231 15.6204-15.6231 40.9482 0 56.5706 15.6211 15.6204 40.95 15.6204 56.571 0l396.0081-395.9895C954.9606 179.0256 954.9606 153.6978 939.3396 138.0774zM792.6369 562.8504c-21.8901 0-39.6378 17.745-39.6378 39.6361v198.1816328529257c0 21.8912-17.7457 39.6361-39.6378 39.6361H198.06603000491688c-21.8921 0-39.6378-17.745-39.6378-39.6361V166.487121731466c0-21.8892 17.7457-39.6361 39.6378-39.6361h396.379902382085c21.8921 0 39.6378-17.745 39.6378-39.6361 0-21.8892-17.7457-39.6361-39.6378-39.6361H158.4282396395892c-43.7822 0-79.2756 35.4919-79.2756 79.2723v713.4542779995163c0 43.7803 35.4934 79.2743 79.2756 79.2743h594.5708529375316c43.7842 0 79.2756-35.4939 79.2756-79.2743V602.4865141434108C832.2747 580.5954 814.529 562.8504 792.6369 562.8504z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-user" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M510.919856 517.891174c114.010192 0 206.432854-92.438319 206.432854-206.443839 0-113.984031-92.421639-206.401884-206.432854-206.401884-114.009168 0-206.430808 92.417853-206.430808 206.401884C304.489048 425.452855 396.910688 517.891174 510.919856 517.891174zM549.161352 621.12435l-79.988983 0c-114.010192 0-405.533419 92.417853-405.533419 206.42235s92.421639 98.046037 206.430808 98.046037l481.70122 0c114.009168 0 206.430808 15.959483 206.430808-98.046037S663.171544 621.12435 549.161352 621.12435z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-mima" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M760.384 441.376l0-103.616c0 0-0.032-241.728-241.728-241.728-241.696 0-241.728 241.728-241.728 241.728l0 103.616L173.312 441.376l0 483.488L864 924.864 864 441.376 760.384 441.376zM553.184 708.064l0 113.184-69.056 0 0-113.184c-20.544-11.968-34.528-34.016-34.528-59.488 0-38.176 30.944-69.088 69.056-69.088 38.112 0 69.056 30.912 69.056 69.088C587.744 674.048 573.76 696.096 553.184 708.064M691.328 441.344 345.984 441.344l0-103.136c0.384-28.928 10.56-173.12 172.672-173.12s172.288 144.192 172.672 172.672L691.328 441.344z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '</svg>'
  var script = function() {
    var scripts = document.getElementsByTagName('script')
    return scripts[scripts.length - 1]
  }()
  var shouldInjectCss = script.getAttribute("data-injectcss")

  /**
   * document ready
   */
  var ready = function(fn) {
    if (document.addEventListener) {
      if (~["complete", "loaded", "interactive"].indexOf(document.readyState)) {
        setTimeout(fn, 0)
      } else {
        var loadFn = function() {
          document.removeEventListener("DOMContentLoaded", loadFn, false)
          fn()
        }
        document.addEventListener("DOMContentLoaded", loadFn, false)
      }
    } else if (document.attachEvent) {
      IEContentLoaded(window, fn)
    }

    function IEContentLoaded(w, fn) {
      var d = w.document,
        done = false,
        // only fire once
        init = function() {
          if (!done) {
            done = true
            fn()
          }
        }
        // polling for no errors
      var polling = function() {
        try {
          // throws errors until after ondocumentready
          d.documentElement.doScroll('left')
        } catch (e) {
          setTimeout(polling, 50)
          return
        }
        // no errors, fire

        init()
      };

      polling()
        // trying to always fire before onload
      d.onreadystatechange = function() {
        if (d.readyState == 'complete') {
          d.onreadystatechange = null
          init()
        }
      }
    }
  }

  /**
   * Insert el before target
   *
   * @param {Element} el
   * @param {Element} target
   */

  var before = function(el, target) {
    target.parentNode.insertBefore(el, target)
  }

  /**
   * Prepend el to target
   *
   * @param {Element} el
   * @param {Element} target
   */

  var prepend = function(el, target) {
    if (target.firstChild) {
      before(el, target.firstChild)
    } else {
      target.appendChild(el)
    }
  }

  function appendSvg() {
    var div, svg

    div = document.createElement('div')
    div.innerHTML = svgSprite
    svgSprite = null
    svg = div.getElementsByTagName('svg')[0]
    if (svg) {
      svg.setAttribute('aria-hidden', 'true')
      svg.style.position = 'absolute'
      svg.style.width = 0
      svg.style.height = 0
      svg.style.overflow = 'hidden'
      prepend(svg, document.body)
    }
  }

  if (shouldInjectCss && !window.__iconfont__svg__cssinject__) {
    window.__iconfont__svg__cssinject__ = true
    try {
      document.write("<style>.svgfont {display: inline-block;width: 1em;height: 1em;fill: currentColor;vertical-align: -0.1em;font-size:16px;}</style>");
    } catch (e) {
      console && console.log(e)
    }
  }

  ready(appendSvg)


})(window)