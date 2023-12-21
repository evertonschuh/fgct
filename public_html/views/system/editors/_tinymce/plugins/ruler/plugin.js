/**
 * Copyright (c) Tiny Technologies, Inc. All rights reserved.
 * Licensed under the LGPL or a commercial license.
 * For LGPL see License.txt in the project root for license information.
 * For commercial licenses see https://www.tiny.cloud/
 *
 * Version: 5.6.2 (2020-12-08)
 */
(function () {
    'use strict';

    var global = tinymce.util.Tools.resolve('tinymce.PluginManager');
    
    function Plugin () {
      global.add('ruler',  function (editor) {
      if (editor.settings.ruler !== true) {
        return void 0;
      }
      const tinyEnv = window.tinymce.util.Tools.resolve("tinymce.Env");
    
      const FilterContent = {
        getPageBreakClass() {
          return RULER_PAGEBREAK_CLASS;
        },
        getPlaceholderHtml() {
          return (
            '<img src="' +
            tinyEnv.transparentSrc +
            '" class="' +
            this.getPageBreakClass() +
            '" data-mce-resize="false" data-mce-placeholder />'
          );
        }
      };
    
      const Settings = {
        getSeparatorHtml() {
          return editor.getParam("pagebreak_separator", "<!-- ruler-pagebreak -->"); // <!-- pagebreak -->
        },
        shouldSplitBlock() {
          return editor.getParam("pagebreak_split_block", false);
        }
      };
    
      const separatorHtml = Settings.getSeparatorHtml(editor);
      var pageBreakSeparatorRegExp = new RegExp(
        separatorHtml.replace(/[\?\.\*\[\]\(\)\{\}\+\^\$\:]/g, function(a) {
          return "\\" + a;
        }),
        "gi"
      );
      editor.on("BeforeSetContent", function(e) {
        e.content = e.content.replace(
          pageBreakSeparatorRegExp,
          FilterContent.getPlaceholderHtml()
        );
      });
      editor.on("PreInit", function() {
        editor.serializer.addNodeFilter("img", function(nodes) {
          var i = nodes.length,
            node,
            className;
          while (i--) {
            node = nodes[i];
            className = node.attr("class");
            if (
              className &&
              className.indexOf(FilterContent.getPageBreakClass()) !== -1
            ) {
              const parentNode = node.parent;
              if (
                editor.schema.getBlockElements()[parentNode.name] &&
                Settings.shouldSplitBlock(editor)
              ) {
                parentNode.type = 3;
                parentNode.value = separatorHtml;
                parentNode.raw = true;
                node.remove();
                continue;
              }
              node.type = 3;
              node.value = separatorHtml;
              node.raw = true;
            }
          }
        });
      });
    
      editor.on("ResolveName", function(e) {
        if (
          e.target.nodeName === "IMG" &&
          editor.dom.hasClass(e.target, FilterContent.getPageBreakClass())
        ) {
          e.name = "pagebreak";
        }
      });
    
      editor.addCommand("mceRulerPageBreak", function() {
        if (editor.settings.pagebreak_split_block) {
          editor.insertContent("<p>" + FilterContent.getPlaceholderHtml() + "</p>");
        } else {
          editor.insertContent(FilterContent.getPlaceholderHtml());
        }
      });
    
      editor.addCommand("mceRulerRecalculate", function() {
        const $document = editor.getDoc();
        const $breaks = $document.querySelectorAll(`.${RULER_PAGEBREAK_CLASS}`);
        for (let i = 0; i < $breaks.length; i++) {
          const $element = $breaks[i];
          const $parent = $element.parentElement;
          const offsetTop = $element.offsetTop;
          const top = HEIGHT * (i + 1);
          if (top >= offsetTop) {
            $parent.style.marginTop =
              ~~(top - (offsetTop - $parent.style.marginTop.replace("px", ""))) +
              "px";
          }
        }
      });
    
      editor.addShortcut(RULER_SHORTCUT, "", "mceRulerPageBreak");
    
      editor.on("init", e => {
        const $document = editor.getDoc();
        createStyle(STYLE_RULER, $document);
        const documentElement = $document.documentElement;
        const hasRuler = documentElement.classList.contains(CLASS_RULER);
    
        if (hasRuler === false) {
          documentElement.classList.add(CLASS_RULER);
        }
      });
    
      const recalculate = debounce(() => {
        editor.execCommand("mceRulerRecalculate");
      }, 100);
    
      editor.on("NodeChange", e => {
        recalculate();
      });
    
    
  

       // var headState = Cell(''), footState = Cell('');
        register(editor, headState);
        register$1(editor);
        setup(editor, headState, footState);
      });
    }

    Plugin();

}());
