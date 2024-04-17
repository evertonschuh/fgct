/**
 * Main
 */

'use strict';

let isRtl = window.Helpers.isRtl(), isDarkStyle = window.Helpers.isDarkStyle(), menu, animate, isHorizontalLayout = !1;
document.getElementById("layout-menu") && (isHorizontalLayout = document.getElementById("layout-menu").classList.contains("menu-horizontal")),
(function () {
  // Initialize menu



  document.querySelectorAll("#layout-menu").forEach(function(e) {
    menu = new Menu(e,{
        orientation: isHorizontalLayout ? "horizontal" : "vertical",
        closeChildren: !!isHorizontalLayout,
        showDropdownOnHover: localStorage.getItem("templateCustomizer-" + templateName + "--ShowDropdownOnHover") ? "true" === localStorage.getItem("templateCustomizer-" + templateName + "--ShowDropdownOnHover") : void 0 === window.templateCustomizer || window.templateCustomizer.settings.defaultShowDropdownOnHover
    }),
    window.Helpers.scrollToActive(animate = !1),
    window.Helpers.mainMenu = menu
});
document.querySelectorAll(".layout-menu-toggle").forEach(e=>{
    e.addEventListener("click", e=>{
        if (e.preventDefault(),
        window.Helpers.toggleCollapsed(),
        config.enableMenuLocalStorage && !window.Helpers.isSmallScreen())
            try {
                localStorage.setItem("templateCustomizer-" + templateName + "--LayoutCollapsed", String(window.Helpers.isCollapsed()));
                var t, o = document.querySelector(".template-customizer-layouts-options");
                o && (t = window.Helpers.isCollapsed() ? "collapsed" : "expanded",
                o.querySelector(`input[value="${t}"]`).click())
            } catch (e) {}
    }
    )
}
);


  if (document.getElementById("layout-menu")) {
    var zz = document.getElementById("layout-menu");
    var o = function() {
        Helpers.isSmallScreen() || document.querySelector(".layout-menu-toggle").classList.add("d-block")
    };
    let e = null;
    zz.onmouseenter = function() {
        e = Helpers.isSmallScreen() ? setTimeout(o, 0) : setTimeout(o, 300)
    }
    ,
    zz.onmouseleave = function() {
        document.querySelector(".layout-menu-toggle").classList.remove("d-block"),
        clearTimeout(e)
    }
  }

  let t = document.querySelector('.style-switcher-toggle');

  function s(n) {
      [].slice.call(document.querySelectorAll("[data-app-" + n + "-img]")).map(function(e) {
          var t = e.getAttribute("data-app-" + n + "-img");
          e.src = assetsPath + "img/" + t
      })
  }

  window.templateCustomizer ? (t && t.addEventListener("click", function() {
    window.Helpers.isLightStyle() ? window.templateCustomizer.setStyle("dark") : window.templateCustomizer.setStyle("light")
  }),

  window.Helpers.isLightStyle() ? (t && (t.querySelector("i").classList.add("bx-moon"),
  new bootstrap.Tooltip(t,{
      title: "Dark mode",
      fallbackPlacements: ["bottom"]
  })),
  s("light")) : (t && (t.querySelector("i").classList.add("bx-sun"),
  new bootstrap.Tooltip(t,{
      title: "Light mode",
      fallbackPlacements: ["bottom"]
  })),
  s("dark"))) : t.parentElement.remove(),
  "undefined" != typeof i18next && "undefined" != typeof i18NextHttpBackend && i18next.use(i18NextHttpBackend).init({
      lng: "en",
      debug: !1,
      fallbackLng: "en",
      backend: {
          loadPath: assetsPath + "json/locales/{{lng}}.json"
      },
      returnObjects: !0
  }).then(function(e) {
      i()
  });

  // Display in main menu when menu scrolls
  let menuInnerContainer = document.getElementsByClassName('menu-inner'),
    menuInnerShadow = document.getElementsByClassName('menu-inner-shadow')[0];
  if (menuInnerContainer.length > 0 && menuInnerShadow) {
    menuInnerContainer[0].addEventListener('ps-scroll-y', function () {
      if (this.querySelector('.ps__thumb-y').offsetTop) {
        menuInnerShadow.style.display = 'block';
      } else {
        menuInnerShadow.style.display = 'none';
      }
    });
  }


  
  // Init helpers & misc
  // --------------------

  // Init BS Tooltip
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Accordion active class
  const accordionActiveFunction = function (e) {
    if (e.type == 'show.bs.collapse' || e.type == 'show.bs.collapse') {
      e.target.closest('.accordion-item').classList.add('active');
    } else {
      e.target.closest('.accordion-item').classList.remove('active');
    }
  };

  const accordionTriggerList = [].slice.call(document.querySelectorAll('.accordion'));
  const accordionList = accordionTriggerList.map(function (accordionTriggerEl) {
    accordionTriggerEl.addEventListener('show.bs.collapse', accordionActiveFunction);
    accordionTriggerEl.addEventListener('hide.bs.collapse', accordionActiveFunction);
  });

  // Auto update layout based on screen size
  window.Helpers.setAutoUpdate(true);

  // Toggle Password Visibility
  window.Helpers.initPasswordToggle();

  // Speech To Text
  window.Helpers.initSpeechToText();

  // Manage menu expanded/collapsed with templateCustomizer & local storage
  //------------------------------------------------------------------

  // If current layout is horizontal OR current window screen is small (overlay menu) than return from here
  if (window.Helpers.isSmallScreen()) {
    return;
  }

  // If current layout is vertical and current window screen is > small

  // Auto update menu collapsed/expanded based on the themeConfig
  window.Helpers.setCollapsed(true, false);
})();
